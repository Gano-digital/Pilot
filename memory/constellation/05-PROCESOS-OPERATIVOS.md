# ⚙️ Procesos Operativos — Workflows, Ciclos, Automaciones

**Última actualización**: 2026-04-06
**Propósito**: Entender los procesos clave, cómo se ejecutan, responsabilidades
**Audiencia**: Team leads, process owners, implementers

---

## 🎯 Procesos Críticos de Fase 4

### PROCESO 1: RCC Audit Workflow

**Objetivo**: Auditar la configuración de RCC, identificar gaps, validar readiness

**Trigger**: Diego o Claude solicita audit
```
Diego: "Execute /phase4-rcc-audit"
  ↓
Antigravity Agent Manager: Load workflow, check rules/skills
  ↓
```

**Ejecución** (30-45 min):

**Paso 1: Current State Assessment** (5 min)
- Antigravity reads `memory/commerce/rcc-pfid-checklist.md`
- Inventory of PFIDs already mapped
- Output: "X/5 PFIDs mapped"

**Paso 2: CTA Verification** (10 min)
- Scan all WordPress templates for CTA buttons
- Verify each CTA uses `gano_reseller_cart_url()`
- Check PFID references valid
- Output: "Y/Z CTAs correctly wired"

**Paso 3: Plugin Alignment Check** (5 min)
- Verify gano-reseller-enhancements plugin is active
- Check gano_reseller_cart_url() function exists
- Validate PFID mappings in plugin
- Output: "Plugin health: OK/ISSUES"

**Paso 4: Staging vs Production Parity** (5 min)
- Verify staging.gano.digital mirrors production
- Check if code changes deployed to staging
- Output: "Staging sync: ✅ or ⚠️"

**Paso 5: Security & Rate Limiting** (5 min)
- Verify CSP headers allow godaddy.com
- Check rate limiting rules (429 active)
- Validate nonce checks
- Output: "Security: All checks pass or ISSUES"

**Paso 6: Missing Data Audit** (5 min)
- Check for placeholder text (NIT, phone, contact)
- Verify legal/compliance docs present
- Check support contact channels
- Output: "Missing data: NONE or LIST"

**Paso 7: Generate Recommendations** (5 min)
- Prioritize gaps by impact (Critical > High > Medium)
- Suggest fixes with effort estimates
- Recommendation output: Markdown report

**Output Artifact**:
```markdown
# RCC Audit Report — 2026-04-08

## Executive Summary
Configuration readiness: 60% (4 Critical gaps)

## Current State
- PFIDs mapped: 0/5 ⚠️ CRITICAL
- CTAs wired: 10/10 ✅
- Plugin active: ✅
- Staging synced: ✅
- Security: ✅
- Missing data: NIT + phone ⚠️ MEDIUM

## Critical Gaps
1. No PFID mappings yet (dependency: Cursor implementation)
2. Missing legal contact info (dependency: Diego)
3. Support channels not documented (dependency: Diego)

## Recommendations
1. PRIORITY 1: Cursor maps first 2 PFIDs (est. 2 days)
2. PRIORITY 2: Diego provides NIT + phone (est. 1 hour)
3. PRIORITY 3: Docs support process (est. 1 hour)

## Next Steps
→ Await Cursor implementation of PFID mappings
→ Re-run audit after each mapping batch
```

**Post-Audit**:
- Antigravity → Claude: "Audit complete, see gaps report"
- Claude → Diego: "Audit shows 3 blockers, here's mitigation"
- Diego → Cursor: "Start PFID mappings (first 2 PFIDs)"
- Obsidian: Log findings in [[08-DEPENDENCIAS-RIESGOS#RCC Audit Gaps]]

---

### PROCESO 2: Cart Testing (E2E) Workflow

**Objetivo**: Validar flujo de carrito completo (product → checkout → order confirmation)

**Trigger**: Staging deployment or Cursor commits PFID changes

```
VS Code: "Staging deployed, ready for cart test"
  ↓
Diego: "/reseller-cart-test staging"
  ↓
```

**Ejecución** (15-20 min):

**Paso 1: Environment Setup** (1 min)
- Antigravity verifies target (staging.gano.digital or production)
- Loads `phase4-commerce` skill (PFID knowledge, test steps)
- Loads `phase4-commerce-rules` (guardrails: staging-first, security)

**Paso 2: Navigate to Product Page** (1 min)
- Opens staging.gano.digital
- Identifies "Get Started" CTA
- Screenshot: Homepage + CTA visible

**Paso 3: Click CTA → Reseller Cart** (2 min)
- Clicks "Get Started" button
- Browser navigates to `https://reseller.godaddy.com/cart?plid=PFID-XXX&...`
- Verify: No 404, no CORS errors, cart loads
- Screenshot: Reseller Store loaded

**Paso 4: Verify Product Details** (2 min)
- Confirm product name matches expectation (e.g., "WordPress Hosting - Annual")
- Verify price displayed correctly
- Check renewal period + billing interval
- Screenshot: Product details visible

**Paso 5: Add to Cart / Select Variant** (2 min)
- If 3-year plan available: Select it
- Verify bundle pricing applied
- Screenshot: Product variant selected

**Paso 6: Proceed to Checkout** (3 min)
- Click "Proceed to Checkout"
- Verify: No timeout (< 30 sec), no console errors
- Checkout form loads (domain, email, payment info fields)
- Screenshot: Checkout form visible

**Paso 7: Fill & Submit Order** (3 min)
- **Staging ONLY**: Use test email, test card 4111-1111-1111-1111
- **Production**: Real customer info (would be Diego test purchase)
- Fill all required fields
- Submit order
- Verify: No 500 error, order confirmed
- Screenshot: Order confirmation page

**Paso 8: Verify Order in RCC** (3 min)
- Wait 1-5 minutes (GoDaddy async processing)
- Check RCC (Reseller Control Center)
- Verify order appears with correct product + price + customer
- Screenshot: Order in RCC confirmed

**Paso 9: Verify Email Confirmation** (1 min)
- Check inbox (staging uses test@gano.digital)
- Verify GoDaddy email received
- Confirm email contains order details, login link
- Screenshot: Email confirmation visible

**Test Report Output**:
```markdown
# Cart E2E Test Report — Staging — 2026-04-08 14:30

## Test Execution

| Step | Result | Duration | Evidence |
|------|--------|----------|----------|
| 1. Env setup | ✅ PASS | 1 min | - |
| 2. Product page | ✅ PASS | 1 min | [screenshot_1.png] |
| 3. Click CTA | ✅ PASS | 2 min | [screenshot_2.png] |
| 4. Product details | ✅ PASS | 2 min | [screenshot_3.png] |
| 5. Add to cart | ✅ PASS | 2 min | [screenshot_4.png] |
| 6. Checkout | ✅ PASS | 3 min | [screenshot_5.png] |
| 7. Submit order | ✅ PASS | 3 min | [screenshot_6.png] |
| 8. RCC verify | ✅ PASS | 3 min | [screenshot_7.png] |
| 9. Email verify | ✅ PASS | 1 min | [screenshot_8.png] |

## Overall Result
🟢 **9/9 PASS** — STAGING READY FOR PRODUCTION

## Browser Recording
[Video file: cart-test-staging-2026-04-08.mp4 — 12 min, 45 MB]

## No Issues Found
- ✅ No CORS errors
- ✅ No timeouts
- ✅ No 500 errors
- ✅ No console JavaScript errors
- ✅ Order confirmed in RCC within 5 minutes
- ✅ Email received within 5 minutes

## Next Steps
→ Antigua gravity can now test PRODUCTION
→ If PROD also PASSES: Go-live approved
```

**Post-Test**:
- If **PASS**: Diego approves production test
- If **FAIL**: Claude + Cursor debug root cause, fix, re-test
- Evidence: Video + screenshots + report in [[06-METRICAS-PROGRESO]]

---

## 🔄 Ciclos de Desarrollo (Iterativo)

### Ciclo 1: PFID Mapping → Test → Fix

```
Week 1 (Mon-Fri):
  Mon: RCC Audit identifies "0 PFIDs mapped" (CRITICAL)
  Tue: Cursor starts PFID-001 mapping
  Wed: Cursor commits PFID-001 + PFID-002
  Thu: VS Code deploys to staging
  Thu afternoon: Antigravity runs /reseller-cart-test staging
       ↓
       IF FAIL (e.g., "PFID-001 shows 404"):
         Claude analyzes → "Check RCC, PFID might be wrong"
         Cursor verifies with GoDaddy → "PFID-001 is valid"
         Cursor adds more logging
         Re-test → PASS
       ↓
  Fri: All PFIDs mapped + tested ✅
       Staging 9/9 PASS
       Ready for production testing
```

### Ciclo 2: Staging → Production Promotion

```
Week 2 (Mon-Tue):
  Mon: Diego approves production testing
  Mon afternoon: Antigravity runs /reseller-cart-test production
       ↓
       IF PASS:
         All systems ready
         Go-live approved
         Update TASKS.md: Fase 4 COMPLETE
       ↓
       IF FAIL:
         Claude analyzes production-specific issue
         (e.g., "PFID not in production RCC yet")
         GoDaddy support escalation
         Fix + re-test
```

---

## 📋 Proceso: Daily Standup (Async)

**Cadencia**: Daily (EOD)
**Formato**: Async notes in Obsidian (no meetings)
**Responsable**: Cada agente

**Template** (en Obsidian daily note):
```
## Daily Standup — 2026-04-08

### Claude
- Did: Reviewed Cursor PR #123, approved PFID mapping logic
- Doing: Analyzing RCC audit findings
- Blockers: None
- Next: Document recommendations

### Cursor
- Did: Implemented PFID-001 + PFID-002 mappings, committed
- Doing: Waiting for VS Code staging deploy
- Blockers: None
- Next: Review Antigravity test results

### VS Code
- Did: Deployed staging update, smoke test PASS
- Doing: Monitoring production (routine checks)
- Blockers: None
- Next: Stand by for cart test execution

### Antigravity
- Did: Completed framework setup, awaiting Diego activation
- Doing: Ready to execute /reseller-cart-test on signal
- Blockers: Diego activation pending
- Next: First test run (TBD)

### Obsidian
- Did: Updated [[06-METRICAS-PROGRESO]] with daily standup logs
- Doing: Logging all findings
- Blockers: None
- Next: Consolidate weekly summary
```

**Diego Review** (next morning, 15 min):
- Reads all standup notes
- Identifies blockers
- Takes decisions if needed
- Updates priorities
- Communicates via Obsidian or Slack

---

## 🎯 Proceso: Weekly Review & Planning

**Cadencia**: Every Friday 5 PM
**Duración**: 30 min
**Formato**: Diego + all agents (async + sync)

**Agenda**:
1. **Progress vs. Plan** (5 min)
   - What did we accomplish this week?
   - Are we on track for Phase 4 deadline (Apr 20)?
   - What's the blockers impact?

2. **Metrics Review** (10 min)
   - Test pass rate
   - Cycle time (idea → deployment)
   - Agent velocity
   - Burn-down chart

3. **Risk Assessment** (5 min)
   - Any new risks emerged?
   - Mitigations in place?
   - Escalations needed?

4. **Planning for Next Week** (10 min)
   - Priority tasks (roadmap alignment)
   - Agent assignments (who does what)
   - Dependencies (blockers to unblock)
   - Milestones (what must ship)

**Output**: Obsidian note with decisions, updated in [[02-FASES-ROADMAP]]

---

## 🚨 Proceso: Blocker Resolution

**When blocker detected** (any agent):

**Step 1: Identify & Communicate** (immediate)
```
Antigravity: "Cart test FAIL: PFID-001 shows 404"
  ↓
Antigravity → Claude + Diego: "Blocker: PFID-001 not found in RCC"
  ↓
Obsidian: Log in [[08-DEPENDENCIAS-RIESGOS#Bloqueadores Activos]]
```

**Step 2: Root Cause Analysis** (5-15 min)
```
Claude: Proposes 3 hypotheses
  1. PFID doesn't exist in RCC (ask GoDaddy)
  2. PFID exists but wrong ID in plugin (fix Cursor)
  3. Reseller cart URL incorrect (fix gano_reseller_cart_url)
  ↓
Team: Debug hypothesis 1 first (most likely)
```

**Step 3: Mitigation** (depends on blocker type)
```
Type A: GoDaddy dependency (external)
  → Diego opens GoDaddy support ticket
  → Estimated resolution: 1-2 days

Type B: Code issue (internal)
  → Cursor fixes + commits
  → VS Code deploys to staging
  → Antigravity re-tests
  → Estimated resolution: 1-4 hours

Type C: Design decision (Diego)
  → Diego decides on path forward
  → Updates timeline if needed
```

**Step 4: Verification & Resolution**
```
Once mitigation applied:
  → Test again (Antigravity)
  → Verify PASS
  → Update blocker status → "RESOLVED"
  → Continue
```

**Escalation Path**:
- If blocker > 4 hours to resolve: Diego gets involved
- If blocker threatens Phase 4 deadline: Timeline adjustment discussion

---

## 📤 Proceso: Deployment Pipeline

### Staging Deployment

```
1. Cursor commits feature to branch
2. VS Code: git pull, npm install, wp-cli test
3. VS Code: Deploy to staging.gano.digital
   - Copy code to staging environment
   - Run database migrations (if any)
   - Clear cache
   - Smoke test: homepage loads, no 500 errors
4. VS Code: "Staging deployment PASS"
5. Antigravity: Wait for signal to test
6. Antigravity: Execute test workflow
7. Result: PASS/FAIL report
```

### Production Deployment

**Only after Staging PASS + Antigravity approval**

```
1. Cursor: Merge feature branch to main
2. VS Code: git pull main
3. VS Code: npm install, wp-cli test
4. VS Code: Backup production (automated)
5. VS Code: Deploy to production.gano.digital
   - Copy code to production
   - Run migrations
   - Clear cache
   - Smoke test
6. VS Code: Monitor for errors (first hour)
7. Obsidian: Log deployment (timestamp, changes, who deployed)
8. Result: Production live ✅ or rollback needed
```

**Rollback Plan** (if production breaks):
```
1. Detect error (monitoring alert or user report)
2. Diego: Approve rollback
3. VS Code: git revert [commit]
4. VS Code: Re-deploy
5. Antigravity: Quick smoke test
6. Root cause analysis (post-incident)
```

---

## 📊 Proceso: Metrics & Reporting

**Responsable**: Obsidian (logging), Claude (analysis), Diego (decisions)

**Métrica 1: Test Pass Rate**
```
Definition: % of cart tests that PASS (9/9 steps)
Tracked: Per environment (staging, production)
Frequency: Daily (after test execution)
Target: >90% by Fase 4 end
Trend: Watch for regressions
```

**Métrica 2: Cycle Time**
```
Definition: Time from Cursor code → Deployed + Tested
Example: "Code commit Tue 10 AM → Staging test Wed 2 PM = 28 hours"
Target: < 24 hours
Trend: Shorten as workflows mature
```

**Métrica 3: PFID Mapping Progress**
```
Definition: N mapped out of 5 required
Tracked: In rcc-pfid-checklist.md
Frequency: Per mapping completion
Target: 100% by Apr 15
Blockers: GoDaddy PFID availability, Cursor capacity
```

**Métrica 4: Blocker Count**
```
Definition: Active blockers preventing progress
Target: 0 (immediate resolution goal)
Tracked: In [[08-DEPENDENCIAS-RIESGOS]]
Actions: If > 2: Diego escalates
```

---

## ✅ Checklist: Procesos Entendidos

- [ ] Entiendo cómo funciona RCC Audit (/phase4-rcc-audit)
- [ ] Entiendo cómo funciona Cart Testing (/reseller-cart-test)
- [ ] Entiendo el ciclo iterativo (PFID → Test → Fix)
- [ ] Entiendo cómo reportar standups
- [ ] Entiendo cómo escalar bloqueadores
- [ ] Entiendo el pipeline de deployment (staging → production)
- [ ] Entiendo las métricas clave

---

**Última revisión**: 2026-04-06
**Mantenedor**: Antigravity (workflows), Claude (docs)
**Próxima revisión**: Post-Phase 4 launch (mayo 2026)

