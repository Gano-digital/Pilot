<#
.SYNOPSIS
  Copia archivos permitidos del repo Pilot al Gano-Wiki (snapshots de memoria).

.PARAMETER WikiRoot
  Ruta absoluta a Gano-Wiki (por defecto OneDrive del usuario actual).

.PARAMETER WhatIf
  Si está presente, solo muestra qué copiaría.

.EXAMPLE
  .\scripts\sync_wiki.ps1 -WhatIf
  .\scripts\sync_wiki.ps1 -WikiRoot "D:\Gano-Wiki"
#>
[CmdletBinding(SupportsShouldProcess = $true)]
param(
  [string]$WikiRoot = 'C:\Users\diego\OneDrive\Documentos\Gano-Wiki'
)

$ErrorActionPreference = 'Stop'
$RepoRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
$DestDir = Join-Path $WikiRoot 'ai-agents\memory-snapshots'
if (-not (Test-Path -LiteralPath $WikiRoot)) {
  Write-Error "WikiRoot no existe: $WikiRoot"
}

$stamp = Get-Date -Format 'yyyy-MM-dd'
$map = @(
  @{ Src = Join-Path $RepoRoot '.cursor\memory\activeContext.md';   Dest = "active-context-$stamp.md" }
  @{ Src = Join-Path $RepoRoot '.cursor\memory\techContext.md';     Dest = "tech-context-$stamp.md" }
  @{ Src = Join-Path $RepoRoot '.cursor\memory\progress.md';        Dest = "progress-$stamp.md" }
  @{ Src = Join-Path $RepoRoot '.cursor\memory\deferredItems.md';    Dest = "deferred-items-$stamp.md" }
)

New-Item -ItemType Directory -Force -Path $DestDir | Out-Null

foreach ($m in $map) {
  if (-not (Test-Path -LiteralPath $m.Src)) {
    Write-Warning "Origen ausente: $($m.Src)"
    continue
  }
  $out = Join-Path $DestDir $m.Dest
  if ($PSCmdlet.ShouldProcess($m.Src, "Copy to $out")) {
    Copy-Item -LiteralPath $m.Src -Destination $out -Force
    Write-Host "OK: $($m.Dest)"
  }
}

Write-Host "Listo. Añade una linea en Gano-Wiki/CHANGELOG.md describiendo estos snapshots."
