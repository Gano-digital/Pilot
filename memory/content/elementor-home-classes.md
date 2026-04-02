# Elementor homepage — clases CSS Gano (child theme)

**Versión tema:** `gano-child` 1.0.1+  
**Copy:** [`homepage-copy-2026-04.md`](homepage-copy-2026-04.md)

En Elementor: selecciona el **contenedor / sección / columna** → **Avanzado** → **Clases CSS** (añade una o varias separadas por espacio).

| Sección (según fixplan) | Clases sugeridas | Notas |
|-------------------------|------------------|--------|
| Hero (texto + ilustración) | `gano-el-stack` en el contenedor padre; `gano-el-layer-top` en columna de copy; `gano-el-layer-base` en columna de imagen/shape | Corrige solapamiento típico (z-index). Opcional: `gano-el-hero-readability` en el contenedor del título; `gano-el-hero-microcopy` en el widget de texto con el microcopy bajo los CTAs ("NVMe · Monitoreo proactivo · Facturación en COP"). |
| Pilares (4 cards) | `gano-el-pillar` en cada tarjeta/columna | Glass ligero, hover, tipografía alineada a tokens. |
| Socio tecnológico | `gano-el-prose-narrow` en el contenedor del texto | Centra y limita ancho de párrafo. |
| Métricas | `gano-el-metrics` en la fila; `gano-el-metric` en cada celda | Usa título + texto pequeño; no inventar cifras. |
| CTA final (iconos) | `gano-el-cta-icons` en la fila | Sustituye ★ por iconos Elementor/Font Awesome por columna. |

## Menú WordPress

- El tema padre **Royal Elementor Kit** registra solo la ubicación **`main`**.
- **Gano child** registra además **`primary`** (`after_setup_theme` prioridad 20).
- En **Apariencia → Menús**: asigna el menú a **“Menú principal (header / Elementor)”** y, si el header de Elementor pide “Primary”, debería aparecer. Si el kit usa solo `main`, sigue usando esa ubicación.

## Auditoría rápida (repo, Abril 2026)

| Tema | Hallazgo | Acción |
|------|----------|--------|
| Navegación | `primary` ausente en padre | Registrado en `gano-child` |
| Homepage | Lorem / overlap en servidor | Copy en MD + clases de capa/pilar |
| CSS | `:root` duplicado (oro solo en bloque shop) | Aceptado; utilidades homepage usan tokens del primer `:root` + sombras |
| `functions.php` | Handle `hello-elementor-parent-style` heredado del nombre | Padre real es `royal-elementor-kit`; el URI es correcto; renombrar handle es cosmético (opcional) |

Tras desplegar `style.css` y `functions.php` al servidor, vacía **caché** (plugin/hosting) y revisa la home en móvil.
