# Progress Tracker

## 2026-04-11 — Integración SOTA base visual + rollout templates

### Completado

- [x] Fase 0 auditada y documentada: `memory/content/sota-audit-mockups-2026-04.md`.
- [x] Sistema visual canónico v1 publicado:
  - `style.css` con componentes reutilizables SOTA (`hero`, `glass-card`, `status-strip`, `cards-grid`, `quiz-shell`).
  - guía: `memory/content/sota-visual-guide-v1.md`.
  - clases Elementor actualizadas en `memory/content/elementor-home-classes.md`.
- [x] Rollout de templates core:
  - `page-nosotros.php` (sin estilos inline),
  - `page-sota-hub.php` (markup unificado a clases del theme),
  - `page-seo-landing.php` (copy/comercial actualizado a Reseller, estilos movidos a `style.css`),
  - `page-ecosistemas.php` (normalización CTA final),
  - nuevo `page-diagnostico-digital.php` con quiz mantenible.
- [x] Capa comercial de catálogo Reseller:
  - `functions.php` ahora incluye `gano_get_reseller_catalog_products()` + `gano_resolver_catalog_cta()`.
  - `shop-premium.php` consume estados normalizados `active|pending|coming-soon`.
  - checklist RCC ampliado: `memory/commerce/rcc-pfid-checklist.md`.
- [x] QA técnica inicial completada (`php -l` en archivos modificados) sin errores de sintaxis.

### Pendiente

- [ ] QA manual en staging (visual, responsive, accesibilidad y smoke comercial RCC/checkout) usando `memory/ops/sota-rollout-qa-wave-2026-04.md`.
- [ ] Completar PFIDs reales en RCC para pasar productos `pending` a `active` en producción.
- [ ] Publicar en producción las plantillas SOTA nuevas (`shop-premium`, `diagnostico-digital`, `sota-hub`) o crear las páginas y asignar template en wp-admin para evitar 404.

### Ajuste post-auditoría (2026-04-11)

- [x] Corregido orden de hook `wp_head` en `page-seo-landing.php` (schema JSON-LD ahora se registra antes de `get_header()`).
- [x] Limpieza de inline styles restantes en `shop-premium.php` (doodles, hero bg text e iconos SVG movidos a clases CSS).
- [x] Catálogo canónico de `mockup_completo_ignorar.html` integrado en `functions.php` + `shop-premium.php`:
  - categorías completas (hosting cPanel, WHP, WP administrado, VPS, VPS HP, SSL, M365, seguridad web, creador, marketing, dominios),
  - estrategias de CTA por tipo (PFID Reseller, dominio search, URL externa VPS, coming-soon),
  - badges/tips/precios por producto y filtros dinámicos por categoría.
- [ ] SSH directo no ejecutado: faltan variables `GANO_SSH_HOST` / `GANO_SSH_USER` / credenciales en entorno local para `ssh_cli.py`.

---

## 2026-04-11 — Triage masivo GitHub + consolidación PRs conflictivos

### Completado

- [x] Verificación de accesos: GitHub por HTTPS/CLI operativo; SSH GitHub y servidor no autenticado.
- [x] Desbloqueo y rerun de checks tras ajuste de billing en Actions.
- [x] Fusión de PRs listos: #174 #175 #176 #177 (dependabot), #178 #179 (docs), #180, #173 y #170.
- [x] Cierre de PRs conflictivos de Arcana (#167/#168/#169/#172) como reemplazados por PR consolidado.
- [x] Creación y merge de **#182** con consolidación de mejoras:
  - tokenización de estilos en `style.css` (member portal + premium cards),
  - contacto sin inline styles + copy final "Soporte por ticket disponible 24/7",
  - asset `hero-datacenter.jpg` y fallback para hero,
  - bloques reutilizables (`gano-form-notice--success`, skeleton loaders).
- [x] Setup inicial de **MCP Obsidian** en Cursor: `.cursor/mcp.json` ahora incluye servidor `obsidian` (`npx -y obsidian-mcp-server`) con base URL local `https://127.0.0.1:27124`.

### Pendiente

- [ ] Ejecutar validación visual en WordPress/Elementor (hero con y sin background inline, contacto y cards premium).
- [ ] Resolver issues abiertos Constellation: #137 #139 #140 #143.

---

## 2026-04-10 — Repo Pilot **público** + alerta runner self-hosted

### Hecho (verificado)

- [x] `Gano-digital/Pilot` — visibilidad **PUBLIC** (`gh repo view`).
- [ ] **P0 seguridad:** runner **`gano-godaddy-server`** (id **21**, `gano-production`) sigue **online** en el repo público — desregistrar o aislar **urgente** (ver [`sota-investigacion-2026-04-09-ci-supply-chain-agents.md`](../../memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md) §5).

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
