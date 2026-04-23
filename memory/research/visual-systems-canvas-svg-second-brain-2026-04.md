---
title: "Sistemas visuales web — second brain (Canvas, SVG, generativo)"
fecha: 2026-04-24
tags: [gano, research, visual, canvas, svg, generativo, fractales, second-brain]
relacionado:
  - motion-and-3d-policy-gano.md
  - gano-wave3-brand-ux-master-brief.md
skill_cursor: .cursor/skills/gano-web-visual-systems/
---

# Sistemas visuales web — referencias y recursos estructurados

> **Propósito:** memoria de proyecto (second brain) para futuros desarrollos visuales en **gano.digital** y prototipos locales: fractales, metabolas, simetría bilateral, caleidoscopios, vector / falso 3D, partículas ligeras.  
> **No sustituye** la política de peso/LCP: alinear siempre con [`motion-and-3d-policy-gano.md`](motion-and-3d-policy-gano.md).

---

## 1. Mapa rápido (qué leer primero)

| Prioridad | Documento / recurso | Uso |
|-----------|----------------------|-----|
| P0 | Skill **`.cursor/skills/gano-web-visual-systems/`** (`SKILL.md` + `reference.md`) | Reglas WP + modos prototipo vs producción + checklist |
| P0 | **`.cursor/rules/101-css-js.mdc`** + **`100-php-wordpress.mdc`** + **`003-security.mdc`** | Variables CSS, enqueue, CSP, reduced-motion |
| P1 | Este archivo | Bibliografía, tablas técnicas, anti-patrones sesión 2026-04 |
| P1 | [`motion-and-3d-policy-gano.md`](motion-and-3d-policy-gano.md) | Cuándo Canvas pesado vs CSS/GSAP; LCP |
| P2 | [`gano-wave3-brand-ux-master-brief.md`](gano-wave3-brand-ux-master-brief.md) | Tokens visuales y voz si el efecto acompaña marca |

---

## 2. Artefactos generados (prototipos HTML locales)

Rutas típicas en máquina de desarrollo (ajustar si se versionan en repo):

| Archivo | Contenido |
|---------|-----------|
| `gano-vector-faux3d-showcase-50-wmp-inspired-2026-04-23.html` | 50 demos vector / falso 3D; lecciones: `resize` robusto, IDs SVG únicos, `@keyframes` únicos, SMIL vs CSS global |
| `gano-fractal-lava-rorschach-kaleido-showcase-50-2026-04-24.html` | 50 demos fractal + metabola + simetría + caleidoscopio; lecciones: alpha `ImageData`, Newton ℂ, Tricorn, Morton, `mirrorInk(d,hw,h,t)`, cuña `arc` clip |

**Patrón común:** IIFE `"use strict"`, `let demos`, `add({id,host,init})`, `demos.sort` por `id`, `dataset.demoId` antes de `init`, sin CDN.

---

## 3. Integración en WordPress (gano-child)

| Tema | Regla |
|------|--------|
| Código PHP nuevo | Prefijo `gano_`, `declare(strict_types=1)`, PHPDoc en públicas |
| Assets | `wp_enqueue_script` / `wp_enqueue_style` con `filemtime` como versión; **sin CDN** para código propio |
| Ubicación sugerida | `wp-content/themes/gano-child/assets/js/visual/` y `.../css/visual/` |
| Acoplamiento | Contenedor único `data-gano-visual="..."`; no asumir orden de secciones Elementor |
| Motion | `prefers-reduced-motion: reduce` → pausar o degradar `requestAnimationFrame` / CSS infinito / SMIL |
| Marca | Preferir variables `:root` (`--gano-blue`, `--gano-green`, etc.); sin Tailwind nuevo |
| CSP | Revisar `mu-plugins/gano-security.php` antes de scripts inline grandes; preferir archivos same-origin |
| Ética | Figuras “tipo mancha de tinta”: **solo generativas**; no copiar láminas Rorschach clínicas ni marcas |

---

## 4. Técnicas — referencias conceptuales (SOTA / clásicas)

### 4.1 Fractales y dinámica compleja

| Tema | Referencia útil | Notas de implementación |
|------|-----------------|-------------------------|
| Mandelbrot / Julia | Peitgen & Richter, *The Beauty of Fractals* (Springer, 1986) — referencia clásica | Escape time; suavizado de paleta por `i` y `|z|` |
| Newton fractals | Matemática estándar de cuencas de atracción | Iterar `z -= (z³-1)/(3z²)` con división compleja explícita |
| Burning Ship | Literatura de variantes “abs” en iteración | `Re/Im` con `Math.abs` antes de elevar |
| Tricorn | `z_{n+1} = \overline{z_n}^2 + c` | Alternativa estable a multibrot mal implementado en polares |
| IFS / Barnsley | Barnsley, *Fractals Everywhere* | Probabilidades acumuladas; limpiar canvas con alpha baja |
| Curvas de relleno | Morton / Z-order (bit interleaving) | Cuidado: decodificar con variable temporal `t`, no corromper índice `k` en el bucle |

### 4.2 Metabolas y “lava”

| Tema | Referencia útil | Notas |
|------|-----------------|-------|
| Campo metabola | Blinn, “A Generalization of Algebraic Surface Drawing” (SIGGRAPH / CACM, 1982) | Suma de potenciales `r²/(d²+ε)`; umbral y banda anti-alias simple |
| Marching squares | Literatura gráfica estándar | Opcional; muchas demos bastan con umbral directo en píxeles |

### 4.3 Simetría y caleidoscopios

| Tema | Referencia útil | Notas |
|------|-----------------|-------|
| Simetría bilateral | Psicometría histórica (contexto solo ético/legal) | Generar formas; no reproducir IP clínica |
| Caleidoscopio digital | Grupos diedrales: `n` reflexiones en sector `2π/n` | Clip: `moveTo(0,0); arc(..., 0, 2π/n); closePath; clip()` |
| Orbit traps | Pickover et al. (orbit traps en fractales) | Útil en cuña combinada con mini-Julia |

### 4.4 Documentación web (implementación)

| Recurso | URL |
|---------|-----|
| Canvas 2D API | https://developer.mozilla.org/en-US/docs/Web/API/Canvas_API |
| `ImageData` | https://developer.mozilla.org/en-US/docs/Web/API/ImageData |
| SVG + `createElementNS` | https://developer.mozilla.org/en-US/docs/Web/API/Document/createElementNS |
| `prefers-reduced-motion` | https://developer.mozilla.org/en-US/docs/Web/CSS/@media/prefers-reduced-motion |
| WCAG motion | https://www.w3.org/WAI/WCAG22/Understanding/animation-from-interactions.html |

---

## 5. Catálogo de errores frecuentes (sesiones de implementación)

| Síntoma | Causa probable | Corrección |
|---------|----------------|------------|
| Píxeles corruptos / franjas en metabola | Índice alpha equivocado en bucle 2×2 | Usar `d[p+3]` de forma explícita por píxel |
| Newton “ruido” sin cuencas | Fórmula de iteración incorrecta | Implementar división compleja de `(z³-1)/(3z²)` |
| Fractal extraño en “z⁵+c” | Potencia en polares inconsistente | Tricorn o fórmula cerrada verificada |
| Curva Z-order incorrecta | Bits mal extraídos de `k` | Decodificar con `t=k`, alternar bits a `x`/`y` |
| Mancha orgánica mal pintada | Mezclar índices `res` con `w` de canvas | Remuestrear `gx, gy` desde rejilla simulada |
| Caleidoscopio vacío o triángulo raro | Clip incorrecto | Cuña circular con `arc` desde origen |
| Polígono invisible en cuña | Primer punto no definido | `moveTo(0,0)` o vértice inicial antes de `lineTo` |
| Espejo de trazo falla | `stroke()` invalida trazo | Repetir `beginPath` + geometría |
| Colisión de animaciones CSS | `@keyframes` globales duplicados | Nombres únicos por demo o SMIL por elemento |
| SVG `<use>` vacío | `href` a id inexistente | Definir `id` en `<defs>` y apuntar bien |
| `const demos` + reasignación | Error de runtime | `let demos` o no reasignar |
| Nav desordenado | `add` fuera de orden | `demos.sort((a,b)=>a.id.localeCompare(b.id))` |
| Variable `c` vs canvas | Sombra de identificador | Renombrar (`cr, ci` o `co, si` para trig) |

---

## 6. Checklist antes de merge o deploy

- [ ] Consola sin errores en desktop y móvil (viewport estrecho).
- [ ] Canvas: `resize` con fallback si `clientWidth === 0`.
- [ ] `devicePixelRatio` acotado (p. ej. ≤ 2) en efectos `putImageData` pesados.
- [ ] `prefers-reduced-motion` probado (DevTools → Rendering).
- [ ] Sin dependencias CDN nuevas para código propio.
- [ ] Textos UI en **es-CO** si son visibles al usuario.
- [ ] Si afecta LCP: contrastar con `motion-and-3d-policy-gano.md`.

---

## 7. Evolución futura (ideas no obligatorias)

- Extraer un módulo `gano-visual-core.js` (RNG, resize, paletas) compartido por varias piezas.
- Web Worker + `OffscreenCanvas` solo si métricas lo justifican y el hosting lo soporta bien.
- Shortcode o bloque Gutenberg **ligero** que solo encole handle por página.

---

## 8. Changelog memoria

| Fecha | Cambio |
|-------|--------|
| 2026-04-24 | Creación: consolidación post-showcases 50+50 y skill `gano-web-visual-systems`; enlaces a política motion y brief wave3. |
| 2026-04-22 | Copia portable ingestada en second brain Descargas: `C:\Users\diego\Downloads\Gano-SecondBrain-COMPLETO\wiki\ux\research\visual-systems-canvas-svg-second-brain-2026-04.md` (frontmatter wiki + `CHANGELOG` / `INDEX`). Mantener ambas rutas alineadas al editar. |

## 9. Copia en Gano-Wiki portable (second brain)

- **Ruta local:** `C:\Users\diego\Downloads\Gano-SecondBrain-COMPLETO\wiki\ux\research\visual-systems-canvas-svg-second-brain-2026-04.md`
- **Canónico repo:** este archivo (`memory/research/…` en el clon Pilot / copia de trabajo).
- **Regenerar paquete completo:** desde Pilot, `powershell -File scripts\export_second_brain_to_downloads.ps1 -FolderName 'Gano-SecondBrain-COMPLETO'` (ver `README-PRIMERO.md` en Descargas).
