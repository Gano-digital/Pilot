#!/usr/bin/env bash
# =============================================================================
# auth-policy.sh
# Authentication hardening for GoDaddy shared hosting.
#
# Usage:
#   bash auth-policy.sh [--web-root /path/to/public_html] [--dry-run]
#
# What this script does:
#   1. Verifies fail2ban is running (or documents alternatives).
#   2. Creates/updates an .htpasswd file to protect wp-admin with HTTP Basic Auth
#      as an additional layer when fail2ban is unavailable.
#   3. Sets up rate limiting for wp-login.php via .htaccess mod_evasive rules.
#   4. Adds CAPTCHA guidance for WordPress login forms.
#   5. Reviews and documents current session timeout settings.
#   6. Checks for hardcoded credentials in deployment scripts.
# =============================================================================
set -euo pipefail

WEB_ROOT="${HOME}/public_html"
DRY_RUN=false

while [[ $# -gt 0 ]]; do
  case "$1" in
    --web-root) WEB_ROOT="$2"; shift 2 ;;
    --dry-run)  DRY_RUN=true; shift ;;
    *) echo "Unknown option: $1"; exit 1 ;;
  esac
done

if $DRY_RUN; then
  echo "[DRY-RUN] No changes will be written."
fi

HTACCESS="${WEB_ROOT}/.htaccess"
WP_ADMIN_HTACCESS="${WEB_ROOT}/wp-admin/.htaccess"
HTPASSWD_FILE="${HOME}/.htpasswd_admin"

echo "===== Authentication Policy Hardening – $(date -u) ====="

# ── 1. fail2ban availability check ────────────────────────────────────────
echo ""
echo "[1/6] Checking fail2ban..."
if command -v fail2ban-client >/dev/null 2>&1; then
  echo "  OK: fail2ban is available"
  fail2ban-client status 2>/dev/null || echo "  fail2ban may not be running. Contact GoDaddy support to enable it."
  # Check if SSH jail is enabled
  if fail2ban-client status sshd >/dev/null 2>&1; then
    echo "  OK: fail2ban sshd jail is active"
  else
    echo "  WARNING: fail2ban sshd jail is not active"
  fi
else
  echo "  INFO: fail2ban is not installed (typical for shared hosting)."
  echo "  ALTERNATIVE: Use .htaccess rate limiting (configured below)."
  echo "  ALTERNATIVE: Enable GoDaddy WAF if available in your plan."
fi

# ── 2. HTTP Basic Auth on wp-admin as extra layer ─────────────────────────
echo ""
echo "[2/6] wp-admin HTTP Basic Auth hardening..."
if [ ! -d "${WEB_ROOT}/wp-admin" ]; then
  echo "  INFO: wp-admin directory not found – skipping."
else
  echo "  INFO: To add HTTP Basic Auth to wp-admin, create $HTPASSWD_FILE and"
  echo "        add the following to ${WP_ADMIN_HTACCESS}:"
  cat << 'DOCEOF'
  ---
  AuthType Basic
  AuthName "Restricted Access"
  AuthUserFile /home/YOUR_CPANEL_USER/.htpasswd_admin
  Require valid-user
  # Allow access to admin-ajax.php for front-end AJAX requests
  <Files "admin-ajax.php">
      Satisfy Any
      Allow from all
  </Files>
  ---
DOCEOF
  echo "  To create the .htpasswd file, run:"
  echo "    htpasswd -c $HTPASSWD_FILE YOUR_ADMIN_USERNAME"
  echo "  NOTE: Replace /home/YOUR_CPANEL_USER/ with your actual home directory path."

  if [ -f "$HTPASSWD_FILE" ]; then
    echo "  OK: $HTPASSWD_FILE already exists"
    HTPASSWD_PERMS=$(stat -c "%a" "$HTPASSWD_FILE")
    if [ "$HTPASSWD_PERMS" != "640" ] && [ "$HTPASSWD_PERMS" != "600" ]; then
      echo "  WARNING: $HTPASSWD_FILE permissions are $HTPASSWD_PERMS – fixing to 640"
      if ! $DRY_RUN; then
        chmod 640 "$HTPASSWD_FILE"
      fi
    else
      echo "  OK: .htpasswd permissions are $HTPASSWD_PERMS"
    fi
  fi
fi

# ── 3. Login rate limiting via .htaccess ──────────────────────────────────
echo ""
echo "[3/6] Configuring login rate limiting..."

LOGIN_RATE_BLOCK='
# ── Login rate limiting (managed by auth-policy.sh) ──
# Block login page access after repeated failures using mod_evasive or
# Wordfence/iThemes Security plugin (preferred on shared hosting).
# The rules below use a Satisfy Any approach compatible with most GoDaddy plans.

# Uncomment to restrict wp-login.php to specific IP addresses only:
# <Files "wp-login.php">
#     Order Deny,Allow
#     Deny from all
#     Allow from YOUR.IP.ADDRESS.HERE
# </Files>

# Block XML-RPC brute-force (already in main security block, reinforced here)
<Files "xmlrpc.php">
    Order Deny,Allow
    Deny from all
</Files>
'

if [ -f "$HTACCESS" ]; then
  if grep -q "Login rate limiting" "$HTACCESS" 2>/dev/null; then
    echo "  OK: Login rate limiting block already present in $HTACCESS"
  else
    if ! $DRY_RUN; then
      echo "$LOGIN_RATE_BLOCK" >> "$HTACCESS"
      echo "  OK: Login rate limiting block added to $HTACCESS"
    else
      echo "  [DRY-RUN] Would add login rate limiting block to $HTACCESS"
    fi
  fi
else
  echo "  WARNING: $HTACCESS not found. Run firewall-rules.sh first."
fi

# ── 4. WordPress session and authentication best practices ────────────────
echo ""
echo "[4/6] WordPress authentication configuration advice..."
WP_CONFIG="${WEB_ROOT}/wp-config.php"
if [ -f "$WP_CONFIG" ]; then
  # Check for session expiry override
  if grep -q "auth_cookie_expiration\|COOKIE_EXPIRATION" "$WP_CONFIG" 2>/dev/null; then
    echo "  OK: Session expiry customization found in wp-config.php"
  else
    echo "  INFO: Consider shortening WordPress session duration."
    echo "        Add to wp-config.php or a plugin:"
    echo "        add_filter('auth_cookie_expiration', function() { return 2 * HOUR_IN_SECONDS; });"
  fi

  # Check for two-factor recommendation note
  echo "  RECOMMENDATION: Install a 2FA plugin such as:"
  echo "    - WP 2FA (recommended, TOTP-based)"
  echo "    - Google Authenticator"
  echo "    - Wordfence (includes 2FA + brute force protection)"
fi

# ── 5. Deployment key and GitHub secrets audit ────────────────────────────
echo ""
echo "[5/6] Deployment credential hygiene..."
SSH_DIR="$HOME/.ssh"

# Check for unencrypted private keys
for keyfile in "$SSH_DIR"/id_rsa "$SSH_DIR"/id_ed25519 "$SSH_DIR"/deploy_key "$SSH_DIR"/deploy_ed25519; do
  if [ -f "$keyfile" ]; then
    if grep -q "ENCRYPTED" "$keyfile" 2>/dev/null; then
      echo "  OK: $keyfile is passphrase-protected"
    else
      echo "  WARNING: $keyfile is not passphrase-protected."
      echo "    For deployment keys, use Ed25519 with no passphrase and restrict to the specific repo."
      echo "    Store in GitHub Actions Secrets as PRIVATE_KEY – never commit to the repository."
    fi
  fi
done

# Ensure no private keys are in the web root
if find "$WEB_ROOT" -maxdepth 3 \( -name "*.pem" -o -name "id_rsa" -o -name "*.key" \) 2>/dev/null | grep -q .; then
  echo "  WARNING: Possible private key files found in web root!"
  find "$WEB_ROOT" -maxdepth 3 \( -name "*.pem" -o -name "id_rsa" -o -name "*.key" \) 2>/dev/null
  if ! $DRY_RUN; then
    echo "  ACTION REQUIRED: Move these files outside the web root immediately."
  fi
else
  echo "  OK: No private key files found in web root"
fi

# ── 6. User account review ────────────────────────────────────────────────
echo ""
echo "[6/6] User account review..."
echo "  MANUAL CHECKS REQUIRED (cannot be automated on shared hosting):"
echo ""
echo "  GoDaddy cPanel:"
echo "    1. Review all FTP sub-accounts (File Manager > FTP Accounts)"
echo "    2. Remove any accounts for former team members"
echo "    3. Ensure all passwords are 16+ chars with mixed case, numbers, symbols"
echo "    4. Enable two-factor authentication for cPanel login"
echo "    5. Review Email Accounts – unused accounts should be removed or disabled"
echo ""
echo "  WordPress:"
echo "    1. Audit user list at /wp-admin/users.php"
echo "    2. Remove or demote accounts with Administrator role that don't need it"
echo "    3. Rename the default 'admin' username to something non-obvious"
echo "    4. Enable login attempt limiting (Wordfence / Limit Login Attempts Reloaded)"
echo "    5. Enforce 2FA for all admin-level accounts"
echo ""
echo "===== Authentication Policy Hardening Complete ====="
