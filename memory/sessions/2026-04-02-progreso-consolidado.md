# Gano Digital — Progreso consolidado

_Documento de síntesis para equipo y continuidad. Última revisión: **3 de abril de 2026**._

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

| Concepto | Situación (2026-04-03) |
|----------|-------------------------|
| **Rama de integración** | `main` con CI (PHP lint Gano, TruffleHog acotado, validación cola JSON, CodeQL). |
| **Colas de agentes** | **Siete** JSON en `.github/agent-queue/` (incl. `tasks-security-guardian.json` — guardián de seguridad / cierre de sesión). |
| **Oleada 4 + infra (docs)** | Entregas markdown fusionadas en `main` (PRs #100–#113, abril 2026). *Pendiente humano:* Elementor, DNS en GoDaddy, cierre de issues en GitHub si aplica. |
| **Cola API (ML + GoDaddy)** | Informe base [`memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`](../research/sota-apis-mercadolibre-godaddy-2026-04.md) + cola `tasks-api-integrations-research.json`; **sembrar con workflow 08** cuando se quieran issues `api-*`. |
| **Guardián seguridad** | Cola `tasks-security-guardian.json` (`sec-*`), skill `.gano-skills/gano-session-security-guardian/`, checklist [`memory/ops/security-end-session-checklist.md`](../ops/security-end-session-checklist.md). |
| **PRs abiertos** | Tras consolidación, **revisar en GitHub**; el objetivo es cola baja o cero. |
| **Prompts Copilot** | Bloques por oleada en [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md): oleada 1, 3, 4, infra, **API integrations**. |
| **Playbooks** | Merge: `.github/MERGE-PLAYBOOK.md` · Coordinación: `.github/DEV-COORDINATION.md`. |
| **SOTA operativo** | [`memory/research/sota-operativo-2026-04.md`](../research/sota-operativo-2026-04.md) (incluye referencias oficiales GitHub sobre Copilot coding agent). |

### 3.3 Skills del proyecto (orientación para agentes)

- **Orquestación GitHub/Copilot:** `.gano-skills/gano-github-copilot-orchestration/SKILL.md`.
- **Multi-agente local:** `.gano-skills/gano-multi-agent-local-workflow/SKILL.md`.
- Otras: seguridad WP, contenido, fase 4 plataforma, etc., en `.gano-skills/`.

### 3.4 Entregables auxiliares

- **Reporte PDF ejecutivo** (junta laboral): generado con `scripts/generate_board_report_pdf.py` → `reports/Gano-Digital-Reporte-Estado-YYYY-MM.pdf` (los `.pdf` suelen estar en `.gitignore`).

---

## 4. Próximos hitos (orden sugerido)

1. **Deploy** de Fases 1–3 al servidor + eliminación de `wp-file-manager`.
2. **Homepage en Elementor:** menú primary, copy real, hero, pilares, CTAs (guías en `memory/content/`).
3. **Fase 4 Reseller:** catálogo RCC, mapeo de CTAs a IDs reales, smoke test checkout.
4. **Opcional — cola API:** Actions → **08** → `tasks-api-integrations-research.json` → asignar Copilot con prompt **API integrations**.
5. **Issues en GitHub:** cerrar o comentar los que ya estén cubiertos por `main` después de la oleada de merges.
6. **Rotación de tokens** y limpieza de remotes con credenciales (al cierre del workflow de despliegue).

---

## 5. Referencias rápidas

| Recurso | Ruta / enlace |
|---------|----------------|
| Lista viva de tareas | [`TASKS.md`](../../TASKS.md) |
| Brief oleada 3 | `memory/research/gano-wave3-brand-ux-master-brief.md` |
| Memoria de proyecto | `memory/projects/gano-digital.md` |
| Cola Copilot | `.github/COPILOT-AGENT-QUEUE.md` |
| Recordatorio API + cierre PRs | [`2026-04-03-recordatorio-api-integrations-y-prs.md`](2026-04-03-recordatorio-api-integrations-y-prs.md) |
| Consolidación PRs Copilot | [`2026-04-03-consolidacion-prs-copilot.md`](2026-04-03-consolidacion-prs-copilot.md) |

---

_Cierre: el **repo** refleja oleada 4, infra y cableado de la cola API; el siguiente ciclo sigue siendo **producción**, **Elementor en vivo** y **decisión** sobre issues/sembrar API._
