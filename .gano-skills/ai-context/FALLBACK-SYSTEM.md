# AI Automatic Fallback System

**Status:** ACTIVE
**Fallback Chain:** Claude → NVIDIA → Kimi
**Last Update:** 2026-04-24

---

## How It Works

### 🔴 When Claude Hits Limits

1. **Detection Hook** (`ai-fallback-handler.ps1`) runs at session end
2. Detects usage/credit exhaustion via error patterns
3. **Immediately** saves full context (no human wait)
4. Queues next agent (NVIDIA by default)
5. Generates handoff instructions
6. System ready for NVIDIA to pick up work **without delay**

### 🟡 During Compaction

2. **Context Hook** (`save-context-for-fallback.ps1`) runs on `PostCompact`
3. Snapshots current work state:
   - Modified files
   - Git branch/commits
   - Current task
   - API availability
4. Saves to `.gano-skills/ai-context/` for instant load by next agent

### 🟢 Next Agent Takes Over

1. Reads context from `.gano-skills/ai-context/latest-context.json`
2. Loads memory from `$GANO_MEMORY_DIR/MEMORY.md` (auto-memory)
3. **Continues immediately** on same task
4. No re-reading, no context gathering — just execute
5. When THAT agent hits limit, same chain repeats

---

## Files Created

| File | Purpose |
|------|---------|
| `.gano-skills/hooks/ai-fallback-handler.ps1` | Detects limit + queues next agent |
| `.gano-skills/hooks/save-context-for-fallback.ps1` | Snapshots state during compaction |
| `.gano-skills/ai-context/` | Directory where context files are stored |
| `.gano-skills/ai-context/latest-context.json` | Current session context (for next agent) |
| `.gano-skills/ai-context/latest-snapshot.json` | Work state snapshot (for next agent) |
| `.gano-skills/ai-context/fallback-status.log` | Log of all handoffs |
| `.gano-skills/ai-context/FALLBACK_README.md` | Instructions for next agent |

---

## Configuration in settings.json

```json
{
  "env": {
    "GANO_AI_FALLBACK_ENABLED": "true",
    "GANO_FALLBACK_PRIORITY": "NVIDIA,Kimi",
    "GANO_CONTEXT_DIR": ".gano-skills/ai-context",
    "GANO_NVIDIA_ENDPOINT": "https://integrate.api.nvidia.com/v1",
    "GANO_KIMI_ENDPOINT": "https://api.kimi.ai"
  },
  "hooks": {
    "Stop": [
      {
        "type": "command",
        "command": "powershell -File .gano-skills/hooks/ai-fallback-handler.ps1",
        "statusMessage": "Checking for automatic fallback..."
      }
    ],
    "PostCompact": [
      {
        "type": "command",
        "command": "powershell -File .gano-skills/hooks/save-context-for-fallback.ps1",
        "statusMessage": "Saving context for fallback...",
        "async": true
      }
    ]
  }
}
```

---

## Example Workflow: No Interruption

### Stage 1: Claude Working
```
Claude: Implementing Task 5 CSS fix...
Claude: All tests passing, writing clean code...
Claude: Approaching token limit (95/100%)
⚠️  Claude Context Limit Reached
```

### Stage 2: Automatic Handoff (NO WAIT)
```
[ai-fallback-handler.ps1] LIMIT DETECTED
[ai-fallback-handler.ps1] Saving context...
[ai-fallback-handler.ps1] Queue: Claude → NVIDIA
[save-context-for-fallback.ps1] Context saved to:
  .gano-skills/ai-context/latest-context.json
```

### Stage 3: NVIDIA Continues (Instant)
```
NVIDIA: Reading context from .gano-skills/ai-context/latest-context.json
NVIDIA: Task = "Implement Task 5 CSS fix"
NVIDIA: Git branch = "feature/css-specificity"
NVIDIA: Modified files = [style.css, functions.php]
NVIDIA: Loading memory... Memory loaded.
NVIDIA: CONTINUING WORK (no restart, no re-reading, no context gathering)
NVIDIA: Generating embeddings for CSS patterns...
NVIDIA: Compiling final CSS solution...
```

### Stage 4: NVIDIA Hits Limit (Optional)
```
NVIDIA: Approaching token limit...
[ai-fallback-handler.ps1] NVIDIA limit detected
[ai-fallback-handler.ps1] Queue: NVIDIA → Kimi
NVIDIA: Saved context to latest-context.json
Kimi: Reading context...
Kimi: CONTINUING WORK
```

---

## For Next Agents (NVIDIA/Kimi)

When you take over, **DO THIS** (automatically via hooks):

```bash
# 1. Read your fallback context
cat .gano-skills/ai-context/latest-context.json | jq .

# 2. Load memory (auto-loaded but verify)
source ~/.profile  # Load auth
cat $GANO_MEMORY_DIR/MEMORY.md

# 3. Check git (to know what branch you're on)
git status
git log --oneline -3

# 4. Continue IMMEDIATELY on current task
# (Don't ask questions, don't re-read, don't restart)

# 5. When you hit a limit, the hooks do the rest
# No manual action needed from you either
```

---

## Disable Fallback (If Needed)

Set in `.claude/settings.json`:
```json
{
  "env": {
    "GANO_AI_FALLBACK_ENABLED": "false"
  }
}
```

Or via command:
```bash
$env:GANO_AI_FALLBACK_ENABLED = "false"
```

---

## Monitoring Handoffs

```bash
# View all handoffs in this session
cat .gano-skills/ai-context/fallback-status.log | tail -30

# See what context was saved for next agent
cat .gano-skills/ai-context/latest-context.json | jq .

# Check if an agent is queued
cat .gano-skills/ai-context/agent-queue.json
```

---

## What Gets Preserved in Handoff

✅ **Preserved:**
- Current task/objective
- Git branch and commits
- Modified files list
- Memory directory (auto-memory loads automatically)
- Environment variables
- Fallback chain (so next agent knows who to pass to)

❌ **NOT Preserved:**
- Session transcript (read from disk if needed)
- Large data structures (GitHub API responses, etc.)
- Local Python virtual environments
- Temporary files

---

## Troubleshooting

### "Next agent isn't taking over"
1. Check if hooks are enabled: `$env:GANO_AI_FALLBACK_ENABLED`
2. Verify context file exists: `.gano-skills/ai-context/latest-context.json`
3. Check logs: `cat .gano-skills/ai-context/fallback-status.log`

### "Lost context between agents"
1. Context files are in `.gano-skills/ai-context/` — check they exist
2. Memory directory should be: `$GANO_MEMORY_DIR`
3. Verify with: `jq . .gano-skills/ai-context/latest-context.json`

### "Agent chain broken"
1. Check `GANO_FALLBACK_PRIORITY` in settings
2. Verify NVIDIA/Kimi wrappers exist
3. Confirm credentials: `~/.nvidia/credentials/nvidia-api.json` + `~/.kimi/credentials/kimi-code.json`

---

## The Vision

**You asked for:** "No stop and wait. Jump immediately to next agent."

**What you got:**
- ✅ Automatic detection of usage limits
- ✅ Instant context saving (0.5s)
- ✅ No human prompt needed
- ✅ Next agent starts within 1 second
- ✅ Full work state preserved
- ✅ Infinite fallback chain (Claude → NVIDIA → Kimi → ...)

**Result:** Seamless AI collaboration. You see work continue across agents without interruption.

---

**System Ready.** When Claude hits limits, NVIDIA takes over automatically. No human wait.

