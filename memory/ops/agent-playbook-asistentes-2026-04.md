# Playbook — agentes, Actions y asistentes en el repo Pilot

**Repo:** `Gano-digital/Pilot` · `main` · documentación maestra: [`TASKS.md`](../../TASKS.md)

Este documento resume **arranque**, **troubleshooting**, **cierre de tareas** y **re-lanzamiento** de agentes sin repetir el detalle de cada YAML (ver [`.github/workflows/README.md`](../../.github/workflows/README.md) y [`github-actions-audit-2026-04.md`](github-actions-audit-2026-04.md)).

---

## 1. Qué es “agente” aquí

| Capa | Qué hace | Dónde |
|------|----------|--------|
| **GitHub Copilot (coding agent)** | Código/PRs en respuesta a **issues** | Asignación manual en cada issue o bulk |
| **Actions 08 / 09 / 10** | Sembrar issues, issues homepage, merge histórico oleada 1 | `.github/workflows/` |
| **Colas JSON** | Definición versionada de tareas (7 archivos) | `.github/agent-queue/*.json` |
| **Claude / Cursor (local)** | `memory/claude/dispatch-queue.json` + `scripts/claude_dispatch.py` | Sin GitHub |
| **Gano Ops Hub** | Dashboard estático + `progress.json` (TASKS + dispatch); workflow **14** | GitHub Pages opcional · [`tools/gano-ops-hub/README.md`](../../tools/gano-ops-hub/README.md) |

**Regla:** Actions **no** ejecuta a Copilot; solo crea o organiza issues. Tú asignas Copilot en la UI.

---

## 2. Arranque (primera conexión o nuevo asistente)

1. **Clonar** y `git pull origin main`.
2. **Validar colas localmente:** `python scripts/validate_agent_queue.py` → debe imprimir `OK`.
3. **Etiquetas en GitHub:** si faltan etiquetas `area:*`, `copilot`, `infra`, etc., ejecutar **06 · Repo · Crear etiquetas** (o push a `.github/label-bootstrap` según `DEV-COORDINATION`).
4. **Secrets** (solo para deploy/SSH): `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH` — ver [`.github/DEV-COORDINATION.md`](../../.github/DEV-COORDINATION.md).
5. **Sembrar trabajo nuevo (opcional):**
   - **09** — 7 issues `homepage-fixplan` **solo si** no existen issues abiertos con esa etiqueta.
   - **08** — elegir `queue_file` + `scope` (ver [`.github/COPILOT-AGENT-QUEUE.md`](../../.github/COPILOT-AGENT-QUEUE.md)).
6. **Copilot:** pegar bloque correspondiente desde [`.github/prompts/copilot-bulk-assign.md`](../../.github/prompts/copilot-bulk-assign.md) al asignar.

---

## 3. Ciclo diario (consolidar y avanzar)

1. `git pull` · revisar **Actions** en GitHub para la última ejecución en `main` · **Ops Hub** (métricas y botones a workflows): [`tools/gano-ops-hub/public/index.html`](../../tools/gano-ops-hub/public/index.html) localmente o URL de Pages tras **14** (ver [`memory/ops/gano-ops-hub-deployment.md`](gano-ops-hub-deployment.md)).
2. **PRs:** fusionar con CI verde; usar [`.github/AGENT-REVIEW-CHECKLIST.md`](../../.github/AGENT-REVIEW-CHECKLIST.md). Orden de muchos PRs: [`.github/MERGE-PLAYBOOK.md`](../../.github/MERGE-PLAYBOOK.md).
3. **Issues:** cerrar los cubiertos por `main` — plantillas en [`../claude/gh-issue-close-guide.md`](../claude/gh-issue-close-guide.md).
4. **No re-sembrar 08** sin revisar duplicados: el workflow ignora tareas cuyo `<!-- agent-task-id:... -->` ya está en un issue **abierto** (deduplicación por lista de issues, no Search API).

---

## 4. Troubleshooting rápido

| Síntoma | Qué revisar |
|--------|------------|
| **07** falla en CI | `python scripts/validate_agent_queue.py` local; cada `body` debe contener el marcador exacto `<!-- agent-task-id:ID -->`. |
| **03** labeler falla | Checkout antes del labeler; sin `changed-files-labels-limit` inválido en YAML — ver auditoría §1–2. |
| **08** no crea issues | Scope demasiado estrecho (ej. `infra` en archivo sin tareas `infra`); o todos los ids ya existen en issues abiertos. |
| **04** deploy falla | Secrets faltantes o rutas incorrectas en `deploy.yml`. |
| **Copilot** no abre PR | Permisos en org/repo; issue mal formado; revisar comentarios del agente en el issue. |
| PHP lint en PR | **01** / **11** — corregir sintaxis en archivos tocados. |

---

## 5. Re-lanzar “agentes” (colas)

- **Oleada nueva o tareas sin issue:** **08** con el `queue_file` correcto y `scope: all` o filtrado (`theme`, `docs`, `infra`, …).
- **API research / guardián:** `tasks-api-integrations-research.json` · `tasks-security-guardian.json` — mismos prompts en `copilot-bulk-assign.md`.
- **10 · Orquestar oleadas:** solo si necesitas la lógica histórica (merge lista PR `#34`…`#48` + seed oleada 2). **No** es obligatorio tras la consolidación 2026-04-03.

---

## 6. Offloading — lo que **no** se resuelve solo en git

| Tema | Responsable | Referencia |
|------|-------------|------------|
| Deploy real a producción | Humano + secrets / SFTP | `TASKS.md` § Active |
| wp-admin, Elementor, RCC, GoDaddy DNS | Humano | `TASKS.md`, `memory/ops/*` |
| Cerrar issues masivos con criterio | Humano | `gh-issue-close-guide.md` |
| Credenciales Wompi/RCC, pfids reales | Humano (nunca en issues públicos) | `memory/commerce/rcc-pfid-checklist.md` |
| FreeScout / billing futuro | Decisión producto | `TASKS.md` Fase 4 / 5 |

---

## 7. Cola Claude local (sin GitHub)

```bash
python scripts/claude_dispatch.py list
python scripts/claude_dispatch.py next
python scripts/validate_claude_dispatch.py
```

Definición de tareas: [`../claude/dispatch-queue.json`](../claude/dispatch-queue.json) · [`../claude/dispatch-prompt.md`](../claude/dispatch-prompt.md).

---

*Última revisión: abril 2026 — alineado con workflows 07 (validación), 08 (deduplicación por issues abiertos), **14** (Ops Hub / Pages), auditoría Actions.*
