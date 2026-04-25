# Deploy Log — Security Hardening (PR #294)

**Date:** 2026-04-24 18:00 CDT  
**Deployer:** Kimi Code CLI (automated SSH deploy)  
**Server:** GoDaddy `72.167.102.145`  
**Docroot:** `/home/f1rml03th382/public_html/gano.digital/`  
**Repo Commit:** `eb6d5f62` (`origin/main`)  
**PR:** #294 — *security(hardening): mitiga 7 riesgos críticos y medios*

---

## 🎯 Summary

Deployed security hardening patches from PR #294 to production via direct SSH/SCP.  
WordPress cache and rewrite rules flushed post-deploy. All PHP files passed syntax validation.

---

## 📁 Files Deployed

| File | Status | Risk Mitigated |
|------|--------|----------------|
| `wp-content/gano-deploy/receive.php` | **NEW** | RCE via `exec()`, ZIP extraction of executables, missing rate limiting |
| `wp-content/mu-plugins/gano-agent/class-gano-agent-api.php` | **MODIFIED** | Public REST endpoints exposed when theme deactivated |
| `wp-content/mu-plugins/gano-agent/class-gano-leads-handler.php` | **MODIFIED** | Apache 2.2 `.htaccess` syntax obsolete + insufficient file protection |
| `wp-content/mu-plugins/gano-agent/class-gano-master-hub.php` | **MODIFIED** | API keys stored in database (`wp_options`) instead of `wp-config.php` constants |
| `wp-content/mu-plugins/gano-agent/gano-reseller-enricher.php` | **MODIFIED** | Missing CSRF nonce + capability checks on admin trigger URL |
| `wp-content/mu-plugins/gano-agent/gano-sota-enricher.php` | **MODIFIED** | Missing CSRF nonce + capability checks on admin trigger URL |
| `wp-content/mu-plugins/gano-agent/gano-sota-final-activator.php` | **MODIFIED** | Missing CSRF nonce + capability checks on admin trigger URL |
| `wp-content/themes/gano-child/css/landing-sota-v2.css` | **NEW** *(from PR #292)* | N/A — UI asset |
| `wp-content/themes/gano-child/js/landing-sota-v2.js` | **NEW** *(from PR #292)* | N/A — UI asset |
| `wp-content/themes/gano-child/front-page.php` | **MODIFIED** | N/A — UI sync |
| `wp-content/themes/gano-child/functions.php` | **MODIFIED** | N/A — UI sync |
| `wp-content/themes/gano-child/templates/page-sla.php` | **MODIFIED** | N/A — UI sync |

**Skills files (`.gano-skills/`)** were excluded from deploy per policy: *skills live local only, never in production.*

---

## 🛡️ Backup

Pre-deploy backups stored on server at:
```
/home/f1rml03th382/public_html/gano.digital/.deploy-backups/2026-04-24-security/
```

Contains copies of all 9 modified files before overwrite.

---

## 🔍 Post-Deploy Verification

| Check | Result |
|-------|--------|
| PHP syntax validation (all files) | ✅ Pass |
| `wp cache flush` | ✅ Success |
| `wp rewrite flush` | ✅ Success |
| File timestamps on server | ✅ All updated to 2026-04-24 18:01–18:03 UTC |
| `GANO_DEPLOY_HOOK_SECRET` in `wp-config.php` | ✅ Already present |
| `GANO_AI_API_KEY` in `wp-config.php` | ⚠️ **NOT SET** — still in `wp_options` |
| `GANO_CRM_WEBHOOK` in `wp-config.php` | ⚠️ **NOT SET** — still in `wp_options` |

---

## ⚠️ Action Items (Post-Deploy)

1. **Migrate credentials to `wp-config.php`**
   - Add `define('GANO_AI_API_KEY', 'sk-...');` to `wp-config.php`
   - Add `define('GANO_CRM_WEBHOOK', 'https://...');` to `wp-config.php`
   - Then delete from `wp_options` to remove database exposure.

2. **Rotate `GANO_DEPLOY_HOOK_SECRET`**
   - Current secret is hardcoded in `wp-config.php`. Consider rotating if it was ever logged or shared.

3. **Monitor error logs**
   - Check `/wp-content/gano-deploy/error_log` and server `error_log` for 24h post-deploy.

4. **Test REST endpoints**
   - Verify `/wp-json/gano-agent/v1/chat` returns 403 without valid nonce.
   - Verify lead capture still works end-to-end.

---

## 📝 Deploy Method

```bash
# 1. Backup existing files on server
mkdir -p .deploy-backups/2026-04-24-security
cp <files> .deploy-backups/2026-04-24-security/

# 2. SCP from local repo to server
scp -o IdentitiesOnly=yes -i ~/.ssh/id_rsa_deploy \
  <local-path> \
  f1rml03th382@72.167.102.145:<remote-path>

# 3. Verify syntax
php -l <file>

# 4. Flush caches
wp cache flush --allow-root
wp rewrite flush --allow-root
```

---

**Status:** ✅ COMPLETE  
**Next Review:** 2026-04-25 (24h health check)
