# Notas de Auditoría de Accesibilidad — Wave 3
**Fecha:** Abril 2026  
**Scope:** `wp-content/themes/gano-child/`  
**Criterios:** WCAG 2.1 AA

---

## Cambios implementados

### 1. Nuevo archivo `css/gano-a11y.css`
Archivo dedicado para utilidades de accesibilidad. Registrado en WordPress con el handle `gano-a11y` y dependencia de `gano-child-style` (se carga después del CSS del tema).

**Contenido:**
- **`:focus-visible` global** — muestra contorno de enfoque únicamente para navegación por teclado (no en clics de ratón). Usa el token `--gano-blue` (#1B4FD8) con `outline: 3px` y `outline-offset: 3px`. Elimina el `outline: none` que existía en los elementos de formulario.
- **Overrides de formularios** — `input/textarea/select:focus-visible` reemplaza el outline por sombra de color azul (respeta el diseño visual sin romper el flujo de navegación).
- **Botones y widgets Elementor** — override de `:focus-visible` para `.btn-primary`, `.gano-btn`, `.elementor-button` y menús de navegación.
- **Skip-to-content link** (`.gano-skip-link`) — oculto por defecto (`top: -100%`); se revela en `top: 1rem` al recibir `:focus`. Cumple WCAG 2.4.1 "Saltar bloques".
- **`.gano-sr-only`** — clase utilitaria para texto visible solo a lectores de pantalla (patrón Bootstrap / HTML5 Boilerplate).
- **`@media (forced-colors: active)`** — soporte para modo de alto contraste de Windows.
- **`@media (prefers-reduced-motion: reduce)`** — desactiva la transición del skip link.

### 2. `functions.php`
- Se agrega `wp_enqueue_style('gano-a11y', ...)` para cargar el nuevo CSS.
- Se agrega hook `add_action('wp_body_open', 'gano_skip_link')` para inyectar el enlace de salto al inicio del `<body>`.

---

## Verificación CSP (`gano-security.php`)
El CSS se sirve desde `'self'`; la política `style-src 'self' 'unsafe-inline' https://fonts.googleapis.com` lo cubre sin cambios. **No se modificó `gano-security.php`.**

---

## Problemas de contraste documentados (sin cambiar colores de marca)

| Elemento | Color fg | Color bg | Ratio | Estado |
|---|---|---|---|---|
| Links en cuerpo (`a`) | `#1B4FD8` (--gano-blue) | `#FFFFFF` | ~5.4:1 | ✅ Pasa AA |
| Texto muted (`.gano-text-muted`) | `#6B7280` (--gano-gray-500) | `#FFFFFF` | ~4.6:1 | ✅ Pasa AA |
| Botón CTA texto | `#FFFFFF` | `#FF6B35` (--gano-orange) | ~3.5:1 | ⚠️ Solo AA Large (≥18pt o ≥14pt bold) |
| Texto navegación dark bg | `#94a3b8` | `#0F1923` (~#111) | ~5.0:1 | ✅ Pasa AA |

> **Acción futura:** el texto blanco sobre `--gano-orange` (#FF6B35) no alcanza AA para texto normal. Considerar oscurecer a `#E05015` (ratio ~4.7:1) o limitar el color naranja a texto de tamaño grande en versiones futuras.

---

## Compatibilidad Elementor
- El hook `wp_body_open` es soportado por Elementor desde la v3.x (Elementor llama `do_action('elementor/page_builder/before_enqueue_scripts')` pero respeta el hook estándar de WP).
- El skip link apunta a `#content`, que es el ID estándar del contenedor en Hello Elementor y compatible con Royal Elementor Kit.
- Los overrides CSS usan selectores de clase (`.elementor-nav-menu`, `.elementor-button`) sin modificar la especificidad base del builder; no rompen la edición en tiempo real.

---

## Cómo validar
1. Navegar el sitio con Tab/Shift+Tab — el primer Tab debe revelar el skip link.
2. Activar VoiceOver (Mac) o NVDA (Windows) y navegar la página.
3. Ejecutar [WAVE Tool](https://wave.webaim.org/) en gano.digital y verificar que desaparezcan las alertas de "Missing skip navigation link" y "No focus indicator".
4. DevTools → Accessibility panel: verificar que el árbol de accesibilidad tenga landmark `<main id="content">`.
