# Coordinación: GitHub ↔ servidor (GoDaddy) ↔ desarrollo local

Este documento es la **brújula operativa** del equipo: humanos, Copilot y Actions deben asumir que lo que aquí se describe se mantiene al día. **No** incluyas contraseñas, tokens ni IPs aquí; eso vive en el panel del proveedor, en secretos de CI o en variables de entorno locales.

---

## 1. Fuentes de verdad (léelas antes de un cambio grande)

| Prioridad | Ubicación | Contenido |
|-----------|-----------|-----------|
| 1 | [`TASKS.md`](../TASKS.md) | Estado por fases, pendientes críticos, checklist homepage/CI |
| 2 | [`CLAUDE.md`](../CLAUDE.md) | Contexto del proyecto, plugins de fase, reglas de no borrar instaladores |
| 3 | [`memory/content/`](../memory/content/) | Copy listo para Elementor (ej. homepage) |
| 4 | [`memory/research/`](../memory/research/) | Investigación (ej. Fase 4 plataforma) |
| 5 | [`memory/sessions/`](../memory/sessions/) | Reportes de sesión (Cursor, decisiones recientes) |
| 6 | [`.github/copilot-instructions.md`](copilot-instructions.md) | Instrucciones para Copilot en el repo |
| 7 | [`.github/AGENT-REVIEW-CHECKLIST.md`](AGENT-REVIEW-CHECKLIST.md) | Revisión estricta de PRs (humanos) antes de merge |

Si algo en **servidor** no está reflejado en `TASKS.md` o en un **issue** etiquetado, GitHub “no lo sabe”: conviene abrir un **reporte de sincronización** (Issues → New → *Reporte de sincronización*) el mismo día.

---

## 2. Qué vive dónde (evita expectativas falsas)

| Dónde | Qué hay |
|-------|---------|
| **Git (GitHub)** | Tema child, MU-plugins Gano, plugins `gano-*`, workflows, documentación, `ssh_cli.py` (sin credenciales en archivo). |
| **Servidor WordPress** | Base de datos (Elementor JSON en `postmeta`, menús, opciones), `wp-config.php`, `uploads/`, plugins instalados desde admin. |
| **Solo local** | Copia de BD opcional, `.env`, credenciales `GANO_SSH_*`, herramientas tipo `ssh_cli` ya configurado. |

**Regla:** un cambio **solo en Elementor** en producción no aparece en git hasta que alguien lo documente (issue `[sync]` + nota en `TASKS.md`) o exporte lo necesario (plantillas, copy en `memory/content/`).

---

## 3. Flujo integrado recomendado

```text
Local (rama feature/ops) → PR → CI (TruffleHog Gano + php-lint) → merge main
        ↓
  Despliegue al servidor (según tu pipeline: SFTP, panel, rsync)
        ↓
  Issue [sync] o actualización de TASKS.md si hubo drift servidor↔repo
```

- **Paralelo:** mientras tanto, issues con etiqueta `copilot` o `homepage-fixplan` pueden avanzar en GitHub sin tocar producción.
- **Tras rotación de credenciales o cambios de DNS/SSL:** un comentario breve en el issue de turno o línea en `TASKS.md` basta (sin pegar secretos).

---

## 4. GitHub Actions que dan contexto al proceso

En **Actions**, los workflows del repo se listan con un **prefijo numérico** (`01` … `12`) para ordenar la barra lateral y agrupar por fase. Leyenda: **CI** (calidad) → **PR** → **Deploy/Ops** → **Repo** (setup) → **Agentes** (Copilot). Detalle en [`.github/workflows/README.md`](workflows/README.md).

| # | Nombre en UI (sidebar) | Archivo | Rol |
|---|------------------------|---------|-----|
| 01 | CI · Sintaxis PHP (Gano) | `php-lint-gano.yml` | Sintaxis PHP en código propio. |
| 02 | CI · Escaneo secretos (TruffleHog) | `secret-scan.yml` | Secretos solo en rutas Gano (Docker TruffleHog). |
| 03 | PR · Etiquetas automáticas | `labeler.yml` | Etiquetas por rutas en PRs. |
| 04 | Deploy · Producción (rsync) | `deploy.yml` | Push a `main` en rutas `gano-child` / `gano-*` / `mu-plugins` → rsync por SSH. **Secrets:** `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH`. |
| 05 | Ops · Verificar parches en servidor | `verify-patches.yml` | **Manual** — compara MD5 repo vs servidor (Fase 1–3); opción `upload_missing`. Mismos secrets que `deploy.yml`. |
| 06 | Repo · Crear etiquetas GitHub | `setup-repo-labels.yml` | Crea etiquetas `area:*`, `coordination`, etc. Manual (**Run workflow**) o automático al hacer **push a `main`** que modifique [`label-bootstrap`](label-bootstrap). |
| 07 | Agentes · Validar cola JSON | `validate-agent-queue.yml` | Valida `.github/agent-queue/*.json` en CI. |
| 08 | Agentes · Sembrar cola Copilot | `seed-copilot-queue.yml` | **Cola masiva** para Copilot: lee `.github/agent-queue/tasks*.json`, crea issues por ámbito, deduplicado por `agent-task-id`. |
| 09 | Agentes · Sembrar issues homepage | `seed-homepage-issues.yml` | Crea issues del fixplan homepage (manual). |
| 10 | Agentes · Orquestar oleadas | `orchestrate-copilot-waves.yml` | Merge ordenado oleada 1 + seed oleada 2 (inputs). |
| 11 | Agentes · Setup pasos Copilot | `copilot-setup-steps.yml` | Pasos de configuración Copilot en el repo. |
| 12 | Ops · Eliminar wp-file-manager (SSH) | `verify-remove-wp-file-manager.yml` | **Manual** — verifica y opcionalmente elimina el plugin vía SSH/WP-CLI. Mismos secrets que `deploy.yml`. |

**Otros** (no viven en `.github/workflows/` del repo): *Copilot coding agent*, *Dependabot Updates* — nombres fijos por GitHub.

Ejecuta **06 · Repo · Crear etiquetas** si faltan etiquetas; sin ellas el labeler y las plantillas no se ven completas. Tras abril 2026 el set incluye **`infra`** (DNS/SSL/runbooks).

**DNS y dominio:** GitHub y Copilot **no** modifican registros en GoDaddy. Usa la cola `tasks-infra-dns-ssl.json` + plantilla de issue *DNS / HTTPS — gano.digital* y el script `scripts/check_dns_https_gano.py` para diagnóstico local.

**Secrets de deploy (Settings → Secrets and variables → Actions):** además de `SSH` (clave privada), define `SERVER_HOST` (hostname o IP para `ssh-keyscan`), `SERVER_USER` (usuario SSH/SFTP) y `DEPLOY_PATH` (ruta absoluta al `public_html` o raíz WP, **sin** barra final inconsistente). Sin estos cuatro, el job de deploy fallará al expandir rutas.

---

## 5. Desarrollo local (recordatorio)

- Clonar **solo desde GitHub** (`origin` → `Gano-digital/Pilot`). GitLab no forma parte del flujo; histórico en `memory/ops/archived-gitlab-remote-2026-04.md`.
- `ssh_cli.py`: variables `GANO_SSH_HOST`, `GANO_SSH_USER`, `GANO_SSH_PASS` o `GANO_SSH_KEY_PATH` (nunca en commit).
- Submódulo `.gsd/`: `git submodule update --init` si usas el workflow GSD.

---

## 6. Cómo “enterar” a GitHub de un cambio en servidor

Elige **una** vía (o combina):

1. **Issue** con plantilla **Reporte de sincronización (servidor / local)** — ideal para Elementor, menús, plugins activados solo en prod.
2. **PR** que actualice solo `TASKS.md` / `memory/content/` — ideal si el cambio es copy o checklist.
3. **Comentario en PR** reciente de deploy — si ya hay un PR abierto relacionado.

Etiqueta sugerida: `coordination` (créala con el workflow de labels si no existe).

---

## 7. Checklist rápido después de tocar producción

- [ ] ¿Está reflejado en `TASKS.md` o en un issue `[sync]`?
- [ ] ¿El repo sigue siendo la referencia para código (no solo el servidor)?
- [ ] ¿CI en `main` sigue verde tras el último merge?
- [ ] ¿Copilot / revisores tienen enlace a este archivo si la tarea es transversal?

---

_Mantén este archivo en commits pequeños cuando cambie el proceso; es preferible historia clara a un monolito desactualizado._
