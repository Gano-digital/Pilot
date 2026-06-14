#Requires -Version 5.1
<#
.SYNOPSIS
    Deploy local de Gano Digital a servidor GoDaddy via SSH/SCP.
.DESCRIPTION
    Sincroniza los paths configurados en .deploy-config.json al servidor,
    hace backup previo, verifica sintaxis PHP, y limpia cache.
.NOTES
    Uso: .\scripts\deploy-local.ps1 [-DryRun] [-SkipBackup] [-SkipVerify]
    Requiere: OpenSSH (scp, ssh), PowerShell 5.1+
#>
[CmdletBinding()]
param(
    [switch]$DryRun,
    [switch]$SkipBackup,
    [switch]$SkipVerify
)

$ErrorActionPreference = 'Stop'

function Invoke-RemoteCmd {
    param([string]$Cmd)
    $fullCmd = "ssh -o IdentitiesOnly=yes -i `"$SshKey`" `"$User@$Server`" `"$Cmd`""
    $result = cmd /c "$fullCmd 2>nul"
    return $result
}
$ConfigPath = Join-Path (Split-Path -Parent $PSScriptRoot) '.deploy-config.json'
$RepoRoot   = Resolve-Path (Split-Path -Parent $PSScriptRoot)

# ── 1. CARGAR CONFIGURACION ──────────────────────────────────────────────────
if (-not (Test-Path $ConfigPath)) {
    throw "No se encontro .deploy-config.json en $ConfigPath"
}
$Config = Get-Content $ConfigPath -Raw | ConvertFrom-Json

$Server    = $Config.server.host
$User      = $Config.server.user
$SshKey    = $Config.server.sshKey -replace '/','\'
$Docroot   = $Config.server.docroot
$BackupDir = "$Docroot/$($Config.server.backupDir)"

# ── 2. VALIDAR PREREQUISITOS ─────────────────────────────────────────────────
Write-Host "=== Gano Digital - Local Deploy Pipeline ===" -ForegroundColor Cyan
Write-Host "Servidor: $User@$Server"
Write-Host "Docroot:  $Docroot"
Write-Host ""

if (-not (Test-Path $SshKey)) {
    throw "SSH key no encontrada: $SshKey"
}

$scp = Get-Command scp -ErrorAction SilentlyContinue
$ssh = Get-Command ssh -ErrorAction SilentlyContinue
if (-not $scp -or -not $ssh) {
    throw "OpenSSH no esta en PATH."
}

# ── 3. VERIFICAR CONEXION ────────────────────────────────────────────────────
Write-Host "Verificando conexion SSH... " -NoNewline
$sshTest = Invoke-RemoteCmd "echo OK" | Where-Object { $_ -eq 'OK' }
if ($sshTest -ne 'OK') {
    Write-Host "FAIL" -ForegroundColor Red
    throw "No se pudo conectar a $Server"
}
Write-Host "OK" -ForegroundColor Green

# ── 4. BACKUP PRE-DEPLOY ─────────────────────────────────────────────────────
$Timestamp = Get-Date -Format 'yyyyMMdd-HHmmss'
if (-not $SkipBackup -and -not $DryRun) {
    Write-Host "Creando backup en servidor ($Timestamp)... " -NoNewline
    $bkCmd = "mkdir -p $BackupDir/$Timestamp && cp -r $Docroot/wp-content/themes/gano-child $BackupDir/$Timestamp/ 2>/dev/null; cp -r $Docroot/wp-content/mu-plugins $BackupDir/$Timestamp/ 2>/dev/null; echo BACKUP_OK"
    $bkResult = Invoke-RemoteCmd $bkCmd
    if ($bkResult -match 'BACKUP_OK') {
        Write-Host "OK" -ForegroundColor Green
    } else {
        Write-Host "WARN" -ForegroundColor Yellow
    }
}

# ── 5. DEPLOY PATHS ──────────────────────────────────────────────────────────
$DeployPaths = $Config.deploy.paths
$Results = @()
$PhpFiles = @()

foreach ($relPath in $DeployPaths) {
    $localPath = Join-Path $RepoRoot $relPath
    if (-not (Test-Path $localPath)) {
        Write-Warning "Path local no existe, omitiendo: $localPath"
        continue
    }

    $remotePath = "$Docroot/$($relPath -replace '\\','/')"
    $remoteParent = $remotePath.TrimEnd('/')
    if ($remotePath.EndsWith('/')) {
        $remoteParent = Split-Path $remotePath -Parent
    }

    Write-Host "Deploying: $relPath " -NoNewline

    if ($DryRun) {
        Write-Host "[DRY-RUN]" -ForegroundColor Yellow
        $Results += New-Object PSObject -Property @{ Path = $relPath; Status = 'DRY-RUN' }
        continue
    }

    # Crear directorio remoto
    Invoke-RemoteCmd "mkdir -p $remoteParent" | Out-Null

    # SCP recursivo
    & scp -r -O -o IdentitiesOnly=yes -i "$SshKey" "$localPath" "${User}@${Server}:${remoteParent}/" 2>$null
    $exitCode = $LASTEXITCODE

    if ($exitCode -eq 0) {
        Write-Host "OK" -ForegroundColor Green
        $Results += New-Object PSObject -Property @{ Path = $relPath; Status = 'OK' }
        $PhpFiles += Get-ChildItem -Path $localPath -Recurse -Filter '*.php' | Select-Object -ExpandProperty FullName
    } else {
        Write-Host "FAIL (exit $exitCode)" -ForegroundColor Red
        $Results += New-Object PSObject -Property @{ Path = $relPath; Status = 'FAIL' }
    }
}

# ── 6. VERIFICAR SINTAXIS PHP ────────────────────────────────────────────────
if (-not $SkipVerify -and -not $DryRun -and $PhpFiles.Count -gt 0) {
    $hasOk = $false
    foreach ($r in $Results) { if ($r.Status -eq 'OK') { $hasOk = $true; break } }

    if ($hasOk) {
        Write-Host "Verificando sintaxis PHP en servidor... " -NoNewline
        $phpPaths = ($PhpFiles | ForEach-Object {
            $rel = $_.Substring($RepoRoot.Path.Length).TrimStart('\','/')
            "$Docroot/$($rel -replace '\\','/')"
        }) -join ' '

        $phpCmd = "for f in $phpPaths; do php -l `"""$""f"""" 2>&1; done | grep -i 'parse error\\|fatal error' || echo ALL_OK"
        $phpResult = Invoke-RemoteCmd $phpCmd

        if ($phpResult -match 'ALL_OK') {
            Write-Host "OK" -ForegroundColor Green
        } else {
            Write-Host "ERRORS" -ForegroundColor Red
            Write-Warning $phpResult
        }
    }
}

# ── 7. POST-DEPLOY HOOKS ─────────────────────────────────────────────────────
if (-not $DryRun) {
    foreach ($hook in $Config.hooks.postDeploy) {
        Write-Host "Hook: $hook " -NoNewline
        $hookResult = Invoke-RemoteCmd "cd $Docroot && $hook"
        if ($LASTEXITCODE -eq 0) {
            Write-Host "OK" -ForegroundColor Green
        } else {
            Write-Host "WARN" -ForegroundColor Yellow
        }
    }
}

# ── 8. REPORTE ───────────────────────────────────────────────────────────────
Write-Host ""
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  DEPLOY REPORT - $Timestamp" -ForegroundColor Cyan
Write-Host "============================================================" -ForegroundColor Cyan

$okCount   = 0
$failCount = 0
$dryCount  = 0
foreach ($r in $Results) {
    if ($r.Status -eq 'OK') { $okCount++ }
    elseif ($r.Status -eq 'FAIL') { $failCount++ }
    elseif ($r.Status -eq 'DRY-RUN') { $dryCount++ }
}

Write-Host "  OK:      $okCount" -ForegroundColor Green
Write-Host "  Failed:  $failCount" -ForegroundColor $(if($failCount -gt 0){'Red'}else{'Green'})
Write-Host "  Dry-run: $dryCount" -ForegroundColor Yellow
Write-Host "  Backup:  $BackupDir/$Timestamp" -ForegroundColor Gray

if ($failCount -gt 0) {
    Write-Host ""
    Write-Host "  FAILED PATHS:" -ForegroundColor Red
    foreach ($r in $Results) {
        if ($r.Status -eq 'FAIL') {
            Write-Host "    - $($r.Path)" -ForegroundColor Red
        }
    }
    exit 1
}

Write-Host ""
Write-Host "  [OK] Deploy completado." -ForegroundColor Green
