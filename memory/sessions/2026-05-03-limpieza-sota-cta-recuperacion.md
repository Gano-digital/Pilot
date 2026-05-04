# Limpieza SOTA masiva + recuperación de CTA + alineación repo-servidor

Fecha: 2026-05-03
Entorno: `gano-godaddy` (`/home/f1rml03th382/public_html/gano.digital`)
Repo: `Gano-digital/Pilot` @ `main`

## Objetivo

1. Eliminar todo rastro de branding SOTA del sitio (copy-visible, DB, JS, PHP, HTML).
2. Recuperar URLs rotas causadas por la eliminación de páginas SOTA legacy.
3. Corregir botones CTA desconectados que apuntaban a URLs inexistentes.
4. Alinear repositorio local con producción.

## Contexto de entrada

- Sesión anterior (Claude, 2026-05-03) recreó 4 páginas SOTA legacy (IDs 1978-1981) que capturaban URLs y rompían redirects 301.
- ~50 páginas en trash creadas por Claude el 2026-04-28.
- 6 posts de blog del 2026-04-18 con branding SOTA en contenido.
- 20 landing pages SOTA del 2026-04-22 con `class="gano-sota-page"`.
- JS files (`gano-nav.js`, `diagnostico.js`, `catalog-gano-v2.js`) usando IDs legacy.
- `wp-config.php` legible (644) con password expuesta.
- `.htaccess` perdió redirects legacy al ser regenerado por WordPress.
- `functions.php` no contenía bloque de redirects legacy (versión en servidor diferente a la documentada en contexto anterior).

## Hallazgos clave

### 1. Estructura de URLs objetivo (limpia)

| Plan | URL | Estado |
|---|---|---|
| WordPress Básico | `/producto/wordpress-basic/` | 200 |
| WordPress Deluxe | `/producto/wordpress-deluxe/` | 200 |
| WordPress Ultimate | `/producto/wordpress-ultimate/` | 200 |
| cPanel Ultimate | `/producto/cpanel-ultimate/` | 200 |
| Catálogo | `/catalogo/` | 200 |
| Ecosistemas | `/ecosistemas/` | 200 |
| Contacto | `/contacto/` | 200 |
| Dominios | `/dominios/` | 200 |

### 2. URLs legacy que deben redirigir

| URL legacy | Redirect 301 a | Estado final |
|---|---|---|
| `/nucleo-prime/` | `/producto/wordpress-basic/` | 200 |
| `/fortaleza-delta/` | `/producto/wordpress-deluxe/` | 200 |
| `/bastion-sota/` | `/producto/wordpress-ultimate/` | 200 |
| `/soberania-total/` | `/producto/cpanel-ultimate/` | 200 |
| `/sota-hub/` | `/ecosistemas/` | 200 |
| `/hosting/` | `/ecosistemas/` | 200 |
| `/catalogo-sota/` | `/catalogo/` | 200 |

### 3. URLs rotas encontradas (CTA desconectados)

| URL rota | Problema | Solución |
|---|---|---|
| `/privacidad/` | Página no existe | Redirect 301 → `/politica-de-privacidad/` |
| `/sla/` | Página existe (ID 1974) pero devuelve 404 (vacía) | Redirect 301 → `/terminos-y-condiciones/` |
| `/terminos/` | Página no existe | Redirect 301 → `/terminos-y-condiciones/` |
| `/shop-premium/` | Página en draft (ID 1754) | Redirect 301 → `/catalogo/` |
| `/diagnostico-digital/` | Página en draft (ID 1757) | Redirect 301 → `/contacto/` |

### 4. Discrepancia de precios

- `gano-plans-data.php`: Basic $28k, Deluxe $44k, Ultimate $59k, cPanel $68k (mensual)
- `page-ecosistemas.php`: Basic $196k, Deluxe $450k, Ultimate $890k, cPanel $1.2M (display)
- **No resuelto en esta sesión** — requiere decisión de producto sobre qué tier de precios es el correcto.

## Cambios ejecutados

### A. Base de datos (MySQL — gano_staging)

1. **Eliminación de páginas SOTA legacy recreadas por Claude**
   - IDs 1978-1981: `nucleo-prime`, `fortaleza-delta`, `bastion-sota`, `soberania-total` → DELETE
   - Estas páginas capturaban URLs legacy y rompían los redirects 301.

2. **Eliminación de landing pages SOTA**
   - ~20 páginas publicadas el 2026-04-22 con `class="gano-sota-page"` → DELETE
   - Slugs: `arquitectura-nvme-el-manifiesto-de-la-velocidad-critica`, `zero-trust-el-fin-de-la-confianza-implicita`, etc.

3. **Vaciado de trash**
   - ~50 páginas en trash creadas por Claude el 2026-04-28 → DELETE permanente
   - ~10 draft pages SOTA del 2026-04-18 → DELETE

4. **Página legacy `hosting` (ID 1660)**
   - Contenido masivo con branding SOTA (`Núcleo Prime`, `Fortaleza Delta`, `Bastión SOTA`)
   - Template asignado: `page-simple-sota.php` (no existe)
   - Acción: movida a `trash`

5. **Creación de páginas de producto**
   - Página padre: `producto` (ID 1996)
   - Hijas: `wordpress-basic` (1997), `wordpress-deluxe` (1998), `wordpress-ultimate` (1999), `cpanel-ultimate` (2000)
   - Contenido: básico con título, precio, link a `/ecosistemas/`
   - Permalink: `/producto/{slug}/` (estructura padre/hijo)

6. **Flush de rewrite rules**
   - `flush_rewrite_rules()` vía script PHP para que WordPress reconozca `/producto/{slug}/`

7. **Limpieza de posts SOTA**
   - Post 1779: reemplazado "tecnologías SOTA" → "tecnologías de vanguardia"
   - Posts 1780, 1781: sin palabra "SOTA" en contenido (solo branding contextual)
   - Página 1776: reemplazado `<!-- SOTA Catalog -->` → `<!-- Catalog -->`

8. **Permisos `wp-config.php`**
   - `chmod 600 wp-config.php` (era 644)

### B. Tema `gano-child` (archivos PHP/JS/HTML)

1. **`templates/page-ecosistemas.php`**
   - IDs de planes: `nucleo-prime` → `wordpress-basic`, `fortaleza-delta` → `wordpress-deluxe`, `bastion-sota` → `wordpress-ultimate`, `ultimate-wp` → `cpanel-ultimate`
   - `enlace_detalle`: `/producto/{slug}/`
   - Precios display: mantenidos ($196k-$1.2M)
   - Tab labels: nombres limpios
   - Comparison table headers: nombres limpios

2. **`js/gano-nav.js`**
   - `priceMap`: slugs legacy → slugs limpios

3. **`js/diagnostico.js`**
   - `GANO_PRODUCTS`: IDs legacy → IDs limpios
   - Nombres: "Núcleo Prime — Start WP" → "WordPress Básico", etc.

4. **`inc/gano-premium-components.php`**
   - Enlaces CTA: `/ecosistemas#nucleo-prime` → `/ecosistemas#wordpress-basic`, etc.

5. **`templates/page-dashboard-demo.php`**
   - Enlaces CTA: mismos reemplazos que arriba
   - `ultimate-wp` → `cpanel-ultimate`

6. **`archive/homepage-2026-preview.html` + `templates/homepage-2026-preview.html`**
   - Reemplazo masivo de slugs legacy a limpios

7. **`front-page.php`**
   - `/privacidad/` → `/politica-de-privacidad/`
   - `/sla/` → `/terminos-y-condiciones/`
   - `/terminos/` → `/terminos-y-condiciones/`

8. **`js/gano-catalog-intelligence.js`**
   - Menú móvil: `/sota-hub/` → `/ecosistemas/` (texto: "Ecosistemas")
   - `/shop-premium/` → `/catalogo/`
   - `/diagnostico-digital/` → `/contacto/` (texto: "Contacto")
   - Eliminado duplicado "Ecosistemas" en menú móvil

### C. `.htaccess`

1. **Redirects legacy SOTA** (bloque `BEGIN GANO LEGACY REDIRECTS`)
   - Posicionados FUERA del bloque WordPress para evitar sobreescritura
   - 6 reglas: `sota-hub`, `nucleo-prime`, `fortaleza-delta`, `bastion-sota`, `soberania-total`, `hosting`

2. **Redirects para URLs rotas** (agregados al mismo bloque)
   - `/sla/` → `/terminos-y-condiciones/`
   - `/privacidad/` → `/politica-de-privacidad/`
   - `/terminos/` → `/terminos-y-condiciones/`
   - `/shop-premium/` → `/catalogo/`
   - `/diagnostico-digital/` → `/contacto/`

3. **Anti-scraper preservado**
   - Bloque `BEGIN GANO DIGITAL ANTI-SCRAPER & AI BOT PROTECTION` intacto

### D. Repositorio Git

| Commit | Mensaje | Archivos |
|---|---|---|
| `58cde763` | fix(ecosistemas): elimina IDs y enlaces SOTA legacy | `templates/page-ecosistemas.php` |
| `8783ffbf` | fix(js,php): elimina IDs y nombres SOTA legacy | `js/gano-nav.js`, `js/diagnostico.js`, `inc/gano-premium-components.php`, `templates/page-dashboard-demo.php` |
| `7a34a6e7` | fix(html): limpia enlaces SOTA legacy en homepage-2026-preview.html | `archive/homepage-2026-preview.html`, `templates/homepage-2026-preview.html` |
| `33abf643` | fix(cta): corrige enlaces rotos en front-page.php y gano-catalog-intelligence.js | `front-page.php`, `js/gano-catalog-intelligence.js` |

- Todos los commits sincronizados con `origin/main`
- MD5 de archivos modificados: coinciden byte-a-byte entre servidor y repo local

## Verificación de estado (post-cambios)

### URLs de producto
```
200 /producto/wordpress-basic/
200 /producto/wordpress-deluxe/
200 /producto/wordpress-ultimate/
200 /producto/cpanel-ultimate/
```

### Redirects legacy
```
200 /sota-hub/       -> /ecosistemas/
200 /nucleo-prime/   -> /producto/wordpress-basic/
200 /fortaleza-delta/-> /producto/wordpress-deluxe/
200 /bastion-sota/   -> /producto/wordpress-ultimate/
200 /soberania-total/-> /producto/cpanel-ultimate/
200 /hosting/        -> /ecosistemas/
200 /privacidad/     -> /politica-de-privacidad/
200 /sla/            -> /terminos-y-condiciones/
200 /terminos/       -> /terminos-y-condiciones/
200 /shop-premium/   -> /catalogo/
200 /diagnostico-digital/ -> /contacto/
```

### Métricas de limpieza
| Métrica | Valor |
|---|---|
| Referencias SOTA en tema | 0 |
| Referencias "SOTA" en contenido publicado | 0 |
| Páginas publicadas | 19 |
| Trash | 1 (página legacy hosting) |
| wp-config.php permisos | 600 |

## Pendientes (siguiente ola)

1. **Discrepancia de precios**
   - `gano-plans-data.php` vs `page-ecosistemas.php`: diferencia de ~7x
   - Requiere decisión de producto sobre qué tier es el correcto

2. **`functions.php` del servidor**
   - Contiene muchos enqueues SOTA (`gano-sota-fx.js`, `landing-sota-v2.css`, etc.) que apuntan a archivos eliminados
   - Genera 404s silenciosos en navegador
   - Requiere limpieza de bloques de enqueue obsoletos

3. **Página `/sota-hub/`**
   - Actualmente redirect 301 a `/ecosistemas/`
   - Considerar crear página propia si se necesita hub de recursos

4. **Páginas en draft**
   - `shop-premium` (ID 1754): publicar o eliminar definitivamente
   - `diagnostico-digital` (ID 1757): publicar o eliminar definitivamente
   - `sla` (ID 1974): contenido vacío, decide si completar o eliminar

5. **Repo-servidor: archivos faltantes**
   - El repo `Pilot` no incluye todos los archivos del tema (`css/`, `js/`, `components/`, `assets/`, etc.)
   - Requiere decisión sobre si el repo debe contener el tema completo o solo archivos críticos

## Índice maestro

- [2026-04-19-trazabilidad-ops-wave-handoff.md](2026-04-19-trazabilidad-ops-wave-handoff.md) — handoff anterior
- [2026-04-19-auditoria-ssh-inventario-sota.md](2026-04-19-auditoria-ssh-inventario-sota.md) — auditoría previa
