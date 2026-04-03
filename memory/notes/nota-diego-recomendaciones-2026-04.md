# Nota personal — Diego (no olvidar)

_Documento de recordatorio operativo. Última revisión: abril 2026._

## Enlaces rápidos

| Qué | Dónde |
|-----|--------|
| Checklist estricta antes de merge PR | [`.github/AGENT-REVIEW-CHECKLIST.md`](../../.github/AGENT-REVIEW-CHECKLIST.md) |
| DoD para agentes / revisores | [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md) (sección *Definition of Done*) |
| Orden y criterio de merge | [`.github/MERGE-PLAYBOOK.md`](../../.github/MERGE-PLAYBOOK.md) |
| Coordinación GitHub ↔ servidor | [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md) |
| Tareas globales | [`TASKS.md`](../../TASKS.md) |

## 1. Secrets de GitHub (bloqueante para CI)

En **Settings → Secrets and variables → Actions** deben existir:

- `SSH` — clave privada (para `webfactory/ssh-agent`)
- `SERVER_HOST` — host o IP del servidor
- `SERVER_USER` — usuario SSH/SFTP
- `DEPLOY_PATH` — ruta absoluta a la raíz WP (coherente al unir con `/wp-content/...`; evita doble barra)

Sin estos cuatro, **no funcionan** `deploy.yml` ni **Verificar parches Fase 1-3** (`verify-patches.yml`).

## 2. Oleadas Copilot (orden seguro)

1. **Orchestrate Copilot waves** — primero `dry_run_merge: true`, revisar el log, luego ejecución real con `merge_wave1` + `seed_wave2` si aplica.
2. **Seed Copilot task queue** — colas en [`.github/agent-queue/`](../../.github/agent-queue/) (`tasks.json`, `tasks-wave2.json`, `tasks-wave3.json`). El workflow usa el prefijo `.github/agent-queue/`.
3. **Seed homepage Fix issues** — una vez, si faltan los 7 issues del fixplan.
4. **Prompt correcto por número de issue:** oleada 1 (#17–#33) vs oleada 3 (#54–#68) según `copilot-bulk-assign.md`.

## 3. PRs y retrabajo (cómo no dar vueltas)

- Marcar **Ready for review** solo cuando **Checks** objetivo estén verdes (PHP lint Gano, TruffleHog en rutas tocadas).
- **Update branch** desde `main` en PRs abiertas antes de merge (o dejar que el bot actualice; si falla, conflicto real).
- **#47** ya cerrada: el CI deploy/verify quedó en `main`; no reabrir merge duplicado.
- **#49 vs #51:** si README ya está alineado en `main` vía #51, **cerrar #49** o fusionar solo el valor residual (p. ej. `.gano-skills/`) en un PR mínimo. **Update branch** desde GitHub puede fallar con **conflicto** en #49: resolver en local o cerrar la PR si el contenido ya está cubierto.
- Issues solo **servidor/Elementor:** no exigir código inventado en repo; cerrar con comentario + checklist manual.

## 4. Servidor y parches

- Tras configurar secrets: ejecutar **Verificar parches** (MD5) o dejar que un push a rutas cubiertas dispare **Deploy**.
- `wp-config.php` **no** va en git; subir solo por canal seguro.
- Quitar **wp-file-manager** en producción (TASKS — crítico).

## 5. Modelo comercial

- Checkout canónico: **GoDaddy Reseller**; Wompi como legacy/archivo según `TASKS.md` y `memory/archive/README-ARCHIVO-WOMPI-Y-PASARELAS-LEGACY.md`.

## 6. Validación local (repo)

- `python scripts/validate_agent_queue.py` — debe imprimir `OK` (colas JSON en `.github/agent-queue/`).

---

_Si movés algo de esta lista, actualizá una línea en `TASKS.md` o en el issue correspondiente para que los agentes no contradigan el estado real._
