# Active Context — Estado Actual

_Última actualización: 2026-04-19._

## Snapshot 2026-04-19 (producción + repo)

- **Ola ops en `gano.digital`:** auditoría SSH, home canónica (`/`), menú corregido, duplicados de página reducidos, opciones `gano_pfid_*` cargadas desde catálogo Reseller (queda `online_storage` en `PENDING_RCC`), política **bots balanceada** en `.htaccess`, `llms.txt` + `bot-seo-context.md` en raíz, limpieza de **13 plugins** inactivos (sin Elementor/Woo en disco).
- **Convergencia código:** `main` del repo es fuente canónica para 8 archivos críticos (tema + MU `gano-seo` / `gano-security` + `class-pfid-admin.php`); desplegado por **SCP** con backup y **MD5** verificado; `wp cache flush` + `rewrite flush`.
- **Trazabilidad consolidada:** [`memory/sessions/2026-04-19-trazabilidad-ops-wave-handoff.md`](../../memory/sessions/2026-04-19-trazabilidad-ops-wave-handoff.md) + detalle técnico [`memory/sessions/2026-04-19-auditoria-ssh-inventario-sota.md`](../../memory/sessions/2026-04-19-auditoria-ssh-inventario-sota.md); tablero GitHub [#263](https://github.com/Gano-digital/Pilot/issues/263).
- **Pendiente inmediato:** validación RCC de PFIDs numéricos vs slugs actuales, copy/Elementor sin placeholders, Rank Math/GSC cuando cierre contenido, Wordfence+2FA en ventana, rotación tokens [#267](https://github.com/Gano-digital/Pilot/issues/267), opcional versionar archivos raíz bot en repo.

_Contexto histórico 2026-04-12 (repo público, runners, vitrina):_

_**`Gano-digital/Pilot` es PÚBLICO** (`gh repo view` → `visibility: PUBLIC`). **Runners en el repo:** API `actions/runners` → **total_count: 0** (verificado 2026-04-11, runner eliminado ✅). Deploy **04** en `main`: **ubuntu-latest** + webhook HTTPS (sin self-hosted en prod). Opcional: un admin de org confirma runners a nivel **organización**; `test-runner.yml` aún declara `runs-on: self-hosted` solo para prueba manual. Triage GitHub actualizado: PRs dependabot + docs + consolidado de Arcana fusionados; PRs conflictivos (#167/#168/#169/#172) cerrados como reemplazados por #182. **Rollout SOTA ejecutado en código (theme/templates/docs) con QA técnica inicial lista.**_

## Foco actual (producto y repo)

- **Vitrina gano.digital:** plan por fases y roles (Diego / Cursor / Copilot / Claude) — [`memory/ops/homepage-vitrina-launch-plan-2026-04.md`](../../memory/ops/homepage-vitrina-launch-plan-2026-04.md) (checklist orden de bloques § Fase 1). Copy fuente: [`homepage-copy-2026-04.md`](../../memory/content/homepage-copy-2026-04.md). Aplicación en **Elementor = humano en wp-admin**; agentes no sustituyen el pegado en panel.
- **Servidor / producción:** desplegar parches Fases 1–3 al hosting real, eliminar `wp-file-manager`, configurar Gano SEO / GSC / Rank Math (`TASKS.md` sección Active). **Informe cPanel (capturas abr 2026):** [`investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md`](../../memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md) — Drupal/Installatron en `/123/` vs WordPress en `gano.digital`, SSL, backups, plan por fases.
- **GitHub `Gano-digital/Pilot`:** **`main` público** — minutos Actions en runners GitHub-hosted más favorables; **no** commitear secretos; vigilar **self-hosted en prod** (ver línea de actualización arriba).
- **Fase 4:** catálogo Reseller, mapeo CTAs en `shop-premium.php`, smoke test checkout — [`memory/commerce/rcc-pfid-checklist.md`](../../memory/commerce/rcc-pfid-checklist.md).
- **Constellation / Battle Map:** plan de **diseño + fine tuning + fases + alineación agentes** — [`memory/constellation/BATTLE-MAP-PLAN-DISENO-FINE-TUNING-2026-04.md`](../../memory/constellation/BATTLE-MAP-PLAN-DISENO-FINE-TUNING-2026-04.md); config ejemplo JSON — [`battle-map-config.example.json`](../../memory/constellation/battle-map-config.example.json); `CONSTELACION-COSMICA.html` expone `window.__GANO_BATTLE_MAP__` (build/plan). Oleada GitHub `copilot/cx-*` sigue playbook; no duplicar PRs masivos.
- **Investigación SOTA UX:** [`memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md`](../../memory/research/sota-ux-rts-fps-constellation-steal-2026-04.md). **Inventario recursos:** [`memory/constellation/INVENTARIO-RECURSOS-DESARROLLO-2026-04.md`](../../memory/constellation/INVENTARIO-RECURSOS-DESARROLLO-2026-04.md).
- **Homepage SOTA 2026 (implementación en curso):**
  - baseline técnico por SSH y budget en [`memory/ops/homepage-sota-hosting-baseline-2026-04-17.md`](../../memory/ops/homepage-sota-hosting-baseline-2026-04-17.md),
  - blueprint UX/copy en [`memory/content/homepage-sota-blueprint-2026-04-17.md`](../../memory/content/homepage-sota-blueprint-2026-04-17.md),
  - refactor de `front-page.php` + `css/homepage.css` + `js/gano-homepage.js` con uniformidad visual y sin estilos/scripts inline.
  - workflows de agentes en paralelo (docs + content_seo) ejecutados con éxito: runs `24635536377` y `24635542040`.

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
- [x] **PR #136** (docs/memoria Fase 4) dejado **merge-ready** (conflictos resueltos, CI verde). Nota: el merge puede quedar bloqueado por ruleset de “Code Quality” en GitHub.
- [x] **PR #159** — plan vitrina + prechequeo SSH en `deploy.yml` + runbook `publickey` — fusionado en `main`; PR #158 cerrado como sustituido.
- [x] **PR #160** — troubleshooting SSH (IP) + reporte handoff para Claude — fusionado en `main`.
- [x] **Triage y limpieza GitHub (2026-04-11):**
  - PRs fusionados: #174 #175 #176 #177 #178 #179 #180 #173 #170 #182.
  - PRs conflictivos cerrados como superseded: #167 #168 #169 #172.
  - Issues Constellation #137 #139 #140 #143 resueltos y cerrados (commit `542011da` en `main`).
- [x] **Oleada agentes (2026-04-11):** workflow **08 · Agentes · Sembrar cola Copilot** ejecutado con `queue_file=tasks-wave3.json` + `scope=all`; creados issues #183–#197 para siguiente ciclo de Copilot.
- [x] **Investigación + plan maestro SOTA de convergencia (2026-04-11):**
  - rutas externas validadas (`Downloads/_ARCHIVO_WEB` + `Downloads/stitch` + `obsidian-prism`),
  - plan maestro documentado en `memory/research/sota-plan-maestro-convergencia-2026-04.md`,
  - code pack en `memory/content/sota-convergence-code-pack-2026-04.md`,
  - kit multimedia en `memory/content/sota-multimedia-production-kit-2026-04.md`,
  - nueva capa CSS `css/gano-sota-convergence.css` encolada desde `functions.php`,
  - clases puente añadidas al playbook Elementor (`elementor-home-classes.md`).
- [x] **Ejecución técnica del plan de convergencia (2026-04-11):**
  - clases `gano-km-*` aplicadas en templates: `page-sota-hub.php`, `page-ecosistemas.php`, `shop-premium.php`, `page-seo-landing.php`, `page-diagnostico-digital.php`,
  - CTAs principales/secundarios alineados al nuevo layer visual en bloques comerciales,
  - `php -l` validado en todos los templates tocados sin errores.
- [x] **Alineación con reporte Claude (2026-04-11):**
  - deploy webhook HTTPS confirmado en `.github/workflows/deploy.yml`,
  - receiver confirmado en `wp-content/gano-deploy/receive.php`,
  - creados docs faltantes en repo: `memory/claude/2026-04-11-plan-aprobado-inicio-ejecucion.md`, `memory/ops/2026-04-11-fase0-webhook-validado.md`, `memory/ops/2026-04-11-FASE1-instrucciones-operativas.md`.
- [x] **Obsidian MCP local (2026-04-11):** `.cursor/mcp.json` actualizado con servidor `obsidian-mcp-server` usando `OBSIDIAN_API_KEY` + `OBSIDIAN_BASE_URL=https://127.0.0.1:27124` para integración con Obsidian Local REST API.
- [x] **Reporte técnico extenso (handoff Claude):** [`memory/claude/2026-04-10-reporte-tecnico-extenso-claude.md`](../../memory/claude/2026-04-10-reporte-tecnico-extenso-claude.md) — problemas (hosting E-01–E-12, runners, deploy, repo), soluciones, estrategias intentadas, checklist P0–P3; **orden 0** en [`memory/claude/README.md`](../../memory/claude/README.md).
- [x] **Fase 1 installer — `.htaccess`:** el archivo fuente estaba mal nombrado `htaccest-security.txt` (typo); el código busca `htaccess-security.txt`. Corregido en repo renombrando a [`htaccess-security.txt`](../../wp-content/plugins/gano-phase1-installer/htaccess-security.txt). En staging: sincronizar carpeta del plugin y **desactivar + volver a activar** `gano-phase1-installer` (o subir solo el `.txt` y repetir activación) para regenerar `.htaccess`.
- [x] **Integración SOTA base visual (2026-04-11):**
  - auditoría de mockups (`memory/content/sota-audit-mockups-2026-04.md`),
  - guía visual canónica (`memory/content/sota-visual-guide-v1.md`) + actualización clases Elementor,
  - nuevos componentes SOTA en `style.css`,
  - rollout templates (`page-nosotros`, `page-sota-hub`, `page-seo-landing`, `page-ecosistemas`) y nuevo `page-diagnostico-digital.php`,
  - capa comercial catálogo en `functions.php` + `shop-premium.php` con estados `active|pending|coming-soon`.

## En progreso

- [x] **Fase 0 webhook deploy ejecutada (2026-04-12):**
  - secrets `GANO_DEPLOY_HOOK_URL` + `GANO_DEPLOY_HOOK_SECRET` presentes en GitHub,
  - endpoint `receive.php` validado (GET con user-agent navegador => `405 Method not allowed`),
  - fix en workflow 04 para user-agent permitido en POST webhook (`fix(deploy): enviar webhook con user-agent permitido`, commit `4487e8f1`),
  - deploy exitoso en run **24297514353** (`Security Scan` + `Deploy to production` en verde).
- [x] **Post-deploy Fase 1.5 (2026-04-12):**
  - workflow **12 · Ops · Eliminar wp-file-manager (SSH)** run **24297554961** en verde,
  - validación remota: `wp-file-manager` no está presente en producción (pasos de eliminación quedaron `skipped` por no aplicar),
  - workflow **05 · Ops · Verificar parches en servidor** run **24297554963** en verde, detectando brecha por ruta remota (`MISSING_ON_SERVER`),
  - ajuste de secreto GitHub `DEPLOY_PATH` al docroot real de WordPress (`/home/f1rml03th382/public_html/gano.digital`),
  - rerun de verificación: **24297613457** en verde con checksums sincronizados entre repo y servidor.
- [x] **Publicación de rutas SOTA faltantes en producción (2026-04-12):**
  - detectados `404` en URLs: `/shop-premium/`, `/sota-hub/`, `/seo-landing/`, `/diagnostico-digital/`,
  - causa raíz: templates presentes en servidor, pero páginas WP no creadas para esos slugs,
  - páginas creadas por WP-CLI y publicadas (IDs `1754`–`1757`),
  - `_wp_page_template` asignado y verificado:
    - `1754` -> `templates/shop-premium.php`,
    - `1755` -> `templates/page-sota-hub.php`,
    - `1756` -> `templates/page-seo-landing.php`,
    - `1757` -> `templates/page-diagnostico-digital.php`,
  - verificación HTTP post-fix: las cuatro rutas responden `200`.
- [x] **Alineación TASKS + memoria Claude (2026-04-12):**
  - `TASKS.md` actualizado con estado real de ejecución para bloques críticos (rollout SOTA, workflow 05, workflow 12),
  - nueva entrada de trazabilidad en `memory/claude/2026-04-12-verificacion-tasks-y-ejecucion.md`,
  - oleada activa confirmada en GitHub: issues `#183`–`#197`.
- [x] **Workflow 14 (Ops Hub):** script `--output` relativo corregido en `main` (#157); run manual post-merge: éxito ([24147290218](https://github.com/Gano-digital/Pilot/actions/runs/24147290218)).
- [ ] SFTP / sync VS Code si aplica al flujo de deploy de Diego.
- [x] Deploy de archivos críticos al servidor (2026-04-19: convergencia SCP + MD5) — seguir usando webhook **04**/**05** para rutina futura.
- [ ] Tareas solo-wp-admin listadas en `TASKS.md` (Elementor, Rank Math, Wordfence/2FA, datos Gano SEO).
- [ ] QA manual en staging para rollout SOTA (visual responsive + smoke comercial RCC): `memory/ops/sota-rollout-qa-wave-2026-04.md`.
- [ ] Aplicación en servidor de catálogo canónico (`mockup_completo_ignorar.html`) pendiente de despliegue remoto; acceso SSH por alias `gano-godaddy` ya verificado operativo.

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
