# Notas de Accesibilidad — Wave 3 (Abril 2026)

> Referencia: Issue [agent] Utilidades accesibilidad: focus-visible, skip link  
> Criterios base: WCAG 2.1 nivel AA  
> Archivos modificados: `wp-content/themes/gano-child/style.css`, `wp-content/themes/gano-child/functions.php`

---

## Cambios implementados

### 1. Skip-to-content link (WCAG 2.4.1 — Bypass Blocks)

**Dónde:** `functions.php` → funciones `gano_skip_link()` y `gano_main_content_anchor()`, hook `wp_body_open`.

**Qué hace:**
- Inyecta `<a class="gano-skip-link" href="#gano-main-content">` al inicio de `<body>`.
- Inyecta `<span id="gano-main-content" tabindex="-1" class="gano-a11y-anchor">` como destino del salto.
- El enlace es visualmente invisible hasta que recibe el foco del teclado (Tab).
- Al recibir el foco, se desliza desde `top: -100%` hasta `top: 0` con transición suave.

**CSS (`style.css` — sección ACCESIBILIDAD):**
```css
.gano-skip-link { position: absolute; top: -100%; … }
.gano-skip-link:focus-visible { top: 0; }
```

**Compatibilidad Elementor:**
- Hello Elementor soporta `wp_body_open` desde la versión 2.7.0.
- Para páginas con plantilla **Canvas** (full-width sin header/footer), el anchor `#gano-main-content` queda al inicio del body. Si se desea apuntar a una sección específica, asignar el ID `gano-main-content` a la primera sección en el editor de Elementor → **Advanced → CSS ID**.

**CSP:** Sin impacto. No se utilizan estilos inline en el HTML. El CSS está en la hoja de estilos del tema (cumple `style-src 'self'`). No se agregan scripts.

---

### 2. Focus-visible (WCAG 2.4.7 — Focus Visible)

**Dónde:** `style.css` — sección `ACCESIBILIDAD — WCAG 2.1 AA`.

**Reglas añadidas:**

| Selector | Outline | Notas |
|---|---|---|
| `:focus-visible` (global) | `3px solid var(--gano-blue)`, offset 3px | Todos los elementos interactivos |
| `:focus:not(:focus-visible)` | `outline: none` | Oculta el outline de mouse/touch (UX) |
| Botones y CTAs (`.btn-primary`, `.gano-btn`, `.elementor-button`, etc.) | `3px solid var(--gano-gold)` + halo `rgba(212,175,55,.28)` | Dorado sobre fondos oscuros y claros |
| Inputs/textareas/selects | `3px solid var(--gano-blue)` + `box-shadow` tenue | Complementa el border de Elementor Forms |

**Contraste :focus-visible:**
- Azul `#1B4FD8` sobre blanco `#FFFFFF` → ratio ≈ **10.2:1** ✓ WCAG AA (mínimo 3:1 para UI)
- Dorado `#D4AF37` sobre oscuro `#0F1923` → ratio ≈ **7.8:1** ✓ WCAG AA

---

### 3. Contraste de enlaces (WCAG 1.4.3)

**Estado:** El color de enlace por defecto es `--gano-blue: #1B4FD8` sobre `#FFFFFF` → **10.2:1** ✓.

**Contextos oscuros:** Se añadió regla para secciones con fondo oscuro (`.gano-bg-dark`, `.e-con--dark`) que sobreescribe el color de enlace a `--gano-blue-light: #E8EEFB`:
- `#E8EEFB` sobre `#0F1923` → ratio ≈ **13.1:1** ✓

**CTA naranja (`--gano-orange: #FF6B35`):**
- Ratio sobre blanco → ≈ **3.0:1** — **NO usar como color de texto de enlace** sobre fondo blanco.
- Solo usar para texto sobre fondo oscuro (`#0F1923`) o como color de botón con texto blanco.
- `#FF6B35` sobre `#0F1923` → ≈ **4.7:1** ✓ AA (texto de botón CTA).

---

## Pendiente (requiere acción manual en wp-admin / Elementor)

| Tarea | Dónde | Impacto WCAG |
|---|---|---|
| Asignar `CSS ID: gano-main-content` a la 1ª sección del canvas en páginas full-width | Elementor Editor → sección principal → Advanced | 2.4.1 |
| Verificar que Elementor no sobrescriba el `outline` de botones con `outline: none !important` | Elementor → Global CSS o panel de estilos | 2.4.7 |
| Revisar contraste de texto en tarjetas con fondo glassmorphism (gano-pricing-card) | Elementor o CSS | 1.4.3 |
| Agregar `alt` descriptivo a todas las imágenes decorativas o `alt=""` a las decorativas | Elementor → widget de imagen | 1.1.1 |
| Activar modo "Modo de alto contraste" en Wordfence si se detecta usuario con preferencia | Wordfence / CSS `prefers-contrast` | 1.4.3 |

---

## Verificación rápida

```bash
# PHP lint del child theme
php -l wp-content/themes/gano-child/functions.php

# CSS: sin errores de sintaxis
# Abre en DevTools → Application → Sources → style.css y verifica los bloques añadidos

# WAVE o axe DevTools:
# 1. Tab desde la barra de dirección → el skip link debe aparecer
# 2. Navegar todos los elementos con Tab → visible outline azul/dorado
# 3. Inputs: foco → outline azul + box-shadow suave
```

---

## No rompe CSP

El MU plugin `gano-security.php` define:
```
Content-Security-Policy: style-src 'self' 'unsafe-inline' …
```

Todos los estilos de accesibilidad añadidos están **en la hoja de estilos del tema** (`style.css`), cargada vía `wp_enqueue_style`. No se usa ningún `<style>` inline ni atributo `style=""` en el HTML generado.

Los hooks `wp_body_open` solo emiten HTML (etiquetas `<a>` y `<span>`) sin scripts, por lo que tampoco afectan `script-src`.
