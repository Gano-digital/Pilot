# Skill: Cloud Agent Quickstart

## Metadata
- **Name**: cloud-agent-quickstart
- **Description**: Minimal starter for Cloud agents — how to set up, lint, test, and validate changes in this WordPress codebase without a running server.
- **Scope**: Workspace (`Gano-digital/Pilot`)
- **Last Updated**: 2026-04-11

## Activation Signals
Read this skill when:
- You are a Cloud agent starting work on this repo for the first time.
- You need to lint, test, or validate PHP/JS/CSS changes.
- You are unsure what CI checks your PR must pass.
- You want to know which files are safe to edit vs off-limits.

---

## 1. Environment Bootstrap

Cloud agents run on Ubuntu with no WordPress runtime. The repo is a **partial WP tree** (no `wp-admin/`, no `wp-includes/`, no database). You work on custom code only.

### 1.1 Install PHP (if missing)

```bash
sudo apt-get update && sudo apt-get install -y php-cli
php -v   # expect 8.x
```

### 1.2 Install Node (if missing, for `.gsd/` tests only)

```bash
# Node is usually pre-installed; if not:
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt-get install -y nodejs
node -v && npm -v
```

### 1.3 Init submodules

```bash
git submodule update --init --recursive
```

This pulls `.gsd/` (get-shit-done SDK with Vitest tests).

### 1.4 No secrets required for lint/test

- `wp-config.php` is **never** in the repo. Do not create it.
- `.env` files are gitignored. No API keys needed for CI-equivalent checks.
- If your task requires GoDaddy API credentials or SSH keys, ask Diego to add them via the Cursor Dashboard (Cloud Agents → Secrets). These are injected as env vars.

---

## 2. Codebase Areas & What You Can Edit

| Area | Path(s) | Editable? | Notes |
|------|---------|-----------|-------|
| **Child theme** | `wp-content/themes/gano-child/` | ✅ Yes | `functions.php`, templates, JS, CSS |
| **MU-plugins** | `wp-content/mu-plugins/gano-security.php`, `gano-seo.php` | ✅ Carefully | Site-wide impact — review mandatory |
| **Gano plugins** | `wp-content/plugins/gano-*/` | ✅ Yes | Phase installers, reseller enhancements, etc. |
| **Scripts** | `scripts/` | ✅ Yes | Python/PHP automation |
| **Memory / docs** | `memory/`, `.cursor/memory/` | ✅ Yes | Session notes, research, context |
| **CI workflows** | `.github/workflows/` | ⚠️ Ask first | Changes affect all contributors |
| **Third-party plugins** | `wp-content/plugins/elementor/`, `wordfence/`, etc. | ❌ No | Vendor code, not ours |
| **WP core** | `wp-admin/`, `wp-includes/`, `wp-config.php` | ❌ Never | Not in repo; never create |
| **Phase plugins** | `wp-content/plugins/gano-phase*/` | ⚠️ Never delete | Installers — read `CLAUDE.md` before touching |

### Prefixing rule

All new PHP functions **must** use the `gano_` prefix. All CSS custom properties use `--gano-*`. JS globals go under `window.ganoApp`.

---

## 3. Testing Workflows by Area

### 3.1 PHP Lint (reproduces CI workflow `01`)

This is the gate every PR must pass. Run it before pushing.

```bash
# Lint MU-plugins + child theme
find wp-content/mu-plugins wp-content/themes/gano-child \
  -name '*.php' -exec php -l {} \;

# Lint gano-* plugins
find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -print0 \
  | xargs -0 -I{} find {} -name '*.php' -exec php -l {} \;
```

**Pass criteria**: every file prints `No syntax errors detected`. Any `Parse error` = CI will reject your PR.

### 3.2 Secret Scan (reproduces CI workflow `02`)

CI runs TruffleHog in Docker. Cloud agents should do a quick manual check:

```bash
# Pre-commit check: make sure no secrets leak
git diff --cached | grep -iE '(password|secret|token|api.key|private.key)' || echo "Clean"
```

For full TruffleHog (if Docker is available):

```bash
for target in wp-content/mu-plugins wp-content/themes/gano-child wp-content/plugins/gano-*; do
  docker run --rm -v "$PWD:/pwd" -w /pwd \
    trufflesecurity/trufflehog:latest \
    filesystem "/pwd/$target" --only-verified
done
```

### 3.3 GSD / Vitest (JavaScript SDK tests)

Only relevant if you modify files under `.gsd/`:

```bash
cd .gsd && npm ci && npm test
```

### 3.4 Python Scripts

Scripts in `scripts/` are standalone. Test individually:

```bash
# Example: validate dispatch queue structure
python3 scripts/validate_claude_dispatch.py

# Example: validate Copilot agent queue
python3 scripts/validate_agent_queue.py

# PDF generators need fpdf2
pip install fpdf2
python3 scripts/generate_project_status_pdf.py
```

### 3.5 Template / Theme PHP (no runtime)

Without a WordPress server, you cannot render pages. Instead:

1. **PHP lint** (§3.1) catches syntax errors.
2. **Read the template** and trace the function calls — verify every `esc_html()`, `esc_attr()`, `esc_url()` is in place.
3. **Search for anti-patterns**:
   ```bash
   # Should return zero results in your diff:
   rg 'echo \$' wp-content/themes/gano-child/ --glob '*.php'
   rg '\$wpdb->query\(' wp-content/themes/gano-child/ --glob '*.php'
   ```

### 3.6 CSS / JS (no browser)

- **CSS**: validate tokens use `var(--gano-*)`. No Tailwind classes.
- **JS**: must be vanilla ES6+. No jQuery. Check with:
  ```bash
  rg '\$\(' wp-content/themes/gano-child/js/ --glob '*.js'
  # Should return zero jQuery-style selectors in new code
  ```

---

## 4. Security Checklist (apply to every PR)

Run through this before committing:

- [ ] All user inputs sanitized: `sanitize_text_field()`, `sanitize_email()`, `absint()`
- [ ] All outputs escaped: `esc_html()`, `esc_attr()`, `esc_url()`
- [ ] Forms include nonce: `wp_nonce_field()` + `check_admin_referer()`
- [ ] SQL uses `$wpdb->prepare()` — never raw queries
- [ ] No hardcoded secrets, tokens, API keys, or passwords
- [ ] `git diff --cached` shows no credential leaks
- [ ] New functions prefixed with `gano_`

---

## 5. Git & PR Workflow

### Branch naming

```
cursor/<descriptive-name>-<suffix>
```

### Commit format

```
[Phase N] Type: Brief description

Body with context (what, why, testing done).
```

Types: `Feat`, `Fix`, `Refactor`, `Docs`, `Chore`, `Security`

### CI checks that run on PR

| Workflow | File | What it does |
|----------|------|--------------|
| PHP Lint | `php-lint-gano.yml` | `php -l` on `mu-plugins/`, `gano-child/`, `gano-*/` plugins |
| Secret Scan | `secret-scan.yml` | TruffleHog on custom paths (only verified secrets) |
| Labeler | `labeler.yml` | Auto-labels PR by changed paths |

Both PHP Lint and Secret Scan must pass before merge.

### Never push to `main` directly

Always create a feature branch and open a PR.

---

## 6. Key Files to Read Before Big Changes

| Priority | File | Why |
|----------|------|-----|
| 1 | `TASKS.md` | Current phase, sprint items, what is blocked |
| 2 | `CLAUDE.md` | Full project context, stack, plugins, preferences |
| 3 | `AGENTS.md` | Universal agent rules, do/don't lists |
| 4 | `.github/DEV-COORDINATION.md` | Git ↔ server ↔ local truth hierarchy |
| 5 | `.cursor/memory/activeContext.md` | What the last agent was working on |

---

## 7. Common Gotchas for Cloud Agents

| Gotcha | Explanation |
|--------|-------------|
| **No WP runtime** | You cannot run `wp-cli`, `php index.php`, or start Apache. Validate via lint + code review. |
| **Phase plugins are sacred** | Never delete `gano-phase*` plugins. They are one-shot installers for production. |
| **Reseller, not Wompi** | Commerce goes through GoDaddy Reseller Store. Legacy payment plugins are not the priority. |
| **No Tailwind, no jQuery** | CSS = vanilla + `--gano-*` tokens. JS = vanilla ES6+. |
| **MU-plugins = site-wide** | A syntax error in `gano-security.php` takes down the entire site. Triple-check. |
| **Español for Diego** | Communication with Diego is always in Spanish. Code comments also in `es-CO`. |
| **Commits in Spanish or English** | Conventional commits, either language is fine. |

---

## 8. Mocking & Feature Flags

This codebase does not use a formal feature-flag system. Conditional behavior is controlled by:

### PHP constants (in `wp-config.php` on the server)

```php
// These are defined on the server, not in git. You may reference but not create them.
define('GANO_PFID_HOSTING_ECONOMIA', 'PENDING_RCC');
define('GANO_RESELLER_BASE', 'https://reseller.godaddy.com/cart');
```

If your code depends on a constant, guard it:

```php
if ( defined('GANO_RESELLER_BASE') ) {
    $base = GANO_RESELLER_BASE;
} else {
    $base = 'https://reseller.godaddy.com/cart'; // safe default
}
```

### `wp_options` (database, not in git)

Options like `gano_reseller_shopper_id` live in the DB. When writing code that reads options, always provide a fallback:

```php
$shopper_id = get_option('gano_reseller_shopper_id', '');
```

### Environment variables (Cloud Agent secrets)

For scripts or API calls that need credentials:

```bash
# Set in Cursor Dashboard → Cloud Agents → Secrets
# Accessed as env vars:
echo $GANO_GODADDY_API_KEY   # only if API work is needed
echo $GANO_SSH_KEY            # only if deploy work is needed
```

---

## 9. Updating This Skill

This skill is a living document. Update it when:

- You discover a new lint command, test trick, or workaround.
- A new CI workflow is added (update §5 table).
- A new codebase area is created (update §2 table).
- You find a gotcha that cost you time (add to §7).
- Feature flags or environment patterns change (update §8).

### How to update

1. Edit this file at `.agents/skills/cloud-agent-quickstart/SKILL.md`.
2. Add your entry to the relevant section.
3. Bump the `Last Updated` date in the Metadata block.
4. Commit with message: `Docs: update cloud-agent-quickstart skill — <what changed>`

### What makes a good addition

- **Concrete**: include the exact command, file path, or code snippet.
- **Tested**: you ran it and it worked (or failed in an instructive way).
- **Scoped**: fits into one of the existing sections, or justifies a new one.

Bad additions: vague advice ("be careful with security"), duplicates of `CLAUDE.md` content, or long prose without actionable steps.
