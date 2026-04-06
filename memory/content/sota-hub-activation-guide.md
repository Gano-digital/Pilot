# Guía de Activación — Hub de Innovación SOTA

**Referencia:** `cd-content-003`  
**Última actualización:** 2026-04-06  
**Rama:** `copilot/activate-gano-content-importer`

---

## Descripción general

El plugin `gano-content-importer` crea automáticamente:

- **20 páginas SOTA** como borradores (`draft`) en WordPress, cada una con la meta `_gano_sota_category` asignada.
- **1 página Hub** (`/hub-sota`) como borrador, con el template `templates/page-sota-hub.php`.

---

## Pasos de activación en staging

### 1. Activar el plugin

```bash
wp --path=/home/f1rml03th382/public_html/gano.digital \
   plugin activate gano-content-importer
```

### 2. Verificar las 20 páginas SOTA creadas

```bash
wp --path=/home/f1rml03th382/public_html/gano.digital \
   post list \
   --post_type=page \
   --post_status=draft \
   --meta_key=_gano_sota_category \
   --fields=ID,post_title,post_status \
   --format=table
```

**Resultado esperado:** 20 filas, todas con `post_status = draft`.

### 3. Verificar template de páginas SOTA

```bash
wp --path=/home/f1rml03th382/public_html/gano.digital \
   post meta get <ID> _wp_page_template
# Resultado esperado: elementor_canvas
```

### 4. Verificar página hub

```bash
wp --path=/home/f1rml03th382/public_html/gano.digital \
   post list \
   --post_type=page \
   --post_status=draft \
   --post_name__in='hub-sota' \
   --fields=ID,post_title,post_name \
   --format=table
```

```bash
wp --path=... post meta get <HUB_ID> _wp_page_template
# Resultado esperado: templates/page-sota-hub.php
```

---

## Categorías de páginas SOTA

| Categoría (`_gano_sota_category`) | Páginas |
|-----------------------------------|---------|
| `infraestructura` | NVMe, Contenedores Aislados, Edge Computing, Resiliencia de Datos, Escalamiento Elástico, Arquitectura Indestructible |
| `seguridad` | Zero-Trust, Inmunidad DDoS, Blindaje Post-Cuántico |
| `inteligencia-artificial` | IA Predictiva, Self-Healing, Co-Piloto IA Soberana |
| `rendimiento` | Headless WordPress, Skeleton Screens, Orquestación CI/CD, Experiencias Cinéticas, Protocolos HTTP/3 |
| `estrategia` | Soberanía Digital, Ingeniería Sostenible, Soberanía de Datos |

---

## Criterios de cierre del issue

| Criterio | Cómo verificar |
|----------|---------------|
| 20 páginas en estado draft con `_gano_sota_category` | `wp post list --meta_key=_gano_sota_category` (debe devolver 20 filas) |
| Página hub `/hub-sota` creada | `wp post list --post_name__in='hub-sota'` |
| Template hub correcto | `wp post meta get <HUB_ID> _wp_page_template` → `templates/page-sota-hub.php` |
| Sin Lorem ipsum en drafts | `wp post list --fields=ID` + `wp post get <ID> --field=post_content \| grep -i lorem` → sin resultados |
| CTAs apuntan a `/contacto` | Inspeccionado en código — valor por defecto de `gano_get_cta_url()` = `/contacto` |

---

## CTA URL configurable

El CTA base de cada página SOTA se obtiene de la opción de WordPress `gano_sota_cta_base_url`.  
Valor por defecto: `/contacto`.

Para apuntar a un carrito Reseller específico:

```bash
wp --path=... option update gano_sota_cta_base_url "/ecosistemas#comprar"
```

---

## Publicar páginas SOTA

Una vez revisado el contenido en staging:

```bash
# Publicar una página individual
wp --path=... post update <ID> --post_status=publish

# Publicar todas las páginas SOTA de una vez
wp --path=... post list \
   --post_type=page \
   --post_status=draft \
   --meta_key=_gano_sota_category \
   --fields=ID \
   --format=ids | xargs -I {} \
   wp --path=... post update {} --post_status=publish

# Publicar el hub tras publicar las páginas
wp --path=... post update <HUB_ID> --post_status=publish
```

---

## Desactivar y eliminar el plugin

Una vez confirmadas las 20 páginas + hub en producción, el plugin puede eliminarse sin efecto sobre las páginas:

```bash
wp --path=... plugin deactivate gano-content-importer
wp --path=... plugin delete gano-content-importer
```

> **Nota:** Las páginas creadas permanecen en WordPress independientemente del plugin.

---

## Archivos relacionados

| Archivo | Descripción |
|---------|-------------|
| `wp-content/plugins/gano-content-importer/gano-content-importer.php` | Plugin importador — activa las páginas SOTA y el hub |
| `wp-content/themes/gano-child/templates/sota-single-template.php` | Template individual para páginas SOTA |
| `wp-content/themes/gano-child/templates/page-sota-hub.php` | Template del hub que lista todas las páginas |
| `wp-content/themes/gano-child/functions.php` | Función `gano_get_sota_hub_pages()` para el hub template |

---

## Dependencias

- **Issue #117** — Assets faltantes: imágenes de 16 páginas pendientes (`hero_digital_garden`, `decorative_abstract_seed`). Las páginas funcionan sin imágenes, pero el thumbnail quedará en blanco.
- **Issue #115** — Nav menus: para que `/hub-sota` aparezca en la navegación principal una vez publicado.
