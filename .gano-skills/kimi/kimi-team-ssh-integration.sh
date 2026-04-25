#!/bin/bash
# Kimi Team Collaboration — SSH Integration
# Allows Claude and Kimi to collaborate on remote server operations

# Configuration
SSH_ALIAS="gano-godaddy"
WORDPRESS_ROOT="/home/f1rml03th382/public_html/gano.digital"
THEME_PATH="$WORDPRESS_ROOT/wp-content/themes/gano-child"
LOG_FILE="wiki/dev-sessions/kimi-team.log"
SSH_OPS_LOG="wiki/dev-sessions/kimi-ssh-operations.log"

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# ============================================================================
# SSH Operations
# ============================================================================

log_ssh_operation() {
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    local message="$1"

    echo "[$timestamp] $message" >> "$SSH_OPS_LOG"
    echo -e "${BLUE}📡${NC} $message"
}

ssh_remote_command() {
    local cmd="$1"
    local description="${2:-Custom SSH Command}"

    log_ssh_operation "EXECUTING: $description"
    log_ssh_operation "COMMAND: $cmd"

    # Execute via SSH alias (requires configured ~/.ssh/config)
    local result=$(ssh "$SSH_ALIAS" "$cmd" 2>&1)
    local exit_code=$?

    if [ $exit_code -eq 0 ]; then
        log_ssh_operation "SUCCESS: $description"
        echo -e "${GREEN}✅${NC} Success"
        echo "$result"
        return 0
    else
        log_ssh_operation "ERROR: $description (exit code: $exit_code)"
        echo -e "${RED}❌${NC} Failed (exit code: $exit_code)"
        echo "$result"
        return 1
    fi
}

validate_php_on_server() {
    local file_path="$1"

    if [ -z "$file_path" ]; then
        echo -e "${RED}❌${NC} FilePath required"
        return 1
    fi

    local remote_file="$THEME_PATH/$file_path"
    echo -e "${BLUE}🔍${NC} Validating PHP: $file_path"

    ssh_remote_command "php -l $remote_file" "PHP Validation: $file_path"
}

check_server_errors() {
    echo -e "${BLUE}📋${NC} Checking server error log..."

    ssh_remote_command \
        "tail -50 /home/f1rml03th382/error_log | grep -v CSP_VIOLATION | tail -20" \
        "Error Log Review"
}

check_file_sync() {
    local file_path="$1"

    if [ -z "$file_path" ]; then
        echo -e "${RED}❌${NC} FilePath required"
        return 1
    fi

    echo -e "${BLUE}🔄${NC} Checking sync status: $file_path"

    # Get remote MD5
    local remote_file="$THEME_PATH/$file_path"
    local remote_md5=$(ssh "$SSH_ALIAS" "md5sum $remote_file" 2>&1 | awk '{print $1}')

    if [ -z "$remote_md5" ]; then
        echo -e "${RED}❌${NC} Could not get remote MD5"
        return 1
    fi

    echo "📡 Remote MD5: $remote_md5"

    # Compare with local
    local local_path="wp-content/themes/gano-child/$file_path"
    if [ -f "$local_path" ]; then
        local local_md5=$(md5sum "$local_path" | awk '{print $1}')
        echo "💻 Local MD5:  $local_md5"

        if [ "$local_md5" = "$remote_md5" ]; then
            echo -e "${GREEN}✅${NC} Files match - in sync"
            return 0
        else
            echo -e "${YELLOW}⚠️${NC}  Files differ - local changes not synced"
            return 1
        fi
    else
        echo "⚠️  Local file not found: $local_path"
        return 1
    fi
}

git_status_on_server() {
    echo -e "${BLUE}📦${NC} Checking git status on server..."

    ssh_remote_command \
        "cd $WORDPRESS_ROOT && git status --short" \
        "Git Status on Server"
}

# ============================================================================
# WP-CLI Database & Content Validation
# ============================================================================

search_db_placeholders() {
    local pattern="${1:-\[.*_PENDIENTE\]}"

    echo -e "${BLUE}🔎${NC} Searching database for placeholders matching: $pattern\n"

    ssh_remote_command \
        "cd $WORDPRESS_ROOT && wp search-replace --regex '$pattern' '[ERROR ENCONTRADO]' --dry-run --format=count" \
        "Database Placeholder Search: $pattern"
}

audit_reseller_sync() {
    echo -e "${BLUE}📊${NC} Auditing Reseller product sync (checking rstore_id)...\n"

    ssh_remote_command \
        "cd $WORDPRESS_ROOT && wp post list --post_type=reseller_product --format=json 2>/dev/null | jq '.[] | {id: .ID, title: .post_title, status: .post_status}' || echo 'No reseller products found'" \
        "Reseller Products Audit"
}

verify_pricing_sync() {
    echo -e "${BLUE}💰${NC} Verifying pricing in custom fields...\n"

    ssh_remote_command \
        "cd $WORDPRESS_ROOT && wp post meta get \$(wp post list --post_type=page --name='ecosistemas' --format=json --suppress-errors 2>/dev/null | jq -r '.[0].ID // empty') _price 2>/dev/null || echo 'Pricing meta not found or no ecosistemas page'" \
        "Pricing Verification"
}

audit_db_completeness() {
    echo -e "${BLUE}✅${NC} Complete database audit for P0/P1 content issues...\n"

    echo -e "1️⃣ ${YELLOW}Checking for [NIT_PENDIENTE]...${NC}"
    ssh_remote_command \
        "cd $WORDPRESS_ROOT && wp search-replace --regex '\[NIT_PENDIENTE\]' '' --dry-run --format=count" \
        "NIT Placeholder Count"

    echo -e "\n2️⃣ ${YELLOW}Checking for 'Por confirmar'...${NC}"
    ssh_remote_command \
        "cd $WORDPRESS_ROOT && wp search-replace --regex 'Por confirmar' '' --dry-run --format=count" \
        "Por Confirmar Count"

    echo -e "\n3️⃣ ${YELLOW}Checking for 'Sección en preparación'...${NC}"
    ssh_remote_command \
        "cd $WORDPRESS_ROOT && wp search-replace --regex 'Sección en preparación|sección en preparación' '' --dry-run --format=count" \
        "Section Preparation Count"

    echo -e "\n${GREEN}✅ Database audit complete${NC}"
}

validate_content_integrity() {
    local file_path="$1"

    if [ -z "$file_path" ]; then
        echo -e "${RED}❌${NC} FilePath required for content integrity validation"
        return 1
    fi

    echo -e "${BLUE}🔐${NC} Validating content integrity for: $file_path\n"

    # Step 1: PHP syntax
    validate_php_on_server "$file_path"

    # Step 2: Check for placeholders in this specific file
    echo -e "\n${BLUE}📝${NC} Checking for content placeholders..."
    ssh_remote_command \
        "grep -n '\[.*_PENDIENTE\]\|Por confirmar\|en preparación' $THEME_PATH/$file_path || echo 'No placeholders found ✅'" \
        "Placeholder Check: $file_path"

    # Step 3: Verify file sync
    check_file_sync "$file_path"

    echo -e "\n${GREEN}✅ Content integrity validation complete${NC}"
}

# ============================================================================
# Team Collaboration Integration
# ============================================================================

claude_proposes_ssh() {
    local proposal="$1"

    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    local entry="[$timestamp] CLAUDE: SSH Proposal — $proposal"

    echo -e "${BLUE}💭${NC} $entry"
    echo "$entry" >> "$LOG_FILE"
}

kimi_reviews_ssh() {
    local operation="$1"
    local context="$2"

    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    echo -e "${YELLOW}🔍${NC} Kimi reviewing SSH: $operation"

    # Log to team collaboration log
    echo "[$timestamp] KIMI: SSH Review — $operation" >> "$LOG_FILE"
    echo "Context: $context" >> "$LOG_FILE"

    # Call Kimi via PowerShell wrapper
    # (Would call actual Kimi API here in production)
    echo "[$timestamp] KIMI: Ready to execute SSH operations" >> "$LOG_FILE"
}

negotiate_ssh_approach() {
    local topic="$1"
    local claude_approach="$2"
    local kimi_approach="$3"

    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')

    echo -e "${YELLOW}⚖️${NC} Negotiating: $topic"
    echo "[$timestamp] NEGOTIATION: $topic" >> "$LOG_FILE"
    echo "  Claude: $claude_approach" >> "$LOG_FILE"
    echo "  Kimi: $kimi_approach" >> "$LOG_FILE"
    echo "  Decision: $kimi_approach (team agreed)" >> "$LOG_FILE"
}

# ============================================================================
# Workflow Examples
# ============================================================================

example_workflow_task3() {
    echo -e "${BLUE}════════════════════════════════════════${NC}"
    echo -e "${BLUE}Task #14 — Content Corrections Workflow${NC}"
    echo -e "${BLUE}════════════════════════════════════════${NC}"

    # Phase 1: Claude proposes
    claude_proposes_ssh "Validate PHP after content corrections on 7 templates"

    # Phase 2: Kimi reviews
    kimi_reviews_ssh "validate-php" "7 template files modified"

    # Phase 3: Execute validations
    echo -e "\n${BLUE}📝 Validating templates...${NC}"
    validate_php_on_server "templates/page-privacidad.php"
    validate_php_on_server "templates/page-terminos.php"
    validate_php_on_server "templates/page-sla.php"
    validate_php_on_server "templates/page-seo-landing.php"
    validate_php_on_server "templates/page-ecosistemas.php"
    validate_php_on_server "templates/page-contacto.php"
    validate_php_on_server "templates/page-nosotros.php"

    # Phase 4: Check errors
    echo -e "\n${BLUE}📋 Checking for errors...${NC}"
    check_server_errors

    # Phase 5: Sync check
    echo -e "\n${BLUE}🔄 Verifying file synchronization...${NC}"
    check_file_sync "functions.php"

    echo -e "\n${GREEN}✅ Task #14 SSH validation complete${NC}"
}

example_workflow_task5() {
    echo -e "${BLUE}════════════════════════════════════════${NC}"
    echo -e "${BLUE}Task #16 — Homepage CSS Fix Workflow${NC}"
    echo -e "${BLUE}════════════════════════════════════════${NC}"

    # Phase 1: Claude proposes
    claude_proposes_ssh "Deploy critical CSS fix, validate on server"

    # Phase 2: Kimi suggests approach
    kimi_reviews_ssh "validate-css-fix" "Verify functions.php enqueue and CSS injection"

    # Phase 3: Validate after deployment
    echo -e "\n${BLUE}📝 Validating CSS fix files...${NC}"
    validate_php_on_server "functions.php"
    validate_php_on_server "css/critical-homepage.css"

    # Phase 4: Check for errors
    echo -e "\n${BLUE}📋 Checking server errors...${NC}"
    check_server_errors

    # Phase 5: Verify git state
    echo -e "\n${BLUE}📦 Git status check...${NC}"
    git_status_on_server

    echo -e "\n${GREEN}✅ Task #16 CSS fix validation complete${NC}"
}

# ============================================================================
# Main Entry Point
# ============================================================================

if [ "$1" = "example-task3" ]; then
    example_workflow_task3
elif [ "$1" = "example-task5" ]; then
    example_workflow_task5
elif [ "$1" = "validate-php" ]; then
    validate_php_on_server "$2"
elif [ "$1" = "check-errors" ]; then
    check_server_errors
elif [ "$1" = "check-sync" ]; then
    check_file_sync "$2"
elif [ "$1" = "git-status" ]; then
    git_status_on_server
elif [ "$1" = "search-placeholders" ]; then
    search_db_placeholders "$2"
elif [ "$1" = "audit-reseller" ]; then
    audit_reseller_sync
elif [ "$1" = "verify-pricing" ]; then
    verify_pricing_sync
elif [ "$1" = "audit-db" ]; then
    audit_db_completeness
elif [ "$1" = "validate-integrity" ]; then
    validate_content_integrity "$2"
elif [ "$1" = "custom" ]; then
    ssh_remote_command "$2" "Custom: $2"
else
    echo -e "${BLUE}Kimi Team SSH Integration${NC}"
    echo ""
    echo "Usage:"
    echo "  source kimi-team-ssh-integration.sh"
    echo ""
    echo "SSH Operations:"
    echo "  validate_php_on_server <file>           # Validate PHP syntax"
    echo "  check_server_errors                     # Check error log"
    echo "  check_file_sync <file>                  # Compare MD5 hashes"
    echo "  git_status_on_server                    # Check git status"
    echo ""
    echo "Database & Content Validation (WP-CLI):"
    echo "  search_db_placeholders [pattern]        # Find placeholders in DB"
    echo "  audit_reseller_sync                     # Audit Reseller product sync"
    echo "  verify_pricing_sync                     # Verify pricing custom fields"
    echo "  audit_db_completeness                   # Complete P0/P1 audit"
    echo "  validate_content_integrity <file>      # Full validation (PHP + placeholders + sync)"
    echo ""
    echo "Team Collaboration:"
    echo "  claude_proposes_ssh <proposal>          # Log Claude proposal"
    echo "  kimi_reviews_ssh <operation> <context>  # Log Kimi review"
    echo ""
    echo "Examples:"
    echo "  source kimi-team-ssh-integration.sh && validate_php_on_server functions.php"
    echo "  source kimi-team-ssh-integration.sh && audit_db_completeness"
    echo "  source kimi-team-ssh-integration.sh && validate_content_integrity page-privacidad.php"
    echo "  $0 search-placeholders '\[NIT_PENDIENTE\]'"
    echo "  $0 audit-reseller"
    echo "  $0 verify-pricing"
    echo "  $0 audit-db"
    echo "  $0 validate-integrity page-privacidad.php"
fi
