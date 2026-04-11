# Skill: Cloud Codebase Starter

## Metadata
- **Name**: cloud-codebase-starter
- **Description**: Arranque rápido para agentes Cloud en `Gano-digital/Pilot`: qué instalar, qué se puede ejecutar desde el repo, qué requiere login humano y cómo probar cada área sin inventar infraestructura.
- **Scope**: Workspace (`/workspace`)
- **Use when**: La tarea pide "run", "test", "start", "staging", "wp-admin", "workflow", "Cloud agent setup" o cuando un agente llega por primera vez al repo.

## Regla base

No busques credenciales en el repo. `wp-config.php`, secrets de GitHub Actions, accesos a wp-admin, RCC y SSH viven fuera de git y son responsabilidad humana o del entorno ya provisionado. Si no existen en el contexto de la tarea, trabaja con checks de repo y documenta la limitación.

## Primeros 5 minutos

1. Leer `TASKS.md`, `.github/DEV-COORDINATION.md`, `CLAUDE.md` y `.github/copilot-instructions.md`.
2. Inicializar el submódulo GSD si hace falta:
   - `git submodule update --init`
3. Verificar runtimes:
   - `php -v`
   - `node -v`
   - `python3 --version`
4. Instalar dependencias solo en el área que vas a tocar:
   - `.gsd/` → `npm install`
   - `.gsd/sdk/` → `npm install`
   - reportes PDF → `pip install fpdf2`
5. Asumir que WordPress completo no arranca solo con este repo a menos que ya exista entorno local o staging operativo.

## Login y accesos

### wp-admin / Elementor / GoDaddy Reseller / servidor
- **Requieren login humano** o una sesión ya provisionada.
- Fuentes permitidas: secretos de GitHub Actions, variables locales seguras o credenciales entregadas explícitamente en la tarea.
- Si necesitas probar Elementor, activar plugins de fase o verificar RCC, primero confirma que el acceso ya existe; si no, limita la validación a lint, archivos y runbooks.

### GitHub Actions manuales
- Requieren permisos de repo/org y, para deploy/ops, estos secrets:
  - `SSH`
  - `SERVER_HOST`
  - `SERVER_USER`
  - `DEPLOY_PATH`

### Claude Code CLI
- Solo es necesario para ciertos tests de `.gsd/sdk/`.
- Si el binario `claude` no existe o no está autenticado, usa `test:unit` y evita vender los E2E como cubiertos.

## Área 1 — WordPress custom code

### Rutas clave
- `wp-content/mu-plugins/`
- `wp-content/themes/gano-child/`
- `wp-content/plugins/gano-*`

### Cómo "arrancar" esta área
- El repo trae la instalación de WordPress y el código custom, pero **no** una base de datos ni un flujo autocontenido para levantar wp-admin en Cloud.
- Si ya existe entorno local o staging provisionado, usa ese entorno.
- Si no existe, usa verificación estática y los workflows/manuales del repo.

### Workflow de prueba mínimo
1. Si tocaste PHP custom, replica el check de CI:
   - `find wp-content/mu-plugins wp-content/themes/gano-child -name '*.php' -exec php -l {} \;`
   - `find wp-content/plugins -maxdepth 1 -type d -name 'gano-*' -print0 | xargs -0 -I{} find "{}" -name '*.php' -exec php -l {} \;`
2. Si tocaste CSS/JS del tema:
   - verifica archivos afectados;
   - si tienes entorno WP accesible, prueba la URL relevante en navegador;
   - si no lo tienes, deja claro que solo hubo validación de repo.
3. Si la tarea depende de contenido Elementor o menús:
   - asume **wp-admin humano**;
   - actualiza docs/runbooks, no inventes pasos automáticos.

### Trucos reales del repo
- Activación inicial documentada:
  1. `gano-phase3-content`
  2. `gano-content-importer`
- Nunca borres `gano-phase*` sin confirmación explícita.
- Para comparar repo vs servidor usa el workflow **05 · Ops · Verificar parches en servidor**.
- Para `wp-file-manager`, usa el workflow **12 · Ops · Eliminar wp-file-manager (SSH)**.

## Área 2 — `.gsd/` (workflow get-shit-done)

### Arranque
1. `cd .gsd`
2. `npm install`

### Workflow de prueba
- Suite principal:
  - `npm test`
- Cobertura cuando toques runners/hooks/lógica base:
  - `npm run test:coverage`

### Cuándo usarla
- Cambios en prompts, hooks, `get-shit-done`, agents o scripts internos de `.gsd/`.
- No necesita login humano.

## Área 3 — `.gsd/sdk/`

### Arranque
1. `cd .gsd/sdk`
2. `npm install`
3. `npm run build`

### Workflow de prueba recomendado
1. Cambio pequeño en librería/CLI:
   - `npm run test:unit`
2. Cambio que toca integración/flujo completo del SDK:
   - `npm run test:integration`
3. Cambio amplio:
   - `npm test`

### Mocks, flags y skips reales
- No hay sistema general de feature flags en el repo.
- El "switch" importante aquí es el entorno:
  - los E2E que dependen de `claude` se **saltan solos** si el CLI no está disponible;
  - si no hay autenticación de `claude`, cubre `unit` y cualquier integration que no dependa del CLI.

## Área 4 — scripts Python, colas y reportes

### Rutas clave
- `scripts/`
- `memory/claude/`
- `.github/agent-queue/`
- `.vscode/tasks.json`

### Workflow de prueba recomendado
- Validar cola Copilot:
  - `python scripts/validate_agent_queue.py`
- Consultar cola Claude sin mutar estado:
  - `python scripts/claude_dispatch.py list`
- Ver siguiente tarea dispatch:
  - `python scripts/claude_dispatch.py next`

### Reportes PDF
- Instala `fpdf2` solo si vas a generar PDFs.
- Después ejecuta el script puntual, por ejemplo:
  - `python scripts/generate_dev_audit_pdf.py`

### Trucos reales del repo
- `dispatch-state.json` es estado local; la fuente de verdad versionada es `memory/claude/dispatch-queue.json`.
- `.vscode/tasks.json` ya refleja varios comandos útiles; si dudas, replica esos comandos en terminal.

## Área 5 — Ops Hub estático

### Arranque
1. `python scripts/generate_gano_ops_progress.py`
2. `cd tools/gano-ops-hub/public`
3. `python -m http.server 8765`

### Workflow de prueba
- Abrir `http://127.0.0.1:8765/`
- Confirmar que `public/data/progress.json` carga.

### Trucos reales del repo
- No usar `file://` porque el frontend hace `fetch()` del JSON.
- Las métricas del GitHub Project en CI son opcionales y dependen de `ADD_TO_PROJECT_PAT`.

## Área 6 — Workflows GitHub / operación manual

### Knobs útiles que sí existen
- **08 · Agentes · Sembrar cola Copilot**
  - `queue_file`: selecciona la cola JSON
  - `scope`: `all`, `homepage`, `theme`, `content_seo`, `security`, `commerce`, `docs`, `coordination`, `infra`
- **05 · Ops · Verificar parches en servidor**
  - `upload_missing`: `true|false`
- **12 · Ops · Eliminar wp-file-manager (SSH)**
  - `force_remove`: `true|false`

### Cómo probar cambios relacionados
- Si editas `.github/agent-queue/*.json`, corre:
  - `python scripts/validate_agent_queue.py`
- Si solo documentas un workflow, valida que el comando, secret o input citado exista realmente en el YAML.
- Si el cambio toca deploy/SSH y no hay secrets en contexto, no simules el deploy; deja el runbook actualizado.

## Qué hacer cuando no puedes levantar algo

1. Distingue entre:
   - **bloqueo real de entorno**;
   - **acceso humano faltante**;
   - **algo que sí se puede validar desde repo**.
2. Ejecuta primero los checks que no dependen de credenciales.
3. Explica qué faltó exactamente: wp-admin, RCC, `claude`, secrets de Actions, DB local, etc.

## Mantenimiento de esta skill

Cuando descubras un truco nuevo o un runbook mejor:

1. Añádelo en la sección del área correspondiente, no en una lista genérica.
2. Incluye siempre:
   - prerequisito;
   - comando exacto;
   - señal esperada de éxito;
   - limitación si depende de login, secret o staging.
3. Si cambia el proceso global, sincroniza también `README.md`, `.github/DEV-COORDINATION.md` o `TASKS.md`.
4. Mantén esta skill corta, práctica y basada en flujos que el repo realmente soporta.
