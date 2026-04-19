# Convergencia Mockups + Producción — 2026-04-17

Implementación ejecutada sobre el plan maestro de convergencia integral (all-at-once) con catálogo canónico Reseller, navegación inteligente, comparador, hardening WCAG 2.2 y secuencia de QA/workflows.

## Entregado en código

- **Design system unificado** (tokens semánticos + menos duplicación runtime):
  - `wp-content/themes/gano-child/style.css`
  - `wp-content/themes/gano-child/css/homepage.css`
  - `wp-content/themes/gano-child/css/gano-sota-convergence.css`
  - `wp-content/themes/gano-child/functions.php` (critical css)

- **Core catálogo Reseller hardening**:
  - nuevo estado `sync-missing`
  - validación de precio canónico (`gano_catalog_price_is_valid`)
  - resolución robusta de estado (`gano_catalog_product_status`)
  - fallback CTA `Ver detalles`
  - modos inteligentes (`gano_get_catalog_nav_modes`, `gano_get_catalog_guided_intents`)

- **UX comercial inteligente** (sitewide para plantillas comerciales):
  - `wp-content/themes/gano-child/js/gano-catalog-intelligence.js`
  - `wp-content/themes/gano-child/css/gano-catalog-intelligence.css`
  - enqueue + localize en `functions.php`
  - modos `grid` / `family` / `guided`
  - comparador 2-3 productos con persistencia en sesión
  - eventos analytics de embudo (modo, filtros, comparador, CTA)
  - navegación móvil full-screen con CTA contextual

- **Plantillas convergidas**:
  - `front-page.php`
  - `templates/shop-premium.php`
  - `templates/page-ecosistemas.php`
  - `templates/page-seo-landing.php` (ahora orientada a catálogo canónico Reseller)
  - `templates/page-nosotros.php`
  - `templates/page-contacto.php`

- **Accesibilidad WCAG 2.2 aplicada**:
  - `scroll-padding-top` + `scroll-margin-top` para evitar foco oculto por sticky header
  - baseline de target size para controles interactivos

- **QA/release sequencing**:
  - script `scripts/qa_catalog_release.py`
  - workflow `.github/workflows/32-commerce-release-sequence.yml`
  - actualización de catálogo de workflows en `.github/workflows/README.md`

## Verificaciones ejecutadas

- `python scripts/qa_catalog_release.py --gate uiux` ✅
- `python scripts/qa_catalog_release.py --gate content` ✅
- `php -l` sobre archivos PHP tocados ✅

## Nota operativa

- El árbol contiene `external/` sin trackear (preexistente).
- Cambios listos para revisión/commit por bloque (UI system, catálogo, workflows).
