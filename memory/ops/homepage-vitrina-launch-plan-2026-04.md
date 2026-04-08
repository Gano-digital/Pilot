# Plan vitrina gano.digital — agentes, procesos y objetivos

**Última actualización:** 2026-04-08  
**URL objetivo:** https://gano.digital/  
**Estado del sitio (abril 2026):** narrativa y hero alineados a marca; persisten **Lorem**, **métricas en cero** y **copy placeholder** en bloques pilar y confianza. Este documento **alinea** a Cursor, Copilot, Claude y trabajo humano sin duplicar listas enteras de `TASKS.md`.

---

## 1. Objetivo global

| Meta | Criterio |
|------|----------|
| **Vitrina creíble** | Sin Lorem ni cifras inventadas; copy en es-CO, tono manifiesto técnico. |
| **Paridad repo ↔ servidor** | Parches F1–3 y child theme desplegados; drift documentado si aplica. |
| **Comercio** | CTAs y plantillas apuntan a **GoDaddy Reseller** (RCC + PFIDs reales), no pasarelas ajenas. |
| **Gobernanza** | Una fuente de verdad por capa: `TASKS.md` (operativo), este doc (vitrina + roles), `memory/content/` (copy fuente). |

---

## 2. Roles y agentes (RACI simplificado)

| Actividad | Responsable | Colabora | Informado |
|-----------|-------------|----------|-----------|
| **Secrets / SSH / workflow 04–05–12** | Diego + verificación CI | Cursor (docs, YAML acotado) | Ops Hub |
| **Pegar copy en Elementor / medios / menús** | Diego (wp-admin) | — | Copilot (issues de guía) |
| **Copy y briefs en repo** | Copilot oleadas / Cursor | Diego (aprobación marca) | `memory/content/` |
| **PHP/CSS tema, `shop-premium.php`, MU-plugins** | Cursor / PRs | Copilot (issues acotados) | `TASKS.md` |
| **RCC, PFID, catálogo Reseller** | Diego | Cursor (checklists en `memory/commerce/`) | Fase 4 en `TASKS.md` |
| **Cola Claude / dispatch** | Diego | `memory/claude/dispatch-queue.json` | Sesiones en `memory/sessions/` |
| **Constellation / Battle Map** | Cursor / Copilot oleadas `cx-*` | Diego | Plan en `memory/constellation/` |

**Regla:** Ningún agente asume estado de **producción** (BD Elementor, menús) sin issue o `TASKS.md` — ver [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md).

---

## 3. Fases (orden de ejecución)

### Fase 0 — Infra y despliegue (bloquea el resto)

- Alinear **secret `SSH`** con la misma pareja que acceso local; `SERVER_*`, `DEPLOY_PATH` coherentes.
- Tras merge **#159**, el workflow **04** incluye **Huella** (`ssh-add -l`) y **Probar SSH** antes de rsync. Si **Probar SSH** falla, comparar la huella del log con `ssh-keygen -lf` de la `.pub` que está en el servidor (ver troubleshooting).
- Workflows: **04 · Deploy**, **05 · Verificar parches**, **12 · wp-file-manager** según [`TASKS.md`](../../TASKS.md) § Active.
- Runbook SSH CI: [`github-actions-ssh-secret-troubleshooting.md`](github-actions-ssh-secret-troubleshooting.md).

### Fase 1 — Homepage en Elementor (solo panel)

- Fuente de copy: [`memory/content/homepage-copy-2026-04.md`](../content/homepage-copy-2026-04.md).
- Sustituir Lorem, bullets en inglés y **métricas en cero** por copy prudente o cifras auditables.
- Menú **primary**, layout: [`elementor-home-classes.md`](../content/elementor-home-classes.md), [`homepage-section-order-spec-2026.md`](../content/homepage-section-order-spec-2026.md).
- Issues opcionales: sembrar **09 · homepage-fixplan** si hace falta granularidad en GitHub.

### Fase 2 — Confianza y páginas legales / marca

- Página **Nosotros** / manifiesto; políticas cuando existan borradores.
- Gano SEO, GSC, Rank Math: [`TASKS.md`](../../TASKS.md) § Active.
- Eliminar testimonios o métricas no respaldadas (`TASKS.md` Pending).

### Fase 3 — Comercio (Fase 4 Reseller)

- RCC, precios, catálogo; mapeo UI → carrito: [`memory/commerce/rcc-pfid-checklist.md`](../commerce/rcc-pfid-checklist.md).
- `shop-premium.php` y CTAs alineados a PFIDs reales.
- Smoke test checkout marca blanca.

### Fase 4 — Endurecimiento operativo

- 2FA wp-admin; limpieza plugins de fase cuando el contenido esté aplicado; colas opcionales (API research, security guardian) **sin** pausar vitrina.

---

## 4. Proceso acordado (flujo de trabajo)

```
memory/content/*.md  →  wp-admin / Elementor (humano)  →  validación visual
        ↑
   Copilot / Cursor (PRs solo para repo)
        ↑
gano-child / plugins  →  04 Deploy (CI)  →  servidor
```

1. **Editar** copy primero en `memory/content/` cuando sea posible (auditable en git).
2. **Replicar** en Elementor en producción o staging (GoDaddy).
3. **Registrar** drift con plantilla *Reporte de sincronización* si repo y panel divergen.

---

## 5. Enlaces canónicos (no ramificar)

| Tema | Documento |
|------|-----------|
| Lista operativa priorizada | [`TASKS.md`](../../TASKS.md) |
| Brujula Git / servidor | [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md) |
| Instrucciones Copilot | [`.github/copilot-instructions.md`](../../.github/copilot-instructions.md) |
| Contexto Cursor | [`.cursor/memory/activeContext.md`](../../.cursor/memory/activeContext.md) |
| Copy homepage | [`homepage-copy-2026-04.md`](../content/homepage-copy-2026-04.md) |
| Mapa `memory/` | [`digital-files-and-content-setup.md`](../content/digital-files-and-content-setup.md) |
| Oleadas y colas JSON | [`.github/agent-queue/`](../../.github/agent-queue/) |

---

## 6. Qué no hacer (todos los agentes)

- No prometer estado del servidor sin fuente en `TASKS.md` o issue.
- No inventar IDs de producto Reseller: solo RCC / documentación comercial.
- No commitear credenciales ni tocar `wp-config.php` en git.

---

_Actualizar este archivo cuando una fase quede cerrada o cambie el RACI._
