# Diseño: Auditoría y Reparación gano.digital — Tres Waves Paralelas

**Fecha:** 2026-06-17
**Estado:** Aprobado
**Prioridad:** A (Comercial) → C (Técnico) → B (Contenido)

---

## Contexto

Auditoría SSH completa de producción reveló tres categorías independientes de problemas en gano.digital. Se diseña un plan de tres ramas paralelas en Git que corren simultáneamente pero se mergean en orden de prioridad, usando los GitHub Actions existentes como validadores automáticos.

---

## Inventario completo de problemas encontrados

### Páginas vacías (solo comentarios HTML)

| ID | Título | Slug | Problema |
|----|--------|------|---------|
| 2039 | Arquitectura Cloud SOTA | `/arquitectura-cloud/` | `<!-- SOTA Cloud Architecture -->` |
| 2037 | Gano SOTA Index | `/catalogo-sota/` | `<!-- SOTA Catalog -->` |
| 2036–2028 | 9 páginas SOTA Architecture | varios | `<!-- SOTA Architecture Content -->` |
| 1996 | Producto | `/producto/` | Vacía, sin propósito |
| 1776 | Catálogo de Productos | `/catalogo/` | `<!-- Catalog -->` |
| 1766 | Blog | `/blog/` | Placeholder comentado |
| 1745 | Inicio | `/inicio/` | Placeholder de Elementor — Elementor no instalado |

### Páginas con contenido roto

| ID | Título | Problema |
|----|--------|---------|
| 1997–2000 | Planes WordPress/cPanel | Encoding UTF-8 roto: `.200.000/mes` en lugar de `$200.000/mes` |

### Problema canónico del catálogo

- `/catalogo/` (ID 1776) debería ser la URL canónica del catálogo Reseller
- `/ecosistemas/` (ID 1656) tiene el catálogo dinámico activo pero debe quedar archivado con redirect 301
- PFIDs no encontrados en `wp_options` con prefijo `pfid*` — requiere verificación de sync

### Plugins activos problemáticos

| Plugin | Problema | Acción |
|--------|---------|--------|
| `gano-phase1-installer` | Debería estar inactivo (removido del disco abr-2026 según CLAUDE.md) | Desactivar |
| `gano-phase2-business` | Ídem | Desactivar |
| `gano-phase3-content` | Ídem | Desactivar |
| `gano-content-importer` | One-shot installer, ya cumplió su función | Desactivar |

Todos se **desactivan** (no borran) para mantener posibilidad de reinstalar entorno desde cero.

### Errores de base de datos

- Rank Math: `Table 'gano_staging.wp_6ce773b45f_rank_math_meta' doesn't exist`
- Fix: ciclo deactivate/activate del plugin fuerza recreación de tablas

### Templates huérfanos

Sin página asignada clara ni uso activo confirmado:

```
page-dashboard-demo.php
page-diagnostico-digital.php
page-ecosistemas-v2.php
page-ecosistemas-v3.php
page-showcase.php
page-sota-hub.php
shop-premium.php
sota-single-template.php
homepage-2026-preview.html
```

Acción: mover a `templates/deprecated/` (no borrar).

### PR pendiente

- **PR #305**: `feat/catalog-acceso-dev-categories` — 7 nuevos productos. Mergear antes de crear las ramas de este plan.

---

## Arquitectura de solución

### Estructura de ramas

```
main
 ├── fix/commercial-catalog    ← Wave A (merge primero)
 ├── fix/technical-cleanup     ← Wave C (merge segundo)
 └── feat/content-sota         ← Wave B (merge último)
```

Las tres ramas se crean simultáneamente. No comparten archivos — cero riesgo de merge conflict.

### Flujo de despliegue

```
PASO 0: Merge PR #305 → base limpia en main
         │
         ├── fix/commercial-catalog ──► php-lint + test-runner ──► PR review ──► MERGE A
         │                                                                           │
         │                                                               reseller-catalog-sync ✓
         │
         ├── fix/technical-cleanup ──► php-lint + test-runner ──► PR review ──► MERGE C
         │                                                                          │
         │                                                          health-check-plugins ✓
         │
         └── feat/content-sota ──► php-lint + test-runner ──► PR review ──► MERGE B
                                                                                │
                                                                        sync-content ✓
                                                                                │
                                                                         deploy.yml → producción
```

### GitHub Actions por wave

| Wave | Actions que se disparan |
|------|------------------------|
| A (commercial) | `php-lint-gano.yml`, `test-runner.yml`, `30-reseller-catalog-sync.yml` |
| C (technical) | `php-lint-gano.yml`, `test-runner.yml`, `08-health-check-plugins.yml`, `31-plugin-health-check-phase4.yml` |
| B (content) | `php-lint-gano.yml`, `test-runner.yml`, `07-sync-content.yml` |
| Post todas | `deploy.yml` |

No se crean workflows nuevos — se usan los 21 existentes.

---

## Wave A — `fix/commercial-catalog`

### Objetivo
Que `/catalogo/` sea la URL canónica del catálogo Reseller y el flujo de compra sea completamente funcional.

### Tareas

1. **Merge PR #305 en main** (prerequisito, no en esta rama)

2. **Hacer `/catalogo/` canónico**
   - Asignar template via WP-CLI: `wp post meta update 1776 _wp_page_template 'templates/page-ecosistemas.php'`
   - Verificar que el shortcode `gano_reseller_iframe` o `gano_get_reseller_catalog_products()` esté activo
   - Agregar metatag canonical: `<link rel="canonical" href="https://gano.digital/catalogo/" />`

3. **Archivar `/ecosistemas/`**
   - Agregar redirect 301 en `functions.php`:
     ```php
     add_action('template_redirect', function() {
         if (is_page('ecosistemas')) {
             wp_redirect(home_url('/catalogo/'), 301);
             exit;
         }
     });
     ```

4. **Fix encoding en páginas de planes** (IDs 1997–2000)
   - Corregir `$` y símbolo COP con encoding correcto
   - Verificar que los CTAs apunten a `/catalogo/` en lugar de `/ecosistemas/`

5. **Trash página "Producto"** (ID 1996)
   - `wp post delete 1996 --force` — no tiene propósito definido ni contenido

6. **Forzar PFID sync**
   - Los PFIDs están vacíos en `wp_options` (confirmado en auditoría). Disparar `30-reseller-catalog-sync.yml` via `workflow_dispatch` inmediatamente después del merge de Wave A.
   - Verificar resultado: `wp option list --search="gano_pfid*"` debe retornar al menos 4 entradas (una por plan).

### Archivos modificados
- `wp-content/themes/gano-child/functions.php` (redirect ecosistemas)
- `wp-content/themes/gano-child/templates/page-ecosistemas.php` (asignar a /catalogo/)
- WP-CLI post updates (IDs 1997–2000, 1776)

---

## Wave C — `fix/technical-cleanup`

### Objetivo
Servidor limpio: plugins innecesarios inactivos, DB sin errores, templates organizados.

### Tareas

1. **Desactivar plugins phase + content-importer**
   ```bash
   wp plugin deactivate gano-phase1-installer gano-phase2-business gano-phase3-content gano-content-importer
   ```

2. **Fix Rank Math DB**
   ```bash
   wp plugin deactivate rank-math
   wp plugin activate rank-math
   wp db query "SHOW TABLES LIKE '%rank_math%'"
   # Si las tablas siguen faltando, forzar re-setup:
   wp option delete rank_math_install_version
   wp plugin deactivate rank-math && wp plugin activate rank-math
   ```

3. **Audit y mover templates huérfanos**
   ```
   templates/
   ├── deprecated/
   │   ├── page-ecosistemas-v2.php
   │   ├── page-ecosistemas-v3.php
   │   ├── page-showcase.php
   │   ├── page-sota-hub.php
   │   ├── shop-premium.php
   │   ├── sota-single-template.php
   │   ├── page-dashboard-demo.php
   │   ├── page-diagnostico-digital.php
   │   └── homepage-2026-preview.html
   └── README.md  ← nuevo: documenta qué template sirve qué página
   ```

4. **Crear `templates/README.md`**

   | Template | Página activa | URL |
   |----------|--------------|-----|
   | `page-ecosistemas.php` | Catálogo (ID 1776) | `/catalogo/` |
   | `page-nosotros.php` | Nuestra Filosofía (ID 1658) | `/nosotros/` |
   | `page-contacto.php` | Contacto (ID 1662) | `/contacto/` |
   | `page-hosting.php` | Ecosistemas Infraestructura (ID 1660) | `/hosting/` |
   | `page-dominios.php` | Registro de Dominios (ID 1659) | `/dominios/` |
   | `page-servicios.php` | Blindaje y Optimización (ID 1661) | `/servicios/` |
   | `page-sla.php` | Acuerdo de Nivel de Servicio (ID 1940) | `/acuerdo-de-nivel-de-servicio/` |
   | `page-privacidad.php` | Política de Privacidad (ID 1939) | `/politica-de-privacidad/` |
   | `page-terminos.php` | Términos y Condiciones (ID 1672) | `/terminos-y-condiciones/` |
   | `page-seo-landing.php` | SEO landing | `/hosting-wordpress-colombia/` |

### Archivos modificados
- `wp-content/themes/gano-child/templates/deprecated/` (9 archivos movidos)
- `wp-content/themes/gano-child/templates/README.md` (nuevo)
- WP-CLI plugin deactivations (no toca archivos del repo)

---

## Wave B — `feat/content-sota`

### Objetivo
Las 11 páginas SOTA vacías tienen copy real, coherente con el tono "Soberanía Digital" de la marca.

### Páginas a completar

| ID | Slug | Tema del copy |
|----|------|--------------|
| 2028 | `/seguridad-zero-trust/` | Zero-Trust Security |
| 2029 | `/almacenamiento-nvme/` | NVMe Gen4 |
| 2030 | `/soberania-digital/` | Soberanía Digital |
| 2031 | `/inteligencia-sintetica/` | IA Predictiva |
| 2032 | `/red-global-anycast/` | Anycast + Edge |
| 2033 | `/computacion-serverless/` | Serverless |
| 2034 | `/ecosistemas-hibridos/` | Hybrid Cloud |
| 2035 | `/edge-computing-pro/` | Edge Computing |
| 2036 | `/ciber-resiliencia-fractal/` | Cyber Resilience |
| 2037 | `/catalogo-sota/` | Index de todas las páginas SOTA |
| 2039 | `/arquitectura-cloud/` | Cloud Architecture |

### Páginas adicionales

- **Blog** (ID 1766): conectar con los 6 posts existentes — el placeholder se reemplaza con shortcode o template que liste posts
- **Inicio** (ID 1745): hacer trash — el home real es ID 1657 con shortcodes `[gano_hero][gano_socio_tecnologico][gano_metrics]`

### Estructura de cada página SOTA
Cada página sigue el patrón del contenido existente en páginas como "Nuestra Filosofía" o "Blindaje y Optimización":
1. H1 con título impactante
2. Párrafo introductorio (tono Soberanía Digital)
3. Sección de características (3-4 bullets técnicos)
4. CTA apuntando a `/catalogo/`

---

## Rollback

Todas las operaciones son reversibles:
- Plugin deactivations: `wp plugin activate <nombre>`
- Post content changes: reversibles via git (WP-CLI scripts en repo)
- Template moves: `git revert`
- Redirect en functions.php: eliminar el `add_action`

No hay cambios de schema destructivos en ninguna wave.

---

## Prerequisito: Merge PR #305

Antes de crear las tres ramas, mergear PR #305 (`feat/catalog-acceso-dev-categories`) para que los 7 nuevos productos de Acceso + Dev queden en main como base. Esto evita rebase conflicts en Wave A.

---

## Criterios de éxito

### Wave A
- `https://gano.digital/catalogo/` carga catálogo Reseller con productos y precios en COP
- `https://gano.digital/ecosistemas/` hace redirect 301 a `/catalogo/`
- Páginas de planes (IDs 1997–2000) muestran precios sin encoding roto
- `30-reseller-catalog-sync.yml` pasa en verde

### Wave C
- `wp plugin list --status=active` no incluye phase1/2/3 ni content-importer
- `SHOW TABLES LIKE '%rank_math%'` retorna al menos 3 tablas
- `08-health-check-plugins.yml` pasa en verde
- `templates/deprecated/` contiene los 9 templates huérfanos

### Wave B
- Ninguna de las 11 páginas SOTA retorna contenido vacío
- Cada página tiene H1, al menos 2 secciones de contenido, y CTA a `/catalogo/`
- `07-sync-content.yml` pasa en verde
