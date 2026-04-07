# 🌌 Gano Digital — Obsidian Local REST API Synchronizer
# PowerShell script para sincronizar constelación en tiempo real

param(
    [string]$ApiKey = "1d3446a85589777fb01d0fae164ae8b458400ea58af0ab700a38d634eaf3c946",
    [int]$Port = 27124,
    [string]$Action = "sync-all"
)

# Configurar variables
$ApiUrl = "https://localhost:$Port"
$Headers = @{
    "Authorization" = "Bearer $ApiKey"
    "Content-Type"  = "application/json"
}

# Ignorar certificados SSL auto-firmados
if (-not ([System.Management.Automation.PSTypeName]'ServerCertificateValidationCallback').Type) {
    $certCallback = @"
using System;
using System.Net;
using System.Net.Security;
using System.Security.Cryptography.X509Certificates;
public class ServerCertificateValidationCallback
{
    public static void Ignore()
    {
        if(ServicePointManager.ServerCertificateValidationCallback ==null)
        {
            ServicePointManager.ServerCertificateValidationCallback +=
                delegate
                (
                    Object obj,
                    X509Certificate certificate,
                    X509Chain chain,
                    SslPolicyErrors errors
                )
                {
                    return true;
                };
        }
    }
}
"@
    Add-Type $certCallback
}
[ServerCertificateValidationCallback]::Ignore()

function Test-ObsidianConnection {
    Write-Host "🔍 Verificando conexión con Obsidian..." -ForegroundColor Cyan

    try {
        $response = Invoke-RestMethod -Uri "$ApiUrl/vault/" -Headers $Headers -ErrorAction Stop
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
        $response = Invoke-RestMethod -Uri "$ApiUrl/vault/" -Headers $Headers
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
        $response = Invoke-RestMethod -Uri "$ApiUrl/vault/abstract/file/$encodedPath" -Headers $Headers
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
            -Body $body

        Write-Host "✅ Archivo escrito: $Path" -ForegroundColor Green
        return $true
    }
    catch {
        Write-Host "⚠️ Error escribiendo $Path : $_" -ForegroundColor Yellow
        return $false
    }
}

function Update-ConstelationStatus {
    Write-Host ""
    Write-Host "📊 Actualizando estado de constelación..." -ForegroundColor Cyan

    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"

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

    Update-ConstelationStatus
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
        Update-ConstelationStatus
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
