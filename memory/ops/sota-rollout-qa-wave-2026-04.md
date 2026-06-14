# QA integral + despliegue por olas (SOTA)

Fecha: 2026-04-11

## Validaciones ejecutadas en código

- [x] `php -l wp-content/themes/gano-child/functions.php`
- [x] `php -l wp-content/themes/gano-child/templates/page-seo-landing.php`
- [x] `php -l wp-content/themes/gano-child/templates/page-nosotros.php`
- [x] `php -l wp-content/themes/gano-child/templates/page-sota-hub.php`
- [x] `php -l wp-content/themes/gano-child/templates/shop-premium.php`
- [x] `php -l wp-content/themes/gano-child/templates/page-diagnostico-digital.php`
- [x] `php -l wp-content/themes/gano-child/templates/page-ecosistemas.php`

## Checklist QA manual (staging)

### Visual / responsive

- [ ] Home Elementor aplica clases canónicas `gano-sota-*` sin overrides conflictivos.
- [ ] `shop-premium` filtra por categoría y mantiene contraste en mobile.
- [ ] `ecosistemas` conserva jerarquía de cards y CTA final consistente.
- [ ] `contacto` mantiene layout de 2 columnas en desktop y 1 columna en móvil.
- [ ] `diagnostico-digital` muestra flujo completo de 6 preguntas y resultado.
- [ ] `sota-hub` muestra tarjetas draft/publish con estilos consistentes.

### Comercial / Reseller

- [ ] Productos `active` abren carrito Reseller con `plid` y `items` correctos.
- [ ] Productos `pending` redirigen a `/contacto/`.
- [ ] `domain_search` redirige a `/dominios/`.
- [ ] Validar PFIDs reales en RCC antes de activar lanzamiento comercial.

### A11y / performance

- [ ] Navegación por teclado en botones de quiz y CTAs.
- [ ] `prefers-reduced-motion` respeta reducción en componentes SOTA.
- [ ] LCP de hero en home y páginas clave sin regresión visible.
- [ ] Revisar peso de imágenes nuevas (hero y miniaturas) antes de producción.

## Plan de despliegue por olas

### Ola 1 — Base visual + contenido estructural

- `style.css` (design system SOTA + templates).
- `page-nosotros.php`, `page-sota-hub.php`, `page-seo-landing.php`.
- Publicar en staging y validar visual con checklist.

### Ola 2 — Comercial y catálogo

- `functions.php` (catálogo + resolver CTA por estado).
- `shop-premium.php` + verificación RCC.
- Smoke test comercial (active/pending/domain search).

### Ola 3 — Diagnóstico y ajuste final

- Publicar `page-diagnostico-digital.php`.
- Ajustar copy/CTA según resultados de QA en staging.
- Aprobación final antes de promover a producción.
