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

## Clases SOTA canónicas (Abril 2026)

Estas clases se pueden usar en Home, Ecosistemas y landings para mantener consistencia sin Tailwind:

- `gano-sota-surface`: fondo oscuro base con contraste de marca.
- `gano-sota-section`: espaciado vertical consistente por secciones.
- `gano-sota-container`: contenedor máximo de 1200px.
- `gano-sota-label`: etiqueta superior tipo badge técnico.
- `gano-sota-hero`: shell del hero principal.
- `gano-sota-hero__title` y `gano-sota-hero__title--accent`: heading SOTA con acento degradado.
- `gano-sota-hero__sub`: subtítulo legible sobre fondos oscuros.
- `gano-sota-hero__cta-row`: fila de CTAs con wrapping.
- `gano-sota-glass-card`: superficie glass reutilizable.
- `gano-sota-status-strip` + `gano-sota-status-strip__grid`: franja de métricas.
- `gano-sota-cards-grid` + `gano-sota-card`: cards de valor / features.
- `gano-sota-quiz-shell`: contenedor canónico del diagnóstico digital.

Regla de oro:

- Home en Elementor usa estas clases como capa visual.
- Templates PHP (`shop-premium`, `ecosistemas`, `diagnóstico`) reutilizan los mismos bloques.
- Si necesitas un estilo nuevo, primero conviértelo en clase reutilizable en `style.css`.

## Clases Kinetic Convergence (Abril 2026)

Bridge visual para converger la identidad base y el lenguaje premium de Obsidian Prism sin usar Tailwind.

- `gano-km-shell`: wrapper de seccion premium (fondo nocturno + grid sutil).
- `gano-km-container`: contenedor maximo para composicion 2 columnas.
- `gano-km-live-badge`: badge operativo con pulso.
- `gano-km-hero`: layout hero convergente.
- `gano-km-title` + `gano-km-title-accent`: titular editorial con acento gradiente.
- `gano-km-lead`: parrafo principal del hero.
- `gano-km-cta-row`: fila flexible de CTAs.
- `gano-km-btn-primary` / `gano-km-btn-secondary`: botones premium reutilizables.
- `gano-km-prism`: contenedor visual para pieza insignia (prism/constellation/ilustracion).
- `gano-km-metrics` + `gano-km-metric*`: franja de metricas de confianza.
- `gano-km-value-grid` + `gano-km-card`: cards de propuesta de valor.

Fuente tecnica:

- CSS: `wp-content/themes/gano-child/css/gano-sota-convergence.css`
- Guia de uso: `sota-convergence-code-pack-2026-04.md`
