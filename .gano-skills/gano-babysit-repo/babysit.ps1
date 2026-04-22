#requires -version 5.0
<#
.SYNOPSIS
Gano Digital Repo Babysitter v1.0
Validaciones: git status, PHP lint, archivos servidor, HTTP 200, error logs, divergencias
#>

param()

$ErrorActionPreference = "Continue"
$WarningPreference = "SilentlyContinue"

$timestamp = (Get-Date).ToUniversalTime().ToString("yyyy-MM-ddTHH:mm:ssZ")
$sshKey = "$env:USERPROFILE\.ssh\id_rsa_deploy"
$sshHost = "f1rml03th382@72.167.102.145"
$serverPath = "/home/f1rml03th382/public_html/gano.digital"
$siteUrl = "https://gano.digital"

# Estado para divergencias
$divergences = @()
$gitCheckStatus = ""
$phpCheckStatus = ""
$serverStatus = ""
$homepageStatus = ""
$errorLogStatus = ""

Write-Host "[$(Get-Date)] [BABYSIT] Iniciando validaciones..." -ForegroundColor Cyan

# 1. GIT STATUS
Write-Host "[$(Get-Date)] [GIT] Verificando cambios..." -ForegroundColor Cyan
try {
    $gitStatus = & git status --porcelain 2>&1 | Where-Object { $_ }
    $gitBranch = & git branch --show-current 2>&1 | Select-Object -First 1
    $gitCommitsAhead = @(& git rev-list --left-only --count origin/main...HEAD 2>&1) -join ""

    if ($null -eq $gitStatus -or $gitStatus.Count -eq 0) {
        $gitCheckStatus = "[OK] limpio"
        $gitPending = "0"
    } else {
        $gitCheckStatus = "[WARN] cambios pendientes"
        $gitPending = $gitStatus.Count
        $divergences += "cambios_locales_sin_push"
    }
} catch {
    $gitCheckStatus = "[ERROR] error git"
    $gitBranch = "ERROR"
    $gitCommitsAhead = "0"
    $gitPending = "unknown"
    $divergences += "git_error"
}

# 2. PHP LINT
Write-Host "[$(Get-Date)] [PHP] Validando sintaxis..." -ForegroundColor Cyan
$phpFunctions = "[ERROR]"
$phpFrontpage = "[ERROR]"
$phpCheckStatus = "[ERROR] ERRORES DETECTADOS"

try {
    $resultFunctions = & php -l "wp-content\themes\gano-child\functions.php" 2>&1
    if ($resultFunctions -match "No syntax errors") {
        $phpFunctions = "[OK]"
    } else {
        $divergences += "php_functions_error"
    }
} catch {
    $divergences += "php_functions_error"
}

try {
    $resultFrontpage = & php -l "wp-content\themes\gano-child\front-page.php" 2>&1
    if ($resultFrontpage -match "No syntax errors") {
        $phpFrontpage = "[OK]"
    } else {
        $divergences += "php_frontpage_error"
    }
} catch {
    $divergences += "php_frontpage_error"
}

if ($phpFunctions -eq "[OK]" -and $phpFrontpage -eq "[OK]") {
    $phpCheckStatus = "[OK]"
}

# 3. SERVIDOR - Archivos actualizados
Write-Host "[$(Get-Date)] [SSH] Verificando servidor..." -ForegroundColor Cyan
$serverStatus = "[ERROR] SSH no responde"
$filesSynced = "unknown"
$localMtime = "unknown"
$serverMtime = "unknown"

try {
    $localFile = Get-Item "wp-content\themes\gano-child\functions.php" -ErrorAction Stop
    $localMtime = $localFile.LastWriteTime.ToString("yyyy-MM-dd HH:mm")

    $sshCheck = & ssh -i $sshKey -o ConnectTimeout=5 $sshHost "stat -c '%y' $serverPath/wp-content/themes/gano-child/functions.php 2>/dev/null | cut -d' ' -f1-2" 2>&1

    if ($LASTEXITCODE -eq 0 -and $sshCheck) {
        $serverMtime = $sshCheck
        $serverStatus = "[OK] Conectado"

        if ($localMtime -eq $serverMtime) {
            $serverStatus = "[OK] Sincronizado"
            $filesSynced = "true"
        } else {
            $serverStatus = "[WARN] Desincronizado"
            $filesSynced = "false"
            $divergences += "archivos_desincronizados"
        }
    } else {
        $serverStatus = "[ERROR] SSH fallo"
        $divergences += "servidor_inaccesible"
    }
} catch {
    $serverStatus = "[ERROR] Excepcion SSH"
    $divergences += "servidor_inaccesible"
}

# 4. HTTP 200 OK
Write-Host "[$(Get-Date)] [HTTP] Verificando sitio live..." -ForegroundColor Cyan
$httpCode = "000"
try {
    $response = Invoke-WebRequest -Uri "$siteUrl/" -UseBasicParsing -TimeoutSec 10 -ErrorAction Stop
    $httpCode = $response.StatusCode
} catch {
    $httpCode = "ERR"
}

if ($httpCode -eq "200" -or $httpCode -eq 200) {
    $homepageStatus = "[OK] HTTP 200"
} else {
    $homepageStatus = "[ERROR] HTTP $httpCode"
    $divergences += "sitio_no_responde_200"
}

# 5. ERROR LOG del servidor
Write-Host "[$(Get-Date)] [LOG] Buscando errores..." -ForegroundColor Cyan
$errorsFound = "0"

try {
    $errorLogResult = & ssh -i $sshKey -o ConnectTimeout=5 $sshHost "tail -50 $serverPath/wp-content/debug.log 2>/dev/null | grep -i 'fatal\|error\|warning' | wc -l" 2>&1
    $errorsFound = if ([int]::TryParse($errorLogResult, [ref]$null)) { $errorLogResult } else { "0" }

    if ([int]$errorsFound -eq 0) {
        $errorLogStatus = "[OK] Sin errores"
    } else {
        $errorLogStatus = "[WARN] $errorsFound alertas"
        $divergences += "error_log_con_alertas"
    }
} catch {
    $errorLogStatus = "[WARN] No se pudo verificar"
    $errorsFound = "unknown"
}

# Generar estado de divergencias
if ($divergences.Count -eq 0) {
    $divergenceStatus = "[OK] LIMPIO"
} else {
    $divergenceStatus = "[WARN] $($divergences.Count) DIVERGENCIAS"
}

# RETORNAR JSON
$jsonOutput = @{
    timestamp = $timestamp
    status = "OK"
    summary = @{
        git = $gitCheckStatus
        php = $phpCheckStatus
        server = $serverStatus
        homepage = $homepageStatus
        error_log = $errorLogStatus
        divergences = $divergenceStatus
    }
    details = @{
        git = @{
            branch = $gitBranch
            pending_changes = $gitPending
            commits_ahead_main = $gitCommitsAhead
        }
        php = @{
            functions_php = $phpFunctions
            front_page_php = $phpFrontpage
        }
        server = @{
            ssh_status = if ($serverStatus -like "*ERROR*") { "unreachable" } else { "connected" }
            files_synced = $filesSynced
            local_mtime = $localMtime
            server_mtime = $serverMtime
        }
        homepage = @{
            url = $siteUrl
            http_code = $httpCode
        }
        error_log = @{
            recent_errors = $errorsFound
        }
    }
    divergences = $divergences
}

$jsonOutput | ConvertTo-Json | Write-Output

# ALERT si hay divergencias
if ($divergences.Count -gt 0) {
    Write-Host ""
    Write-Host "[ALERT] DIVERGENCIAS DETECTADAS:" -ForegroundColor Yellow
    foreach ($div in $divergences) {
        Write-Host "  - $div" -ForegroundColor Yellow
    }
    exit 1
} else {
    Write-Host "[OK] Todo sincronizado" -ForegroundColor Green
    exit 0
}
