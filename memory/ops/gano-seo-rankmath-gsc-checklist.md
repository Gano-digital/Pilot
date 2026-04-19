# Checklist wp-admin: Gano SEO, Rank Math, Google Search Console

**Fecha:** 2026-04-19 (actualizado — issue #266)  
**Objetivo:** completar el bloque SEO operativo pendiente en `TASKS.md` §Active para un negocio digital sin tienda física.

> **Estado:** ⚠️ BLOQUEADO por acción humana (Diego). El código MU plugin (`gano-seo.php`) ya está
> desplegado. Las tareas pendientes son de configuración en wp-admin, GSC y Rank Math.

Referencias:
- `TASKS.md` (sección Active — tareas SEO de Alta prioridad)
- `wp-content/mu-plugins/gano-seo.php` — plugin activo con Schema JSON-LD, OG, resource hints, LCP preload

---

## A. Gano SEO (wp-admin → Ajustes → Gano SEO)

> El MU plugin `gano-seo.php` ya inyecta Schema JSON-LD (Organization, LocalBusiness, WebSite,
> Product, FAQPage, BreadcrumbList). Los ajustes de este panel complementan/sobrescriben los valores
> del schema sin necesidad de editar PHP.

- [ ] **A1.** Ir a `wp-admin → Ajustes → Gano SEO`.
- [ ] **A2.** Completar campos de empresa digital:
  - Nombre comercial (ej. "Gano Digital")
  - URL canónica base: `https://gano.digital`
  - Descripción corta del negocio (sin Lorem ipsum; usar copy de `memory/content/homepage-copy-2026-04.md`)
- [ ] **A3.** Definir cobertura como **Colombia** (o "Colombia y LATAM" según estrategia acordada).
  - No inventar dirección física local; usar "Bogotá, Colombia" como referencia regional si es necesario.
- [ ] **A4.** Revisar URL de imagen LCP hero (si está configurada). Debe apuntar a la imagen real del hero.
- [ ] **A5.** Guardar y verificar: recargar cualquier página pública → inspeccionar HTML → buscar
  `<script type="application/ld+json">` con datos coherentes (sin `[NIT]`, `[teléfono]` ni placeholders).
- [ ] **A6.** Validar schema en <https://search.google.com/test/rich-results> con la URL de la home.

**Nota de coherencia:** Rank Math y `gano-seo.php` pueden generar schema duplicado si ambos están
activos con configuración OG. Revisar módulo "Rich Snippets" de Rank Math:
si ya emite `Organization` schema, desactivarlo en Rank Math para evitar conflicto.

---

## B. Rank Math (wp-admin → Rank Math → Setup Wizard)

- [ ] **B1.** Ir a `wp-admin → Rank Math → Setup Wizard`.
- [ ] **B2.** Seleccionar tipo de sitio: **Business / Organization** (servicios digitales, no tienda física).
- [ ] **B3.** Configurar datos base:
  - Nombre del sitio: `Gano Digital`
  - URL canónica: `https://gano.digital`
  - Descripción: copy aprobado de `memory/content/homepage-copy-2026-04.md`
  - Social/OG: URL logo en `wp-content/themes/gano-child/` (imagen real, no placeholder).
- [ ] **B4.** Revisar módulos activos; desactivar los no usados para reducir overhead:
  - Activar: `SEO Analysis`, `Sitemap`, `Rich Snippets`, `404 Monitor`.
  - Evaluar: `WooCommerce SEO` (activar si los productos WooCommerce son indexables).
  - Desactivar si no se usa: `News Sitemap`, `Video Sitemap`, `bbPress`, `BuddyPress`.
- [ ] **B5.** Verificar sitemap en `https://gano.digital/sitemap_index.xml` — debe listar páginas y posts.
- [ ] **B6.** Confirmar URL canónica base `https://gano.digital` (sin www) en Rank Math → General → Links.
- [ ] **B7.** Guardar y salir del wizard.

---

## C. Google Search Console

- [ ] **C1.** Abrir <https://search.google.com/search-console/>.
- [ ] **C2.** Añadir propiedad de tipo **URL prefix**: `https://gano.digital`.
- [ ] **C3.** Verificar propiedad — opciones en orden de facilidad:
  1. **DNS TXT record** (recomendado si tienes acceso al DNS de GoDaddy): agregar registro TXT con el valor que provee GSC.
  2. **Archivo HTML**: subir archivo `.html` a la raíz del sitio vía cPanel o deploy.
  3. **Meta tag**: agregar meta tag de verificación en `wp-admin → Rank Math → General → Webmaster Tools → Google Search Console`.
- [ ] **C4.** Confirmar verificación en GSC — debe aparecer "Verified" en propiedades.
- [ ] **C5.** Enviar sitemap:
  - GSC → Sitemaps → Agregar: `sitemap_index.xml` → Submit.
  - Verificar: estado "Success" y páginas indexadas > 0 (puede tardar 24–48 h).
- [ ] **C6.** Registrar fecha de verificación y método en nota interna (sin exponer tokens ni credenciales).

---

## D. Verificación final

- [ ] **D1.** Home `https://gano.digital/` carga sin errores (sin `WP_DEBUG` output visible).
- [ ] **D2.** Fuente HTML contiene `<meta name="description">` con copy real (sin "Lorem ipsum").
- [ ] **D3.** `<script type="application/ld+json">` presente y sin placeholders (`[NIT]`, `[phone]`).
- [ ] **D4.** Sitemap accesible: `https://gano.digital/sitemap_index.xml` devuelve 200.
- [ ] **D5.** Robots.txt: `https://gano.digital/robots.txt` permite indexación de páginas principales.
- [ ] **D6.** Herramienta de inspección de URL en GSC: verificar que la home es "URL is on Google" (o al menos que no está bloqueada).

---

## E. Si HTTPS o DNS fallan

- Ejecutar primero `python scripts/check_dns_https_gano.py`.
- Seguir `memory/ops/dns-https-godaddy-runbook-2026.md` antes de repetir GSC.
- El certificado SSL debe estar activo en GoDaddy Managed WordPress antes de verificar en GSC.

---

## F. Checklist de cierre (issue #266)

```
BLOQUEADO por acción humana (Diego):
  [ ] A — Panel Gano SEO completado con datos reales (sin placeholders)
  [ ] B — Rank Math configurado (tipo negocio, sitemap, módulos revisados)
  [ ] C — GSC verificada + sitemap enviado
  [ ] D — Validación final (schema sin placeholders, sitemap 200)
  [ ] Anotar fecha y resultado en issue #266
```
