# Referencia técnica y FAQ — Claude

Tablas y respuestas cortas para **no redescubrir** convenciones del repo Pilot.

---

## 1. Workflows GitHub Actions (prefijos 01–12)

| # | Nombre en UI (patrón) | Archivo YAML | Rol resumido |
|---|------------------------|--------------|--------------|
| 01 | CI · Sintaxis PHP (Gano) | `php-lint-gano.yml` | Lint PHP en rutas Gano. |
| 02 | CI · Escaneo secretos | `secret-scan.yml` | TruffleHog acotado. |
| 03 | PR · Etiquetas automáticas | `labeler.yml` | Requiere etiquetas existentes (**06**). |
| 04 | Deploy · Producción | `deploy.yml` | rsync SSH al push en rutas cubiertas. |
| 05 | Ops · Verificar parches | `verify-patches.yml` | MD5 repo vs servidor; manual. |
| 06 | Repo · Crear etiquetas | `setup-repo-labels.yml` | Manual o vía `label-bootstrap`. |
| 07 | Agentes · Validar cola JSON | `validate-agent-queue.yml` | CI al editar `.github/agent-queue/`. |
| 08 | Agentes · Sembrar cola Copilot | `seed-copilot-queue.yml` | Crea issues desde JSON; input `queue_file` + scope. |
| 09 | Agentes · Sembrar issues homepage | `seed-homepage-issues.yml` | 7 issues fixplan; manual. |
| 10 | Agentes · Orquestar oleadas | `orchestrate-copilot-waves.yml` | Merge ordenado + seed oleada 2; **solo si** hay lote nuevo. |
| 11 | Agentes · Setup pasos Copilot | `copilot-setup-steps.yml` | Configuración documentada en repo. |
| 12 | Ops · Eliminar wp-file-manager (SSH) | `verify-remove-wp-file-manager.yml` | Verificación y eliminación remota; mismos secrets que **04**. |

Guía con más detalle: [`.github/workflows/README.md`](../../.github/workflows/README.md).

---

## 2. Rutas canónicas

| Tema | Ruta |
|------|------|
| Cola oleada 1 | `.github/agent-queue/tasks.json` |
| Cola oleada 2 | `.github/agent-queue/tasks-wave2.json` |
| Cola oleada 3 | `.github/agent-queue/tasks-wave3.json` |
| Prompt bulk Copilot | `.github/prompts/copilot-bulk-assign.md` |
| Checklist revisión PR agente | `.github/AGENT-REVIEW-CHECKLIST.md` |
| Merge de muchos PRs | `.github/MERGE-PLAYBOOK.md` |
| Coordinación sync | `.github/DEV-COORDINATION.md` |
| Skill orquestación | `.gano-skills/gano-github-copilot-orchestration/SKILL.md` |
| Validador local cola | `scripts/validate_agent_queue.py` |

---

## 3. Secrets de Actions (deploy + 05 + 12)

- `SSH` — clave privada  
- `SERVER_HOST` — host para `ssh-keyscan` / conexión  
- `SERVER_USER` — usuario SSH  
- `DEPLOY_PATH` — ruta absoluta al document root WP (consistente, sin barra final ambigua)

Documentado en [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md).

---

## 4. FAQ

### ¿Hay que ejecutar el workflow 10 ahora?

**No**, para la oleada de PRs que ya se fusionó en `main` el **2026-04-03**. Usar **10** solo si vuelve a haber un conjunto de PRs “oleada 1” y quieres automatizar merges + semilla oleada 2.

### ¿Hay que ejecutar 08 de nuevo?

Solo si necesitas **nuevos** issues desde los JSON y has comprobado que no duplican `agent-task-id` existentes.

### ¿Dónde está el recordatorio corto para Diego?

[`../notes/nota-diego-recomendaciones-2026-04.md`](../notes/nota-diego-recomendaciones-2026-04.md).

### ¿Qué pasó con los PRs en abril 2026?

Consolidación masiva con squash; detalle y conflictos #52/#76 en [`../sessions/2026-04-03-consolidacion-prs-copilot.md`](../sessions/2026-04-03-consolidacion-prs-copilot.md) y narrativa en [`01-historial-y-contexto-cursor-2026-04.md`](01-historial-y-contexto-cursor-2026-04.md).

### ¿Puedo borrar los plugins `gano-phase*`?

**No** sin confirmación explícita de Diego y verificación de que su instalación ya corrió en WP — ver [`../notes/plugins-de-fase.md`](../notes/plugins-de-fase.md).

### ¿El checkout es Wompi o Reseller?

**Estrategia actual (TASKS / CLAUDE):** comercio y checkout vía **GoDaddy Reseller**; código o plugins Wompi pueden ser **legado** en algunos entornos — no asumir que son el eje sin mirar el sitio desplegado.

### ¿Qué carpeta leo primero si soy Claude sin contexto?

[`README.md`](README.md) de `memory/claude/`, luego [`01-historial-y-contexto-cursor-2026-04.md`](01-historial-y-contexto-cursor-2026-04.md).

### ¿Qué es la “cola dispatch” para Claude?

Archivo [`dispatch-queue.json`](dispatch-queue.json) + scripts `claude_dispatch.py` / `validate_claude_dispatch.py`. Sirve para **ordenar trabajo que Claude puede hacer en el repo** (docs, auditorías, validación). No sustituye GitHub Actions ni Copilot. Detalle: [`dispatch-prompt.md`](dispatch-prompt.md).

### ¿Puedo programar tareas en la app de Claude desde este repo?

**No de forma automática:** aquí no hay API ni token hacia Anthropic. Sí puedes **copiar** la salida de `python scripts/claude_dispatch.py show <id>` en tareas programadas o instrucciones de proyecto en claude.ai, manteniendo el JSON en git como fuente de verdad.

---

_Ultima revisión: **2026-04-03**._
