# Checklist estricta — revisión de PRs (Copilot / agentes)

Usar **antes de aprobar o fusionar**. Objetivo: código mantenible, sin retrabajo, alineado a `TASKS.md` y al modelo **GoDaddy Reseller**.

## Por cada PR

| # | Criterio | Acción si falla |
|---|----------|-----------------|
| 1 | **Alcance:** el diff corresponde al issue (título + cuerpo). | Pedir división de PR o descartar cambios fuera de scope. |
| 2 | **Conflicto:** rama fusionable con `main` (`mergeable` / sin conflictos). | Update branch desde GitHub o rebase local del agente. |
| 3 | **CI:** workflows requeridos en verde (PHP lint Gano, TruffleHog en rutas tocadas). | Corregir sintaxis o falsos positivos **sin** silenciar secretos reales. |
| 4 | **Seguridad:** cero credenciales, API keys, PAT en texto; SQL con `$wpdb->prepare()`; salidas escapadas. | Request changes; rotar credencial si hubo filtración. |
| 5 | **WordPress:** prefijo `gano_` en funciones nuevas del child; no tocar `wp-config` en PR. | Rechazar o revertir líneas. |
| 6 | **Plugins de fase:** no eliminar `gano-phase*` sin confirmación explícita en issue. | Revertir. |
| 7 | **Comercio:** CTAs y checkout coherentes con Reseller; no reactivar Wompi como estrategia por defecto. | Alinear con `TASKS.md` / `memory/archive/README-ARCHIVO-WOMPI-Y-PASARELAS-LEGACY.md`. |
| 8 | **Documentación:** si solo es `memory/**/*.md`, revisar tono (es-CO), honestidad Reseller, sin datos legales inventados. | Edición menor o comentario al agente. |
| 9 | **Cierre:** al fusionar, incluir `Closes #NN` si el issue queda resuelto en repo. | Añadir en squash message. |

## Oleadas y colas (operación)

| Momento | Acción |
|---------|--------|
| Antes de orchestrate | `dry_run_merge: true` en **10 · Orquestar oleadas** (ver `.github/MERGE-PLAYBOOK.md`). |
| Issues nuevos oleada 2 | **08 · Sembrar cola Copilot** → `tasks-wave2.json` (deduplicado por `agent-task-id`). |
| Homepage fixplan | **Seed homepage Fix issues** si faltan los 7 issues. |
| Asignación Copilot | Prompt correcto: oleada 1 **#17–33** vs oleada 3 **#54–68** (`.github/prompts/copilot-bulk-assign.md`). |

## Deploy

| Secret | Uso |
|--------|-----|
| `SSH` | Clave privada para `webfactory/ssh-agent` |
| `SERVER_HOST` | Host para `ssh-keyscan` y `rsync` |
| `SERVER_USER` | Usuario remoto |
| `DEPLOY_PATH` | Ruta absoluta al document root WP (**sin** barra final duplicada al unir rutas) |

Sin los cuatro, `deploy.yml` no puede desplegar.
