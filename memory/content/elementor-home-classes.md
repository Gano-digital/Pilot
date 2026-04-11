# Elementor homepage — clases CSS Gano (child theme)

**Versión tema:** `gano-child` 1.0.1+
**Copy:** [`homepage-copy-2026-04.md`](homepage-copy-2026-04.md)
**Arquitectura completa:** [`elementor-architecture-wave3.md`](elementor-architecture-wave3.md)
**Especificación de orden:** [`homepage-section-order-spec-2026.md`](homepage-section-order-spec-2026.md)

En Elementor: selecciona el **contenedor / sección / columna** → **Avanzado** → **Clases CSS** (añade una o varias separadas por espacio).

## Mapa completo: Bloque → clase CSS

| #   | Bloque                 | Elemento Elementor     | Clase(s) CSS               | Notas                                                                                                                         |
| --- | ---------------------- | ---------------------- | -------------------------- | ----------------------------------------------------------------------------------------------------------------------------- |
| 1   | **Hero**               | Contenedor padre       | `gano-el-stack`            | Corrige solapamiento de capas (z-index).                                                                                      |
| 1   | **Hero**               | Columna de copy        | `gano-el-layer-top`        | Asegura posición z-index sobre imagen de fondo.                                                                               |
| 1   | **Hero**               | Columna imagen/shape   | `gano-el-layer-base`       | Capa decorativa detrás del copy.                                                                                              |
| 1   | **Hero**               | Widget Heading (H1)    | `gano-el-hero-readability` | Mejora legibilidad sobre fondos con imagen.                                                                                   |
| 1   | **Hero**               | Widget texto microcopy | `gano-el-hero-microcopy`   | Texto bajo CTAs: "NVMe · Monitoreo proactivo · Facturación en COP".                                                           |
| 2   | **Cuatro pilares**     | Cada tarjeta/columna   | `gano-el-pillar`           | Glass ligero, hover, tipografía alineada a tokens.                                                                            |
| 3   | **Socio tecnológico**  | Contenedor del texto   | `gano-el-prose-narrow`     | Centra y limita ancho de párrafo (~65 ch).                                                                                    |
| 4   | **Métricas**           | Fila/contenedor        | `gano-el-metrics`          | Franja visual de confianza.                                                                                                   |
| 4   | **Métricas**           | Cada celda de métrica  | `gano-el-metric`           | Usa título grande + etiqueta pequeña; no inventar cifras.                                                                     |
| 5   | **Ecosistemas/Planes** | Botón CTA por tarjeta  | `gano-btn`                 | Tokens `--gano-orange`; texto blanco.                                                                                         |
| 6   | **CTA final**          | Fila con iconos        | `gano-el-cta-icons`        | Shortcode `[gano_cta_icons]`: 5 íconos FA (bolt, shield-halved, circle-check, headset, coins) con etiquetas y foco accesible. |
| 6   | **CTA final**          | Botón principal        | `gano-btn`                 | Fondo `--gano-orange`; `focus-visible` obligatorio; contraste WCAG AA.                                                        |

## Menú WordPress

- El tema padre **Royal Elementor Kit** registra solo la ubicación **`main`**.
- **Gano child** registra además **`primary`** (`after_setup_theme` prioridad 20).
- En **Apariencia → Menús**: asigna el menú a **“Menú principal (header / Elementor)”** y, si el header de Elementor pide “Primary”, debería aparecer. Si el kit usa solo `main`, sigue usando esa ubicación.

## Auditoría rápida (repo, Abril 2026)

| Tema            | Hallazgo                            | Acción                                                                                     |
| --------------- | ----------------------------------- | ------------------------------------------------------------------------------------------ |
| Navegación      | `primary` ausente en padre          | Registrado en `gano-child`                                                                 |
| Homepage        | Lorem / overlap en servidor         | Copy en MD + clases de capa/pilar                                                          |
| CSS             | `:root` principal + `:root` de shop | Aceptado; tokens de oro están globales y el bloque shop conserva variables de vidrio/fondo |
| `functions.php` | Handle parent style del child       | Ya usa `royal-elementor-kit-parent`; item cosmético cubierto en `main`                     |

## Hook LCP del Hero (MutationObserver)

El child theme inyecta un script en homepage para marcar la primera imagen del hero con `fetchpriority="high"` y `loading="eager"`.

Selectores esperados (en este orden):

- `.e-con .elementor-widget-image img`
- `.e-con-full .elementor-widget-image img`
- `.elementor-top-section .elementor-widget-image img`
- `.elementor-section .elementor-widget-image img`
- `.elementor-widget-image img`

Comportamiento:

- Intenta encontrar imagen inmediatamente.
- Si no existe aún en DOM, observa mutaciones (`MutationObserver`) hasta encontrarla.
- Se desconecta en `DOMContentLoaded` o a los 2500ms.

Validación rápida en front:

1. Abrir home publicada y ubicar primera imagen visual del hero.
2. Verificar en DevTools que tenga `fetchpriority="high"` y `loading="eager"`.
3. Si no aparece, revisar que el hero tenga un widget de imagen compatible con los selectores listados.
4. Si el hero cambia a otro widget o estructura, documentar nuevo selector antes de tocar JS.

Tras desplegar `style.css` y `functions.php` al servidor, vacía **caché** (plugin/hosting) y revisa la home en móvil.
