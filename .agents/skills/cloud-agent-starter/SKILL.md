# Skill: Cloud Agent Starter

## Metadata
- **Name**: cloud-agent-starter
- **Description**: Minimal bootstrap skill for Cloud agents working in `Gano-digital/Pilot`. Use it to decide how to start, what can be tested locally, which logins are required, and which checks to run before and after edits.
- **Scope**: Workspace (`/workspace`)
- **Dependencies**: `git`, `python3` (use this, not `python`, in cloud), `php`, `node >= 20` for `.gsd`, `gh` CLI (read-only auth already available in cloud), Docker optional for TruffleHog parity.

## Use this skill when
- The prompt says "how do I run this repo", "how do I test this", "cloud setup", "bootstrap", "smoke test", or "what should I run first".
- You are starting a task in this repo and need the fastest safe path to runtime evidence.
- You need to know whether a step is repo-local, cloud-safe, or blocked on human login.

## Read first
1. `TASKS.md`
2. `CLAUDE.md`
3. `.github/DEV-COORDINATION.md`
4. `.github/copilot-instructions.md`
5. If the task touches commerce, also read `.agents/skills/phase4-commerce/SKILL.md`

## Access and login matrix

| System | What a Cloud agent can do now | When login is required | Notes |
|--------|-------------------------------|------------------------|-------|
| Local git repo | Read, edit, lint, test, commit, push | Never | Default working mode. |
| GitHub | Use `gh` for read-only checks | No extra login in cloud | Good for `gh auth status`, `gh pr list`, `gh run list`, `gh run view --log`. |
| WordPress code in `wp-content/` | Edit and lint code | No | Repo code is available even when WordPress runtime is not. |
| WordPress admin / Elementor | Usually blocked | Yes, human-provided credentials or staging URL | Do not invent access. Many homepage/content tasks are human-only in wp-admin. |
| SSH deploy / server diff | Usually blocked | Yes, via env vars or CI secrets only | Use `GANO_SSH_*` locally or GitHub secrets `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH`. Never commit secrets. |
| GoDaddy RCC / Reseller | Usually blocked | Yes, human login | Do not invent PFIDs or product IDs. Use placeholders like `PENDING_RCC` when documentation says they are pending. |

## First 10 minutes
1. Confirm repo state:
   - `git status`
   - `git submodule update --init`
2. Confirm cloud GitHub auth if the task needs GitHub context:
   - `gh auth status`
3. Run the two fastest repo health checks:
   - `python3 scripts/validate_agent_queue.py`
   - `python3 scripts/validate_claude_dispatch.py`
4. Decide the area you are touching and run the matching workflow from the table below.

## Feature flags, mocks, and environment toggles
- There is **no central app-level feature flag system** in this repo.
- Treat workflow inputs such as `scope`, `queue_file`, `dry_run_merge`, and `upload_missing` as **operational toggles**, not product flags.
- If the WordPress runtime is missing in cloud, do **not** invent fake flags or fake PFIDs. Instead:
  - lint PHP;
  - validate JSON queues;
  - run Node/Python tool tests;
  - serve static tools locally when possible;
  - document which steps still require wp-admin, RCC, SSH, or staging.
- For GoDaddy Reseller work, staging-first is the rule. Repo-side checks can confirm code wiring, but order creation and RCC visibility still need a real external environment.

## Codebase areas and concrete test workflows

### 1. WordPress custom code (`wp-content/themes/gano-child`, `wp-content/mu-plugins`, `wp-content/plugins/gano-*`)

**What lives here**
- Theme code, custom plugins, MU plugins, storefront wiring, SEO/security logic.

**How to start the app**
- Full WordPress boot is only possible when a local WordPress stack and DB are available.
- If you truly have a local WP runtime, the documented local bootstrap is:
  - `cp wp-config-sample.php wp-config.php` (local only, never commit)
  - configure DB credentials in `wp-config.php`
  - load the local WordPress site with your own stack
  - activate `gano-phase3-content`
  - activate `gano-content-importer` only if you need the seeded SOTA pages, then deactivate it after use
- In most cloud sessions, you will **not** have that DB/runtime. Default to code-level checks instead of blocking.

**Cloud-safe test workflow**
1. Run targeted syntax checks on files you edited:
   - `php -l path/to/file.php`
2. Run bulk lint for all custom PHP before commit:
   - `rg --files wp-content/mu-plugins wp-content/themes/gano-child -g '*.php' | while IFS= read -r file; do php -l "$file"; done`
   - `rg --files wp-content/plugins -g '*.php' | rg '^wp-content/plugins/gano-' | while IFS= read -r file; do php -l "$file"; done`
3. If you changed JS/CSS and you have a real URL or local WP runtime, do a browser smoke test there.
4. If there is no working runtime, explicitly say the change was validated at code level only and why runtime proof is blocked.

**When to stop and escalate**
- The task is 100% Elementor/wp-admin content work.
- The change depends on DB state, menu assignments, plugin activation order, or production-only content.
- The task needs RCC, GoDaddy checkout, or SSH validation.

### 2. GitHub agent queues and workflow config (`.github/agent-queue`, `.github/workflows`, `scripts/validate_agent_queue.py`)

**What lives here**
- Copilot issue queues, workflow inputs, dedup markers, CI/Actions automation.

**Cloud-safe test workflow**
1. Validate queue JSON:
   - `python3 scripts/validate_agent_queue.py`
2. If the task touched workflow docs or queue routing, review:
   - `.github/COPILOT-AGENT-QUEUE.md`
   - `.github/workflows/README.md`
3. If the task depends on recent Actions behavior, inspect live runs:
   - `gh run list --limit 10`
   - `gh run view --log <run-id>`

**Operational toggles that matter here**
- `queue_file`
- `scope`
- `dry_run_merge`
- `upload_missing`

**Good completion signal**
- Validator prints `OK`.
- The changed workflow/queue behavior is documented in the matching `.md` file if the operator flow changed.

### 3. Claude dispatch and memory queue (`memory/claude`, `scripts/claude_dispatch.py`)

**What lives here**
- Local Claude task queue, handoff docs, replayable prompts, progress tracking.

**Cloud-safe test workflow**
1. Validate structure:
   - `python3 scripts/validate_claude_dispatch.py`
2. Inspect queue behavior:
   - `python3 scripts/claude_dispatch.py list`
   - `python3 scripts/claude_dispatch.py next`
3. If you changed task definitions or docs, make sure the commands still describe the queue accurately.

**Good completion signal**
- Validator prints `OK`.
- `list` and `next` return sane output without tracebacks.

### 4. GSD tooling (`.gsd/` and `.gsd/sdk/`)

**What lives here**
- The `get-shit-done` submodule plus the SDK and its test suites.

**Setup**
- `node -v` should be `>= 20`.
- If dependencies are missing, install them only inside the touched package:
  - `npm install` in `.gsd/`
  - `npm install` in `.gsd/sdk/`

**Cloud-safe test workflow**
- For `.gsd/` changes:
  - `npm test`
  - optional: `npm run test:coverage`
- For `.gsd/sdk/` changes:
  - `npm run build`
  - `npm test`
  - or split into `npm run test:unit` and `npm run test:integration`

**Good completion signal**
- The package you touched builds or tests cleanly.
- You do not need to run both `.gsd/` and `.gsd/sdk/` unless your diff touches both.

### 5. Ops Hub and repo-local static tools (`tools/gano-ops-hub`, `scripts/generate_gano_ops_progress.py`, `scripts/check_dns_https_gano.py`)

**What lives here**
- Static dashboard, progress JSON generation, repo health and DNS/HTTPS diagnostics.

**How to start**
1. Generate fresh data:
   - `python3 scripts/generate_gano_ops_progress.py`
2. Serve the static UI locally:
   - `cd tools/gano-ops-hub/public && python3 -m http.server 8765`
3. Open `http://127.0.0.1:8765/`

**Important**
- Do **not** use `file://` for the Ops Hub UI; it needs HTTP so the browser can `fetch()` JSON.

**Cloud-safe test workflow**
- Regenerate `progress.json`.
- Serve the static site.
- If the task touched DNS/HTTPS tooling, also run:
  - `python3 scripts/check_dns_https_gano.py`

### 6. Commerce / Reseller / RCC

**What lives here**
- Phase 4 storefront wiring, PFID references, cart URLs, RCC runbooks, checkout documentation.

**Read before touching it**
- `.agents/skills/phase4-commerce/SKILL.md`
- `memory/commerce/rcc-pfid-checklist.md`
- `TASKS.md` Phase 4 items

**Cloud-safe test workflow**
1. Validate repo-side code changes in the theme/plugin.
2. Run PHP lint if PHP changed.
3. If a real staging URL is available, do the staging browser flow first.
4. If staging is not available to the agent, stop at code-side validation and say exactly which external step is still pending.

**Never do this**
- Never guess PFIDs.
- Never switch checkout strategy away from GoDaddy Reseller just to make a test easier.
- Never claim checkout is verified without a real cart or RCC check.

## Quick decision table

| If you touched... | Run at minimum |
|-------------------|----------------|
| `wp-content/**/*.php` | `php -l` on edited files plus CI-parity bulk lint |
| `.github/agent-queue/**` | `python3 scripts/validate_agent_queue.py` |
| `memory/claude/dispatch-queue.json` or dispatch docs | `python3 scripts/validate_claude_dispatch.py` and `python3 scripts/claude_dispatch.py list` |
| `.gsd/**` | `npm test` in `.gsd/` |
| `.gsd/sdk/**` | `npm run build` and `npm test` in `.gsd/sdk/` |
| `tools/gano-ops-hub/**` or progress scripts | `python3 scripts/generate_gano_ops_progress.py` and serve `tools/gano-ops-hub/public` |
| Commerce / PFID / cart wiring | Repo-side lint + staging-first workflow from `phase4-commerce` |

## Do not waste time on these dead ends
- There is no single repo-root "start the whole app" command for Cloud agents.
- Many homepage and content tasks are split between repo code and wp-admin state; repo changes alone may not recreate production.
- GitHub Actions cannot modify GoDaddy DNS.
- The repo does not store WordPress admin logins, RCC credentials, or deploy secrets.

## How to update this skill when new runbook knowledge appears
When you discover a repeatable setup trick, staging workaround, or test shortcut:
1. Add the **exact command** or UI path, not a vague note.
2. Say whether the step is:
   - cloud-safe;
   - requires human login;
   - requires staging/production;
   - optional parity with CI.
3. Link the source of truth (`TASKS.md`, `memory/ops/*`, `memory/sessions/*`, `.github/*.md`, or an existing skill).
4. Keep the "First 10 minutes" section short. Move detail into the relevant codebase area instead of bloating the top.
5. Prefer workflows that produce evidence (`OK` validator output, build output, browser proof, logs) over prose-only advice.

## Success state
This skill is working if a new Cloud agent can answer all of these quickly:
- What can I test right now without credentials?
- Which codebase area owns my change?
- Which exact commands should I run before commit?
- Which parts still need wp-admin, staging, RCC, SSH, or a human?
