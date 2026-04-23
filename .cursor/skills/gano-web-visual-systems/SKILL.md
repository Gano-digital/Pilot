---
name: gano-web-visual-systems
description: >-
  Implementa sistemas visuales en gano.digital (Canvas 2D, SVG, patrones
  generativos: fractales, metabolas/lava, simetría tipo manchas, caleidoscopios,
  vector/falso 3D, partículas) cumpliendo convenciones WordPress del child
  theme, seguridad, rendimiento y accesibilidad. También cubre prototipos
  HTML locales sin CDN. Usar cuando el usuario pida efectos visuales,
  generativo, “showcase”, integración en páginas Elementor, o assets JS/CSS
  nuevos en gano-child/plugins gano_*.
---

# Sistemas visuales web — Gano Digital

## Alcance

- **Técnicas**: escape-time (Mandelbrot/Julia/variantes), IFS/curvas, campos tipo metabola, simetría bilateral, grupos diedrales (caleidoscopio), orbit/attractors, falso 3D isométrico, degradados animados, SVG SMIL/CSS controlado, composición con GSAP **solo** si ya encaja el stack.
- **Destinos**: (A) prototipo **HTML estático local**; (B) **producción** en WordPress (`gano-child`, plugins `gano_*`, bloques HTML de Elementor donde aplique).

## Reglas del proyecto (obligatorias al tocar código)

| Área | Regla |
|------|--------|
| **Contexto** | Stack WP + Elementor + child `gano-child`; comercio vía Reseller GoDaddy; no reintroducir pasarelas legacy como eje. |
| **PHP** | Prefijo `gano_`; `declare(strict_types=1)` en archivos nuevos; `wp_enqueue_script` / `wp_enqueue_style` — **no** depender de CDNs externos para código propio; PHPDoc en funciones públicas. |
| **Seguridad** | Sanitizar entradas, escapar salidas (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post` según contexto); nonces en formularios; sin secretos en repo; si el script inline es necesario, **revisar CSP** en `mu-plugins/gano-security.php`. |
| **CSS** | Variables `:root` del tema (`--gano-blue`, `--gano-green`, `--gano-orange`, `--gano-gold`, `--gano-dark`); **sin Tailwind** en código nuevo; tipografías del tema (Plus Jakarta Sans / Inter). |
| **JS** | Vanilla preferido; GSAP/ScrollTrigger **ya** pueden estar globales — registrar plugin si se usa; **`prefers-reduced-motion: reduce`** → desactivar o simplificar animaciones de bucle (canvas `requestAnimationFrame`, CSS infinito, SMIL). |
| **Rendimiento** | Cap a `devicePixelRatio` en canvas pesados; considerar `OffscreenCanvas`/worker solo si el entorno lo permite; evitar `putImageData` full-viewport 60fps en móvil sin downscale; `defer` en scripts no críticos. |
| **Idioma** | Strings y comentarios en **es-CO** en código del producto. |
| **Legal / ética** | Figuras “tipo Rorschach”: **generativas y originales**; no reproducir láminas clínicas ni marcas ajenas. |

## Flujo de trabajo (siempre)

1. **Investigación breve (SOTA)** — algoritmo, complejidad, simetría matemática, referencias conceptuales (sin copiar assets restringidos).
2. **Plan** — dónde vive el código (tema vs plugin), dependencias, fallback sin animación, impacto en CWV/CSP.
3. **Implementación** — acotar superficie (un módulo, un handle de enqueue, un contenedor con `data-*`).
4. **QA** — consola limpia, resize/HiDPI, reduced motion, viewport móvil, Lighthouse razonable en la página afectada.

## Modo A — Prototipo HTML local

- Un archivo autocontenido, **sin CDN**, útil para validar matemática y UX antes de subir a WP.
- Patrones: registro de demos `add({ id, host, init })`, `resize(canvas)` con fallback si `clientWidth===0`, IDs SVG únicos por instancia (`dataset.demoId`), `let demos` + `sort` por `id` si hace falta.
- Detalle de implementación y fallos típicos: [reference.md](reference.md) (matemática + checklist técnico).

## Modo B — Integración en gano.digital

1. **Ubicación**: preferir `wp-content/themes/gano-child/assets/js|css/` (o subcarpeta temática, p. ej. `assets/js/visual/`) y enganchar desde `functions.php` con **`gano_enqueue_*`** coherente con el existente.
2. **Encolado**: `wp_enqueue_script( 'gano-visual-foo', get_stylesheet_directory_uri().'/assets/js/visual/foo.js', [], filemtime( ... ), true );` — dependencias explícitas (`'gsap-js'` solo si se usa GSAP).
3. **Acoplamiento al DOM**: un contenedor por instancia (`<div class="gano-visual-x" data-gano-visual="fractal-hero" aria-label="…">`); el JS busca ese root y no asume orden global de secciones Elementor.
4. **Elementor**: evitar megabytes de JS en “HTML” suelto; mejor archivo versionado en el child. Si el usuario insiste en widget HTML, minimizar inline y **sin** `eval` / string-to-function desde input.
5. **Accesibilidad**: controles opcionales (pausa); no depender solo del color; `aria-hidden="true"` en canvas puramente decorativos si el copy paralelo describe la intención.
6. **MU-plugins**: cambios en CSP/rate limit solo con revisión consciente; no relajar reglas “porque el canvas lo pide” sin alternativa (nonce, archivo externo same-origin).

## Familias técnicas (recordatorio)

| Familia | Piezas |
|---------|--------|
| Fractales 2D | Escape time, Julia/Mandelbrot/Tricorn/Burning Ship, Newton en ℂ, IFS, Morton/Z-order |
| Metabolas | Campo Σ r²/d² + umbral; blobs móviles |
| Simetría bilateral | Mitad buffer + espejo; tinta acumulada |
| Caleidoscopio | Clip cuña 2π/n, rotaciones del grupo |
| Falso 3D / vector | Proyección consistente; cuidado con nombres de variables (`co`/`si` vs `c`) |

## Verificación unificada

- [ ] Modo B: script/estilo **encolados**; sin globals innecesarias (`window.GanoVisualFoo` solo si hace falta a Elementor hooks).
- [ ] `prefers-reduced-motion` respetado.
- [ ] Colores alineados a **variables del tema** donde sea UI mezclada con branding.
- [ ] Sin errores en consola; canvas con **resize robusto**.
- [ ] Modo A: validación sintáctica del IIFE (ver [reference.md](reference.md)).

## Progresión

- Prototipo local (A) → ajuste de coste/render → port a archivo en child (B) → enqueue condicional (solo front, solo plantillas que lo necesiten) → prueba en staging.
