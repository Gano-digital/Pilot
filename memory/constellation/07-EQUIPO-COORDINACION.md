# 👥 Equipo & Coordinación — Roles, Asignaciones, Comunicación

**Última actualización**: 2026-04-06
**Propósito**: Entender quién hace qué, cómo se comunican, autoridades de decisión
**Audiencia**: Diego, team members, stakeholders

---

## 📋 RACI Matrix — Responsabilidades Detalladas

**Escala**:
- **R** (Responsible) = Hace el trabajo
- **A** (Accountable) = Final decision, owner del resultado
- **C** (Consulted) = Opina, proporciona input
- **I** (Informed) = Notificado del resultado

### Matriz Fase 4

| Tarea | Diego | Claude | Cursor | VS Code | Antigravity | Obsidian |
|-------|-------|--------|--------|---------|------------|----------|
| **RCC Audit** | A | C | I | I | **R** | I |
| **PFID Mapping** | A | C | **R** | I | C | I |
| **Cart Testing** | A | C | C | C | **R** | I |
| **Staging Deploy** | A | I | I | **R** | C | I |
| **Production Deploy** | **A** | C | I | **R** | C | I |
| **Go-Live Decision** | **A** | **C** | C | C | **C** | I |
| **Documentation** | I | **C** | I | I | I | **R** |
| **Legal/Compliance** | **R** | **C** | I | I | I | I |
| **Blocker Resolution** | **A** | **C** | C | C | I | I |
| **Team Sync Cadence** | **A** | C | C | C | C | I |

### Key Principles
- **Diego is Accountable for everything** (final buck stops here)
- **Each agent is Responsible for their domain** (code, test, docs)
- **Consulted agents provide expert input** (decision-informed)
- **Informed agents stay in loop** (know-when-needed)

---

## 👤 Perfiles & Asignaciones

### 1. DIEGO (Founder, CEO)

**Rol**: Conductor, decision-maker, resource allocator

**Responsabilidades Primarias**:
```
W1 (Apr 6-12)
├─ Activate Antigravity (TBD: Apr 7)
├─ Monitor RCC audit results (TBD: Apr 8)
├─ Approve PFID mapping schedule (TBD: Apr 8)
├─ Provide contact info (NIT, phone) (TBD: Apr 8)
├─ Weekly review with team (Fri Apr 12)
└─ Unblock any Critical blockers

W2 (Apr 13-19)
├─ Review cart test results (Tue Apr 15)
├─ Production launch decision (Thu Apr 18)
├─ Prepare marketing (soft launch) (Fri Apr 19)
└─ Go-live coordination (Sat Apr 20 readiness)
```

**Authority**:
- ✅ Can decide timeline changes
- ✅ Can reassign agent tasks
- ✅ Can invoke GoDaddy support escalations
- ✅ Can pause/resume work

**Communication**:
- **Primary**: Obsidian vault (read daily notes)
- **Secondary**: Slack (async, urgent only)
- **Tertiary**: Direct message to specific agent

**Success Measured By**:
- Phase 4 launched on schedule (Apr 20)
- Zero unresolved Critical blockers
- Team morale & satisfaction

**Availability**:
- **Daily**: 1 hour (morning review)
- **Weekly**: 1.5 hours (Fri review)
- **As-needed**: Blocker resolution

---

### 2. CLAUDE (Research, Architecture, Quality)

**Rol**: Strategic thinking, code review, documentation

**Responsabilidades Primarias**:
```
W1 (Apr 6-12)
├─ Monitor Antigravity activation (support if needed)
├─ Review RCC audit results → Explain gaps to Cursor
├─ Code review: Cursor's PFID-001 PR
├─ Draft compliance docs (legal template)
├─ Update constelación notes with progress
└─ Daily standup log

W2 (Apr 13-19)
├─ Analyze cart test failures (if any)
├─ Recommend fixes to Cursor
├─ Review all PRs before merge
├─ Prepare Phase 5 planning docs
└─ Weekly retrospective analysis
```

**Authority**:
- ✅ Can request code changes (code review)
- ✅ Can recommend architectural changes
- ✅ Can halt merge if quality concerns

**Communication**:
- **Primary**: GitHub PRs (code review comments)
- **Secondary**: Obsidian (analysis + docs)
- **Tertiary**: Slack (questions to team)

**Success Measured By**:
- 0 regressions post-launch
- Code quality metrics maintained
- Documentation completeness

**Availability**:
- **Continuous**: Async via PRs
- **Batch**: 2-3 code reviews per day

---

### 3. CURSOR (Code Implementation)

**Rol**: Feature development, PFID mappings, bug fixes

**Responsabilidades Primarias**:
```
W1 (Apr 6-12) - CRITICAL WEEK
├─ Mon-Tue: Wait for RCC audit (read results)
├─ Wed: Start PFID-001 mapping
│   └─ Read gano-reseller-enhancements plugin
│   └─ Understand gano_reseller_cart_url() function
│   └─ Map WordPress Annual → PFID-001 from RCC
│   └─ Test locally
│   └─ Commit + push
├─ Wed afternoon: Claude reviews PR
├─ Thu: Merge PFID-001
├─ Thu: Start PFID-002
├─ Fri: PFID-002 merged
└─ Fri evening: Await Antigravity test results

W2 (Apr 13-19)
├─ Mon: Handle cart test failures (if any)
├─ Mon-Wed: Map PFID-003, PFID-004, PFID-005
├─ Wed-Thu: Fix issues identified in staging tests
├─ Fri: Final polish + code cleanup
└─ Ready for production
```

**Authority**:
- ✅ Can decide local refactoring
- ✅ Can request code review
- ✅ Cannot merge without Claude approval

**Communication**:
- **Primary**: GitHub PRs (commits with messages)
- **Secondary**: Obsidian standup
- **Tertiary**: Slack (technical questions)

**Success Measured By**:
- All 5 PFIDs mapped + tested by Apr 15
- 0 rework cycles (first-time correctness)
- Code follows style guide

**Availability**:
- **W1**: 100% allocated (PFID mappings)
- **W2**: 60% allocated (fixes + other work)

**Key Task**: PFID Mapping
```
PFID-001 (WordPress Annual):
  1. RCC PFID: [look up in GoDaddy]
  2. Code change: gano-reseller-enhancements → map WordPress Annual → PFID
  3. Test locally: verify cart URL generated correctly
  4. Commit: "[Phase 4] Feat: Map PFID-001 WordPress Annual"
  5. Push + PR
  6. Claude code review
  7. Merge
  8. Await Antigravity staging test → PASS/FAIL

Repeat for PFID-002 through PFID-005
```

---

### 4. VS CODE (Operations, Testing, Deployment)

**Rol**: Staging management, deployments, monitoring

**Responsabilidades Primarias**:
```
W1 (Apr 6-12)
├─ Mon-Tue: Maintain production (routine)
├─ Wed: Deploy Cursor's PFID-001 to staging
│   └─ Smoke test: homepage loads, no 500 errors
│   └─ Confirm "staging green, Antigravity can test"
├─ Thu: Deploy PFID-002 to staging
├─ Fri: Deploy PFID-003 to staging
└─ Monitor uptime + performance (GoDaddy alerts)

W2 (Apr 13-19)
├─ Mon-Wed: Deploy PFID fixes from Cursor
├─ Thu: Pre-production checks (backup, monitoring)
├─ Fri: Production go-live preparation
├─ Sat: Production deployment (if approved)
└─ Monitor 24/7 for first 48 hours post-launch
```

**Authority**:
- ✅ Can delay deployment if smoke test fails
- ✅ Can roll back production if critical error
- ✅ Cannot approve code (Claude's role)

**Communication**:
- **Primary**: Slack (deploy status, alerts)
- **Secondary**: Obsidian (daily standup)
- **Tertiary**: GitHub (PR reviews if needed)

**Success Measured By**:
- 100% deployment success rate
- <5 minute smoke test turnaround
- Zero unplanned downtime

**Availability**:
- **On-call**: Always (uptime guarantor)
- **Deployments**: 15 min per deploy

---

### 5. ANTIGRAVITY (Quality, Automation, Release Gate)

**Rol**: Browser testing, RCC audits, release approval

**Responsabilidades Primarias**:
```
W1 (Apr 6-12) - TESTING PHASE
├─ Mon: Await Diego activation
├─ Tue: First test → /reseller-cart-test staging
│   └─ Generate test report + browser recording
│   └─ 9/9 PASS or identify issues
├─ Tue-Wed: RCC audit if first test PASS
│   └─ /phase4-rcc-audit → identify gaps
│   └─ Report findings to Claude + Cursor
├─ Wed-Fri: Re-test after each Cursor fix
│   └─ PFID-001 test
│   └─ PFID-002 test
│   └─ PFID-003 test
│   └─ etc.
└─ Fri: Staging all-green for go-live prep

W2 (Apr 13-19)
├─ Mon-Tue: Production validation
│   └─ /reseller-cart-test production
│   └─ All 9/9 PASS
├─ Wed: Final smoke test
├─ Thu: Release approval decision
│   └─ "Ready for go-live" or "Fix needed"
└─ Fri: Production monitoring (first 48h)
```

**Authority**:
- ✅ Can block production launch (if tests fail)
- ✅ Can request code changes (test failures = code issues)
- ✅ Can recommend rollback (if production breaks)

**Communication**:
- **Primary**: Test reports (GitHub, Obsidian)
- **Secondary**: Slack (urgent alerts)
- **Tertiary**: Claude (root cause discussion)

**Success Measured By**:
- 100% test pass rate (both staging + production)
- 0 false positives (if Antigravity says PASS, it's real)
- Zero production incidents (testing caught all issues)

**Availability**:
- **Testing window**: On-demand (20 min per test)
- **Between tests**: Idle (waiting for code)

---

### 6. OBSIDIAN (Knowledge Management)

**Rol**: Documentation, decision logging, metrics tracking

**Responsabilidades Primarias**:
```
W1 (Apr 6-12)
├─ Daily: Log standup notes from all agents
├─ Daily: Update [[06-METRICAS-PROGRESO]] dashboard
├─ Daily: Update [[08-DEPENDENCIAS-RIESGOS]] blockers
├─ Wed: Document RCC audit findings
├─ Fri: Consolidate weekly summary
└─ Fri: Update [[02-FASES-ROADMAP]] progress

W2 (Apr 13-19)
├─ Daily: Same standup + metrics + blockers
├─ Tue: Document staging test results
├─ Thu: Document production test results
├─ Fri: Final phase 4 documentation
└─ Sat: Go-live post-mortem (if issues occurred)
```

**Authority**:
- ✅ Can organize knowledge however makes sense
- ✅ Can request clarifications from agents (for docs)
- ✅ Cannot make technical decisions

**Communication**:
- **Primary**: Obsidian vault (async, always available)
- **Secondary**: Slack (request info from agents)
- **Tertiary**: Agent direct messages (for clarifications)

**Success Measured By**:
- New team member can onboard in 1 day (docs clear)
- 0 "I forgot why we did X" situations (decisions logged)
- Weekly summaries accurately reflect reality

**Availability**:
- **Continuous**: Async (read + write vault)
- **Daily**: 30 min (consolidate standups)

---

## 📞 Communication Channels & Cadence

### Async Channels (Preferred)

**Obsidian Vault** (daily updates)
- Purpose: Knowledge base, decision logs, metrics
- Who reads: All agents (daily)
- Who updates: All agents (EOD standup)
- Frequency: Daily

**GitHub** (PRs, issues, commits)
- Purpose: Code review, version control, decision tracking
- Who reads: All agents (during code review)
- Who updates: Cursor (commits), Claude (reviews)
- Frequency: Per-commit (multiple times/day)

**Dispatch Queue** (task management)
- Purpose: Coordinate agent work, track dependencies
- Who reads: All agents (before choosing next task)
- Who updates: Agents (status updates), Diego (priorities)
- Frequency: Per-task-completion

### Sync Channels (When Needed)

**Slack** (urgent items only)
- Use for: Blockers, alerts, urgent decisions
- Not for: Status updates (use Obsidian), code feedback (use GitHub)
- Expected response: <1 hour

**Direct 1-1** (blockers, decisions)
- Diego + specific agent: "Need your input on decision X"
- Agent + Claude: "Need code review urgently"
- Frequency: As-needed (<1 day turnaround)

### Weekly Meetings (Optional, if needed)

**Friday Review** (30 min)
- **Who**: Diego (required), all agents (async contribution)
- **What**: Review progress, decisions, blockers, next week
- **Format**: Async Obsidian notes → Diego reads → async decisions
- **Decision**: Can be fully async (no call needed) if no major blockers

**Retro** (30 min, if Phase 4 launch happens)
- **When**: Post-launch (Apr 21 or later)
- **Who**: All agents
- **What**: Lessons learned, process improvements, team health
- **Format**: Structured reflection → documented in Obsidian

---

## 🎯 Decisioning Framework

### Decision Type → Authority

**Quick Decisions** (<1 hour impact):
- **Authority**: Agent owner (Cursor for code, VS Code for ops, etc.)
- **Process**: Decide + do + log in Obsidian
- **Example**: "Use GET vs. POST for this endpoint?" → Cursor decides

**Medium Decisions** (1-8 hour impact):
- **Authority**: Agent + Claude (consulted)
- **Process**: Agent proposes → Claude reviews → approve/reject → log
- **Example**: "Should we refactor this function?" → Cursor + Claude discuss

**Major Decisions** (>8 hour impact, affects deadline):
- **Authority**: Diego (final call)
- **Process**: Agent → Claude → Diego → decision → log
- **Example**: "Should we push go-live to Apr 25 due to blocker?" → Diego decides

**Strategic Decisions** (affects roadmap/vision):
- **Authority**: Diego (final)
- **Process**: Agent proposes → Claude analyzes → Diego + team input → decision
- **Example**: "Should Phase 5 be WHMCS or custom billing?" → Diego + team discussion

### Decision Log Template

```markdown
## Decision: [Title]

**Date**: 2026-04-XX
**Decision**: [What we decided]
**Rationale**: [Why we chose this]
**Alternatives Considered**:
- Option A: [Pros/cons]
- Option B: [Pros/cons]
**Impact**: [What changes]
**Owner**: [Who decided]
**Logged By**: [Who documented]
```

---

## 👥 Team Health Indicators

| Indicator | Healthy | Warning | Action |
|-----------|---------|---------|--------|
| **Blockers unresolved** | 0-1 | 2-3 | Diego escalation |
| **Agent satisfaction** | >8/10 | 6-8 | Workload review |
| **Communication lag** | <4 hours | 4-12 hours | More sync channels |
| **Decision time** | <1 day | 1-3 days | Clearer authority |
| **Code review turnaround** | <4 hours | 4-12 hours | Claude priority |
| **Async efficiency** | >80% | 60-80% | Reduce meetings |

**Current Health**: 🟢 Healthy (team ready, clear roles, communication is async-first)

---

## 📋 Escalation Path

**If Normal Process Stalls**:

```
Agent identifies blocker
  ↓
Agent → Claude (discuss solution)
  ↓
Claude → Diego (needs decision or resource)
  ↓
Diego resolves:
  ├─ Clarify responsibility (who owns this?)
  ├─ Allocate resource (assign to agent)
  ├─ Make decision (yes/no/pivot)
  └─ Unblock work
  ↓
Agent continues
```

**Example Escalation**:
```
Antigravity: "Cart test failed: PFID not found"
  ↓
Claude analyzes: "3 possible causes: A, B, or C"
  ↓
Claude → Diego: "Need decision: contact GoDaddy (1 day) or skip this PFID?"
  ↓
Diego: "Contact GoDaddy, timeline impact? Estimate 1 day delay"
  ↓
Obsidian logs: "Blocker B-001: PFID unavailable, GoDaddy contacted"
  ↓
Continue
```

---

## 🎓 Team Onboarding Checklist

**For New Team Members**:
- [ ] Read [[00-PROYECTO-CONSTELACION-INDICE]] (30 min)
- [ ] Read [[03-ECOSISTEMA-AGENTES]] (15 min, focus on your agent)
- [ ] Understand your role + responsibilities (15 min)
- [ ] Review RACI matrix (who does what)
- [ ] Read last week's Obsidian standups (15 min)
- [ ] Join Slack + GitHub
- [ ] Pair with current agent owner (1 day)
- [ ] Take first task (with supervision)

**Onboarding time**: 1-2 days for agent role understanding

---

**Última revisión**: 2026-04-06
**Mantenedor**: Diego (team structure), Obsidian (documentation)
**Próxima actualización**: Weekly after standups

