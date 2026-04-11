---
name: gano-github-projects-board
description: >
  Tablero GitHub Projects @Gano.digital: matrices de campos (Area/Source alineados al repo), RACI, DoR/DoD,
  vistas, workflows nativos, status updates e Insights. Usar cuando el usuario mencione Projects, Kanban,
  reporting de equipo, o alinear issues de Pilot con el board.
---

# Gano — GitHub Projects (@Gano.digital)

## Qué es qué

- **@Gano.digital** = **GitHub Project** (interfaz: tablero, campos, automatizaciones del proyecto).
- **Pilot** = **repositorio** `Gano-digital/Pilot`; las tarjetas son issues/PRs de ese repo.

## Documento maestro (rutas verificadas desde la raíz del repo)

| Recurso | Ruta |
|---------|------|
| Playbook completo (matrices industria + estado Gano abril 2026) | [`memory/ops/github-projects-gano-digital-playbook-2026-04.md`](../../memory/ops/github-projects-gano-digital-playbook-2026-04.md) |
| Entrada desde `.github/` | [`.github/GITHUB-PROJECT-GANO-DIGITAL.md`](../../.github/GITHUB-PROJECT-GANO-DIGITAL.md) |
| Plantilla *status update* | [`.github/templates/project-status-update.md`](../../.github/templates/project-status-update.md) |
| Workflow **13** | [`.github/workflows/project-add-to-project.yml`](../../.github/workflows/project-add-to-project.yml) |

## Contenido clave del playbook

- **§0.1** — Tabla de rutas canónicas (sin enlaces rotos).
- **§1** — Matriz de **campos**; **Area** alineada a `.github/labeler.yml` (`area:theme`, `area:mu-plugins`, `area:plugins-gano`, `area:ci`) + extensiones reales del proyecto (`docs-memory`, `infra`, `commerce-reseller`, …).
- **§1.3** — **Source** alineado a workflows **08, 09, 10**, Dependabot, seguridad, coordinación.
- **§3** — Matriz **RACI** del proceso de tablero.
- **§4** — **DoR / DoD** (incl. trabajo solo servidor / Elementor).
- **§11** — **Estado y necesidades** actuales según `TASKS.md` (deploy, wp-file-manager, SEO, contenido).

## Automatización Actions

- **Variable** `GANO_PROJECT_URL` + **secret** `ADD_TO_PROJECT_PAT` — ver comentarios en el YAML del workflow 13. Sin ellos el job no se ejecuta.

## Convenciones

- Título con **`[agent]`** y/o etiqueta **`copilot`** (etiqueta creada por workflow **06**).
- PRs: `Closes #n` / `Ref #n`; revisión humana según **`.github/AGENT-REVIEW-CHECKLIST.md`**.

## Skills relacionadas

- `gano-github-copilot-orchestration`, `gano-github-ops`, `gano-multi-agent-local-workflow`
