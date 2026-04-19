# DEPLOY WORKFLOW — Gano Digital Frontend SOTA
**Status:** 2026-04-18 | **Branch:** main (commit b426edb1)

---

## 🔌 CONEXIONES REQUERIDAS

### SSH Server
```
Host: 72.167.102.145
User: f1rml03th382
Key: ~/.ssh/id_rsa_deploy (600 permissions)
Deploy path: /home/f1rml03th382/public_html/gano.digital
```

### GitHub PAT
```
Scope: repo, workflow
Config: git config --global github.token <PAT>
Verify: git ls-remote origin HEAD
```

---

## 📤 WORKFLOW AUTOMATIZADO

### 1. Local Development → Commit

```bash
cd /path/to/gano-digital
git add <files>
git commit -m "message"
git push origin main
```

**Auto-triggers:** GitHub Actions workflow 04 (Deploy)

### 2. GitHub Actions Deploy.yml

```yaml
name: Deploy
on:
  push:
    branches: [main]
    paths:
      - 'wp-content/themes/gano-child/**'
      - 'wp-content/plugins/gano-*/**'
      - 'wp-content/mu-plugins/**'

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Deploy via rsync
        run: |
          rsync -avz --delete \
            -e "ssh -i ${{ secrets.SSH_KEY }}" \
            wp-content/themes/gano-child/ \
            ${{ secrets.SERVER_USER }}@${{ secrets.SERVER_HOST }}:${{ secrets.DEPLOY_PATH }}/wp-content/themes/gano-child/
```

### 3. Server Post-Deploy

```bash
# SSH into server
ssh -i ~/.ssh/id_rsa_deploy f1rml03th382@72.167.102.145

# Flush WordPress cache + rewrite rules
wp cache flush --path=/home/f1rml03th382/public_html/gano.digital
wp rewrite flush --path=/home/f1rml03th382/public_html/gano.digital

# Verify deployment
curl -s https://gano.digital/ | grep "gc-dark"  # Should output > 0
```

---

## ✅ VERIFICACIÓN POST-DEPLOY

### 1. CSS Variables Applied

```bash
curl -s https://gano.digital/ | grep -o "var(--gc-" | wc -l
# Expected: > 10
```

### 2. Lead Capture Endpoint

```bash
curl -X POST https://gano.digital/wp-json/gano/v1/lead-capture \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@ejemplo.com",
    "nonce": "<NONCE>",
    "plan": "general"
  }'
# Expected: 200 OK + success response
```

### 3. Admin Page Accessible

```
1. Login: https://gano.digital/wp-admin/
2. Click "Leads" in menu
3. Should show table: Email | Plan | Fecha | IP
```

### 4. Lighthouse Score

```bash
# Use Google Lighthouse CLI or PageSpeed Insights
lighthouse https://gano.digital/ --output=json

# Expected: Performance > 85, Accessibility > 90
```

### 5. Visual Verification

```
Homepage (/):
- Hero: Dark background (#05080b)
- Nav: Transparent → solid on scroll
- Ecosistemas: Blue bordered cards (#1B4FD8)
- Lead magnet: Green button (#00C26B)

/ecosistemas/:
- Dark theme consistent
- 4 plan cards clickable
- CTAs redirect to secureserver.net

/dominios/:
- Domain search visible
- TLD grid populated

/innovacion/ (once phase7 activator runs):
- 20 articles in grid
- Light mode background
- Links to individual articles
```

---

## 🔄 SYNCHRONIZATION CHECKLIST

### Before First Deploy

- [ ] SSH key verified (test connection)
- [ ] GitHub PAT configured (test git ls-remote)
- [ ] GitHub Actions secrets set:
  - [ ] SSH (private key)
  - [ ] SERVER_HOST
  - [ ] SERVER_USER
  - [ ] DEPLOY_PATH
- [ ] Local main branch up-to-date with origin
- [ ] All changes committed and pushed

### During Deploy

- [ ] GitHub Actions workflow 04 runs
- [ ] rsync completes without errors
- [ ] No file permission issues
- [ ] Webhook/notification to Slack (if configured)

### After Deploy

- [ ] SSH into server and verify files
- [ ] Run wp cache flush
- [ ] Run wp rewrite flush
- [ ] Test endpoint: curl /wp-json/gano/v1/lead-capture
- [ ] Visual checks: homepage, /ecosistemas/, /dominios/
- [ ] Admin page: Leads menu appears
- [ ] Lighthouse score > 85

---

## 📋 TROUBLESHOOTING

### SSH Key Issues

```bash
# Check key permissions
ls -la ~/.ssh/id_rsa_deploy
# Should be: -rw------- (600)

# Fix permissions if needed
chmod 600 ~/.ssh/id_rsa_deploy

# Test connection
ssh -i ~/.ssh/id_rsa_deploy f1rml03th382@72.167.102.145 'echo OK'
```

### GitHub Actions Fails

```bash
# Check workflow logs: github.com/gano-digital/pilot/actions
# Common issues:
# 1. SSH key expired → rotate via Settings > Secrets
# 2. Deploy path changed → update DEPLOY_PATH secret
# 3. File permissions on server → SSH in and chmod wp-content/themes/gano-child
```

### Elementor Overrides CSS

```php
// Already fixed in functions.php:
add_action('wp_head', 'gano_critical_css', 1);
// Prioridad 1 vence a Elementor (prioridad 10)
// If still issues, check:
// - is_front_page() condition
// - CSS specificity (use !important if needed)
```

### Lead Capture Not Working

```bash
# Check REST endpoint registered
wp rest-api schema --path=/path/to/wordpress | grep gano

# Check nonce in frontend
curl https://gano.digital/ | grep "gano-lead-capture"

# Test POST directly
curl -X POST https://gano.digital/wp-json/gano/v1/lead-capture \
  -H "Content-Type: application/json" \
  -d '{"email":"test@test.com","nonce":"<NONCE>","plan":"general"}'
# Should return 200 with message
```

### Admin Page "Leads" Not Showing

```php
// Verify in wp-admin/
// - Go to Appearance > Menus
// - Or check functions.php:
grep "add_menu_page.*gano-leads" wp-content/themes/gano-child/functions.php
// Should be present

// If missing, check:
// - WordPress not in safe mode
// - User has admin capability
// - Theme functions.php loaded
```

---

## 🚀 DEPLOYMENT STRATEGY

### Phase 1: Testing (2026-04-18)

```bash
# 1. Verify SSH + PAT connections
ssh -i ~/.ssh/id_rsa_deploy f1rml03th382@72.167.102.145 'ls -la public_html/gano.digital/wp-content/themes/gano-child/'

# 2. Create feature branch and test workflows
git checkout -b test/sprint-deployment
git push origin test/sprint-deployment
# Manually trigger workflow 04 if needed
```

### Phase 2: Staging Sync (2026-04-19)

```bash
# 1. Use WordPress staging site to test
wp wp-staging create --name=gano-main-test \
  --path=/home/f1rml03th382/public_html/gano.digital

# 2. Deploy to staging (manual rsync with --dry-run)
rsync -avz --dry-run \
  -e "ssh -i ~/.ssh/id_rsa_deploy" \
  wp-content/themes/gano-child/ \
  f1rml03th382@72.167.102.145:public_html/gano.digital-staging/wp-content/themes/gano-child/

# 3. Verify in browser: gano.digital/staging or staging.gano.digital
```

### Phase 3: Production Deploy (2026-04-20)

```bash
# 1. Final verification on main
git log --oneline -5 origin/main

# 2. Push to main (triggers GitHub Actions)
git push origin main

# 3. Monitor workflow: github.com/gano-digital/pilot/actions

# 4. SSH to server post-deploy
ssh -i ~/.ssh/id_rsa_deploy f1rml03th382@72.167.102.145 << 'EOF'
  cd public_html/gano.digital
  wp cache flush
  wp rewrite flush
  echo "✓ Production synced"
EOF

# 5. Verify live: curl https://gano.digital/ | grep gc-dark
```

---

## 📊 METRICS & MONITORING

### During Deploy

- [ ] GitHub Actions workflow completes in < 5 min
- [ ] rsync transfers < 50 MB (first deploy) or < 10 MB (subsequent)
- [ ] Zero permission errors in logs

### Post-Deploy Checklist

- [ ] Homepage loads in < 3s
- [ ] Lead capture form functional
- [ ] Admin page "Leads" accessible
- [ ] No 404 errors in browser console
- [ ] CSS critical variables visible in source
- [ ] /wp-json/gano/v1/lead-capture returns 200

### Ongoing Monitoring

- [ ] Lighthouse score tracked (weekly)
- [ ] Lead capture conversion rate
- [ ] Page load time (Core Web Vitals)
- [ ] Error logs from Wordfence

---

## 🎯 SUCCESS CRITERIA

✅ **Deploy Successful When:**

1. All CSS variables (--gc-*) render on homepage
2. Lead magnet form captures email to wp-options
3. Admin page shows captured leads with correct data
4. /ecosistemas/ CTAs route to secureserver.net
5. /dominios/ domain search functional
6. Navigation is sticky and responsive
7. Lighthouse score > 85
8. Zero console errors (except third-party)

✅ **Ready for Marketing When:**

1. First lead captured successfully
2. Email notification sent to hola@gano.digital
3. Admin confirms lead in WordPress > Leads page
4. GA4 event "gano_lead_capture" fires
5. Checkout flow tested end-to-end (fake purchase)

---

## 📞 ESCALATION PATH

**Issue Level 1 (CSS/Visual)**
→ Check browser DevTools → Verify --gc-* variables → Update css files → Push

**Issue Level 2 (API/REST)**
→ Check WP_DEBUG logs → Verify nonce → Test endpoint with curl → Update inc/lead-magnet-handler.php

**Issue Level 3 (Deploy)**
→ Check SSH connection → Verify rsync logs → SSH to server and check permissions → Re-run workflow

**Issue Level 4 (Server)**
→ SSH to server → Check WordPress installation → Verify database → Run wp doctor

---

## 📅 TIMELINE

| Date | Task | Owner | Status |
|------|------|-------|--------|
| 2026-04-18 | All sprints implemented | Claude | ✅ |
| 2026-04-18 | SSH + PAT configured | Diego/Claude | ⏳ |
| 2026-04-19 | Deploy to staging | Diego | 📅 |
| 2026-04-20 | Production deploy | Diego | 📅 |
| 2026-04-21 | First lead capture (target) | - | 📅 |
| 2026-05-18 | First sale (north star) | - | 🎯 |

---

**Prepared:** 2026-04-18 | **Verified by:** Claude Code | **Approved by:** (pending)
