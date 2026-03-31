# Proyecto: Gano Digital

**URL**: https://gano.digital
**Estado**: En desarrollo activo — pre-lanzamiento comercial
**Última sesión**: Marzo 18, 2026

## Qué es
Proveedor de hosting WordPress de alto rendimiento y seguridad empresarial
orientado al mercado colombiano y latinoamericano. Modelo de negocio: venta de
ecosistemas de hosting (planes) con WooCommerce + Wompi Colombia.

## Stack tecnológico completo
- WordPress 6.x + Elementor + Royal Elementor Addons
- WooCommerce 8.x (moneda COP, zona Bogotá/Colombia)
- Wompi Colombia — pasarela PSE, Tarjetas, Nequi (Bancolombia)
- Wordfence — WAF y escaneo
- Rank Math SEO
- Tema: Hello Elementor (padre) → gano-child (hijo)
- MU Plugin: gano-security.php — hardening base
- Chat IA: gano-chat.js (captura leads nombre+WhatsApp)
- Quiz: gano-sovereignty-quiz.js (diagnóstico de soberanía digital)
- GoDaddy Reseller Store — buscador de dominios
- git-deployer-app — despliegue continuo vía Git

## Catálogo de productos (ecosistemas)

| SKU | Nombre | Setup COP | Mensual COP |
|-----|--------|-----------|-------------|
| GD-STARTUP-01 | Startup Blueprint | $796.000 | $196.000 |
| GD-BASIC-01 | Ecosistema Básico | $4.000.000 | $600.000 |
| GD-ADVANCED-01 | Ecosistema Avanzado | $10.000.000 | $1.200.000 |
| GD-SOBERANIA-01 | Soberanía Digital | $20.000.000 | $2.000.000 |

## Plugins de instalación (fases)
Estos plugins despliegan el sitio. NO eliminar sin confirmar ejecución:
- gano-phase1-installer.php — Instalación base
- gano-phase2-business.php — Configuración de negocio
- gano-phase3-content.php — Contenido inicial
- gano-phase6-catalog.php — Catálogo de productos WooCommerce
- gano-phase7-activator.php — Activador de funcionalidades

## Roadmap — 5 Fases

### Fase 1 — Reparación y Credibilidad (Semanas 1-3)
**Objetivo**: Sitio profesional y creíble. Sin esto, ningún marketing funciona.
Tareas críticas:
- Desactivar WP_DEBUG en producción
- Eliminar wp-file-manager (riesgo RCE)
- Reemplazar todo el Lorem ipsum
- Datos de contacto reales colombianos
- Eliminar testimonios y estadísticas falsas
- Reparar navegación duplicada
- Reescribir hero con propuesta de valor

### Fase 2 — Seguridad y Pagos (Semanas 3-5)
**Objetivo**: Asegurar stack de pagos antes de transacciones reales.
Tareas críticas:
- Implementar verificación HMAC-SHA256 en webhooks Wompi
- Eliminar clave Wompi hardcodeada del código
- Agregar nonce CSRF al endpoint REST del chat IA
- 2FA en wp-admin (Wordfence 2FA — ya instalado)
- Prueba completa de pago en sandbox y producción

### Fase 3 — SEO y Performance (Semanas 5-7)
**Objetivo**: Posicionamiento orgánico en Google Colombia.
- Configurar Rank Math (metadata, schema, sitemap)
- Schema JSON-LD: Organization, Product, LocalBusiness
- Landing pages SEO: "hosting wordpress colombia", "vps colombia", etc.
- Core Web Vitals: LCP < 2.5s
- Google Search Console

### Fase 4 — Plataforma Real de Hosting (Semanas 8-16)
**Objetivo**: Provisión y gestión real de servicios.
- Instalar WHMCS o Blesta (recomendado: Blesta por precio/seguridad)
- Módulo Wompi para WHMCS (disponible en marketplace)
- Facturación electrónica DIAN (módulo ESTUPENDO)
- Portal de cliente (my.gano.digital)
- Sistema de tickets (support.gano.digital)
- API de dominios (ResellerClub — nativo en WHMCS)
- Provisión VPS (DigitalOcean/Hetzner/Proxmox)
- WhatsApp Business API para notificaciones
- StatusPage público (status.gano.digital)

### Fase 5 — Crecimiento y SaaS (Mes 5+)
- Marketplace de plugins
- Site builder integrado
- CDN integrado (Cloudflare/BunnyCDN)
- Backup cloud automático
- API pública de Gano
- Programa de resellers

## Investigación completada (Estado del arte Marzo 2026)

### Billing Panel
- **Recomendado**: Blesta ($350 one-time, sin caps de clientes, mejor seguridad)
- **Alternativa**: WHMCS ($360/año, ecosistema más grande)
- Wompi para WHMCS: módulo oficial en marketplace (actualizado Oct 2025, v8.13+)
- DIAN: Módulo ESTUPENDO (contacto: ismary.lara@estupendo.com.co, +57 3132323588)
- Dominios: ResellerClub (nativo WHMCS, más completo)
- VPS DO: ModulesGarden module (actualizado Abr 2025)
- VPS Hetzner: Caasify free module (DO+Hetzner+Vultr+Linode)
- WhatsApp: WA Client module (~$20/mes) o GitHub CloudLinkd (gratis, self-hosted)

### Seguridad WordPress 2026
- WP_DEBUG = false es CRÍTICO en producción
- Webhooks: siempre verificar HMAC con hash_equals() (timing-safe)
- 2FA: usar Wordfence 2FA o WP 2FA (no "Two Factor Authentication via Email" — CVE-2025-13587)
- MU-plugins: monitorear, son vector de ataque activo en 2025-2026
- 19.2 billones de intentos de brute force en Q3 2025

### SEO Colombia 2026
- Keywords principales: "hosting barato colombia", "hosting wordpress colombia", "vps bogota"
- Schema crítico: Organization (NIT, +57 phone), Product (precio COP), LocalBusiness
- Core Web Vitals: NO lazy-load hero (usa fetchpriority="high"), WebP < 150KB
- Local SEO: Google Business Profile + Páginas Amarillas + Cylex Colombia
- Rank Math: configurar WooCommerce module + schema validation

### Wompi Colombia 2026
- Firma webhook: SHA256 dinámico (properties array varía por evento)
- Header: X-Event-Checksum
- Plugin WooCommerce oficial: payment-integration-wompi v4.0.1 (WP.org)
- Claves: almacenar en .env o wp_options cifrado, nunca hardcodeado
- Ambientes completamente separados (sandbox ≠ producción, datos no se transfieren)
