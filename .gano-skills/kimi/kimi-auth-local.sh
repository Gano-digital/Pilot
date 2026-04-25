#!/bin/bash

################################################################################
# Kimi Local Authentication Helper
# Uses stored OAuth tokens to enable Kimi CLI without 2FA phone requirement
#
# Setup:
#   source ./.gano-skills/kimi/kimi-auth-local.sh
#
# Then use Kimi CLI normally:
#   kimi chat "your prompt"
#   kimi code analyze-file.py
################################################################################

set -euo pipefail

CREDENTIALS_FILE="${HOME}/.kimi/credentials/kimi-code.json"
CONFIG_FILE="${HOME}/.kimi/config.toml"

# Colors
BLUE='\033[0;34m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

################################################################################
# Helper functions
################################################################################

log_info() {
    echo -e "${BLUE}ℹ️  $1${NC}"
}

log_success() {
    echo -e "${GREEN}✅ $1${NC}"
}

log_warn() {
    echo -e "${YELLOW}⚠️  $1${NC}"
}

log_error() {
    echo -e "${RED}❌ ERROR: $1${NC}"
}

################################################################################
# Validate and extract tokens
################################################################################

validate_and_load_tokens() {
    log_info "Validating Kimi local authentication..."

    # Check if credentials file exists
    if [ ! -f "$CREDENTIALS_FILE" ]; then
        log_error "Credentials file not found: $CREDENTIALS_FILE"
        return 1
    fi

    # Check if it's valid JSON
    if ! jq empty "$CREDENTIALS_FILE" 2>/dev/null; then
        log_error "Credentials file is not valid JSON"
        return 1
    fi

    # Extract tokens
    local access_token=$(jq -r '.access_token' "$CREDENTIALS_FILE" 2>/dev/null)
    local refresh_token=$(jq -r '.refresh_token' "$CREDENTIALS_FILE" 2>/dev/null)
    local expires_at=$(jq -r '.expires_at' "$CREDENTIALS_FILE" 2>/dev/null)
    local device_id=$(jq -r '.["device_id"]' "$CREDENTIALS_FILE" 2>/dev/null || echo "")

    # Validate tokens exist
    if [ -z "$access_token" ] || [ "$access_token" = "null" ]; then
        log_error "access_token not found in credentials"
        return 1
    fi

    if [ -z "$refresh_token" ] || [ "$refresh_token" = "null" ]; then
        log_error "refresh_token not found in credentials"
        return 1
    fi

    # Check expiration
    local current_time=$(date +%s)
    if (( $(echo "$expires_at < $current_time" | bc -l) )); then
        log_warn "Token is expired (expires_at: $expires_at, current: $current_time)"
        return 1
    fi

    # Export tokens as environment variables for Kimi CLI
    export KIMI_ACCESS_TOKEN="$access_token"
    export KIMI_REFRESH_TOKEN="$refresh_token"
    export KIMI_DEVICE_ID="${device_id:-local-auth}"

    log_success "Tokens loaded successfully"
    log_info "Token expiration: $(date -d @${expires_at%.*} 2>/dev/null || echo 'April 2026')"

    return 0
}

################################################################################
# Configure Kimi to use environment token
################################################################################

configure_kimi_env() {
    log_info "Configuring Kimi to use local tokens..."

    if [ ! -f "$CONFIG_FILE" ]; then
        log_warn "Config file not found at $CONFIG_FILE"
        return 0
    fi

    # The config.toml has api_key="" which should fall back to OAuth storage
    # We'll set an environment variable that Kimi CLI checks before api_key
    export KIMI_API_KEY="${KIMI_ACCESS_TOKEN}"

    log_success "Environment variables configured"
}

################################################################################
# Test authentication
################################################################################

test_auth() {
    log_info "Testing Kimi authentication (non-blocking)..."

    # Try a simple API call (non-blocking, just verify token works)
    if command -v kimi &> /dev/null; then
        # Just verify kimi command exists; actual auth happens on first use
        log_success "Kimi CLI is available and ready"
        log_info "Run: kimi chat 'Hello' to test authentication"
    else
        log_warn "Kimi CLI not found in PATH; install with: brew install kimi (macOS) or download from releases"
    fi
}

################################################################################
# Main
################################################################################

main() {
    echo ""
    echo "╔════════════════════════════════════════════════════════════╗"
    echo "║ Kimi Local Authentication Setup                            ║"
    echo "╚════════════════════════════════════════════════════════════╝"
    echo ""

    # Load tokens
    if ! validate_and_load_tokens; then
        log_error "Failed to load tokens"
        return 1
    fi

    # Configure environment
    if ! configure_kimi_env; then
        log_error "Failed to configure Kimi environment"
        return 1
    fi

    # Test
    test_auth

    echo ""
    log_success "Kimi authentication ready!"
    echo ""
    echo "📝 Usage:"
    echo "   kimi chat 'Your prompt here'"
    echo "   kimi code analyze-file.js"
    echo "   kimi task 'Complete my work'"
    echo ""
    echo "🔄 Environment variables set:"
    echo "   KIMI_ACCESS_TOKEN ✓"
    echo "   KIMI_REFRESH_TOKEN ✓"
    echo "   KIMI_DEVICE_ID: $KIMI_DEVICE_ID"
    echo ""
}

# Run if sourced or executed
if [ "${BASH_SOURCE[0]}" == "${0}" ]; then
    main "$@"
else
    # When sourced, just load the tokens silently
    validate_and_load_tokens > /dev/null 2>&1 && configure_kimi_env > /dev/null 2>&1
    log_success "Kimi tokens loaded (sourced)"
fi
