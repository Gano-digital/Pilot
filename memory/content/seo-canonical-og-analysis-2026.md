# Análisis SEO Técnico: canonical, OG y schema — Rank Math vs gano-seo.php
**Issue #229** · Fecha: abril 2026 · Autor: Copilot (issue w2-*)

---

## 1. Resumen ejecutivo

`gano-seo.php` (MU plugin) y Rank Math (plugin regular) comparten responsabilidades
sobre el `<head>` SEO. Este documento describe los solapamientos encontrados,
su severidad y las correcciones aplicadas.

---

## 2. Diagnóstico por área

### 2.1 Meta canonical (`<link rel="canonical">`)

| Emisor | Mecanismo | Estado |
|--------|-----------|--------|
| **Rank Math** | `rank_math/head` @ prioridad 20 (clase `Head`) | ✅ Activo cuando RM está instalado |
| **WordPress core** | `wp_head()` → `rel_canonical()` | ✅ RM lo desactiva (`remove_all_filters('wp_robots')`) |
| **gano-seo.php** | No emite `<link rel="canonical">` | ✅ Sin conflicto |

**Conclusión**: No había solapamiento real en canonical. `gano-seo.php` nunca emitió
este tag; Rank Math lo gestiona solo.

---

### 2.2 Open Graph y Twitter Card

| Emisor | Condición | Estado |
|--------|-----------|--------|
| **Rank Math** (`OpenGraph` class) | Siempre cuando RM activo; hook `rank_math/head` @ 30 | ✅ |
| **gano-seo.php** `gano_og_fallback()` | `wp_head` @ prioridad 3; omite si `class_exists('RankMath')` | ✅ Guard correcto |

**Flujo de ejecución** (prioridades `wp_head`):
```
wp_head pri 1  → Rank Math::head() → rank_math/head (pri 30 → OG tags de RM)
wp_head pri 3  → gano_og_fallback() → comprueba class_exists('RankMath') → omite
```

**Conclusión**: La guard ya era correcta. RM siempre emite OG tags cuando está activo
(el módulo OpenGraph no es opcional en la versión free). No se requirió cambio aquí.

---

### 2.3 Schema JSON-LD — Organization + WebSite ⚠️ PROBLEMA ENCONTRADO Y CORREGIDO

**Situación anterior (bug)**:

`gano_output_base_schema()` emitía **siempre** un bloque Organization + WebSite,
incluso con Rank Math activo. Esto producía:

- **Dos nodos `Organization`** con el mismo `@id` (`home_url('/#organization')`):
  uno de Rank Math (key `publisher`) y otro de `gano-seo.php`.
- **Dos nodos `WebSite`** con el mismo `@id` (`home_url('/#website')`):
  uno de Rank Math (key `WebSite`) y otro de `gano-seo.php`.

El comentario original decía "usar `rank_math_json_ld` filter para complementar",
pero el filtro **nunca fue implementado**. El schema completo se emitía siempre.

**Corrección aplicada (PR #229)**:

1. **Guard en `gano_output_base_schema()`**: cuando `class_exists('RankMath')`,
   la función solo emite el FAQPage (que RM no genera automáticamente) y retorna.

2. **Filtro `rank_math/json_ld`** (`gano_extend_rankmath_schema()`):
   registrado en `init` (después de `plugins_loaded`, antes de `wp_head`).
   Extiende el nodo `publisher` de RM con los campos que RM omite:
   - `areaServed` (Colombia, México, América Latina)
   - `currenciesAccepted` (COP)
   - `paymentAccepted`
   - `legalName` (si configurado en wp-admin → Ajustes → Gano SEO)
   - `taxID` / NIT (si configurado)

3. **Helper `gano_build_faq_schema()`**: extrae la construcción del FAQPage
   para reutilización. Se invoca tanto en el branch RM-activo como en el RM-inactivo.

**Resultado**:

| Escenario | Antes | Después |
|-----------|-------|---------|
| Rank Math activo | 2× Organization, 2× WebSite, 1× FAQPage | 1× Organization (RM+extensiones Gano), 1× WebSite (RM), 1× FAQPage (Gano) |
| Rank Math inactivo | 1× Organization, 1× WebSite, 1× FAQPage | Sin cambio (igual comportamiento) |

---

### 2.4 Schema JSON-LD — BreadcrumbList

Rank Math genera BreadcrumbList automáticamente.
`gano_output_breadcrumb_schema()` ya tenía guard `class_exists('RankMath')`.
**Sin cambios necesarios.**

---

### 2.5 Schema JSON-LD — Producto WooCommerce

`gano_output_product_schema()` no tenía guard contra Rank Math.
Rank Math también genera schema Product para productos WooCommerce.
**Riesgo resuelto**: schema Product duplicado en páginas de producto.

**Estado**: ✅ Corregido en issue #266 (PR ops-seo-configurar-rank-math).
`gano_output_product_schema()` ahora retorna temprano si `class_exists('RankMath')`.
Los datos de pago colombianos (PSE, Nequi, Daviplata) se mantienen en el nodo
Organization vía `gano_extend_rankmath_schema` (`paymentAccepted`).

---

## 3. Orden de ejecución (referencia)

```
plugins_loaded  → Rank Math cargado (class_exists('RankMath') = true)
init            → gano_seo_init_rankmath_integration() registra rank_math/json_ld filter
wp              → WordPress setup completado
wp_head
  ├── pri 1     → RankMath\Frontend\Head::head()
  │               └── do_action('rank_math/head')
  │                   ├── pri 1   → title tag
  │                   ├── pri 6   → meta description
  │                   ├── pri 10  → robots
  │                   ├── pri 20  → canonical
  │                   ├── pri 30  → OpenGraph + Twitter Card
  │                   └── pri 90  → JSON-LD (aplica filtro rank_math/json_ld)
  │                                 └── gano_extend_rankmath_schema() (pri 5)
  ├── pri 1     → gano_resource_hints() (preconnect / preload)
  ├── pri 2     → gano_gsc_verification_meta()
  ├── pri 3     → gano_og_fallback() → omite por class_exists('RankMath')
  ├── pri 5     → gano_output_base_schema()
  │               └── class_exists('RankMath') = true → solo FAQPage, return
  ├── pri 6     → gano_output_product_schema() ← ⚠️ no tiene guard RM
  └── pri 7     → gano_output_breadcrumb_schema() → omite por class_exists('RankMath')
```

---

## 4. Configuración recomendada en Rank Math (wp-admin)

Para que la integración funcione óptimamente:

1. **wp-admin → Rank Math → Setup Wizard** (si no se ha ejecutado):
   - Tipo de sitio: "Empresa/Organización" (no "Negocio local")
   - Activar módulo Schema/Rich Snippets
   - Activar módulo OpenGraph (activo por defecto)

2. **wp-admin → Rank Math → Titles & Meta → Global Meta**:
   - Verificar que "Open Graph" esté habilitado
   - Completar imagen OG por defecto

3. **wp-admin → Ajustes → Gano SEO**:
   - Completar `Nombre legal`, `NIT`, `Teléfono`, `WhatsApp`
   - Estos valores se inyectarán en el schema Organization de RM

---

## 5. Validación

- `php -l wp-content/mu-plugins/gano-seo.php` → sin errores
- Validar schema en producción: [Google Rich Results Test](https://search.google.com/test/rich-results)
- Confirmar un solo nodo Organization en el `@graph` cuando RM está activo

---

## 6. Pendientes (fuera de este PR)

- [x] ~~Agregar guard RM en `gano_output_product_schema()` (schema Product duplicado)~~ → corregido issue #266
- [ ] Completar datos NIT y teléfono en wp-admin → Ajustes → Gano SEO (acción humana)
- [ ] Ejecutar Rank Math Setup Wizard (acción humana en wp-admin)
- [ ] Verificar propiedad en Google Search Console (acción humana)
