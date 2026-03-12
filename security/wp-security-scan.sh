#!/usr/bin/env bash
# =============================================================================
# wp-security-scan.sh
# WordPress plugin, theme, and core security scanner for GoDaddy shared hosting.
#
# Usage:
#   bash wp-security-scan.sh [--wp-root /path/to/public_html] [--fix] [--dry-run]
#
# What this script does:
#   1. Checks WordPress core version and flags outdated installs.
#   2. Lists all plugins and themes with their versions.
#   3. Checks wp-config.php for common security misconfigurations.
#   4. Scans for known malicious PHP backdoor patterns.
#   5. Validates file permissions for critical WordPress files.
#   6. Checks for exposed sensitive files (readme.html, debug.log, etc.).
#   7. With --fix: applies automatic remediations where safe to do so.
# =============================================================================
set -euo pipefail

WP_ROOT="${HOME}/public_html"
FIX_MODE=false
DRY_RUN=false

while [[ $# -gt 0 ]]; do
  case "$1" in
    --wp-root)  WP_ROOT="$2"; shift 2 ;;
    --fix)      FIX_MODE=true; shift ;;
    --dry-run)  DRY_RUN=true; shift ;;
    *) echo "Unknown option: $1"; exit 1 ;;
  esac
done

if $DRY_RUN; then
  echo "[DRY-RUN] No changes will be made."
fi

REPORT_FILE="/tmp/wp-security-$(date +%Y%m%d%H%M%S).txt"
ISSUES=0

log()  { echo "$*" | tee -a "$REPORT_FILE"; }
warn() { log "  WARNING: $*"; ISSUES=$((ISSUES + 1)); }
ok()   { log "  OK: $*"; }
info() { log "  INFO: $*"; }

log "===== WordPress Security Scan – $(date -u) ====="
log "  WP Root: $WP_ROOT"
log ""

if [ ! -d "$WP_ROOT" ]; then
  log "ERROR: WordPress root $WP_ROOT does not exist."
  exit 1
fi

WP_CONTENT="${WP_ROOT}/wp-content"
WP_CONFIG="${WP_ROOT}/wp-config.php"
WP_VERSION_FILE="${WP_ROOT}/wp-includes/version.php"

# ── 1. WordPress core version ─────────────────────────────────────────────
log "--- [1] WordPress Core Version ---"
if [ -f "$WP_VERSION_FILE" ]; then
  WP_VERSION=$(grep -oP '(?<=\$wp_version = '"'"')[^'"'"']+' "$WP_VERSION_FILE" 2>/dev/null || echo "unknown")
  log "  Installed version: $WP_VERSION"
  info "Verify this is the latest version at https://wordpress.org/news/category/releases/"
else
  warn "WordPress version file not found at $WP_VERSION_FILE"
fi
log ""

# ── 2. wp-config.php security settings ────────────────────────────────────
log "--- [2] wp-config.php Security Settings ---"
if [ -f "$WP_CONFIG" ]; then
  # Security keys
  if grep -q "put your unique phrase here" "$WP_CONFIG" 2>/dev/null; then
    warn "Default WordPress security keys in use. Regenerate at: https://api.wordpress.org/secret-key/1.1/salt/"
  else
    ok "Security keys are set"
  fi

  # DB prefix
  if grep -qE "table_prefix.*=.*'wp_'" "$WP_CONFIG" 2>/dev/null; then
    warn "Default database table prefix 'wp_' detected. Change to a custom prefix to reduce SQL injection risk."
  else
    ok "Custom database table prefix in use"
  fi

  # DISALLOW_FILE_EDIT
  if grep -q "DISALLOW_FILE_EDIT.*true" "$WP_CONFIG" 2>/dev/null; then
    ok "DISALLOW_FILE_EDIT is true (theme/plugin editor disabled)"
  else
    warn "DISALLOW_FILE_EDIT not enabled. Add to wp-config.php: define('DISALLOW_FILE_EDIT', true);"
    if $FIX_MODE && ! $DRY_RUN; then
      sed -i "/\/\* That's all/i define('DISALLOW_FILE_EDIT', true);" "$WP_CONFIG"
      log "  FIXED: DISALLOW_FILE_EDIT added to wp-config.php"
    fi
  fi

  # WP_DEBUG in production
  if grep -q "WP_DEBUG.*true" "$WP_CONFIG" 2>/dev/null; then
    warn "WP_DEBUG is enabled in production. Set to false to avoid leaking debug information."
    if $FIX_MODE && ! $DRY_RUN; then
      sed -i "s/define.*WP_DEBUG.*true.*/define('WP_DEBUG', false);/" "$WP_CONFIG"
      log "  FIXED: WP_DEBUG set to false"
    fi
  else
    ok "WP_DEBUG is disabled"
  fi

  # DISALLOW_FILE_MODS (prevents plugin/theme installation via admin)
  if grep -q "DISALLOW_FILE_MODS.*true" "$WP_CONFIG" 2>/dev/null; then
    ok "DISALLOW_FILE_MODS is true"
  else
    warn "DISALLOW_FILE_MODS not set. Consider adding: define('DISALLOW_FILE_MODS', true); on production."
  fi

  # wp-config.php permissions
  WP_CONFIG_PERMS=$(stat -c "%a" "$WP_CONFIG")
  # Convert to octal for correct comparison (e.g., 600 octal = 384 decimal)
  if [ $((8#$WP_CONFIG_PERMS)) -gt $((8#440)) ]; then
    warn "wp-config.php permissions are $WP_CONFIG_PERMS. Should be 440 or 400."
    if $FIX_MODE && ! $DRY_RUN; then
      chmod 440 "$WP_CONFIG"
      log "  FIXED: wp-config.php permissions set to 440"
    fi
  else
    ok "wp-config.php permissions are acceptable ($WP_CONFIG_PERMS)"
  fi
else
  warn "wp-config.php not found at $WP_CONFIG"
fi
log ""

# ── 3. Plugin audit ───────────────────────────────────────────────────────
log "--- [3] Plugin Audit ---"
PLUGIN_DIR="${WP_CONTENT}/plugins"
if [ -d "$PLUGIN_DIR" ]; then
  PLUGIN_COUNT=$(find "$PLUGIN_DIR" -maxdepth 1 -mindepth 1 -type d | wc -l)
  log "  Total plugins installed: $PLUGIN_COUNT"
  for plugin_dir in "$PLUGIN_DIR"/*/; do
    plugin_name=$(basename "$plugin_dir")
    main_file=$(find "$plugin_dir" -maxdepth 1 -name "*.php" 2>/dev/null | head -1)
    if [ -n "$main_file" ]; then
      version=$(grep -i "^[[:space:]]*\*[[:space:]]*Version:" "$main_file" 2>/dev/null | head -1 | sed 's/.*Version:[[:space:]]*//' | tr -d '\r')
      log "    $plugin_name (${version:-version unknown})"
    else
      log "    $plugin_name (no main PHP file found)"
    fi
  done
else
  warn "wp-content/plugins directory not found"
fi
log ""

# ── 4. Theme audit ────────────────────────────────────────────────────────
log "--- [4] Theme Audit ---"
THEME_DIR="${WP_CONTENT}/themes"
if [ -d "$THEME_DIR" ]; then
  THEME_COUNT=$(find "$THEME_DIR" -maxdepth 1 -mindepth 1 -type d | wc -l)
  log "  Total themes installed: $THEME_COUNT"
  # WordPress ships with 2-3 default themes; more likely indicates abandoned
  # installations that widen the attack surface (outdated theme = potential RCE).
  if [ "$THEME_COUNT" -gt 3 ]; then
    warn "$THEME_COUNT themes installed. Remove unused themes to reduce attack surface."
  fi
  for theme_dir in "$THEME_DIR"/*/; do
    theme_name=$(basename "$theme_dir")
    style_css="${theme_dir}/style.css"
    if [ -f "$style_css" ]; then
      version=$(grep -i "^[[:space:]]*Version:" "$style_css" 2>/dev/null | head -1 | sed 's/.*Version:[[:space:]]*//' | tr -d '\r')
      log "    $theme_name (${version:-version unknown})"
    else
      log "    $theme_name (no style.css found)"
    fi
  done
else
  warn "wp-content/themes directory not found"
fi
log ""

# ── 5. PHP backdoor pattern scan ─────────────────────────────────────────
log "--- [5] Malicious Code Pattern Scan ---"
SUSPICIOUS_PATTERNS=(
  'eval\(base64_decode'
  'eval\(gzinflate'
  'eval\(str_rot13'
  'preg_replace.*\/e'
  'assert\(\$_'
  '\$GLOBALS\[.*\]=.*base64'
  'passthru\(\$_'
  'system\(\$_'
  'exec\(\$_'
  'shell_exec\(\$_'
  '\$_POST\[.*\]\(\$_'
  'FilesMan'
  'c99shell'
  'r57shell'
)

MALWARE_FOUND=0
for pat in "${SUSPICIOUS_PATTERNS[@]}"; do
  matches=$(find "$WP_ROOT" -type f -name "*.php" -exec grep -lE "$pat" {} \; 2>/dev/null | head -5)
  if [ -n "$matches" ]; then
    warn "Suspicious pattern '$pat' found in:"
    echo "$matches" | while read -r f; do log "      $f"; done
    MALWARE_FOUND=$((MALWARE_FOUND + 1))
  fi
done
if [ "$MALWARE_FOUND" -eq 0 ]; then
  ok "No common backdoor patterns detected"
fi
log ""

# ── 6. Sensitive file exposure ────────────────────────────────────────────
log "--- [6] Sensitive File Exposure ---"
SENSITIVE_FILES=(
  "${WP_ROOT}/readme.html"
  "${WP_ROOT}/license.txt"
  "${WP_ROOT}/wp-admin/install.php"
  "${WP_ROOT}/wp-content/debug.log"
  "${WP_ROOT}/.env"
  "${WP_ROOT}/.git"
)
for f in "${SENSITIVE_FILES[@]}"; do
  if [ -e "$f" ]; then
    warn "$f exists and may be publicly accessible – block via .htaccess or delete if unused"
  else
    ok "$f is not present"
  fi
done
log ""

# ── 7. Upload directory PHP execution ─────────────────────────────────────
log "--- [7] PHP Execution in Upload Directory ---"
UPLOADS_DIR="${WP_CONTENT}/uploads"
UPLOADS_HTACCESS="${UPLOADS_DIR}/.htaccess"
if [ -d "$UPLOADS_DIR" ]; then
  if [ -f "$UPLOADS_HTACCESS" ] && grep -q "php" "$UPLOADS_HTACCESS" 2>/dev/null; then
    ok "PHP execution restriction found in uploads/.htaccess"
  else
    warn "No PHP execution restriction in $UPLOADS_HTACCESS"
    warn "Add to ${UPLOADS_HTACCESS}: <FilesMatch \"\\.php$\">\\n    Deny from all\\n</FilesMatch>"
    if $FIX_MODE && ! $DRY_RUN; then
      cat > "$UPLOADS_HTACCESS" << 'HTEOF'
# Block PHP execution in uploads directory
<FilesMatch "\.php$">
    Deny from all
</FilesMatch>
HTEOF
      log "  FIXED: PHP execution blocked in uploads directory"
    fi
  fi
fi
log ""

# ── Summary ───────────────────────────────────────────────────────────────
log "===== Scan Complete ====="
log "  Issues found : $ISSUES"
log "  Report saved : $REPORT_FILE"
log ""
if [ "$ISSUES" -gt 0 ]; then
  log "ACTION REQUIRED: Review the warnings above and remediate before next deployment."
  exit 1
else
  log "No critical issues found."
fi
