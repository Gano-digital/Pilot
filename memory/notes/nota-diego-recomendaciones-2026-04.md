# Nota personal — Diego (no olvidar)

_Documento de recordatorio operativo. Última revisión: **2026-04-03** (post-consolidación PRs)._

## Recordatorio rápido — qué te toca a ti

| Prioridad | Acción |
|-----------|--------|
| **Crítico** | Configurar secrets **SSH**, **SERVER_HOST**, **SERVER_USER**, **DEPLOY_PATH** en GitHub → luego **04 · Deploy** o **05 · Verificar parches**. |
| **Crítico** | Quitar **wp-file-manager** del servidor: **12 · Ops · Eliminar wp-file-manager** (Actions) o manual en wp-admin/SFTP. |
| **Alta** | **RCC:** reemplazar `PENDING_RCC` / pfids en `functions.php` y validar **Private Label ID** del Reseller para checkout real. |
| **Alta** | **Elementor / wp-admin:** menú `primary`, Lorem → copy (`memory/content/homepage-copy-2026-04.md`), datos reales footer/contacto (auditorías en `memory/content/`). |
| **Alta** | **Gano SEO**, **Rank Math**, **Google Search Console** según `TASKS.md` (Fase 3 operativa). |
| **Media** | Cerrar **issues** en GitHub que sigan abiertos pero ya cubiertos por merges a `main` (revisión manual en lista de issues). |

## ¿Faltan agentes y workflows por lanzar?

### Workflows (Actions) — cuándo ejecutarlos

| Workflow | ¿Hace falta ahora? | Notas |
|----------|-------------------|--------|
| **04 · Deploy** | **Sí**, cuando tengas secrets y quieras subir `main` al servidor. | Se dispara también por push a rutas cubiertas si ya configuraste secrets. |
| **05 · Verificar parches** | **Sí** (recomendado), para comprobar MD5 repo vs servidor antes/después del deploy. | Manual. |
| **12 · Eliminar wp-file-manager** | **Sí** si el plugin sigue en producción. | Mismos secrets que deploy. |
| **08 · Sembrar cola Copilot** | **Solo si** quieres **nuevos** issues desde `tasks.json` / `tasks-wave2.json` / `tasks-wave3.json` y no existen ya (deduplicación por `agent-task-id`). | No obligatorio tras consolidar PRs. |
| **09 · Sembrar issues homepage** | **Solo si** los 7 issues `homepage-fixplan` **no** están creados en el repo. | Ejecutar una vez y revisar duplicados. |
| **10 · Orquestar oleadas** | **No** para la oleada ya fusionada (2026-04-03). | Úsalo **solo** si vuelves a tener un lote de PRs oleada 1 y quieres merge automático + seed oleada 2. |
| **06 · Crear etiquetas** | **Solo si** faltan etiquetas `area:*` (labeler roto). | |
| **07 · Validar cola JSON** | Automático al tocar `.github/agent-queue/`; local: `python scripts/validate_agent_queue.py`. | |
| **11 · Setup Copilot** | Opcional; al configurar el repo o nuevos pasos Copilot. | |

### Agentes (Copilot coding agent)

- **No** hay que “lanzar” un agente global: se asigna **por issue** o en bulk desde la UI de GitHub.
- Tras consolidar código en `main`, el siguiente uso típico del agente es: **08** (nuevos issues) → asignar Copilot → prompt desde [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md) (incluye **bloque maestro** para bulk).

## Enlaces útiles

| Qué | Dónde |
|-----|--------|
| Guía workflows (01–12) | [`.github/workflows/README.md`](../../.github/workflows/README.md) |
| Consolidación PRs | [`../sessions/2026-04-03-consolidacion-prs-copilot.md`](../sessions/2026-04-03-consolidacion-prs-copilot.md) |
| Coordinación git ↔ servidor | [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md) |
| Tareas globales | [`TASKS.md`](../../TASKS.md) |
| Prompt bulk / DoD | [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md) |

## Historial (archivo — ya resuelto)

- ~~#47, #49, #84, #85~~ cerrados; oleada de PRs fusionada en `main` el 2026-04-03.
- **Orchestrate oleadas** ya no es el siguiente paso obligatorio para esa oleada.

## Validación local

```bash
python scripts/validate_agent_queue.py
```

Debe imprimir `OK` si las colas en `.github/agent-queue/` son válidas.

---

_Actualizá esta nota o `TASKS.md` cuando cierres un bloque (deploy, RCC, Elementor)._
