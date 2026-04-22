# Checklist wp-admin: Gano SEO, Rank Math, Google Search Console
**Issue #266** · Actualizado: abril 2026

Objetivo: completar el bloque SEO operativo pendiente en `TASKS.md` para un negocio digital sin tienda física obligatoria.

Referencias:
- `TASKS.md` (sección Active → SEO)
- `wp-content/mu-plugins/gano-seo.php`
- `memory/content/seo-canonical-og-analysis-2026.md`

---

## Estado de bloqueo

Estos pasos **requieren acceso manual a wp-admin** en `https://gano.digital/wp-admin`.
No pueden ejecutarse desde el repositorio.

**Condición previa**: el deploy de `gano-child` / `mu-plugins` debe estar en producción
(verificar con Actions → **05 · Ops · Verificar parches**).

---

## A. Gano SEO (wp-admin → Ajustes → Gano SEO)

> El plugin `gano-seo.php` ya expone esta pantalla. Todos los valores se almacenan
> en `wp_options` y se inyectan automáticamente en los schemas JSON-LD.

1. **Nombre legal**: completar la razón social definitiva (p.ej. `Gano Digital SAS`).
2. **NIT**: ingresar con formato `900.123.456-7` (sin inventar — usar el NIT real).
3. **Teléfono**: formato internacional `+57 300 123 4567`.
4. **WhatsApp**: solo número sin `+` ni espacios `573001234567`.
5. **Email de contacto**: confirmar `hola@gano.digital` o la dirección activa.
6. **URL Logo**: ruta completa a la imagen de logo subida en Media Library.
7. **URL Hero (LCP)**: URL de la imagen principal del home (para preload de LCP).
8. **URL Fuente LCP**: URL `.woff2` exacta de `fonts.gstatic.com` para preload de fuente crítica.
   - Obtener: Chrome DevTools → Network → Fonts → copiar Request URL del `.woff2` de Plus Jakarta Sans.
9. **Tipo de negocio**: dejar en `Digital / Organización` — Gano Digital **no** tiene local físico.
   - NO seleccionar "Negocio local" (evita schema LocalBusiness incorrecto).
10. **Dirección física**: dejar en blanco (negocio 100 % digital).
11. Clic en **Guardar ajustes SEO**.
12. Verificar que la página de admin no muestre errores PHP.

---

## B. Rank Math (wp-admin → Rank Math → Dashboard → "Re-run Setup Wizard")

> Configuración optimizada para empresa digital sin tienda física en Colombia.

1. **Modo**: seleccionar **Advanced Mode** (modo Avanzado) para control total.
2. **Tipo de sitio**: elegir **Company / Organization** (Empresa/Organización).
   - ⚠️ NO seleccionar "Local Business" — Gano Digital es 100 % digital.
3. **Datos de la empresa**:
   - Nombre: el mismo que en Gano SEO.
   - URL: `https://gano.digital`
   - Logo: subir el mismo logo configurado en Gano SEO.
   - Dirección: **dejar en blanco** o poner solo `Colombia` como país.
4. **Webmaster Tools**: pegar el token de Google Search Console (ver §C paso 2).
5. **Sitemap XML**: activar → incluir Páginas, Posts, Productos WooCommerce.
   - Excluir: carrito, checkout, cuenta (añadidas por WooCommerce, bajo valor SEO).
6. **Optimizaciones**:
   - Activar **Noindex** para páginas de autor, resultados de búsqueda, tags de bajo valor.
   - Activar módulo **Schema / Rich Snippets**.
   - OpenGraph activo por defecto — no desactivar.
7. **Schema por defecto**: tipo de artículo → `Article` para posts/noticias.
8. **Verificar integración con gano-seo.php**:
   - El MU plugin extiende el nodo Organization de RM con `areaServed`, `legalName`, `taxID`.
   - Solo cuando RM está activo, `gano_output_base_schema()` emite únicamente el FAQPage.
   - Confirmar en Rich Results Test que hay **un solo** nodo Organization en el `@graph`.

---

## C. Google Search Console (https://search.google.com/search-console)

1. **Añadir propiedad**: `https://gano.digital` (propiedad de URL prefix o Domain).
   - Recomendado: **Domain** (cubre http, https y subdominios) vía verificación DNS.
   - Alternativa: **URL prefix** con verificación por meta tag HTML.
2. **Obtener el token de verificación** (si usas meta tag HTML):
   - GSC → Añadir propiedad → "HTML tag" → copiar solo el valor del atributo `content`
     (cadena de 43–44 caracteres alfanuméricos, sin la etiqueta `<meta>`).
   - Pegar ese token en: **wp-admin → Ajustes → Gano SEO → Google Search Console**.
   - El MU plugin emite automáticamente `<meta name="google-site-verification" content="...">`.
   - Clic en **Verificar** en GSC.
3. **Si usas verificación DNS** (preferida para Domain):
   - GSC proporciona un registro TXT → añadir en DNS de GoDaddy.
   - No requiere cambio en wp-admin.
4. **Enviar sitemap**:
   - GSC → Sitemaps → Agregar nuevo sitemap → `https://gano.digital/sitemap_index.xml`
   - Confirmar que Rank Math genera el sitemap (wp-admin → Rank Math → Sitemap).
5. **Registrar fecha de verificación y responsable** en bitácora interna (este archivo o issues).

---

## D. Verificación final

- [ ] Fuente HTML de `https://gano.digital` contiene `<meta name="google-site-verification">` (si verificación por meta tag).
- [ ] Fuente HTML contiene un solo bloque `<script type="application/ld+json">` con `Organization` cuando RM activo.
- [ ] [Google Rich Results Test](https://search.google.com/test/rich-results) → sin errores en `https://gano.digital`.
- [ ] [Schema.org Validator](https://validator.schema.org/) → schema válido, `areaServed` incluye Colombia.
- [ ] GSC → Cobertura → sin errores 404 / canonicals rotos.
- [ ] Sitemap visible en GSC con estado `Correcto`.

---

## E. Si HTTPS o DNS fallan (pre-requisito)

- Ejecutar primero: `python scripts/check_dns_https_gano.py`
- Seguir: `memory/ops/dns-https-godaddy-runbook-2026.md`
- Confirmar que `gano.digital` resuelve con HTTPS válido antes de GSC.

---

## F. Estado de tareas de código (repo)

| Tarea de código | Estado | Referencia |
|-----------------|--------|-----------|
| Schema JSON-LD Organization + WebSite | ✅ MU plugin `gano-seo.php` | Fase 3 |
| Guard Rank Math en base schema | ✅ `gano_output_base_schema()` | issue #229 |
| Extensión Organization via filtro RM | ✅ `gano_extend_rankmath_schema()` | issue #229 |
| Guard Rank Math en schema Producto | ✅ `gano_output_product_schema()` | issue #266 |
| Guard Rank Math en Breadcrumb | ✅ `gano_output_breadcrumb_schema()` | Fase 3 |
| Guard Rank Math en OG fallback | ✅ `gano_og_fallback()` | Fase 3 |
| Meta tag GSC verification | ✅ `gano_gsc_verification_meta()` | Fase 3 |
| Panel wp-admin Ajustes → Gano SEO | ✅ `gano_seo_settings_page()` | Fase 3 |
| **Completar datos en wp-admin** | ⏳ **Acción humana** | §A arriba |
| **Ejecutar Setup Wizard Rank Math** | ⏳ **Acción humana** | §B arriba |
| **Verificar propiedad GSC** | ⏳ **Acción humana** | §C arriba |
