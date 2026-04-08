# Progress Tracker

## 2026-04-08 — Post-merge #159 + estado Actions

### Completado

- [x] **#159** en `main` — plan vitrina, docs agentes, pasos *Huella* / *Probar SSH* en deploy.
- [x] **Workflow 14** (push #159): éxito en mismo commit que tocó `gano-ops-hub.yml` paths.
- [ ] **Workflow 04:** último run con #159 falla en *Probar SSH* ([24148915581](https://github.com/Gano-digital/Pilot/actions/runs/24148915581)) — siguiente paso operativo: alinear huella/pem en secret `SSH` con `authorized_keys` (ver [`github-actions-ssh-secret-troubleshooting.md`](../../memory/ops/github-actions-ssh-secret-troubleshooting.md)).

---

## 2026-04-08 — Plan vitrina + realineación agentes (documentación)

### Completado

- [x] [`memory/ops/homepage-vitrina-launch-plan-2026-04.md`](../../memory/ops/homepage-vitrina-launch-plan-2026-04.md) — fases 0–4, RACI, proceso repo → deploy → Elementor, enlaces canónicos.
- [x] `TASKS.md` — enlace al plan bajo “Siguiente foco”.
- [x] `AGENTS.md` — fuente de verdad #6 (plan vitrina).
- [x] `.github/copilot-instructions.md` — sección **Prioridad vitrina**.
- [x] `memory/content/digital-files-and-content-setup.md` — fila plan vitrina + fecha.
- [x] `.cursor/memory/activeContext.md` + `techContext.md` — alineación foco y decisión arquitectónica #8.

---

## 2026-04-08 — Merge #156/#157 + billing Actions + verificación workflows

### Completado

- [x] Fusionados en `main`: **#156** (docs SSH CI + `progress.json`) y **#157** (fix ruta relativa `generate_gano_ops_progress.py`).
- [x] Tras arreglo de **billing** en GitHub, **workflow 14** manual en verde ([run 24147290218](https://github.com/Gano-digital/Pilot/actions/runs/24147290218)).
- [ ] **Workflow 04** Deploy: sigue fallando en rsync con `publickey` (run [24147291642](https://github.com/Gano-digital/Pilot/actions/runs/24147291642)) — alinear secret `SSH` / usuario / host con el acceso que ya funciona en local.

---

## 2026-04-08 — Ops: enlaces SSH CI + artefacto Ops Hub

### Completado

- [x] Enlace explícito a [`memory/ops/github-actions-ssh-secret-troubleshooting.md`](../memory/ops/github-actions-ssh-secret-troubleshooting.md) desde `.github/DEV-COORDINATION.md` y comentario en `deploy.yml`.
- [x] Sección de actualización en `memory/ops/github-actions-audit-2026-04.md` (workflows 13–14 + troubleshooting).
- [x] Regenerado `tools/gano-ops-hub/public/data/progress.json` con `scripts/generate_gano_ops_progress.py` (alineado a `TASKS.md` actual).
- [x] `.cursor/memory/activeContext.md` — tabla de PRs obsoleta sustituida por instrucción dinámica; foco deploy CI + SSH.

---

## 2026-04-07 — Battle Map: plan diseño + fine tuning + agentes

### Completado (documentación + marca en HTML)

- [x] `memory/constellation/BATTLE-MAP-PLAN-DISENO-FINE-TUNING-2026-04.md` — fases 0–3, objetivos D1–D5, tabla agentes (Diego / Cursor / Copilot / Claude), checklist Go/No-Go.
- [x] `memory/constellation/battle-map-config.example.json` — plantilla Fase 2 (SFX + systems stub).
- [x] `CONSTELACION-COSMICA.html` — `window.__GANO_BATTLE_MAP__` + comentario en módulo principal.
- [x] Enlaces desde `INVENTARIO-RECURSOS-DESARROLLO-2026-04.md` y `activeContext.md`.

### Próximo (ejecución)

- [ ] Fase 0 checklist (abrir local, probar rutas).
- [ ] Fase 1: tuning CSS/SFX según plan (PR acotado).
- [ ] Fase 2 (opcional): `fetch` config + fallback.

---

## 2026-04-07 — Investigación SOTA workflow + paralelismo (registro)

### Completado (documentación)

- [x] `memory/research/sota-workflow-ops-parallel-2026-04.md` — SOTA (GitOps ligero, staging, gates merge, inventario plugins, runbooks), colisión con estado Gano, cambios esenciales P0–P2, modelo carriles A/B/C, checklist operativa.
- [x] Enlaces desde `TASKS.md` (*Trabajo en paralelo*) y `.cursor/memory/activeContext.md`.

### Pendiente (ir tachando en el doc de investigación o aquí)

- [ ] Ritual semanal 15 min PRs bloqueados por CodeQL/ruleset.
- [ ] Runbook incidente 1 página + drill restauración (Carril C).
- [ ] Tabla inventario plugins terceros v1.

---

## 2026-04-02 — Setup entorno y gobernanza (cerrado)

### Completado

- [x] VS Code instalado y configurado (extensiones según sesión)
- [x] Cursor: **9** rules `.mdc` (contexto, boundaries, security, error handling, copilot oversight, memory protocol, PHP/WP, CSS/JS, git workflow)
- [x] Cursor hooks configurados
- [x] Cursor memory bank inicializado (`projectBrief`, `techContext`, `activeContext`, `deferredItems`)
- [x] `AGENTS.md` (estándar cross-tool)
- [x] Workflows / repo: `copilot-setup-steps.yml`, `CODEOWNERS` (según sesión de setup)
- [x] Documentación Copilot repo: `copilot-instructions.md`, cola `.github/agent-queue/`, playbooks merge/coordinación

### Cierre explícito

- [x] **“Setup Cursor rules + memory protocol”** — considerado **completado**; el foco del equipo pasa a **entrega en producción + triage GitHub**.

---

## 2026-04-02 — Post-actualización (memory bank alineado)

### Estado operativo (referencia `TASKS.md`)

- Código **Fases 1–3** sólido en repo; pendiente **deploy** y tareas de **panel/servidor**.
- GitHub: cola de agentes **~32 issues** (#17–33, #54–68); **~35 PRs abiertos**, mayoría **draft** — siguiente esfuerzo: **revisión humana + CI + merge**.

### En progreso

- [ ] SSH/SFTP GoDaddy (según lo que permita el plan)
- [ ] Revisión y merge de PRs Copilot
- [ ] Deploy F1–3 + eliminación `wp-file-manager` + SEO/GSC/Rank Math
- [ ] Homepage Elementor + Fase 4 Reseller

### Backlog

- [ ] Migrar / alinear contenido staging si aplica
- [ ] MCPs adicionales (Figma, Dataplex, etc.) si se priorizan
- [ ] Rotación de tokens y limpieza de remotes con credenciales (cuando toque despliegue masivo)

---

## 2026-04-03 — Dispatch Claude + validación entorno (cerrado)

### Completado

- [x] Cola `memory/claude/dispatch-queue.json` completada (`cd-repo-001` a `cd-repo-012`).
- [x] Checklists operativos creados y/o actualizados para:
    - RCC -> PFID
    - Gano SEO + GSC + Rank Math
    - Remoción de `wp-file-manager`
    - Wordfence + 2FA
- [x] PHP 8.3 CLI habilitado en Windows local.
- [x] `php -l wp-content/themes/gano-child/functions.php` validado sin errores.
- [x] Acceso al repo confirmado por HTTPS (`origin`).

### Hallazgos

- [ ] SSH a GitHub aún no autentica con la clave local actual.
- [ ] SSH al servidor `72.167.102.145` aún no autentica con las claves locales actuales.

### Estado operativo

- Repo: operativo por HTTPS.
- Cursor memory bank: requiere usar `activeContext.md` actualizado 2026-04-03 como punto de arranque.
- Próximo cuello real: deploy/servidor + panel WordPress, no cola Claude.

---

## 2026-04-07 — Tooling agentes + Constellation + PR #136 (en curso)

### Completado

- [x] Integración segura de tooling opcional (sin runtime):
  - Graphify (skill `gano-graphify-local`) para mapas de arquitectura.
  - Agent Orchestrator (skill `gano-agent-orchestrator-local`) como orquestador opcional.
  - ML‑SSD incorporado como submodule `vendor/ml-ssd` + skill `gano-ml-ssd` (I+D/evaluación).
- [x] PR #136 dejado merge-ready: conflictos resueltos, checks verdes.

### En progreso / por validar

- [ ] “Code Quality” ruleset: identificar hallazgos exactos en GitHub cuando bloquea merges aun con CI verde.
- [ ] Oleada Constellation: resolver issues #138–#144 con prompt maestro y parches mínimos.

---

## Cómo usar este archivo

Tras hitos importantes, añadir una sección con fecha o actualizar la sección **Post-actualización** para que nuevas sesiones de Cursor lean el estado en `.cursor/memory/activeContext.md` y aquí.
