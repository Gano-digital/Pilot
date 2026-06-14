#!/bin/bash

################################################################################
# Kimi Local Authentication Helper (No jq dependency)
# Uses stored OAuth tokens to enable Kimi CLI without 2FA
################################################################################

set -euo pipefail

CREDENTIALS_FILE="${HOME}/.kimi/credentials/kimi-code.json"

# Colors
BLUE='\033[0;34m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

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

main() {
    echo ""
    echo "╔════════════════════════════════════════════════════════════╗"
    echo "║ Kimi Local Authentication Setup                            ║"
    echo "╚════════════════════════════════════════════════════════════╝"
    echo ""

    log_info "Validating Kimi local authentication..."

    # Check if credentials file exists
    if [ ! -f "$CREDENTIALS_FILE" ]; then
        log_error "Credentials file not found: $CREDENTIALS_FILE"
        return 1
    fi

    # Extract tokens using grep and sed (no jq required)
    local access_token=$(grep -o '"access_token":"[^"]*"' "$CREDENTIALS_FILE" | cut -d'"' -f4)
    local refresh_token=$(grep -o '"refresh_token":"[^"]*"' "$CREDENTIALS_FILE" | cut -d'"' -f4)
    local expires_at=$(grep -o '"expires_at":[0-9.]*' "$CREDENTIALS_FILE" | cut -d':' -f2)
    local device_id=$(grep -o '"device_id":"[^"]*"' "$CREDENTIALS_FILE" | cut -d'"' -f4)

    # Validate tokens exist
    if [ -z "$access_token" ]; then
        log_error "access_token not found in credentials"
        return 1
    fi

    if [ -z "$refresh_token" ]; then
        log_error "refresh_token not found in credentials"
        return 1
    fi

    if [ -z "$expires_at" ]; then
        log_error "expires_at not found in credentials"
        return 1
    fi

    # Check expiration
    local current_time=$(date +%s)
    if (( $(echo "$expires_at < $current_time" | bc -l 2>/dev/null || echo 0) )); then
        log_warn "Token is expired"
        return 1
    fi

    # Export tokens as environment variables for Kimi CLI
    export KIMI_ACCESS_TOKEN="$access_token"
    export KIMI_REFRESH_TOKEN="$refresh_token"
    export KIMI_DEVICE_ID="${device_id:-local-auth}"
    export KIMI_API_KEY="$access_token"

    log_success "Tokens loaded successfully"
    log_info "Token expires at: $(date -d @${expires_at%.*} 2>/dev/null || echo 'April 2026')"

    echo ""
    log_success "Kimi authentication ready!"
    echo ""
    echo "📝 Usage:"
    echo "   kimi chat 'Your prompt here'"
    echo "   kimi code analyze-file.js"
    echo ""
    echo "🔄 Environment variables set:"
    echo "   KIMI_ACCESS_TOKEN ✓"
    echo "   KIMI_REFRESH_TOKEN ✓"
    echo "   KIMI_DEVICE_ID: $KIMI_DEVICE_ID"
    echo ""

    return 0
}

main "$@"
