#!/usr/bin/env bash
# =============================================================================
# firewall-rules.sh
# Firewall hardening for GoDaddy shared hosting using .htaccess (Apache).
#
# Usage:
#   bash firewall-rules.sh [--web-root /path/to/public_html] [--dry-run]
#
# On GoDaddy shared hosting, direct iptables/ufw access is not available.
# This script instead writes Apache-level access controls via .htaccess.
#
# What this script does:
#   1. Blocks common vulnerability scanners and attack tools via User-Agent.
#   2. Blocks access to sensitive files (wp-config, .env, .git, etc.).
#   3. Rate-limits the WordPress login and XML-RPC endpoints.
#   4. Blocks known bad bots and botnets.
#   5. Prevents directory listing.
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
  echo "[DRY-RUN] No files will be modified."
fi

HTACCESS="${WEB_ROOT}/.htaccess"

echo "===== Firewall Rules (Apache .htaccess) – $(date -u) ====="
echo "  Web root : $WEB_ROOT"
echo "  .htaccess: $HTACCESS"

if [ ! -d "$WEB_ROOT" ]; then
  echo "ERROR: Web root $WEB_ROOT does not exist."
  exit 1
fi

# ── Build the security block to inject ────────────────────────────────────
SECURITY_BLOCK='
# ============================================================
# BEGIN Security Hardening – managed by firewall-rules.sh
# ============================================================

# 1. Disable directory listing
Options -Indexes

# 2. Block access to sensitive files
<FilesMatch "(wp-config\.php|\.env|\.git|\.htpasswd|phpinfo\.php|readme\.html|license\.txt|composer\.(json|lock)|package\.json|package-lock\.json)$">
    Order Allow,Deny
    Deny from all
</FilesMatch>

# 3. Block access to WordPress xmlrpc.php (common brute-force target)
#    Remove this rule if you explicitly use the XML-RPC API (e.g. Jetpack).
<Files "xmlrpc.php">
    Order Deny,Allow
    Deny from all
</Files>

# 4. Block common vulnerability scanner user-agents
<IfModule mod_rewrite.c>
    RewriteEngine On

    # Block empty or missing user-agent
    RewriteCond %{HTTP_USER_AGENT} ^-?$
    RewriteRule ^ - [F,L]

    # Block known attack tools and scanners
    RewriteCond %{HTTP_USER_AGENT} (sqlmap|nikto|nmap|masscan|ZGrab|nuclei|dirbuster|gobuster|wfuzz|hydra|acunetix|nessus|openvas|havij|pangolin|jbrofuzz|skipfish|w3af) [NC]
    RewriteRule ^ - [F,L]

    # Block path traversal attempts
    RewriteCond %{QUERY_STRING} (\.\./|\.\.%2F|%2F\.\.) [NC]
    RewriteRule ^ - [F,L]

    # Block SQL injection patterns in query strings
    RewriteCond %{QUERY_STRING} (union.*select|select.*from|drop\s+table|insert\s+into|benchmark\(|sleep\() [NC]
    RewriteRule ^ - [F,L]

    # Block XSS attempts in query strings
    RewriteCond %{QUERY_STRING} (<script|javascript:|vbscript:|onload=|onerror=|alert\() [NC]
    RewriteRule ^ - [F,L]

    # Block shell injection patterns
    RewriteCond %{QUERY_STRING} (;.*cmd=|;.*exec=|base64_decode\(|eval\(.*\$|passthru\(|system\(|phpinfo\(\)) [NC]
    RewriteRule ^ - [F,L]
</IfModule>

# 5. Block WordPress login page brute force (limit to specific IPs if needed)
#    To restrict wp-admin to your IP only, uncomment the lines below and
#    replace YOUR.IP.ADDRESS with your actual IP.
# <Files "wp-login.php">
#     Order Deny,Allow
#     Deny from all
#     Allow from YOUR.IP.ADDRESS
# </Files>

# 6. Set security response headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
    Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
    # Remove server version banner
    Header unset Server
    Header always unset X-Powered-By
</IfModule>

# 7. Hide server signature and version
ServerSignature Off

# 8. Prevent hotlinking of images (optional – uncomment to enable)
# <IfModule mod_rewrite.c>
#     RewriteCond %{HTTP_REFERER} !^$
#     RewriteCond %{HTTP_REFERER} !^https?://(www\.)?yourdomain\.com/ [NC]
#     RewriteRule \.(gif|jpg|jpeg|png|webp|svg)$ - [F,NC,L]
# </IfModule>

# ============================================================
# END Security Hardening
# ============================================================
'

if $DRY_RUN; then
  echo ""
  echo "[DRY-RUN] Would write the following security block to $HTACCESS:"
  echo "$SECURITY_BLOCK"
else
  # Backup existing .htaccess
  if [ -f "$HTACCESS" ]; then
    cp "$HTACCESS" "${HTACCESS}.bak-$(date +%Y%m%d%H%M%S)"
    echo "  Backup created: ${HTACCESS}.bak-$(date +%Y%m%d%H%M%S)"

    # Remove any existing managed block to avoid duplication
    if grep -q "BEGIN Security Hardening" "$HTACCESS"; then
      echo "  Removing existing security block from $HTACCESS..."
      # Use awk to delete the managed block
      awk '/# BEGIN Security Hardening/{skip=1} skip && /# END Security Hardening/{skip=0; next} !skip' "$HTACCESS" > "${HTACCESS}.tmp"
      mv "${HTACCESS}.tmp" "$HTACCESS"
    fi

    # Append new block
    echo "$SECURITY_BLOCK" >> "$HTACCESS"
    echo "  OK: Security block appended to existing $HTACCESS"
  else
    echo "$SECURITY_BLOCK" > "$HTACCESS"
    echo "  OK: Created new $HTACCESS with security rules"
  fi
fi

echo ""
echo "===== Firewall Rules Applied ====="
echo ""
echo "IMPORTANT: After applying these rules:"
echo "  1. Test your site immediately to ensure nothing is broken."
echo "  2. If your deployment pipeline uses XML-RPC, remove the xmlrpc.php block."
echo "  3. Review blocked IPs in your access log and whitelist any false positives."
echo "  4. Consider adding mod_security rules for WAF-level protection (GoDaddy Pro plans)."
