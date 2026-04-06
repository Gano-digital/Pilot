# Guía de activación — 20 páginas SOTA (cd-content-003)

> Generada 2026-04-06 por Claude. Tarea: `cd-content-003`.

## Estado del plugin
- Archivo: `wp-content/plugins/gano-content-importer/gano-content-importer.php`
- Versión: 2.0.0
- Las 20 páginas están definidas en `gano_get_pages_data_v2()`.
- Se crean como **draft** al activar el plugin (no se publican automáticamente).
- Se eliminan duplicados si ya existen por título.

## Categorías y páginas

| # | Título | Categoría | Imagen |
|---|--------|-----------|--------|
| 1 | Arquitectura NVMe: El Manifiesto de la Velocidad Crítica | infraestructura | icon_nvme_speed.png ✅ |
| 2 | Zero-Trust: El Fin de la Confianza Implícita | seguridad | icon_zero_trust_security.png ✅ |
| 3 | Gestión Predictiva con AI: Cero Caídas, Cero Sorpresas | inteligencia-artificial | icon_predictive_ai_server.png ✅ |
| 4 | Soberanía Digital en LATAM: Tus Datos, Tu Control | estrategia | — |
| 5 | Headless WordPress: La Velocidad Absoluta | rendimiento | — |
| 6 | Mitigación DDoS Inteligente: Firewall de Nueva Generación | seguridad | — |
| 7 | La Muerte del Hosting Compartido: El Riesgo Invisible | estrategia | — |
| 8 | Edge Computing: Contenido a Cero Distancia | rendimiento | icon_edge_computing.png ✅ |
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

## Procedimiento de activación (staging)

### 1. Activar en staging primero
```bash
# Vía wp-cli en el servidor (staging)
wp --path=/home/f1rml03th382/public_html/gano.digital \
   plugin activate gano-content-importer

# Verificar cuántas páginas se crearon
wp --path=/home/f1rml03th382/public_html/gano.digital \
   post list --post_type=page --post_status=draft --meta_key=_gano_sota_category --format=table
```

### 2. Verificar drafts
Ir a **wp-admin → Páginas → Borradores** y confirmar que aparecen las 20 páginas SOTA.

### 3. Asignar el template de hub
Crear página `/hub-sota` con Template: **Hub SOTA — Innovación Gano** (`templates/page-sota-hub.php`).

### 4. Revisar y publicar de a poco
No publicar las 20 de golpe. Orden sugerido:
1. Revisar copy y CTA de cada página (contenido ya viene del plugin).
2. Verificar que `sota-single-template.php` se aplica y carga `gano-sota-animations.css`.
3. Publicar en lotes de 5 por categoría.
4. Actualizar menú de navegación si se quiere incluir `/hub-sota`.

### 5. Deactivate + delete
Tras confirmar las 20 páginas publicadas correctamente:
```bash
wp --path=... plugin deactivate gano-content-importer
wp --path=... plugin delete gano-content-importer
```
⚠️ No eliminar el plugin hasta confirmar todas las páginas en producción.

## Template del hub
`wp-content/themes/gano-child/templates/page-sota-hub.php` — creado en esta tarea.
Lista las páginas por categoría, solo muestra las **publicadas**. Mientras estén en draft, muestra mensaje "Contenido en preparación".

## GitHub issue para agentes
Issue #119 (ver Gano-digital/Pilot) — activar plugin en staging, revisar drafts, publicar.

## Bloqueantes
- 16 páginas sin imagen featured asignada (attachment IDs faltantes). Ver issue #117.
- Hasta que issue #115 (nav menus) esté resuelto, la página hub no aparece en navegación.
