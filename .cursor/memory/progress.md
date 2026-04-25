# Progress Tracker

## 2026-04-24 — Fix funnel + Showcase futurista

### Completado

- [x] Fix REST whitelist: `/wp-json/gano/v1/lead` añadido a `rest_authentication_errors`.
- [x] Validar sintaxis PHP (`php -l` sin errores) — `functions.php`.
- [x] Commit local `16fc7dcd` (fix REST).
- [x] Showcase futurista: plan aprobado + ejecución de 12 efectos procedurales.
- [x] Crear `css/gano-showcase.css` — tokens SOTA, layout grid, CSS 3D, reduced-motion.
- [x] Crear `js/gano-showcase.js` — motor lazy-init, 8 efectos Canvas 2D/WebGL/SVG adaptados.
- [x] Crear `templates/page-showcase.php` — hero, grid 8 tarjetas, separador SVG, CTA fractal.
- [x] Enqueue condicional en `functions.php` para template showcase.
- [x] Validar sintaxis PHP (`php -l` sin errores) — `page-showcase.php`.
- [x] Commit local `1dde1c54` (feat showcase).

### Pendiente

- [ ] Deploy a producción (SCP o webhook 04): commits `16fc7dcd` + `1dde1c54`.
- [ ] Crear página WP `/showcase/` y asignar template `page-showcase.php` (WP-CLI).
- [ ] Smoke test end-to-end: completar quiz → enviar lead → verificar CSV.
- [ ] Smoke test showcase: visual responsive + reduced-motion + lazy-init en scroll.

---

## 2026-04-22 — Second brain sistemas visuales (memoria repo)

### Completado

- [x] Documento de investigación y recursos: [`memory/research/visual-systems-canvas-svg-second-brain-2026-04.md`](../../memory/research/visual-systems-canvas-svg-second-brain-2026-04.md) (bibliografía, tablas WP, anti-patrones, enlaces MDN).
- [x] Entrada en [`memory/_indice-consolidado.md`](../../memory/_indice-consolidado.md) § Investigación vigente.
- [x] [`CLAUDE.md`](../../CLAUDE.md) tabla *Archivos importantes* + [`.cursor/memory/techContext.md`](../../.cursor/memory/techContext.md) decisión §13.

---

## 2026-04-20 — Babysit PRs (merge #273 + fixes #278)

### Completado

- [x] Merge `main` → `claude/gifted-dewdney-b958c5` (PR #273): conflictos en `02-pendientes-detallados.md` y `front-page.php` resueltos; trust bar + enlaces catálogo; push a origin.
- [x] PR #278: respuesta a revisión Copilot en `gano-start-journey.css` + bump `style.css` 1.0.6; push a origin; CI php-lint/trufflehog verde.

### Pendiente (humano / orden de merge)

- [ ] Merge a `main` en el orden acordado (`MERGE-PLAYBOOK`); revisar duplicación visual trust bar vs proof bar en home tras fusionar #273 + #278.

---

## 2026-04-19 — Ola ops producción + convergencia repo ↔ servidor

### Completado

- [x] Auditoría / inventario SSH documentado ([`memory/sessions/2026-04-19-auditoria-ssh-inventario-sota.md`](../../memory/sessions/2026-04-19-auditoria-ssh-inventario-sota.md)).
- [x] Backups en servidor (DB, `.htaccess`, archivos pre-convergencia).
- [x] Ajustes operativos: home canónica, menú **Inicio → /**, limpieza de duplicados de página, `gano_pfid_*` en `wp_options`, política bots balanceada, archivos `llms.txt` / `bot-seo-context.md`.
- [x] Eliminación controlada de plugins inactivos no críticos en producción.
- [x] Convergencia de 8 archivos críticos: repo `main` → producción por SCP, verificación MD5, flush de caché y rewrites WP-CLI.
- [x] Índice de trazabilidad y herramientas: [`memory/sessions/2026-04-19-trazabilidad-ops-wave-handoff.md`](../../memory/sessions/2026-04-19-trazabilidad-ops-wave-handoff.md).

### Pendiente (alta prioridad)

- [ ] Validar PFIDs contra RCC (numéricos vs slugs actuales) y cerrar `gano_pfid_online_storage`.
- [ ] Contenido final / Elementor sin placeholders; luego Rank Math + GSC.
- [ ] Wordfence + 2FA en ventana acordada; rotación de tokens (issue [#267](https://github.com/Gano-digital/Pilot/issues/267)).

---

## 2026-04-19 — Homepage SOTA (fase de implementación técnica)

### Completado

- [x] Baseline de hosting por SSH y presupuesto técnico documentado en [`memory/ops/homepage-sota-hosting-baseline-2026-04-17.md`](../../memory/ops/homepage-sota-hosting-baseline-2026-04-17.md).
- [x] Blueprint UX del homepage en [`memory/content/homepage-sota-blueprint-2026-04-17.md`](../../memory/content/homepage-sota-blueprint-2026-04-17.md).
- [x] Refactor de [`wp-content/themes/gano-child/front-page.php`](../../wp-content/themes/gano-child/front-page.php):
  - narrativa de soporte/asesoría/capacitación/seguimiento,
  - catálogo inteligente con modos avanzados progresivos,
  - comparador activo y adaptación móvil de cards,
  - eliminación total de estilos inline y script inline.
- [x] Rediseño de [`wp-content/themes/gano-child/css/homepage.css`](../../wp-content/themes/gano-child/css/homepage.css) con continuidad visual entre secciones y sin huecos blancos no intencionales.
- [x] Nuevo handler frontend [`wp-content/themes/gano-child/js/gano-homepage.js`](../../wp-content/themes/gano-child/js/gano-homepage.js) para lead capture y toggles de interacción.
- [x] Optimización de performance en [`wp-content/themes/gano-child/functions.php`](../../wp-content/themes/gano-child/functions.php):
  - `gano-homepage.js` encolado solo en front page,
  - exclusión de GSAP/Constellation en front page cuando no son necesarios.
- [x] QA técnico en verde:
  - `php -l front-page.php` y `php -l functions.php`,
  - `python scripts/qa_catalog_release.py --gate uiux`,
  - `python scripts/qa_catalog_release.py --gate content`.
- [x] Paralelización de carga pesada en GitHub:
  - workflow `seed-copilot-queue.yml` habilitado y ejecutado para `tasks-wave4-ia-content.json` en scopes `docs` y `content_seo`.

---

## 2026-04-10 — Investigación servidor (capturas cPanel + Installatron) + skills

### Completado

- [x] [`memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md`](../../memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md) — problemas evidenciables E-01–E-12, doble pasada SOTA, plan de reparación Fase 0–4.
- [x] Skill [`.gano-skills/gano-cpanel-ssh-management/SKILL.md`](../../.gano-skills/gano-cpanel-ssh-management/SKILL.md) — § híbrido WordPress + Installatron, checklist y enlaces.
- [x] Skill [`.gano-skills/gano-wp-security/SKILL.md`](../../.gano-skills/gano-wp-security/SKILL.md) — enlace al informe de hosting.

---

## 2026-04-10 — Repo Pilot **público** + alerta runner self-hosted

### Hecho (verificado)

- [x] `Gano-digital/Pilot` — visibilidad **PUBLIC** (`gh repo view`).
- [x] **Mitigación aplicada (repo):** `actions/runners` → **0 runners** registrados en `Pilot`; deploy **04** corre en **ubuntu-latest** + webhook HTTPS (sin self-hosted). (La verificación org-level requiere permisos `admin:org`.)

---

## 2026-04-10 — Investigación SOTA cPanel/Installatron (instalar WP sin sobrescribir)

### Completado

- [x] Actualizada skill [`.gano-skills/gano-cpanel-ssh-management/SKILL.md`](../../.gano-skills/gano-cpanel-ssh-management/SKILL.md) con runbooks SOTA:
  - `Domains → New Document Root` (GoDaddy/cPanel) sin root
  - Installatron: instalar en **subdominio/subdirectorio** sin sobrescribir
  - Installatron: **Import existing install** (adoptar WP existente)
  - WP Toolkit: staging clone/copy (conceptual)
- [x] Skill [`.gano-skills/gano-wp-security/SKILL.md`](../../.gano-skills/gano-wp-security/SKILL.md) enlaza a los flujos cPanel/Installatron para evitar confusiones entre hosting y repo.

---

## 2026-04-10 — Investigación SOTA SSH en cPanel + automatización en hosting pequeño

### Completado

- [x] Skill [`.gano-skills/gano-cpanel-ssh-management/SKILL.md`](../../.gano-skills/gano-cpanel-ssh-management/SKILL.md) ampliada con:
  - Modelo mental correcto de **SSH en GoDaddy cPanel** (shared, sin root/sudo)
  - Checklist de diagnóstico no destructivo
  - Patrones de deploy automatizado: **Git Version Control + `.cpanel.yml`** y alternativa **webhook HTTPS**
  - Cómo encaja “Managed Hosting for Node.JS” (PaaS beta) sin mezclar con WordPress

---

## 2026-04-09 — Investigación SOTA (CI, supply chain, runners)

### Completado

- [x] [`memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md`](../../memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md) — síntesis OWASP A03:2025, GitHub secure use, checklist P0–P2 vs Pilot (self-hosted, minutos, webhook).
- [x] Skills [`.gano-skills/gano-github-copilot-orchestration/SKILL.md`](../../.gano-skills/gano-github-copilot-orchestration/SKILL.md) y [`.gano-skills/gano-github-ops/SKILL.md`](../../.gano-skills/gano-github-ops/SKILL.md) — sección SOTA + runners/minutos.
- [x] `techContext.md` — decisión arquitectónica #9 (SOTA CI/supply chain).

---

## 2026-04-08 — Dependabot `.gsd/sdk` + checklist vitrina Fase 1

### Completado

- [x] **`overrides`** en [`.gsd/sdk/package.json`](../../.gsd/sdk/package.json) (`hono` ^4.12.12, `@hono/node-server` ^1.19.13, `@anthropic-ai/sdk` ^0.81.0); `package-lock.json` regenerado; `npm audit` 0 en `.gsd/sdk` y `.gsd/`.
- [x] Plan vitrina — checklist wp-admin por bloques (hero → CTA final) + nota toolchain Node en [`homepage-vitrina-launch-plan-2026-04.md`](../../memory/ops/homepage-vitrina-launch-plan-2026-04.md); referencia en `TASKS.md`.
- [x] `techContext` / `activeContext` actualizados.

---

## 2026-04-08 — Reporte Claude handoff (SSH/Deploy/tokens)

### Completado

- [x] [`memory/claude/2026-04-08-reporte-handoff-ssh-deploy-tokens.md`](../../memory/claude/2026-04-08-reporte-handoff-ssh-deploy-tokens.md) — reporte detallado para Claude (huella, runs, hipótesis IP, PR #160).
- [x] Enlaces en `memory/claude/README.md`, `02-pendientes-detallados`, plan vitrina, `digital-files-and-content-setup`, `activeContext`, `deferredItems`.
- [x] **PR #160** fusionado en `main` — troubleshooting SSH + reporte handoff Claude.

---

## 2026-04-08 — Post-merge #159 + estado Actions

### Completado

- [x] **#159** en `main` — plan vitrina, docs agentes, pasos *Huella* / *Probar SSH* en deploy.
- [x] **Workflow 14** (push #159): éxito en mismo commit que tocó `gano-ops-hub.yml` paths.
- [ ] **Workflow 04:** huella de clave **coincide** con local; sigue `publickey` en CI — revisar `SERVER_*` y **IP/firewall** hosting (ver [`2026-04-08-reporte-handoff-ssh-deploy-tokens.md`](../../memory/claude/2026-04-08-reporte-handoff-ssh-deploy-tokens.md)).

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
- [ ] SSH al servidor de producción aún no autentica con las claves locales actuales (host en secrets / panel).

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
