# Kimi SSH Setup — Quick Start (5 Minutes)

## ✅ Prerequisites Check

```bash
# 1. Verify SSH key exists and has correct permissions
ls -l ~/.ssh/id_rsa_deploy
# Expected: -rw------- (600 permissions)

# 2. Verify SSH config
cat ~/.ssh/config | grep -A 5 "Host gano-godaddy"
# Expected: Host entry with IdentityFile and IdentitiesOnly=yes
```

---

## 🚀 SETUP — Do This Now

### Step 1: Update SSH Config (2 min)

Open `~/.ssh/config` and add/update:

```ini
Host gano-godaddy
    HostName 72.167.102.145
    User f1rml03th382
    Port 22
    IdentityFile ~/.ssh/id_rsa_deploy
    IdentitiesOnly yes
    ServerAliveInterval 60
    ServerAliveCountMax 3
    StrictHostKeyChecking no
    UserKnownHostsFile /dev/null
```

**Save and close.**

### Step 2: Test Connection (1 min)

```bash
ssh gano-godaddy "whoami"
# Expected output: f1rml03th382

ssh gano-godaddy "php -l /home/f1rml03th382/public_html/gano.digital/wp-config.php"
# Expected: No syntax errors detected
```

If both pass ✅, skip to Step 3. If fail ❌, troubleshoot below.

### Step 3: Load Kimi SSH Helpers (1 min)

```bash
cd C:\Users\diego\Downloads\Gano.digital-copia
source .gano-skills/kimi/kimi-team-ssh-integration.sh
```

### Step 4: Test Kimi SSH Operations (1 min)

```bash
# Test remote PHP validation
validate_php_on_server "functions.php"

# Test error log check
check_server_errors

# Test file sync
check_file_sync "functions.php"

# Test git status
git_status_on_server
```

All green ✅? **You're ready to use Kimi SSH!**

---

## 🔥 Use Kimi SSH During Tasks 3-5

### For Task #14 (Content Corrections):

```bash
# After making changes to 7 templates:
validate_php_on_server "templates/page-privacidad.php"
validate_php_on_server "templates/page-terminos.php"
validate_php_on_server "templates/page-sla.php"
validate_php_on_server "templates/page-seo-landing.php"
validate_php_on_server "templates/page-ecosistemas.php"
validate_php_on_server "templates/page-contacto.php"
validate_php_on_server "templates/page-nosotros.php"

# Check for errors
check_server_errors
```

### For Task #16 (Homepage CSS Fix):

```bash
# After deploying CSS fix:
validate_php_on_server "functions.php"
validate_php_on_server "css/critical-homepage.css"
check_server_errors
git_status_on_server
```

### Full Team Collaboration Example:

```bash
# 1. Claude proposes
claude_proposes_ssh "Validate Task #14 content changes on server"

# 2. Kimi reviews
kimi_reviews_ssh "validate-content" "7 templates modified"

# 3. Execute validations
validate_php_on_server "templates/page-privacidad.php"
check_server_errors

# 4. Negotiate approach
negotiate_ssh_approach "Error handling" "Fix locally first" "Validate remote errors"

# 5. Check sync status
check_file_sync "templates/page-privacidad.php"
```

---

## ❌ Troubleshooting

### Problem: "Connection timed out"

**Solution:**
```bash
ssh -i ~/.ssh/id_rsa_deploy \
    -o IdentitiesOnly=yes \
    -o ConnectTimeout=5 \
    f1rml03th382@72.167.102.145 \
    "whoami"
```

If this works, your SSH config has an issue. Verify:
- `IdentitiesOnly yes` is set
- `IdentityFile ~/.ssh/id_rsa_deploy` is correct path

### Problem: "Permission denied (publickey)"

**Solution:**
1. Verify key permissions: `chmod 600 ~/.ssh/id_rsa_deploy`
2. Verify key is loaded: `ssh-add ~/.ssh/id_rsa_deploy`
3. Verify authorized_keys on server contains your key

### Problem: "No such file or directory"

**Solution:**
- Check remote file path is correct
- Use absolute path: `/home/f1rml03th382/public_html/gano.digital/...`
- Verify file exists: `ssh gano-godaddy "ls -la /path/to/file"`

---

## 📋 Verify Kimi SSH is Ready

```bash
# Quick sanity check
ssh gano-godaddy "echo 'Kimi SSH Ready!'"

# Check logs created
ls -la wiki/dev-sessions/kimi-ssh-operations.log

# Test all operations
source .gano-skills/kimi/kimi-team-ssh-integration.sh
validate_php_on_server "functions.php"
check_server_errors
git_status_on_server
```

---

## 🎯 Next Steps

1. ✅ Run this quickstart
2. ✅ Test all commands pass
3. ✅ Start Task #14 with `validate_php_on_server` for each template
4. ✅ Use `check_server_errors` after changes
5. ✅ Use `check_file_sync` to verify deployments

---

**Created:** 2026-04-25
**Status:** Ready to use
**Time to complete:** ~5 minutes

Questions? Check `KIMI-SSH-CONFIG.md` for detailed docs.
