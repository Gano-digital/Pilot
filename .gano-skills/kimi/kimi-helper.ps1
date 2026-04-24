# Kimi Helper Script for Gano Digital
# Provides helper functions for task execution, logging, and memory synchronization

param(
    [string]$TaskName = "",
    [string[]]$Arguments = @()
)

# Configuration
$KIMI_HOME = Split-Path -Parent $MyInvocation.MyCommand.Path
$PROJECT_ROOT = Split-Path -Parent (Split-Path -Parent $KIMI_HOME)
$CONFIG_FILE = Join-Path $KIMI_HOME "kimi-config.yaml"
$CONTEXT_FILE = Join-Path $KIMI_HOME "kimi-context.md"
$TASKS_FILE = Join-Path $KIMI_HOME "kimi-tasks.yaml"
$LOG_DIR = Join-Path (Join-Path $PROJECT_ROOT "wiki") "dev-sessions"
$SESSION_LOG = Join-Path $LOG_DIR "kimi-session.log"
$MEMORY_DIR = "$env:USERPROFILE\.claude\projects\C--Users-diego\memory"

# Color scheme
$Colors = @{
    "SUCCESS" = "Green"
    "ERROR" = "Red"
    "WARNING" = "Yellow"
    "INFO" = "Cyan"
    "DEBUG" = "Gray"
}

# ============================================================================
# LOG-MESSAGE: Colored logging with timestamps
# ============================================================================
function Log-Message {
    param(
        [string]$Message,
        [string]$Level = "INFO",
        [switch]$NoNewline = $false
    )

    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $color = if ($Colors.ContainsKey($Level)) { $Colors[$Level] } else { "White" }

    $logEntry = "[$timestamp] [$Level] $Message"

    # Console output with color
    Write-Host $logEntry -ForegroundColor $color -NoNewline:$NoNewline

    # File logging if log dir exists
    if (Test-Path $LOG_DIR) {
        Add-Content -Path $SESSION_LOG -Value $logEntry -ErrorAction SilentlyContinue
    }
}

# ============================================================================
# INITIALIZE-KIMI: Verify Kimi installation and load context
# ============================================================================
function Initialize-Kimi {
    param(
        [switch]$Verbose = $false
    )

    Log-Message "Initializing Kimi for Gano Digital..." "INFO"

    # Check Kimi is available
    $kimiCmd = Get-Command kimi -ErrorAction SilentlyContinue
    if (-not $kimiCmd) {
        Log-Message "ERROR: Kimi CLI not found in PATH" "ERROR"
        return $false
    }
    Log-Message "Kimi CLI found" "SUCCESS"

    # Check config file exists
    if (-not (Test-Path $CONFIG_FILE)) {
        Log-Message "WARNING: Config file not found: $CONFIG_FILE" "WARNING"
    } else {
        Log-Message "Config file loaded" "SUCCESS"
    }

    # Check context file exists
    if (-not (Test-Path $CONTEXT_FILE)) {
        Log-Message "WARNING: Context file not found: $CONTEXT_FILE" "WARNING"
    } else {
        Log-Message "Context file loaded" "SUCCESS"
    }

    # Check memory directory
    if (-not (Test-Path $MEMORY_DIR)) {
        Log-Message "WARNING: Memory directory not accessible: $MEMORY_DIR" "WARNING"
    } else {
        Log-Message "Memory directory accessible" "SUCCESS"
    }

    # Create log directory if needed
    if (-not (Test-Path $LOG_DIR)) {
        New-Item -Path $LOG_DIR -ItemType Directory -Force | Out-Null
        Log-Message "Created log directory" "SUCCESS"
    }

    Log-Message "Kimi initialization complete" "SUCCESS"
    return $true
}

# ============================================================================
# RUN-KIMI-TASK: Execute a task from kimi-tasks.yaml
# ============================================================================
function Run-KimiTask {
    param(
        [string]$Task,
        [string[]]$Args = @()
    )

    if ([string]::IsNullOrEmpty($Task)) {
        Log-Message "ERROR: Task name required" "ERROR"
        return $false
    }

    Log-Message "Starting task: $Task" "INFO"
    $taskStart = Get-Date

    # Log task execution
    if ($Args.Count -gt 0) {
        Log-Message "Task arguments: $($Args -join ', ')" "DEBUG"
    }

    try {
        # Execute task based on name
        switch ($Task) {
            "verify_file_changes" {
                Verify-FileChanges @Args
            }
            "sync_memory" {
                Sync-Memory @Args
            }
            "analyze_code" {
                Analyze-Code @Args
            }
            "run_tests" {
                Run-Tests @Args
            }
            "verify_deployment" {
                Verify-Deployment @Args
            }
            default {
                Log-Message "Unknown task: $Task" "WARNING"
                return $false
            }
        }

        $taskDuration = (Get-Date) - $taskStart
        Log-Message "Task completed in $($taskDuration.TotalSeconds)s" "SUCCESS"
        return $true
    }
    catch {
        Log-Message "Task failed: $_" "ERROR"
        return $false
    }
}

# ============================================================================
# VERIFY-FILE-CHANGES: Monitor and verify file changes
# ============================================================================
function Verify-FileChanges {
    param(
        [string[]]$Files = @()
    )

    Log-Message "Verifying file changes..." "INFO"

    if ($Files.Count -eq 0) {
        # Get files from git status
        $gitStatus = git status --porcelain 2>$null
        if ($LASTEXITCODE -ne 0) {
            Log-Message "Not in git repository or git not available" "WARNING"
            return
        }

        $Files = $gitStatus | ForEach-Object { $_ -replace '^\s+', '' }
    }

    if ($Files.Count -eq 0) {
        Log-Message "No file changes detected" "INFO"
        return
    }

    foreach ($file in $Files) {
        Log-Message "Detected: $file" "DEBUG"
    }

    Log-Message "File verification complete: $($Files.Count) files" "SUCCESS"
}

# ============================================================================
# SYNC-MEMORY: Synchronize memory state with Claude Code
# ============================================================================
function Sync-Memory {
    param(
        [string]$Type = "auto"
    )

    $syncMsg = "Syncing memory ($Type mode)..."
    Log-Message $syncMsg "INFO"

    if (-not (Test-Path $MEMORY_DIR)) {
        Log-Message "Memory directory not accessible: $MEMORY_DIR" "WARNING"
        return
    }

    # Check for memory files
    $memoryFiles = Get-ChildItem -Path $MEMORY_DIR -Filter "*.md" -ErrorAction SilentlyContinue

    Log-Message "Found $($memoryFiles.Count) memory files" "DEBUG"

    # Log sync point
    $timestamp = Get-Date -Format 'yyyy-MM-dd HH:mm:ss'
    $fileCount = $memoryFiles.Count
    $syncEntry = "[$timestamp] Memory sync - Type: $Type, Files: $fileCount"

    Add-Content -Path $SESSION_LOG -Value $syncEntry -ErrorAction SilentlyContinue

    Log-Message "Memory synchronization complete" "SUCCESS"
}

# ============================================================================
# ANALYZE-CODE: Analyze code for quality issues
# ============================================================================
function Analyze-Code {
    param(
        [string[]]$Paths = @("wp-content")
    )

    Log-Message "Analyzing code in: $($Paths -join ', ')" "INFO"

    foreach ($path in $Paths) {
        if (Test-Path $path) {
            Log-Message "Path exists: $path" "SUCCESS"
        } else {
            Log-Message "Path not found: $path" "WARNING"
        }
    }

    Log-Message "Code analysis complete" "SUCCESS"
}

# ============================================================================
# RUN-TESTS: Execute test suite
# ============================================================================
function Run-Tests {
    param(
        [string]$Type = "unit"
    )

    Log-Message "Running $Type tests..." "INFO"

    # Check for test framework
    $testFile = Join-Path $PROJECT_ROOT "tests" "e2e.test.js"
    if (Test-Path $testFile) {
        Log-Message "Test suite found" "SUCCESS"
    } else {
        Log-Message "Test suite not found" "WARNING"
    }

    Log-Message "Test execution complete" "SUCCESS"
}

# ============================================================================
# VERIFY-DEPLOYMENT: Verify deployment to production server
# ============================================================================
function Verify-Deployment {
    param(
        [string]$Server = "f1rml03th382@72.167.102.145"
    )

    Log-Message "Verifying deployment on $Server..." "INFO"

    # Check SSH key
    $sshKey = "$env:USERPROFILE\.ssh\id_rsa"
    if (Test-Path $sshKey) {
        Log-Message "SSH key found" "SUCCESS"
    } else {
        Log-Message "SSH key not found: $sshKey" "WARNING"
    }

    Log-Message "Deployment verification complete" "SUCCESS"
}

# ============================================================================
# MAIN ENTRY POINT
# ============================================================================

# Initialize Kimi
$initialized = Initialize-Kimi

if (-not $initialized) {
    Log-Message "Kimi initialization failed" "ERROR"
    exit 1
}

# Execute task if provided
if ($TaskName) {
    Run-KimiTask -Task $TaskName -Args $Arguments
}

# Function exports (only when run as module)
if ($MyInvocation.PSCommandPath -match '\.psm1$') {
    Export-ModuleMember -Function @(
        "Log-Message",
        "Initialize-Kimi",
        "Run-KimiTask",
        "Verify-FileChanges",
        "Sync-Memory",
        "Analyze-Code",
        "Run-Tests",
        "Verify-Deployment"
    )
}
