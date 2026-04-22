# Post Tool Use Hook - Log bash commands to dev-sessions wiki
# Triggered when Bash tool is used (async, doesn't block)

param(
    [string]$WikiDir = $env:GANO_WIKI_DIR
)

# Read hook input from stdin
$hookInput = @"
$(Read-Host -Prompt '' -InputObject $input)
"@ | ConvertFrom-Json -ErrorAction SilentlyContinue

if ($hookInput.tool_input.command) {
    $command = $hookInput.tool_input.command
    $commandsLog = "$WikiDir/session_commands.log"
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"

    # Log important commands (skip obvious safe ones like ls, pwd)
    if ($command -notmatch '^(ls|pwd|cd|echo)') {
        Add-Content -Path $commandsLog -Value "$timestamp | Command: $command" -Encoding UTF8 -ErrorAction SilentlyContinue
    }
}

# Always continue (async)
@{ continue = $true } | ConvertTo-Json | Write-Output
