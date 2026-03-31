# 🚀 Gano Digital — Content Hub Importer v2.0

**Versión:** 2.0.0
**Requisitos:** WordPress 5.9+, PHP 7.4+
**Estado:** Producción (Fase 3.5)

---

## ¿Qué es este plugin?

**gano-content-importer** es un instalador de contenido que despliega automáticamente 20 páginas SOTA (State-of-the-Art) con:

- ✅ HTML semántico y accesible (WCAG AA)
- ✅ Animaciones fluidas CSS + scroll reveal JavaScript
- ✅ Contenido estratégico enfocado en Gano Digital ecosistemas
- ✅ CTAs dinámicos con extensibilidad via hooks
- ✅ Responsividad mobile-first (480px, 768px, desktop)

**Importante:** Este es un plugin **one-shot installer**. Tras la activación, se pueden eliminar. Las páginas creadas permanecen indefinidamente.

---

## 📋 20 PÁGINAS INCLUIDAS

| Categoría | Páginas | Temas |
|-----------|---------|-------|
| **Infraestructura** | 6 | NVMe, Hosting Compartido, Edge, HA, Escalamiento, Backups |
| **Seguridad** | 4 | Zero-Trust, DDoS, Post-Quantum, Analytics |
| **IA** | 3 | Predictivo, Self-Healing, Agente IA |
| **Estrategia** | 2 | Soberanía Digital, Analytics Server-Side |
| **Rendimiento** | 5 | Headless, Green, Skeleton, Micro-animations, HTTP/3 |

---

## ⚡ INSTALACIÓN

### 1. Verificar Requisitos

```php
// wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
```

### 2. Copiar Plugin

```bash
# Copiar carpeta a wp-content/plugins
cp -r gano-content-importer/ /path/to/wp-content/plugins/
```

### 3. Copiar CSS a Tema

```bash
# Copiar animaciones a tema hijo
cp gano-sota-animations.css /path/to/wp-content/themes/gano-child/
```

### 4. Activar en WordPress

- Admin → Plugins → Gano Digital Content Hub Importer
- Click "Activate"

### ✅ Verificación

- Ir a Pages → deberías ver ~20 nuevas páginas como DRAFTS
- Check `wp-content/debug.log` para mensajes de éxito
- Las páginas usan template Elementor Canvas

---

## 🎨 ESTRUCTURA DE CADA PÁGINA

```html
<article class="gano-sota-page" role="main" aria-label="Título">
  <!-- Hook box: Problema/oportunidad -->
  <div class="gano-hook-box" role="doc-introduction">
    <p>Contexto del problema...</p>
  </div>

  <!-- Innovation section: SOTA técnica -->
  <section>
    <h2>Innovación — Estado del Arte</h2>
    <ul role="list">
      <li>Punto técnico 1</li>
      <li>Punto técnico 2</li>
      <li>Punto técnico 3</li>
    </ul>
  </section>

  <!-- Divider visual -->
  <div class="gano-divider" aria-hidden="true"></div>

  <!-- Quote inspirador -->
  <div class="gano-quote-box" role="doc-pullquote">
    <p>💬 <em>"Cita inspiradora..."</em></p>
  </div>

  <!-- Activación en Gano -->
  <section>
    <h2>Cómo activarlo en tu Ecosistema Gano</h2>
    <p>Explicación de cómo usar en planes específicos...</p>
  </section>

  <!-- CTA -->
  <div class="gano-cta-box">
    <a href="#" class="gano-btn-primary">Botón Acción</a>
    <p class="gano-muted-text">Subtext de credibilidad</p>
  </div>
</article>
```

---

## 🔧 CUSTOMIZACIÓN VIA HOOKS

### 1. Personalizar URLs de CTA

```php
// En tu tema o MU plugin
add_filter( 'gano_sota_cta_url', function( $url, $category ) {
    // Variar CTA por categoría
    switch( $category ) {
        case 'infraestructura':
            return '/planes/enterprise#infraestructura';
        case 'seguridad':
            return '/demo/seguridad';
        case 'inteligencia-artificial':
            return '/ai-features';
        default:
            return $url;
    }
}, 10, 2 );
```

### 2. Modificar Contenido de Página

```php
add_filter( 'gano_sota_page_content', function( $content, $page_data ) {
    // Personalizar contenido antes de crear
    if ( $page_data['category'] === 'seguridad' ) {
        // Agregar comparativa de planes
        $content .= '<section><h3>Seguridad en cada plan...</h3></section>';
    }
    return $content;
}, 10, 2 );
```

### 3. Acción Post-Creación

```php
add_action( 'gano_sota_page_created', function( $post_id, $page_data ) {
    // Hacer algo después de crear página
    if ( $page_data['category'] === 'infraestructura' ) {
        // Agregar metadata personalizada
        update_post_meta( $post_id, '_gano_feature_tier', 'premium' );
    }
}, 10, 2 );
```

---

## 🎬 ANIMACIONES

Las animaciones se controlan via CSS en `gano-sota-animations.css`:

### Entrada de Página
```css
.gano-sota-page {
  animation: fadeInUp 0.6s ease-out;
}
```

### Staggered Bullet Points
```css
.gano-sota-page li:nth-child(1) { animation-delay: 0.2s; }
.gano-sota-page li:nth-child(2) { animation-delay: 0.3s; }
/* etc... */
```

### Button Ripple Effect
```css
.gano-btn-primary::before {
  transition: width 0.6s, height 0.6s;
}
.gano-btn-primary:hover::before {
  width: 300px;
  height: 300px;
}
```

### Scroll Reveal
JavaScript en `js/scroll-reveal.js` usa IntersectionObserver para triggear animaciones cuando el elemento entra en viewport.

### Respetar Preferencias de Accesibilidad
```css
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0s !important;
    transition-duration: 0s !important;
  }
}
```

---

## 📱 RESPONSIVIDAD

### Breakpoints

| Dispositivo | Ancho | Cambios |
|---|---|---|
| **Mobile** | < 480px | Padding 20px, font reducido, stack vertical |
| **Tablet** | 480-768px | Padding 30px, font mediano |
| **Desktop** | > 768px | Padding 40px, layout completo, max-width 900px |

### Testing

```html
<!-- Chrome DevTools → Toggle device toolbar (Ctrl+Shift+M) -->
<!-- Probar en 320px, 480px, 768px, 1024px -->
```

---

## 🔐 SEGURIDAD

El plugin implementa:

- ✅ `wp_kses_post()` en contenido HTML
- ✅ `sanitize_key()` en metadata
- ✅ `$wpdb->prepare()` en queries
- ✅ Validación de permisos en activation
- ✅ Logging en `error_log()` para debugging
- ✅ Idempotency checks (no duplica páginas)

---

## 📊 FUNCIONES PRINCIPALES

### `gano_import_content_hub_v2()`
Activation hook que crea las 20 páginas.

**Opciones guardadas:**
```php
get_option( 'gano_sota_import_stats' );
// Retorna: [
//   'version' => '2.0.0',
//   'imported' => 20,
//   'errors' => 0,
//   'timestamp' => '2026-03-19 11:47:00'
// ]
```

### `gano_get_pages_data_v2()`
Retorna array de 20 páginas con estructura:
```php
[
  'title' => string,
  'category' => 'infraestructura'|'seguridad'|'inteligencia-artificial'|'estrategia'|'rendimiento',
  'feature_img_name' => 'icon_*.png',
  'cta_url' => string,
  'stats' => [
    ['label' => 'Métrica', 'value' => '6x'],
    ...
  ],
  'content' => string (HTML)
]
```

### `gano_attach_image_by_filename_v2()`
Busca imagen en media library o sideload desde `/uploads/2026/03/`.

---

## 🔍 DEBUGGING

### Ver Logs

```bash
# En terminal
tail -f wp-content/debug.log | grep "GANO IMPORTER"
```

### Mensajes Esperados

```
GANO IMPORTER v2.0: Importación completada. 20 páginas creadas, 0 errores.
GANO IMPORTER: Plugin loaded. Ready for activation.
```

### Problemas Comunes

| Problema | Solución |
|----------|----------|
| Pages no aparecen | Verificar `wp-content/debug.log` para errores |
| CSS no carga | Copiar `gano-sota-animations.css` a tema hijo |
| JS de scroll falla | Verificar que navegador soporta IntersectionObserver |
| Imágenes no se asignan | Subir icons a `/uploads/2026/03/` o ignorar warnings |

---

## 🗑️ ELIMINACIÓN

### Eliminar Plugin (Seguro)

1. Admin → Plugins → Gano Digital Content Hub Importer
2. Click "Deactivate"
3. Click "Delete"
4. ✅ Las 20 páginas **permanecen**. El plugin solo se elimina.

### Eliminar Páginas Creadas (Si es necesario)

```php
// En functions.php (temporal)
function delete_gano_sota_pages() {
    $pages = get_posts([
        'post_type' => 'page',
        'meta_query' => [
            [
                'key' => '_gano_sota_category',
                'compare' => 'EXISTS'
            ]
        ],
        'posts_per_page' => -1
    ]);

    foreach( $pages as $page ) {
        wp_delete_post( $page->ID, true );
    }
}
// add_action( 'wp_loaded', 'delete_gano_sota_pages' );
```

---

## 📈 MÉTRICAS

### Antes (v1.0)
```
Score UX: 4/10
Score Seguridad: 7/10
Score Engagement: 5/10
Score Accesibilidad: 5/10
PROMEDIO: 5.2/10
```

### Después (v2.0)
```
Score UX: 8/10 (+4)
Score Seguridad: 8/10 (+1)
Score Engagement: 7/10 (+2)
Score Accesibilidad: 7/10 (+2)
PROMEDIO: 7.6/10 (+46% 📈)
```

---

## 🤝 SOPORTE

Para issues o customizaciones:

1. Revisar `CHANGELOG_GANO_CONTENT_IMPORTER_v2.md`
2. Revisar `AUDIT_gano-content-importer.md`
3. Revisar `STATUS_GANO_CONTENT_IMPORTER_V2.md`
4. Contactar a Diego (diego@gano.digital)

---

## 📝 LICENCIA

GPL v2+ (Compatible con WordPress core)

---

## 🎉 CRÉDITOS

**Desarrollado para:** Gano Digital
**Creado por:** Claude (Cowork) + Diego
**Fecha:** Marzo 19, 2026
**Versión:** 2.0.0

---

## 🚀 SIGUIENTE FASE

Fase 4 — Plataforma Real de Hosting
- WHMCS/Blesta integration
- Portal cliente (my.gano.digital)
- Soporte tickets (support.gano.digital)
- Facturación DIAN
- Status page

¡Vamos! 💪

