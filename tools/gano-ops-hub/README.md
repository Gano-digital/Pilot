# Gano Ops Hub

Dashboard estático para **estadísticas** (desde `TASKS.md` + cola Claude), **enlaces** a Actions y **seguimiento** del trabajo de agentes. No sustituye a WordPress ni a AEGIS: es una capa ligera **en el repo**.

## Contenido

| Ruta | Rol |
|------|-----|
| `public/index.html` | UI: métricas, secciones, botones a workflows GitHub |
| `public/data/progress.json` | Generado por `scripts/generate_gano_ops_progress.py` |
| `../../scripts/generate_gano_ops_progress.py` | Fuente de verdad: parsea `TASKS.md`, `dispatch-queue.json` y (en CI con PAT) **GitHub Project** vía GraphQL |
| `../../.github/gano-project-hub.json` | URLs del tablero org, número de proyecto y convenciones de trazabilidad ↔ playbook Projects |

## Local

```bash
python scripts/generate_gano_ops_progress.py
cd tools/gano-ops-hub/public
python -m http.server 8765
```

Abre http://127.0.0.1:8765/ — `file://` no sirve para `fetch()` del JSON.

## CI / GitHub Pages

Workflow **14 · Ops · Gano Ops Hub** (`.github/workflows/gano-ops-hub.yml`):

1. Regenera `progress.json` en el runner.
2. Si el repo tiene el secret **`ADD_TO_PROJECT_PAT`** (el mismo que el workflow **13**), cuenta ítems del Project **@Gano.digital** por campo **Status** y añade vista previa de issues *In progress*.
3. Sube `public/` como artefacto de Pages y despliega.

Ajusta las URLs `urls.*` en `.github/gano-project-hub.json` si tus vistas no coinciden con `/views/1` y `/views/2` (copia la URL desde el navegador).

**Activar Pages:** Repo → *Settings* → *Pages* → *Build and deployment* → **Source: GitHub Actions**.

La URL será del tipo `https://gano-digital.github.io/Pilot/` (o el dominio que configure GitHub).

## Sincronización con agentes

- Cada push a `main` que toque `TASKS.md` o `memory/claude/dispatch-queue.json` dispara el workflow.
- Cron diario 06:00 UTC.
- Tras `python scripts/claude_dispatch.py complete <id>`, haz commit de `dispatch-state.json` **solo si** quieres reflejar progreso local en el JSON del repo; en CI ese archivo suele no existir (pendientes = cola completa).

## Subdominio (ops.gano.digital)

Ver [`memory/ops/gano-ops-hub-deployment.md`](../../memory/ops/gano-ops-hub-deployment.md).

## Constelación 3D

El mapa WebGL sigue en `memory/constellation/CONSTELACION-COSMICA.html`. El Ops Hub enlaza al árbol en GitHub; para experiencia local abre el HTML con servidor estático.
