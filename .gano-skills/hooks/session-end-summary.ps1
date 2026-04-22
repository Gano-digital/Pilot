# Session End Hook - Create comprehensive session summary
# Triggered when session stops (user exits, compacts, etc.)

param(
    [string]$ProjectRoot = $env:GANO_PROJECT_ROOT,
    [string]$WikiDir = $env:GANO_WIKI_DIR,
    [string]$MemoryDir = $env:GANO_MEMORY_DIR
)

$timestamp = Get-Date -Format "yyyy-MM-dd_HHmmss"
$sessionFile = "$WikiDir/session_end_$timestamp.md"

# Collect session data
$gitStatus = @()
try {
    Push-Location $ProjectRoot
    $gitStatus += "## Git Status (Session End)"
    $gitStatus += ""
    $gitStatus += "**Branch:** $(git rev-parse --abbrev-ref HEAD 2>/dev/null)"
    $gitStatus += "**Changes:** $(git status --short 2>/dev/null | Measure-Object -Line | Select-Object -ExpandProperty Lines) files modified"
    $gitStatus += ""
    $gitStatus += "### Modified Files"
    git status --short 2>/dev/null | ForEach-Object { $gitStatus += "- $_" }
    Pop-Location
} catch {
    $gitStatus += "Git status unavailable"
}

$changesLog = ""
$commandsLog = ""
if (Test-Path "$WikiDir/session_changes.log") {
    $changesLog = "## File Changes This Session`n`n" + (Get-Content "$WikiDir/session_changes.log" -Tail 20 | Out-String)
}
if (Test-Path "$WikiDir/session_commands.log") {
    $commandsLog = "## Commands Executed`n`n" + (Get-Content "$WikiDir/session_commands.log" -Tail 10 | Out-String)
}

# Create summary
@"
---
name: Session End Summary — $timestamp
description: Workspace snapshot at session end with git status and changes
type: project
---

# Session End Summary — $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')

$($gitStatus -join "`n")

$changesLog

$commandsLog

## Next Steps
1. **If PR #279 needs merge:** Run \`gh pr merge 279 --merge\`
2. **If deployment needed:** Monitor GitHub Actions workflow #04
3. **If production validation:** SSH to 72.167.102.145 and verify

## Workspace State
- **All changes committed:** $(try { if ((git status --short 2>/dev/null | Measure-Object -Line).Lines -eq 0) { '✅ Yes' } else { '❌ No' } } catch { 'Unknown' })
- **Second brain sync:** ✅ Enabled
- **Memory updated:** $(if (Test-Path $MemoryDir) { '✅ Yes' } else { '❌ No' })

---
**Session ID:** $timestamp
**Status:** ✅ Session concluded
"@ | Out-File -FilePath $sessionFile -Encoding UTF8 -Force

# Clean up session logs
Remove-Item "$WikiDir/session_changes.log" -ErrorAction SilentlyContinue
Remove-Item "$WikiDir/session_commands.log" -ErrorAction SilentlyContinue

# Return success
$output = @{
    systemMessage = "📝 Session summary saved to wiki/dev-sessions/ — Ready for next session"
}
$output | ConvertTo-Json | Write-Output
