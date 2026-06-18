# Templates — gano-child

Mapa de templates activos y páginas que sirven.

## Templates activos en producción

| Template | Página activa | ID | URL |
|----------|--------------|-----|-----|
| `page-ecosistemas-v3.php` | Catálogo Reseller | 1776 | `/catalogo/` |
| `page-nosotros.php` | Nuestra Filosofía | 1658 | `/nosotros/` |
| `page-contacto.php` | Contacto | 1662 | `/contacto/` |
| `page-hosting.php` | Ecosistemas Infraestructura | 1660 | `/hosting/` |
| `page-dominios.php` | Registro de Dominios | 1659 | `/dominios/` |
| `page-servicios.php` | Blindaje y Optimización | 1661 | `/servicios/` |
| `page-sla.php` | Acuerdo de Nivel de Servicio | 1940 | `/acuerdo-de-nivel-de-servicio/` |
| `page-privacidad.php` | Política de Privacidad | 1939 | `/politica-de-privacidad/` |
| `page-terminos.php` | Términos y Condiciones | 1672 | `/terminos-y-condiciones/` |
| `page-seo-landing.php` | SEO landing | — | `/hosting-wordpress-colombia/` |
| `page-comenzar-aqui.php` | Comenzar aquí | — | `/comenzar-aqui/` |
| `page-contacto-gracias.php` | Gracias por contactar | — | `/contacto/gracias/` |

## Templates deprecados

Movidos a `deprecated/` — no asignados a ninguna página activa. No borrar: pueden
reinstalarse desde git si se necesitan.

| Template | Razón |
|----------|-------|
| `deprecated/page-dashboard-demo.php` | Demo sin página asignada |
| `deprecated/page-diagnostico-digital.php` | Herramienta diagnóstico sin uso activo |
| `deprecated/page-showcase.php` | Showcase sin contenido publicado |
| `deprecated/page-sota-hub.php` | Hub SOTA reemplazado por `/catalogo-sota/` |
| `deprecated/shop-premium.php` | Shop premium sin uso (checkout vía Reseller Store) |
| `deprecated/sota-single-template.php` | Template individual SOTA sin asignación activa |
| `deprecated/homepage-2026-preview.html` | Preview HTML estático de homepage (2026) |

## Historial

| Fecha | Cambio |
|-------|--------|
| 2026-06-17 | `page-ecosistemas.php` → `archive/page-ecosistemas-v1.php` (PR #305) |
| 2026-06-17 | `page-ecosistemas-v3.php` asignado como template canónico de `/catalogo/` |
| 2026-06-17 | 7 templates huérfanos movidos a `deprecated/` (Wave C) |
