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

**Animaciones 3D / Galactic Map:** no están en esta carpeta; viven en `memory/constellation/CONSTELACION-COSMICA.html`. Servidor aparte, p. ej. puerto **8766** desde `memory/constellation/`.

## URL pública (GitHub Pages)

**Objetivo:** [https://gano-digital.github.io/Pilot/](https://gano-digital.github.io/Pilot/)

Publicación actual en el remoto: rama **`gh-pages`** (contenido = `public/` del Hub). Actualizar con:

```powershell
pwsh -File scripts/publish-gano-ops-gh-pages.ps1
```

**Importante:** el repo **Pilot** es **privado**. En el plan gratuito de GitHub, **Pages en `github.io` no es visible para el público** (404 anónimo). Para un enlace real “para todos”: hacer el repo **público**, usar **Team/Enterprise**, o desplegar la misma rama `gh-pages` en **Netlify / Cloudflare Pages** (repos privados OK). Detalle: [`memory/ops/gano-ops-hub-deployment.md`](../../memory/ops/gano-ops-hub-deployment.md).

## CI / GitHub Actions (workflow **14**)

Cuando `.github/workflows/gano-ops-hub.yml` esté en **`main`**:

1. Regenera `progress.json` en el runner.
2. Con **`ADD_TO_PROJECT_PAT`**, métricas del Project **@Gano.digital** vía GraphQL.
3. Opcional: despliegue con **Source: GitHub Actions** en *Settings → Pages* (sustituye o complementa `gh-pages`).

Ajusta `urls.*` en `.github/gano-project-hub.json` si tus vistas del Project no coinciden con `/views/1` y `/views/2`.

## Sincronización con agentes

- Cada push a `main` que toque `TASKS.md` o `memory/claude/dispatch-queue.json` dispara el workflow.
- Cron diario 06:00 UTC.
- `dispatch-state.json` es estado local y está en `.gitignore`; no se versiona. En CI los pendientes se calculan desde la cola completa.

## Subdominio (ops.gano.digital)

Ver [`memory/ops/gano-ops-hub-deployment.md`](../../memory/ops/gano-ops-hub-deployment.md).

## Constelación 3D

El mapa WebGL está en `memory/constellation/CONSTELACION-COSMICA.html` (Galactic Map / HUD). **Referencia canónica en el historial:** commit `9c8fdaf6` (`feat(constellation): Dead Space HUD cosmic map…`). Para restaurar el archivo tras un `git restore` o copia incompleta:

```bash
git checkout 9c8fdaf6 -- memory/constellation/CONSTELACION-COSMICA.html
```

Cambios locales **nunca commiteados** no están en `git reflog`; ahí solo ayuda el historial del editor. El Ops Hub enlaza al árbol `memory/constellation` en GitHub; para probar el HTML en local usa un servidor estático (mismo criterio que `fetch()` del Hub).
