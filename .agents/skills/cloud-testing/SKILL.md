# Skill: Cloud Agent — Run & Test This Codebase

## Metadata
- **Name**: cloud-testing
- **Description**: Practical setup, execution, and testing instructions for Cloud agents working on the Gano Digital codebase. Covers PHP linting, GSD SDK tests, security scanning, and common environment workflows.
- **Scope**: Entire workspace
- **Last Updated**: 2026-04-11

## Activation Signals
- Cloud agent starting work on any area of the codebase
- Agent needs to run tests, lint, or validate changes
- Agent is unsure how to set up the local environment

---

## 1. Environment Bootstrap

Cloud agents start on a bare Ubuntu VM. Install what you need on demand.

### PHP (required for any `wp-content/` work)

```bash
sudo apt-get update -qq
sudo apt-get install -y -qq php8.2-cli php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-gd php8.2-intl
php -v  # verify 8.2.x
```

If `php8.2-cli` is unavailable, fall back to whatever `php-cli` is available — CI uses 8.2 but syntax checks are forward-compatible.

### Node 20 (required for `.gsd/` SDK work)

```bash
node -v  # likely pre-installed; must be >=20
# if missing:
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### jq (useful for agent-queue validation)

```bash
sudo apt-get install -y -qq jq
```

### No Docker required

TruffleHog runs in Docker **in CI only**. Cloud agents should not attempt to run TruffleHog locally — rely on the CI workflow `secret-scan.yml` after pushing.

### No WordPress runtime

There is no local WP installation, database, or web server. This repo is a **file-based WordPress codebase** synced to a remote GoDaddy Managed WordPress host. All PHP work is validated via syntax checks, not by running WordPress locally.

---

## 2. Codebase Areas & Testing Workflows

### 2a. PHP — Custom WordPress Code

**Scope**: `wp-content/mu-plugins/gano-*.php`, `wp-content/themes/gano-child/`, `wp-content/plugins/gano-*/`

**What CI does** (workflow `php-lint-gano.yml`):
```bash
find wp-content/mu-plugins -maxdepth 1 -name "gano-*.php" -exec php -l {} \;
find wp-content/themes/gano-child -name '*.php' -exec php -l {} \;
find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -print0 | \
  while IFS= read -r -d '' dir; do find "$dir" -name '*.php' -exec php -l {} \; done
```

**Run locally before pushing**:
```bash
# Lint all Gano PHP (same as CI)
find wp-content/mu-plugins wp-content/themes/gano-child -name '*.php' -exec php -l {} \;
find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -print0 | \
  while IFS= read -r -d '' dir; do find "$dir" -name '*.php' -exec php -l {} \; done
```

**Quick single-file check**:
```bash
php -l wp-content/mu-plugins/gano-security.php
```

**Key files and what they do**:

| File | Role |
|------|------|
| `wp-content/mu-plugins/gano-security.php` | CSP headers, rate limiting, wp-file-manager alert |
| `wp-content/mu-plugins/gano-seo.php` | JSON-LD schema, OG tags, resource hints, LCP preload |
| `wp-content/themes/gano-child/functions.php` | Enqueue, nonce CSRF for chat, Core Web Vitals, REST endpoints |
| `wp-content/themes/gano-child/inc/homepage-blocks.php` | Homepage shortcodes |
| `wp-content/themes/gano-child/inc/contact-form-handler.php` | Contact form processing |
| `wp-content/themes/gano-child/templates/shop-premium.php` | CTA template → Reseller cart |
| `wp-content/plugins/gano-reseller-enhancements/` | Price overrides, bundle handler, `gano_reseller_cart_url()` |

**PHP code rules** (enforced by project conventions):
- All functions use `gano_` prefix
- SQL via `$wpdb->prepare()` only
- Inputs: `sanitize_text_field()`, `sanitize_email()`, `wp_kses_post()`, `absint()`
- Outputs: `esc_html()`, `esc_url()`, `esc_attr()`, `esc_js()`
- Forms: `wp_nonce_field()` + `check_admin_referer()` / `wp_verify_nonce()`
- No `eval()`, no `exec()`, no hardcoded secrets
- No jQuery — vanilla JS only

### 2b. Node / GSD SDK Tests (Vitest)

**Scope**: `.gsd/sdk/`

**Setup**:
```bash
cd .gsd/sdk
npm install
```

**Run all tests**:
```bash
npm test                 # runs vitest (unit + integration)
npm run test:unit        # unit tests only
npm run test:integration # integration tests only (120s timeout)
```

**Test file conventions**:
- Unit: `src/**/*.test.ts` (excluding `*.integration.test.ts`)
- Integration: `src/**/*.integration.test.ts`
- Config: `.gsd/sdk/vitest.config.ts`

**Parent package** (`.gsd/`):
```bash
cd .gsd
npm install
npm test          # runs all GSD tests
npm run test:coverage  # with c8 coverage (70% line threshold)
```

### 2c. Agent Queue Validation

**Scope**: `.github/agent-queue/*.json`

**Run the validator**:
```bash
python3 scripts/validate_agent_queue.py
```

This checks JSON structure, `agent-task-id` uniqueness, and required fields in all queue files. CI runs this automatically when queue files change.

### 2d. Scripts (Python)

**Scope**: `scripts/*.py`

Most scripts are standalone (stdlib only). Key ones:

| Script | What it does | Dependencies |
|--------|-------------|--------------|
| `validate_agent_queue.py` | Validates `.github/agent-queue/` JSON files | stdlib |
| `validate_claude_dispatch.py` | Validates `memory/claude/dispatch-queue.json` | stdlib |
| `check_dns_https_gano.py` | Checks DNS/HTTPS records for gano.digital | stdlib |
| `generate_project_status_pdf.py` | Generates project status PDF | `fpdf2` |
| `generate_dev_audit_pdf.py` | Generates developer audit PDF | `fpdf2` |
| `security_session_reminder.py` | Session security checklist reminder | stdlib |

**Quick smoke test for validation scripts**:
```bash
python3 scripts/validate_agent_queue.py
python3 scripts/validate_claude_dispatch.py
```

### 2e. CSS & JS (Theme Assets)

**Scope**: `wp-content/themes/gano-child/css/`, `wp-content/themes/gano-child/js/`

No build step — files are served directly by WordPress. Validate by reading and reviewing.

**CSS tokens**: all custom properties use `--gano-*` prefix (defined in `:root`).

**JS conventions**: vanilla only (no jQuery), nonce passed via `wp_localize_script()`.

### 2f. Memory / Documentation

**Scope**: `memory/`, `.cursor/memory/`

Markdown-only — no tests needed. Validate links and structure by inspection. When completing significant work, update:
- `.cursor/memory/activeContext.md` — current focus
- `.cursor/memory/progress.md` — completion tracker

---

## 3. Pre-Push Checklist

Run this sequence before every push:

```bash
# 1. PHP syntax (if you touched PHP)
find wp-content/mu-plugins -maxdepth 1 -name "gano-*.php" -exec php -l {} \;
find wp-content/themes/gano-child -name '*.php' -exec php -l {} \;
find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -print0 | \
  while IFS= read -r -d '' dir; do find "$dir" -name '*.php' -exec php -l {} \; done

# 2. GSD SDK tests (if you touched .gsd/)
cd .gsd/sdk && npm test && cd ../..

# 3. Agent queue validation (if you touched queue files)
python3 scripts/validate_agent_queue.py

# 4. Check for secrets in staged files
git diff --cached | grep -iE '(password|secret|token|api_key|private_key)' && echo "STOP: possible secret" || echo "OK: no secrets detected"

# 5. Verify git status — stage files individually, never git add .
git status
```

---

## 4. CI Workflows Reference

| Workflow | Trigger | What it checks |
|----------|---------|----------------|
| `php-lint-gano.yml` | Push/PR touching Gano PHP paths | `php -l` on all custom PHP |
| `secret-scan.yml` | Push/PR to main | TruffleHog on Gano paths (Docker) |
| `validate-agent-queue.yml` | Changes to `.github/agent-queue/` | JSON structure validation |
| `copilot-setup-steps.yml` | Manual/push | PHP 8.2 + Node 20 + lint (Copilot env) |
| `deploy.yml` | Push to main (Gano paths) | Grep for credentials + rsync to server |

Cloud agents cannot trigger `deploy.yml` or `verify-patches.yml` (require SSH secrets and self-hosted runner). Focus on passing `php-lint-gano.yml` and `secret-scan.yml`.

---

## 5. Feature Flags & Configuration

There are no runtime feature flags in this codebase. Configuration is managed via:

- **WordPress options** (`get_option()` / `update_option()`): SEO settings, Reseller shopper ID
- **PHP constants** in `wp-config.php` (never in repo): `WP_DEBUG`, `DISALLOW_FILE_EDIT`
- **PFID constants** for Reseller: `define('GANO_PFID_HOSTING_ECONOMIA', 'PENDING_RCC')` — placeholders until RCC provides real IDs
- **MU-plugin `gano-phase6-setup.php`**: one-time setup flag via `gano_phase6_setup_done` option

To "mock" configuration for testing purposes, you don't need to — PHP lint checks syntax, not runtime behavior. If writing new PHP functions that read options, document the expected option keys and defaults.

---

## 6. Common Gotchas

| Issue | Resolution |
|-------|-----------|
| `php` not found | Install: `sudo apt-get install -y php8.2-cli` |
| `node` version < 20 | Upgrade via nodesource or nvm |
| `npm test` fails in `.gsd/sdk` | Run `npm install` first; check Node >= 20 |
| PHP lint fails on vendor files | Only lint `gano-*` paths — never lint third-party plugins |
| `git add .` rejected by convention | Stage files individually: `git add path/to/file` |
| Secrets detected in diff | Remove immediately; never commit `.env`, keys, or tokens |
| TruffleHog fails locally | Don't run it locally — it uses Docker in CI; just avoid committing secrets |
| `wp-config.php` in repo | It exists but must NEVER be modified via git |
| Plugins `gano-phase*` | NEVER delete — they are one-time installers; only deactivate after confirming content is applied |

---

## 7. Keeping This Skill Up to Date

When you discover a new testing trick, environment workaround, or runbook step while working in this codebase, **add it here** so future Cloud agents benefit.

### What to add
- New install commands needed for a fresh VM
- Test commands that were missing or wrong
- Gotchas you hit and how you resolved them
- New CI workflows and what they validate
- New codebase areas that need their own testing section

### How to add
1. Edit this file (`.agents/skills/cloud-testing/SKILL.md`)
2. Place the new content in the relevant section (Environment, Area, Checklist, Gotchas, etc.)
3. Update `Last Updated` in the Metadata section
4. Commit with message: `docs(skill): update cloud-testing with <brief description>`

### When to add
- Immediately after resolving a non-obvious environment or testing issue
- When a new workflow or test framework is introduced
- When a workaround becomes obsolete (remove it)

---

**Skill Status**: ACTIVE
**Last Updated**: 2026-04-11
