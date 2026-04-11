# Skill: Cloud Agent Starter

## Metadata
- **Name**: cloud-agent-starter
- **Description**: Minimal first-stop runbook for Cloud agents working in this repository. Use when a task requires practical setup, login checks, app/runtime expectations, feature-flag-style mocking, or concrete test commands.
- **Scope**: Workspace (`Gano-digital/Pilot`)
- **Dependencies**: GitHub CLI auth; optional WordPress, RCC, or SSH access supplied outside the repo

## Activation Signals
Automatically load this skill when the prompt contains ideas such as:
- "how do I run this repo"
- "how do I test this"
- "cloud agent setup"
- "wp-admin"
- "staging"
- "feature flag"
- "mock this"
- "what should I run first"

## Start Here (first 5 minutes)
1. Read the current project state:
   - `TASKS.md`
   - `CLAUDE.md`
   - `.github/DEV-COORDINATION.md`
2. Confirm the Cloud runtime:
   - `php -v`
   - `node -v`
   - `npm -v`
   - `gh auth status`
3. Check submodules and branch state:
   - `git submodule status`
   - `git status --short --branch`
4. Optionally probe WP-CLI:
   - `wp --version`
   - If this fails with `command not found`, treat that as normal for this Cloud environment.

## Reality Check (important)
- This repo is **not** a self-contained WordPress runtime. Database state, `uploads/`, Elementor content, active plugin settings, `wp-admin`, and production secrets live outside git.
- Do **not** search the repo for credentials. Never read `.env*` or `wp-config.php`.
- There is **no single `npm start` or `wp server` flow** for the whole product in Cloud by default.
- Default Cloud workflow is:
  1. edit code in git,
  2. run the best local lint/tests available here,
  3. use staging/production only if access is already provisioned.
- There is no general feature-flag framework in this repo. Safe "mock mode" usually means leaving external integrations unset so code falls back to no-op behavior.

## Codebase Area 1 — WordPress custom code

### Main paths
- `wp-content/themes/gano-child/`
- `wp-content/mu-plugins/gano-*.php`
- `wp-content/plugins/gano-*/`

### How to "start the app"
- In Cloud, assume you **cannot fully boot WordPress locally** unless the environment already has:
  - a local database,
  - a local-only `wp-config.php`,
  - a web server or prebuilt local WordPress stack.
- If a full runtime is truly required, follow the documented local bootstrap pattern from `README.md` / `SERGIO-ONBOARDING.md` on a provisioned machine:
  - initialize submodules,
  - create a local DB,
  - copy `wp-config-sample.php` to a local-only `wp-config.php`,
  - activate the required phase plugins in `wp-admin`.
- For normal Cloud work, treat staging or an already running WordPress environment as the runtime and use repo-local checks first.

### Fast checks
- Single file:
  - `php -l wp-content/themes/gano-child/functions.php`
- CI-equivalent custom PHP sweep:
  - `find wp-content/mu-plugins wp-content/themes/gano-child -name '*.php' -exec php -l {} \;`
  - `find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -exec find {} -name '*.php' -exec php -l {} \; \;`

### Testing workflow
1. Edit the target PHP/CSS/JS/template files.
2. If PHP changed, lint the touched files first.
3. If multiple custom PHP files changed, run the full custom PHP sweep.
4. If only CSS/JS changed but the page is rendered by a PHP template, still lint the related template or `functions.php`.
5. If the task needs rendered verification, use staging or an already provisioned WordPress env; do not pretend repo-only checks prove page rendering.

## Codebase Area 2 — Commerce / GoDaddy Reseller

### Main paths
- `wp-content/themes/gano-child/functions.php`
- `wp-content/themes/gano-child/templates/shop-premium.php`
- `wp-content/themes/gano-child/templates/page-ecosistemas.php`
- `wp-content/plugins/reseller-store/`
- `memory/commerce/rcc-pfid-checklist.md`

### Login and external systems
- Real checkout work may require:
  - `wp-admin` access,
  - Reseller Control Center (RCC) access,
  - Reseller Store settings such as PLID.
- Those credentials/settings are **outside git**. Only use them if they are already provided in the active environment or by a human.

### Safe mock / feature-flag behavior
- The repo already has a safe fallback:
  - unknown product IDs stay as `PENDING_RCC`,
  - `gano_rstore_cart_url()` returns `#` when PFID or PLID is missing.
- That is the correct mock mode for Cloud agents.
- Do **not** invent PFIDs, PLIDs, or reseller IDs.

### Fast checks
- Inspect current PFID and cart wiring:
  - `rg "PENDING_RCC|GANO_PFID_|gano_rstore_cart_url" wp-content/themes/gano-child/functions.php wp-content/themes/gano-child/templates`
- Lint after touching commerce PHP:
  - `php -l wp-content/themes/gano-child/functions.php`
  - `php -l wp-content/themes/gano-child/templates/shop-premium.php`
  - `php -l wp-content/themes/gano-child/templates/page-ecosistemas.php`

### Testing workflow
1. Confirm whether the task is static wiring or real checkout.
2. For static wiring, keep unknown PFIDs as `PENDING_RCC`.
3. Verify the touched template uses the shared cart helper rather than hardcoded URLs.
4. Lint the touched PHP files.
5. Only run browser checkout tests when staging/wp-admin/RCC access is already available.
6. If external access is missing, clearly report: "code wiring validated locally; real cart flow blocked by external RCC/wp-admin access."

## Codebase Area 3 — `.gsd/` and `.gsd/sdk/`

### When this area matters
- Only use these commands when the task touches the agent tooling, prompts, SDK, or test harness under `.gsd/`.
- Do **not** run these suites for ordinary WordPress/theme changes.

### Main paths
- `.gsd/`
- `.gsd/sdk/`

### Fast checks
- Root package:
  - `cd .gsd && npm test`
- SDK unit tests:
  - `cd .gsd/sdk && npm run test:unit`
- SDK integration tests:
  - `cd .gsd/sdk && npm run test:integration`

### Known Cloud caveats
- `.gsd/sdk` integration tests expect a local GSD binary at:
  - `~/.claude/get-shit-done/bin/gsd-tools.cjs`
- If that file is missing, integration tests can fail for environment reasons, not because of your code.
- The broad `.gsd` root test suite may also fail on existing environment/repo baseline issues such as non-executable scan scripts. Read the failing output before treating it as a regression.

### Testing workflow
1. If you changed `.gsd/sdk`, start with `npm run test:unit`.
2. Run `npm run test:integration` only when the required local GSD binary is present.
3. If you changed root `.gsd` tooling, run `npm test` and inspect whether failures are:
   - caused by your diff,
   - or caused by known environment assumptions.
4. For WordPress-only tasks, skip this area entirely.

## Codebase Area 4 — GitHub / Actions / Ops

### Main paths
- `.github/workflows/`
- `.github/DEV-COORDINATION.md`
- `.github/workflows/README.md`
- `memory/ops/`

### Login and inspection
- GitHub CLI is the main Cloud login surface:
  - `gh auth status`
- Useful read-only commands:
  - `gh run list --limit 5`
  - `gh run view <run-id> --log`
  - `gh pr status`

### Important limitation
- `gh` is read-only in this environment. Use it to inspect PRs, runs, and logs, not to mutate GitHub state.

### Testing workflow
1. If you touch workflow docs or CI config, read `.github/workflows/README.md`.
2. Run the closest local validation you can (PHP lint, Node unit tests, JSON validation, etc.).
3. After pushing, inspect the relevant workflow runs with `gh run list` / `gh run view --log`.
4. For deploy issues, cross-check the documented secrets and flow in `.github/DEV-COORDINATION.md` before assuming code is broken.

## Optional Full WordPress Bootstrap
- Use this only when a task truly requires local rendering and the environment is already provisioned for WordPress.
- The documented pattern is:
  1. `git submodule update --init`
  2. create a local database,
  3. copy `wp-config-sample.php` to a local-only `wp-config.php`,
  4. log into local `wp-admin`,
  5. activate `gano-phase3-content`,
  6. activate `gano-content-importer`, then deactivate it after seeding.
- Cloud agents usually should **not** choose this path unless the task explicitly requires it and the machine is already prepared.

## When You Discover New Runbook Knowledge
- Keep this skill short and practical.
- Add new knowledge only if it is one of these:
  - a command that reliably works in Cloud,
  - a recurring environment limitation,
  - a safe mock/default for external systems,
  - a faster or more trustworthy test workflow.
- Put the note under the correct codebase area instead of a generic dump section.
- Prefer exact commands, exact file paths, and exact failure modes.
- Remove stale instructions when the environment changes (for example, if WP-CLI becomes available or a one-command local bootstrap is added).
