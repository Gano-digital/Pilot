# Guía de implementación — Homepage Gano 2026 en Elementor

> Generada 2026-04-06 por Claude (tarea `cd-content-001`).
> Fuentes: `homepage-section-order-spec-2026.md`, `homepage-copy-2026-04.md`, `visual-tokens-wave3.md`, `elementor-home-classes.md`.
> Preview HTML standalone: `wp-content/themes/gano-child/templates/homepage-2026-preview.html`.

## Estado del CSS

✅ **Las 11 clases `gano-el-*` ya existen** en `wp-content/themes/gano-child/style.css` (líneas ~1140–1430).
✅ **Los 12 tokens visuales** (`--gano-fs-display`, `--gano-lh-*`, `--gano-fw-*`, `--gano-ls-*`, `--gano-gold-glow`) ya están en `:root`.
✅ **Skip link** y `:focus-visible` ya implementados.

**No hay que tocar PHP/CSS**. Solo construir/ajustar la home en Elementor con clases y copy correctos.

## Procedimiento (en staging primero)

### Paso 0 — Backup
1. Activar staging site (Managed WordPress Deluxe lo permite).
2. Exportar Elementor template actual de la home como respaldo: **Plantillas → Plantillas guardadas → Exportar**.

### Paso 1 — Estructura base
Crear nueva página `Home 2026` y editarla con Elementor. Usar **6 contenedores** (no secciones legacy) en este orden:

| # | Contenedor | Layout | CSS ID | Clases CSS |
|---|------------|--------|--------|------------|
| 1 | Hero | Grid 2 cols (móvil: 1 col) | `gano-main-content` | `gano-el-stack` |
| 2 | Pilares | Grid 2x2 | — | — (cards usan `gano-el-pillar`) |
| 3 | Socio | Single col centrada | — | — (texto usa `gano-el-prose-narrow`) |
| 4 | Métricas | Flex row | — | `gano-el-metrics` |
| 5 | Ecosistemas | Grid 3 cols | `ecosistemas` | — (botones usan `gano-btn`) |
| 6 | CTA final | Single col centrada | — | — (icons usan `gano-el-cta-icons`) |

### Paso 2 — Bloque 1 (Hero)
1. Contenedor padre → **Avanzado → Clases CSS:** `gano-el-stack` · **CSS ID:** `gano-main-content`.
2. Columna izquierda → Clase: `gano-el-layer-top`. Insertar:
   - Widget **Heading H1** con clase `gano-el-hero-readability`. Texto:
     > Infraestructura NVMe blindada y un agente IA que trabaja antes de que algo falle.
   - Widget **Text Editor** (subheadline):
     > Hosting WordPress de alto rendimiento con seguridad de nivel empresarial, soporte en español y operación en pesos colombianos. Menos ruido, más continuidad.
   - Widget **Button** primario (`gano-btn`): "Ver arquitecturas y planes" → `#ecosistemas`.
   - Widget **Button** secundario: "Hablar con el equipo" → `/contacto`.
   - Widget **Text** con clase `gano-el-hero-microcopy`:
     > NVMe · Monitoreo proactivo · Facturación en COP
3. Columna derecha → Clase: `gano-el-layer-base`. Imagen o shape decorativo con `aria-hidden="true"`.

### Paso 3 — Bloque 2 (Cuatro pilares)
1. Contenedor: layout grid 2×2 (1 col en móvil).
2. Heading **H2** sobre el grid: "Nuestras ventajas".
3. **4 contenedores hijos**, cada uno con clase `gano-el-pillar`:

| Pilar | Título (H3) | Texto |
|-------|------------|-------|
| 1 | NVMe que se nota en Core Web Vitals, no solo en el folleto. | Almacenamiento de nueva generación y stack optimizado para WordPress: menos espera, más conversión. Tu sitio carga cuando el cliente ya decidió quedarse. |
| 2 | WordPress endurecida para el tráfico real de un negocio. | Hardening continuo, controles de acceso y visibilidad sobre lo que ocurre en tu instalación. Menos superficie de ataque, más tranquilidad operativa. |
| 3 | Confianza cero por defecto: identidad, sesiones y permisos bajo control. | La seguridad no es un cartel: es política aplicada en capas. Menos suposiciones, más trazabilidad cuando importa. |
| 4 | Contenido más cerca del usuario, sin magia barata. | Arquitectura pensada para entregar experiencias rápidas donde vive tu audiencia. Menos saltos innecesarios, más respuesta perceptible. |

### Paso 4 — Bloque 3 (Socio tecnológico)
1. Contenedor con widget Heading **H2**: "Un socio tecnológico que trabaja en silencio."
2. Widget **Text Editor** con clase `gano-el-prose-narrow`:
   > Gano Digital no compite en "hosting barato". Compite en **continuidad**: infraestructura seria, soporte en tu idioma y visibilidad sobre lo que pasa detrás del sitio. Tú creces; nosotros sostenemos el piso técnico para que no te enteres de los incidentes por redes sociales.
3. Lista de bullets:
   - Infraestructura alineada a cargas reales de WordPress y comercio.
   - Monitoreo y respuesta con foco en negocio, no en excusas.
   - Roadmap claro hacia soberanía digital y cumplimiento en Colombia.
4. Enlace de texto: "Conoce nuestra historia →" → `/nosotros`.

### Paso 5 — Bloque 4 (Métricas)
1. Contenedor con clase `gano-el-metrics`. **Sin H2**.
2. **4 contenedores hijos** con clase `gano-el-metric`, cada uno con `<strong>` (cifra) + `<span>` (etiqueta):

| Cifra | Etiqueta |
|-------|----------|
| 99,9% | Disponibilidad objetivo (según plan) |
| NVMe  | Almacenamiento por slot |
| 24/7  | Soporte en español |
| COP   | Facturación local |

⚠️ **No inflar cifras**. Solo lo respaldado por SLA real.

### Paso 6 — Bloque 5 (Ecosistemas)
1. Contenedor con CSS ID `ecosistemas`, layout grid 3 cols.
2. Heading **H2** sobre el grid: "Elige tu arquitectura".
3. 3 tarjetas de plan (H3 nombre + texto + botón `gano-btn`):
   - **Núcleo Prime** → "Elegir este plan" → `/ecosistemas/nucleo-prime`
   - **Fortaleza Delta** → "Elegir este plan" → `/ecosistemas/fortaleza-delta`
   - **Bastión SOTA** → "Elegir este plan" → `/ecosistemas/bastion-sota` (estilo dark + dorado, ver `visual-tokens-wave3.md §2`)

⚠️ Los enlaces apuntarán al **catálogo Reseller** (PFIDs) cuando Diego confirme en RCC. Por ahora drafts/placeholders.

### Paso 7 — Bloque 6 (CTA final)
1. Contenedor full-width, fondo `--gano-dark-bg`.
2. Heading **H2**: "¿Listo para una infraestructura que no te pida disculpas?"
3. Shortcode `[gano_cta_icons]` (5 íconos FA: bolt, shield-halved, circle-check, headset, coins) — clase contenedor `gano-el-cta-icons`.
4. Botón **`gano-btn`** con texto "Elegir mi arquitectura" → `#ecosistemas`.

### Paso 8 — Validación a11y antes de publicar
- [ ] CSS ID `gano-main-content` asignado al primer contenedor del Hero.
- [ ] H1 único y visible sin scroll en 375px y 1280px.
- [ ] Ningún bloque salta jerarquía (H2 → H4 sin H3).
- [ ] Íconos decorativos: `aria-hidden="true"`.
- [ ] Imágenes con `alt` real.
- [ ] Tab navigation: el anillo dorado de `:focus-visible` se ve.
- [ ] Skip link aparece al primer Tab.
- [ ] Probar `prefers-reduced-motion` en micro-animaciones del hero.

### Paso 9 — Publicación
1. Vaciar caché (plugin + hosting).
2. Verificar en móvil real (no DevTools).
3. PageSpeed / CWV antes vs después.
4. Si OK → migrar a producción desde staging.

## Bloqueantes para Diego

1. **PFIDs ecosistemas** — confirmar en RCC para los 3 botones del Bloque 5.
2. **Imagen Hero** — definir cuál va en `gano-el-layer-base` (mockup actual usa shape).
3. **Datos métricas** — confirmar SLA real del Managed WordPress Deluxe para Bloque 4.

## Referencias rápidas

- Preview HTML: `wp-content/themes/gano-child/templates/homepage-2026-preview.html`
- Copy: `memory/content/homepage-copy-2026-04.md`
- Spec orden: `memory/content/homepage-section-order-spec-2026.md`
- Tokens: `memory/content/visual-tokens-wave3.md`
- Clases: `memory/content/elementor-home-classes.md`
- Story arc: `memory/content/homepage-story-arc-wave3.md`
