# Historial y contexto — Cursor y GitHub (abril 2026)

Documento narrativo para **Claude**: resume trabajo hecho en sesiones de Cursor relacionadas con **GitHub Pilot**, **Copilot coding agent**, **workflows** y **documentación**, y cómo encaja con el resto del proyecto Gano Digital.

---

## 1. Marco del proyecto (recordatorio breve)

**gano.digital** es el sitio WordPress + Elementor + WooCommerce (Colombia) de Gano Digital. Este repositorio (**Pilot** en GitHub) contiene sobre todo:

- Tema child (`gano-child`), MU-plugins de seguridad/SEO, plugins `gano-*`, scripts y documentación.
- Automatización: GitHub Actions numerados **01–12**, colas JSON para issues Copilot, playbooks de merge y coordinación con servidor.

Las **fases 1–3** (seguridad, hardening, SEO/performance en código) están **aplicadas en el repo**; la brecha operativa típica es **sincronizar con el servidor real**, **configurar paneles** (SEO, GSC, Rank Math) y **contenido en Elementor**.

---

## 2. Línea de tiempo — abril 2026

### 2.1 Sesiones previas documentadas en `memory/sessions/`

| Fecha (aprox.) | Archivo | Tema |
|----------------|---------|------|
| 2026-04-01 | [`2026-04-01-reporte-cursor-descargas-y-herramientas.md`](../sessions/2026-04-01-reporte-cursor-descargas-y-herramientas.md) | Organización local, scripts de análisis, higiene de claves SSH. |
| 2026-04-02 | Varios (`2026-04-02-*.md`) | Triage Copilot, progreso consolidado, investigación SOTA/skills, ejecución y continuidad. |
| 2026-04-02 | [`2026-04-02-progreso-consolidado.md`](../sessions/2026-04-02-progreso-consolidado.md) | Síntesis equipo: logros fases 1–3, estado GitHub, pendientes. **Nota:** la sección de “PRs abiertos ~35” quedó **obsoleta** tras el merge masivo del 2026-04-03; usar este historial + sesión 04-03 como verdad actual para PRs. |

### 2.2 Consolidación masiva de PRs Copilot — **2026-04-03**

**Objetivo:** Vaciar la cola de pull requests abiertos generados por agentes, integrando en `main` con **squash merge** y criterio del [`MERGE-PLAYBOOK`](../../.github/MERGE-PLAYBOOK.md).

**PRs cerrados sin merge (duplicado u obsoleto):**

| PR | Motivo |
|----|--------|
| #49 | README/deploy ya alineados en `main`; conflicto con base. |
| #84 | WIP vacío; trabajo sustituido por #83 (a11y). |
| #85 | WIP; especificación OG cubierta por #82. |

**Fusionados (orden aproximado):** secuencia larga desde #34 hasta #82, incluyendo #83; al final **#52** y **#76** tras **resolución manual de conflictos**.

**Conflictos relevantes y decisión:**

1. **`shop-premium.php` (#52):** La rama del agente usaba un modelo con `rstore_id`; `main` ya llevaba el modelo **pfid** (`GANO_PFID_*`) y `gano_rstore_cart_url()`. **Resolución:** Mantener el modelo **pfid** de `main` y precios **1.200.000 / 1.800.000 COP** (catálogo alineado con la estrategia Reseller).

2. **`style.css` (#76):** Solapamiento entre utilidades tipográficas (wave3) y bloque **accesibilidad** (#83). **Resolución:** Conservar **ambos** bloques en el mismo archivo (tipografía primero, bloque a11y después).

**Estado al cierre de esa sesión:** PRs abiertos en **0**; `main` incorporaba smoke test reseller, visual tokens wave3 y catálogo shop alineado.

Detalle tabular: [`../sessions/2026-04-03-consolidacion-prs-copilot.md`](../sessions/2026-04-03-consolidacion-prs-copilot.md).

### 2.3 Documentación y alineación post-consolidación (misma ventana abril 2026)

Tras vaciar PRs, se alineó la **documentación operativa** para que Cursor, Claude y Diego compartan el mismo mapa:

| Cambio | Archivo(s) | Propósito |
|--------|------------|-----------|
| Skill Copilot/Actions actualizada | [`.gano-skills/gano-github-copilot-orchestration/SKILL.md`](../../.gano-skills/gano-github-copilot-orchestration/SKILL.md) | Estado **post-merge**, workflows **01–12**, cuándo usar **08/09/10/12**, foco secrets → deploy → parches → wp-file-manager. |
| Recordatorio personal | [`../notes/nota-diego-recomendaciones-2026-04.md`](../notes/nota-diego-recomendaciones-2026-04.md) | Tabla de prioridades humanas + tabla “¿hace falta lanzar este workflow?” |
| Tareas globales | [`TASKS.md`](../../TASKS.md) | Bloque **REGRESAR AQUÍ**, sección GitHub: oleada 1 marcada como fusionada; **10 · Orquestar oleadas** solo si hay **futuro** lote. |
| Nombre unificado workflow 12 | [`.github/workflows/verify-remove-wp-file-manager.yml`](../../.github/workflows/verify-remove-wp-file-manager.yml) | `name:` = **`12 · Ops · Eliminar wp-file-manager (SSH)`** (coherencia con sidebar numerado). |
| Índice workflows | [`.github/workflows/README.md`](../../.github/workflows/README.md) | Fila del **12**; fases **04–05, 12** como Ops. |
| Tabla DEV-COORDINATION | [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md) | Rango `01 … **12**` y fila del workflow **12**. |
| Tabla skills en memoria raíz | [`CLAUDE.md`](../../CLAUDE.md) | Fila `gano-github-copilot-orchestration` apunta a workflows **01–12** y estado abril 2026. |

**Commit de referencia (documentación):** `8f816713` — mensaje tipo *docs: skill Copilot 01-12, recordatorio Diego, workflow 12 alineado* — pusheado a `origin/main` del repo Pilot en GitHub.

### 2.4 Creación de esta carpeta `memory/claude/`

**Petición de Diego:** Documentar **extensivamente** lo hecho y lo pendiente, en **registros bien estructurados** para Claude en **carpeta propia**.

**Entrega:** `memory/claude/README.md` + tres documentos de apoyo (`01`–`03`) + enlace desde `CLAUDE.md`.

### 2.5 Post-consolidación — investigación APIs, CI y guardián (2026-04-04)

Tras el merge masivo del **2026-04-03**, el equipo siguió en `main` con trabajo **documentación + colas Copilot + CI** (referencia de commits recientes en el remoto):

| Tema | Commit (corto) | Notas |
|------|------------------|--------|
| Cola Copilot **API** (ML + GoDaddy) + informe SOTA | `58d3d626` | `tasks-api-integrations-research.json`, `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`, cableado en docs/skills. |
| SOTA operativo y progreso consolidado | `076ef8bf` | Alineación narrativa GitHub/Copilot con el estado del repo. |
| **Labeler v6**, PHP lint en workflow **11**, auditoría Actions | `a34e4a13` | `memory/ops/github-actions-audit-2026-04.md` — checkout antes de `actions/labeler@v6`, rutas de labels. |
| PDF junta (`generate_board_report_pdf`) | `f8f3ddfc` | Contenido abril 2026 embebido en script; regenerar para handoff ejecutivo. |
| **Guardián seguridad** — cola, skill, playbook | `a7a1e353` | `tasks-security-guardian.json`, `.gano-skills/gano-session-security-guardian/SKILL.md`, `memory/ops/security-guardian-playbook-2026.md`. |

Issues/PRs posteriores en la serie **#108–#113** cubrieron copy coherencia, índice `memory/content/`, DNS/HTTPS y enlaces en `TASKS` / FAQ según el historial de merge.

---

## 3. Implicaciones para el trabajo futuro de un agente

1. **No asumir que “hay docenas de PRs abiertos”** sin mirar GitHub: la oleada grande ya entró en `main` (2026-04-03).

2. **Workflow 10:** Solo tiene sentido si vuelve a existir un **lote** de PRs “oleada 1” y se desea automatizar merge ordenado + semilla oleada 2. **No** es el siguiente paso obligatorio para la oleada ya fusionada.

3. **Workflows 08 / 09:** Ejecutar **solo si** hace falta **crear** nuevos issues desde JSON o el fixplan homepage aún no existe en el repo (deduplicación y estado real del remoto).

4. **RCC / pfids:** El código en `main` puede seguir con placeholders (`PENDING_RCC` u homólogos); completar en `functions.php` / shop es **tarea humana + RCC**, no solo merge.

5. **Plugins de fase** (`gano-phase1-installer`, …): **No eliminar** hasta confirmar activación y contenido en sitio — ver [`../notes/plugins-de-fase.md`](../notes/plugins-de-fase.md) y [`CLAUDE.md`](../../CLAUDE.md).

---

## 4. Relación con otros documentos de memoria

- **Wave3 marca/UX:** Brief [`../research/gano-wave3-brand-ux-master-brief.md`](../research/gano-wave3-brand-ux-master-brief.md), cola [`.github/agent-queue/tasks-wave3.json`](../../.github/agent-queue/tasks-wave3.json).
- **Copy homepage:** [`../content/homepage-copy-2026-04.md`](../content/homepage-copy-2026-04.md), [`../content/elementor-home-classes.md`](../content/elementor-home-classes.md).
- **Fase 4 plataforma (billing, portal, etc.):** [`../research/fase4-plataforma.md`](../research/fase4-plataforma.md) — estrategia actual en TASKS incluye foco Reseller; convivir con investigación histórica.

---

_Ultima actualización del contenido de este archivo: **2026-04-04**._
