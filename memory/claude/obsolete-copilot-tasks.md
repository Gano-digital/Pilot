# Drift: cola Copilot vs código actual (auditoría local)

**Regla:** no borrar entradas de `.github/agent-queue/*.json` sin OK de Diego; esto solo documenta **probable obsolescencia** para cerrar issues en GitHub.

| agent-task-id | Archivo cola | Estado en `main` (evidencia) | Recomendación |
|---------------|--------------|-------------------------------|---------------|
| `theme-audit-handle` | `tasks.json` | `functions.php` ya usa handle `royal-elementor-kit-parent` y dependencia del child correcta (líneas ~24–25). | Cerrar issue abierto con ese id si el PR ya fusionó el cambio; no re-sembrar. |
| (revisar otros `theme-*` tras merges) | `tasks.json` | Revisar diff de `gano-child` en consolidación 2026-04-03. | Triage manual por issue. |

## Cómo usar esta tabla

1. Buscar en GitHub issues abiertos con `agent-task-id:theme-audit-handle` en el cuerpo.
2. Si `main` ya contiene el handle correcto, comentar enlace a `functions.php` y cerrar con plantilla de [`gh-issue-close-guide.md`](gh-issue-close-guide.md).
