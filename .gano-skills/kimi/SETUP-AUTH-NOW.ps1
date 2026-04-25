################################################################################
# 🚀 QUICK SETUP: Kimi Local Authentication (Copy & Paste)
#
# Just run this entire script in PowerShell:
#   1. Open PowerShell
#   2. Paste this entire script
#   3. Press Enter
#
# Then use: kimi chat "Hello"
################################################################################

# Navigate to project
cd "C:\Users\diego\Downloads\Gano.digital-copia"

# Load authentication
Write-Host "Loading Kimi authentication..." -ForegroundColor Cyan
. ".\.gano-skills\kimi\kimi-auth-local.ps1"

# Verify
Write-Host ""
Write-Host "🎯 Ready to use Kimi!" -ForegroundColor Green
Write-Host ""
Write-Host "Try these commands:"
Write-Host "  kimi chat 'Hello, Kimi!'"
Write-Host "  kimi code 'Analyze this file'"
Write-Host "  kimi task 'Help me with this'"
Write-Host ""
Write-Host "For team collaboration:"
Write-Host "  . '.\.gano-skills\kimi\kimi-team-client.sh'"
Write-Host ""
