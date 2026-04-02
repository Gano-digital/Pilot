# Gano Digital Platform

> **Plataforma de hosting WordPress profesional para empresas colombianas**
> Stack: WordPress 6.x · Elementor · WooCommerce · Wompi · GSAP 3
> URL: https://gano.digital · Hosting: GoDaddy Managed WordPress (detalles de infra en panel del proveedor, no en el repo)

---

## 🔁 Coordinación del equipo (GitHub ↔ servidor ↔ local)

El repositorio se integra con **producción** y con **máquinas locales** mediante un flujo explícito. Lee la guía **[`.github/DEV-COORDINATION.md`](.github/DEV-COORDINATION.md)** para saber qué archivos son fuente de verdad, cómo reportar cambios hechos solo en el servidor (Elementor, plugins) y cómo usar la plantilla de issue **Reporte de sincronización**.

Para offloading masivo de tareas a Copilot (cola de issues), consulta **[`.github/COPILOT-AGENT-QUEUE.md`](.github/COPILOT-AGENT-QUEUE.md)**.

---

## 🗂️ Estructura del Proyecto

```
Gano.digital-copia/
├── .github/
│   ├── DEV-COORDINATION.md         ← GitHub ↔ servidor ↔ local (fuente operativa)
│   ├── agent-queue/tasks.json      ← Cola de issues para Copilot agent (Actions)
│   ├── COPILOT-AGENT-QUEUE.md      ← Cómo ejecutar el workflow de offloading
│   ├── workflows/                  ← CI (TruffleHog, php-lint, seeds, labels)
│   ├── copilot-instructions.md     ← Contexto para GitHub Copilot
│   ├── ISSUE_TEMPLATE/             ← Incl. reportes de sincronización
│   └── agents/                     ← Agentes GSD exportados
├── .gsd/                           ← get-shit-done workflow system (submodule)
├── wp-content/
│   ├── themes/gano-child/          ← Tema hijo (TODO el desarrollo visual va aquí)
│   │   ├── style.css               ← Design tokens Kinetic Monolith
│   │   ├── functions.php           ← GSAP enqueue, SVG noise filter
│   │   ├── js/
│   │   │   ├── gano-sota-fx.js     ← Animaciones GSAP (scrollytelling)
│   │   │   └── scroll-reveal.js    ← IntersectionObserver reveals
│   │   └── templates/
│   │       ├── sota-single-template.php
│   │       └── shop-premium.php
│   ├── plugins/
│   │   ├── gano-content-importer/  ← ⭐ 20 páginas SOTA (activar una vez)
│   │   ├── gano-phase3-content/    ← Builder páginas principales + menú
│   │   ├── gano-wompi-integration/ ← Pasarela Wompi Colombia
│   │   └── gano-reseller-enhancements/ ← GoDaddy Reseller Store
│   └── mu-plugins/
│       ├── gano-security.php       ← CSP + headers de seguridad
│       └── gano-seo.php            ← Schema JSON-LD + Open Graph
├── memory/                         ← Notas de investigación (Fase 4+)
├── CLAUDE.md                       ← Contexto del proyecto para AI
└── TASKS.md                        ← Estado de tareas por fase
```

---

## 🚀 Fases de Desarrollo

| Fase | Estado | Descripción |
|------|--------|-------------|
| **1 — Seguridad Base** | ✅ Completa | WP_DEBUG, Wompi HMAC, nonces CSRF |
| **2 — Hardening** | ✅ Completa | CSP enforced, Rate limiting REST, AES-256 |
| **3 — SEO/Performance** | ✅ Completa | Schema JSON-LD, Core Web Vitals, templates SEO |
| **3.5 — SOTA Content** | 🔄 En curso | 20 páginas SOTA, GSAP animations, Kinetic Monolith |
| **4 — Plataforma Real** | 📋 Planificada | WHMCS, Wompi billing, FreeScout, DIAN |
| **5 — Escala** | ⏳ Futuro | CDN, backup cloud, API pública |

---

## ⚡ Setup Local (Laragon)

```bash
# 1. Clonar repositorio
git clone git@github.com:Gano-digital/Pilot.git
git submodule update --init  # Incluye .gsd

# 2. Copiar wp-config (nunca en git)
cp wp-config-sample.php wp-config.php
# Editar DB_NAME, DB_USER, DB_PASS para local

# 3. Activar plugins de fase en este orden:
# WordPress Admin → Plugins → Activar:
# 1. gano-phase3-content (crea páginas principales)
# 2. gano-content-importer (crea 20 páginas SOTA) → luego desactivar
```

---

## 🔐 Secrets Requeridos (GitHub Actions)

| Secret | Descripción |
|--------|-------------|
| `SSH` | Clave privada SSH para rsync al servidor (requerida por `deploy.yml`) |

Para configurar: `GitHub Repo → Settings → Secrets and variables → Actions → New repository secret`

---

## 🛠️ Desarrollo Workflow (GSD)

Este proyecto integra [get-shit-done](https://github.com/gsd-build/get-shit-done) para spec-driven development:

```bash
# Ver agentes disponibles
ls .gsd/agents/

# Agentes clave:
# gsd-planner.md      → Planificar features
# gsd-executor.md     → Ejecutar con contexto
# gsd-verifier.md     → Verificar cambios
# gsd-debugger.md     → Debug profundo
# gsd-ui-auditor.md   → Auditar UX/UI
```

---

## 💳 Stack de Pagos (Colombia)

- **Gateway**: Wompi (`wp-content/plugins/gano-wompi-integration/`)
- **Métodos**: PSE, Nequi, Daviplata, Tarjetas débito/crédito
- **Sandbox**: `pub_test_...` / `prv_test_...`
- **Producción**: En GitHub Secrets (nunca en código)
- **Webhook**: `https://gano.digital/wp-json/gano-wompi/v1/webhook`

---

## 📞 Contacto del Proyecto

- **Fundador**: Diego — Gano Digital SAS, Bogotá, Colombia
- **Servidor**: Managed WordPress Deluxe (20GB NVMe)  
- **Deploy**: GitHub Actions → rsync vía SSH
