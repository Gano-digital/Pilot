# Tasks — Gano Digital
_Última actualización: Abril 2026_

## REGRESAR AQUÍ (pendiente tu acción)

**Si saliste y vuelves:** nota de continuidad **cPanel/DNS + agentes GitHub** → [`memory/notes/nota-salida-cpanel-dns-y-agentes-2026-04.md`](memory/notes/nota-salida-cpanel-dns-y-agentes-2026-04.md).

**Recordatorio personal (prioridades y workflows):** [`memory/notes/nota-diego-recomendaciones-2026-04.md`](memory/notes/nota-diego-recomendaciones-2026-04.md) · **Contexto para Claude (carpeta dedicada):** [`memory/claude/README.md`](memory/claude/README.md) · **Mapa de archivos digitales y contenido (`memory/`, constelación, handoff):** [`memory/content/digital-files-and-content-setup.md`](memory/content/digital-files-and-content-setup.md) · **Ops Hub (métricas + Actions):** [`tools/gano-ops-hub/README.md`](tools/gano-ops-hub/README.md) · **Playbook agentes + asistentes (arranque, troubleshooting, offloading):** [`memory/ops/agent-playbook-asistentes-2026-04.md`](memory/ops/agent-playbook-asistentes-2026-04.md)

**Estado abril 2026:** la cola de **PRs Copilot** se consolidó en `main` (ver [`memory/sessions/2026-04-03-consolidacion-prs-copilot.md`](memory/sessions/2026-04-03-consolidacion-prs-copilot.md)). **Ya no hace falta** ejecutar **10 · Orquestar oleadas** para fusionar esa oleada.

**Repo `Pilot` (2026-04-11):** **público** — **runners self-hosted eliminados** ✅ (verificado 2026-04-11: `gh api` → total_count: 0). Deploy **04** usa `ubuntu-latest` + webhook HTTPS. Ver `memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md` §5 y `.cursor/memory/activeContext.md`.

**Hosting cPanel (evidencias capturas):** [`memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md`](memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md) — Installatron/Drupal (`/123/`) vs WordPress `gano.digital`, errores de config, SSL/HTTPS, backups, RAM 512 MB; plan de reparación por fases (ticket GoDaddy recomendado para Installatron).

**Siguiente foco:** secrets → **04 · Deploy** / **05 · Verificar parches** → **12 · Eliminar wp-file-manager** (si aún está en servidor) → RCC / Elementor según `TASKS.md` abajo. Guía de nombres en Actions: [`.github/workflows/README.md`](.github/workflows/README.md).

**Plan vitrina (homepage + comercio + roles de agentes):** [`memory/ops/homepage-vitrina-launch-plan-2026-04.md`](memory/ops/homepage-vitrina-launch-plan-2026-04.md) — fases 0–4, RACI, checklist Fase 1 (wp-admin), procesos y enlaces canónicos. Complementa § Active, Pending y Fase 4 sin sustituirlos.

**Dependabot (Node — `.gsd/sdk`):** alertas de `hono` / `@hono/node-server` / `@anthropic-ai/sdk` resueltas con `overrides` en [`.gsd/sdk/package.json`](.gsd/sdk/package.json); `npm audit` en `.gsd/` y `.gsd/sdk/` → 0 vulnerabilidades (abr 2026).

### Trabajo en paralelo (no sustituye Active ni Fase 4)

- **Investigación SOTA + flujo + carriles A/B/C:** [`memory/research/sota-workflow-ops-parallel-2026-04.md`](memory/research/sota-workflow-ops-parallel-2026-04.md) — colisión con progreso real, cambios P0–P2 al workflow, checklist para ir tachando **sin pausar** deploy, contenido ni Reseller. Si un ítem choca con un P0 de esta tabla, gana la tabla de abajo.
- **SOTA CI / cadena de suministro / runners (abr 2026):** [`memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md`](memory/research/sota-investigacion-2026-04-09-ci-supply-chain-agents.md) — OWASP A03:2025, uso seguro de Actions, checklist vs self-hosted y minutos GitHub-hosted.

---

## ✅ Fase 1 — Parches de código aplicados

- [x] ~~**V-01**: WP_DEBUG=false + DISALLOW_FILE_EDIT/MODS~~ → `wp-config.php` ✅
- [x] ~~**V-05**: Nonce CSRF en Chat IA (localize_script + X-WP-Nonce + permission_callback)~~ → `functions.php` + `gano-chat.js` ✅
- [x] ~~**V-05**: Input sanitizado (textContent, maxlength, sanitizeInput)~~ → `gano-chat.js` ✅
- [x] ~~**V-05**: Nudge proactivo reescrito (sin afirmaciones alarmistas)~~ → `gano-chat.js` ✅
- [x] ~~**V-04**: Alerta de wp-file-manager en wp-admin~~ → `gano-security.php` ✅

## ✅ Fase 2 — Hardening avanzado aplicado

- [x] ~~**Rate limiting REST chat**: max 20 req/IP/60s con WP transients~~ → `functions.php` ✅
  - Devuelve HTTP 429 con WP_Error serializado
  - Ventana deslizante con transient `_start` para precisión
- [x] ~~**CSP enforced** (fue Report-Only): directivas para Elementor + WooCommerce~~ → `gano-security.php` ✅
  - Agrega: X-XSS-Protection, Referrer-Policy, Permissions-Policy
  - Cambia X-Frame-Options de DENY a SAMEORIGIN

## ✅ Fase 3 — SEO y Performance aplicado

- [x] ~~**Schema JSON-LD Organization+LocalBusiness**: datos de negocio digital~~ → `gano-seo.php` MU plugin ✅
- [x] ~~**Schema JSON-LD WebSite**: SearchAction para Google~~ → `gano-seo.php` ✅
- [x] ~~**Schema JSON-LD Product**: precios en COP para los 4 ecosistemas~~ → `gano-seo.php` ✅
- [x] ~~**Schema JSON-LD FAQPage**: 5 preguntas frecuentes (mejora CTR)~~ → `gano-seo.php` ✅
- [x] ~~**Schema JSON-LD BreadcrumbList**: automático en páginas internas~~ → `gano-seo.php` ✅
- [x] ~~**Página wp-admin "Gano SEO"**: configurador digital~~ → `gano-seo.php` ✅
- [x] ~~**Open Graph + Twitter Card**: fallback si Rank Math no está activo~~ → `gano-seo.php` ✅
- [x] ~~**Resource hints**: preconnect Google Fonts, dns-prefetch Analytics~~ → `gano-seo.php` ✅
- [x] ~~**LCP Hero Image preload**: configurable por URL vía wp-admin~~ → `gano-seo.php` ✅
- [x] ~~**Core Web Vitals — Emoji removal**: ~50KB JS eliminados~~ → `functions.php` ✅
- [x] ~~**Core Web Vitals — Head cleanup**: rsd_link, wlwmanifest, wp_generator, shortlink eliminados~~ → `functions.php` ✅
- [x] ~~**Core Web Vitals — Defer JS**: gano-chat-js, gano-quiz-js, shop-animations~~ → `functions.php` ✅
- [x] ~~**Core Web Vitals — LCP JS hook**: MutationObserver establece fetchpriority=high en hero~~ → `functions.php` ✅

## 🔴 Active — Acción manual requerida en servidor real

- [x] **[CRÍTICO] Rollout SOTA en producción (actualizado 2026-04-12):**
  - Repo: integración completada (design system, templates SOTA y catálogo canónico en `functions.php` + `shop-premium.php`).
  - Producción:
    1. Deploy de `gano-child` validado por webhook + verificación de parches (`05`),
    2. páginas SOTA publicadas con template asignado (`/shop-premium/`, `/sota-hub/`, `/seo-landing/`, `/diagnostico-digital/`),
    3. verificación HTTP en vivo: rutas responden `200`.
  - Cierre operativo pendiente:
    - checklist QA manual visual/comercial en `memory/ops/sota-rollout-qa-wave-2026-04.md`,
    - aplicación final de clases/copy en Home Elementor (wp-admin).

- [x] **[CRÍTICO] Sincronizar parches Fases 1–3 con el servidor** — Ejecutado 2026-04-12:
  1. **CI:** configurar secrets `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH` (ver [`.github/DEV-COORDINATION.md`](.github/DEV-COORDINATION.md)); un push a `main` que toque `gano-child` / `gano-*` / `mu-plugins` dispara [`deploy.yml`](.github/workflows/deploy.yml).
  2. **Verificación:** Actions → **05 · Ops · Verificar parches en servidor** ([`verify-patches.yml`](.github/workflows/verify-patches.yml)) — run `24297613457` en verde y checksums alineados.
  3. **Manual:** SFTP/rsync si no usas Actions.
  - `wp-config.php` — **no** va en git; subir solo por SFTP seguro.
  - `wp-content/themes/gano-child/templates/shop-premium.php` — va dentro del deploy del child theme cuando exista en repo.

- [x] **[CRÍTICO] Eliminar wp-file-manager del servidor** — Verificado 2026-04-12:
  - **Opción A (recomendada)**: GitHub Actions → **12 · Ops · Eliminar wp-file-manager (SSH)** → run `24297554961` en verde.
  - **Opción B (manual)**: wp-admin → Plugins → Desactivar → SFTP eliminar `wp-content/plugins/wp-file-manager/`
  - Post-eliminación: verificar en wp-admin que la alerta de `gano-security.php` desapareció (pendiente validación visual humana).

- [ ] **[ALTA] Configurar datos SEO (Empresa Digital)** en wp-admin → Ajustes → Gano SEO:
  - Definir área de cobertura (Colombia) sin dirección física local obligatoria.

- [ ] **[ALTA] Configurar Google Search Console**
  - Añadir propiedad: https://gano.digital

- [ ] **[ALTA] Configurar Rank Math en WordPress**
  - Ir a: wp-admin → Rank Math → Setup Wizard
  - Ajustar para modelo de servicios digitales (no tienda física).

## 🟡 Pending — Contenido y Sinergia

- [ ] **[ALTA] Reemplazar todo el Lorem ipsum y texto placeholder** — Requiere acceso al panel de Elementor
- [ ] **[ALTA] Eliminar testimonios falsos y métricas infladas**
- [ ] **[ALTA] Crear página Nosotros real** con manifiesto SOTA
- [ ] **[MEDIA] Habilitar 2FA en wp-admin** (Wordfence ya instalado)
- [ ] **[MEDIA] Ejecutar y limpiar plugins de fase (1, 2, 3, 6, 7)** 

## 📋 Fase 4 — Integración GoDaddy Reseller (Agilizada)

**ESTRATEGIA ACTUALIZADA**: Toda la facturación y el checkout recaen sobre el **carrito y el programa nativos** del GoDaddy Reseller (RCC + Reseller Store), no sobre la **API REST** del Developer Portal. Esa API es **opcional** solo como herramienta complementaria (scripts, back-office, futuro billing); **Good as Gold** aplica cuando haya **compras** vía API, no para uso meramente consultivo. Se elimina el overhead de mantener paneles de facturación locales (WHMCS) **mientras** el modelo activo sea Reseller; `memory/research/fase4-plataforma.md` queda como referencia para expansiones posteriores.

**Referencias operativas API GoDaddy Reseller:** [`memory/research/godaddy-api-reseller-operations-2026.md`](memory/research/godaddy-api-reseller-operations-2026.md) — ToU resumidos, flujo OTE → producción → Good as Gold, matriz acción/requisito/riesgo.

- [ ] **Depurar Catálogo en GoDaddy Reseller Control Center**:
  - Asegurar que los productos (Hosting, VPS, SSL) tengan el precio base en el RCC (Reseller Control Center).
- [ ] **Mapeo de UI SOTA -> Reseller**:
  - Conectar los ID de productos requeridos en los CTAs de `shop-premium.php` con el carrito de compra GoDaddy.
  - Guía paso a paso RCC → pfid: [`memory/commerce/rcc-pfid-checklist.md`](memory/commerce/rcc-pfid-checklist.md).
- [ ] **Prueba de Flujo de Checkout**:
  - Presionar "Comprar" en el SOTA Mockup de Gano -> Verificar que mande al carrito marca blanca -> Cierre.
- [ ] **Instalar soporte/chat**: FreeScout o similar para atención a cliente, ya que el soporte inicial lo da el reseller (vos).

## 📋 Someday — Fase 5

- [ ] StatusPage (Upptime o Cachet)
- [ ] Conectar Chat IA a LLM real (n8n / Make / API propia)
- [ ] Programa de afiliados / sub-resellers

## 📋 Etapa posterior — Tests y calidad de ingeniería del código

_Activar cuando:_ parches F1–3 desplegados de forma estable, flujo Fase 4 Reseller validado en staging (o criterio explícito de “listos para deuda técnica”), y contenido crítico bajo control. Objetivo: acercar la dimensión **tests automatizados** al nivel ya alto de **CI + documentación** (ver conversación de auditoría ~7.5–8/10 global vs ~5–6/10 en tests puros).

- [ ] Leer y aplicar criterios de `.gano-skills/gano-wp-engineering-quality/SKILL.md`.
- [ ] Definir alcance mínimo (p. ej. helpers en `gano-seo.php`, utilidades sin DOM; **no** todo Elementor).
- [ ] Introducir PHPUnit + bootstrap WP **o** batería WP-CLI/smoke acotada que complemente `php -l` (sin duplicar ruido).
- [ ] Opcional: E2E muy acotado en flujos sensibles (coste alto; solo si aporta).
- [ ] Documentar en CI un job que ejecute solo tests nuevos en paths definidos.

---

## 🔄 Abril 2026 — Progreso (homepage + GitHub)

### GitHub / automatización

- [x] **Oleada 4 — narrativa y páginas (Copilot):** cola [`.github/agent-queue/tasks-wave4-ia-content.json`](.github/agent-queue/tasks-wave4-ia-content.json) — entregas markdown fusionadas en `main` (2026-04-03, PRs #100–#109). *Pendiente humano:* aplicar en Elementor / wp-admin según §Contenido homepage.
- [x] **Cola infra DNS/HTTPS (Copilot + humano):** [`.github/agent-queue/tasks-infra-dns-ssl.json`](.github/agent-queue/tasks-infra-dns-ssl.json) — runbooks y plantillas en `memory/ops/` en `main` (2026-04-03, PRs #105–#107, #110–#113). *Pendiente humano:* ejecutar cambios DNS/SSL en GoDaddy; verificación local: `python scripts/check_dns_https_gano.py`.
- [ ] **Cola API Mercado Libre + GoDaddy (research):** [`.github/agent-queue/tasks-api-integrations-research.json`](.github/agent-queue/tasks-api-integrations-research.json) — profundiza [`memory/research/sota-apis-mercadolibre-godaddy-2026-04.md`](memory/research/sota-apis-mercadolibre-godaddy-2026-04.md). Sembrar: **08** → `queue_file: tasks-api-integrations-research.json` → `scope: all` (o `docs` / `coordination`). Prompt: bloque **API integrations** en [`.github/prompts/copilot-bulk-assign.md`](.github/prompts/copilot-bulk-assign.md).
- [ ] **Cola Guardián seguridad (higiene + cierre de sesión):** [`.github/agent-queue/tasks-security-guardian.json`](.github/agent-queue/tasks-security-guardian.json) — purga documental, `.gitignore`, instrucciones; skill [`.gano-skills/gano-session-security-guardian/SKILL.md`](.gano-skills/gano-session-security-guardian/SKILL.md). Sembrar: **08** → `tasks-security-guardian.json` → `scope: security` o `all`. Prompt: **Guardián seguridad**. Checklist humano: [`memory/ops/security-end-session-checklist.md`](memory/ops/security-end-session-checklist.md) · `python scripts/security_session_reminder.py`.
- [x] **Consolidación PRs Copilot (2026-04-03):** cola de PRs vaciada en `main` (squash + cierre duplicados). Detalle: [`memory/sessions/2026-04-03-consolidacion-prs-copilot.md`](memory/sessions/2026-04-03-consolidacion-prs-copilot.md). Revisar en GitHub **issues** aún abiertos y cerrarlos con comentario si el trabajo ya está en `main`.
- [x] **Oleada 2+:** `.github/agent-queue/tasks-wave2.json` + workflow **08 · Sembrar cola Copilot** con input `queue_file`: `tasks.json` | `tasks-wave2.json` | `tasks-wave3.json` | `tasks-wave4-ia-content.json` | `tasks-infra-dns-ssl.json` | `tasks-api-integrations-research.json` | `tasks-security-guardian.json` (siete archivos validados en CI).
- [x] **Validación cola:** workflow **Validate agent queue JSON** + `scripts/validate_agent_queue.py` (CI al tocar `.github/agent-queue/`).
- [x] **Oleada 3 — issues creados en GitHub (#54–#68)** — Seed con `tasks-wave3.json` ejecutado.
- [ ] **Prompt Copilot:** en **#54–#68** usa el bloque **“oleada 3”** en [`.github/prompts/copilot-bulk-assign.md`](.github/prompts/copilot-bulk-assign.md); en **#17–#33** usa el bloque **“oleada 1”** (no priorizar brief wave3 en hero/menú/Lorem). Si ya asignaste con el prompt largo mezclado, no pasa nada grave: el prompt unificado ahora lo matiza por número de issue.
- [x] ~~**Consolidar oleada 1 (merge PRs)**~~ — hecho 2026-04-03. **10 · Orquestar oleadas** solo si en el futuro vuelves a tener un lote de PRs oleada 1 y quieres automatizar merge + **seed oleada 2**. *Trabajo repetible en repo sin GitHub (dispatch Claude): ver [memory/claude/dispatch-queue.json](memory/claude/dispatch-queue.json).*
- [x] PR #13: CI TruffleHog + PHP lint + plantillas Copilot + `ssh_cli` sin credenciales en archivo
- [x] Dependabot (GitHub Actions) + plantilla de PR; `labeler.yml` (workflow) retirado hasta crear etiquetas `area:*` en el repo o reactivar con permisos
- [x] `.github/labeler.yml` conserva reglas; workflow `labeler.yml` restaurado tras ejecutar **06 · Repo · Crear etiquetas** en Actions
- [x] **06 · Repo · Crear etiquetas** — workflow disparado vía push de [`.github/label-bootstrap`](.github/label-bootstrap) (o manual en Actions)
- [x] Fusionar PR #13 y verificar checks en `main` (squash merge 2026-04-02)
- [ ] Actions → ejecutar **09 · Sembrar issues homepage** una vez (7 issues `homepage-fixplan`)  
  - *Nota: marcar este ítem [x] solo tras confirmar en github.com que los 7 issues homepage-fixplan existen.*
- [ ] Actions → **08 · Sembrar cola Copilot** (`all` o por ámbito) → asignar Copilot coding agent en issues generados  
  - *Nota: no re-ejecutar sin revisar duplicados por `agent-task-id` — el workflow omite si el cuerpo ya tiene el mismo ID.*
- [ ] Rotación de tokens y limpieza de remotes con credenciales (al cierre del workflow de despliegue)

### Contenido homepage (Elementor en servidor)

- [x] **Repo:** ubicación de menú `primary` registrada en `gano-child` + utilidades CSS Elementor (`gano-el-*`) — desplegar al servidor
- [ ] Menú principal asignado en wp-admin (y/o header Elementor) tras despliegue
- [ ] Sustituir Lorem / placeholders usando `memory/content/homepage-copy-2026-04.md` como fuente
- [ ] Hero: imagen + attachment coherente con diseño
- [ ] Ajustes de layout: aplicar clases `gano-el-stack` / `gano-el-layer-*` + CTA final con iconos reales
- [x] Post "coming soon" u oculto según estrategia — `dashboard-infraestructura` marcado como borrador (`_gano_coming_soon`); phase7 respeta la bandera.

### Referencia de copy

- `memory/content/homepage-copy-2026-04.md` — bloques listos para pegar en Elementor
- `memory/content/elementor-home-classes.md` — clases CSS, tabla por sección, auditoría breve

### Oleada 3 — marca/UX/comercial

- **Brief maestro:** [`memory/research/gano-wave3-brand-ux-master-brief.md`](memory/research/gano-wave3-brand-ux-master-brief.md)
- **Cola de tareas:** [`.github/agent-queue/tasks-wave3.json`](.github/agent-queue/tasks-wave3.json)
- Activar: Actions → **08 · Sembrar cola Copilot** → `queue_file: tasks-wave3.json` → `scope: all` (o por ámbito: `theme` / `content_seo` / `commerce` / `docs` / `coordination`). Luego asignar Copilot en los issues generados.

### Oleada 4 — narrativa, páginas y comercio

- **Índice de documentos:** [`memory/content/README-CONTENT-INDEX-2026.md`](memory/content/README-CONTENT-INDEX-2026.md) — mapa de lectura de todos los markdown en `memory/content/` con dependencias entre archivos.
- **Cola de tareas:** [`.github/agent-queue/tasks-wave4-ia-content.json`](.github/agent-queue/tasks-wave4-ia-content.json)
- **Áreas principales:** plan maestro de contenidos, brecha IA vs inventario real, narrativa de páginas SOTA, copy legal/contacto, pilares (20 páginas).
- Activar: Actions → **08 · Sembrar cola Copilot** → `queue_file: tasks-wave4-ia-content.json` → `scope: all` (o `docs` / `content_seo` / `commerce`). Luego asignar Copilot en los issues generados.
- **Documentos oleada 4** se añaden al índice anterior (`README-CONTENT-INDEX-2026.md`) a medida que se crean.

### Coordinación operativa (prioridad paralela)

- **Guía canónica:** [`.github/DEV-COORDINATION.md`](.github/DEV-COORDINATION.md) — qué vive en Git vs servidor vs local; cómo mantener a GitHub “enterado” sin filtrar secretos.
- **Issues:** plantilla *Reporte de sincronización* + etiqueta `coordination` (crear etiquetas con workflow **06 · Repo · Crear etiquetas** si faltan).

### Infra DNS/HTTPS

> **Repo:** documentación y script **ya están en `main`**. Pendiente operativo: aplicar cambios en GoDaddy/hosting y validar con el script local.

- [x] **Runbook DNS + HTTPS GoDaddy:** [`memory/ops/dns-https-godaddy-runbook-2026.md`](memory/ops/dns-https-godaddy-runbook-2026.md)
- [x] **Plantilla registros DNS (apex + www):** [`memory/ops/dns-expected-records-template-2026.md`](memory/ops/dns-expected-records-template-2026.md)
- [x] **Checklist HTTPS Managed WP:** [`memory/ops/https-wordpress-managed-checklist-2026.md`](memory/ops/https-wordpress-managed-checklist-2026.md)
- [x] **URL canónica (apex vs www) + HSTS:** [`memory/ops/url-canonical-gano-digital-2026.md`](memory/ops/url-canonical-gano-digital-2026.md)
- [x] **Script verificación local (stdlib, sin deps):** [`scripts/check_dns_https_gano.py`](scripts/check_dns_https_gano.py)
- [x] **Docs de uso del script:** [`memory/ops/dns-verify-script-usage-2026.md`](memory/ops/dns-verify-script-usage-2026.md)
- [ ] **Ejecutar** en tu PC: `python scripts/check_dns_https_gano.py` y comparar con plantilla/runbook tras cualquier cambio DNS.
- [ ] **Aplicar** registros y SSL en panel (humano) — la cola `tasks-infra-dns-ssl.json` sirve para issues Copilot que **documenten**; el cambio en DNS no lo hace GitHub.
- Opcional — sembrar issues desde cola: Actions → **08** → `queue_file: tasks-infra-dns-ssl.json` → `scope: infra`
