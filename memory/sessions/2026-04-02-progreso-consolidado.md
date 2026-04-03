# Gano Digital — Progreso consolidado

_Documento de síntesis para equipo y continuidad. Última revisión: 2 de abril de 2026._

La fuente operativa detallada sigue siendo [`TASKS.md`](../../TASKS.md) en la raíz del repo. Este archivo resume **logros**, **estado actual**, **pendientes** y **dónde seguir**.

---

## 1. Visión en una frase

**gano.digital** es la vitrina WordPress + Elementor (enfoque SOTA / Kinetic Monolith) de ecosistemas de hosting orientados a Colombia y LATAM, con **marca blanca GoDaddy Reseller** para inventario, pagos y facturación del cliente final.

---

## 2. Lo ya logrado (repo — Fases 1 a 3)

| Área | Estado | Notas breves |
|------|--------|----------------|
| **Fase 1 — Parches críticos** | Hecho en repo | `wp-config` endurecido; chat con nonce CSRF y entrada saneada; alerta `wp-file-manager` en MU security. |
| **Fase 2 — Hardening** | Hecho en repo | Rate limiting REST (429); CSP enforced para Elementor/Woo; headers adicionales; X-Frame-Options SAMEORIGIN. |
| **Fase 3 — SEO técnico** | Hecho en repo | MU `gano-seo.php`: JSON-LD (Org/Local digital, WebSite, Product, FAQ, Breadcrumb), OG/Twitter fallback, hints, preload LCP configurable. |
| **Fase 3 — Performance (tema)** | Hecho en repo | `gano-child`: defer JS, limpieza head, emoji removal, hook LCP con MutationObserver. |
| **Estrategia comercial** | Decidida | Checkout y facturación vía **GoDaddy Reseller**; sin priorizar pasarelas locales ajenas a ese ecosistema. |
| **Shop / mockup** | En repo | `shop-premium.php` con integración del mockup SOTA; Fase 4 pendiente de IDs reales y prueba de flujo. |

---

## 3. Estado actual (abril 2026)

### 3.1 Servidor y panel (brecha principal)

El código de las fases 1–3 está **listo en el repositorio** pero **no genera valor público** hasta:

- Subir archivos críticos al **servidor real** (ver lista en `TASKS.md`).
- **Eliminar** `wp-file-manager` en producción.
- Configurar **Gano SEO**, **Search Console** y **Rank Math** en wp-admin.
- Sustituir **Lorem** y placeholders en **Elementor** usando `memory/content/homepage-copy-2026-04.md` y `elementor-home-classes.md`.

### 3.2 GitHub `Gano-digital/Pilot`

| Concepto | Situación |
|----------|-----------|
| **Rama de integración** | `main` con CI (PHP lint Gano, TruffleHog acotado). |
| **Colas de agentes** | `tasks.json` (oleada 1), `tasks-wave2.json`, `tasks-wave3.json` en `.github/agent-queue/`. |
| **Issues abiertos (cola)** | ~**32** en rangos **#17–#33** (homepage + fixplan + sync) y **#54–#68** (marca/UX/comercial wave3). |
| **PRs abiertos** | ~**35**; a fecha de última revisión **todos en borrador** → los agentes **entregan ramas**; falta **triage**, marcar listos, CI verde y **merge**. |
| **Prompts Copilot** | Dos bloques en `.github/prompts/copilot-bulk-assign.md`: **oleada 1** (#17–33) vs **oleada 3** (#54–68). |
| **Playbooks** | Merge: `.github/MERGE-PLAYBOOK.md` · Coordinación: `.github/DEV-COORDINATION.md`. |

### 3.3 Skills del proyecto (orientación para agentes)

- **Orquestación GitHub/Copilot:** `.gano-skills/gano-github-copilot-orchestration/SKILL.md` (incluye interpretación de PRs draft e issues).
- **Multi-agente local:** `.gano-skills/gano-multi-agent-local-workflow/SKILL.md`.
- Otras: seguridad WP, contenido, fase 4 plataforma, etc., en `.gano-skills/`.

### 3.4 Entregables auxiliares

- **Reporte PDF ejecutivo** (junta laboral): generado con `scripts/generate_board_report_pdf.py` → `reports/Gano-Digital-Reporte-Estado-YYYY-MM.pdf` (los `.pdf` suelen estar en `.gitignore`).

---

## 4. Próximos hitos (orden sugerido)

1. **Deploy** de Fases 1–3 al servidor + eliminación de `wp-file-manager`.
2. **Triage de PRs en GitHub:** priorizar merges de bajo riesgo (solo `memory/**/*.md` wave3), luego tema/MU según `MERGE-PLAYBOOK`.
3. **Homepage en Elementor:** menú primary, copy real, hero, pilares, CTAs (issues #17–#22 y guías en `memory/content/`).
4. **Fase 4 Reseller:** catálogo RCC, mapeo de CTAs a IDs reales, smoke test checkout.
5. **Opcional:** Actions → **Orchestrate Copilot waves** con `dry_run_merge: true` antes de merges masivos; **Seed homepage Fix issues** si aún no se ejecutó.

---

## 5. Referencias rápidas

| Recurso | Ruta / enlace |
|---------|----------------|
| Lista viva de tareas | [`TASKS.md`](../../TASKS.md) |
| Brief oleada 3 | `memory/research/gano-wave3-brand-ux-master-brief.md` |
| Memoria de proyecto | `memory/projects/gano-digital.md` |
| Cola Copilot | `.github/COPILOT-AGENT-QUEUE.md` |
| Reporte sesión Cursor (descargas/herramientas) | `memory/sessions/2026-04-01-reporte-cursor-descargas-y-herramientas.md` |

---

_Cierre: el progreso técnico en **Git** es sólido; el foco del siguiente ciclo es **producción + revisión de PRs + contenido en vivo**._
