# Cola masiva para Copilot coding agent

## Qué es

- **`agent-queue/tasks.json`** — definición versionada de issues (título, cuerpo, etiquetas, ámbito).
- **Workflow `Seed Copilot task queue`** — crea issues en GitHub **sin duplicar** si ya hay uno **abierto** con el mismo `<!-- agent-task-id:... -->` en el cuerpo.

## Cómo usarlo (offloading)

1. Repo → **Actions** → **Seed Copilot task queue** → **Run workflow**.
2. Elige **ámbito**:
   - `all` — las ~17 tareas (solo las que no tengan issue abierto equivalente).
   - `homepage` / `theme` / `content_seo` / `security` / `commerce` / `docs` — lote parcial.
3. Abre **Issues**; en cada tarjeta, **Assignees → GitHub Copilot** (coding agent) para que proponga PRs.
4. Revisa CI (`php-lint`, TruffleHog en rutas Gano) antes de fusionar.

## Requisitos

- Plan / política org que permita **Copilot coding agent** en el repo.
- Etiquetas creadas (workflow **Setup repository labels** ya ejecutado en `main`).

## Añadir tareas

Edita `tasks.json` con un `id` único, `scope` y el comentario HTML `<!-- agent-task-id:tu-id -->` al final del `body` (el workflow lo usa para deduplicar).

## Límites

- **Actions no sustituye** a Copilot: solo **crea y organiza** issues.
- Tareas que son **100 % wp-admin/Elementor** las ejecuta el agente con limitaciones; el cuerpo del issue ya separa “en servidor” vs “en repo”.
