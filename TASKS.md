# Tasks — Gano Digital
_Última actualización: Abril 2026_

## REGRESAR AQUÍ (pendiente tu acción en GitHub)

Cuando vuelvas: **Actions → [Orchestrate Copilot waves](https://github.com/Gano-digital/Pilot/actions/workflows/orchestrate-copilot-waves.yml)** → primero `dry_run_merge: true`, luego ejecución real con `merge_wave1` + `seed_wave2`. Detalle: [`.github/MERGE-PLAYBOOK.md`](.github/MERGE-PLAYBOOK.md).

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

- [ ] **[CRÍTICO] Subir archivos corregidos al servidor real** — Los archivos editados (Fases 1-3) están listos:
  - `wp-config.php`
  - `wp-content/mu-plugins/gano-security.php`
  - `wp-content/mu-plugins/gano-seo.php`
  - `wp-content/themes/gano-child/functions.php`
  - `wp-content/themes/gano-child/js/gano-chat.js`
  - `wp-content/themes/gano-child/templates/shop-premium.php` (SOTA Mockup Integrado)

- [ ] **[CRÍTICO] Eliminar wp-file-manager del servidor** — El MU plugin ahora muestra la alerta en wp-admin.
  1. Ir a wp-admin → Plugins → Desactivar wp-file-manager
  2. Eliminar vía SFTP: `wp-content/plugins/wp-file-manager/`

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

- [x] **Auditoría oleada Copilot (2026-04):** issues #17–#33 siguen **abiertos**; hay **~17 PRs** abiertos (muchos draft) — los agentes **entregaron PRs**, falta **revisión CI + merge + cierre de issues**. Ver [`.github/MERGE-PLAYBOOK.md`](.github/MERGE-PLAYBOOK.md).
- [x] **Oleada 2:** `agent-queue/tasks-wave2.json` + workflow **Seed Copilot task queue** con input `queue_file` (`tasks.json` | `tasks-wave2.json`).
- [x] **Validación cola:** workflow **Validate agent queue JSON** + `scripts/validate_agent_queue.py` (CI al tocar `agent-queue/`).
- [x] **Oleada 3 — issues creados en GitHub (#54–#68)** — Seed con `tasks-wave3.json` ejecutado.
- [ ] **Prompt Copilot:** en **#54–#68** usa el bloque **“oleada 3”** en [`.github/prompts/copilot-bulk-assign.md`](.github/prompts/copilot-bulk-assign.md); en **#17–#33** usa el bloque **“oleada 1”** (no priorizar brief wave3 en hero/menú/Lorem). Si ya asignaste con el prompt largo mezclado, no pasa nada grave: el prompt unificado ahora lo matiza por número de issue.
- [ ] **Consolidar oleada 1 + sembrar oleada 2:** Actions → **Orchestrate Copilot waves** — opcional `dry_run_merge` primero; luego ejecutar con merge + seed (requiere permisos del token sobre PRs).
- [x] PR #13: CI TruffleHog + PHP lint + plantillas Copilot + `ssh_cli` sin credenciales en archivo
- [x] Dependabot (GitHub Actions) + plantilla de PR; `labeler.yml` (workflow) retirado hasta crear etiquetas `area:*` en el repo o reactivar con permisos
- [x] `.github/labeler.yml` conserva reglas; workflow `labeler.yml` restaurado tras ejecutar **Setup repository labels** en Actions
- [x] **Setup repository labels** — workflow disparado vía push de [`.github/label-bootstrap`](.github/label-bootstrap) (o manual en Actions)
- [x] Fusionar PR #13 y verificar checks en `main` (squash merge 2026-04-02)
- [ ] Actions → ejecutar **Seed homepage Fix issues** una vez (7 issues `homepage-fixplan`)
- [ ] Actions → **Seed Copilot task queue** (`all` o por ámbito) → asignar Copilot coding agent en issues generados
- [ ] Rotación de tokens y limpieza de remotes con credenciales (al cierre del workflow de despliegue)

### Contenido homepage (Elementor en servidor)

- [x] **Repo:** ubicación de menú `primary` registrada en `gano-child` + utilidades CSS Elementor (`gano-el-*`) — desplegar al servidor
- [ ] Menú principal asignado en wp-admin (y/o header Elementor) tras despliegue
- [ ] Sustituir Lorem / placeholders usando `memory/content/homepage-copy-2026-04.md` como fuente
- [ ] Hero: imagen + attachment coherente con diseño
- [ ] Ajustes de layout: aplicar clases `gano-el-stack` / `gano-el-layer-*` + CTA final con iconos reales
- [ ] Post “coming soon” u oculto según estrategia

### Referencia de copy

- `memory/content/homepage-copy-2026-04.md` — bloques listos para pegar en Elementor
- `memory/content/elementor-home-classes.md` — clases CSS, tabla por sección, auditoría breve

### Coordinación operativa (prioridad paralela)

- **Guía canónica:** [`.github/DEV-COORDINATION.md`](.github/DEV-COORDINATION.md) — qué vive en Git vs servidor vs local; cómo mantener a GitHub “enterado” sin filtrar secretos.
- **Issues:** plantilla *Reporte de sincronización* + etiqueta `coordination` (crear etiquetas con workflow **Setup repository labels** si faltan).
