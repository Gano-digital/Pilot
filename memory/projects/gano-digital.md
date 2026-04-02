# Proyecto: Gano Digital

**URL**: https://gano.digital
**Estado**: En desarrollo activo — integración SOTA Mockup
**Última sesión**: Abril 2026

## Qué es
Proveedor de ecosistemas de hosting de alto rendimiento (SOTA - State of the Art) orientado al mercado colombiano y latinoamericano.

**Modelo de negocio actualizado**: Marca blanca basada en el programa GoDaddy Reseller. GoDaddy maneja el inventario, aprovisionamiento, facturación y pagos de los clientes (carrito de marca blanca). Gano Digital actúa como la vitrina de alto impacto (Kinetic Monolith) y orquesta el marketing/branding SOTA.

## Stack tecnológico completo
- WordPress 6.x + Elementor + Royal Elementor Addons
- GoDaddy Reseller Store Plugin — Importador de catálogo y redirección al carrito
- GSAP + ScrollTrigger — Animaciones de front-end (Kinetic Monolith)
- Tema: Hello Elementor (padre) → gano-child (hijo)
- MU Plugin: gano-security.php — Hardening base y CSP configurado
- MU Plugin: gano-seo.php — Core SEO (Schema.org de Negocio Digital y JSON-LD)
- Herramientas: gano-chat.js, gano-sovereignty-quiz.js
- git-deployer-app — despliegue continuo vía Git

## Nomenclatura Actualizada (Ecosistemas)
- **Núcleo Prime** (Hosting Inicial / Startup)
- **Fortaleza Delta** (Ecosistema Básico/Avanzado)
- **Bastión SOTA** (Soberanía Digital / Alto Rendimiento)

## Roadmap — Resumen de Fases

### Fase 1 — Hardening de Código y Seguridad
- WP_DEBUG=false.
- Eliminación de dependencias de Wompi (ahora delegadas al checkout global de GoDaddy).
- Desactivación de wp-file-manager y limpieza general.

### Fase 2 — Protección y Prevención CSRF
- Rate limiting en endpoints.
- Content Security Policy (CSP) en enforced mode (ajustado sin Wompi domains).
- Cookies con flags Secure, HttpOnly, SameSite=Strict.

### Fase 3 — SEO y Optimizaciones Frontend
- Configuración Schema JSON-LD para Organización 100% Digital.
- Optimizaciones Core Web Vitals (Defer scripts, preloads de Hero Image).
- Plantilla *Kinetic Monolith* y 20 Pilares SOTA generados.

### Fase 4 — Integración Reseller de GoDaddy (NUEVA ESTRATEGIA)
- Evitar instalar pasarelas complejas locales (WHMCS + Wompi etc).
- Mantener los CTAs del SOTA Mockup mapeando correctamente al `rstore_cart_button` o APIs del Reseller.
- Completar la delegación del flujo de compras directo en el backend y carrito de GoDaddy.

## Decisiones Críticas Recientes (Abril 2026)
- **No se usará Wompi ni MercadoPago** locales. El Reseller Store gestiona y procesa todos los pagos y facturas a nivel sistémico para las suscripciones de los ecosistemas. Se eliminaron los plugins y repos integrados para Wompi.
- El sitio mantendrá perfil 100% digital (sin direcciones proxy) para el SEO local.
- Se unificó el catálogo en 48 verticales utilizando la plantilla `GANO-SOTA-MOCKUP.html` directamente en la vista `shop-premium.php`.
