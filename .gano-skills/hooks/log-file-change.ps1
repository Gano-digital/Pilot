# Post Tool Use Hook - Log file changes to dev-sessions wiki
# Triggered when Write or Edit tools are used

param(
    [string]$ProjectRoot = $env:GANO_PROJECT_ROOT,
    [string]$WikiDir = $env:GANO_WIKI_DIR
)

# Read hook input from stdin (JSON from Claude Code)
$hookInput = @"
$(Read-Host -Prompt '' -InputObject $input)
"@ | ConvertFrom-Json -ErrorAction SilentlyContinue

if ($hookInput.tool_input.file_path) {
    $filePath = $hookInput.tool_input.file_path
    $fileName = Split-Path -Leaf $filePath

    # Log to changes file
    $changesLog = "$WikiDir/session_changes.log"
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"

    Add-Content -Path $changesLog -Value "$timestamp | Modified: $fileName" -Encoding UTF8 -ErrorAction SilentlyContinue

    # Return quiet success
    @{
        continue = $true
        suppressOutput = $true
    } | ConvertTo-Json | Write-Output
} else {
    # No file path, continue normally
    @{ continue = $true } | ConvertTo-Json | Write-Output
}
