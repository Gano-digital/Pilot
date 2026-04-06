---
applyTo: "wp-content/mu-plugins/**/*.php"
---

Estos archivos cargan en **cada request** de WordPress. Cambios mínimos y revisables.

- **MU plugins** (`gano-security.php`, `gano-seo.php`, `gano-agent-core.php`): prioridad máxima en seguridad y rendimiento.
- Evitar `include` de plugins pesados de terceros; solo código Gano.
- **CSP y headers** en `gano-security.php`: cualquier cambio puede romper Elementor, Wompi o scripts; probar en staging.
- **Schema / OG** en `gano-seo.php`: coherencia con Rank Math; no duplicar `og:image` sin comprobar filtros.
- Si añades hooks en `wp_head` o `wp_footer`, documentar prioridad y conflicto con Rank Math.
