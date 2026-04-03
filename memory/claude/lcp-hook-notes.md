# Hook LCP (fetchpriority) — `functions.php` vs Elementor

**Ubicación:** `wp-content/themes/gano-child/functions.php` — `add_action( 'wp_head', … )` en frontend, solo `is_front_page()`.

## Selectores (orden de prioridad)

Cadena `HERO_SEL` une, en este orden:

1. `.e-con .elementor-widget-image img`
2. `.e-con-full .elementor-widget-image img`
3. `.elementor-top-section .elementor-widget-image img`
4. `.elementor-section .elementor-widget-image img`
5. `.elementor-widget-image img` (fallback amplio)

La **primera** imagen que coincida recibe `fetchpriority="high"` y `loading="eager"`.

## Comportamiento

- `MutationObserver` en `document.documentElement` hasta encontrar la imagen o **timeout 2500 ms** / `DOMContentLoaded`, lo que ocurra primero.
- Si el hero **no** usa `elementor-widget-image` (p. ej. solo background CSS), este script **no** marcará LCP en una `<img>`.

## Validación en front

1. Home en ventana anónima → DevTools → pestaña Network / Performance: primera imagen hero con `fetchpriority=high`.
2. Si se cambia la estructura de Elementor, actualizar selectores en `functions.php` con cuidado (probar móvil).

## Documentación relacionada

- [`memory/content/elementor-home-classes.md`](../content/elementor-home-classes.md) — clases de layout homepage.
