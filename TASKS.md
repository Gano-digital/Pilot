# Tasks — Gano Digital
_Última actualización: Abril 2026_

## REGRESAR AQUÍ (pendiente tu acción)

**Recordatorio personal (prioridades y workflows):** [`memory/notes/nota-diego-recomendaciones-2026-04.md`](memory/notes/nota-diego-recomendaciones-2026-04.md) · **Contexto para Claude (carpeta dedicada):** [`memory/claude/README.md`](memory/claude/README.md)

**Estado abril 2026:** la cola de **PRs Copilot** se consolidó en `main` (ver [`memory/sessions/2026-04-03-consolidacion-prs-copilot.md`](memory/sessions/2026-04-03-consolidacion-prs-copilot.md)). **Ya no hace falta** ejecutar **10 · Orquestar oleadas** para fusionar esa oleada.

**Siguiente foco:** secrets → **04 · Deploy** / **05 · Verificar parches** → **12 · Eliminar wp-file-manager** (si aún está en servidor) → RCC / Elementor según `TASKS.md` abajo. Guía de nombres en Actions: [`.github/workflows/README.md`](.github/workflows/README.md).

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

- [ ] **[CRÍTICO] Sincronizar parches Fases 1–3 con el servidor** — Opciones:
  1. **CI:** configurar secrets `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH` (ver [`.github/DEV-COORDINATION.md`](.github/DEV-COORDINATION.md)); un push a `main` que toque `gano-child` / `gano-*` / `mu-plugins` dispara [`deploy.yml`](.github/workflows/deploy.yml).
  2. **Verificación:** Actions → **05 · Ops · Verificar parches en servidor** ([`verify-patches.yml`](.github/workflows/verify-patches.yml)) — compara checksums; opcional subir diferencias.
  3. **Manual:** SFTP/rsync si no usas Actions.
  - `wp-config.php` — **no** va en git; subir solo por SFTP seguro.
  - `wp-content/themes/gano-child/templates/shop-premium.php` — va dentro del deploy del child theme cuando exista en repo.

- [ ] **[CRÍTICO] Eliminar wp-file-manager del servidor** — Ejecutar workflow automatizado:
  - **Opción A (recomendada)**: GitHub Actions → **12 · Ops · Eliminar wp-file-manager (SSH)** → `Run workflow` → `force_remove: true` (requiere mismos secrets que deploy)
  - **Opción B (manual)**: wp-admin → Plugins → Desactivar → SFTP eliminar `wp-content/plugins/wp-file-manager/`
  - Post-eliminación: verificar en wp-admin que la alerta de `gano-security.php` desapareció.

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

**ESTRATEGIA ACTUALIZADA**: Toda la facturación y el checkout recaen sobre el API y Carrito nativo de GoDaddy Reseller. Se elimina el overhead de mantener paneles de facturación locales (WHMCS) y gateways de pago propios.

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

---

## 🔄 Abril 2026 — Progreso (homepage + GitHub)

### GitHub / automatización

- [ ] **Oleada 4 — narrativa y páginas (Copilot):** cola [`.github/agent-queue/tasks-wave4-ia-content.json`](.github/agent-queue/tasks-wave4-ia-content.json) — plan maestro de contenidos, matriz productos/servicios, brecha IA vs inventario, orden homepage, pilares, menú, legal/contacto, índice. Sembrar: Actions → **08 · Sembrar cola Copilot** → `queue_file: tasks-wave4-ia-content.json` → `scope: all` (o `docs` / `content_seo` / `commerce`). Luego asignar Copilot.
- [ ] **Cola infra DNS/HTTPS (Copilot + humano):** [`.github/agent-queue/tasks-infra-dns-ssl.json`](.github/agent-queue/tasks-infra-dns-ssl.json) — runbooks y checklists (Copilot **no** cambia DNS en GoDaddy). Sembrar con `queue_file: tasks-infra-dns-ssl.json` y `scope: infra` o `all`. Etiqueta **`infra`** (crear con **06 · Crear etiquetas** si no existe). Verificación local: `python scripts/check_dns_https_gano.py`.
- [x] **Consolidación PRs Copilot (2026-04-03):** cola de PRs vaciada en `main` (squash + cierre duplicados). Detalle: [`memory/sessions/2026-04-03-consolidacion-prs-copilot.md`](memory/sessions/2026-04-03-consolidacion-prs-copilot.md). Revisar en GitHub **issues** aún abiertos y cerrarlos con comentario si el trabajo ya está en `main`.
- [x] **Oleada 2:** `.github/agent-queue/tasks-wave2.json` + workflow **08 · Sembrar cola Copilot** con input `queue_file`: `tasks.json` | `tasks-wave2.json` | `tasks-wave3.json` | `tasks-wave4-ia-content.json` | `tasks-infra-dns-ssl.json` (cinco archivos validados en CI).
- [x] **Validación cola:** workflow **Validate agent queue JSON** + `scripts/validate_agent_queue.py` (CI al tocar `.github/agent-queue/`).
- [x] **Oleada 3 — issues creados en GitHub (#54–#68)** — Seed con `tasks-wave3.json` ejecutado.
- [ ] **Prompt Copilot:** en **#54–#68** usa el bloque **“oleada 3”** en [`.github/prompts/copilot-bulk-assign.md`](.github/prompts/copilot-bulk-assign.md); en **#17–#33** usa el bloque **“oleada 1”** (no priorizar brief wave3 en hero/menú/Lorem). Si ya asignaste con el prompt largo mezclado, no pasa nada grave: el prompt unificado ahora lo matiza por número de issue.
- [x] ~~**Consolidar oleada 1 (merge PRs)**~~ — hecho 2026-04-03. **10 · Orquestar oleadas** solo si en el futuro vuelves a tener un lote de PRs oleada 1 y quieres automatizar merge + **seed oleada 2**. *Trabajo repetible en repo sin GitHub: cola Claude* [`memory/claude/dispatch-queue.json`](memory/claude/dispatch-queue.json) *+ `python scripts/claude_dispatch.py next`.*
- [x] PR #13: CI TruffleHog + PHP lint + plantillas Copilot + `ssh_cli` sin credenciales en archivo
- [x] Dependabot (GitHub Actions) + plantilla de PR; `labeler.yml` (workflow) retirado hasta crear etiquetas `area:*` en el repo o reactivar con permisos
- [x] `.github/labeler.yml` conserva reglas; workflow `labeler.yml` restaurado tras ejecutar **06 · Repo · Crear etiquetas** en Actions
- [x] **06 · Repo · Crear etiquetas** — workflow disparado vía push de [`.github/label-bootstrap`](.github/label-bootstrap) (o manual en Actions)
- [x] Fusionar PR #13 y verificar checks en `main` (squash merge 2026-04-02)
- [ ] Actions → ejecutar **09 · Sembrar issues homepage** una vez (7 issues `homepage-fixplan`)  
  - *Nota: marcar este ítem `[x]` solo después de verificar en github.com que esos 7 issues existen; si ya están creados, marcá hecho y fecha.*
- [ ] Actions → **08 · Sembrar cola Copilot** (`all` o por ámbito) → asignar Copilot coding agent en issues generados  
  - *Nota: no re-ejecutar sin revisar duplicados — el workflow omite si el cuerpo ya tiene el mismo `<!-- agent-task-id:... -->`. Para nuevas oleadas: `tasks-wave4-ia-content.json` (narrativa/páginas) y `tasks-infra-dns-ssl.json` (DNS/HTTPS, scope `infra`).*
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
