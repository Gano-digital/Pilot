# Cola masiva para Copilot coding agent

## Qué es

- **`agent-queue/tasks.json`** — definición versionada de issues (título, cuerpo, etiquetas, ámbito).
- **Workflow `Seed Copilot task queue`** — crea issues en GitHub **sin duplicar** si ya hay uno **abierto** con el mismo `<!-- agent-task-id:... -->` en el cuerpo.

## Cómo usarlo (offloading)

1. Repo → **Actions** → **Seed Copilot task queue** → **Run workflow**.
2. Elige **`queue_file`**:
   - `tasks.json` — oleada 1 (~17 tareas). No crea duplicados mientras el issue abierto tenga el mismo `agent-task-id`.
   - `tasks-wave2.json` — oleada 2 (8 tareas: merge playbook, SEO canonical, fuentes, Reseller doc, `.htaccess`, CI, a11y, post-Dependabot). Usar cuando quieras **nuevos** issues tras triage de la oleada 1.
3. Elige **ámbito** (`scope`):
   - `all` — todas las tareas del archivo elegido que no tengan issue abierto con el mismo id.
   - `homepage` / `theme` / `content_seo` / `security` / `commerce` / `docs` — lote parcial (mismos nombres de `scope` en el JSON).
4. Abre **Issues** y asigna al agente (individual o masivo). En el modal, pega el **prompt adicional** desde [`.github/prompts/copilot-bulk-assign.md`](prompts/copilot-bulk-assign.md) para mínima supervisión.
5. Revisa CI (`php-lint`, TruffleHog en rutas Gano) antes de fusionar. Orden sugerido de fusión: [MERGE-PLAYBOOK.md](MERGE-PLAYBOOK.md).

## Requisitos

- Plan / política org que permita **Copilot coding agent** en el repo.
- Etiquetas creadas (workflow **Setup repository labels** ya ejecutado en `main`).
- Al editar colas: el workflow **Validate agent queue JSON** comprueba `tasks.json` / `tasks-wave2.json` (ids únicos y marcador `agent-task-id`). Local: `python scripts/validate_agent_queue.py`.

## Añadir tareas

- Oleada 1: edita `agent-queue/tasks.json` con un `id` único, `scope` y `<!-- agent-task-id:tu-id -->` al final del `body`.
- Oleada 2: edita `agent-queue/tasks-wave2.json` (ids `w2-*`). Mantén `scope` dentro del conjunto que el workflow filtra (`docs`, `theme`, etc.).

## Límites

- **Actions no sustituye** a Copilot: solo **crea y organiza** issues.
- Tareas que son **100 % wp-admin/Elementor** las ejecuta el agente con limitaciones; el cuerpo del issue ya separa “en servidor” vs “en repo”.
