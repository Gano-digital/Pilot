# Sesión 2026-04-06 — GitHub Actions + estrategia de oleadas de agentes

## Inventario de workflows (pestaña Actions)

| # | Nombre | Rol | Notas |
|---|--------|-----|--------|
| 01 | CI · Sintaxis PHP (Gano) | Calidad | OK en pushes/PRs |
| 02 | CI · Escaneo secretos (TruffleHog) | Seguridad | OK |
| 03 | PR · Etiquetas automáticas | DX | OK |
| 04 | Deploy · Producción (rsync) | CD | **Antes:** fallaba en *Security Scan* por `grep` global que encontraba `password = ...` en WooCommerce/Essential Addons (falsos positivos). **2026-04-06:** escaneo limitado a `gano-child`, `mu-plugins`, `plugins/gano-*`, `scripts`, `.github`. Requiere secrets `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH`. |
| 05 | Ops · Verificar parches en servidor | Comparación MD5 | Manual (`workflow_dispatch`); alinea con issue #29 |
| 06 | Repo · Crear etiquetas | Setup | Una vez / cuando falten labels |
| 07 | Agentes · Validar cola JSON | CI colas | Corre si cambian `agent-queue/*.json` |
| 08 | Agentes · Sembrar cola Copilot | Crear issues | Idempotente por `<!-- agent-task-id -->` |
| 09 | Agentes · Sembrar issues homepage | Homepage | Manual |
| 10 | Agentes · Orquestar oleadas | Merge wave1 + seed wave2 | Lista de PRs **histórica** (#34–#50); la mayoría ya fusionada. Usar con cuidado o actualizar lista en YAML |
| 11 | Agentes · Setup pasos Copilot | Onboarding | Documentación |
| 12 | Ops · Eliminar wp-file-manager | SSH | Mismos secrets que deploy |
| — | CodeQL | Análisis | Dinámico GitHub |
| — | Dependabot | Deps | Vite corregido en `.gsd` (commit previo) |
| — | Copilot coding agent | — | Dinámico |

## Qué sobraba / estaba roto

- **Deploy (04):** el paso de “credenciales” era demasiado amplio; no era falta de SSH, era **falso positivo en vendor**. Corregido escaneando solo código Gano.
- **Orquestar oleadas (10):** la tabla `wave1` puede estar **obsoleta**; no es duplicado de 08, pero requiere mantenimiento si se usa de nuevo.

## Qué falta (operativo)

1. **Secret `SSH` en GitHub:** tras corregir el escaneo, el job **Deploy** avanza pero **ssh-agent falla** con `Error loading key "(stdin)": error in libcrypto` — suele ser clave mal pegada (faltan líneas `BEGIN/END`), passphrase en clave no soportada por el action, o formato distinto a PEM/OpenSSH. Regenerar clave de deploy **solo para CI** o volver a pegar el PEM completo en el secret `SSH`.
2. **Otros secrets:** confirmar `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH` coherentes con el servidor.
3. **Issue #29:** checklist servidor; ejecutar **05** con `upload_missing` según resultado, o SFTP manual.
4. **Colas Copilot:** con **un solo issue abierto** (#29), el backlog de agentes en GitHub está **limpio**. Las siguientes oleadas deben **sembrar** tareas nuevas (08) o ejecutar trabajo en **Cursor/Claude** (`memory/claude/dispatch-queue.json`).

## Estrategia de oleadas siguientes (orden lógico)

### Oleada A — Estabilizar entrega (humano + CI)
- Verificar que **04** pasa security-check y luego deploy (o falla solo por SSH real).
- Cerrar o actualizar **#29** tras `05` en verde o parches subidos.

### Oleada B — Repo / documentación (Claude dispatch, prioridad alta en `dispatch-queue.json`)
- `cd-repo-001` … `cd-repo-002` (TASKS + RCC/PFID guía): alinea documentación con automatización real.
- `cd-repo-006`: auditar `tasks.json` vs `main` → `memory/claude/obsolete-copilot-tasks.md`.

### Oleada C — GitHub Copilot (08), scopes sugeridos
| Orden | `queue_file` | `scope` | Objetivo |
|-------|----------------|---------|----------|
| 1 | `tasks-security-guardian.json` | `security` | Higiene secretos / `.gitignore` (AG-07 del handoff) |
| 2 | `tasks-api-integrations-research.json` | `all` | RCC + APIs (AG-08) |
| 3 | `tasks-wave3.json` | `theme` o `content_seo` | Solo si las tareas siguen vigentes tras B |
| 4 | `tasks-wave4-ia-content.json` | `content_seo` | Contenido/IA cuando RCC y trust estén publicados |

No sembrar 08 en masa hasta revisar **obsolete-copilot-tasks** para no reabrir trabajo ya en `main`.

### Oleada D — Producto WordPress (manual o agentes locales)
- Formulario contacto + redirect (`gano_contacto_submit`, AG-01/04).
- OG dimensions con Rank Math activo (AG-05).
- Páginas wp-admin con templates trust (handoff comercial).

## Cómo disparar agentes (recordatorio)

- **Copilot issues:** Actions → **08 · Sembrar cola Copilot** → `queue_file` + `scope`.
- **Claude/Cursor:** `python scripts/claude_dispatch.py list` / `next` / `complete`.

## Referencias cruzadas

- `TASKS.md` — checkboxes GitHub
- `.github/COPILOT-AGENT-QUEUE.md` — guía de colas
- `memory/claude/dispatch-queue.json` — tareas Claude (18 completadas wave contenidos; nuevas tareas `cd-repo-*`)
