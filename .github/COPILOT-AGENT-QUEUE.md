# Cola masiva para Copilot coding agent

## Qué es

- **`agent-queue/tasks.json`** — definición versionada de issues (título, cuerpo, etiquetas, ámbito).
- **Workflow `08 · Agentes · Sembrar cola Copilot`** (archivo `seed-copilot-queue.yml`) — crea issues en GitHub **sin duplicar** si ya hay uno **abierto** con el mismo `<!-- agent-task-id:... -->` en el cuerpo. La deduplicación lee **todos los issues abiertos** del repo (paginado), no la API Search; evita límites de tasa al sembrar muchas tareas.

## Cómo usarlo (offloading)

1. Repo → **Actions** → **08 · Sembrar cola Copilot** → **Run workflow**.
2. Elige **`queue_file`**:
   - `tasks.json` — oleada 1 (~17 tareas). No crea duplicados mientras el issue abierto tenga el mismo `agent-task-id`.
   - `tasks-wave2.json` — oleada 2 (8 tareas: merge playbook, SEO canonical, fuentes, Reseller doc, `.htaccess`, CI, a11y, post-Dependabot). Usar cuando quieras **nuevos** issues tras triage de la oleada 1.
   - `tasks-wave3.json` — oleada 3 (**marca, UX, comercial, activos, IA, microcopy**). Brief maestro: [`memory/research/gano-wave3-brand-ux-master-brief.md`](../memory/research/gano-wave3-brand-ux-master-brief.md). Varias tareas usan `scope: coordination`; elige **`all`** o **`coordination`** según necesites.
   - `tasks-wave4-ia-content.json` — oleada 4 (**narrativa única, orden de contenidos, productos/servicios/páginas**, coherencia con IA y shop Reseller). Salida esperada: `memory/content/*-2026.md` y enlaces en `TASKS.md`.
   - `tasks-infra-dns-ssl.json` — **DNS / HTTPS / dominio**: runbooks en `memory/ops/`, uso de `scripts/check_dns_https_gano.py`. El agente **documenta**; Diego o soporte aplican cambios en GoDaddy/hosting.
   - `tasks-api-integrations-research.json` — **APIs externas (ML + GoDaddy)**: profundiza sobre `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md` (mapas, runbooks, catálogo endpoints, matriz viabilidad). **Sin** credenciales en el repo.
   - `tasks-security-guardian.json` — **Guardián de seguridad** (`sec-*`): higiene de exposición, `.gitignore`, instrucciones/plantillas sin secretos; **no** revoca tokens en GitHub (humano). Skill: `.gano-skills/gano-session-security-guardian/`.
3. Elige **ámbito** (`scope`):
   - `all` — todas las tareas del archivo elegido que no tengan issue abierto con el mismo id.
   - `homepage` / `theme` / `content_seo` / `security` / `commerce` / `docs` / `coordination` / `infra` — lote parcial (`infra` solo aplica a `tasks-infra-dns-ssl.json`).
4. Abre **Issues** y asigna al agente (individual o masivo). En el modal, pega el **prompt adicional** desde [`.github/prompts/copilot-bulk-assign.md`](prompts/copilot-bulk-assign.md): hay bloques para **oleada 1**, **oleada 3**, **oleada 4**, **infra**, **API**, **guardián seguridad** — usa el que corresponda al lote.
5. Revisa CI (`php-lint`, TruffleHog en rutas Gano) antes de fusionar. Orden sugerido de fusión: [MERGE-PLAYBOOK.md](MERGE-PLAYBOOK.md).

## Requisitos

- Plan / política org que permita **Copilot coding agent** en el repo.
- Etiquetas creadas (workflow **06 · Repo · Crear etiquetas** ya ejecutado en `main`).
- Al editar colas: el workflow **07 · Validar cola JSON** comprueba los siete archivos en `agent-queue/` (ids únicos y marcador `agent-task-id`). Local: `python scripts/validate_agent_queue.py`.

## Añadir tareas

- Oleada 1: edita `agent-queue/tasks.json` con un `id` único, `scope` y `<!-- agent-task-id:tu-id -->` al final del `body`.
- Oleada 2: edita `agent-queue/tasks-wave2.json` (ids `w2-*`). Mantén `scope` dentro del conjunto que el workflow filtra (`docs`, `theme`, etc.).

## Límites

- **Actions no sustituye** a Copilot: solo **crea y organiza** issues.
- Tareas que son **100 % wp-admin/Elementor** las ejecuta el agente con limitaciones; el cuerpo del issue ya separa “en servidor” vs “en repo”.
- **DNS/registrador:** ningún agente puede modificar registros en GoDaddy desde GitHub; la cola `tasks-infra-dns-ssl.json` genera **documentación y checklists** para que un humano ejecute el fix.
