# 🏗️ Arquitectura de Sistemas — Technology Stack & Infrastructure

**Última actualización**: 2026-04-06
**Propósito**: Entender la arquitectura técnica, componentes, integraciones, data flows
**Audiencia**: Technical team, architects, implementers

---

## 🌐 Visión General: 6 Sistemas Principales

```
┌─────────────────────────────────────────────────────────────────┐
│                    GANO DIGITAL ARCHITECTURE                    │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  CUSTOMER FACING                BACKEND SYSTEMS                │
│  ┌──────────────────┐          ┌─────────────────────────┐   │
│  │ WordPress Site   │◄────────►│ GoDaddy Hosting (Mgd)   │   │
│  │ + Elementor      │          │ + Reseller Account      │   │
│  │ + WooCommerce    │          │ + RCC (control center)  │   │
│  │ + Plugins        │          │                         │   │
│  │ + 20 SOTA pages  │          │ OTE (test env)         │   │
│  └──────────────────┘          │ Production env         │   │
│           │                     └──────────┬──────────────┘   │
│           │                                │                  │
│           ▼                                ▼                  │
│  ┌──────────────────┐          ┌─────────────────────────┐   │
│  │ GoDaddy Reseller │          │ GitHub Repository       │   │
│  │ Store (checkout) │          │ + Code storage          │   │
│  │ + PFID mapping   │          │ + Workflow automation   │   │
│  │ + Cart           │          │ + MCP integration       │   │
│  └──────────────────┘          │ + Actions/CI            │   │
│           │                     └─────────────────────────┘   │
│           │                                                    │
│           ▼                                                    │
│  ┌──────────────────┐          ┌─────────────────────────┐   │
│  │ GoDaddy Email    │          │ Agent Ecosystem         │   │
│  │ (order confirm)  │          │ + Claude (research)     │   │
│  └──────────────────┘          │ + Cursor (code)         │   │
│                                │ + VS Code (ops)         │   │
│  MONITORING & DOCS             │ + Antigravity (test)    │   │
│  ┌──────────────────┐          │ + Obsidian (docs)       │   │
│  │ Obsidian Vault   │          └─────────────────────────┘   │
│  │ (knowledge)      │                                         │
│  │                  │          ┌─────────────────────────┐   │
│  │ Uptime monitor   │          │ Dispatch Queue          │   │
│  │ (GoDaddy alerts) │          │ + Task coordination     │   │
│  └──────────────────┘          │ + Dependency tracking   │   │
│                                └─────────────────────────┘   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 1️⃣ Sistema: WordPress Site (Customer Facing)

**Propósito**: Sitio web público, vitrina de productos, contenido SOTA

**Componentes**:
- **CMS**: WordPress 6.x (latest)
- **Theme**: Hello Elementor → gano-child (child theme)
- **Page builder**: Elementor + Royal Elementor Addons
- **E-commerce**: WooCommerce 8.x
- **Plugins principales**:
  - Wordfence (seguridad)
  - Rank Math (SEO)
  - gano-phase1-installer (setup base)
  - gano-phase2-business (hardening)
  - gano-phase3-content (SOTA pages)
  - gano-reseller-enhancements (Phase 4, cart integration) ← NUEVO
  - gano-content-importer (20 SOTA pages) ← NUEVO

**Configuración clave**:
```php
// wp-config.php
define('WP_DEBUG', false); // Production
define('WP_ENVIRONMENT_TYPE', 'production');
define('GANO_RESELLER_BASE', 'https://reseller.godaddy.com/cart');
```

**MU Plugins** (Must-use, siempre activos):
- `gano-security.php` (Fase 2: CSP, rate limiting, headers)
- `gano-seo.php` (Fase 3: Schema JSON-LD, structured data)

**Hosting**: GoDaddy Managed WordPress Deluxe
```
Plan specs:
• 20 GB NVMe storage
• Managed backups (daily)
• CDN (2x speed improvement)
• SSL (Let's Encrypt, auto-renew)
• DDoS protection
• Staging site (mirror of production)
```

**Data**:
- Posts/pages/products (MySQL database)
- Media (NVMe filesystem)
- User accounts + orders (WooCommerce tables)
- Metadata + ACF fields (post_meta, postmeta)

**Access**:
- Admin panel: `gano.digital/wp-admin`
- FTP/SFTP: GoDaddy control panel
- Git: Version control for code, not database

**Monitoring**:
- Uptime: GoDaddy 99.95% SLA
- Performance: Core Web Vitals (LCP <2.5s, CLS <0.1)
- Errors: Wordfence alerts, WP Debug log

---

## 2️⃣ Sistema: GoDaddy Hosting & Reseller Store

**Propósito**: Infraestructura física, checkout marca blanca, order management

### Part A: Managed WordPress (Infrastructure)

**Dónde corre el sitio**:
- GoDaddy datacenter (US, pero performance optimizado)
- NVMe storage (rápido)
- Apache + PHP 8.1+
- MySQL 8.0

**Backups**:
- Daily automated (30 days retention)
- On-demand restoration available
- Tested regularly (recovery drills)

**Security**:
- DDoS protection (automatic)
- Firewall rules (custom)
- SSL certificate (auto-renewed)
- Malware scanning (Wordfence + GoDaddy)

**Staging**:
- Complete mirror of production
- Available in GoDaddy control panel
- Used for testing before production pushes
- **Key for Fase 4**: Cart testing happens here first

### Part B: Reseller Store & RCC

**Flujo de orden**:
```
Customer clicks CTA (gano.digital)
  ↓ (via gano_reseller_cart_url())
Reseller Store (marca blanca GoDaddy)
  ↓
Customer selects product (WordPress plan)
  ↓
Customer enters details (domain, email, name)
  ↓
Customer pays (GoDaddy payment processor)
  ↓
RCC order created (backend)
  ↓
Hosting provisioned (automated by GoDaddy)
  ↓
Email confirmation sent (GoDaddy)
  ↓
Customer dashboard access (GoDaddy portal)
```

**RCC (Reseller Control Center)**:
- Backend order management
- Product catalog (all GoDaddy products available)
- Customer info (email, domain, plan details)
- Billing & invoicing
- Support ticketing
- Reports & analytics

**PFID Mapping** (Product Family ID):
```
WordPress Plan (Gano)          PFID (GoDaddy)
────────────────────────────────────────────
WordPress Hosting Annual   →   PFID-12345
WordPress Hosting 3-Year   →   PFID-12346
WooCommerce Annual         →   PFID-54321
Advanced Security Annual   →   PFID-67890
Elite Support Annual       →   PFID-99999
```

**OTE (Official Test Environment)**:
- Sandbox before production
- Test credit card: 4111-1111-1111-1111
- Webhook testing available
- PFIDs mirrored from production

---

## 3️⃣ Sistema: GitHub Repository

**Propósito**: Version control, code storage, automation workflows, collaboration

**Repo**: `https://github.com/Gano-digital/Pilot`

**Estructura**:
```
Gano.digital-copia/
├── .agents/                    # Antigravity framework
│   ├── rules/                  # System instructions
│   │   ├── gano-digital-guidelines.md
│   │   └── phase4-commerce-rules.md
│   ├── workflows/              # Saved automation prompts
│   │   ├── phase4-rcc-audit.md
│   │   └── reseller-cart-test.md
│   └── skills/                 # Domain knowledge
│       └── phase4-commerce/SKILL.md
├── .github/
│   └── workflows/              # CI/CD automation
├── .claude/                    # Session data (not committed)
│   ├── CLAUDE.md               # Project context
│   ├── dispatch-queue.json     # Task queue
│   └── ...
├── wp-config.php               # WordPress config (hardened)
├── wp-content/
│   ├── mu-plugins/             # Must-use plugins
│   │   ├── gano-security.php   # (Fase 2)
│   │   └── gano-seo.php        # (Fase 3)
│   ├── plugins/                # Regular plugins
│   │   ├── gano-phase1-installer/
│   │   ├── gano-phase2-business/
│   │   ├── gano-phase3-content/
│   │   ├── gano-reseller-enhancements/  # (Fase 4 NEW)
│   │   ├── gano-content-importer/       # (Fase 3 NEW)
│   │   ├── wordfence/
│   │   ├── rank-math/
│   │   └── woocommerce/
│   └── themes/
│       ├── hello-elementor/
│       └── gano-child/         # (Child theme)
│           ├── functions.php
│           ├── templates/
│           │   ├── shop-premium.php    # (CTA template, Fase 4)
│           │   └── page-seo-landing.php
│           └── seo-pages/       # (20 SOTA pages)
├── memory/                     # Knowledge base (in repo)
│   ├── projects/               # Project docs
│   │   └── gano-digital.md     # Master plan
│   ├── research/               # Analysis
│   │   ├── fase4-plataforma.md
│   │   ├── sota-*.md
│   │   └── ...
│   ├── commerce/               # Commerce-specific
│   │   ├── rcc-pfid-checklist.md  # PFID tracker
│   │   └── screenshots/        # Test evidence
│   ├── claude/                 # Claude session data
│   │   ├── dispatch-queue.json # Task coordination
│   │   └── antigravity-status-*.md
│   ├── integration/            # Integration docs
│   │   └── antigravity-*.md
│   └── constellation/          # THIS CONSTELATION
│       ├── 00-PROYECTO-CONSTELACION-INDICE.md
│       ├── 01-MAPA-ESTRATEGICO.md
│       ├── 02-FASES-ROADMAP.md
│       ├── 03-ECOSISTEMA-AGENTES.md
│       ├── 04-ARQUITECTURA-SISTEMAS.md
│       └── ...
├── scripts/                    # Utility scripts
│   ├── claude_dispatch.py      # Task queue executor
│   └── generate_audit_pdf.py
├── TASKS.md                    # Sprint tasks (detailed)
├── CLAUDE.md                   # Project instructions
├── ANTIGRAVITY-QUICKSTART.md   # Antigravity guide
├── README.md
└── .gitignore                  # (secrets, .env, *.pdf)
```

**Branches**:
- `main` — Production code (stable)
- `docs/memoria-fase4-...` — Documentation (current)
- `feature/pfid-mapping-*` — Feature branches (Cursor work)
- Staging branch (deployed to staging.gano.digital)

**Git Workflow** (as per rules):
```
Cursor: Create feature branch (feature/phase4-pfid-001)
  ↓
Cursor: Commit with clear message: "[Phase 4] Feat: Map PFID-12345 (WordPress Annual)"
  ↓
Cursor: Push to remote
  ↓
Claude: Code review + approval
  ↓
Cursor: Merge to main (or staging first)
  ↓
VS Code: Deploy to staging/production
  ↓
Antigravity: Test + validate
  ↓
Obsidian: Document in PFID checklist
```

**Secrets Management**:
- NO secrets in git (use .env, not committed)
- GoDaddy API keys → environment variables
- Database credentials → GoDaddy control panel
- SSL certs → GoDaddy managed

---

## 4️⃣ Sistema: Agent Infrastructure (MCP + Dispatch)

**Propósito**: Coordinate 5 agents, manage tasks, share context

### MCP (Model Context Protocol) Integration

**Connected Services**:
- **GitHub**: Read/write code, create PRs, manage issues
- **Obsidian**: Read/write vault (knowledge base)
- **Google Calendar**: (planned) Schedule team sync
- **Email**: (planned) Alerts + notifications

### Dispatch Queue System

**File**: `memory/claude/dispatch-queue.json`

**Structure**:
```json
{
  "queue": [
    {
      "id": "ag-phase4-001",
      "agent": "antigravity",
      "task": "/reseller-cart-test staging",
      "priority": "critical",
      "status": "pending",
      "requires_human_after": true,
      "blocker": "diego-activation",
      "created": "2026-04-06T21:00Z"
    },
    {
      "id": "ag-phase4-002",
      "agent": "antigravity",
      "task": "/phase4-rcc-audit",
      "priority": "high",
      "status": "pending",
      "blocker": "ag-phase4-001-pass"
    }
  ]
}
```

**Executor**: `scripts/claude_dispatch.py`
- Reads queue
- Checks dependencies (blockers)
- Executes next ready task
- Updates status

**VS Code tasks** (for manual trigger):
- `Ctrl+Shift+B` → antigravity-next (run next task)
- `Ctrl+Shift+B` → antigravity-list (show queue)
- `Ctrl+Shift+B` → antigravity-show (details of task)

---

## 5️⃣ Sistema: Obsidian Vault (Knowledge Management)

**Propósito**: Central knowledge repository, decision logs, team reference

**Ubicación**: `C:\Users\diego\...\.obsidian/` (local)

**Contenido**:
- **Daily notes**: Progress updates, standup logs
- **Decisions**: Why we chose X over Y
- **Processes**: Step-by-step workflows
- **Metrics**: Progress trackers, dashboards
- **References**: Links to external docs

**This Constelation** (6 interconnected notes):
- 00-PROYECTO-CONSTELACION-INDICE (index)
- 01-MAPA-ESTRATEGICO (vision + decisions)
- 02-FASES-ROADMAP (timeline + phases)
- 03-ECOSISTEMA-AGENTES (5 agents + roles)
- 04-ARQUITECTURA-SISTEMAS (this note, tech stack)
- 05-PROCESOS-OPERATIVOS (workflows, automations)
- 06-METRICAS-PROGRESO (dashboards, KPIs)
- 07-EQUIPO-COORDINACION (team assignments, RACI)
- 08-DEPENDENCIAS-RIESGOS (blockers, risks, mitigation)

**Linking**: Markdown `[[note]]` links enable quick navigation

**Graphs**: Obsidian graph view shows how notes connect

---

## 6️⃣ Sistema: Monitoring & Alerting

**Propósito**: Detect issues, ensure uptime, performance tracking

**Components**:
- **Uptime monitor**: GoDaddy alerts (if site down)
- **Performance**: Core Web Vitals (Lighthouse, RUM)
- **Security**: Wordfence alerts, CSP violations
- **Errors**: WordPress error log, 500 errors
- **Backups**: Daily verification, recovery tested

**Alerts** (who gets notified):
- Diego: All critical (downtime, security breach)
- Claude: Architectural alerts (high error rate)
- VS Code: Ops alerts (deployment issues)
- Obsidian: Logs all alerts for historical tracking

---

## 🔄 Data Flows (Critical Scenarios)

### Scenario 1: Customer Places Order

```
1. Customer visits gano.digital
2. Clicks "Get Started" CTA → gano_reseller_cart_url() generated
3. Browser redirected to: https://reseller.godaddy.com/cart?plid=PFID-12345&psid=SHOPPER_ID
4. Customer selects WordPress plan, domain, payment method
5. GoDaddy processes payment
6. RCC order created (automated by GoDaddy)
7. Hosting provisioned (automated)
8. Customer receives email from GoDaddy (with login details)
9. 5 minutes later: Order visible in RCC dashboard
10. Customer logs in to GoDaddy to manage hosting
```

**Gano Digital's Role**: CTA + PFID mapping (rest is GoDaddy automated)

### Scenario 2: Testing New PFID Mapping

```
1. Cursor: Adds new PFID-12346 to gano-reseller-enhancements plugin
2. Cursor: Commits + pushes to branch
3. VS Code: Deploys to staging.gano.digital
4. Antigravity: Runs /reseller-cart-test staging
   a. Opens staging.gano.digital
   b. Clicks CTA → navigates to Reseller cart
   c. Verifies PFID-12346 shows correct product
   d. Proceeds to checkout (test card)
   e. Verifies order in RCC within 5 min
   f. Verifies email received
5. Antigravity: Generates report + browser recording
6. Claude: Reviews test results, approves code
7. Cursor: Merges PR to main
8. VS Code: Deploys to production
9. PFID-12346 now live ✅
```

### Scenario 3: Blocker Resolution

```
1. Antigravity: "Cart shows 404 on PFID-12345"
2. Claude: Analyzes → "Likely wrong Reseller URL or PFID not in RCC"
3. Cursor: Checks gano_reseller_cart_url() function
   - Correct base URL? YES (https://reseller.godaddy.com/cart)
   - PFID exists in RCC? NO → Contact GoDaddy
4. Diego: Opens GoDaddy support ticket
5. GoDaddy: "PFID-12345 not found, use PFID-12344 instead"
6. Cursor: Updates plugin + re-commits
7. Antigravity: Re-tests → PASS ✅
```

---

## 🛡️ Security Architecture

**Layers**:
1. **Firewall** (GoDaddy DDoS protection) — Blocks bad traffic
2. **HTTPS/TLS** (Let's Encrypt) — Encrypts in transit
3. **WordPress Hardening** (MU plugin) — CSP, rate limiting, nonce validation
4. **Wordfence** — Malware scanning, IP blocking
5. **Backups** (daily, tested) — Recovery point objective (RPO)

**Secrets Management**:
- No credentials in code
- .env file (local, not committed)
- GoDaddy control panel (encrypted)
- SSH keys → ~/.ssh/ (local machine only)

**Compliance**:
- HTTPS enforced
- CSP headers active
- Rate limiting active
- Log retention (30 days)
- No PCI-DSS needed (GoDaddy handles payments)

---

## 📈 Performance Targets

| Metric | Target | Current | Status |
|--------|--------|---------|--------|
| **LCP** (Largest Paint) | <2.5s | ~2.2s | ✅ |
| **FID** (Input Delay) | <100ms | ~80ms | ✅ |
| **CLS** (Layout Shift) | <0.1 | ~0.05 | ✅ |
| **Page load** | <3s | ~2.8s | ✅ |
| **Uptime** | 99.95% | 99.95% | ✅ |

---

## 🔗 External Integrations

| Service | Purpose | Status |
|---------|---------|--------|
| **GoDaddy Hosting** | Infrastructure | ✅ Active |
| **GoDaddy Reseller** | Commerce | 🟠 Phase 4 |
| **GoDaddy RCC** | Order mgmt | 🟠 Phase 4 |
| **Let's Encrypt** | SSL certs | ✅ Active |
| **Google Search Console** | SEO | ✅ Verified |
| **Google Analytics** | Metrics | ✅ Configured |
| **GitHub** | Source control | ✅ Active |
| **Wordfence** | Security | ✅ Active |

---

**Última revisión**: 2026-04-06
**Mantenedor**: Claude (tech), Cursor (implementation)
**Próxima revisión**: Post-Phase 4 launch (mayo 2026)

