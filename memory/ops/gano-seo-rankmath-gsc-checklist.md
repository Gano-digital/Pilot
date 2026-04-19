# Checklist wp-admin: Gano SEO, Rank Math, Google Search Console

Objetivo: completar el bloque SEO operativo pendiente en `TASKS.md` para un negocio digital sin tienda fisica obligatoria.

Referencias:

- `TASKS.md` (seccion Active)
- `wp-content/mu-plugins/gano-seo.php`

## A. Gano SEO (wp-admin -> Ajustes -> Gano SEO)

1. Completar datos de empresa digital (nombre comercial/legal, cobertura, contacto publico).
2. Definir cobertura principal en Colombia (o region acordada).
3. Revisar URLs canonicas base (home, ecosistemas, shop, contacto).
4. Guardar y validar que no existan errores en admin.
5. Verificar una pagina publica y confirmar metadatos/schema coherentes.

Nota de coherencia: mantener consistencia con `gano-seo.php` para evitar schema contradictorio.

## B. Rank Math (wp-admin -> Rank Math -> Setup Wizard)

1. Ejecutar setup inicial con perfil de servicios digitales.
2. Configurar metadatos base (titulo, descripcion, social/OG) sin inventar datos legales.
3. Revisar modulos activos y desactivar los no usados.
4. Verificar sitemap y URL canonica bajo `https://gano.digital`.

## C. Google Search Console

1. Crear o abrir propiedad `https://gano.digital`.
2. Verificar propiedad via DNS, archivo HTML o meta tag.
3. Enviar sitemap principal y confirmar recepcion.
4. Registrar fecha y responsable en bitacora interna.

## D. Verificacion final

- Home publica sin errores visibles.
- Fuente HTML con metadatos esperados.
- Evidencias en nota interna/issue privado (sin exponer tokens).

## E. Si HTTPS o DNS fallan

- Ejecutar primero `python scripts/check_dns_https_gano.py`.
- Seguir `memory/ops/dns-https-godaddy-runbook-2026.md` antes de repetir GSC.
