# Tasks — Gano Digital
_Última actualización: Abril 2026_

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
