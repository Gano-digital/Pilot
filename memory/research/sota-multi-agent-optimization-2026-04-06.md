# SOTA Multi-Agent Optimization Strategy — Gano Digital (2026-04-06)

**Objetivo:** Alinear Claude Code, Cursor, VS Code y Antigravity en un ecosistema coherente, maximizando SOTA capabilities y minimizando fricción.

---

## 1. Landscape Actual (Baseline Assessment)

### 1.1 Skills Instalados (15 total)

| Skill | Status | Última actualización | Crítico |
|-------|--------|---------------------|---------|
| `gano-wp-security` | Activo | Fase 2 (Mar 2026) | 🔴 Sí |
| `gano-fase4-plataforma` | Referencia | Abr 2026 | 🟡 Parcial |
| `gano-github-copilot-orchestration` | Activo | Abr 2026 | 🔴 Sí |
| `gano-mcp-and-cursor-tooling` | Desactualizado | Mar 2026 | 🟡 Parcial |
| `gano-multi-agent-local-workflow` | Activo | Mar 2026 | 🔴 Sí |
| `gano-session-security-guardian` | Activo | Abr 2026 | 🟡 Parcial |
| `gano-github-projects-board` | Activo | Abr 2026 | 🟡 Parcial |
| `gano-cursor-models` | Referencia | Feb 2026 | 🔴 **DESACTUALIZADO** |
| `gano-cpanel-ssh-management` | Ops | Mar 2026 | 🟡 Parcial |
| Otros (7 skills) | Referencia/Legacy | Q1 2026 | 🟢 No crítico |

**Gap crítico:** `gano-cursor-models` está desactualizado y necesita refresh con Cursor v0.45+ capabilities.

### 1.2 Dependencias Clave

```
Node.js:           >=20.0.0 (actual: check con `node --version`)
Claude Agent SDK:  v0.2.84 (@gsd-build/sdk) — UP TO DATE vs v0.2.92 en monorepo
TypeScript:        v5.7.0 (SOTA)
Vitest:            v4.1.2 (SOTA para testing)
Anthropic SDK:     En SDK (needs audit)
```

**Gap detectado:** Agent SDK en SDK (v0.2.84) vs dependabot mostró v0.2.92. Actualizar SDK.

### 1.3 Configuración de Agentes

#### Claude Code (.claude/projects/*)
- ✅ **CLAUDE.md**: Completo y actualizado (2026-04-02 handoff integrado)
- ✅ **Dispatch queue**: Funcional (`memory/claude/dispatch-queue.json`)
- ✅ **Memory system**: Operativo (sessiones persistentes)
- ❌ **MCP servers**: Ninguno registrado formalmente (implícito via CLI)

#### Cursor (.cursor/*)
- ✅ **Memory system**: 4 archivos (`activeContext.md`, `projectBrief.md`, `techContext.md`, `progress.md`)
- ✅ **Hooks.json**: PHP lint básico configurado
- ❌ **MCP servers**: Vacío (`mcpServers: {}`) — **CRÍTICO: No hay integración GitHub, filesystem**
- ❌ **Instrucciones personalizadas**: No encontradas (`.cursor/instructions.md` falta)

#### VS Code (.vscode/*)
- ✅ **Extensions**: Recomendadas incluyen Python, PHP, YAML, GitHub Actions, PowerShell
- ❌ **Configuración avanzada**: settings.json mínimo (solo 3 propiedades)
- ❌ **Tasks.json**: Existe pero no vinculado a MCP/Agent workflows
- ❌ **Launch.json**: No hay (debugging setup incompleto)
- ❌ **Workspace settings**: Falta optimización por monorepo (WP + .gsd dual)

#### Antigravity
- ❌ **Status desconocido**: No hay archivos de configuración hallados
- ❌ **Integración con ecosistema**: No documentada
- ❌ **Role definido**: Falta claridad

### 1.4 Integraciones Faltantes (SOTA 2026)

| Integración | Criticidad | Alternativa SOTA | Esfuerzo |
|------------|-----------|------------------|----------|
| **GitHub MCP en Cursor** | 🔴 Alta | Oficial: `github.com/modelcontextprotocol/servers` | ~2h setup |
| **Filesystem MCP en Cursor** | 🔴 Alta | Oficial: `mcp-server-filesystem` | ~1h |
| **VS Code Workspace Profiles** | 🟡 Media | Nativo desde v1.94 | ~1h |
| **Cursor Rules (v0.45+)** | 🔴 Alta | `.cursor/rules` o `.cursorrules` | ~3h (traducir skills) |
| **Claude Code Async Agents** | 🟡 Media | Agent SDK v0.2.92+ | ~2h config |
| **GitHub Copilot Chat en VS Code** | 🟢 Baja | Native extension | 30m |

---

## 2. SOTA State per Agent (2026-04-06)

### 2.1 Claude Code (AI Assistant Model)

**Current:** Claude Haiku 4.5 (context window 100K, tools: full Anthropic ecosystem)

**SOTA Capabilities:**
- ✅ File system tools (Read, Write, Edit, Bash, Glob, Grep)
- ✅ Git operations via Bash + context awareness
- ✅ Dispatch queue orchestration (`scripts/claude_dispatch.py`)
- ✅ Multi-language code analysis (PHP, JS, Python, SQL)
- ✅ Memory persistence (`.claude/projects/*`)
- ✅ Security sandbox (implicit)

**Gaps & Optimizations:**
1. **Fine-tune dispatch prompt** — Current: generic. Recomendado: Add Gano-specific context (Reseller workflow, PFID mapping, security checklist)
2. **Formalize MCP integration** — Currently implicit via CLI. Crear manifest formal.
3. **Context compression** — Handoff documents large. Implementar summarization strategy.

**Recommendation:** `claude-opus-4` para tasks complejas (Phase 4 automation), mantener Haiku para dispatch/lightweight.

---

### 2.2 Cursor (IDE Embedding + Chat)

**Current:** v0.44 (probable, based on extensions.json pattern). Upgrade to **v0.45+** required.

**SOTA Capabilities (v0.45+):**
- ✅ Inline chat (edits + suggestions)
- ✅ Memory system (`.cursor/memory/*`)
- ✅ Hooks (afterFileEdit, beforeShellExecution)
- ✅ Custom models support
- ✅ Codebase indexing (~50GB WP supported)
- ❌ **Cursor Rules** (NEW in v0.45) — Define custom instructions per project

**Gaps (CRITICAL):**

| Gap | Impact | Fix |
|-----|--------|-----|
| **No MCP servers** | Can't fetch GitHub repos, filesystem context | Add `github-mcp`, `filesystem-mcp` to `.cursor/mcp.json` |
| **No .cursorrules** | Using generic model, not Gano-aware | Create `.cursor/rules` with Gano patterns (Reseller, PFID, security) |
| **Memory files stale** | activeContext.md may be outdated | Sync with `CLAUDE.md` on each session start |
| **No Antigravity link** | Can't coordinate with other agents | Define in `.cursor/memory/agents.md` |

**Action Items (PRIORITY: HIGH):**
1. Update Cursor to v0.45+
2. Populate `.cursor/mcp.json` with:
   ```json
   {
     "mcpServers": {
       "github": {
         "command": "npx",
         "args": ["@modelcontextprotocol/server-github"]
       },
       "filesystem": {
         "command": "node",
         "args": ["${workspaceFolder}/.mcp/servers/filesystem.js"]
       }
     }
   }
   ```
3. Create `.cursor/rules` file (see Section 4.1)
4. Add post-fetch hook para auto-sync memory files

---

### 2.3 VS Code (Editor Substrate)

**Current:** v1.99+ (inferred from extensions.json)

**SOTA Capabilities:**
- ✅ Workspace profiles (v1.94+) — Can define "PHP/WordPress" vs ".gsd/SDK" profiles
- ✅ Extensions ecosystem — 8 recommended extensions loaded
- ✅ Task automation — `.vscode/tasks.json` exists
- ✅ Git integration — Native support
- ❌ **MCP integration** — Not available in VS Code natively (via Cursor or Claude Code instead)

**Gaps (MEDIUM):**

| Gap | Impact | Fix |
|-----|--------|-----|
| **Minimal settings** | No performance, formatting, linting rules | Expand `settings.json` with WordPress + Node.js rules |
| **No workspace profiles** | Context switching manual | Create 2 profiles: `wordpress.code-workspace` + `sdk.code-workspace` |
| **launch.json missing** | Can't debug PHP scripts, Node.js CLI | Add PHP XDebug + Node remote debugging configs |
| **tasks.json orphaned** | Not linked to GitHub Copilot or Cursor | Extend with MCP-aware task hooks |

**Action Items (PRIORITY: MEDIUM):**
1. Create `.vscode/settings.json` (expanded — see Section 4.2)
2. Create `.vscode/launch.json` with PHP + Node debug configs
3. Create `gano.code-workspace` with dual roots (WP + .gsd)
4. Update `.vscode/tasks.json` with MCP-aware dispatch hooks

---

### 2.4 Antigravity (Unknown)

**Current:** No configuration found in repo.

**Speculation:** May be:
- GitHub Actions workflow automation
- Third-party CI/CD tool
- Custom Python/Node.js orchestrator
- Remote agent (not in `.claude/worktrees/*`)

**Recommendation:** **Clarify role & location with Diego first.**

---

## 3. Alignment Strategy (Multi-Agent Orchestration)

### 3.1 Shared Context Model

**Goal:** All agents reference same source of truth.

```
Hierarchy (priority):
1. memory/sessions/2026-04-02-reporte-handoff-godaddy-api-reseller-whmcs.md
   ↓ (feeds)
2. CLAUDE.md
   ↓ (feeds)
3. .claude/projects/*/MEMORY.md (auto-generated, per agent)
4. .cursor/memory/activeContext.md (manual, per session)
5. .vscode/.code-workspace (static, per profile)
```

**Action:** Create `.claude/context-sync.sh` script:
```bash
#!/bin/bash
# Sync context from CLAUDE.md → cursor/memory/activeContext.md
# Run before starting any agent session
CLAUDE_SUMMARY=$(grep -A 20 "## Próximos pasos" CLAUDE.md)
echo "# Auto-synced $(date)" > .cursor/memory/activeContext.md
echo "$CLAUDE_SUMMARY" >> .cursor/memory/activeContext.md
```

### 3.2 Dispatch Queue Unification

**Current state:**
- Claude has `memory/claude/dispatch-queue.json`
- Cursor has no formal queue
- VS Code tasks.json exists but unlinked
- Antigravity: unknown

**Proposed unified structure:**
```
.claude/dispatch-unified.json
├── claude-tasks: [] (current dispatch)
├── cursor-tasks: [] (inline edits, chat prompts)
├── vscode-tasks: [] (Run Task commands)
└── antigravity-tasks: [] (if applicable)
```

Run `scripts/claude_dispatch.py` every 6h to merge & execute queue.

### 3.3 Security/Compliance Sync

**Shared checklist:** All agents follow `memory/ops/security-end-session-checklist.md`

**Per-agent enforcement:**
- Claude: Post-session hook (via dispatch)
- Cursor: `.cursor/hooks.json` → `afterFileEdit` + memory cleanup
- VS Code: Task shortcut `Ctrl+Shift+P` → "Gano Security Guardian"
- Antigravity: TBD

---

## 4. Recommended Implementations (Priority Order)

### 4.1 CRITICAL: Cursor Rules (`.cursor/rules`)

**Effort:** 3–4 hours (translate skills + test)

**Content:**
```
# Gano Digital — Cursor Rules
## Product Model
- **Canónico:** GoDaddy Reseller Store + RCC checkout (WordPress vitrina)
- **API:** Developer Portal REST (optional, automation only)
- **Good as Gold:** Only when API purchases (debits prepago account)
- **Phase 4 focus:** Reseller catalog, PFID mapping, checkout smoke test

## Code Patterns
- PHP: WordPress hooks, MU plugins, nonce CSRF, transient rate limiting
- JS: Elementor events, chat websocket, React-like state (no frameworks)
- CSS: Token-based (--gano-gold, --gano-blue), BEM naming, Elementor classes

## Security Guardrails
- 🚫 Never commit API keys, GoDaddy secrets, or Good as Gold credentials
- ✅ Always use env vars, .gitignore, server-side handlers
- ✅ Verify nonce on form submission, rate limit by IP
- ✅ Sanitize input (sanitize_*, wp_kses_*), escape output (esc_*)

## MCP Integration
- Use GitHub MCP to fetch issues, PRs, branch contexts
- Use Filesystem MCP for read-only file exploration
- Avoid direct bash for sensitive operations

[Copy remaining sections from relevant skills...]
```

**Implementation:**
```bash
# Extract rules from skills
cat .gano-skills/gano-wp-security/README.md \
    .gano-skills/gano-github-copilot-orchestration/README.md \
    >> .cursor/rules

# Test: Cursor should auto-suggest fixes based on rules
```

---

### 4.2 HIGH: VS Code Expanded Configuration

**Effort:** 1–2 hours

**`.vscode/settings.json` (extended):**
```json
{
  "python.terminal.executeInFileDir": false,
  "task.quickOpen.detail": true,
  "task.quickOpen.skip": false,

  // PHP/WordPress
  "php.validate.executablePath": "/usr/bin/php",
  "php.suggest.basic": true,
  "intelephense.environment.phpVersion": "8.2",

  // JavaScript/Node
  "javascript.format.semicolons": "insert",
  "javascript.format.insertSpaceAfterSemicolonInForStatements": true,
  "typescript.tsdk": "node_modules/typescript/lib",

  // Editor defaults
  "editor.defaultFormatter": "esbenp.prettier-vscode",
  "[php]": { "editor.defaultFormatter": "bmewburn.vscode-intelephense-client" },
  "[json]": { "editor.defaultFormatter": "esbenp.prettier-vscode" },
  "editor.formatOnSave": true,
  "files.exclude": { "**/.claude": true, "**/.cursor": true },

  // Git
  "git.ignoreLimitWarning": true,
  "git.autorefresh": true,

  // Markdown
  "[markdown]": { "editor.wordWrap": "on", "editor.rulers": [80] }
}
```

**`.vscode/launch.json` (new):**
```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Listen for XDebug",
      "type": "php",
      "port": 9003,
      "pathMapping": { "/var/www/html": "${workspaceFolder}" },
      "xdebugSettings": { "max_data": 65535 }
    },
    {
      "name": "Node CLI Debug",
      "type": "node",
      "request": "launch",
      "program": "${workspaceFolder}/.gsd/sdk/dist/cli.js",
      "args": [],
      "cwd": "${workspaceFolder}"
    }
  ]
}
```

**`gano.code-workspace` (new):**
```json
{
  "folders": [
    { "path": ".", "name": "WordPress" },
    { "path": "./.gsd", "name": "GSD SDK" }
  ],
  "settings": {
    "[php]": { "editor.defaultFormatter": "bmewburn.vscode-intelephense-client" },
    "files.exclude": { "**/.cursor": true }
  }
}
```

---

### 4.3 HIGH: Cursor MCP Servers

**Effort:** 1–2 hours (copy official MCP configs)

**`.cursor/mcp.json` (populated):**
```json
{
  "mcpServers": {
    "github": {
      "command": "npx",
      "args": ["@modelcontextprotocol/server-github"],
      "env": {
        "GITHUB_PERSONAL_ACCESS_TOKEN": "${env:GITHUB_TOKEN}"
      }
    },
    "filesystem": {
      "command": "node",
      "args": [
        "${workspaceFolder}/.mcp/servers/filesystem-server.js"
      ],
      "env": {
        "ALLOWED_PATHS": "${workspaceFolder}"
      }
    }
  }
}
```

**Setup:**
```bash
mkdir -p .mcp/servers
npm install @modelcontextprotocol/server-github --save-dev
# Copy filesystem server stub or use reference impl
echo "export GITHUB_TOKEN=ghp_..." >> .env.local
```

---

### 4.4 MEDIUM: Unified Dispatch Queue

**Effort:** 2–3 hours

**New file: `.claude/dispatch-unified.json`**
```json
{
  "version": 1,
  "metadata": {
    "created": "2026-04-06T00:00:00Z",
    "coordinator": "claude-multi-agent",
    "syncInterval": "6h"
  },
  "tasks": {
    "claude": [
      { "id": "c-001", "status": "pending", "prompt": "..." }
    ],
    "cursor": [
      { "id": "cur-001", "type": "inline-edit", "file": "...", "instruction": "..." }
    ],
    "vscode": [
      { "id": "vsc-001", "command": "Run Task", "task": "..." }
    ],
    "antigravity": [
      { "id": "ag-001", "role": "TBD", "task": "..." }
    ]
  },
  "dependencies": {
    "c-001": [],
    "cur-001": ["c-001"]
  }
}
```

---

### 4.5 MEDIUM: Claude Code Dispatch Prompt Enhancement

**Effort:** 1–2 hours

**Current:** Generic dispatch prompt in `scripts/claude_dispatch.py`

**Enhancement:** Add Gano context block:
```markdown
## Gano Digital Context

### Product Model
[From CLAUDE.md §Product Model]

### Phase 4 Checklist (if dispatch involves checkout)
- [ ] PFID mapping verified
- [ ] Nonce CSRF on forms
- [ ] Rate limiting in place
- [ ] RCC catalog synced

### Recommended Models
- **Phase 4 Automation:** `claude-opus-4` (complex logic)
- **Content/Docs:** `claude-haiku-4-5` (fast, cost-effective)
- **Debugging:** `claude-opus-4` (reasoning)

[Rest of dispatch logic...]
```

---

## 5. Antigravity Clarification (TBD)

**Diego: Please confirm:**
1. **What is Antigravity?** (GitHub Actions, third-party CI, custom tool, cloud agent?)
2. **Where is its config?** (repo path, cloud platform, external service?)
3. **Role in Gano workflow?** (post-merge automation, testing, deployment, content sync?)
4. **Integration needs?** (GitHub webhook, dispatch queue, shared memory?)

**Once clarified, will create:**
- `.antigravity/config.json` or equivalent
- Integration points with Claude/Cursor/VS Code queue
- Security & permission model

---

## 6. Implementation Roadmap (Priority)

| Semana | Task | Owner | Dependency |
|--------|------|-------|------------|
| W1 (0–3h) | Cursor upgrade + .cursorrules | Cursor | None |
| W1 (1–2h) | VS Code expanded settings + launch.json | VS Code | None |
| W2 (1–2h) | Cursor MCP servers setup | Cursor | GitHub token |
| W2 (2–3h) | Unified dispatch queue (JSON schema) | Claude | None |
| W3 (1–2h) | Claude dispatch prompt enhancement | Claude | Dispatch JSON |
| W3 | **Antigravity clarification + integration** | Diego | **BLOCKING** |
| W4 | Test full multi-agent workflow | All | All above |
| W4 | Document runbook per agent | All | All above |

---

## 7. SOTA Maintenance (Quarterly)

**Checklist (every 3 months):**
- [ ] Update Claude Agent SDK (check anthropic-ai/claude-agent-sdk releases)
- [ ] Update Cursor to latest (weekly updates typical)
- [ ] Review VS Code extensions for deprecations
- [ ] Audit .gano-skills for relevance (mark "deprecated" if unused >6mo)
- [ ] Sync `.cursor/memory/*` with `CLAUDE.md`
- [ ] Test MCP servers (GitHub API quota, filesystem permissions)
- [ ] Review security-guardian checklist for new patterns

---

## 8. Success Criteria (Multi-Agent Coherence)

✅ **All agents share:**
- Same CLAUDE.md context
- Same security checklist (end-of-session)
- Same dispatch queue structure
- Same PFID/Reseller understanding

✅ **Cursor-specific:**
- Can fetch GitHub issues inline via MCP
- Suggests fixes matching `.cursor/rules`
- Memory files auto-sync with Claude

✅ **VS Code-specific:**
- Can switch contexts via workspace profiles
- Can run Gano-specific tasks via `Ctrl+Shift+P`
- PHP debugging works via XDebug

✅ **Workflow:**
- A task in Claude dispatch → Cursor picks it up → VS Code executes → Results logged
- No manual context-passing between agents
- Security checklist runs on agent exit (automated)

---

## 9. Estimated Total Effort

| Phase | Hours | Notes |
|-------|-------|-------|
| Planning + exploration | 4 | **Done** |
| Cursor rules (critical) | 3–4 | High impact, translating skills |
| VS Code config + launch.json | 1–2 | Straightforward |
| Cursor MCP setup | 1–2 | Mostly configuration |
| Unified dispatch queue | 2–3 | Schema design + merge logic |
| Claude dispatch enhancement | 1–2 | Prompt engineering |
| **Testing & docs** | 3–4 | Integration testing, runbooks |
| **Antigravity integration** | 3–5 | TBD (depends on clarification) |
| **TOTAL (excluding Antigravity)** | **15–20h** | Can be parallelized |

---

## 10. Next Steps

1. **Diego reviews this doc** (15 min)
2. **Clarify Antigravity** (BLOCKING — 30 min call)
3. **Approve prioritization** (Diego) — revise roadmap if needed
4. **Parallel execution** (4 independent work streams):
   - Cursor rules (Cursor agent)
   - VS Code config (VS Code or Claude)
   - Dispatch queue schema (Claude)
   - MCP setup (Cursor agent)
5. **Integration testing** (all agents)
6. **Runbook per agent** (documentation)
7. **Quarterly review cycle** (automated via CronCreate)

---

**Document owner:** Claude Haiku 4.5
**Last updated:** 2026-04-06
**Status:** READY FOR APPROVAL
