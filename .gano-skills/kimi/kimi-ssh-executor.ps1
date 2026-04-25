<#
    Kimi SSH Executor — Remote operations on GoDaddy server

    Usage:
    . ".\.gano-skills\kimi\kimi-ssh-executor.ps1" -OperationType "validate_php" -FilePath "functions.php"
    . ".\.gano-skills\kimi\kimi-ssh-executor.ps1" -OperationType "check_errors"
    . ".\.gano-skills\kimi\kimi-ssh-executor.ps1" -OperationType "custom" -Command "git status"
#>

param(
    [Parameter(Mandatory=$false)]
    [ValidateSet("validate_php", "check_errors", "sync_status", "file_list", "git_status", "custom")]
    [string]$OperationType = "custom",

    [Parameter(Mandatory=$false)]
    [string]$Command,

    [Parameter(Mandatory=$false)]
    [string]$FilePath,

    [Parameter(Mandatory=$false)]
    [switch]$Verbose
)

# Configuration
$SSHAlias = "gano-godaddy"
$WordPressRoot = "/home/f1rml03th382/public_html/gano.digital"
$ThemePath = "$WordPressRoot/wp-content/themes/gano-child"
$PluginsPath = "$WordPressRoot/wp-content/plugins"
$MuPluginsPath = "$WordPressRoot/wp-content/mu-plugins"
$ErrorLogPath = "/home/f1rml03th382/error_log"

# Log file
$LogFile = "wiki/dev-sessions/kimi-ssh-operations.log"

function Write-Log {
    param([string]$Message, [string]$Level = "INFO")

    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $logEntry = "[$timestamp] [$Level] $Message"

    if ($Verbose) {
        Write-Host $logEntry
    }

    # Append to log file
    Add-Content -Path $LogFile -Value $logEntry -ErrorAction SilentlyContinue
}

function Invoke-RemoteCommand {
    param(
        [string]$RemoteCommand,
        [string]$Description
    )

    Write-Log "Executing: $Description" "DEBUG"
    Write-Host "🔵 SSH: $Description"

    try {
        # Execute via SSH
        $result = ssh $SSHAlias $RemoteCommand 2>&1

        if ($LASTEXITCODE -eq 0) {
            Write-Log "Success: $Description" "SUCCESS"
            Write-Host "✅ Success"
            return @{
                success = $true
                output  = $result
                time    = Get-Date
            }
        }
        else {
            Write-Log "Error code $LASTEXITCODE for: $Description" "ERROR"
            Write-Host "❌ Error (Exit code: $LASTEXITCODE)"
            return @{
                success = $false
                output  = $result
                code    = $LASTEXITCODE
                time    = Get-Date
            }
        }
    }
    catch {
        Write-Log "Exception: $($_.Exception.Message)" "ERROR"
        Write-Host "❌ Exception: $($_.Exception.Message)"
        return @{
            success = $false
            output  = $_.Exception.Message
            time    = Get-Date
        }
    }
}

function Test-SSHConnection {
    Write-Log "Testing SSH connection to $SSHAlias" "INFO"
    Write-Host "🔍 Testing SSH connection..."

    $result = Invoke-RemoteCommand "whoami" "SSH Connectivity Test"

    if ($result.success) {
        Write-Host "✅ SSH connection established as: $($result.output)"
        return $true
    }
    else {
        Write-Host "❌ SSH connection failed"
        return $false
    }
}

# Operation dispatcher
switch ($OperationType) {
    "validate_php" {
        if (-not $FilePath) {
            Write-Host "❌ FilePath required for validate_php operation"
            exit 1
        }

        $remoteFile = if ($FilePath.StartsWith("/")) { $FilePath } else { "$ThemePath/$FilePath" }
        $cmd = "php -l $remoteFile"

        Write-Host "📝 Validating PHP: $FilePath"
        Invoke-RemoteCommand $cmd "PHP Validation: $FilePath"
    }

    "check_errors" {
        Write-Host "📋 Checking error log..."
        $cmd = "tail -50 $ErrorLogPath | grep -v CSP_VIOLATION | grep -v 'DEBUG'"
        Invoke-RemoteCommand $cmd "Error Log Review (last 50 non-CSP errors)"
    }

    "sync_status" {
        if (-not $FilePath) {
            Write-Host "❌ FilePath required for sync_status operation"
            exit 1
        }

        $remoteFile = if ($FilePath.StartsWith("/")) { $FilePath } else { "$ThemePath/$FilePath" }
        Write-Host "🔄 Checking file sync: $FilePath"

        # Get remote MD5
        $cmd = "md5sum $remoteFile"
        $result = Invoke-RemoteCommand $cmd "MD5 Check: $FilePath"

        if ($result.success) {
            $remoteMD5 = ($result.output -split ' ')[0]
            Write-Host "📡 Remote MD5: $remoteMD5"

            # Compare with local if available
            $localPath = "wp-content/themes/gano-child/$FilePath"
            if (Test-Path $localPath) {
                $localMD5 = (Get-FileHash $localPath -Algorithm MD5).Hash.ToLower()
                Write-Host "💻 Local MD5:  $localMD5"

                if ($localMD5 -eq $remoteMD5.ToLower()) {
                    Write-Host "✅ Files match!"
                }
                else {
                    Write-Host "⚠️  Files differ - local changes not synced"
                }
            }
        }
    }

    "file_list" {
        if (-not $FilePath) {
            $FilePath = "."
        }

        $remoteDir = if ($FilePath.StartsWith("/")) { $FilePath } else { "$ThemePath/$FilePath" }
        Write-Host "📂 Listing: $FilePath"

        $cmd = "ls -lah $remoteDir"
        Invoke-RemoteCommand $cmd "Directory Listing: $FilePath"
    }

    "git_status" {
        Write-Host "📦 Checking git status on server..."
        $cmd = "cd $WordPressRoot && git status --short"
        Invoke-RemoteCommand $cmd "Git Status on Server"
    }

    "custom" {
        if (-not $Command) {
            Write-Host "❌ Command required for custom operation"
            exit 1
        }

        Write-Host "⚙️  Running custom command: $Command"
        Invoke-RemoteCommand $Command "Custom Command"
    }

    default {
        Write-Host "❌ Unknown operation: $OperationType"
        exit 1
    }
}

Write-Log "Operation completed: $OperationType" "INFO"
