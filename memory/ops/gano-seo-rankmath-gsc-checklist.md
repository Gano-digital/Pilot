# Checklist wp-admin: Gano SEO + Rank Math + Google Search Console

Objetivo: ejecutar la configuracion operativa pendiente de SEO indicada en `TASKS.md` para un negocio digital (sin tienda fisica obligatoria).

Referencias:

- `TASKS.md` (bloque ALTA: Gano SEO, GSC, Rank Math)
- `wp-content/mu-plugins/gano-seo.php`

## 1) Gano SEO (wp-admin -> Ajustes -> Gano SEO)

- [ ] Definir nombre legal/comercial de la empresa digital.
- [ ] Definir cobertura (Colombia) y zona horaria operativa.
- [ ] Definir email y telefono de contacto publicable.
- [ ] Revisar URLs canonicas principales (home, planes, contacto).
- [ ] Guardar cambios y validar que no haya errores en admin.

Evidencia:

- [ ] Captura de pantalla de la configuracion guardada.

## 2) Google Search Console

- [ ] Crear o abrir propiedad `https://gano.digital`.
- [ ] Verificar propiedad con metodo recomendado (DNS o HTML).
- [ ] Enviar sitemap principal (si aplica por plugin SEO).
- [ ] Confirmar estado "Propiedad verificada".

Evidencia:

- [ ] Captura de propiedad verificada.
- [ ] Captura de sitemap enviado.

## 3) Rank Math (wp-admin -> Rank Math -> Setup Wizard)

- [ ] Ejecutar Setup Wizard.
- [ ] Seleccionar perfil de sitio de servicios digitales.
- [ ] Confirmar metadatos base (titulo, descripcion, social/open graph).
- [ ] Revisar modulos activos necesarios y desactivar modulos no usados.
- [ ] Guardar y validar que no aparezcan alertas criticas.

Evidencia:

- [ ] Captura del resumen final del wizard.

## 4) Verificacion funcional rapida

- [ ] Abrir home publica y confirmar que no hay errores visibles.
- [ ] Ver codigo fuente y confirmar presencia de metadatos SEO/OG esperados.
- [ ] Registrar fecha y responsable de la ejecucion.

## 5) DoD (Definition of Done)

- [ ] Gano SEO configurado para modelo 100% digital.
- [ ] GSC propiedad verificada para `https://gano.digital`.
- [ ] Rank Math wizard completado con configuracion coherente al proyecto.
- [ ] Evidencias adjuntas en nota de sesion o issue.

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
