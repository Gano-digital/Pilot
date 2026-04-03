# Checklist wp-admin: Gano SEO, Rank Math, Google Search Console

Orden sugerido. **No** adjuntar capturas con tokens de verificación en issues públicos.

## A. Gano SEO (MU plugin)

1. **wp-admin → Ajustes → Gano SEO** (o el menú que exponga `gano-seo.php`).
2. Completar datos de **empresa digital** (modelo sin tienda física obligatoria).
3. Área de cobertura: **Colombia** (o la región acordada).
4. Si hay campo de URL de imagen LCP / hero: usar URL estable de producción (HTTPS).
5. Guardar y revisar una página pública: ver código fuente o Rich Results Test para JSON-LD si aplica.

_Coherencia:_ los datos deben ser consistentes con `wp-content/mu-plugins/gano-seo.php` (Organization / LocalBusiness digital).

## B. Rank Math

1. **wp-admin → Rank Math → Setup Wizard** (o panel principal).
2. Tipo de sitio: **negocio / servicios** (no tienda física si no aplica).
3. Completar redes y datos básicos sin inventar NIT/teléfono: usar placeholders hasta tener datos reales.
4. Revisar **sitemap** si Rank Math lo genera: URL debe ser `https://gano.digital/...`.
5. Evitar duplicar schema gravemente contradictorio con Gano SEO; si hay solapamiento, priorizar una fuente y documentar en issue `coordination`.

## C. Google Search Console

1. Ir a [Google Search Console](https://search.google.com/search-console).
2. Añadir propiedad **URL con prefijo** `https://gano.digital` (o dominio según estrategia).
3. Verificar por **archivo HTML**, **meta tag** (Rank Math puede ayudar) o **DNS** según preferencia.
4. Tras verificar: enviar **sitemap** si está disponible (ruta la da Rank Math o plugin SEO).
5. Anotar fecha de verificación en tu bitácora interna (no pegar código de verificación en GitHub).

## D. Si HTTPS aún falla

- Resolver primero DNS/SSL (ver `scripts/check_dns_https_gano.py` y cola `tasks-infra-dns-ssl.json`).
- Sin HTTPS estable, GSC y cookies suelen fallar.

## Referencia

- `TASKS.md` — sección Active (Fase 3 operativa).
