# Tasks — Gano Digital
_Última actualización: Marzo 18, 2026_

## ✅ Fase 1 — Parches de código aplicados

- [x] ~~**V-01**: WP_DEBUG=false + DISALLOW_FILE_EDIT/MODS~~ → `wp-config.php` ✅
- [x] ~~**V-02**: Clave Wompi hardcodeada eliminada~~ → `gano-wompi-integration.php` ✅
- [x] ~~**V-03**: Webhook REST Wompi con verificación HMAC-SHA256 (timing-safe)~~ → `gano-wompi-integration.php` ✅
- [x] ~~**V-03**: Mapeo estados Wompi→WC + referencia guardada en meta de orden~~ → `class-gano-wompi-gateway.php` ✅
- [x] ~~**V-05**: Nonce CSRF en Chat IA (localize_script + X-WP-Nonce + permission_callback)~~ → `functions.php` + `gano-chat.js` ✅
- [x] ~~**V-05**: Input sanitizado (textContent, maxlength, sanitizeInput)~~ → `gano-chat.js` ✅
- [x] ~~**V-05**: Nudge proactivo reescrito (sin afirmaciones alarmistas)~~ → `gano-chat.js` ✅
- [x] ~~**V-04**: Alerta de wp-file-manager en wp-admin~~ → `gano-security.php` ✅
- [x] ~~Endpoints Wompi webhook + chat en lista blanca REST del MU plugin~~ → `gano-security.php` ✅
- [x] ~~Logging de transacciones Wompi con WC Logger~~ → `gano-wompi-integration.php` ✅

## ✅ Fase 2 — Hardening avanzado aplicado

- [x] ~~**Rate limiting REST chat**: max 20 req/IP/60s con WP transients~~ → `functions.php` ✅
  - Devuelve HTTP 429 con WP_Error serializado
  - Ventana deslizante con transient `_start` para precisión
- [x] ~~**Encriptación clave privada Wompi**: AES-256-CBC + IV aleatorio derivado de wp_salt()~~ → `gano-wompi-integration.php` ✅
  - Prefijo `ENC:` identifica valores cifrados
  - Filtros `pre_update_option` en `gano_wompi_private_key`, `events_key`, `integrity_secret`
  - Retrocompatible: descifra valores sin prefijo como texto plano
- [x] ~~**CSP enforced** (fue Report-Only): directivas para Elementor + WooCommerce + Wompi~~ → `gano-security.php` ✅
  - Agrega: X-XSS-Protection, Referrer-Policy, Permissions-Policy
  - Cambia X-Frame-Options de DENY a SAMEORIGIN
- [x] ~~**CSRF troubleshooter**: `wp_nonce_field()` + `check_admin_referer()` + output escapado~~ → `gano-wompi-troubleshooter.php` ✅
  - Usa claves correctas (`gano_wompi_private_key`) y las descifra
  - URL de API según `gano_wompi_mode` (sandbox/production)
  - Detecta `$order->is_paid()` antes de marcar como pagado

## ✅ Fase 3 — SEO y Performance aplicado

- [x] ~~**Schema JSON-LD Organization+LocalBusiness**: datos de negocio, schema CO~~ → `gano-seo.php` MU plugin ✅
- [x] ~~**Schema JSON-LD WebSite**: SearchAction para Google~~ → `gano-seo.php` ✅
- [x] ~~**Schema JSON-LD Product**: precios en COP para los 4 ecosistemas~~ → `gano-seo.php` ✅
- [x] ~~**Schema JSON-LD FAQPage**: 5 preguntas de hosting frecuentes (mejora CTR)~~ → `gano-seo.php` ✅
- [x] ~~**Schema JSON-LD BreadcrumbList**: automático en páginas internas~~ → `gano-seo.php` ✅
- [x] ~~**Página wp-admin "Gano SEO"**: formulario para configurar NIT, teléfono, dirección, logo~~ → `gano-seo.php` ✅
- [x] ~~**Open Graph + Twitter Card**: fallback si Rank Math no está activo~~ → `gano-seo.php` ✅
- [x] ~~**Resource hints**: preconnect Google Fonts, Wompi, dns-prefetch Analytics~~ → `gano-seo.php` ✅
- [x] ~~**LCP Hero Image preload**: configurable por URL vía wp-admin~~ → `gano-seo.php` ✅
- [x] ~~**Core Web Vitals — Emoji removal**: ~50KB JS eliminados~~ → `functions.php` ✅
- [x] ~~**Core Web Vitals — Head cleanup**: rsd_link, wlwmanifest, wp_generator, shortlink eliminados~~ → `functions.php` ✅
- [x] ~~**Core Web Vitals — Defer JS**: gano-chat-js, gano-quiz-js, shop-animations~~ → `functions.php` ✅
- [x] ~~**Core Web Vitals — LCP JS hook**: MutationObserver establece fetchpriority=high en hero~~ → `functions.php` ✅
- [x] ~~**Template SEO Landing Page**: PHP con schema Service, precios COP, trust signals~~ → `templates/page-seo-landing.php` ✅
- [x] ~~**Landing page "hosting wordpress colombia"**: contenido HTML + instrucciones de despliegue~~ → `seo-pages/hosting-wordpress-colombia.php` ✅
- [x] ~~**Roadmap de 4 keywords adicionales**: vps-colombia, hosting-woocommerce, hosting-barato, hosting-bogota~~ → `seo-pages/hosting-wordpress-colombia.php` ✅

## 🔴 Active — Acción manual requerida en servidor real

- [ ] **[CRÍTICO] Subir archivos corregidos al servidor real** — Los archivos editados (Fases 1-3) están listos:
  - `wp-config.php`
  - `wp-content/mu-plugins/gano-security.php` (CSP enforced + 4 nuevos headers)
  - `wp-content/mu-plugins/gano-seo.php` ← **NUEVO Fase 3**
  - `wp-content/plugins/gano-wompi-integration/gano-wompi-integration.php`
  - `wp-content/plugins/gano-wompi-integration/class-gano-wompi-gateway.php`
  - `wp-content/plugins/gano-wompi-integration/gano-wompi-troubleshooter.php`
  - `wp-content/themes/gano-child/functions.php` (Core Web Vitals Fase 3)
  - `wp-content/themes/gano-child/js/gano-chat.js`
  - `wp-content/themes/gano-child/templates/page-seo-landing.php` ← **NUEVO Fase 3**
  - `wp-content/themes/gano-child/seo-pages/hosting-wordpress-colombia.php` ← **NUEVO Fase 3 (referencia)**

- [ ] **[CRÍTICO] Eliminar wp-file-manager del servidor** — El MU plugin ahora muestra la alerta en wp-admin. Pasos:
  1. Ir a wp-admin → Plugins → Desactivar wp-file-manager
  2. Eliminar vía SFTP: `wp-content/plugins/wp-file-manager/`
  - ⚠️ NO confundir con plugins de fase (gano-phase*)

- [ ] **[CRÍTICO] Configurar claves Wompi en WordPress** — Ir a wp-admin → WooCommerce → Ajustes → Pagos → Wompi y llenar:
  - Public Key (sandbox: `pub_test_...`)
  - Private Key (sandbox: `prv_test_...`)
  - Integrity Secret (sandbox: `test_integrity_...`)
  - Events Key (sandbox: `test_events_...`)
  - Modo: `sandbox` para pruebas
  - ⚠️ Las claves privadas ahora se cifran automáticamente al guardar (AES-256-CBC)

- [ ] **[CRÍTICO] Configurar URL del webhook en dashboard Wompi**
  - Ir a: dashboard.wompi.co → Developers → Webhooks
  - URL a configurar: `https://gano.digital/wp-json/gano-wompi/v1/webhook`

- [ ] **[ALTA] Configurar datos SEO en wp-admin → Ajustes → Gano SEO** — Después de subir los archivos:
  - Nombre legal, NIT, teléfono, WhatsApp, dirección colombiana
  - URL del logo y del hero image (para preload LCP)
  - Validar schema en: https://search.google.com/test/rich-results

- [ ] **[ALTA] Crear landing pages SEO en WordPress** — Crear 1 página con plantilla "SEO Landing Page":
  - URL: /hosting-wordpress-colombia (ver contenido en `seo-pages/hosting-wordpress-colombia.php`)
  - Configurar campos personalizados según instrucciones del archivo
  - Validar con Rank Math antes de publicar
  - Páginas siguientes: /vps-colombia, /hosting-woocommerce-colombia, /hosting-barato-colombia

- [ ] **[ALTA] Configurar Google Search Console**
  - Ir a: https://search.google.com/search-console
  - Añadir propiedad: https://gano.digital
  - Método de verificación recomendado: HTML tag (Rank Math lo hace automáticamente)
  - Enviar sitemap: https://gano.digital/sitemap_index.xml

- [ ] **[ALTA] Configurar Rank Math en WordPress**
  - Ir a: wp-admin → Rank Math → Setup Wizard
  - Activar módulo WooCommerce (para schema de productos)
  - Activar módulo Local SEO → Poner dirección Bogotá, teléfono COP, NIT
  - Sitemap: incluir productos + páginas, excluir tags/archivos

- [ ] **[CRÍTICO] Probar transacción Wompi de extremo a extremo en sandbox**
  - Ver datos de prueba: https://docs.wompi.co/en/docs/colombia/datos-de-prueba-en-sandbox/
  - Verificar: orden creada → redirigido a Wompi → pago aprobado → webhook llega → orden marcada como pagada

## 🟡 Pending — Contenido y credibilidad

- [ ] **[ALTA] Reemplazar todo el Lorem ipsum y texto placeholder** — Requiere acceso al panel de Elementor
  - Skill: `.gano-skills/gano-content-audit/` (ver copy propuesto)
  - Páginas: Home, Nosotros, Contacto, Servicios

- [ ] **[ALTA] Actualizar datos de contacto** — ⚠️ Diego debe confirmar primero:
  - Dirección real en Colombia
  - Teléfono colombiano (+57)
  - Número WhatsApp Business
  - Skill: `.gano-skills/gano-content-audit/`

- [ ] **[ALTA] Eliminar testimonios falsos** — Sección con "Sony CEO" y personas ficticias

- [ ] **[ALTA] Eliminar estadísticas falsas** — "Customers Worldwide / Projects Done / Datacenters"

- [ ] **[ALTA] Crear página Nosotros real** — Ver estructura en `.gano-skills/gano-content-audit/SKILL.md`

- [ ] **[ALTA] Reescribir hero con propuesta de valor** — Copy listo en `.gano-skills/gano-content-audit/SKILL.md`

- [ ] **[MEDIA] Habilitar 2FA en wp-admin** — Usar Wordfence 2FA (plugin ya instalado)
  - Ir a: wp-admin → Wordfence → Login Security → 2FA
  - Activar para todos los roles `administrator`

- [ ] **[MEDIA] Reparar navegación duplicada** — Auditar secciones Elementor con header repetido

- [ ] **[MEDIA] Verificar y ejecutar plugins de fase (1, 2, 3, 6, 7)** — Activar en WordPress, confirmar resultado, luego eliminar
  - ⚠️ NO eliminar sin confirmar ejecución exitosa de cada fase
  - Ver: `memory/notes/plugins-de-fase.md`

- [ ] **[MEDIA] Configurar Rank Math SEO** — Metadata, schema Organization/Product/LocalBusiness, sitemap

- [ ] **[MEDIA] CSP: migrar de unsafe-inline a nonces** — Fase 3+
  - El CSP actual funciona y es enforced, pero aún permite unsafe-inline para Elementor
  - Migración requiere soporte de nonces en Elementor (Pro feature o plugin adicional)

## ⏳ Waiting On — Requiere datos de Diego

- [ ] **Datos de contacto reales** — Dirección colombiana, teléfono, WhatsApp Business, NIT/razón social

- [ ] **Decisión billing panel** — WHMCS vs Blesta (recomendado: Blesta). Contactar ESTUPENDO para módulo DIAN

- [ ] **Credenciales Wompi producción** — Cuando active la cuenta real: pub_prod_, prv_prod_, prod_integrity_, prod_events_

## 📋 Fase 4 — Plataforma Real de Hosting (investigación completa en `memory/research/fase4-plataforma.md`)

### Semana 1 — Billing + Pagos
- [ ] **[CRÍTICO] Comprar WHMCS Plus** ($34.95/mes) — No Blesta por ahora (no tiene módulo Wompi)
  - URL: https://www.whmcs.com/pricing/
- [ ] **Instalar WHMCS** en my.gano.digital (servidor independiente del WordPress)
- [ ] **Instalar módulo Wompi Widget & Web Checkout** para WHMCS
  - URL: https://marketplace.whmcs.com/product/8212-wompi-widget-web-checkout
  - Actualizado Oct 2025, compatible WHMCS 8.13, soporte colombiano incluido
- [ ] **Contactar ESTUPENDO** para facturación electrónica DIAN
  - Ismary Lara: ismary.lara@estupendo.com.co | +57 3132323588
  - Módulo: https://marketplace.whmcs.com/product/5744-estupendo-facturacion-electronica-colombia
  - ⚠️ El proceso DIAN puede tomar 2-4 semanas — iniciar PRIMERO

### Semana 2 — Dominios + Soporte + Status
- [ ] **Crear cuenta ResellerClub** para dominios (750+ TLDs, .co incluido)
  - Depositar mínimo ~$50 USD, configurar en WHMCS → Registradores de dominios
- [ ] **Instalar FreeScout** en support.gano.digital (gratis, open source)
  - GitHub: https://github.com/freescout-help-desk/freescout
  - Módulo WHMCS: https://github.com/LJPc-solutions/freescout-whmcs-module
- [ ] **Desplegar Upptime** en status.gano.digital (GitHub Pages, $0)
  - Repo: https://github.com/upptime/upptime
  - Setup ~30 minutos — solo configurar URLs a monitorear y CNAME en DNS

### Semana 3 — WhatsApp + VPS
- [ ] **Registrar Meta Business Account** + WhatsApp Business Account (WABA)
  - Costo notificaciones: ~$0.06/mes por 100 clientes (utility messages Colombia)
  - Marketing messages: $0.02/msg (solo si se usa para promos)
- [ ] **Instalar CloudLinkd** módulo WHMCS WhatsApp
  - GitHub: https://github.com/cloudlinkd-networks/WHMCS-WhatsApp-Notification
  - Activamente mantenido (último update Mayo 2025)
- [ ] **Configurar Hetzner** como proveedor VPS + módulo Caasify (gratis, multi-provider)
  - Hetzner CX22: €3.79/mes (2vCPU, 4GB, 40GB NVMe) → vender a $80.000-100.000 COP

### Semana 4 — Testing y Go-Live
- [ ] End-to-end: nueva contratación → provisión → factura → notificación WhatsApp
- [ ] Proceso DIAN con ESTUPENDO (puede estar en curso desde semana 1)
- [ ] Publicar página de precios en WHMCS integrada con gano.digital

### Costo estimado Fase 4
| Componente | Costo mensual |
|-----------|---------------|
| WHMCS Plus | $34.95 USD |
| ESTUPENDO DIAN | Por confirmar con Ismary |
| FreeScout | $0 |
| Upptime | $0 |
| WhatsApp (100 clientes) | ~$0.06 USD |
| **TOTAL base** | **~$35 USD/mes** |

## 📋 Someday — Fase 5

- [ ] Blesta licencia one-time ($350) — migrar cuando tenga módulo Wompi o cuando escale
- [ ] Cachet (status page self-hosted, más control que Upptime)
- [ ] Conectar Chat IA a LLM real (n8n / Make / API propia) — Fase 5
- [ ] CDN integrado (Cloudflare/BunnyCDN)
- [ ] Backup cloud automático (R2 o B2)
- [ ] API pública de Gano
- [ ] Programa de resellers
- [ ] Marketplace de plugins WordPress
