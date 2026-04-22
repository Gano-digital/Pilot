# Gano Digital — IDE Sync & Workspace Configuration

> **Status:** ✅ **LIVE** — Bidirectional sync between Cursor, Antigravity, and second brain active

---

## Quick Start

### **Open in Cursor**
```bash
cursor C:\Users\diego\Downloads\Gano.digital-copia
```
→ Loads `.cursor-workspace.json` with current priorities, P0-P3 tasks, recent files

### **Open in Antigravity**
```bash
antigravity C:\Users\diego\Downloads\Gano.digital-copia
```
→ Loads `.antigravity-workspace.json` with server connections, deployment focus

### **Both IDEs Auto-Sync To**
- `wiki/dev-sessions/` — Session captures, changes, commands, commits
- `C:\Users\diego\.claude\projects\C--Users-diego\memory\` — Central memory (project state, deployment status)

---

## Current Workspace State

### **Phase**
**Fase 4 - Integración GoDaddy Reseller**

### **Blockers**
| Status | Item | Action |
|--------|------|--------|
| 🔴 BLOCKING | PR #279 merge to main | Admin only (branch protection) |
| 🟡 READY | Production validation | Depends on PR merge + workflow #04 |
| 🟢 READY | Reseller integration | Can start independently |

### **Recent Work**
- ✅ Auditoría UX/Visual completada (6 bloques)
- ✅ CTA Registro componente + 4 integraciones
- ✅ CSS Foundation consolidado
- ✅ 3 archivos nuevos deployados vía SCP al servidor
- ⏳ Esperando admin merge PR #279

---

## IDE Configuration Files

### `.cursor-workspace.json`
Frontend/UI development focus. Contains:
- **Priorities:** P0-P3 tasks aligned with Gano Digital
- **Recent files:** front-page.php, cta-registro.php, gano-cta-registro.css
- **Extensions:** WordPress, PHP intellisense, CSS, Git
- **Sync settings:** Auto-capture on save, update memory on session end

### `.antigravity-workspace.json`
Backend/server-side focus. Contains:
- **Remote SSH:** Pre-configured connection to production (72.167.102.145)
- **Priorities:** P0-P3 for infrastructure, API, deployment
- **Focus:** Workflow triggers, deployment validation, server monitoring
- **Sync settings:** Auto-document SSH + git, capture deployment logs

### `.claude/settings.json`
Claude Code project-level configuration. Contains:
- **Hooks:** SessionStart, PostToolUse (Write/Edit/Bash), Stop
- **Environment vars:** GANO_PROJECT_ROOT, GANO_WIKI_DIR, GANO_MEMORY_DIR, GANO_PHASE
- **Memory:** Auto-enabled, linked to central memory directory
- **Model:** Sonnet (default), Effort level: Medium

---

## Automatic Sync Flow

```
┌─────────────────────────────────────────┐
│   Session Start                         │
├─────────────────────────────────────────┤
│ → Hook captures: git status, commits    │
│ → Creates: session_start_*.md           │
│ → Loads: priorities from workspace.json │
└─────────────────────────────────────────┘
                   ⬇
┌─────────────────────────────────────────┐
│   During Session                        │
├─────────────────────────────────────────┤
│ → Edit/Write files                      │
│   → Logged to session_changes.log       │
│ → Run bash commands                     │
│   → Logged to session_commands.log      │
│ → Git commits                           │
│   → Logged to commits.log (post-commit) │
└─────────────────────────────────────────┘
                   ⬇
┌─────────────────────────────────────────┐
│   Session End (/stop)                   │
├─────────────────────────────────────────┤
│ → Hook captures: git status, summary    │
│ → Creates: session_end_*.md             │
│ → Updates: memory files in ~/.claude/   │
│ → Cleans up: session logs               │
└─────────────────────────────────────────┘
```

---

## Wiki Structure (Auto-Populated)

```
wiki/
├── dev-sessions/                    # Auto-captured by hooks
│   ├── session_start_2026-04-21_*.md
│   ├── session_end_2026-04-21_*.md
│   ├── commits.log                  # Git post-commit hook
│   ├── merges.log                   # Git post-merge hook
│   ├── session_changes.log          # Temporary, cleaned up on session end
│   └── session_commands.log         # Temporary, cleaned up on session end
│
├── content-library/
│   ├── copy/                        # SOTA copy from second brain
│   │   ├── homepage-copy-2026-04.md
│   │   ├── ecosystems-copy-matrix-wave3.md
│   │   └── trust-and-reseller-copy-wave3.md
│   ├── reseller/                    # GoDaddy Reseller integration docs
│   └── components/                  # Reusable components
│
└── architecture/                    # Design decisions
    ├── design-system.md
    └── deployment-strategy.md
```

---

## Environment Variables (Configured)

These are set in `.claude/settings.json` and available to all hooks:

```
GANO_PROJECT_ROOT    = C:\Users\diego\Downloads\Gano.digital-copia
GANO_WIKI_DIR        = C:\Users\diego\Downloads\Gano.digital-copia\wiki\dev-sessions
GANO_MEMORY_DIR      = C:\Users\diego\.claude\projects\C--Users-diego\memory
GANO_PHASE           = Fase 4 - Integración Reseller GoDaddy
GANO_PR_BLOCKING     = PR #279 (Auditoría UX/Visual) - esperando merge a main
```

---

## Memory Integration (Source of Truth)

Central memory files linked from `.claude/projects/C--Users-diego/memory/`:

| File | Updated By | Contains |
|------|-----------|----------|
| `project_gano_digital.md` | Manual + hooks | Overall project state, phases, stack |
| `deployment_complete_2026_04_21.md` | Session end hook | PR status, deployment blockers, next steps |
| `audit_final_summary_2026_04_21.md` | Manual | Audit completion details, file changes |
| `ide_sync_setup_2026_04_21.md` | Manual | This sync configuration + troubleshooting |

→ **When you open a session:** Claude Code reads all memory files automatically
→ **When session ends:** Hooks update deployment_complete_2026_04_21.md with current status

---

## Common Workflows

### **Workflow 1: Waiting for PR #279 Merge**

**You:**
1. Open Antigravity workspace
2. See P0 blocker: "PR #279 merge to main"
3. Session hook captures: Git status showing PR branch, no uncommitted changes
4. Keep working on P2 (Reseller integration) independently

**When admin merges PR #279:**
1. GitHub Actions workflow #04 auto-triggers
2. Deploys files to server via RSYNC
3. Session end hook captures: Deployment event

### **Workflow 2: Production Validation (After PR Merge)**

**You:**
1. Open Antigravity, SSH connection ready
2. Run validation commands (CSS rendering, CTA animations, error logs)
3. Each bash command logged to session_commands.log
4. Session end hook creates summary with server status
5. Memory file updated: "Production validation: ✅ PASSED"

### **Workflow 3: Reseller Integration (Fase 4)**

**You:**
1. Open Cursor workspace
2. P2 priority: "Reseller integration - catálogo, PFIDs, checkout"
3. Edit files: functions.php, page-ecosistemas.php, components
4. All changes logged to session_changes.log
5. Session end hook captures: Files modified, git status
6. Memory updated: "Fase 4 progress: X% complete"

---

## Troubleshooting

### **Hooks not firing?**

**Check 1:** Settings syntax
```bash
jq . .claude/settings.json  # Should output valid JSON
```

**Check 2:** Reload config
- Type `/hooks` in Claude Code (UI command)
- This reloads settings.json

**Check 3:** PowerShell execution policy
```powershell
Get-ExecutionPolicy -Scope CurrentUser
# If "Restricted", run:
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser -Force
```

### **Session files not created?**

**Check 1:** Directory exists
```bash
ls wiki/dev-sessions/  # Should show directory
```

**Check 2:** Permissions
```bash
chmod 755 wiki/dev-sessions/
chmod 755 .gano-skills/hooks/
```

**Check 3:** PowerShell scripts readable
```bash
file .gano-skills/hooks/*.ps1  # Should say "ASCII text" or "UTF-8 Unicode"
```

### **Memory files not updating?**

**Check 1:** Memory enabled in .claude/settings.json
```bash
grep autoMemoryEnabled .claude/settings.json  # Should say "true"
```

**Check 2:** Memory directory writable
```bash
touch C:\Users\diego\.claude\projects\C--Users-diego\memory\test.md
# If error, check Windows file permissions
```

---

## Next Steps (Auto-Suggested by Hooks)

1. **Admin merges PR #279** ✅ Required for deployment
2. **GitHub Actions workflow #04 executes** → Auto-deploys files
3. **Production validation** → Run validation commands in Antigravity
4. **Fase 4 starts** → Independent from deployment (can start anytime)

---

## For Team Members

If you're also working on Gano Digital:

1. **Clone the repo:**
   ```bash
   git clone https://github.com/Gano-digital/Pilot.git
   cd Pilot
   ```

2. **Open in your IDE:**
   ```bash
   cursor .   # or: antigravity .
   ```

3. **You'll see:**
   - Current workspace priorities
   - Recent files from other team members
   - Session history in wiki/dev-sessions/
   - Central memory in ~/.claude/projects/*/memory/

4. **Your changes auto-sync:**
   - Edit/write files → Logged
   - Git commits → Documented
   - Session end → Summary created

---

## Configuration Ownership

| File | Owner | Git Status | Sync |
|------|-------|-----------|------|
| `.claude/settings.json` | Project | ✅ Committed | Claude Code hooks |
| `.cursor-workspace.json` | Project | ✅ Committed | Cursor IDE |
| `.antigravity-workspace.json` | Project | ✅ Committed | Antigravity IDE |
| `.gano-skills/hooks/` | Project | ✅ Committed | PowerShell scripts |
| `wiki/dev-sessions/` | Project | ✅ Committed | Auto-populated |
| `.git/hooks/` | Local | ❌ Not committed | User-specific |
| `~/.claude/projects/*/memory/` | User | ✅ Persisted | Central memory |

---

**Last Updated:** 2026-04-21
**Status:** ✅ All systems operational
**Next Review:** After PR #279 merge + workflow #04 execution

For detailed troubleshooting, see: `C:\Users\diego\.claude\projects\C--Users-diego\memory\ide_sync_setup_2026_04_21.md`
