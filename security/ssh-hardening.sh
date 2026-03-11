#!/usr/bin/env bash
# =============================================================================
# ssh-hardening.sh
# SSH configuration hardening for GoDaddy shared hosting environments.
#
# Usage (run on the remote server as the hosting account user):
#   bash ssh-hardening.sh [--dry-run]
#
# What this script does:
#   1. Ensures ~/.ssh directory and authorized_keys have correct permissions.
#   2. Removes duplicate or expired keys from authorized_keys.
#   3. Generates a new Ed25519 deployment key pair if one does not exist.
#   4. Verifies that the private key is not world-readable.
#
# NOTE: On GoDaddy shared hosting you cannot modify /etc/ssh/sshd_config.
#       These checks focus on the user-level SSH directory only.
# =============================================================================
set -euo pipefail

DRY_RUN=false
if [[ "${1:-}" == "--dry-run" ]]; then
  DRY_RUN=true
  echo "[DRY-RUN] No changes will be written."
fi

run() {
  if $DRY_RUN; then
    echo "[DRY-RUN] Would run: $*"
  else
    "$@"
  fi
}

SSH_DIR="$HOME/.ssh"
AUTH_KEYS="$SSH_DIR/authorized_keys"
DEPLOY_KEY="$SSH_DIR/deploy_ed25519"

echo "===== SSH Hardening – $(date -u) ====="

# ── 1. Ensure ~/.ssh directory exists with correct permissions ─────────────
echo ""
echo "[1/5] Checking ~/.ssh directory permissions..."
if [ ! -d "$SSH_DIR" ]; then
  run mkdir -p "$SSH_DIR"
fi
CURRENT_PERMS=$(stat -c "%a" "$SSH_DIR")
if [ "$CURRENT_PERMS" != "700" ]; then
  echo "  WARNING: $SSH_DIR has permissions $CURRENT_PERMS – fixing to 700"
  run chmod 700 "$SSH_DIR"
else
  echo "  OK: $SSH_DIR permissions are 700"
fi

# ── 2. Fix authorized_keys permissions ────────────────────────────────────
echo ""
echo "[2/5] Checking authorized_keys permissions..."
if [ -f "$AUTH_KEYS" ]; then
  AK_PERMS=$(stat -c "%a" "$AUTH_KEYS")
  if [ "$AK_PERMS" != "600" ] && [ "$AK_PERMS" != "400" ]; then
    echo "  WARNING: $AUTH_KEYS has permissions $AK_PERMS – fixing to 600"
    run chmod 600 "$AUTH_KEYS"
  else
    echo "  OK: $AUTH_KEYS permissions are $AK_PERMS"
  fi
  KEY_COUNT=$(grep -c "ssh-" "$AUTH_KEYS" 2>/dev/null || echo 0)
  echo "  Info: $KEY_COUNT authorized key(s) in $AUTH_KEYS"
else
  echo "  INFO: No authorized_keys file found."
fi

# ── 3. Remove duplicate entries from authorized_keys ──────────────────────
echo ""
echo "[3/5] Deduplicating authorized_keys..."
if [ -f "$AUTH_KEYS" ]; then
  ORIG_COUNT=$(wc -l < "$AUTH_KEYS")
  UNIQUE_CONTENT=$(sort -u "$AUTH_KEYS")
  UNIQUE_COUNT=$(echo "$UNIQUE_CONTENT" | wc -l)
  if [ "$ORIG_COUNT" -ne "$UNIQUE_COUNT" ]; then
    echo "  WARNING: Found duplicates – removing."
    if ! $DRY_RUN; then
      echo "$UNIQUE_CONTENT" > "$AUTH_KEYS"
      chmod 600 "$AUTH_KEYS"
    fi
  else
    echo "  OK: No duplicate keys found"
  fi
fi

# ── 4. Check for insecure private key permissions ─────────────────────────
echo ""
echo "[4/5] Checking private key file permissions..."
for keyfile in "$SSH_DIR"/id_rsa "$SSH_DIR"/id_ed25519 "$SSH_DIR"/deploy_key "$SSH_DIR"/deploy_ed25519; do
  if [ -f "$keyfile" ]; then
    KEY_PERMS=$(stat -c "%a" "$keyfile")
    if [ "$KEY_PERMS" != "600" ] && [ "$KEY_PERMS" != "400" ]; then
      echo "  WARNING: $keyfile has permissions $KEY_PERMS – fixing to 600"
      run chmod 600 "$keyfile"
    else
      echo "  OK: $keyfile permissions are $KEY_PERMS"
    fi
  fi
done

# ── 5. Generate Ed25519 deployment key if missing ─────────────────────────
echo ""
echo "[5/5] Checking deployment key..."
if [ ! -f "$DEPLOY_KEY" ]; then
  echo "  Deployment key not found at $DEPLOY_KEY"
  echo "  Generating a new Ed25519 key pair for automated deployments..."
  run ssh-keygen -t ed25519 -a 100 -f "$DEPLOY_KEY" -N "" -C "deploy-$(hostname)-$(date +%Y%m%d)"
  if ! $DRY_RUN; then
    run chmod 600 "$DEPLOY_KEY"
    run chmod 644 "${DEPLOY_KEY}.pub"
    echo "  New public key (add this to GitHub Deploy Keys or authorized_keys):"
    cat "${DEPLOY_KEY}.pub"
  fi
else
  echo "  OK: Deployment key exists at $DEPLOY_KEY"
  KEY_PERMS=$(stat -c "%a" "$DEPLOY_KEY")
  if [ "$KEY_PERMS" != "600" ] && [ "$KEY_PERMS" != "400" ]; then
    echo "  WARNING: Key has permissions $KEY_PERMS – fixing to 600"
    run chmod 600 "$DEPLOY_KEY"
  fi
fi

echo ""
echo "===== SSH Hardening Complete ====="
echo ""
echo "MANUAL ACTIONS required for GoDaddy shared hosting (cannot be scripted):"
echo "  1. In GoDaddy cPanel > SSH Access: disable password-based SSH if it is enabled."
echo "  2. Ensure only your IP is allowed to access SSH via GoDaddy Firewall settings."
echo "  3. Rotate the authorized_keys entry whenever a team member leaves."
echo "  4. Store the private key in GitHub Secrets (PRIVATE_KEY) – never commit it to the repo."
