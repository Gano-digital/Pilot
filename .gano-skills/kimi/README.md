# Kimi CLI Integration for Gano Digital

Kimi is an autonomous development assistant for Gano Digital that works in parallel with Claude Code. It monitors file changes, runs tests, optimizes code, and maintains development memory.

## Quick Start

### 1. Verify Kimi Installation

```powershell
# Check Kimi is installed
kimi --version

# Check from PowerShell
$env:PATH = "C:\Users\diego\.local\bin;$env:PATH"
kimi --help
```

### 2. Load Context and Initialize

```powershell
# Navigate to project root
cd C:\Users\diego\Downloads\Gano.digital-copia

# Load Kimi helper functions
. .\.gano-skills\kimi\kimi-helper.ps1

# Initialize Kimi
Initialize-Kimi -Verbose
```

### 3. Run a Task

```powershell
# Run a specific task
Run-KimiTask -Task "verify_file_changes"

# Run with arguments
Run-KimiTask -Task "analyze_code" -Args "wp-content/themes"
```

## Configuration

### kimi-config.yaml

The main configuration file defines:
- **Project settings:** Name, root, branch, description
- **AI settings:** Provider, model, context window, token limits
- **Execution settings:** Auto-execute, approval requirements, timeouts
- **Shell settings:** Type (PowerShell), working directory
- **Logging:** Level, format, output location
- **Memory:** Auto-save, directory path
- **MCP:** Enabled tools (file ops, web fetch, git)

Edit `kimi-config.yaml` to:
- Change model from claude-3-5-sonnet to another
- Adjust timeout values
- Enable/disable auto-execution
- Configure logging level

### kimi-context.md

Provides knowledge base for Kimi:
- Project overview and current goals
- Codebase structure and file organization
- Recent commits and version history
- Design system standards (colors, typography, motion, accessibility)
- SSH credentials and remote access
- Performance targets and metrics
- Escalation criteria

Update `kimi-context.md` after:
- Significant architecture changes
- Design system modifications
- New deployment procedures
- Updated performance targets

## Task Templates

Available tasks in `kimi-tasks.yaml`:

### Analysis Tasks
- **analyze_code** — Code quality, complexity, performance
- **lint_php** — PHP syntax and WordPress standards
- **validate_css_design_system** — CSS against design system standards

### Testing Tasks
- **run_tests** — E2E test suite execution
- **check_accessibility** — WCAG 2.1 AA compliance audit
- **generate_lighthouse_report** — Performance metrics

### Deployment Tasks
- **verify_deployment** — Production server verification
- **verify_file_changes** — Git status and file integrity
- **monitor_tasks** — Progress monitoring for Phase 4

### Optimization Tasks
- **optimize_css** — CSS performance optimization
- **document_findings** — Change documentation
- **sync_memory** — Memory synchronization with Claude Code

### Task Groups

Run multiple tasks together:

```powershell
# Code quality group
Run-KimiTask -Task "code_quality"

# Testing and validation
Run-KimiTask -Task "testing_validation"

# Deployment monitoring
Run-KimiTask -Task "deployment_monitoring"
```

## Helper Script Functions

The `kimi-helper.ps1` script provides these functions:

### Log-Message
Colored logging with timestamps:
```powershell
Log-Message "Operation started" "INFO"
Log-Message "File processed" "SUCCESS"
Log-Message "Warning: No files found" "WARNING"
Log-Message "Error: Connection failed" "ERROR"
```

### Initialize-Kimi
Verify installation and load context:
```powershell
Initialize-Kimi -Verbose
```

### Run-KimiTask
Execute a named task:
```powershell
Run-KimiTask -Task "verify_deployment" -Args @("production")
```

### Verify-FileChanges
Monitor and verify file changes:
```powershell
Verify-FileChanges -Files @("style.css", "index.php")
```

### Sync-Memory
Synchronize development memory:
```powershell
Sync-Memory -Type "auto"
```

### Analyze-Code
Analyze code for quality issues:
```powershell
Analyze-Code -Paths @("wp-content/themes", "wp-content/plugins")
```

### Run-Tests
Execute test suite:
```powershell
Run-Tests -Type "e2e"
```

### Verify-Deployment
Check production deployment:
```powershell
Verify-Deployment -Server "f1rml03th382@72.167.102.145"
```

## Integration with Claude Code

Kimi integrates with Claude Code through:

### 1. PostToolUse Hooks

When Claude Code uses Write or Edit tools, Kimi automatically:
- Verifies file changes
- Logs modifications
- Checks file integrity
- Updates memory state

Hook configuration in `.claude/settings.json`:
```json
{
  "hooks": {
    "PostToolUse": [
      {
        "matcher": "Write|Edit",
        "hooks": [
          {
            "type": "command",
            "command": "powershell -NoProfile -Command \". '.gano-skills/kimi/kimi-helper.ps1' verify_file_changes\"",
            "timeout": 30
          }
        ]
      }
    ]
  }
}
```

### 2. Memory Synchronization

Kimi and Claude Code share memory via:
- `.claude/projects/C--Users-diego/memory/` — Persistent memory files
- `wiki/dev-sessions/kimi-session.log` — Session logs
- `kimi-context.md` — Shared knowledge base

After each task, run:
```powershell
Sync-Memory -Type "task_complete"
```

### 3. Task Coordination

Claude Code and Kimi coordinate on Phase 4 tasks:

| Task | Owner | Role |
|------|-------|------|
| Task 5 | Claude Code | Webhook integration testing |
| Task 6 | Kimi | CSS optimization & accessibility audit |
| Task 7 | Claude Code | E2E test automation |
| Task 8 | Kimi | Memory sync & documentation |
| Task 9 | Both | Final verification & deployment |

## Workflow: Tasks 5-9 Execution Timeline

```
Phase 4 Task Execution Plan
============================

Week 1: Tasks 5-6
├─ Task 5: Claude Code — Webhook integration (4 days)
└─ Task 6: Kimi — CSS optimization & a11y audit (2 days, parallel)
   └─ Kimi generates CSS optimization report
      └─ Claude Code reviews and applies fixes

Week 2: Task 7
├─ Task 7: Claude Code — E2E test automation (3 days)
└─ Kimi monitors execution and logs progress

Week 3: Task 8
├─ Task 8: Kimi — Memory sync & documentation (2 days)
└─ Generates final documentation

Week 4: Task 9
├─ Task 9: Both — Final verification & deployment
└─ Kimi verifies, Claude Code handles any final fixes
   └─ Production deployment complete

Timeline: ~4 weeks total
```

## Environment Variables

Set these for Kimi execution:

```powershell
# Project paths
$env:GANO_PROJECT_ROOT = "C:\Users\diego\Downloads\Gano.digital-copia"
$env:GANO_MEMORY_DIR = "C:\Users\diego\.claude\projects\C--Users-diego\memory"

# SSH access
$env:GANO_SSH_HOST = "f1rml03th382@72.167.102.145"
$env:GANO_SSH_KEY = "$env:USERPROFILE\.ssh\id_rsa"

# Kimi config
$env:KIMI_CONFIG = ".gano-skills\kimi\kimi-config.yaml"
$env:KIMI_LOG_LEVEL = "INFO"
```

## Troubleshooting

### Issue: Kimi command not found

**Solution:**
```powershell
# Update PATH
$env:PATH = "C:\Users\diego\.local\bin;$env:PATH"

# Or use full path
C:\Users\diego\.local\bin\kimi --version
```

### Issue: Helper script not executing

**Solution:**
```powershell
# Check execution policy
Get-ExecutionPolicy

# If Restricted, change it
Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser

# Or run with bypass
powershell -ExecutionPolicy Bypass -File ".\kimi-helper.ps1"
```

### Issue: SSH connection fails

**Solution:**
```powershell
# Verify SSH key
Test-Path "$env:USERPROFILE\.ssh\id_rsa"

# Test SSH connection
ssh -o IdentitiesOnly=yes f1rml03th382@72.167.102.145 "echo 'Connected'"
```

### Issue: Memory files not syncing

**Solution:**
```powershell
# Check memory directory
Test-Path "C:\Users\diego\.claude\projects\C--Users-diego\memory"

# Manually sync
Sync-Memory -Type "manual"
```

## Performance Considerations

- **Token usage:** Kimi tasks average 5-20k tokens per execution
- **Execution time:** Most tasks complete in 1-5 minutes
- **Parallel execution:** Up to 2 concurrent tasks (configurable)
- **Memory impact:** ~50MB baseline, +20MB per active task

## Resources

- **Configuration:** `.gano-skills/kimi/kimi-config.yaml`
- **Context:** `.gano-skills/kimi/kimi-context.md`
- **Tasks:** `.gano-skills/kimi/kimi-tasks.yaml`
- **Helper:** `.gano-skills/kimi/kimi-helper.ps1`
- **Integration:** `.claude/kimi-integration.md`
- **Memory:** `~/.claude/projects/C--Users-diego/memory/`
- **Logs:** `wiki/dev-sessions/kimi-session.log`

## Support & Escalation

For issues requiring human review:
1. Check logs: `wiki/dev-sessions/kimi-session.log`
2. Review memory: `.claude/projects/C--Users-diego/memory/`
3. Escalate to Claude Code (see escalation criteria in kimi-context.md)
4. Contact Diego for urgent issues

---

**Last Updated:** 2026-04-23
**Version:** 1.0.0
**Status:** Active for Phase 4 Tasks 5-9
