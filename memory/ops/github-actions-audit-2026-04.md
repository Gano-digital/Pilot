# Auditoría GitHub Actions — Gano Digital (Pilot)

**Fecha:** 2026-04-03. **Objetivo:** inventario de workflows, fallos observados, riesgos y acciones aplicadas o recomendadas.

---

## Inventario (`.github/workflows/`)

| ID | Archivo | Disparadores | Rol |
|----|---------|--------------|-----|
| 01 | `php-lint-gano.yml` | push/PR `main`/`master` (paths PHP Gano) | `php -l` en MU, child, `gano-*` |
| 02 | `secret-scan.yml` | push/PR + manual | TruffleHog en Docker, rutas Gano |
| 03 | `labeler.yml` | PR opened/sync/reopened | Etiquetas por ruta (`actions/labeler@v6`) |
| 04 | `deploy.yml` | push paths tema/plugins/MU + manual | rsync SSH + verificación HTTP |
| 05 | `verify-patches.yml` | manual | Checksums repo vs servidor |
| 06 | `setup-repo-labels.yml` | manual + push `label-bootstrap` | Crear etiquetas en el repo |
| 07 | `validate-agent-queue.yml` | push/PR `agent-queue` + scripts | `validate_agent_queue.py` |
| 08 | `seed-copilot-queue.yml` | manual | Issues desde JSON |
| 09 | `seed-homepage-issues.yml` | manual | 7 issues homepage `homepage-fixplan` |
| 10 | `orchestrate-copilot-waves.yml` | manual | Merge oleada 1 + seed oleada 2 (lista PRs en script) |
| 11 | `copilot-setup-steps.yml` | manual + push/PR solo si cambia este YAML | Verificación entorno + PHP lint |
| 12 | `verify-remove-wp-file-manager.yml` | manual | SSH: comprobar/eliminar plugin riesgo |

**Fuera de esta carpeta (GitHub / org):** *CodeQL*, *Dependabot*, *Copilot coding agent*, *Vercel* (el fallo de Vercel en PRs típico de plan/org privada no es un bug del YAML del repo).

---

## Fallos corregidos en esta auditoría

### 1. `03 · PR · Etiquetas automáticas` — fallo real

**Síntoma:** `Error: found unexpected type for label 'changed-files-labels-limit' (should be array of config options)`.

**Causa:** Con `actions/labeler@v6`, la clave global `changed-files-labels-limit` en `.github/labeler.yml` fue interpretada como **nombre de etiqueta** en el parser del run analizado (valor escalar `8` ≠ array de reglas).

**Acción:** Eliminar `changed-files-labels-limit` del YAML (las reglas son pocas; el límite no era necesario). Añadir **`actions/checkout@v6`** antes del labeler para que la configuración del **PR** exista en el runner (evita depender solo del fetch de la API en escenarios de cambio de configuración).

### 2. `11 · Agentes · Setup pasos Copilot` — riesgo de falso fallo

**Síntoma potencial:** `grep -v "No syntax errors"` devuelve **código de salida 1** si todos los archivos pasan `php -l` (sin líneas que mostrar), y el job falla sin error de sintaxis.

**Acción:** Sustituir por el mismo patrón que `01 · php-lint-gano.yml` (`find` + `php -l` sin `grep`). Alinear **PHP 8.2** con el workflow 01. Unificar `actions/checkout@v6`.

### 3. `12 · Ops · wp-file-manager` — consistencia

**Acción:** `webfactory/ssh-agent` actualizado a **v0.10.0** (misma versión que `04` y `05`).

---

## Riesgos / deuda técnica (sin cambio obligatorio ahora)

| Tema | Riesgo | Recomendación |
|------|--------|----------------|
| **TruffleHog `latest`** | Imagen Docker no fijada por digest | Fijar tag/digest en `secret-scan.yml` para builds reproducibles. |
| **Oleada 1 en workflow 10** | Lista `wave1` de PRs es **histórica**; PRs ya fusionados se omiten sin error | Documentado en comentarios del YAML; vaciar o reemplazar si se reutiliza el workflow para otra oleada. |
| **Deploy 04 / 05 / 12** | Dependen de `secrets.SSH`, `SERVER_HOST`, etc. | Fallo esperado si faltan secretos; no es defecto del flujo. |
| **Labeler y forks** | PRs desde fork pueden tener permisos limitados para escribir etiquetas | Si hace falta etiquetar forks, evaluar `pull_request_target` con **precaución** (seguridad); hoy el uso principal es PRs de la misma org. |
| **Dos flujos PHP lint** | `01` y `11` solapan si ambos corren | `11` solo corre al cambiar `.github/workflows/copilot-setup-steps.yml`; `01` es el gate principal en `php` paths. |

---

## Qué no requiere “arreglo” en el repo

- **CodeQL** (si está habilitado en org/repo): puede tardar o marcar alertas; no duplicar reglas en YAML local salvo que se añada workflow propio.
- **Vercel** en checks de PR: configuración de producto/plan; no se corrige desde este repositorio.

---

## Referencias

- [`../.github/workflows/README.md`](../.github/workflows/README.md) — prefijos 01–12.
- [`../.github/MERGE-PLAYBOOK.md`](../.github/MERGE-PLAYBOOK.md) — orden de merges.
- `actions/labeler` README: [github.com/actions/labeler](https://github.com/actions/labeler)

_Verificar tras push: Actions → ejecutar un PR de prueba o revisar el siguiente PR para confirmar **03** en verde._
