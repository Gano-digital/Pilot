# Gano Digital — Política de Motion y 3D

**Versión:** 1.0 · Abril 2026  
**Alcance:** Sitio gano.digital (WordPress + Elementor + gano-child)  
**Referencia:** Brief maestro §3 sistema visual; `gano-sota-fx.js`, `scroll-reveal.js`

---

## 1. Principio rector

> **MVP = Estático + micro-motion CSS/GSAP ya instalado.**  
> Cada capa adicional (Lottie, video loop, Spline/WebGL) debe justificarse con una mejora medible de conversión o comprensión, nunca por decoración.

El stack actual ya incluye GSAP 3 + ScrollTrigger + IntersectionObserver en `gano-child`. No se requieren dependencias npm nuevas para la mayoría de casos de uso.

---

## 2. Tabla de decisión — cuándo usar cada medio

| Medio | Usar cuando | No usar cuando | Peso orientativo | Impacto LCP |
|-------|-------------|----------------|-------------------|-------------|
| **Imagen estática (WebP)** | Hero principal, tarjetas de plan, OG images | Nunca evitar — es el baseline obligatorio | 30–120 KB | Neutro (es el LCP ideal) |
| **CSS transition/animation** | Hover de botones, focus-ring, estados UI ≤ 300 ms | Animaciones largas o complejas | 0 KB extra | Ninguno |
| **GSAP (ya instalado)** | Scroll reveals, stagger de secciones, parallax ligero, text reveal | Efectos que no respetan `prefers-reduced-motion` | ~35 KB (ya cargado) | Ninguno si no bloquea render |
| **Lottie JSON** | Iconografía animada (checkmarks, loaders, indicadores de estado) | Hero fullscreen, ilustraciones complejas > 150 KB | 20–80 KB JSON | Bajo si se carga diferido |
| **Video loop (MP4/WebM)** | Hero secundario o sección "cómo funciona", sin audio | Hero above-the-fold en mobile (afecta LCP); nunca autoplay con sonido | 500 KB–2 MB | **Alto**: requiere poster obligatorio |
| **Spline / WebGL embebido** | Landing de producto flagship (Bastión SOTA) con presupuesto y métricas | MVP, páginas de conversión directa, conexiones lentas | 1–5 MB JS + assets | **Muy alto**: diferir y usar fallback |
| **GIF** | Nunca en producción | Siempre — reemplazar por WebP animado o Lottie | Variable (suele ser > video) | Alto |

---

## 3. Reglas de peso y LCP por zona de página

### 3.1 Above the fold (hero)

- **LCP objetivo:** < 2.5 s en mobile 4G (Core Web Vitals Good).
- **Regla:** el LCP element **debe ser una imagen WebP estática** con `width`/`height` declarados y `fetchpriority="high"`.
- Si hay video loop: añadir `poster="hero-static.webp"` visible desde el primer frame; el `<video>` **no** puede ser el LCP element.
- Si hay Spline: cargar solo después de `load` event (`defer`); mostrar imagen WebP mientras carga.

### 3.2 Below the fold (secciones scroll)

- GSAP ScrollTrigger + `IntersectionObserver` ya implementados: apropiados para reveals y parallax.
- Lottie aceptable si JSON < 80 KB y se carga con `lottie.loadAnimation({ autoplay: false })` activando solo al entrar en viewport.
- Video loop aceptable si está detrás de `IntersectionObserver` y se pausa fuera del viewport.

### 3.3 Modal / overlay

- Lottie o CSS animation para feedback (éxito, error, carga): ✅
- Video o WebGL en modal: ❌ — coste de CPU innecesario.

---

## 4. Accesibilidad y `prefers-reduced-motion`

### 4.1 Implementación obligatoria en GSAP

El archivo `gano-sota-fx.js` ya incluye el guard correcto — **mantenerlo en todos los JS nuevos**:

```js
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (prefersReducedMotion) return;
```

### 4.2 CSS — patrón aprobado

```css
/* Aplicar a todos los keyframes y transitions no esenciales */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
```

### 4.3 Video loop

```html
<!-- Patrón correcto: muted + playsinline + poster + aria-hidden -->
<video
  autoplay
  loop
  muted
  playsinline
  poster="assets/hero-poster.webp"
  aria-hidden="true"
  width="1920"
  height="1080"
>
  <source src="assets/hero-loop.webm" type="video/webm">
  <source src="assets/hero-loop.mp4" type="video/mp4">
</video>
```

- `aria-hidden="true"` en videos decorativos.
- Proveer mecanismo de pausa si el video dura > 5 s (WCAG 2.2 criterio 2.2.2).

### 4.4 Lottie

- Si el JSON codifica una animación informativa (no decorativa): añadir `aria-label` al contenedor y `role="img"`.
- Si es decorativa: `aria-hidden="true"` en el contenedor.
- Respetar `prefers-reduced-motion` pausando la animación:

```js
const anim = lottie.loadAnimation({ /* ... */ });
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  anim.goToAndStop(0, true); // mostrar primer frame estático
}
```

### 4.5 Spline / WebGL

- Proveer siempre una imagen de fallback visible mientras carga y cuando falla.
- Añadir `aria-label` describiendo qué representa el modelo 3D.
- Desactivar completamente si `prefers-reduced-motion: reduce` (reemplazar con imagen estática).

---

## 5. Fallback estático — regla de oro

> **Toda animación o 3D debe degradarse graciosamente a una imagen estática sin romper layout.**

| Medio | Fallback recomendado |
|-------|---------------------|
| GSAP ScrollTrigger | Elementos visibles sin transform (opacity: 1, y: 0 por defecto antes de JS) |
| Lottie | Imagen WebP del frame final de la animación |
| Video loop | `<img>` con el poster como fallback de `<noscript>` |
| Spline / WebGL | Imagen WebP renderizada del modelo en el ángulo hero |
| CSS animation | Estado final de la animación aplicado como estilos base |

**Implementación CSS para GSAP:** declarar estados visibles en CSS y dejar que GSAP sobreescriba; si JS falla, el contenido es visible:

```css
/* Estado base visible — GSAP animará desde este punto */
.gano-reveal {
  opacity: 1;
  transform: translateY(0);
}
```

---

## 6. Recomendación MVP (lanzamiento)

### ✅ Priorizar (ya disponible, cero dependencias nuevas)

1. **Imágenes WebP** como LCP element en todas las secciones hero.
2. **CSS transitions** para hover, focus y estados de formulario (< 300 ms).
3. **GSAP + ScrollTrigger** (ya cargado) para:
   - Scroll reveals con `opacity` + `y` en secciones debajo del fold.
   - Stagger en tarjetas de planes/ecosistemas.
   - Parallax sutil (< 20 px) en backgrounds decorativos.
4. **IntersectionObserver** (`scroll-reveal.js` existente) para elementos fuera del viewport crítico.

### ⚠️ Considerar en Fase 2 (con métricas previas)

5. **Lottie JSON** (< 80 KB) para:
   - Checkmarks de características en tarjetas de plan.
   - Indicadores de estado en dashboard/portal cliente (my.gano.digital).
   - Loader de pago Wompi.
   - **Dependencia:** `lottie-web` (~60 KB gzip) — cargar solo en páginas que lo usen vía `wp_enqueue_script` condicional.

6. **Video loop** (< 1 MB WebM) para hero de landing de Bastión SOTA:
   - Solo desktop (media query `min-width: 1024px`).
   - Poster estático en mobile.
   - Diferido hasta `window.load`.

### ❌ Posponer hasta Fase 3+ (requiere presupuesto, métricas y diseñador 3D)

7. **Spline / WebGL:**
   - Solo para landing flagship de Bastión SOTA o demo de infraestructura.
   - Requiere: benchmark de LCP antes/después, diseñador 3D, aprobación de Diego.
   - Coste de mantenimiento: actualizar el archivo `.splinecode` con cada cambio de marca.

---

## 7. Checklist de aprobación antes de producción (para cualquier asset de motion/3D)

- [ ] Peso del asset documentado (KB/MB).
- [ ] LCP medido con y sin el asset (Lighthouse / PageSpeed Insights).
- [ ] `prefers-reduced-motion` implementado y probado.
- [ ] Fallback estático verificado (JS desactivado o asset bloqueado en DevTools).
- [ ] `aria-hidden` o `aria-label` apropiado según si es decorativo o informativo.
- [ ] Video: `muted`, `playsinline`, `poster`, sin audio, mecanismo de pausa si > 5 s.
- [ ] No bloquea render del LCP element (cargar diferido si está below the fold).
- [ ] Probado en mobile (conexión simulada 4G lenta en DevTools).

---

## 8. Convenciones de archivos

| Tipo | Ruta sugerida | Nomenclatura |
|------|--------------|--------------|
| Lottie JSON | `wp-content/themes/gano-child/assets/lottie/` | `gano-{nombre}.json` |
| Video loop | `wp-content/themes/gano-child/assets/video/` | `gano-{sección}-loop.{webm,mp4}` |
| Poster/fallback | `wp-content/themes/gano-child/assets/img/` | `gano-{sección}-poster.webp` |
| Spline embed | URL externa de Spline.design o iframe aislado | Documentar versión y fecha de exportación |

---

## 9. Referencias internas

- `wp-content/themes/gano-child/js/gano-sota-fx.js` — implementación GSAP de referencia (guard `prefers-reduced-motion` ✅)
- `wp-content/themes/gano-child/js/scroll-reveal.js` — IntersectionObserver de referencia
- `memory/research/gano-wave3-brand-ux-master-brief.md` §3 — sistema visual
- `wp-content/mu-plugins/gano-security.php` — revisar CSP si se agregan dominios externos (p. ej. `cdn.spline.design`)
