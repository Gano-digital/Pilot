# Contexto para Claude — Gano Digital (Pilot)

Esta carpeta concentra **registros estructurados** para que cualquier instancia de Claude (Cursor, Claude Code, web con repo adjunto) recupere **qué se hizo en abril 2026**, **en qué estado quedó el repositorio** y **qué sigue pendiente**, sin depender solo del historial de un chat.

## Orden de lectura recomendado

| Orden | Archivo | Para qué sirve |
|------:|---------|----------------|
| 1 | [README.md](README.md) (este) | Navegación y convenciones. |
| 2 | [01-historial-y-contexto-cursor-2026-04.md](01-historial-y-contexto-cursor-2026-04.md) | Línea de tiempo, decisiones, consolidación de PRs, paso de documentación. |
| 3 | [02-pendientes-detallados.md](02-pendientes-detallados.md) | Inventario exhaustivo de pendientes por área y prioridad. |
| 4 | [03-referencia-tecnica-y-faq.md](03-referencia-tecnica-y-faq.md) | Workflows, rutas canónicas, respuestas cortas a dudas recurrentes. |
| 5 | [dispatch-prompt.md](dispatch-prompt.md) + [dispatch-queue.json](dispatch-queue.json) | **Cola programable para Claude** en el repo: qué puede hacer el agente sin wp-admin ni secrets; scripts en `scripts/claude_dispatch.py`. |

## Cola dispatch (Claude en el workspace)

- **Definición de tareas:** [`dispatch-queue.json`](dispatch-queue.json) — 12 ítems (`cd-repo-001` … `cd-repo-012`) con instrucciones, paths y *definition of done*.
- **Cómo usarlo:** [`dispatch-prompt.md`](dispatch-prompt.md) — flujo `next` / `complete`, prompt maestro para Claude Projects, y límites (no se puede “programar” la nube Anthropic desde git).
- **Scripts:** `python scripts/claude_dispatch.py list|next|show <id>|complete <id>` · `python scripts/validate_claude_dispatch.py`
- **Cursor/VS Code:** tareas en [`.vscode/tasks.json`](../../.vscode/tasks.json) (`Tasks: Run Task`); atajos globales en perfil Cursor (ver [`dispatch-prompt.md`](dispatch-prompt.md)).
- **Progreso local:** `memory/claude/dispatch-state.json` (gitignored; se crea al primer `complete`).

Esto es **independiente** de la cola Copilot (`.github/agent-queue/*.json` → issues en GitHub).

## Documentos “oficiales” fuera de esta carpeta

- **Memoria del proyecto y stack:** [`CLAUDE.md`](../../CLAUDE.md) en la raíz del workspace.
- **Lista maestra de tareas:** [`TASKS.md`](../../TASKS.md).
- **Recordatorio operativo de Diego (prioridades + cuándo correr Actions):** [`../notes/nota-diego-recomendaciones-2026-04.md`](../notes/nota-diego-recomendaciones-2026-04.md).
- **Consolidación PRs Copilot (2026-04-03):** [`../sessions/2026-04-03-consolidacion-prs-copilot.md`](../sessions/2026-04-03-consolidacion-prs-copilot.md).
- **Coordinación git ↔ servidor:** [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md).
- **Skill orquestación GitHub/Copilot:** [`.gano-skills/gano-github-copilot-orchestration/SKILL.md`](../../.gano-skills/gano-github-copilot-orchestration/SKILL.md).

## Convenciones

- Las fechas del proyecto asumen **año 2026** (autoridad: metadatos de sesión del workspace).
- **“Hecho en repo”** no implica **desplegado en producción** salvo que se diga explícitamente.
- Los números de **issue/PR** de GitHub pueden cambiar en forks; la fuente de verdad es el remoto **Gano-digital/Pilot** en GitHub.

## Mantenimiento

Cuando se cierre un bloque grande (deploy, RCC, eliminación de wp-file-manager, cierre masivo de issues), actualizar:

1. [`02-pendientes-detallados.md`](02-pendientes-detallados.md) y [`TASKS.md`](../../TASKS.md).
2. Opcionalmente añadir una entrada datada al final de [`01-historial-y-contexto-cursor-2026-04.md`](01-historial-y-contexto-cursor-2026-04.md) o crear `memory/sessions/YYYY-MM-DD-*.md` y enlazarlo aquí.
