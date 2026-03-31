# Gano Digital — GitHub Copilot Instructions
# Este archivo da contexto a GitHub Copilot para todo el proyecto.
# Ref: https://docs.github.com/en/copilot/customizing-copilot/adding-repository-instructions-for-github-copilot

## Proyecto

**Gano Digital** es una plataforma de hosting WordPress para el mercado colombiano.
URL: https://gano.digital | Servidor: 72.167.102.145

## Stack Tecnológico

- **CMS**: WordPress 6.x + Elementor Pro + Royal Elementor Addons
- **E-commerce**: WooCommerce (moneda COP, zona Bogotá)
- **Pagos**: Wompi Colombia (PSE, Nequi, Daviplata, Tarjetas)
- **Seguridad**: Wordfence + MU Plugin `gano-security.php`
- **SEO**: Rank Math + MU Plugin `gano-seo.php`
- **Tema**: Hello Elementor → `gano-child` (child theme)
- **Animaciones**: GSAP 3 + ScrollTrigger + IntersectionObserver

## Convenciones de Código

### PHP (WordPress)
- Prefijo de función obligatorio: `gano_` (ej: `gano_enqueue_styles()`)
- Sanitizar inputs: `sanitize_text_field()`, `wp_kses_post()`, `absint()`
- Escapar outputs: `esc_html()`, `esc_url()`, `esc_attr()`
- Nonces en formularios: `wp_nonce_field()` + `check_admin_referer()`
- Prepared queries: SIEMPRE `$wpdb->prepare()`, nunca SQL crudo
- Idioma: comentarios y strings visibles en **es-CO**

### CSS (Vanilla CSS + Variables)
- Variables de color ya definidas en `:root` de `style.css`:
  - `--gano-blue: #1B4FD8` — Azul corporativo
  - `--gano-green: #00C26B` — Verde digital
  - `--gano-orange: #FF6B35` — CTA principal
  - `--gano-gold: #D4AF37` — Acento SOTA / Kinetic Monolith
  - `--gano-dark: #0F1923` — Fondo nocturno
- Tipografía: `'Plus Jakarta Sans'` (headings), `'Inter'` (body)
- NO usar Tailwind CSS. Solo Vanilla CSS + variables.
- Glassmorphism: `backdrop-filter: blur(24px) saturate(180%)`

### JavaScript
- GSAP 3 ya cargado globalmente via `wp_enqueue_script('gsap-js')`
- ScrollTrigger registrado: `gsap.registerPlugin(ScrollTrigger)`
- Respetar `prefers-reduced-motion` antes de animar
- Vanilla JS preferido sobre jQuery para código nuevo
- IntersectionObserver para scroll reveals (`scroll-reveal.js`)

## Arquitectura de Archivos Clave

```
wp-content/
├── themes/gano-child/
│   ├── style.css              ← Design tokens + Glassmorphism 2.0
│   ├── functions.php          ← Enqueue GSAP, SOTA FX, quiz
│   ├── js/
│   │   ├── gano-sota-fx.js    ← GSAP animations (magnetic, parallax)
│   │   └── scroll-reveal.js   ← IntersectionObserver reveals
│   └── templates/
│       └── sota-single-template.php  ← Template páginas SOTA
├── plugins/
│   ├── gano-content-importer/ ← 20 páginas SOTA (activar 1 vez)
│   ├── gano-phase3-content/   ← Builder de páginas principales
│   └── gano-wompi-integration/← Gateway de pago Wompi
└── mu-plugins/
    ├── gano-security.php      ← CSP + headers + rate limiting
    └── gano-seo.php           ← Schema JSON-LD + Open Graph
```

## Flujo de Desarrollo (GSD + Copilot)

Este proyecto usa **get-shit-done (GSD)** como sistema de workflow `.gsd/`.
Los agentes GSD están disponibles para: planning, debugging, UI audit, verification.

1. Cambios de desarrollo → rama `feature/nombre`
2. PR a `main` → GitHub Actions ejecuta security scan
3. Merge a `main` → Auto-deploy vía rsync al servidor

## Reglas de Seguridad

- **NUNCA** dejar credenciales en texto plano en el código
- Contraseñas y API keys → GitHub Secrets / `wp-options` cifradas
- `wp-config.php` y `ssh_cli.py` están en `.gitignore`
- `WP_DEBUG` debe estar en `false` en producción
- Rate limiting en endpoints REST: máx 20 req/IP/60s

## Contexto Colombiano

- Moneda: COP (pesos colombianos)
- Zona horaria: America/Bogota (UTC-5)
- Cumplimiento: GDPR + normativa SIC Colombia + DIAN facturación
- Idioma: es-CO (español colombiano formal)
- Checkout: Wompi (no Stripe, no PayPal para Colombia)
