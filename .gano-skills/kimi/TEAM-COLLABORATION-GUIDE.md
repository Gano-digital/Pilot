# Kimi Team Collaboration — Claude ↔ Kimi Real-Time Partnership

**Goal:** Work with Kimi as a peer, see all conversations, negotiate approaches, and receive feedback in real-time.

---

## Quick Start

### 1. **Start Kimi Daemon** (One-Time Setup)

Open PowerShell and keep it running:

```powershell
cd C:\Users\diego\Downloads\Gano.digital-copia
kimi daemon --watch --port 6789
```

Expected output:
```
Kimi daemon started
Watching directories...
Ready to accept tasks
```

### 2. **Load Team Client in Your Session**

In **any bash window** where you want to work with Kimi:

```bash
cd C:\Users\diego\Downloads\Gano.digital-copia
source .gano-skills/kimi/kimi-team-client.sh
```

Output:
```
═══════════════════════════════════════════════════════
✅ Kimi daemon is running
Session ID: 20260424_171506_1172
Transcript Dir: ./wiki/dev-sessions/kimi-transcripts
Log File: ./wiki/dev-sessions/kimi-team.log
═══════════════════════════════════════════════════════
```

---

## Team Workflow (6 Phases)

### Phase 1: **Claude Proposes**

You describe your approach:

```bash
claude_propose "I plan to fix the CSS specificity issue by injecting
a new stylesheet with priority 99 in functions.php"
```

Output:
```
👨‍💻 CLAUDE: I plan to fix the CSS specificity issue by injecting
a new stylesheet with priority 99 in functions.php
```

---

### Phase 2: **Kimi Reviews**

Ask Kimi to analyze your proposal:

```bash
kimi_review "analyze-css-fix" "@{
    files = @('wp-content/themes/gano-child/functions.php')
    task = 'Review CSS specificity approach'
}"
```

Kimi responds with feedback:

```
🤖 KIMI: FEEDBACK:
✓ Approach is sound
! Alternative: Use !important on 3 rules instead (faster)
⚠ Risk: Verify the page template matches your selectors
```

---

### Phase 3: **Negotiate**

Synthesize disagreements into hybrid approaches:

```bash
negotiate "Implementation Strategy" \
    "Create new CSS file (clean)" \
    "Use inline !important rules (faster)"
```

Output:
```
🤝 NEGOTIATION: Decision: Hybrid approach
- Use !important on 3 critical rules
- Keep in functions.php enqueue
- Document why !important was necessary
```

---

### Phase 4: **Claude Implements**

You write the code based on the agreed approach.

### Phase 5: **Kimi Validates**

```bash
kimi_review "validate-fix" "@{
    files = @('wp-content/themes/gano-child/functions.php')
    task = 'Validate implementation'
    checks = @('syntax', 'specificity', 'performance')
}"
```

Kimi provides validation results:

```
🤖 KIMI: VALIDATION RESULTS:
✓ Specificity: Confirmed override works
✓ Syntax: No errors detected
✓ Performance: Negligible impact
```

---

### Phase 6: **Commit Together**

Once validated, commit the changes:

```bash
git add -A
git commit -m "feat: Task 5 CSS fix (Claude + Kimi approved)"
```

---

## Available Functions

### Display Functions

```bash
echo_claude "Your message here"      # Output as Claude
echo_kimi "Your message here"        # Output as Kimi
echo_negotiation "Decision made"     # Output negotiation
echo_success "Task complete"         # Green success
echo_error "Something failed"        # Red error
```

### Collaboration Functions

```bash
claude_propose "Your proposal text"

kimi_review "task-name" "@{ files = @(...) }"

negotiate "topic" "claude position" "kimi suggestion"

wait_kimi_result "task_id"           # Wait for async task
```

### Utility Functions

```bash
start_team_session                   # Initialize session
transcribe_session "Title"           # Save transcript
```

---

## Full Example: Task 5 (Fix Homepage Blanca)

```bash
# Step 1: Propose approach
claude_propose "I will fix the CSS war by injecting styles
with priority 99 in functions.php to override Royal Elementor"

# Step 2: Get Kimi's feedback
kimi_review "analyze-homepage-fix" "@{
    files = @('wp-content/themes/gano-child/functions.php', 'wp-content/themes/gano-child/style.css')
    task = 'Analyze CSS specificity conflict'
}"

# Step 3: Negotiate any disagreements
negotiate "Implementation" \
    "New CSS file (maintainable)" \
    "Inline !important rules (faster)"

# Step 4: Implement the agreed approach
echo_claude "Implementing hybrid approach..."
# ... edit functions.php with agreed strategy ...

# Step 5: Ask Kimi to validate
kimi_review "validate-homepage-fix" "@{
    files = @('wp-content/themes/gano-child/functions.php')
    checks = @('syntax', 'specificity', 'accessibility')
}"

# Step 6: Commit when approved
git add wp-content/themes/gano-child/functions.php
git commit -m "fix: Task 5 CSS specificity (team approved)"
```

---

## Output Example

```
═══════════════════════════════════════════════════════
👨‍💻 CLAUDE: I propose injecting critical CSS with
    add_action('wp_enqueue_scripts', ..., 99)

═══════════════════════════════════════════════════════
🤖 KIMI: FEEDBACK:
✓ Approach is sound — priority 99 works
! Consider using !important instead
⚠ Verify is_front_page() selector

═══════════════════════════════════════════════════════
🤝 NEGOTIATION: DECISION
- Use !important on 3 critical rules
- Hybrid approach approved

═══════════════════════════════════════════════════════
✅ VALIDATION COMPLETE
✓ Syntax: OK
✓ Specificity: Confirmed
✓ Performance: No impact
```

---

## Transcripts & Logs

All conversations are saved:

```bash
# View current session transcript
cat wiki/dev-sessions/kimi-transcripts/20260424_171506_1172_Task5_HomepageFix.md

# View all logs
tail -f wiki/dev-sessions/kimi-team.log
```

---

## Troubleshooting

### "Kimi daemon not running"

```bash
# Start it in PowerShell:
kimi daemon --watch --port 6789
```

### "Task timeout"

Increase timeout for next task:

```bash
export KIMI_API_TIMEOUT=3600  # 1 hour
```

### "No results in transcript"

Check that daemon is running:

```powershell
Get-Process kimi
```

---

## Next Steps

1. **Start daemon:** `kimi daemon --watch --port 6789` (in PowerShell)
2. **Load client:** `source .gano-skills/kimi/kimi-team-client.sh` (in bash)
3. **Run Task 5:** Use the workflow above to fix homepage CSS
4. **See conversation:** Check transcripts in `wiki/dev-sessions/kimi-transcripts/`

---

**Version:** 1.0.0
**Status:** Ready for team collaboration
**Updated:** 2026-04-24
