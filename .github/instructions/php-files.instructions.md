---
applyTo: "**/*.php"
---

Todas las funciones PHP deben usar el prefijo `gano_` (ej: `gano_enqueue_styles()`).

Usar `declare(strict_types=1)` en archivos nuevos.

SIEMPRE sanitizar inputs con funciones de WordPress:
- `sanitize_text_field()` para strings
- `absint()` para enteros
- `wp_kses_post()` para HTML

SIEMPRE escapar outputs:
- `esc_html()` para texto
- `esc_url()` para URLs
- `esc_attr()` para atributos HTML

SIEMPRE usar `$wpdb->prepare()` para queries SQL. NUNCA SQL directo.

SIEMPRE usar nonces en formularios: `wp_nonce_field()` + `check_admin_referer()`.

Target PHP version: 8.3
