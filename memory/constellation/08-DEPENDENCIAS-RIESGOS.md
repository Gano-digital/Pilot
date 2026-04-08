# 🚨 Dependencias & Riesgos — Blockers, Dependencies, Mitigation Strategies

**Última actualización**: 2026-04-06 21:45 UTC
**Propósito**: Identificar riesgos, dependencias externas, planes de mitigación
**Audiencia**: Diego (decisions), team leads, risk owners

---

## 🔴 Bloqueadores Activos (Critical Now)

### B-001: Diego Antigravity Activation

**Severidad**: 🔴 **CRITICAL**
**Estado**: 🟠 In Progress (target: Apr 7 EOD)
**Impacto**: Blocks all cart testing (can't progress without this)

**Descripción**:
- Diego must install Antigravity desktop app (~10 min)
- Configure workspace + Chrome extension (~5 min)
- Set security settings (~5 min)
- **Total**: 20 minutes of human effort

**Dependencia**:
- Antigravity setup complete → First cart test can run
- First cart test PASS → RCC audit can begin
- RCC audit PASS → Cursor can start PFID mappings

**Critical Path Impact**:
```
If DELAYED 1 day:
  ├─ RCC audit pushed to Apr 9 (vs. Apr 8)
  ├─ PFID mapping starts Apr 10 (vs. Apr 9)
  ├─ Staging ready Apr 14 (vs. Apr 13)
  ├─ Production ready Apr 16 (vs. Apr 15)
  └─ Go-live risk: 2-3 day buffer remaining (still OK)

If DELAYED 3 days:
  ├─ Staging ready Apr 17
  ├─ Production ready Apr 18
  └─ Go-live risk: Critical (only 2 days buffer)
```

**Mitigación**:
- [x] Clear documentation (ANTIGRAVITY-QUICKSTART.md)
- [x] 10-min setup guide (ready)
- [ ] Diego activates by EOD Apr 7 (action item)
- [x] Support available if issues (Claude standby)

**Owner**: Diego
**Próximo Paso**: Install Antigravity (Apr 7, 20 min)

---

### B-002: PFID Availability in RCC

**Severidad**: 🟡 **HIGH**
**Estado**: 🟡 Unknown (assumption: available, not verified)
**Impacto**: If PFIDs not in RCC, can't create cart URLs

**Descripción**:
- GoDaddy Reseller must have 5+ PFIDs for WordPress products
- These PFIDs are what we map to (e.g., PFID-12345)
- Assumption: They exist (standard GoDaddy offerings)
- Risk: Non-standard setup or account restrictions

**Root Cause Investigation**:
1. Log into RCC (Reseller Control Center)
2. Browse Manage Products
3. Find WordPress product family
4. Note down actual PFID numbers

**Critical Path Impact**:
```
If PFIDs missing/wrong:
  ├─ Contact GoDaddy support (1 day)
  ├─ Possible account reconfiguration (1-2 days)
  ├─ Staging testing delayed (2-3 days total)
  └─ Go-live pushed to Apr 22+ (HIGH RISK)
```

**Mitigación**:
- [x] Assumption documented (expected PFID set)
- [ ] Verify PFIDs exist (action: Cursor, when ready)
- [x] GoDaddy support ticket template ready
- [x] Fallback: Use placeholder PFIDs for staging test (works)

**Owner**: Cursor (verify) / GoDaddy Support (provide)
**Próximo Paso**: Verify PFIDs on Apr 8-9 (during RCC audit)

---

### B-003: Missing Compliance Data

**Severidad**: 🟡 **MEDIUM** (can parallelize)
**Estado**: 🟡 Pending Input
**Impacto**: Legal/contact requirements for launch

**Descripción**:
- NIT (Número de Identificación Tributaria) → Colombia tax ID
- Phone number (customer support contact)
- Legal address (for business operations)
- Support contact email/hours

**Critical Path Impact**:
```
If delayed:
  ├─ Does NOT block technical launch (separate from cart)
  ├─ Can be fixed in parallel (non-critical path)
  ├─ Needed before accepting real customers (Day 1 soft launch)
  └─ Target: Have before Apr 20 (go-live day)
```

**Mitigación**:
- [x] Placeholder identified in website
- [x] Template legal/privacy docs drafted
- [ ] Diego provides NIT + phone (action item, 1 hour)
- [x] Legal review optional (check with accountant if available)

**Owner**: Diego (input) / Claude (documentation)
**Próximo Paso**: Diego provides data (Apr 8)

---

## 🟡 Dependencias Externas (Not Our Control)

### D-001: GoDaddy Reseller Store Availability

**Tipo**: External Service Dependency
**Criticidad**: 🔴 **CRITICAL**
**Estado**: ✅ Verified Active (Reseller account works)

**Descripción**:
- GoDaddy Reseller Store must be online and operational
- If down: Customers can't checkout, revenue stops
- SLA: 99.95% uptime (GoDaddy standard)

**Impact if Down**:
- Customer checkouts fail
- Orders don't get created
- Soft launch at risk (if during launch week)

**Mitigación**:
- [x] Account verified active (works today)
- [x] GoDaddy monitoring (uptime alerts configured)
- [x] Fallback: Manual order process (temporary)
- [x] Communication ready: "Service temporarily down, try again in 1 hour"

**Owner**: GoDaddy Support
**Monitoring**: GoDaddy alerts (auto)

---

### D-002: GoDaddy Managed WordPress Infrastructure

**Tipo**: External Infrastructure
**Criticidad**: 🔴 **CRITICAL**
**Estado**: ✅ Verified Healthy (99.95% uptime)

**Descripción**:
- gano.digital runs on GoDaddy Managed WordPress
- If down: Website inaccessible, no checkout, no content
- SLA: 99.95% guaranteed

**Impact if Down**:
- Business stops
- Customers lose access
- Revenue = 0

**Mitigación**:
- [x] Daily backups (automated, tested)
- [x] Staging site (for quick rollback)
- [x] DDoS protection (GoDaddy)
- [x] Uptime monitoring (GoDaddy alerts)

**Owner**: GoDaddy
**Monitoring**: GoDaddy alerts (auto)

---

### D-003: Internet Connectivity & Payment Processing

**Tipo**: External Network/Service
**Criticidad**: 🟡 **HIGH**
**Estado**: ✅ Verified Working

**Descripción**:
- Customer browser → GoDaddy payment processor
- If broken: Checkout fails mid-transaction
- Risk: Network issues, payment gateway down

**Impact if Down**:
- Checkout fails
- Orders don't process
- Customer frustration

**Mitigación**:
- [x] Error handling in cart (try again messages)
- [x] Test payment processing (staging test card)
- [x] GoDaddy payment monitoring (auto)
- [x] Customer support template (if issues occur)

**Owner**: GoDaddy + ISP
**Monitoring**: Transaction logs

---

## 🟠 Dependencias Internas (Our Control)

### D-004: Cursor Availability (PFID Mapping)

**Tipo**: Team Resource
**Criticidad**: 🟡 **HIGH**
**Estado**: ✅ Allocated (Cursor assigned full-time Apr 9-12)

**Descripción**:
- Cursor is sole developer for PFID mappings
- 5 PFIDs × 4 hours per PFID = ~20 hours of work
- Timeline: 4 days (Apr 9-12) at 5 hours/day

**Risk: Cursor Overloaded**:
- If other urgent work appears → PFID mappings delayed
- Mitigation: Block calendar (no interruptions Apr 9-12)

**Risk: Cursor Unavailable**:
- If sick/unavailable → No one else can map PFIDs
- Mitigation: Parallel backup? (train Claude or VS Code as backup) [LOW PRIORITY]

**Mitigación**:
- [x] Cursor allocated + calendar blocked
- [x] Clear PFID spec (plugin knows what to do)
- [x] Claude available for code review (fast turnaround)
- [ ] Backup plan if needed (optional, low probability)

**Owner**: Cursor (execution), Diego (allocation)
**Status**: On Track

---

### D-005: Claude Code Review Availability

**Tipo**: Team Resource
**Criticidad**: 🟡 **HIGH**
**Estado**: ✅ Allocated

**Descripción**:
- Claude must review all Cursor PRs (5 PRs expected)
- SLA: <4 hour turnaround per PR (so CI/CD is fast)
- Risk: If Claude delays → Pipeline backs up

**Mitigación**:
- [x] Claude allocated (30% time)
- [x] Clear review criteria (PFID mapping checklist)
- [x] Async process (no meetings needed)
- [x] If urgent: Slack for same-day review

**Owner**: Claude
**Status**: Allocated & Ready

---

### D-006: Antigravity Setup Quality

**Tipo**: Tool/Framework
**Criticidad**: 🟡 **MEDIUM**
**Estado**: ✅ Setup 100% (framework ready)

**Descripción**:
- Antigravity framework (rules, workflows, skills) is complete
- Risk: Something missing in setup → Test doesn't work
- Impact: Can't validate cart, delays everything

**Mitigación**:
- [x] Framework documented (ANTIGRAVITY-QUICKSTART.md)
- [x] Workflows created + tested (dummy run)
- [x] Skills populated (PFID knowledge base)
- [x] Rules defined (guardrails)
- [x] First test execution will verify setup works

**Owner**: Antigravity / Diego (activation)
**Status**: Ready

---

## 📊 Risk Heat Map (Probability × Impact)

```
Probability
    High ┤
         │        D-004 (Cursor availability)
         │               [Medium risk]
         │
   Medium┤  B-002         B-001
         │  (PFID avail)  (Diego activation)
         │  [High risk]   [Medium risk]
         │
     Low┤                          D-001 (GoDaddy Reseller)
         │              B-003      D-002 (Infrastructure)
         │              (Compliance)[Low risk]
         │
         └────────────────────────────────────────
         Low         Medium        High
                    Impact
```

**Risk Color Coding**:
- 🟢 **Low**: Probability <10% AND Impact <2 day delay
- 🟡 **Medium**: Probability 10-30% OR Impact 2-5 day delay
- 🔴 **High**: Probability >30% AND Impact >5 day delay
- 🔥 **Critical**: Probability >50% OR Impact >1 week delay

---

## 📋 Detailed Risk Register

| # | Risk | Probability | Impact | Heat | Mitigation | Owner | Status |
|---|------|-------------|--------|------|-----------|-------|--------|
| **R-001** | Diego delayed Antigravity | 5% | Critical | 🟡 | Clear docs, 20-min install | Diego | 🟠 Active |
| **R-002** | PFID not in RCC | 10% | 2-3 days | 🟡 | Verify early, GoDaddy ticket | Cursor | 🟡 Monitored |
| **R-003** | Cart test finds major issues | 40% | 2-5 days | 🟡 | Debug process, buffer time | Antigravity | 🟡 Expected |
| **R-004** | Cursor capacity insufficient | 15% | 1-2 days | 🟢 | Can parallelize mappings | Cursor | 🟢 Low |
| **R-005** | GoDaddy downtime | 5% | Variable | 🟢 | Auto-monitoring, fallback | GoDaddy | ✅ Monitored |
| **R-006** | Staging ↔ Prod mismatch | 5% | 1-2 days | 🟢 | Daily sync, mirror verified | VS Code | ✅ Verified |
| **R-007** | Production launch fails | 2% | Critical | 🟢 | Rollback plan, staging PASS | All | ✅ Prepared |
| **R-008** | Missing compliance data | 60% | 0 days (parallel) | 🟢 | Not on critical path | Diego | 🟡 Pending |

---

## 🛡️ Mitigation Strategies by Risk Type

### Strategy 1: Early Detection

**For**: B-001, B-002, R-001, R-002
**How**: Test assumptions early, don't wait until last minute

```
B-001: Diego activation
  Action: Do on Apr 7 (not last-minute)
  Early: Know if setup issue by end of Day 1

B-002: PFID availability
  Action: Verify PFID during audit (Apr 8)
  Early: Know if GoDaddy issue by end of Day 2
```

### Strategy 2: Redundancy / Backup Plans

**For**: D-004, D-005, D-006
**How**: Identify backup resources or processes

```
D-004: Cursor unavailable
  Backup: Claude learns PFID mapping (low priority)

D-005: Claude code review slow
  Backup: Self-review checklist for Cursor (quality gate)

D-006: Antigravity setup broken
  Backup: Manual cart testing (slower, but works)
```

### Strategy 3: Time Buffer

**For**: R-003, R-004
**How**: Add extra time to critical path

```
PFID mapping timeline: 4 days (Apr 9-12)
  + 1 day buffer for issues
  ────────────────────────────────
  Target completion: Apr 13 (vs. Apr 12)
  Gives 2-day buffer before staging deadline
```

### Strategy 4: Escalation & Decision Authority

**For**: B-003, R-008
**How**: Clear decision owner, fast escalation

```
B-003: Missing compliance data
  Owner: Diego
  If blocking: Diego decides (include anyway, or delay to Apr 21)

R-008: Missing contact info
  Not blocking: Can be added after launch
  But needed before accepting customers
```

### Strategy 5: Continuous Monitoring

**For**: D-001, D-002, D-003
**How**: Real-time alerts, daily checks

```
GoDaddy services:
  - Uptime alerts (auto)
  - Daily health check (manual, 5 min)
  - Payment processing test (during staging test)
```

---

## 🎯 Critical Success Factors (CSF)

**Things That MUST Happen for Phase 4 Success**:

| # | CSF | Owner | Deadline | How to Know It's Done |
|---|-----|-------|----------|----------------------|
| **CSF-1** | Diego activates Antigravity | Diego | Apr 7 EOD | /reseller-cart-test runs without error |
| **CSF-2** | First cart test produces output | Antigravity | Apr 8 | Test report + browser recording exist |
| **CSF-3** | RCC audit identifies all gaps | Antigravity | Apr 8 | Audit report lists critical/high/medium items |
| **CSF-4** | Cursor maps PFID-001 & tests | Cursor | Apr 10 | Antigravity test PASS for PFID-001 |
| **CSF-5** | All 5 PFIDs working in staging | Cursor + Antigravity | Apr 13 | /reseller-cart-test PASS 9/9 |
| **CSF-6** | Production test validates all | Antigravity | Apr 15 | /reseller-cart-test production PASS 9/9 |
| **CSF-7** | Go-live approval given | Diego | Apr 18 | "Approved for launch" decision logged |
| **CSF-8** | Phase 4 live on gano.digital | VS Code | Apr 20 | Customers can access "Get Started" CTA |

**If ANY CSF fails**: Go-live is delayed (timeline adjustment needed)

---

## 📅 Risk Timeline

```
RISK EXPOSURE BY WEEK

W1 (Apr 6-12): HIGH RISK
├─ B-001: Antigravity activation (single point of failure)
├─ B-002: PFID availability (external dependency)
├─ D-004: Cursor capacity (mapping work begins)
└─ R-003: Cart test issues (unknown unknowns)
   Risk level: 🔴 HIGH (multiple critical paths)

W2 (Apr 13-19): MEDIUM RISK
├─ R-003: Remaining cart test issues
├─ D-002: GoDaddy stability (production environment)
└─ B-003: Compliance data (parallelize, not critical path)
   Risk level: 🟡 MEDIUM (fewer unknowns, mostly execution)

Launch Day (Apr 20): LOW RISK
└─ R-007: Production deployment (rollback plan ready)
   Risk level: 🟢 LOW (well-prepared, tested)
```

---

## 🔄 Continuous Risk Management

**Weekly Risk Review** (Friday 5 PM):
1. **Re-assess probabilities**: Has situation changed?
2. **Monitor indicators**: Any early warning signs?
3. **Update mitigations**: Are our plans still valid?
4. **Escalate new risks**: Any new blockers identified?

**Risk Review Questions**:
- [ ] Any blocker status changed?
- [ ] Any new risks emerged?
- [ ] Mitigations working as planned?
- [ ] Early warning signs detected?
- [ ] Timeline still on track?

**Risk Escalation Trigger**:
- If any risk goes from 🟡 to 🔴: Diego informed immediately
- If blocker appears: Discuss resolution same day

---

## ✅ Risk Owner Responsibilities

| Owner | Responsibilities |
|-------|-----------------|
| **Diego** | Monitor B-001, B-003, escalate if needed |
| **Cursor** | Monitor D-004, report if capacity exceeded |
| **Claude** | Monitor D-005, code review quality |
| **VS Code** | Monitor D-002, infrastructure health |
| **Antigravity** | Monitor D-006, test output quality |
| **All** | Report new risks immediately |

---

**Última revisión**: 2026-04-06 21:45 UTC
**Mantenedor**: Diego (decision owner), Obsidian (tracking)
**Próxima revisión**: Weekly (Fri EOD, or immediately if Critical blocker)
**Escalation Contact**: Diego (Slack: @diego if urgent)

