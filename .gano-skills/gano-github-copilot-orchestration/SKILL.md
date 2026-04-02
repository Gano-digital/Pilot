---
name: gano-github-copilot-orchestration
description: >
  Orquestación GitHub Actions + Copilot coding agent + cola de issues para Gano Digital (repo
  Gano-digital/Pilot). Usar cuando haya que crear o ampliar la cola de tareas delegables,
  alinear etiquetas/labeler, explicar workflow_dispatch vs push, prompts masivos para el agente,
  DEV-COORDINATION, o cuando el usuario mencione "Seed Copilot task queue", "agent-queue",
  "label-bootstrap", "assign copilot", "offloading issues", "PRs del agente".
---

# Gano — GitHub, Actions y Copilot coding agent (SOTA operativo, Abril 2026)

## Fuente de verdad en GitHub

- **Repo:** `Gano-digital/Pilot` en GitHub (`origin`). `main` es la rama de integración con CI.
- **Documentación operativa:** [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md) — qué vive en git vs servidor vs local; plantilla `[sync]`.
- **Tareas por fase:** [`TASKS.md`](../../TASKS.md).

## Cola masiva de issues (offloading)

| Artefacto | Rol |
|-----------|-----|
| [`.github/agent-queue/tasks.json`](../../.github/agent-queue/tasks.json) | Oleada 1: `id`, `scope`, `title`, `body`, `labels` + `<!-- agent-task-id:... -->` para deduplicar. |
| [`.github/agent-queue/tasks-wave2.json`](../../.github/agent-queue/tasks-wave2.json) | Oleada 2 (`w2-*`): consolidación SEO/CI/docs/Fase 4 tras triage de PRs de la oleada 1. |
| [`.github/agent-queue/tasks-wave3.json`](../../.github/agent-queue/tasks-wave3.json) | Oleada 3 (`w3-*`): marca, UX, comercial, activos, IA, microcopy. Brief: [`memory/research/gano-wave3-brand-ux-master-brief.md`](../../memory/research/gano-wave3-brand-ux-master-brief.md). |
| **Workflow `Seed Copilot task queue`** | **`workflow_dispatch`**: elige **`queue_file`** (`tasks.json` o `tasks-wave2.json`) y **ámbito** (`all`, `homepage`, `theme`, `content_seo`, `security`, `commerce`, `docs`). |
| [`.github/MERGE-PLAYBOOK.md`](../../.github/MERGE-PLAYBOOK.md) | Orden sugerido de fusión de PRs Copilot y cierre de issues. |
| Búsqueda de duplicados | Issues **abiertos** cuyo cuerpo contiene `agent-task-id:tu-id` → no se recrea. |

**Workflow paralelo:** `Seed homepage Fix issues` — 7 issues del fixplan Elementor (etiqueta `homepage-fixplan`), también manual.

## Etiquetas y labeler

- **Setup repository labels:** manual **o** push a [`.github/label-bootstrap`](../../.github/label-bootstrap) en `main` (dispara el mismo workflow).
- Etiquetas usadas por la cola: `copilot`, `coordination`, `homepage-fixplan`, `area:theme`, `area:mu-plugins`, `area:plugins-gano`, `area:ci`, `dependencies`.
- **Pull Request Labeler** (`labeler.yml`) requiere que existan esas etiquetas (por eso el bootstrap).

## CI que deben pasar los PRs (código Gano)

- **PHP lint (Gano custom code):** `wp-content/mu-plugins/`, `gano-child/`, `wp-content/plugins/gano-*/`.
- **TruffleHog:** imagen Docker, **solo rutas Gano** (no todo el vendor de Elementor).
- **Deploy** (`deploy.yml`): puede fallar por secretos SSH / entorno; es independiente de la cola Copilot.

## Prompt adicional para asignación masiva al agente

- Archivo canónico: [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md).
- Incluye: rol/alcance, prioridad, reglas duras, CI, tabla de **self-troubleshooting**, entrega en español.
- Guía de uso: [`.github/COPILOT-AGENT-QUEUE.md`](../../.github/COPILOT-AGENT-QUEUE.md).

## Límites (honestidad SOTA)

- **Actions no ejecuta** al agente: solo **crea issues**. El usuario asigna **GitHub Copilot** (sidebar o asignación masiva).
- Issues **100 % wp-admin/Elementor** suelen cerrarse con **comentarios** o checklist en el issue; el agente no sustituye el panel.
- **No** commitear PAT en URL de remotes; rotar tokens tras workflows sensibles.

## Skills relacionadas

- `gano-multi-agent-local-workflow` — Cursor vs Claude vs GitHub.
- `gano-content-audit` — copy y placeholders enlazados a `memory/content/`.
- `gano-wp-security` — MU-plugins, CSP, secretos.
