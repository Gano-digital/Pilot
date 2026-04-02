# Gano Digital — Tokens visuales oleada 3: escala tipográfica y contraste

**Generado:** Abril 2026  
**Fuente:** Brief maestro oleada 3 §3 sistema visual + tokens actuales en `gano-child/style.css`  
**PR asociado:** extiende `:root` en `style.css` con tokens de display, line-height, font-weight, letter-spacing y regla `:focus-visible`.

---

## 1. Escala tipográfica

Todos los tamaños usan la variable CSS correspondiente en `style.css`. Las columnas "Mín" y "Máx" aplican donde se usa `clamp()`.

| Nivel | Token CSS | px base | Mín | Máx | Line-height | Font-weight | Letter-spacing | Uso principal |
|-------|-----------|---------|-----|-----|------------|------------|----------------|---------------|
| **Display** | `--gano-fs-display` | 72 px | 48 px | 72 px | `--gano-lh-tight` (1.1) | `--gano-fw-extrabold` (800) | `--gano-ls-tight` (−0.03em) | Hero principal, titular de sección de impacto máximo |
| **H1** | `--gano-fs-5xl` | 48 px | 30 px | 48 px | `--gano-lh-tight` (1.1) | `--gano-fw-bold` (700) | −0.02em (valor base de headings) | Título único por página, template landing SEO |
| **H2** | `--gano-fs-4xl` | 36 px | 24 px | 36 px | `--gano-lh-snug` (1.25) | `--gano-fw-bold` (700) | `--gano-ls-tight` (−0.02em) | Secciones de pilares, características, ecosistemas |
| **H3** | `--gano-fs-3xl` | 30 px | 20 px | 30 px | `--gano-lh-snug` (1.25) | `--gano-fw-semibold` (600) | −0.01em | Tarjetas de producto, subtiturlos de sección |
| **H4** | `--gano-fs-xl` | 20 px | — | — | `--gano-lh-base` (1.5) | `--gano-fw-semibold` (600) | 0 | Etiquetas de funcionalidad, tablas de precios |
| **Body** | `--gano-fs-base` | 16 px | — | — | `--gano-lh-relaxed` (1.65) | `--gano-fw-normal` (400) | 0 | Párrafos, descripciones, copy de beneficios |
| **Small / Label** | `--gano-fs-sm` | 14 px | — | — | `--gano-lh-base` (1.5) | `--gano-fw-normal` (400) | 0 | Microcopy, notas de precio, pie de tarjeta |
| **XS / Meta** | `--gano-fs-xs` | 12 px | — | — | `--gano-lh-base` (1.5) | `--gano-fw-normal` (400) | 0.01em | Badges, etiquetas de estado, metadatos SEO |

> **Fuentes:** `--gano-font-heading` (`'Plus Jakarta Sans'`) en niveles Display–H4; `--gano-font-body` (`'Inter'`) en Body–XS.

---

## 2. Uso de oro sobre oscuro (`--gano-gold` sobre fondos dark)

### 2.1 Paleta de acento dorado

| Token | Valor | Nombre de uso |
|-------|-------|---------------|
| `--gano-gold` | `#D4AF37` | Acento premium — Bastión SOTA, Kinetic Monolith |
| `--gano-gold-soft` | `rgba(212, 175, 55, 0.10)` | Fondo sutil en tarjetas SOTA, borders suaves |
| `--gano-gold-glow` | `rgba(212, 175, 55, 0.20)` | Sombra / glow hover en cards premium |
| `--gano-dark-bg` | `#0F1115` | Fondo oscuro profundo de secciones SOTA/shop |

### 2.2 Contraste y WCAG AA

| Combinación | Ratio estimado | WCAG AA (4.5:1 texto normal) | Recomendación |
|-------------|---------------|------------------------------|---------------|
| `#D4AF37` sobre `#0F1115` | ≈ 7.5 : 1 | ✅ Supera AA y AAA | Usar para texto de énfasis, cifras, badges |
| `#D4AF37` sobre `#0F1923` | ≈ 7.3 : 1 | ✅ Supera AA y AAA | Válido en fondo `--gano-bg-dark` |
| `#D4AF37` sobre `#1A2535` | ≈ 6.1 : 1 | ✅ AA | Aceptable para textos de apoyo |
| `#FFFFFF` sobre `#0F1115` | ≈ 19.1 : 1 | ✅ AAA | Texto body sobre fondos oscuros |
| `#94a3b8` (gray) sobre `#0F1115` | ≈ 4.8 : 1 | ✅ AA (ajustado) | Usar con tamaño ≥ 16 px |

> ⚠️ **Evitar:** usar `--gano-gold-soft` (fondo tenue) como color de texto — el contraste sobre blanco es insuficiente para WCAG AA.  
> ⚠️ **Evitar:** texto de 12 px en dorado sobre `--gano-gray-700` sin verificar contraste.

### 2.3 Reglas de uso (Bastión SOTA / secciones oscuras)

1. **Texto de acento / titulares cortos** → `color: var(--gano-gold)` sobre `background: var(--gano-dark-bg)`.
2. **Bordes de tarjeta hover** → `border-color: var(--gano-gold)`.
3. **Badge "Premium"** → `background: var(--gano-gold); color: #0F1115` (negro, contraste AAA).
4. **Cuerpo de texto** → siempre `#FFFFFF` o `--gano-gray-300` sobre fondos oscuros; nunca dorado para párrafos largos.
5. **Íconos decorativos** → `--gano-gold` con `aria-hidden="true"` en SVG.

---

## 3. Focus visible (accesibilidad)

### 3.1 Regla CSS implementada

```css
/* Accesibilidad: focus-visible — anillo de foco */
:focus-visible {
    outline: 3px solid var(--gano-gold);
    outline-offset: 3px;
    border-radius: var(--gano-radius-sm);
}

/* Suprime outline :focus heredado solo donde :focus-visible existe */
:focus:not(:focus-visible) {
    outline: none;
}

/* Sobre fondos claros: anillo azul corporativo */
.gano-bg-light :focus-visible,
.elementor-section.bg-light :focus-visible {
    outline-color: var(--gano-blue);
}
```

### 3.2 Rationale WCAG 2.4.11 (AA)

| Criterio | Requerimiento | Implementación |
|----------|--------------|----------------|
| WCAG 2.1 – 2.4.7 Focus Visible | Foco teclado visible | ✅ `outline: 3px solid` siempre presente en `:focus-visible` |
| WCAG 2.2 – 2.4.11 Focus Appearance | Mínimo 3:1 contraste del indicador, ≥ 2px | ✅ Oro `#D4AF37` sobre `#0F1115` ≈ 7.5:1; grosor 3 px |
| No eliminar outline en `:focus` global | Solo suprimir si `:focus-visible` disponible | ✅ Selector `:focus:not(:focus-visible)` |

> Los navegadores modernos (Chrome 86+, Firefox 85+, Safari 15.4+) soportan `:focus-visible`.  
> Para browsers sin soporte, el `outline` de `:focus` ya existe en los estilos base del tema padre.

---

## 4. Tokens nuevos añadidos al `:root` de `style.css`

Los siguientes custom properties se añadieron al bloque principal `:root` (junto a los tokens existentes):

```css
/* Tamaño display — por encima de 5xl */
--gano-fs-display: clamp(3rem, 6vw, 4.5rem); /* 48–72 px */

/* Escala de line-height */
--gano-lh-tight:   1.1;
--gano-lh-snug:    1.25;
--gano-lh-base:    1.5;
--gano-lh-relaxed: 1.65;
--gano-lh-loose:   1.85;

/* Escala de font-weight */
--gano-fw-normal:    400;
--gano-fw-medium:    500;
--gano-fw-semibold:  600;
--gano-fw-bold:      700;
--gano-fw-extrabold: 800;

/* Escala de letter-spacing */
--gano-ls-tight:  -0.03em;
--gano-ls-normal: 0em;
--gano-ls-wide:   0.04em;
--gano-ls-wider:  0.08em;

/* Oro — promovido al bloque global (era solo en Phase 6) */
--gano-gold:      #D4AF37;    /* Acento SOTA — contraste ≥ 7:1 sobre dark-bg */
--gano-gold-soft: rgba(212, 175, 55, 0.10);
--gano-gold-glow: rgba(212, 175, 55, 0.20);  /* Sombra hover en cards premium */
```

> `--gano-gold` y `--gano-gold-soft` se movieron del bloque "Phase 6" al `:root` global para que estén disponibles en cualquier componente del child theme.

---

## 5. Clases tipográficas utilitarias (Elementor)

Asignar en **Avanzado → Clases CSS** del widget de texto o sección:

| Clase | Efecto |
|-------|--------|
| `.gano-text-display` | Aplica `--gano-fs-display` + `--gano-lh-tight` + `--gano-fw-extrabold` |
| `.gano-text-h1` | Réplica semántica del `<h1>` para widgets Heading de Elementor |
| `.gano-text-gold` | `color: var(--gano-gold)` — solo para texto de énfasis corto |
| `.gano-text-muted` | Color `--gano-gray-500` para microcopy y notas de precio (ya definida en `style.css` línea ~506) |
| `.gano-dark-section` | `background: var(--gano-dark-bg); color: #fff` — sección SOTA |

---

## 6. Checklist de validación

- [ ] Verificar contraste con **Axe DevTools** o **Chrome DevTools → Accessibility** en secciones SOTA.
- [ ] Navegar con Tab por hero, shop y formulario de contacto: el anillo dorado debe ser visible.
- [ ] En `prefers-reduced-motion: reduce` → micro-animaciones de hover (`transition`) deben reducirse o eliminarse (ver regla existente en `style.css` sección Elementor utilities).
- [ ] Probar tipografía display en mobile (320 px): el `clamp()` garantiza mínimo 48 px.
- [ ] Actualizar `elementor-home-classes.md` si se crean clases `.gano-dark-section` o `.gano-text-display` en Elementor.

---

_Documento generado como insumo de diseño — no requiere PHP. Ver PR asociado para cambios en `style.css`._
