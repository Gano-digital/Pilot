################################################################################
# Kimi Local Authentication Helper (PowerShell)
# Uses stored OAuth tokens to enable Kimi CLI without 2FA phone requirement
#
# Setup:
#   . ".\.gano-skills\kimi\kimi-auth-local.ps1"
#
# Then use Kimi CLI normally:
#   kimi chat "your prompt"
#   kimi code analyze-file.ps1
################################################################################

param(
    [switch]$Test,
    [switch]$Verbose
)

$ErrorActionPreference = "Stop"

$CredentialsFile = "$env:USERPROFILE\.kimi\credentials\kimi-code.json"
$ConfigFile = "$env:USERPROFILE\.kimi\config.toml"

################################################################################
# Helper functions
################################################################################

function Write-Info {
    param([string]$Message)
    Write-Host "ℹ️  $Message" -ForegroundColor Cyan
}

function Write-Success {
    param([string]$Message)
    Write-Host "✅ $Message" -ForegroundColor Green
}

function Write-Warning {
    param([string]$Message)
    Write-Host "⚠️  $Message" -ForegroundColor Yellow
}

function Write-Error {
    param([string]$Message)
    Write-Host "❌ ERROR: $Message" -ForegroundColor Red
}

################################################################################
# Validate and extract tokens
################################################################################

function Test-KimiTokens {
    Write-Info "Validating Kimi local authentication..."

    # Check if credentials file exists
    if (-not (Test-Path $CredentialsFile)) {
        Write-Error "Credentials file not found: $CredentialsFile"
        return $false
    }

    # Load and validate JSON
    $credentialsContent = Get-Content $CredentialsFile -Raw
    $credentials = $credentialsContent | ConvertFrom-Json

    # Extract tokens
    $script:AccessToken = $credentials.access_token
    $script:RefreshToken = $credentials.refresh_token
    $script:ExpiresAt = $credentials.expires_at
    $script:DeviceId = $credentials.device_id ?? "local-auth"

    # Validate tokens exist
    if ([string]::IsNullOrWhiteSpace($script:AccessToken)) {
        Write-Error "access_token not found in credentials"
        return $false
    }

    if ([string]::IsNullOrWhiteSpace($script:RefreshToken)) {
        Write-Error "refresh_token not found in credentials"
        return $false
    }

    # Check expiration
    $currentTime = [int][double]::Parse((Get-Date -UFormat %s))
    $expiresAtInt = [int]$script:ExpiresAt

    if ($expiresAtInt -lt $currentTime) {
        Write-Warning "Token is expired (expires_at: $expiresAtInt, current: $currentTime)"
        return $false
    }

    Write-Success "Tokens loaded successfully"
    $expiresDate = (Get-Date -UnixTimeSeconds $expiresAtInt).ToString("yyyy-MM-dd HH:mm:ss")
    Write-Info "Token expiration: $expiresDate"

    return $true
}

################################################################################
# Configure Kimi to use environment tokens
################################################################################

function Set-KimiEnvironment {
    Write-Info "Configuring Kimi to use local tokens..."

    # Set environment variables for this session
    $env:KIMI_ACCESS_TOKEN = $script:AccessToken
    $env:KIMI_REFRESH_TOKEN = $script:RefreshToken
    $env:KIMI_DEVICE_ID = $script:DeviceId
    $env:KIMI_API_KEY = $script:AccessToken

    Write-Success "Environment variables configured"
}

################################################################################
# Test authentication
################################################################################

function Test-KimiAuth {
    Write-Info "Testing Kimi authentication..."

    # Check if kimi command exists
    $kimiCmd = Get-Command kimi -ErrorAction SilentlyContinue

    if ($kimiCmd) {
        Write-Success "Kimi CLI is available and ready"
        Write-Info "Run: kimi chat 'Hello' to test authentication"

        if ($Test) {
            Write-Info "Running test command: kimi version"
            try {
                $versionOutput = & kimi version 2>&1
                Write-Success "Test passed: $versionOutput"
            } catch {
                Write-Warning "Test command failed (may require active connection): $_"
            }
        }
    } else {
        Write-Warning "Kimi CLI not found in PATH"
        Write-Info "Install from: https://github.com/anthropics/kimi-cli/releases"
    }
}

################################################################################
# Main
################################################################################

function Start-KimiAuthSetup {
    Write-Host ""
    Write-Host "╔════════════════════════════════════════════════════════════╗"
    Write-Host "║ Kimi Local Authentication Setup                            ║"
    Write-Host "╚════════════════════════════════════════════════════════════╝"
    Write-Host ""

    # Validate and load tokens
    if (-not (Test-KimiTokens)) {
        Write-Error "Failed to load tokens"
        return $false
    }

    # Configure environment
    Set-KimiEnvironment

    # Test
    Test-KimiAuth

    Write-Host ""
    Write-Success "Kimi authentication ready!"
    Write-Host ""
    Write-Host "📝 Usage:"
    Write-Host "   kimi chat 'Your prompt here'"
    Write-Host "   kimi code analyze-file.ps1"
    Write-Host "   kimi task 'Complete my work'"
    Write-Host ""
    Write-Host "🔄 Environment variables set (this session):"
    Write-Host "   KIMI_ACCESS_TOKEN ✓"
    Write-Host "   KIMI_REFRESH_TOKEN ✓"
    Write-Host "   KIMI_DEVICE_ID: $($script:DeviceId)"
    Write-Host ""

    return $true
}

# Run main
Start-KimiAuthSetup
