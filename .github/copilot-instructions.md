# Gano Digital — GitHub Copilot Instructions

Este archivo da contexto a GitHub Copilot para todo el proyecto.

Ref: https://docs.github.com/en/copilot/customizing-copilot/adding-repository-instructions-for-github-copilot

## Proyecto

**Gano Digital** es una plataforma de hosting WordPress para el mercado colombiano.

URL pública: https://gano.digital

## Coordinación GitHub ↔ servidor ↔ local

Antes de asumir que el repo es idéntico a producción, lee **[`.github/DEV-COORDINATION.md`](DEV-COORDINATION.md)**: fuentes de verdad (`TASKS.md`, `memory/`), qué vive en el servidor (BD Elementor, uploads) frente a git, y cómo reportar drift con la plantilla **Reporte de sincronización**. No inventes estado de servidor si no está documentado en issues o en `TASKS.md`.

## Stack tecnológico

- **CMS**: WordPress 6.x + Elementor Pro + Royal Elementor Addons
- **E-commerce**: WooCommerce (moneda COP, zona Bogotá)
- **Pagos**: Wompi Colombia (PSE, Nequi, Daviplata, Tarjetas)
- **Seguridad**: Wordfence + MU Plugin `gano-security.php`
- **SEO**: Rank Math + MU Plugin `gano-seo.php`
- **Tema**: Hello Elementor → `gano-child` (child theme)
- **Animaciones**: GSAP 3 + ScrollTrigger + IntersectionObserver

## Convenciones de código

### PHP (WordPress)

- Prefijo de función obligatorio: `gano_` (ej: `gano_enqueue_styles()`)
- Sanitizar inputs: `sanitize_text_field()`, `wp_kses_post()`, `absint()`
- Escapar outputs: `esc_html()`, `esc_url()`, `esc_attr()`
- Nonces en formularios: `wp_nonce_field()` + `check_admin_referer()`
- Prepared queries: SIEMPRE `$wpdb->prepare()`, nunca SQL crudo
- Idioma: comentarios y strings visibles en **es-CO**

### CSS (Vanilla CSS + variables)

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

- GSAP 3 ya cargado globalmente vía `wp_enqueue_script('gsap-js')`
- ScrollTrigger registrado: `gsap.registerPlugin(ScrollTrigger)`
- Respetar `prefers-reduced-motion` antes de animar
- Vanilla JS preferido sobre jQuery para código nuevo
- IntersectionObserver para scroll reveals (`scroll-reveal.js`)

## Arquitectura de archivos clave

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

## Flujo de desarrollo (GSD + Copilot)

Este proyecto usa **get-shit-done (GSD)** como sistema de workflow `.gsd/`.
Los agentes GSD están disponibles para: planning, debugging, UI audit, verification.

1. Cambios de desarrollo → rama `feature/nombre` u `ops/...`
2. PR a `main` → GitHub Actions ejecutan TruffleHog y `php -l` sobre código custom Gano
3. Merge a `main` → despliegue según tu pipeline (no commitear credenciales de despliegue)

## Reglas de seguridad (refuerzo)

- **NUNCA** commitear credenciales, tokens, claves API, contraseñas de BD ni rutas absolutas con usuario de hosting.
- Scripts SSH o de despliegue: credenciales solo por variables de entorno o secretos de CI.
- `wp-config.php` no va al repositorio. El helper `ssh_cli.py` en repo usa **solo variables de entorno** (`GANO_SSH_*`).
- Revisar con especial cuidado cambios en `wp-content/mu-plugins/` y `gano-wompi-integration/`.
- `WP_DEBUG` debe estar en `false` en producción.
- Rate limiting en endpoints REST: máx 20 req/IP/60s (según hardening existente).

## Plugins de fase (instaladores)

No eliminar ni desactivar de forma permanente los plugins `gano-phase*` hasta confirmar que su contenido ya está aplicado en WordPress. Son instaladores del sitio.

## Prioridad homepage / Elementor (alineado al fixplan)

1. Menú principal asignado a la ubicación `primary` (nav_menu_locations).
2. Hero: imagen y textos enlazados correctamente en datos de Elementor.
3. Pilares: sustituir Lorem y placeholders por copy e iconografía final.
4. Ajustes de layout (z-index, CTA, páginas en borrador si aplica).

## Integración continua y agentes en GitHub

- Workflows: `secret-scan.yml` (TruffleHog), `php-lint-gano.yml`, `seed-homepage-issues.yml` (fixplan homepage), **`seed-copilot-queue.yml`** (cola masiva desde `agent-queue/tasks.json`).
- **Offloading:** ejecuta **Seed Copilot task queue** en Actions (ámbito `all` o parcial), luego asigna **GitHub Copilot** en cada issue. Guía: [`.github/COPILOT-AGENT-QUEUE.md`](COPILOT-AGENT-QUEUE.md).
- Plantilla **Copilot task queue** para tareas sueltas; la cola JSON para lotes versionados.

## Narrativa y marca (High-Ticket)

Tono **"Manifiesto Técnico"** (autoritario, visionario, sofisticado y soberano).

- **Nomenclatura de ecosistemas**: Núcleo Prime, Fortaleza Delta, Bastión SOTA.
- **Prohibiciones**: evitar lenguaje de "barato", "hosting compartido" o comparativas de bajo costo; el foco es inversión en soberanía.
- **Conceptos**: Soberanía digital, Zero-Trust, Edge/NVMe, IA soberana para operación.

## Contexto colombiano

- **Moneda**: COP
- **Zona horaria**: America/Bogota (UTC-5)
- **Cumplimiento**: GDPR + normativa SIC Colombia + DIAN facturación (roadmap)
- **Idioma**: es-CO
- **Checkout**: Wompi (integración personalizada)
