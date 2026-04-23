<#
.SYNOPSIS
  Exporta el Gano-Wiki completo a Descargas como paquete portable, y además
  incrusta memoria relevante del repo Pilot (sessions/claude/cursor/research).

.DESCRIPTION
  Destino por defecto: $env:USERPROFILE\Downloads\Gano-SecondBrain-COMPLETO\
    wiki\                      espejo del Gano-Wiki (OneDrive)
    wiki\sessions\             copia de memory/sessions (repo)
    wiki\dev-sessions\         copia de wiki/dev-sessions (repo)
    wiki\pilot-claude-memory\   copia de memory/claude + dispatch-queue.json (repo)
    wiki\pilot-cursor-memory\   snapshots de .cursor/memory (repo) con sufijo de fecha
    wiki\pilot-memory-research\ volcado íntegro de memory/research (repo)

  NOTA: el Gano-Wiki incluye `_secrets-index` (datos sensibles). No subir el paquete
  portable a repos públicos.

.PARAMETER WikiSource
  Ruta absoluta a la wiki canónica (por defecto OneDrive del usuario actual).

.PARAMETER DownloadsRoot
  Ruta base de Descargas (por defecto USERPROFILE\Downloads).

.PARAMETER FolderName
  Nombre de carpeta del paquete portable.
#>
[CmdletBinding()]
param(
  [string]$WikiSource = '',
  [string]$DownloadsRoot = '',
  [string]$FolderName = 'Gano-SecondBrain-COMPLETO'
)

$ErrorActionPreference = 'Stop'

if (-not $WikiSource) {
  $WikiSource = Join-Path $env:USERPROFILE 'OneDrive\Documentos\Gano-Wiki'
}
if (-not $DownloadsRoot) {
  $DownloadsRoot = Join-Path $env:USERPROFILE 'Downloads'
}

$RepoRoot = Split-Path -Parent (Split-Path -Parent $MyInvocation.MyCommand.Path)
$DestRoot = Join-Path $DownloadsRoot $FolderName
$DestWiki = Join-Path $DestRoot 'wiki'

if (-not (Test-Path -LiteralPath $WikiSource)) {
  Write-Error "No existe la wiki fuente: $WikiSource (ajusta -WikiSource o crea Gano-Wiki en OneDrive)."
}

New-Item -ItemType Directory -Force -Path $DestWiki | Out-Null

Write-Host "Robocopy wiki -> $DestWiki"
$robolog = Join-Path $DestRoot 'robocopy-wiki-last.log.txt'
robocopy.exe $WikiSource $DestWiki /E /COPY:DAT /R:2 /W:2 /NFL /NDL /NJH /NJS *>&1 | Out-File -FilePath $robolog -Encoding utf8

function EnsureDir([string]$p) { New-Item -ItemType Directory -Force -Path $p | Out-Null }

# 1) memory/sessions → wiki/sessions
$srcSessions = Join-Path $RepoRoot 'memory\sessions'
$dstSessions = Join-Path $DestWiki 'sessions'
EnsureDir $dstSessions
if (Test-Path -LiteralPath $srcSessions) {
  Copy-Item -Force (Join-Path $srcSessions '*.md') $dstSessions
}

# 2) repo wiki/dev-sessions → wiki/dev-sessions
$srcDevSessions = Join-Path $RepoRoot 'wiki\dev-sessions'
$dstDevSessions = Join-Path $DestWiki 'dev-sessions'
EnsureDir $dstDevSessions
if (Test-Path -LiteralPath $srcDevSessions) {
  Copy-Item -Force (Join-Path $srcDevSessions '*.md') $dstDevSessions
}

# 3) memory/claude → wiki/pilot-claude-memory (+ dispatch-queue.json)
$srcClaude = Join-Path $RepoRoot 'memory\claude'
$dstClaude = Join-Path $DestWiki 'pilot-claude-memory'
EnsureDir $dstClaude
if (Test-Path -LiteralPath $srcClaude) {
  Copy-Item -Force (Join-Path $srcClaude '*.md') $dstClaude
  $dq = Join-Path $srcClaude 'dispatch-queue.json'
  if (Test-Path -LiteralPath $dq) {
    Copy-Item -Force $dq $dstClaude
  }
}

# 4) .cursor/memory → wiki/pilot-cursor-memory (con sufijo de fecha)
$srcCursorMem = Join-Path $RepoRoot '.cursor\memory'
$dstCursorMem = Join-Path $DestWiki 'pilot-cursor-memory'
EnsureDir $dstCursorMem
$stamp = Get-Date -Format 'yyyy-MM-dd'
foreach ($f in @('activeContext.md','techContext.md','progress.md','deferredItems.md','projectBrief.md')) {
  $src = Join-Path $srcCursorMem $f
  if (Test-Path -LiteralPath $src) {
    $name = [IO.Path]::GetFileNameWithoutExtension($f)
    Copy-Item -Force $src (Join-Path $dstCursorMem ($name + '-' + $stamp + '.md'))
  }
}

# 5) memory/research → wiki/pilot-memory-research (volcado íntegro)
$srcResearch = Join-Path $RepoRoot 'memory\research'
$dstResearch = Join-Path $DestWiki 'pilot-memory-research'
EnsureDir $dstResearch
if (Test-Path -LiteralPath $srcResearch) {
  Copy-Item -Force (Join-Path $srcResearch '*.md') $dstResearch
}

$readme = @"
# Gano Second Brain — paquete portable

**Generado:** $(Get-Date -Format 'yyyy-MM-dd HH:mm')
**Origen wiki:** ``$WikiSource``
**Este folder:** ``$DestRoot``

## Qué contiene

| Subcarpeta | Contenido |
|------------|------------|
| ``wiki\`` | Copia completa del Gano-Wiki (Markdown, PDFs, _secrets-index, etc.). |
| ``wiki\sessions\`` | Copia de `memory/sessions/*.md` (repo Pilot). |
| ``wiki\dev-sessions\`` | Copia de `wiki/dev-sessions/*.md` (repo Pilot). |
| ``wiki\pilot-claude-memory\`` | Copia de `memory/claude/*.md` + `dispatch-queue.json` (repo Pilot). |
| ``wiki\pilot-cursor-memory\`` | Snapshots de `.cursor/memory/*` con sufijo de fecha (repo Pilot). |
| ``wiki\pilot-memory-research\`` | Volcado íntegro de `memory/research/*.md` (repo Pilot). |

## Orden de lectura (humano)

1. ``wiki\README.md`` y ``wiki\INDEX.md``
2. ``wiki\STATUS-AND-ROADMAP.md``
3. ``wiki\exports\pdf-readers\`` (PDF 00–06 + **LIBRO-ESTUDIO-PROFESIONAL** + README + CURRICULO)
4. ``wiki\AGENTS.md``
5. (Pilot) ``wiki\sessions\README.md`` y ``wiki\pilot-claude-memory\README.md`` si vas a retomar ejecución de agentes.

## Variables de entorno

- **OneDrive (canónico):** ``GANO_WIKI_ROOT`` = ``$WikiSource``
- **Este portable:** ``GANO_WIKI_ROOT`` = ``$DestWiki``

## Seguridad

La carpeta wiki incluye `_secrets-index` (posibles claves y PII). No subas este paquete a GitHub.

## Re-exportar este paquete

    cd <ruta>\Pilot
    powershell -File scripts\export_second_brain_to_downloads.ps1 -FolderName '$FolderName'

"@

Set-Content -Path (Join-Path $DestRoot 'README-PRIMERO.md') -Value $readme -Encoding UTF8

Write-Host "Listo: $DestRoot"
