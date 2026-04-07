# Antigravity Quick Start — Gano Digital

**Status**: 🟢 READY FOR ACTIVATION
**Time to activate**: 10 minutes (install + configure)
**First test**: 20 minutes (`/reseller-cart-test staging`)

---

## What is Antigravity?

Google Antigravity is an **agentic IDE** (like Cursor + automation). Unlike Cursor (code generation in IDE), Antigravity:

- **Spawns multiple autonomous agents** that work in parallel
- **Automates browser tasks** (click buttons, screenshot, video recording)
- **Creates artifacts** (implementation plans, code diffs, test videos)
- **Supports feedback loops** (comment → agent refines → repeat)

**For Gano Digital**: Primary use is **Phase 4 commerce testing** (cart validation, RCC verification, checkout flow).

---

## 5-Minute Setup

### 1. Download & Install (3 min)
```bash
# Visit https://antigravity.google/download
# Download for Windows, Mac, or Linux
# Run installer, follow prompts
# Choose: Fresh start, Dark theme, "Agent-driven development"
# Sign in with personal Gmail
```

### 2. Open Workspace (2 min)
```bash
# In Antigravity, click "Open Workspace"
# Navigate to: <ruta-del-clon>\Gano.digital-copia\
# Example on Windows: %USERPROFILE%\Downloads\Gano.digital-copia\
# The exact path depends on where you cloned the repo
# Antigravity loads project structure automatically
```

### 3. Verify (Extra: Browser Extension)
```bash
# Antigravity will ask to install Chrome extension
# Click "Setup" → Browser opens → "Add to Chrome"
# Extension enables browser automation (required for cart tests)
```

**Done!** Antigravity is ready.

---

## First Test: 20 Minutes

### Run Phase 4 Cart Test
```
In Antigravity Agent Manager chat:

/reseller-cart-test staging
```

**What it does**:
1. Opens staging.gano.digital
2. Clicks "Get Started" CTA
3. Navigates Reseller cart
4. Verifies product details & pricing
5. Screenshots each step
6. Records browser video
7. Generates test report (PASS/FAIL)

**Expected output**:
- 📹 Browser recording (video of entire flow)
- 📸 5+ screenshots (proof of each step)
- 📋 Test report (9/9 steps passed)
- ⏱️ Time: ~15 minutes

---

## Your Workflows (Ready to Use)

### `/phase4-rcc-audit`
Comprehensive audit of PFID mappings, CTA wiring, plugin config.
```
In chat: /phase4-rcc-audit
Output: Gap report + recommendations
Time: 30-45 min
```

### `/reseller-cart-test staging`
End-to-end cart + checkout test in staging environment.
```
In chat: /reseller-cart-test staging
Output: Test report + video recording + screenshots
Time: 15-20 min
Must pass before `/reseller-cart-test production`
```

### `/reseller-cart-test production`
Final validation in production (run only after staging PASS).
```
In chat: /reseller-cart-test production
Output: Production readiness report
Time: 15-20 min
⚠️ Run in staging first to confirm process
```

---

## Your Rules (Guides Agent Behavior)

Automatically applied — no setup needed:

**`gano-digital-guidelines.md`**
- Code standards (PHP naming, git commits)
- When in doubt: check TASKS.md, review Obsidian decisions

**`phase4-commerce-rules.md`**
- PFID validation before mapping
- Staging tested before production
- Never override Reseller Store logic
- Rate limiting + security checks

---

## Your Skill (Specialized Knowledge)

Automatically loaded when you mention "Phase 4" or "PFID":

**`phase4-commerce`**
- PFID mappings (WordPress → GoDaddy product IDs)
- Critical files (gano-reseller-enhancements plugin, CTA templates)
- Common issues & fixes (cart 404, CORS errors, timeout)
- Testing sequence (cart load → checkout → RCC order → email)

---

## Integration Checklist

- [ ] Antigravity installed
- [ ] Workspace opened (Gano.digital-copia)
- [ ] Chrome extension installed
- [ ] First test run: `/reseller-cart-test staging`
- [ ] Test PASSED (9/9 steps)
- [ ] Browser recording captured
- [ ] Screenshot evidence visible
- [ ] Ready to commit test results to PR

---

## Example: Testing New PFID Mapping

**Scenario**: Cursor just added PFID-12346 (WordPress 3-year) to plugin.

```
In Antigravity:

"Test the new PFID-12346 mapping. Verify cart loads and 3-year pricing applied."

Antigravity:
1. Reads .agents/skills/phase4-commerce/ (knows PFID format)
2. Reads CLAUDE.md (understands project context)
3. Loads gano-reseller-enhancements plugin (to see new PFID)
4. Opens staging.gano.digital
5. Clicks CTA → navigates to Reseller cart with PFID-12346
6. Verifies: "WordPress Hosting - 3 Year" product shown
7. Verifies: Correct pricing displayed (discounted)
8. Takes screenshot of cart
9. Proceeds to checkout
10. Generates report: "✅ PFID-12346 test PASSED"
11. Browser recording shows entire flow
```

**You then**:
- Review report + video
- Comment: "Perfect! This mapping is ready for production"
- Approve Cursor's PR with test evidence

---

## When Something Fails

### Cart shows 404
**Cause**: Wrong PFID or Reseller URL
**Fix**:
1. Check `gano-reseller-cart-url()` function in plugin
2. Verify PFID exists in RCC
3. Test URL manually in browser
4. Re-run test after fix

### CORS Error in browser
**Cause**: CSP header blocks godaddy.com
**Fix**:
1. Edit `wp-content/mu-plugins/gano-security.php`
2. Add `connect-src: godaddy.com` to CSP header
3. Clear cache
4. Re-run test

### Checkout timeout (> 30 sec)
**Cause**: Reseller API slow or network latency
**Fix**:
1. Test in OTE (sandbox) first
2. Check Reseller API status
3. Retry test
4. Contact GoDaddy support if issue persists

### Order not appearing in RCC (> 5 min)
**Cause**: Webhook not registered
**Fix**:
1. Log into RCC
2. Check webhook configuration
3. Verify webhook URL receiving POST data
4. Test webhook manually

---

## Asking Antigravity for Help

### Task-Oriented
```
"Map all 5 product families to Reseller cart and test each one"
→ Antigravity suggests `/phase4-rcc-audit` workflow
```

### Decision-Making
```
"Should we test production before or after RCC verification?"
→ Antigravity references RULES and suggests staging-first approach
```

### Debugging
```
"Why is PFID-12345 showing 404 in staging?"
→ Antigravity checks gano-reseller-enhancements plugin, RCC config, and suggests fixes
```

### Integration with Other Agents
```
"Summarize Antigravity cart test results for Claude"
→ Copy artifacts to Claude, ask for decision on production readiness
```

---

## Security Settings (Configure Once)

In **Antigravity Settings** (Cmd/Ctrl + ,):

### Terminal Execution
- Set to: **"Request Review"** (safest)
- Allow list: `git status`, `git diff`, `npm test`, `wp plugin list`
- Deny list: `rm`, `sudo`, `git push --force`

### Browser JavaScript
- Set to: **"Request Review"** (ask before running JS)
- This prevents prompt injection attacks

### Browser URL Allowlist
Add:
- `gano.digital`
- `staging.gano.digital`
- `reseller.godaddy.com` (or OTE equivalent)

---

## Did You Know?

- **Parallel agents**: Spawn 3 cart tests simultaneously, see all results in Mission Control
- **Artifacts**: Implementation plans, code diffs, video recordings — all reviewable before final action
- **Feedback loops**: Comment on code diffs or test results; agent refines and re-runs
- **Skills**: Your `.agents/skills/phase4-commerce/` is loaded automatically when relevant
- **Workflows**: `/phase4-rcc-audit` is saved prompt you can trigger anytime

---

## Next Steps

1. ✅ Install Antigravity (this document assumes it's done)
2. 🟡 **Run first test**: `/reseller-cart-test staging`
3. ✅ Verify test PASSES (9/9 steps)
4. 🟡 **Audit RCC**: `/phase4-rcc-audit` (identify gaps)
5. 🟡 **Fix gaps** (with Cursor or VS Code)
6. 🟡 **Re-test staging**: `/reseller-cart-test staging` (confirm fixes)
7. 🟡 **Test production**: `/reseller-cart-test production` (final check)
8. ✅ **Phase 4 complete**: Commit all test evidence to PR

---

## Links & Resources

| Resource | Purpose |
|----------|---------|
| [Antigravity Docs](https://antigravity.google/docs) | Official documentation |
| [Getting Started Codelab](https://codelabs.developers.google.com/getting-started-google-antigravity) | Interactive tutorial |
| `.agents/rules/phase4-commerce-rules.md` | Commerce automation rules |
| `.agents/workflows/phase4-rcc-audit.md` | RCC audit workflow details |
| `.agents/workflows/reseller-cart-test.md` | Cart test workflow details |
| `.agents/skills/phase4-commerce/SKILL.md` | Phase 4 knowledge base |
| `memory/integration/antigravity-integration-2026-04-06.md` | Full integration strategy |

---

## TL;DR

1. **Install** Antigravity from https://antigravity.google/download
2. **Open workspace** (Gano.digital-copia)
3. **Run** `/reseller-cart-test staging`
4. **Review** test report + browser recording
5. **Commit** test evidence to PR
6. **Repeat** for each PFID mapping or Phase 4 feature

---

**Status**: 🟢 ACTIVATED
**Estimated impact**: 40% reduction in manual Phase 4 testing time
**Owner**: Diego Gómez (Gano Digital)
**Questions**: See `memory/integration/antigravity-integration-2026-04-06.md`
