# Sesión 2026-04-06 — Arranque ola de contenidos web

## Objetivo del día
Terminar de montar **todos los contenidos** en gano.digital usando agentes desplegados desde Cursor + MCPs + skills disponibles. Propuesta UX coherente y profesional, alimentada por los mejores datos ya recopilados en `memory/content/` (Wave 3 + master plan 2026).

## Auditoría de carpetas (hecha hoy por Claude)
- Creados índices: `memory/_indice-consolidado.md` y `memory/_indice-archivo.md`.
- **Criterio**: no mover archivos dentro de `memory/` (rutas referenciadas por CLAUDE.md, dispatch-queue, scripts, cola Copilot). Solo catalogar.
- **Movidos físicamente**:
  - 14 archivos one-shot de raíz → `_archivo-raiz/` (STATUS_*, AUDIT_*, DELIVERY_*, mockups HTML SOTA viejos, DIEGO_QUICKSTART, etc.).
  - `C:\Users\diego\Downloads\Gano.digital v2\` → `C:\Users\diego\Downloads\Gano-ARCHIVO\v2-backup-marzo-2026\` (snapshot marzo, fuera del repo).
- **Vivo y consolidado**: `memory/content/*` (23 docs), `memory/ops/*` (12 playbooks), `memory/research/*` (7), `memory/claude/*` (dispatch + historial), `memory/projects/gano-digital.md`, `memory/commerce/rcc-pfid-checklist.md`, `memory/smoke-tests/checkout-reseller-2026-04.md`.

## Plan de ejecución — wave de contenidos
Fuente: `memory/content/content-master-plan-2026.md`, `homepage-copy-2026-04.md`, `homepage-section-order-spec-2026.md`, `site-ia-wave3-proposed.md`, `ecosystems-copy-matrix-wave3.md`, `trust-and-reseller-copy-wave3.md`, `microcopy-wave3-kit.md`, `visual-tokens-wave3.md`, `elementor-architecture-wave3.md`.

### Tareas (entran a `memory/claude/dispatch-queue.json` como wave `cd-content-*`)
1. **Homepage 2026** — implementar el orden definitivo (`homepage-section-order-spec-2026`) sobre Elementor, mapeando clases de `elementor-home-classes.md` y tokens de `visual-tokens-wave3.md`.
2. **Páginas de producto / ecosistemas** (4 planes) — copy desde `ecosystems-copy-matrix-wave3.md` + CTAs Reseller (PFIDs por confirmar contra `commerce/rcc-pfid-checklist.md`).
3. **20 páginas SOTA** (gano-content-importer) — activar plugin en orden, revisar drafts, completar hook-box / quote / CTA por categoría (infra, seguridad, IA, estrategia, performance).
4. **Trust pages bundle** — `trust-pages-bundle-2026.md` + `trust-and-reseller-copy-wave3.md` (legal, garantías, SLA, soporte, contacto).
5. **Navegación primaria + footer** — `navigation-primary-spec-2026.md` + `footer-contact-audit-wave3.md`.
6. **FAQ schema** — implementar candidatos de `faq-schema-candidates-wave3.md` con JSON-LD vía MU plugin `gano-seo.php`.
7. **Microcopy + a11y** — pasar `microcopy-wave3-kit.md` y `ux-a11y-wave3-notes.md` sobre toda la UI.
8. **Assets raster + social previews** — generar según `raster-asset-spec-wave3.md` y `social-preview-spec-wave3.md`.
9. **Cierre de gap IA vs live inventory** — `gap-ia-vs-live-inventory-2026.md`.

### Recursos a usar (MCPs + skills)
- **Skills**: `design:design-system`, `design:ux-copy`, `design:design-critique`, `design:accessibility-review`, `marketing:content-creation`, `marketing:brand-review`, `marketing:seo-audit`, `brand-voice:brand-voice-enforcement`, `engineering:code-review`, `.gano-skills/gano-content-audit/`, `.gano-skills/gano-wp-security/`.
- **MCPs**: `Figma` (design context, screenshots), `Claude_Preview` (preview start/screenshot), `notion` (sync docs), `Claude_in_Chrome` (validación visual en staging), `gcal` (recordatorios entrega).
- **Despacho**: agentes Cursor leen `memory/claude/dispatch-queue.json`, una tarea `cd-content-*` por sesión, commits atómicos, sin tocar wp-admin ni RCC en vivo.

## Decisiones del día
- Carpeta consolidada y archivo se manejan **por índices**, no moviendo archivos de `memory/`.
- Snapshot v2 fuera del repo activo.
- Sesiones previas a 2026-04-06 quedan como histórico de referencia, no fuente de verdad.
- Este archivo es la fuente de verdad operativa para hoy.

## Pendiente para Diego
- Confirmar PFIDs reales en RCC para CTAs de ecosistemas (bloquea tarea 2).
- Datos legales (NIT, teléfono) para trust pages (bloquea tarea 4).
- Si quiere split del PR, decidir antes del commit final.
