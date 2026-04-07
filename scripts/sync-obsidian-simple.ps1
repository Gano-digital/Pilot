#!/usr/bin/env powershell
# Gano Digital - Sincronizador Obsidian Simple

$apiKey = $env:OBSIDIAN_API_KEY
if (-not $apiKey) {
    Write-Error "API key no configurada. Define la variable de entorno OBSIDIAN_API_KEY."
    exit 1
}
$apiUrl = "https://localhost:27124"

# Validar que el host sea localhost antes de omitir verificación TLS
$uri = [System.Uri]$apiUrl
if ($uri.Host -notin @("localhost","127.0.0.1","::1")) {
    Write-Error "apiUrl apunta a un host no-local ($($uri.Host)). No se puede omitir verificación TLS."
    exit 1
}
$skipTls = @{ SkipCertificateCheck = $true }

Write-Host "🌌 Gano Digital - Obsidian Synchronizer" -ForegroundColor Magenta
Write-Host "======================================" -ForegroundColor Magenta
Write-Host ""

# Probar conexion
Write-Host "Testing connection..." -ForegroundColor Cyan

$headers = @{
    "Authorization" = "Bearer $apiKey"
}

try {
    $response = Invoke-RestMethod -Uri "$apiUrl/vault/" -Headers $headers -SkipCertificateCheck
    Write-Host "Success! Connected to Obsidian." -ForegroundColor Green
    Write-Host ""
    Write-Host "Files in vault:" -ForegroundColor Yellow
    $response.files | ForEach-Object { Write-Host "  - $_" }
    Write-Host ""
} catch {
    Write-Host "Error: Could not connect to Obsidian Local REST API" -ForegroundColor Red
    Write-Host "Make sure:" -ForegroundColor Yellow
    Write-Host "  1. Obsidian is open"
    Write-Host "  2. Local REST API plugin is installed and enabled"
    Write-Host "  3. HTTPS server is running on port 27124"
    exit 1
}

# Crear archivo de status
$statusContent = @"
# Status - Obsidian Sync Active

**Last sync**: $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss') UTC
**System**: Online
**Connection**: Connected

## Phase 4 Metrics
- Completion: 15%
- PFID Mappings: 0/5
- Blockers: 1
- Go-Live: Apr 20, 2026

---
Synced via Obsidian Local REST API
"@

Write-Host "Writing status file..." -ForegroundColor Cyan

$body = @{
    path = "memory/constellation/STATUS-LIVE.md"
    content = $statusContent
    overwrite = $true
} | ConvertTo-Json

try {
    Invoke-RestMethod -Uri "$apiUrl/vault/create" `
        -Method POST `
        -Headers $headers `
        -Body $body `
        -SkipCertificateCheck | Out-Null

    Write-Host "Success! Status file updated." -ForegroundColor Green
} catch {
    Write-Host "Error writing file: $_" -ForegroundColor Red
}

Write-Host ""
Write-Host "Done!" -ForegroundColor Green
