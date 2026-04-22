# Session Start Hook - Capture Gano Digital workspace state
# Automatically triggered when a Claude Code session starts in Gano Digital project

param(
    [string]$ProjectRoot = $env:GANO_PROJECT_ROOT,
    [string]$WikiDir = $env:GANO_WIKI_DIR,
    [string]$Phase = $env:GANO_PHASE
)

$timestamp = Get-Date -Format "yyyy-MM-dd_HHmmss"
$sessionDir = "$WikiDir"
$sessionFile = "$sessionDir/session_start_$timestamp.md"

# Create session capture
@"
---
name: Session Start — $timestamp
description: Workspace snapshot at session start
type: project
---

# Session Start Capture — $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')

## Current Phase
$Phase

## Git Status
$(cd $ProjectRoot; git status --short 2>/dev/null || 'Git not available')

## Recent Changes
$(cd $ProjectRoot; git log --oneline -5 2>/dev/null || 'No git history')

## Modified Files (Uncommitted)
$(cd $ProjectRoot; git diff --name-only 2>/dev/null | ForEach-Object { "- $_" } || 'No modified files')

## Active Branch
$(cd $ProjectRoot; git rev-parse --abbrev-ref HEAD 2>/dev/null || 'N/A')

## Workspace Priority Items
1. PR #279 merge to main (Auditoría UX/Visual)
2. Production validation post-deployment
3. Fase 4 - Integración GoDaddy Reseller

## Memory Sync
- Project memory last updated: $(Get-Item C:\Users\diego\.claude\projects\C--Users-diego\memory\project_gano_digital.md -ErrorAction SilentlyContinue | Select-Object -ExpandProperty LastWriteTime)
- Deployment status: Ready for admin merge

---
**Session ID:** $timestamp
**Status:** ✅ Session initialized
"@ | Out-File -FilePath $sessionFile -Encoding UTF8 -Force

# Return success JSON
$output = @{
    systemMessage = "Session started - workspace state captured in $sessionFile"
}
$output | ConvertTo-Json | Write-Output
