---
name: gano-github-projects-board
description: >
  Tablero GitHub Projects @Gano.digital: vistas, campos, workflows nativos, status updates e Insights
  para reportar progreso al equipo. Usar cuando el usuario mencione Projects, tablero Kanban, roadmap del proyecto,
  status update, automatizar columnas, o alinear issues Pilot con el board.
---

# Gano — GitHub Projects (@Gano.digital)

## Qué es qué

- **@Gano.digital** = **Project** (interfaz de tablero en GitHub).
- **Pilot** = **repo** (`Gano-digital/Pilot`); las tarjetas son issues/PRs de ese repo.

## Documento maestro

**[`memory/ops/github-projects-gano-digital-playbook-2026-04.md`](../../memory/ops/github-projects-gano-digital-playbook-2026-04.md)** — campos recomendados (Status, Priority, Area, Source, Iteration), vistas (Backlog, Iteration, Roadmap, Agent queue), **Workflows** nativos del proyecto, **Status updates**, **Insights**, permisos.

## Plantilla rápida

- Status update semanal: [`.github/templates/project-status-update.md`](../../.github/templates/project-status-update.md)
- Entrada en repo: [`.github/GITHUB-PROJECT-GANO-DIGITAL.md`](../../.github/GITHUB-PROJECT-GANO-DIGITAL.md)

## Automatización en Actions

- Workflow **13 · Projects · Añadir issues al tablero** — `project-add-to-project.yml`; requiere `GANO_PROJECT_URL` (variable) y `ADD_TO_PROJECT_PAT` (secret). Sin ellos no hace nada.

## Convenciones

- Issues de cola Copilot: título con **`[agent]`** y/o etiqueta **`copilot`**.
- Al cerrar trabajo: cerrar issue en GitHub y enlazar PR; dejar **Workflow** del proyecto que ponga **Status → Done** si aplica.

## Skills relacionadas

- `gano-github-copilot-orchestration`, `gano-github-ops`, `gano-multi-agent-local-workflow`
