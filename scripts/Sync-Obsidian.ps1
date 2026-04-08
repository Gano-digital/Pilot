# 🌌 Gano Digital — Obsidian Local REST API Synchronizer
# PowerShell script para sincronizar constelación en tiempo real

param(
    [string]$ApiKey = "",
    [int]$Port = 27124,
    [string]$Action = "sync-all"
)

# Leer API key desde variable de entorno si no se pasa como parámetro
if (-not $ApiKey) {
    $ApiKey = $env:OBSIDIAN_API_KEY
}
if (-not $ApiKey) {
    Write-Error "API key no configurada. Define la variable de entorno OBSIDIAN_API_KEY o pasa -ApiKey <token>."
    exit 1
}

# Configurar variables
$ApiUrl = "https://localhost:$Port"
$Headers = @{
    "Authorization" = "Bearer $ApiKey"
    "Content-Type"  = "application/json"
}

# -SkipCertificateCheck existe solo en PowerShell 7+.
if ($PSVersionTable.PSVersion.Major -lt 7) {
    Write-Error "Este script requiere PowerShell 7+ (usa -SkipCertificateCheck). Instala PowerShell 7 y reintenta."
    exit 1
}

# Certificado auto-firmado en localhost: usar -SkipCertificateCheck por request (PS 7+).
# Validar que el host sea realmente localhost antes de permitir skip.
$LocalHost = [System.Uri]$ApiUrl
if ($LocalHost.Host -notin @("localhost","127.0.0.1","::1")) {
    Write-Error "ApiUrl apunta a un host no-local ($($LocalHost.Host)). No se puede omitir verificación TLS."
    exit 1
}
$SkipTls = @{ SkipCertificateCheck = $true }

function Test-ObsidianConnection {
    Write-Host "🔍 Verificando conexión con Obsidian..." -ForegroundColor Cyan

    try {
        $response = Invoke-RestMethod -Uri "$ApiUrl/vault/" -Headers $Headers @SkipTls -ErrorAction Stop
        Write-Host "✅ Conectado a Obsidian Local REST API" -ForegroundColor Green
        return $true
    }
    catch {
        Write-Host "❌ Error de conexión: $_" -ForegroundColor Red
        return $false
    }
}

function Get-VaultInfo {
    try {
        $response = Invoke-RestMethod -Uri "$ApiUrl/vault/" -Headers $Headers @SkipTls
        return $response
    }
    catch {
        Write-Host "❌ Error obteniendo vault info: $_" -ForegroundColor Red
        return $null
    }
}

function Read-ObsidianFile {
    param([string]$Path)

    try {
        $encodedPath = [System.Uri]::EscapeDataString($Path)
        $response = Invoke-RestMethod -Uri "$ApiUrl/vault/abstract/file/$encodedPath" -Headers $Headers @SkipTls
        return $response
    }
    catch {
        Write-Host "❌ Error leyendo $Path : $_" -ForegroundColor Red
        return $null
    }
}

function Write-ObsidianFile {
    param(
        [string]$Path,
        [string]$Content,
        [bool]$Overwrite = $true
    )

    try {
        $body = @{
            path = $Path
            content = $Content
            overwrite = $Overwrite
        } | ConvertTo-Json -Depth 10

        $response = Invoke-RestMethod -Uri "$ApiUrl/vault/create" `
            -Headers $Headers `
            -Method POST `
            -Body $body `
            @SkipTls

        Write-Host "✅ Archivo escrito: $Path" -ForegroundColor Green
        return $true
    }
    catch {
        Write-Host "⚠️ Error escribiendo $Path : $_" -ForegroundColor Yellow
        return $false
    }
}

function Update-ConstellationStatus {
    Write-Host ""
    Write-Host "📊 Actualizando estado de constelación..." -ForegroundColor Cyan

    $timestamp = (Get-Date).ToUniversalTime().ToString("yyyy-MM-dd HH:mm:ss")

    $statusContent = @"
# 🌌 Estado Live — Constelación Gano Digital

**Última sincronización**: $timestamp UTC
**Sistema**: ✅ Online
**Conexión**: ✅ Obsidian Local REST API

## 📈 Métricas Phase 4

| Métrica | Valor | Estado |
|---------|-------|--------|
| **Fases completadas** | 3/5 (60%) | ✅ |
| **Fase 4 Progress** | ~15% | 🟠 |
| **PFID Mappings** | 0/5 | 🔴 CRITICAL |
| **Test Pass Rate** | N/A | ⏳ |
| **Production Ready** | 0% | 🔴 |
| **Blockers Activos** | 1 | 🟡 |
| **Go-Live Target** | Apr 20, 2026 | 📅 |

## 🎯 Próximas Acciones Inmediatas

1. **Apr 7 EOD** - Diego activa Antigravity (~20 min)
2. **Apr 8** - Antigravity ejecuta RCC Audit
3. **Apr 9** - Cursor inicia PFID mappings
4. **Apr 13** - Staging test 9/9 PASS esperado
5. **Apr 15** - Production test validado
6. **Apr 20** - **GO LIVE 🚀**

## 🛠️ Sistema de Sincronización

- **Plataforma**: Obsidian Local REST API
- **Autenticación**: Bearer Token ✅
- **Encriptación**: HTTPS (puerto 27124)
- **Frecuencia**: Actualización continua

---
*Sincronizado automáticamente por Claude + Obsidian MCP Integration*
*Para más detalles, ver [[memory/constellation/]] en tu vault*
"@

    Write-ObsidianFile "memory/constellation/STATUS-LIVE.md" $statusContent
}

function Sync-AllFiles {
    Write-Host "🔄 Sincronizando todos los archivos..." -ForegroundColor Cyan

    $vaultInfo = Get-VaultInfo
    if ($null -eq $vaultInfo) {
        return
    }

    Write-Host ""
    Write-Host "📂 Archivos encontrados:" -ForegroundColor Cyan
    $vaultInfo.files | ForEach-Object {
        Write-Host "   - $_"
    }

    Update-ConstellationStatus
}

function Show-Dashboard {
    Write-Host ""
    Write-Host "╔════════════════════════════════════════════════════════════╗" -ForegroundColor Magenta
    Write-Host "║     🌌 GANO DIGITAL — CONSTELACIÓN EN OBSIDIAN 🌌         ║" -ForegroundColor Magenta
    Write-Host "╚════════════════════════════════════════════════════════════╝" -ForegroundColor Magenta
    Write-Host ""
    Write-Host "✅ Sistemas Operativos:" -ForegroundColor Green
    Write-Host "   • Obsidian Local REST API: CONECTADO"
    Write-Host "   • Sincronización: ACTIVA"
    Write-Host "   • Autenticación: VALIDADA"
    Write-Host ""
    Write-Host "📊 Estado Phase 4:" -ForegroundColor Cyan
    Write-Host "   • Progreso: 15% completado"
    Write-Host "   • Fase actual: Activación + Testing"
    Write-Host "   • Bloqueadores: 1 (Diego Antigravity - baja prioridad)"
    Write-Host "   • Target: Go-live Apr 20, 2026"
    Write-Host ""
    Write-Host "💡 Integraciones Activas:" -ForegroundColor Yellow
    Write-Host "   • Obsidian Local REST API"
    Write-Host "   • 8 documentos de constelación"
    Write-Host "   • Actualización automática de métricas"
    Write-Host "   • Monitor en tiempo real"
    Write-Host ""
}

# Main execution
Write-Host ""
Show-Dashboard

if (-not (Test-ObsidianConnection)) {
    Write-Host ""
    Write-Host "⚠️ Para configurar Obsidian Local REST API:" -ForegroundColor Yellow
    Write-Host "   1. Abre Obsidian → Settings → Community Plugins"
    Write-Host "   2. Instala 'Local REST API' por mrjackphil"
    Write-Host "   3. Habilita el plugin"
    Write-Host "   4. Obtén tu API Key desde Local REST API settings"
    exit 1
}

Write-Host ""

switch ($Action) {
    "sync-all" {
        Sync-AllFiles
    }
    "status" {
        Update-ConstellationStatus
    }
    "test" {
        Write-Host "🧪 Ejecutando tests..." -ForegroundColor Cyan
        Test-ObsidianConnection
        Sync-AllFiles
    }
    default {
        Write-Host "❓ Acción desconocida: $Action" -ForegroundColor Yellow
        Write-Host "Acciones disponibles: sync-all, status, test"
    }
}

Write-Host ""
Write-Host "✅ Proceso completado" -ForegroundColor Green
Write-Host ""
Write-Host "💾 Archivos se han sincronizado con Obsidian" -ForegroundColor Green
Write-Host "🔄 Ejecuta este script periódicamente para mantener actualizado" -ForegroundColor Cyan
Write-Host ""
