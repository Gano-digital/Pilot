---
name: gano-github-copilot-orchestration
description: >
  Orquestación GitHub Actions + Copilot coding agent + cola de issues para Gano Digital (repo
  Gano-digital/Pilot). Usar cuando haya que crear o ampliar la cola de tareas delegables,
  alinear etiquetas/labeler, explicar workflow_dispatch vs push, prompts masivos para el agente,
  DEV-COORDINATION, o cuando el usuario mencione "Seed Copilot task queue", "agent-queue",
  "label-bootstrap", "assign copilot", "offloading issues", "PRs del agente", "orchestrate waves".
---

# Gano — GitHub, Actions y Copilot coding agent (actualizado **2026-04-03**)

## Fuente de verdad en GitHub

- **Repo:** `Gano-digital/Pilot` en GitHub (`origin`). `main` es la rama de integración con CI.
- **Documentación operativa:** [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md) — qué vive en git vs servidor vs local; plantilla `[sync]`.
- **Tareas por fase:** [`TASKS.md`](../../TASKS.md).
- **Nombres numerados de workflows (sidebar):** [`.github/workflows/README.md`](../../.github/workflows/README.md) — prefijos **01–12** (incluye **12 · Ops · wp-file-manager**).

## Estado post-consolidación (abril 2026)

- La **cola de PRs Copilot** de la oleada masiva se **fusionó en `main`** (squash, 2026-04-03). Ver [`memory/sessions/2026-04-03-consolidacion-prs-copilot.md`](../../memory/sessions/2026-04-03-consolidacion-prs-copilot.md).
- **10 · Orquestar oleadas** ya **no** es el paso obligatorio para esa oleada; usarlo solo si vuelve a haber un lote de PRs oleada 1 y se desea merge automático + `seed_wave2`.
- Puede quedar trabajo en **issues abiertos** en GitHub: revisar y **cerrar manualmente** con comentario si el código ya está en `main`.
- **Siguiente foco operativo:** secrets → **04 · Deploy** / **05 · Verificar parches** → **12 · Eliminar wp-file-manager** (si aplica) → RCC / Elementor.

## Cola masiva de issues (offloading)

| Artefacto | Rol |
|-----------|-----|
| [`.github/agent-queue/tasks.json`](../../.github/agent-queue/tasks.json) | Oleada 1: `id`, `scope`, `title`, `body`, `labels` + `<!-- agent-task-id:... -->` para deduplicar. |
| [`.github/agent-queue/tasks-wave2.json`](../../.github/agent-queue/tasks-wave2.json) | Oleada 2 (`w2-*`). |
| [`.github/agent-queue/tasks-wave3.json`](../../.github/agent-queue/tasks-wave3.json) | Oleada 3 (`w3-*`). Brief: [`memory/research/gano-wave3-brand-ux-master-brief.md`](../../memory/research/gano-wave3-brand-ux-master-brief.md). |
| [`.github/agent-queue/tasks-wave4-ia-content.json`](../../.github/agent-queue/tasks-wave4-ia-content.json) | Oleada 4 (`w4-*`): narrativa, páginas, matriz productos/servicios vs shop. |
| [`.github/agent-queue/tasks-infra-dns-ssl.json`](../../.github/agent-queue/tasks-infra-dns-ssl.json) | Infra (`dns-*`): runbooks DNS/HTTPS en `memory/ops/`; **humano** aplica cambios en registrador. |
| [`.github/agent-queue/tasks-api-integrations-research.json`](../../.github/agent-queue/tasks-api-integrations-research.json) | APIs ML + GoDaddy (`api-*`): anexos a `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`; sin secretos. |
| [`.github/agent-queue/tasks-security-guardian.json`](../../.github/agent-queue/tasks-security-guardian.json) | Guardián (`sec-*`): higiene repo/docs/.gitignore; skill `gano-session-security-guardian`. |
| **08 · Agentes · Sembrar cola Copilot** (`seed-copilot-queue.yml`) | `workflow_dispatch`: **`queue_file`** (7 JSON), **ámbito** (`all`, …, **`infra`**). |
| **09 · Agentes · Sembrar issues homepage** | 7 issues fixplan (`homepage-fixplan`), manual. |
| [`.github/MERGE-PLAYBOOK.md`](../../.github/MERGE-PLAYBOOK.md) | Orden sugerido de fusión cuando vuelva a haber muchos PRs. |

**Workflow paralelo Ops:** **12 · Ops · Eliminar wp-file-manager** — requiere los mismos secrets SSH que **04 · Deploy**.

## Etiquetas y labeler

- **06 · Repo · Crear etiquetas:** manual **o** push a [`.github/label-bootstrap`](../../.github/label-bootstrap) en `main`.
- **03 · PR · Etiquetas automáticas** (`labeler.yml`) usa [`.github/labeler.yml`](../../.github/labeler.yml) + `actions/checkout` antes del labeler; **no** usar `changed-files-labels-limit` en el mismo archivo si el parser v6 lo rechaza (ver [`memory/ops/github-actions-audit-2026-04.md`](../../memory/ops/github-actions-audit-2026-04.md)).

## CI que deben pasar los PRs (código Gano)

- **01 · CI · Sintaxis PHP (Gano)** — `wp-content/mu-plugins/`, `gano-child/`, `wp-content/plugins/gano-*/`.
- **02 · CI · TruffleHog** — rutas Gano acotadas.
- **04 · Deploy** — depende de secretos; independiente de la cola Copilot.

## Prompt adicional para asignación masiva al agente

- Archivo canónico: [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md).
- Incluye **bloque maestro (bulk)**, bloques por lote: oleada **1** (#17–#33), oleada **3** (#54–#68), oleada **4** (contenido/narrativa), **infra** DNS/HTTPS, **API** ML+GoDaddy (research), y *Definition of Done*.
- Guía: [`.github/COPILOT-AGENT-QUEUE.md`](../../.github/COPILOT-AGENT-QUEUE.md).
- Marco “SOTA” operativo (prácticas repo + enlaces oficiales GitHub sobre Copilot agent): [`memory/research/sota-operativo-2026-04.md`](../../memory/research/sota-operativo-2026-04.md).

## Límites (honestidad SOTA)

- **Actions no ejecuta** al agente: **crea issues**. El usuario asigna **GitHub Copilot** (sidebar o bulk).
- Issues **100 % wp-admin/Elementor** suelen cerrarse con **checklist** en el issue.
- **No** commitear PAT en URL de remotes; rotar tokens tras workflows sensibles.

## Indicadores (actualizar antes de citar números)

| Indicador | Significado |
|-----------|-------------|
| Issues `[agent]` abiertos | Trabajo no cerrado en GitHub o no reflejado en `main`; revisar uno a uno. |
| PRs abiertos | Tras consolidación 2026-04-03 debería ser **bajo**; cada PR nuevo → checklist [`.github/AGENT-REVIEW-CHECKLIST.md`](../../.github/AGENT-REVIEW-CHECKLIST.md). |

## Skills relacionadas

- `gano-multi-agent-local-workflow` — Cursor vs Claude vs GitHub.
- `gano-session-security-guardian` — cierre de sesión, checklist, cola `tasks-security-guardian`.
- `gano-content-audit` — copy y placeholders en `memory/content/`.
- `gano-wp-security` — MU-plugins, CSP, secretos.
