# Active Context — Estado Actual

_Última actualización: 2026-04-10 — **`Gano-digital/Pilot` es repositorio PÚBLICO** (`gh repo view` → `visibility: PUBLIC`). **Riesgo P0:** sigue registrado runner self-hosted **`gano-godaddy-server`** (etiqueta `gano-production`, **online**) — con repo público, un workflow en PR/fork puede intentar ejecutar código en ese host; mitigar **ya** (desregistrar runner, runner aislado fuera de prod, o política estricta de Actions en forks). Ver §5–§6 en [`sota-investigacion-2026-04-09-ci-supply-chain-agents.md`](../../memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md). Plan vitrina y Fase 4 sin cambio de foco._

## Foco actual (producto y repo)

- **Vitrina gano.digital:** plan por fases y roles (Diego / Cursor / Copilot / Claude) — [`memory/ops/homepage-vitrina-launch-plan-2026-04.md`](../../memory/ops/homepage-vitrina-launch-plan-2026-04.md) (checklist orden de bloques § Fase 1). Copy fuente: [`homepage-copy-2026-04.md`](../../memory/content/homepage-copy-2026-04.md). Aplicación en **Elementor = humano en wp-admin**; agentes no sustituyen el pegado en panel.
- **Servidor / producción:** desplegar parches Fases 1–3 al hosting real, eliminar `wp-file-manager`, configurar Gano SEO / GSC / Rank Math (`TASKS.md` sección Active).
- **GitHub `Gano-digital/Pilot`:** **`main` público** — minutos Actions en runners GitHub-hosted más favorables; **no** commitear secretos; vigilar **self-hosted en prod** (ver línea de actualización arriba).
- **Fase 4:** catálogo Reseller, mapeo CTAs en `shop-premium.php`, smoke test checkout — [`memory/commerce/rcc-pfid-checklist.md`](../../memory/commerce/rcc-pfid-checklist.md).
- **Constellation / Battle Map:** plan de **diseño + fine tuning + fases + alineación agentes** — [`memory/constellation/BATTLE-MAP-PLAN-DISENO-FINE-TUNING-2026-04.md`](../../memory/constellation/BATTLE-MAP-PLAN-DISENO-FINE-TUNING-2026-04.md); config ejemplo JSON — [`battle-map-config.example.json`](../../memory/constellation/battle-map-config.example.json); `CONSTELACION-COSMICA.html` expone `window.__GANO_BATTLE_MAP__` (build/plan). Oleada GitHub `copilot/cx-*` sigue playbook; no duplicar PRs masivos.
- **Investigación SOTA UX:** [`memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md`](../../memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md). **Inventario recursos:** [`memory/constellation/INVENTARIO-RECURSOS-DESARROLLO-2026-04.md`](../../memory/constellation/INVENTARIO-RECURSOS-DESARROLLO-2026-04.md).

## Completado recientemente (entorno + gobernanza agentes)

- [x] **Setup Cursor:** rules **9** `.mdc` (001–006, 100–101, 200), memory protocol, PHP/WP, CSS/JS, git workflow, copilot oversight.
- [x] Memory bank (`.cursor/memory/`) operativo; `AGENTS.md` y flujo multi-agente documentados.
- [x] Skills proyecto en `.gano-skills/` (incl. orquestación Copilot + multi-agente local) alineadas con cola GitHub y prompts por oleada.
- [x] **Cola Claude dispatch:** tareas `cd-repo-001` a `cd-repo-012` completadas; checklists operativos creados en `memory/commerce/` y `memory/ops/`.
- [x] **PHP CLI local:** PHP 8.3 habilitado en Windows y `php -l wp-content/themes/gano-child/functions.php` pasa sin errores.
- [x] **Tooling opcional incorporado (sin romper runtime):**
  - Graphify seguro (skill `gano-graphify-local`) para mapas de arquitectura.
  - Agent Orchestrator (AO) opcional (skill `gano-agent-orchestrator-local`) para oleadas paralelas (recomendado WSL2).
  - ML‑SSD (Apple) como submodule `vendor/ml-ssd` + skill `gano-ml-ssd` (I+D / evaluación codegen).
- [x] **Skill cloud bootstrap:** nueva skill `.agents/skills/cloud-agent-starter/SKILL.md` con arranque rápido, matriz de accesos/login, límites del entorno cloud, comandos mínimos por área (`wp-content`, colas GitHub, dispatch Claude, `.gsd`, Ops Hub, comercio) y mantenimiento del runbook.
- [x] **PR #136** (docs/memoria Fase 4) dejado **merge-ready** (conflictos resueltos, CI verde). Nota: el merge puede quedar bloqueado por ruleset de “Code Quality” en GitHub.
- [x] **PR #159** — plan vitrina + prechequeo SSH en `deploy.yml` + runbook `publickey` — fusionado en `main`; PR #158 cerrado como sustituido.
- [x] **PR #160** — troubleshooting SSH (IP) + reporte handoff para Claude — fusionado en `main`.

## En progreso

- [ ] **CI Deploy (04):** la **huella** de la clave en CI coincide con `id_rsa_deploy` local (misma pareja que el secret). Si sigue `publickey`, revisar **`SERVER_USER` / `SERVER_HOST`** en GitHub y posible **bloqueo por IP** del hosting frente a runners de GitHub — [`memory/ops/github-actions-ssh-secret-troubleshooting.md`](../../memory/ops/github-actions-ssh-secret-troubleshooting.md) § *Huella local = huella en CI*.
- [x] **Workflow 14 (Ops Hub):** script `--output` relativo corregido en `main` (#157); run manual post-merge: éxito ([24147290218](https://github.com/Gano-digital/Pilot/actions/runs/24147290218)).
- [ ] SFTP / sync VS Code si aplica al flujo de deploy de Diego.
- [ ] Deploy de archivos críticos al servidor y tareas solo-wp-admin listadas en `TASKS.md`.

## GitHub — PRs y merges

- **Estado dinámico:** revisar con `gh pr list --repo Gano-digital/Pilot` o la pestaña Pull requests. Varios PRs históricos pueden estar ya fusionados en `main`; no usar tablas de auditoría antigua como fuente de verdad sin verificar.
- **Ruleset «Code quality» / CodeQL:** si un PR queda bloqueado por hallazgos de escaneo, el desbloqueo es en la UI de GitHub (Security / reglas del PR).

## Bloqueadores

- **Runner self-hosted + repo público (P0 seguridad):** mientras `gano-godaddy-server` esté **online** y vinculado a este repo **público**, la superficie de ataque incluye **ejecución arbitraria** vía Actions si un flujo malicioso llega a colarse (forks/PR). Acción recomendada: desregistrar runner en GitHub + parar servicio en host, **o** VM/runner dedicado sin acceso a WordPress prod + “Require approval for all outside collaborators” en Actions.
- **SSH autorización:** `origin` responde por HTTPS, pero `ssh` al servidor de producción puede devolver rechazo de clave; no asumir shell operativo hasta validar `authorized_keys` / deploy key (host/usuario en secretos, no en texto público).
- **Brecha Git ↔ producción:** código en `main` no reflejado en gano.digital hasta deploy + acciones en panel.
- **Ruleset GitHub (Code Quality):** algunos PRs pueden quedar “BLOCKED” aunque CI esté verde; requiere triage del hallazgo exacto en la UI de GitHub.

## Skills / prioridades diferidas

- **Tests y calidad de ingeniería (WP):** skill `.gano-skills/gano-wp-engineering-quality/SKILL.md`; prioridad añadida en `TASKS.md` (*Etapa posterior — Tests y calidad de ingeniería*). Activar cuando deploy F1–3 y Fase 4 estén en orden.

## Trabajo en paralelo (SOTA workflow)

- **Registro maestro:** [`memory/research/sota-workflow-ops-parallel-2026-04.md`](../../memory/research/sota-workflow-ops-parallel-2026-04.md) — carriles A (producto), B (repo/GitHub), C (ops madurez); P0–P2; checklist; regla: no interrumpir hilo principal (`TASKS.md` Active / Fase 4). Enlace también en `TASKS.md` § *Trabajo en paralelo*.

## Próximos pasos (orden sugerido)

1. **Fase 0 plan vitrina:** deploy F1–3 estable, `wp-file-manager` fuera, secret `SSH` alineado con servidor (ver plan § Fase 0).
2. **Fase 1:** Lorem y métricas falsas fuera en homepage (`homepage-copy-2026-04.md` + menú primary).
3. **Fase 2–3:** Nosotros / confianza; RCC + CTAs + checkout Reseller.
4. GitHub: cerrar issues obsoletos; sembrar **09 · homepage-fixplan** solo si hace falta granularidad.

## Decisiones / referencias

- **Handoff Claude (SSH, Deploy CI, tokens/API):** [`memory/claude/2026-04-08-reporte-handoff-ssh-deploy-tokens.md`](../../memory/claude/2026-04-08-reporte-handoff-ssh-deploy-tokens.md).
- Fuentes de verdad: `TASKS.md`, `CLAUDE.md`, `.github/DEV-COORDINATION.md`, `.github/copilot-instructions.md`, `memory/ops/homepage-vitrina-launch-plan-2026-04.md` (vitrina + agentes).
- Jerarquía si hay conflicto: `CLAUDE.md` > `copilot-instructions.md` > `AGENTS.md`. **Comercio y checkout:** solo **GoDaddy Reseller / lo que ya está en el hosting GoDaddy** — no priorizar Wompi ni pasarelas ajenas a ese ecosistema.
- Resumen ejecutivo para humanos: `memory/sessions/2026-04-02-progreso-consolidado.md`.
- Estado dispatch y documentación operativa reciente: `memory/sessions/2026-04-03-claude-dispatch.md`.
