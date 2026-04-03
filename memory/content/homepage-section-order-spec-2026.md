# Especificación de orden de secciones — Homepage Gano Digital (2026)

> **Propósito:** documento ejecutable que converge `homepage-story-arc-wave3.md` y  
> `homepage-copy-2026-04.md` en una lista numerada de bloques lista para implementar  
> en Elementor. Cada entrada indica: etapa AIDA, objetivo de conversión, fuente de  
> copy y clase CSS `gano-el-*`.
>
> **Accesibilidad:** ver sección §7 al final (skip link, jerarquía H1–H2, WCAG AA).
>
> **Último actualizado:** Abril 2026 — Oleada 4

---

## Orden canónico de bloques

### Bloque 1 — Hero

| Campo | Valor |
|-------|-------|
| **Etapa AIDA** | Atención |
| **Objetivo de conversión** | Capturar atención en < 3 s; comunicar propuesta de valor y categoría (NVMe, soporte es-CO, COP). Invitar al primer CTA sin scroll. |
| **Copy fuente** | [`homepage-copy-2026-04.md § Hero`](homepage-copy-2026-04.md#hero) |
| **Clase CSS contenedor** | `gano-el-stack` |
| **Clases CSS hijas** | `gano-el-layer-top` (columna de copy) · `gano-el-layer-base` (columna imagen/shape) · `gano-el-hero-readability` (widget título) · `gano-el-hero-microcopy` (microcopy bajo CTAs) |
| **Jerarquía tipográfica** | `H1` único (Headline) + `<p>` subheadline; sin `H2` en este bloque |
| **CTA** | Primario: "Ver arquitecturas y planes"; secundario: "Hablar con el equipo" |
| **Notas Elementor** | Un solo CTA dominante visible en primer viewport (375 px y 1280 px). CTA secundario debajo del fold en móvil. |

---

### Bloque 2 — Cuatro pilares

| Campo | Valor |
|-------|-------|
| **Etapa AIDA** | Interés |
| **Objetivo de conversión** | Traducir promesa técnica a beneficios concretos; reducir tasa de salida post-hero. |
| **Copy fuente** | [`homepage-copy-2026-04.md § Cuatro pilares`](homepage-copy-2026-04.md#cuatro-pilares-reemplaza-lorem-en-cada-card) |
| **Clase CSS contenedor** | *(fila grid)* — sin clase obligatoria en el contenedor padre |
| **Clase CSS por tarjeta** | `gano-el-pillar` |
| **Jerarquía tipográfica** | `H2` de sección (p.e. "Nuestras ventajas") + `H3` en cada tarjeta (título pilar); `<p>` de descripción |
| **CTA** | Sin CTA en este bloque; la tarjeta es informacional |
| **Notas Elementor** | Rejilla 2×2 en escritorio; apiladas en móvil. Íconos SVG con `aria-hidden="true"` si son decorativos. |

---

### Bloque 3 — Un socio tecnológico

| Campo | Valor |
|-------|-------|
| **Etapa AIDA** | Interés → Prueba social |
| **Objetivo de conversión** | Establecer credibilidad diferencial; posicionar a Gano como aliado técnico, no commodity. Desactivar objeción "¿son confiables?". |
| **Copy fuente** | [`homepage-copy-2026-04.md § Bloque "Un socio tecnológico"`](homepage-copy-2026-04.md#bloque-un-socio-tecnológico) |
| **Clase CSS contenedor** | *(sin clase obligatoria)* |
| **Clase CSS texto** | `gano-el-prose-narrow` |
| **Jerarquía tipográfica** | `H2` de bloque; `<ul>` de bullets sin encabezado adicional |
| **CTA** | Opcional: enlace de texto "Conoce nuestra historia" (no botón primario) |
| **Notas Elementor** | Honestidad Reseller: no afirmar datacenter propio. Resaltar acompañamiento, idioma es-CO y operación en COP. |

---

### Bloque 4 — Métricas / Franja de confianza

| Campo | Valor |
|-------|-------|
| **Etapa AIDA** | Prueba social |
| **Objetivo de conversión** | Anclar promesas del hero con cifras o indicadores verificables; reducir fricciones cognitivas antes de ver precios. |
| **Copy fuente** | [`homepage-copy-2026-04.md § Métricas / franjas`](homepage-copy-2026-04.md#métricas--franjas-sin-inflar-cifras) |
| **Clase CSS fila** | `gano-el-metrics` |
| **Clase CSS celda** | `gano-el-metric` |
| **Jerarquía tipográfica** | Sin encabezado `H2` en este bloque (franja visual); etiquetas de métricas en `<span>` o `<p>` |
| **CTA** | Sin CTA en este bloque |
| **Notas Elementor** | Solo cifras respaldadas por SLA o rango prudente. No inventar logos de clientes sin contrato. |

---

### Bloque 5 — Ecosistemas / Planes

| Campo | Valor |
|-------|-------|
| **Etapa AIDA** | Deseo |
| **Objetivo de conversión** | Concretar la oferta; el visitante elige su arquitectura. Generar up-sell natural (Núcleo Prime → Fortaleza Delta → Bastión SOTA). |
| **Copy fuente** | Catálogo WooCommerce / Reseller Store (precios en COP). Ver también [`ecosystems-copy-matrix-wave3.md`](ecosystems-copy-matrix-wave3.md) |
| **Clase CSS contenedor** | *(grilla de tarjetas)* — coherente con `gano-el-pillar` en tipografía/tamaño |
| **Clase CSS CTA por tarjeta** | Botón Elementor con clase `gano-btn` (tokens naranja) |
| **Jerarquía tipográfica** | `H2` de sección ("Elige tu arquitectura") + nombre del plan en `H3` por tarjeta |
| **CTA** | "Elegir este plan" en cada tarjeta — enlace a `/ecosistemas` o shop Reseller |
| **Notas Elementor** | Precios en COP; formulación "desde $X/mes" con enlace a página de detalle. Máximo 3 clics de home a checkout. |

---

### Bloque 6 — CTA final

| Campo | Valor |
|-------|-------|
| **Etapa AIDA** | Acción |
| **Objetivo de conversión** | Cierre de página: recoge visitantes que hicieron scroll completo sin convertir. Una pregunta retórica + un botón de conversión principal. |
| **Copy fuente** | [`homepage-copy-2026-04.md § CTA final`](homepage-copy-2026-04.md#cta-final) |
| **Clase CSS contenedor** | `gano-el-cta-icons` (si incluye iconos de beneficios) |
| **Clase CSS botón** | `gano-btn` + fondo `--gano-orange` |
| **Jerarquía tipográfica** | `H2` retórico ("¿Listo para una infraestructura que no te pida disculpas?") + botón |
| **CTA** | "Elegir mi arquitectura" |
| **Notas Elementor** | Sin distractores adicionales. Usar shortcode `[gano_cta_icons]` si se quieren íconos de beneficio. `focus-visible` obligatorio en el botón; contraste mínimo WCAG AA sobre fondo oscuro. |

---

## Resumen de flujo (visualización rápida)

```
┌────────────────────────────────────────────────────────────────────┐
│ 1. HERO              Atención          primer viewport             │
│    └── H1 único · CTA primario visible sin scroll                  │
├────────────────────────────────────────────────────────────────────┤
│ 2. CUATRO PILARES    Interés           cards 2×2                   │
│    └── H2 sección + H3 por pilar · sin CTA                        │
├────────────────────────────────────────────────────────────────────┤
│ 3. SOCIO TECNOLÓGICO Interés/Confianza manifiesto                  │
│    └── H2 + bullets · CTA de texto opcional                        │
├────────────────────────────────────────────────────────────────────┤
│ 4. MÉTRICAS          Prueba social     franja visual               │
│    └── sin H2 · cifras verificables únicamente                     │
├────────────────────────────────────────────────────────────────────┤
│ 5. ECOSISTEMAS/PLANES Deseo           tarjetas de plan             │
│    └── H2 sección + H3 por plan · botón "Elegir este plan"         │
├────────────────────────────────────────────────────────────────────┤
│ 6. CTA FINAL         Acción           cierre de página             │
│    └── H2 retórico · botón naranja "Elegir mi arquitectura"        │
└────────────────────────────────────────────────────────────────────┘
```

---

## Mapa rápido: Bloque → clase CSS `gano-el-*`

| # | Bloque | Clase(s) CSS |
|---|--------|-------------|
| 1 | Hero (contenedor) | `gano-el-stack` |
| 1 | Hero (columna copy) | `gano-el-layer-top` |
| 1 | Hero (columna imagen) | `gano-el-layer-base` |
| 1 | Hero (widget título) | `gano-el-hero-readability` |
| 1 | Hero (microcopy) | `gano-el-hero-microcopy` |
| 2 | Pilar (cada tarjeta) | `gano-el-pillar` |
| 3 | Socio tecnológico (texto) | `gano-el-prose-narrow` |
| 4 | Métricas (fila) | `gano-el-metrics` |
| 4 | Métrica (celda) | `gano-el-metric` |
| 5 | Ecosistemas (botón CTA) | `gano-btn` |
| 6 | CTA final (iconos) | `gano-el-cta-icons` |
| 6 | CTA final (botón) | `gano-btn` |

> **Referencia completa** de las clases y su CSS declarado: [`elementor-home-classes.md`](elementor-home-classes.md)

---

## §7 — Accesibilidad (skip link, jerarquía H1–H2)

### Skip-to-content link (WCAG 2.4.1)

El child theme `gano-child` implementa un skip link mediante las funciones
`gano_skip_link()` y `gano_main_content_anchor()` enlazadas al hook `wp_body_open`.

- El destino del salto es `<span id="gano-main-content">` que debe estar en el
  **primer bloque significativo de la página** (Bloque 1 — Hero).
- En Elementor: asignar el **CSS ID** `gano-main-content` a la primera sección
  del canvas desde **Avanzado → ID CSS**.
- El enlace es invisible hasta recibir foco de teclado; al hacerlo sube con
  transición suave desde `top: -100%` hasta `top: 0`.

```html
<!-- Generado automáticamente por gano_skip_link() -->
<a class="gano-skip-link" href="#gano-main-content">
  Saltar al contenido principal
</a>
```

**Referencia de implementación:** [`ux-a11y-wave3-notes.md § Skip-to-content link`](ux-a11y-wave3-notes.md)

---

### Jerarquía de encabezados H1–H2 (WCAG 1.3.1)

| Nivel | Elemento en la página | Bloque |
|-------|-----------------------|--------|
| `H1` | Headline del Hero — **único en toda la página** | Bloque 1 |
| `H2` | Encabezado de sección "Nuestras ventajas" (o similar) | Bloque 2 |
| `H3` | Título de cada tarjeta pilar (×4) | Bloque 2 |
| `H2` | Encabezado "Un socio tecnológico que trabaja en silencio" | Bloque 3 |
| *(sin H2)* | Franja de métricas — etiquetas en `<span>`/`<p>` | Bloque 4 |
| `H2` | "Elige tu arquitectura" | Bloque 5 |
| `H3` | Nombre de cada plan (×3) | Bloque 5 |
| `H2` | Pregunta retórica del CTA final | Bloque 6 |

**Reglas:**

1. Solo un `H1` por página. En Elementor: widget Heading configurado como `H1`
   en la sección Hero; los demás títulos de sección deben ser `H2`.
2. No saltar niveles (p.e. pasar de `H2` a `H4` sin `H3` intermedio).
3. El orden de los encabezados debe seguir el flujo visual de arriba abajo.
4. Los íconos decorativos en tarjetas y CTAs deben tener `aria-hidden="true"`.

---

### Contraste y `focus-visible` (WCAG 1.4.3 / 2.4.7)

| Elemento | Ratio mínimo | Estado |
|----------|-------------|--------|
| Texto normal sobre fondos claros | 4.5:1 | Verificado (tokens `--gano-dark` / `--gano-blue`) |
| Texto grande y botones | 3:1 | Verificado (botón naranja `--gano-orange` sobre `--gano-dark`) |
| Outline `focus-visible` global | 3px `--gano-blue` | Implementado en `style.css` |
| Outline `focus-visible` botones/CTA | 3px `--gano-gold` | Implementado en `style.css` |

> Ver guía completa: [`ux-a11y-wave3-notes.md`](ux-a11y-wave3-notes.md)

---

### Checklist de accesibilidad antes de publicar

- [ ] `CSS ID: gano-main-content` asignado a la primera sección del Hero en Elementor.
- [ ] `H1` único y visible sin scroll en 375 px (móvil) y 1280 px (escritorio).
- [ ] Ningún pilar, métrica ni sección usa `H1` o salta de `H2` a `H4`.
- [ ] Íconos SVG/Font Awesome decorativos tienen `aria-hidden="true"`.
- [ ] Todas las imágenes tienen atributo `alt` descriptivo (o `alt=""` si decorativas).
- [ ] Botón CTA final tiene `focus-visible` visible con contraste ≥ 3:1.
- [ ] `prefers-reduced-motion` respetado en animaciones GSAP del hero.
- [ ] Skip link aparece al presionar **Tab** desde la barra de dirección.

---

## Referencias cruzadas

| Archivo | Descripción |
|---------|-------------|
| [`homepage-copy-2026-04.md`](homepage-copy-2026-04.md) | Texto completo de cada bloque listo para pegar en Elementor |
| [`homepage-story-arc-wave3.md`](homepage-story-arc-wave3.md) | Justificación narrativa AIDA y reglas de coherencia |
| [`elementor-home-classes.md`](elementor-home-classes.md) | Detalle de cada clase CSS `gano-el-*`, tokens visuales y auditoría |
| [`elementor-architecture-wave3.md`](elementor-architecture-wave3.md) | Guía de arquitectura Elementor (contenedores, Theme Builder) |
| [`ux-a11y-wave3-notes.md`](ux-a11y-wave3-notes.md) | Implementación completa del skip link y `focus-visible` |
| [`ecosystems-copy-matrix-wave3.md`](ecosystems-copy-matrix-wave3.md) | Copy de planes/ecosistemas para Bloque 5 |

---

_Especificación generada — Oleada 4 · Abril 2026. Mantener sincronizado con `homepage-copy-2026-04.md` y `elementor-home-classes.md`._
