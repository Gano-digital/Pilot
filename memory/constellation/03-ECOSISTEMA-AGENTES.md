# 🤖 Ecosistema de Agentes — Roles, Responsabilidades, Integración

**Última actualización**: 2026-04-06
**Propósito**: Entender los 5 agentes, qué hace cada uno, cómo se integran
**Audiencia**: Diego (conductor), nuevos miembros del equipo

---

## 🏗️ Arquitectura de 5 Agentes

```
                         DIEGO (Conductor)
                              │
                ┌─────────────┼─────────────┐
                │             │             │
            ┌───▼──┐      ┌───▼──┐      ┌──▼──┐
            │CLAUDE│      │CURSOR│      │VS   │
            │(Chat)│      │(IDE) │      │CODE │
            └──┬───┘      └──┬───┘      └──┬──┘
               │             │             │
        ┌──────┴─────────────┼─────────────┴──────┐
        │                    │                    │
    ┌───▼────┐           ┌───▼──────┐        ┌───▼────┐
    │Antigra-│           │Obsidian  │        │Shared  │
    │vity    │           │(Docs)    │        │Resources
    │(QA)    │           └──────────┘        │ • Git  │
    └────────┘                               │ • MCP  │
                                             │ • Queue│
                                             └────────┘
```

---

## 👤 1. DIEGO (Human Conductor)

**Rol**: Fundador, CEO, decisor final, propietario de visión

**Responsabilidades Principales**:
- ✅ Definir dirección estratégica y prioridades
- ✅ Tomar decisiones mayores (plataforma, timing, features)
- ✅ Activar agentes (iniciar procesos, desbloquear)
- ✅ Supervisar progreso vs. plan
- ✅ Comunicación con stakeholders externos
- ✅ Manejo de recursos y timeline

**Tools que usa**:
- Obsidian vault (knowledge base)
- GitHub (repo, PRs, issues)
- Email/Slack (comunicación)
- Browser (testing manual cuando necesario)

**Métricas clave**:
- Decisiones tomadas por semana: 3-5
- Bloqueadores resueltos por semana: 1-2
- Progreso vs. plan: Track weekly

**Interface típico**:
- Lee constelación (este documento y referencias)
- Revisa metrics en [[06-METRICAS-PROGRESO]]
- Toma decisiones con contexto completo
- Comunica decisiones a agentes vía chat / GitHub

**Próximos 7 días**:
- Activar Antigravity (critical)
- Revisar resultados de primer test
- Decidir qué hace Cursor (fix PFID issues si hay)

---

## 🧠 2. CLAUDE (Research & Analysis Agent)

**Rol**: Investigación, arquitectura, decisiones técnicas, análisis SOTA

**Responsabilidades Principales**:
- ✅ SOTA research (competitive analysis, new technologies)
- ✅ Architecture design (component diagrams, data flows)
- ✅ Decision support (pros/cons, trade-offs)
- ✅ Code review & quality (suggestions, best practices)
- ✅ Documentation (guides, READMEs, changelogs)
- ✅ Consulting (debugging complex issues with Diego)

**Tools que usa**:
- Chat interface (main)
- MCP: Obsidian (read knowledge base)
- MCP: GitHub (read code, PRs, issues)
- Markdown (writing docs)
- Análisis: WebSearch, inference

**Typical Workflow**:
```
Diego: "Should we use WHMCS or custom billing for Fase 5?"
  ↓
Claude: Research WHMCS vs. DIY (architecture, cost, risk)
  ↓
Claude: Write pros/cons table
  ↓
Diego: Reviews analysis, decides
  ↓
Claude: Documents decision in [[01-MAPA-ESTRATEGICO]]
```

**Éxito medido por**:
- Decisiones informadas (90%+ context provided)
- Documentación completitud (0 orphaned decisions)
- Architecture quality (no major rework needed)
- Team understanding (docs are clear to read)

**Próximos 7 días**:
- Monitorear Antigravity test results
- Si fallan: Analizar root cause
- Documentar learnings en constelación

**Current focus**: Phase 4 commerce execution support

---

## 💻 3. CURSOR (Code Implementation Agent)

**Rol**: Feature development, plugin coding, template updates, code generation

**Responsabilidades Principales**:
- ✅ Feature implementation (code, plugins, themes)
- ✅ PFID mapping (gano-reseller-enhancements updates)
- ✅ Bug fixes (from test results)
- ✅ Code quality (linting, standards compliance)
- ✅ Git workflow (commits, branches, PRs)
- ✅ Collaboration (code reviews with team)

**Tools que usa**:
- IDE (Cursor, VS Code)
- PHP/JavaScript editors
- Git (local)
- MCP: GitHub (push PRs)
- WP-CLI (WordPress testing)

**Typical Workflow**:
```
Antigravity (to Cursor): "PFID-001 mapping failed, 404 in cart"
  ↓
Cursor: Read error logs, find bug in gano_reseller_cart_url()
  ↓
Cursor: Fix function, test locally
  ↓
Cursor: Commit & push to branch
  ↓
Cursor: Request code review from Claude
  ↓
Antigravity: Re-test in staging
  ↓
PASS ✅ → Merge to main
```

**Éxito medido por**:
- Code works first time (reduce rework)
- Zero security vulnerabilities
- Clean commits (clear messages, atomic changes)
- PR acceptance rate (reviewers approve easily)

**Próximos 7 días**:
- Await Antigravity audit results (Apr 8)
- Implement PFID mappings (Apr 9-12)
- Create PRs with test evidence
- Coordinate with Antigravity on fixes

**Current focus**: PFID mapping (5 WordPress plans → GoDaddy products)

---

## 🧪 4. VS CODE (Testing & Operations Agent)

**Rol**: Testing, deployment, staging management, operational health

**Responsabilidades Principales**:
- ✅ Unit/integration testing (npm test, WP test suite)
- ✅ Staging deployment (updates pre-production)
- ✅ Smoke testing (quick validation after deploy)
- ✅ Monitoring (uptime, errors, performance)
- ✅ Backup/recovery (data protection)
- ✅ Infrastructure (server health, DNS, SSL)

**Tools que usa**:
- Terminal / CLI
- Git (local operations)
- npm / Composer (dependencies)
- WP-CLI (WordPress)
- Monitoring tools (uptime, logs)

**Typical Workflow**:
```
Cursor: Pushes PFID-001 code to staging branch
  ↓
VS Code: npm run deploy:staging
  ↓
VS Code: Runs smoke test (cart loads, no 500 errors)
  ↓
VS Code: PASS ✅ → Gives greenlight to Antigravity
  ↓
Antigravity: Full e2e test in staging.gano.digital
```

**Éxito medido por**:
- Zero production incidents (from undetected staging issues)
- Deploy success rate >99%
- Uptime 99.95% (GoDaddy SLA)
- Alert response <5 minutes

**Próximos 7 días**:
- Deploy code changes from Cursor (as they arrive)
- Staging smoke tests after each deploy
- Coordinate with Antigravity on timing
- Maintain production (uptime, backups)

**Current focus**: Staging readiness for Fase 4 testing

---

## 📹 5. ANTIGRAVITY (Quality & Automation Agent)

**Rol**: Browser automation, cart testing, RCC verification, release approval

**Responsabilidades Principales**:
- ✅ RCC audit (review PFID mappings, CTA wiring, plugin config)
- ✅ Cart e2e testing (product page → checkout → order confirmation)
- ✅ Browser recording (video evidence of tests)
- ✅ Screenshot capture (step-by-step proof)
- ✅ Test reporting (PASS/FAIL per step, root cause analysis)
- ✅ Release approval (staging → production gate)

**Tools que usa**:
- Agent Manager (Mission Control)
- Browser automation subagent
- Workflows: `/phase4-rcc-audit`, `/reseller-cart-test`
- Skills: `phase4-commerce` (PFID knowledge)
- Rules: `phase4-commerce-rules` (guardrails)

**Typical Workflow**:
```
Diego (to Antigravity): "Run /reseller-cart-test staging"
  ↓
Antigravity Agent Manager: Parse task, check rules/workflows/skills
  ↓
Antigravity Browser agent:
  1. Navigate to staging.gano.digital
  2. Click "Get Started" CTA
  3. Navigate Reseller cart
  4. Select product variant
  5. Proceed to checkout
  6. Submit test order
  7. Wait 5 min, verify order in RCC
  8. Check email confirmation
  ↓
Antigravity: Generate test report + browser recording + screenshots
  ↓
Output: "9/9 steps PASSED ✅ STAGING READY FOR PRODUCTION"
```

**Éxito medido por**:
- Test reliability (if test says PASS, it's real)
- Execution speed (cart test in <20 min)
- Evidence completeness (video + screenshots + report)
- Zero false positives (no "PASS" when actually broken)

**Próximos 7 días**:
- Awaiting Diego activation (Apr 7)
- First test run (Apr 7-8)
- RCC audit (Apr 8-9)
- Iterative testing as Cursor fixes issues (Apr 9-13)
- Final staging/production validation (Apr 13-15)

**Current focus**: Ready to launch (framework 100% complete, awaiting human activation)

---

## 📚 6. OBSIDIAN (Knowledge Management Agent)

**Rol**: Documentation, decision logging, knowledge base management

**Responsabilidades Principales**:
- ✅ Maintain vault (organize notes, manage links)
- ✅ Document decisions (with rationale, alternatives)
- ✅ Write playbooks (step-by-step processes)
- ✅ Track daily progress (standup notes, sprint logs)
- ✅ Preserve institutional knowledge (decisions, learnings)
- ✅ Enable async communication (team reads vault vs. Slack)

**Tools que usa**:
- Obsidian app (local)
- Markdown (native format)
- Linking ([[note-reference]])
- Dataview (queries, tables)
- Excalidraw (diagrams)

**Typical Workflow**:
```
Claude (to Obsidian): "Document Phase 4 RCC audit results"
  ↓
Obsidian: Receives audit findings, creates new page
  ↓
Obsidian: Links to [[02-FASES-ROADMAP]], [[08-DEPENDENCIAS-RIESGOS]]
  ↓
Obsidian: Updates [[06-METRICAS-PROGRESO#Blockers]]
  ↓
Diego: Reads vault, understands context, takes decision
```

**Éxito medido por**:
- Team knowledge retention (no "I forgot why we chose X")
- Onboarding time (new member understands in 1 day)
- Decision traceability (rationale always documented)
- Asynchonous efficiency (team doesn't need meetings to know context)

**Próximos 7 días**:
- Populate [[06-METRICAS-PROGRESO]] daily
- Document Antigravity test results
- Update blockers/risks as they appear
- Create Phase 4 audit findings note

**Current focus**: Daily tracking of Phase 4 execution

---

## 🔗 Integración Entre Agentes

### Diego ↔ Claude
- **Input**: Decision requests, architectural questions
- **Output**: Analysis, recommendations, documentation
- **Cadencia**: Ad-hoc (when decision needed)
- **Example**: "Should PFID validation happen in plugin or template?" → Claude analyzes both, recommends plugin

### Claude ↔ Cursor
- **Input**: Architecture specs, code review feedback
- **Output**: Code changes, PRs with test evidence
- **Cadencia**: Per-feature (usually 1-2 day cycles)
- **Example**: Claude says "PFID array should use post meta" → Cursor implements + commits

### Cursor ↔ VS Code
- **Input**: Code pushed to staging branch
- **Output**: Deployment confirmation, smoke test results
- **Cadencia**: Per-commit (automatic)
- **Example**: Cursor pushes → VS Code deploys → "Staging green, ready for Antigravity"

### VS Code ↔ Antigravity
- **Input**: "Staging is ready, execute test"
- **Output**: "Test PASS/FAIL, here's video + report"
- **Cadencia**: Per-test-cycle (15-20 min execution)
- **Example**: VS Code → "Deploy complete" → Antigravity → "Running 9-step test"

### Antigravity ↔ Claude
- **Input**: Test results, audit findings
- **Output**: Root cause analysis, recommendations
- **Cadencia**: Post-test (analysis of results)
- **Example**: Antigravity → "Cart shows 404" → Claude + Cursor → "Check PFID in RCC"

### All Agents ↔ Obsidian
- **Input**: Daily progress, decisions, blockers
- **Output**: Knowledge base, decision logs, metrics
- **Cadencia**: Daily standup async
- **Example**: Each agent updates their section in vault → Diego reviews consolidated view

---

## 📊 RACI Matrix (Responsabilidades)

| Task | Responsible | Accountable | Consulted | Informed |
|------|-------------|-----------|----------|----------|
| **RCC Audit** | Antigravity | Diego | Claude | Cursor, VS Code |
| **PFID Mapping** | Cursor | Diego | Claude | Antigravity |
| **Cart Testing** | Antigravity | Diego | Claude | Cursor, VS Code |
| **Staging Deploy** | VS Code | Diego | Cursor | Antigravity |
| **Production Deploy** | VS Code | Diego | Claude | Antigravity |
| **Go-live Decision** | Diego | Diego | Claude, Antigravity | All |
| **Documentation** | Obsidian | Diego | Claude | All |

---

## ⚡ Parallelización (Speed-up)

**Antes** (sin Antigravity):
```
Cursor code → VS Code test → Manual cart test (1 hour) → Claude analysis
Total: 2 hours/cycle
```

**Ahora** (con Antigravity):
```
Cursor code (30 min) ↓
              → VS Code test (5 min) ↓ (parallel)
                                    → Antigravity test (20 min) ↓
                                                                → Claude analysis (10 min)
Total: 65 min/cycle (vs. 120 min = 46% speedup)
```

**Impact**: Feedback loops más rápidos = mejor quality, faster iteration

---

## 🎯 Weekly Sync Cadence

**Monday 9 AM** (30 min):
- Diego: Reads vault metric updates
- All agents: Async standup (what did you do, what's next, blockers)
- Decision: Any blockers need Diego decision?

**Wednesday 12 PM** (15 min):
- Diego: Reviews progress vs. plan
- Agents: Status update if anything major changed

**Friday 5 PM** (30 min):
- Diego: Weekly retrospective (what went well, what didn't)
- Agents: Reflect on quality, suggest process improvements

**As-needed**:
- Blockers: Diego decides immediately (Slack/email)
- Technical questions: Claude consulted anytime

---

## 📈 Agent Capacity & Allocation

| Agent | Capacity | Current Allocation | Slack |
|-------|----------|------------------|-------|
| **Diego** | 40 hrs/wk | 100% (Fase 4 conductor) | 0% |
| **Claude** | ∞ (no limit) | 30% (research, docs, review) | 70% (available for more) |
| **Cursor** | ∞ (no limit) | 20% (awaiting PFID tasks) | 80% (will ramp up) |
| **VS Code** | ∞ (no limit) | 10% (maintain, monitor) | 90% (ready to deploy) |
| **Antigravity** | ∞ (no limit) | 5% (framework ready) | 95% (ready to test) |

**Bottleneck**: PFID mapping task (Cursor) starts Apr 9, will spike to 100% for 4 days

---

## 🚨 Escalation Path

**If blocker**:
1. Agent identifies blocker
2. Agent communicates to Diego (and Obsidian docs)
3. Diego decides: fix now, defer, or pivot
4. Agent executes decision

**Example**: If RCC audit finds 3 PFIDs missing:
- Antigravity → "3 PFIDs not in RCC (Apr 8)"
- Diego → "Contact GoDaddy support, expect reply Apr 9"
- Cursor → "Waiting on PFID availability"
- Diego → "Revisit timeline, push go-live if needed"

---

## 🌟 Team Health Indicators

| Indicator | Healthy | Warning | Critical |
|-----------|---------|---------|----------|
| **Blocker count** | 0-1 | 2-3 | 4+ |
| **Test pass rate** | >90% | 70-90% | <70% |
| **Cycle time** | <2 hrs | 2-4 hrs | >4 hrs |
| **Agent utilization** | 40-70% | 20-40% | <20% or >80% |
| **Async efficiency** | <1 meeting/day | 1-2 meetings | >2 meetings |

**Current health**: 🟢 Healthy (all green, awaiting activation)

---

**Última revisión**: 2026-04-06
**Mantenedor**: Claude (documentation), Diego (oversight)
**Próxima revisión**: Post-Antigravity activation (Apr 8)

