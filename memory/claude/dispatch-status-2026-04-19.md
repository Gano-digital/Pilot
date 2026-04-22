# Estado dispatch Claude — 2026-04-19

Complementa [`dispatch-status-2026-04-17.md`](dispatch-status-2026-04-17.md). La cola `cd-repo-*` sigue **cerrada**; el foco cambió a **operación en producción** y **validación humana**.

## Completado (fuera de dispatch — ola ops)

- Auditoría SSH + documentación: [`../sessions/2026-04-19-auditoria-ssh-inventario-sota.md`](../sessions/2026-04-19-auditoria-ssh-inventario-sota.md).
- Home canónica `/`, menú Inicio, limpieza duplicados, `gano_pfid_*` en `wp_options`, política bots en `.htaccess`, `llms.txt` / `bot-seo-context.md`.
- Limpieza de plugins inactivos en servidor; runtime comercial = Reseller + fases 6/7.
- Convergencia **8 archivos** repo ↔ producción (MD5 verificado).
- Memoria Cursor + `TASKS.md` + `.github/DEV-COORDINATION.md` §6 actualizados; commit `bdf33c02` (docs trazabilidad).

## Pendiente (prioridad para Claude como asistente / handoff)

| Prioridad | Área | Qué hacer |
|-----------|------|-----------|
| P0 | RCC / Fase 4 | Validar PFIDs: slugs `rstore_id` vs **PFID numérico** RCC; cerrar `gano_pfid_online_storage`. |
| P0 | Contenido | Sustituir placeholders; alinear con [`../content/homepage-copy-2026-04.md`](../content/homepage-copy-2026-04.md). |
| P1 | SEO | Tras copy: Rank Math, GSC, pantalla Gano SEO (ver checklists en `memory/ops/`). |
| P1 | Seguridad | Activar Wordfence + 2FA en ventana; rotar tokens [#267](https://github.com/Gano-digital/Pilot/issues/267). |
| P2 | Repo | Opcional: versionar `llms.txt` / `bot-seo-context.md` bajo ruta acordada. |

## Instrucción de continuidad para Claude

1. **Leer primero:** [`2026-04-19-trazabilidad-ops-wave-handoff.md`](../sessions/2026-04-19-trazabilidad-ops-wave-handoff.md) y [`02-pendientes-detallados.md`](02-pendientes-detallados.md) (revisión 2026-04-19).
2. **No reabrir** tareas `cd-repo-*` salvo regresión documentada.
3. Tareas `ag-phase4-*` y `cd-content-*` del dispatch histórico siguen válidas como **etiquetas de trabajo humano**; el estado real está en `TASKS.md` + issue [#263](https://github.com/Gano-digital/Pilot/issues/263).
