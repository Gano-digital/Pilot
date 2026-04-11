# Skill: Cloud Agent Starter — Setup & Testing Runbook

## Metadata
- **Name**: cloud-agent-starter
- **Description**: Minimal starter runbook for Cloud agents (Cursor, Codex, Claude Code, etc.). Covers environment orientation, how to run and lint the codebase, CI workflows, and testing patterns for each major codebase area. Load this skill at the beginning of any session that touches code or CI. Update this file whenever a new testing trick or environment detail is discovered.
- **Scope**: Workspace root (`Gano-digital/Pilot`)
- **Dependencies**: PHP 8.x CLI, Python 3 stdlib, Node 18+ (optional, for `.gsd/sdk`)

## Activation Signals
Load this skill when the prompt contains:
- "how do I test", "how to run", "setup", "environment", "lint", "CI", "where do I start"
- Any new Cloud agent session that will modify code files

---

## 1. Orientation (Read First)

This is a **WordPress-first monorepo**. The WordPress site (`gano.digital`) runs on **GoDaddy Managed WordPress Deluxe**; the files in this repo are deployed to it — they do not run locally as a full WP stack. Cloud agents operate on the **repo files only** (static analysis, lint, scripts, docs) and cannot log in to `wp-admin` or run WordPress functions.

### Sources of truth — read in this order before large changes
1. `TASKS.md` — sprint-level tasks, blockers, what is done
2. `CLAUDE.md` — project context, plugin rules, stack decisions
3. `.cursor/memory/activeContext.md` — current session focus
4. `.github/DEV-COORDINATION.md` — what lives in Git vs server vs local

### Hard limits for Cloud agents
- **Never** modify `wp-config.php`, `.env`, `*.key`, `*.pem`, credentials files
- **Never** delete `gano-phase*` plugins without explicit Diego confirmation
- **Never** push directly to `main` — always via PR
- **Never** commit secrets (TruffleHog CI will block the merge)
- **Never** use jQuery or Tailwind CSS in new code

---

## 2. Environment State at Session Start

Run these to orient yourself quickly:

```bash
# Confirm PHP is available
php --version        # expect 8.x

# Confirm Python 3 is available
python3 --version    # expect 3.x

# Confirm git remote is correct
git remote -v        # should point to Gano-digital/Pilot on GitHub

# Confirm current branch
git status           # should be on main or a feature/* branch

# Quick health check on custom PHP files
find wp-content/mu-plugins wp-content/themes/gano-child -name '*.php' -exec php -l {} \;
find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -exec sh -c 'find "$1" -name "*.php" -exec php -l {} \;' _ {} \;

# Validate agent queues
python3 scripts/validate_agent_queue.py      # expect: "OK (N tareas en 8 colas)"

# Check DNS + HTTPS reachability
python3 scripts/check_dns_https_gano.py      # expect: DNS resolved + HTTPS 200
```

No credentials are needed for these checks. They use only the local filesystem and public network.

---

## 3. Codebase Areas and Testing Workflows

### 3.1 WordPress Theme — `wp-content/themes/gano-child/`

**What it contains:** Child theme for Hello Elementor. Core files: `functions.php`, `style.css`, JS in `js/`, PHP page templates in `templates/`.

**Static lint (run after any PHP edit):**
```bash
find wp-content/themes/gano-child -name '*.php' -exec php -l {} \;
```

**Manual verification checklist (requires server access, not automatable here):**
- [ ] `gano-chat.js` — nonce localized in `functions.php` via `wp_localize_script`
- [ ] Rate limiting transient key: `gano_rl_` prefix in REST endpoint
- [ ] `shop-premium.php` — CTAs use `gano_reseller_cart_url()`, no hardcoded URLs
- [ ] Defer attributes on `gano-chat-js`, `gano-quiz-js`, `shop-animations` enqueues

**CSS tokens to verify (no raw hex values allowed in new code):**
```css
/* All colors must reference these variables */
--gano-gold: #D4AF37
--gano-orange: #FF8C42
```

**Feature flags / conditional logic:**
- `_gano_coming_soon` post meta — controls coming-soon visibility; set via wp-admin
- `GANO_PFID_*` constants in `functions.php` — placeholder until RCC IDs obtained

---

### 3.2 MU Plugins — `wp-content/mu-plugins/`

**Files:** `gano-security.php`, `gano-seo.php`, `gano-agent-core.php`, `gano-p6-security-audit.php`, `gano-phase6-setup.php`

**Critical:** MU plugins load on **every page load**. A fatal error here takes down the entire site. Never commit a PHP syntax error to these files.

**Lint (mandatory before any commit touching mu-plugins):**
```bash
find wp-content/mu-plugins -name '*.php' -exec php -l {} \;
```

**What each file does:**
- `gano-security.php` — CSP headers (enforced, not report-only), `X-Frame-Options: SAMEORIGIN`, Referrer-Policy, Permissions-Policy, rate limiting REST (max 20 req/IP/60s, returns HTTP 429), wp-file-manager alert in wp-admin
- `gano-seo.php` — Schema JSON-LD (Organization, LocalBusiness, WebSite, Product, FAQPage, BreadcrumbList), Open Graph, Twitter Card, resource hints, LCP preload; configurable via wp-admin → "Gano SEO"

**Smoke test for CSP / headers (public site, no auth needed):**
```bash
curl -sI https://gano.digital | grep -E "Content-Security-Policy|X-Frame-Options|Referrer-Policy"
```

---

### 3.3 Custom Plugins — `wp-content/plugins/gano-*/`

**Do not delete** the `gano-phase*` installer plugins until each has been activated in WordPress and confirmed. See `CLAUDE.md` § "NOTA CRÍTICA — Plugins de fase".

**Lint all gano-* plugins:**
```bash
while IFS= read -r -d '' dir; do
  find "$dir" -name '*.php' -exec php -l {} \;
done < <(find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -print0 2>/dev/null)
```

**Key plugin: `gano-reseller-enhancements/`**
- Provides `gano_reseller_cart_url($pfid)` helper
- Contains ACF price override filters and 3-year bundle handler
- All PFID constants (`GANO_PFID_*`) are placeholders until mapped in RCC
- See `memory/commerce/rcc-pfid-checklist.md` for current mapping state

**Key plugin: `reseller-store/`**
- GoDaddy white-label cart integration
- `rstore_get_option('pl_id')` must match Diego's real Reseller account ID
- `rstore_id => 0` in `shop-premium.php` means product IDs are not yet configured — do not treat as a code bug, this is an ops pending item

---

### 3.4 GitHub Actions CI — `.github/workflows/`

CI runs on every push/PR to `main` that touches custom PHP paths. Workflows are numbered `01`–`14`; see `.github/workflows/README.md` for the full legend.

**The two mandatory CI checks are:**
1. **01 · CI · Sintaxis PHP (Gano)** (`php-lint-gano.yml`) — runs `php -l` on mu-plugins, gano-child, gano-* plugins
2. **02 · CI · Escaneo secretos (TruffleHog)** (`secret-scan.yml`) — scans for leaked credentials in Gano paths

**Simulate CI locally before pushing:**
```bash
# PHP lint (mirrors workflow exactly)
find wp-content/mu-plugins wp-content/themes/gano-child -name '*.php' -exec php -l {} \;
while IFS= read -r -d '' dir; do
  find "$dir" -name '*.php' -exec php -l {} \;
done < <(find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -print0 2>/dev/null)

# Pre-commit secret check (manual)
git diff --cached
```

**Deploy secrets required (must be set in GitHub → Settings → Secrets → Actions):**
- `SSH` — private key (PEM/OpenSSH, no passphrase, no CRLF)
- `SERVER_HOST` — hostname or IP for ssh-keyscan
- `SERVER_USER` — SSH/SFTP username
- `DEPLOY_PATH` — absolute path to WP root on server (no trailing slash inconsistency)

Without all four, workflow `04 · Deploy · Producción` will fail at "Setup SSH Agent".

**Manual workflow dispatch (safe to run at any time):**
- `05 · Ops · Verificar parches en servidor` — MD5 comparison, read-only
- `07 · Agentes · Validar cola JSON` — validates `.github/agent-queue/*.json`
- `14 · Ops · Gano Ops Hub` — regenerates `progress.json`

---

### 3.5 Python Scripts — `scripts/`

No dependencies beyond Python 3 stdlib (except PDF generators which need `fpdf2`).

| Script | Purpose | Command |
|--------|---------|---------|
| `validate_agent_queue.py` | Validate all agent task queues | `python3 scripts/validate_agent_queue.py` |
| `check_dns_https_gano.py` | Check DNS resolution + HTTPS reachability | `python3 scripts/check_dns_https_gano.py` |
| `security_session_reminder.py` | Print end-of-session security checklist | `python3 scripts/security_session_reminder.py` |
| `generate_gano_ops_progress.py` | Regenerate `progress.json` for Ops Hub | `python3 scripts/generate_gano_ops_progress.py` |
| `generate_dev_audit_pdf.py` | PDF audit report (needs `fpdf2`) | `pip install fpdf2 && python3 scripts/generate_dev_audit_pdf.py` |
| `generate_project_status_pdf.py` | Compact status PDF (needs `fpdf2`) | `pip install fpdf2 && python3 scripts/generate_project_status_pdf.py` |
| `validate_claude_dispatch.py` | Validate Claude dispatch queue | `python3 scripts/validate_claude_dispatch.py` |

**Install fpdf2 once if needed:**
```bash
pip install fpdf2 --quiet
```

---

### 3.6 Agent Queues — `.github/agent-queue/`

JSON task files for GitHub Copilot agent. Eight validated queue files (72 tasks total).

**Validate after any edit:**
```bash
python3 scripts/validate_agent_queue.py
# Must output: "validate_agent_queue: OK (72 tareas en 8 colas)"
```

**Queue files:**
- `tasks.json` — main wave 1
- `tasks-wave2.json`, `tasks-wave3.json`, `tasks-wave4-ia-content.json`
- `tasks-infra-dns-ssl.json`
- `tasks-api-integrations-research.json`
- `tasks-security-guardian.json`
- (others as added)

**Seed a queue to GitHub Issues** (requires human to trigger in GitHub UI):
Actions → **08 · Agentes · Sembrar cola Copilot** → `Run workflow` → `queue_file: <filename>.json` → `scope: all`

---

### 3.7 GSD SDK — `.gsd/sdk/`

TypeScript SDK with Vitest tests. Only relevant when modifying or extending GSD plans.

```bash
cd .gsd/sdk
npm install          # installs deps (overrides for hono vulnerabilities already in package.json)
npm test             # runs Vitest suite
npm run build        # compiles TypeScript → dist/
cd ../..             # return to workspace root
```

`npm audit` should return 0 vulnerabilities (Dependabot overrides applied April 2026).

---

### 3.8 Memory & Documentation — `memory/`

No tests — these are markdown knowledge files. Only concern is not breaking links or overwriting canonical sources.

**Before editing any file in `memory/`:**
1. Check `memory/content/README-CONTENT-INDEX-2026.md` for dependency map
2. If editing a session report, use ISO date prefix: `YYYY-MM-DD-description.md`
3. Do not create new files in `memory/commerce/` or `memory/ops/` without updating `TASKS.md`

---

## 4. Git Workflow for Cloud Agents

```bash
# 1. Always start from main, pull latest
git checkout main
git pull origin main

# 2. Create a feature branch with cursor/ prefix and -3b72 suffix
git checkout -b cursor/descriptive-name-3b72

# 3. Make changes. Before staging, verify:
git diff                          # check what changed
php -l <modified_file.php>        # lint any PHP you touched
git diff --cached                 # after staging — verify no secrets

# 4. Stage files individually (NEVER git add . or git add -A)
git add wp-content/themes/gano-child/functions.php

# 5. Commit with conventional format
git commit -m "feat(gano-child): add rate limiting to chat REST endpoint"

# 6. Push and open PR
git push -u origin cursor/descriptive-name-3b72
# Then create PR via ManagePullRequest tool
```

**Commit message format:** `[scope] type: short description` (< 72 chars)
Example: `feat(reseller): map PFID for Nucleo Prime plan`

**Before merge:** both `01 · CI · Sintaxis PHP` and `02 · CI · Escaneo secretos` must be green.

---

## 5. Security Checklist (Run at End of Session)

```bash
python3 scripts/security_session_reminder.py
```

Or manually verify:
- [ ] No `.env`, `wp-config.php`, `*.key`, `*.pem` staged
- [ ] `git diff --cached` shows no API keys, tokens, passwords
- [ ] `php -l` passed on all modified PHP files
- [ ] No new `eval()`, `exec()`, `system()` calls introduced
- [ ] All new PHP functions prefixed `gano_`
- [ ] All new form handlers include nonce + sanitize + escape

---

## 6. Known Environment Constraints

| Constraint | Detail |
|-----------|--------|
| No local WP stack | WordPress runs on GoDaddy server only; cannot run `wp eval` or test PHP hooks locally |
| No `wp-config.php` in repo | File lives on server only; never commit it |
| Self-hosted runner risk | `gano-godaddy-server` runner is connected to a **public** repo — do not trigger untrusted workflows; see `activeContext.md` P0 blocker |
| SSH secrets not in env | Deploy/verify workflows need 4 secrets set in GitHub UI; agents cannot set them |
| Elementor content not in git | Homepage copy lives in WordPress DB (postmeta); use `memory/content/homepage-copy-2026-04.md` as reference only |
| Phase plugins are sacred | `gano-phase1` through `gano-phase7-activator` must not be deleted until activated on server |

---

## 7. Updating This Skill

When you discover a new testing trick, workflow shortcut, or environment constraint, update this file **in the same PR as the change that revealed the need**. Use the following process:

1. Add the new knowledge to the most relevant section (sections 3.x for area-specific, section 6 for environment constraints)
2. If it is a new codebase area not yet covered, add a new `3.x` subsection following the same structure: what it contains → lint/test command → manual checklist → feature flags
3. Update the `## Metadata` block's `Last Updated` date
4. Commit as: `docs(skill): add [topic] to cloud-agent-starter runbook`
5. If the update is significant enough to affect Diego's workflow, also update `CLAUDE.md` table "Skills disponibles" and `AGENTS.md`

**Metadata — Last Updated:** 2026-04-11
