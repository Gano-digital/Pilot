# Digital files and content setup — Gano Digital

**Última actualización:** 2026-04-07 (Ops Hub + workflow 14)  
**Propósito:** Una sola referencia para agentes y humanos: **dónde vive qué** en el repo, cómo se relaciona el **contenido web** con **memoria/constelación**, y el estado acordado en abril 2026 (GoDaddy Reseller vs API, GitHub, Claude).

---

## 1. Principios

| Principio | Significado |
|-----------|-------------|
| **Repo = verdad del código y la documentación** | Lo que está en `main` (o en PR) es lo que el equipo puede auditar. |
| **Producción WordPress ≠ git completo** | BD Elementor, menús, medios subidos y `wp-config.php` no se asumen iguales al repo. Ver [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md). |
| **Checkout comercial** | **Reseller Store + RCC** es el camino canónico; la **Developer API REST** es opcional (herramientas, back-office). **Good as Gold** solo cuando la API **compre** productos que debiten prepago. Detalle: [`memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`](../research/sota-apis-mercadolibre-godaddy-2026-04.md) §3.7. |

---

## 2. Mapa de carpetas `memory/`

| Carpeta | Contenido |
|---------|-----------|
| **`memory/content/`** | Copy, briefs de homepage, guías Elementor, **este archivo** (`digital-files-and-content-setup.md`). |
| **`memory/claude/`** | Contexto Claude: [`README.md`](../claude/README.md), [`dispatch-queue.json`](../claude/dispatch-queue.json), pendientes, handoff. |
| **`memory/sessions/`** | Reportes datados (Cursor, handoff GoDaddy, auditoría GitHub). |
| **`memory/research/`** | Investigaciones (Fase 4 plataforma, APIs ML + GoDaddy SOTA). |
| **`memory/commerce/`** | RCC ↔ PFID: [`rcc-pfid-checklist.md`](../commerce/rcc-pfid-checklist.md). |
| **`memory/constellation/`** | Constelación del proyecto: notas `00`–`08`, visualizador [`CONSTELACION-COSMICA.html`](../constellation/CONSTELACION-COSMICA.html), portal [`🌌-CONSTELACION-COSMICA.md`](../constellation/🌌-CONSTELACION-COSMICA.md). **Convención:** versionar en git si el equipo debe compartir el mapa; Obsidian abre los mismos `.md`. |
| **`tools/gano-ops-hub/`** | **Ops Hub:** dashboard estático (`public/index.html`) + `data/progress.json` desde `TASKS.md`, cola Claude y (CI) **GitHub Project @Gano.digital**. Config tablero: [`.github/gano-project-hub.json`](../../.github/gano-project-hub.json). CI: **14**. Guía: [`tools/gano-ops-hub/README.md`](../../tools/gano-ops-hub/README.md) · despliegue [`memory/ops/gano-ops-hub-deployment.md`](../ops/gano-ops-hub-deployment.md). |
| **`memory/ops/`** | Runbooks (DNS, HTTPS, agentes, seguridad). |
| **`memory/projects/`** | [`gano-digital.md`](../projects/gano-digital.md) — visión del producto. |

---

## 3. Contenido del sitio (gano.digital)

| Fuente | Uso |
|--------|-----|
| **`memory/content/*.md`** | Texto fuente y especificaciones (homepage, trust, ecosistemas). Aplicación real: **Elementor / wp-admin** en el servidor. |
| **`wp-content/themes/gano-child/`** | Plantillas PHP, `shop-premium.php`, estilos; despliegue según `TASKS.md` y workflows de deploy. |
| **Rank Math / Gano SEO** | Metadatos y schema en MU-plugin; configuración en panel. |

**Flujo recomendado:** editar copy en `memory/content/` → replicar o pegar en Elementor → validar en staging antes de producción.

---

## 4. Handoff y continuidad (abril 2026)

| Documento | Tema |
|-----------|------|
| [`memory/sessions/2026-04-02-reporte-handoff-godaddy-api-reseller-whmcs.md`](../sessions/2026-04-02-reporte-handoff-godaddy-api-reseller-whmcs.md) | APIs GoDaddy, WHMCS, guardrails. |
| [`memory/sessions/2026-04-07-audit-github-pr-issues.md`](../sessions/2026-04-07-audit-github-pr-issues.md) | PR #136, issue #29, CI/Vercel. |

**Cola ejecutable (sin GitHub):** `python scripts/claude_dispatch.py next` — ver [`memory/claude/dispatch-prompt.md`](../claude/dispatch-prompt.md).

**Fase 4 automatización (Antigravity):** [`ANTIGRAVITY-QUICKSTART.md`](../../ANTIGRAVITY-QUICKSTART.md), [`memory/integration/antigravity-integration-2026-04-06.md`](../integration/antigravity-integration-2026-04-06.md).

---

## 5. GitHub (snapshot)

- **PR abierto (revisar en remoto):** documentación Fase 4 Reseller vs Developer API — merge cuando **CodeQL** y reglas de rama lo permitan; revisar **Vercel** si bloquea.
- **Issue abierto:** [#29](https://github.com/Gano-digital/Pilot/issues/29) — paridad servidor con parches Fases 1–3.

---

## 6. Archivos raíz que deben leerse primero

1. [`CLAUDE.md`](../../CLAUDE.md)  
2. [`TASKS.md`](../../TASKS.md)  
3. Este archivo (`digital-files-and-content-setup.md`)  
4. [`memory/claude/README.md`](../claude/README.md)

---

*Mantenedor: actualizar la fecha al cambiar la estructura de `memory/` o la estrategia comercial (Reseller / API).*
