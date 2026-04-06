# Guía de activación — Hub de Innovación SOTA (cd-content-003)

**Referencia:** `cd-content-003`  
**Última actualización:** 2026-04-06  
**Rama:** `copilot/activate-gano-content-importer`

> Generada por Claude + Copilot. Tarea: activar `gano-content-importer` en staging y verificar borradores.

---

## Descripción general

El plugin `gano-content-importer` crea automáticamente:

- **20 páginas SOTA** como borradores (`draft`) en WordPress, cada una con la meta `_gano_sota_category` asignada.
- **1 página Hub** (`/hub-sota`) como borrador, con el template `templates/page-sota-hub.php`.

### Estado del plugin

- Archivo: `wp-content/plugins/gano-content-importer/gano-content-importer.php`
- Versión: 2.0.0
- Las 20 páginas están definidas en `gano_get_pages_data_v2()`.
- Se crean como **draft** al activar el plugin (no se publican automáticamente).
- Se eliminan duplicados si ya existen por título.

---

## Categorías y páginas (referencia)

| # | Título | Categoría | Imagen |
|---|--------|-----------|--------|
| 1 | Arquitectura NVMe: El Manifiesto de la Velocidad Crítica | infraestructura | icon_nvme_speed.png |
| 2 | Zero-Trust: El Fin de la Confianza Implícita | seguridad | icon_zero_trust_security.png |
| 3 | Gestión Predictiva con AI: Cero Caídas, Cero Sorpresas | inteligencia-artificial | icon_predictive_ai_server.png |
| 4 | Soberanía Digital en LATAM: Tus Datos, Tu Control | estrategia | — |
| 5 | Headless WordPress: La Velocidad Absoluta | rendimiento | — |
| 6 | Mitigación DDoS Inteligente: Firewall de Nueva Generación | seguridad | — |
| 7 | La Muerte del Hosting Compartido: El Riesgo Invisible | estrategia | — |
| 8 | Edge Computing: Contenido a Cero Distancia | rendimiento | icon_edge_computing.png |
| 9 | Green Hosting: Infraestructura Sostenible | estrategia | — |
| 10 | Cifrado Post-Cuántico: La Bóveda del Futuro | seguridad | — |
| 11 | CI/CD Automatizado: Nunca Más Rompas tu Tienda | rendimiento | — |
| 12 | Backups Continuos en Tiempo Real: Tu Máquina del Tiempo | infraestructura | — |
| 13 | Skeleton Screens: La Psicología de la Velocidad Percibida | rendimiento | — |
| 14 | Escalamiento Elástico: Sobrevive a tu Propio Éxito Viral | infraestructura | — |
| 15 | Self-Healing: El Ecosistema que se Cura Solo | infraestructura | — |
| 16 | Micro-Animaciones e Interacciones Hápticas | rendimiento | — |
| 17 | HTTP/3 y QUIC: El Protocolo que Rompe la Congestión | rendimiento | — |
| 18 | Alta Disponibilidad (HA): La Infraestructura Indestructible | infraestructura | — |
| 19 | Analytics Server-Side: Privacidad, Velocidad y Datos Reales | estrategia | — |
| 20 | El Agente IA de Administración: Tu Infraestructura Habla Español | inteligencia-artificial | — |

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

También en **wp-admin → Páginas → Borradores** confirmar que aparecen las 20 páginas SOTA.

---

## Categorías de páginas SOTA (agrupación)

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
|----------|----------------|
| 20 páginas en estado draft con `_gano_sota_category` | `wp post list --meta_key=_gano_sota_category` (debe devolver 20 filas) |
| Página hub `/hub-sota` creada | `wp post list --post_name__in='hub-sota'` |
| Template hub correcto | `wp post meta get <HUB_ID> _wp_page_template` → `templates/page-sota-hub.php` |
| Sin Lorem ipsum en drafts | `wp post list --fields=ID` + `wp post get <ID> --field=post_content` (revisar) |
| CTAs apuntan a `/contacto` | Valor por defecto de `gano_get_cta_url()` = `/contacto` |

**Issue GitHub para agentes:** #119 — activar plugin en staging, revisar drafts, publicar.

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

No publicar las 20 de golpe. Orden sugerido: revisar copy y CTA, verificar `sota-single-template.php` y `gano-sota-animations.css`, publicar en lotes por categoría, actualizar menú si se incluye `/hub-sota`.

```bash
# Publicar una página individual
wp --path=... post update <ID> --post_status=publish

# Publicar todas las páginas SOTA de una vez (cuando corresponda)
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

## Template del hub

`wp-content/themes/gano-child/templates/page-sota-hub.php` — lista por categoría vía `gano_get_sota_hub_pages()` y `gano_get_sota_categories()` en `functions.php`. Las páginas en **draft** aparecen como “En preparación”; las publicadas como tarjetas con enlace.

---

## Desactivar y eliminar el plugin

⚠️ No eliminar el plugin hasta confirmar todas las páginas en producción.

```bash
wp --path=... plugin deactivate gano-content-importer
wp --path=... plugin delete gano-content-importer
```

> Las páginas creadas permanecen en WordPress independientemente del plugin.

---

## Archivos relacionados

| Archivo | Descripción |
|---------|-------------|
| `wp-content/plugins/gano-content-importer/gano-content-importer.php` | Plugin importador |
| `wp-content/themes/gano-child/templates/sota-single-template.php` | Template individual SOTA |
| `wp-content/themes/gano-child/templates/page-sota-hub.php` | Template del hub |
| `wp-content/themes/gano-child/functions.php` | `gano_get_sota_hub_pages()`, `gano_get_sota_categories()` |

---

## Dependencias y bloqueantes

- **Issue #117** — Assets faltantes: imágenes de varias páginas pendientes. Las páginas funcionan sin imágenes, pero el thumbnail puede quedar en blanco.
- **Issue #115** — Nav menus: para que `/hub-sota` aparezca en la navegación principal una vez publicado.
