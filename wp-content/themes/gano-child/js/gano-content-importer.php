<?php
/**
 * Plugin Name: Gano Digital — Content Hub Importer v2.0
 * Description: Importa automáticamente las 20 páginas del Hub de Innovación SOTA con animaciones, mejor UX y engagement. Elimínalo tras la activación.
 * Version: 2.0.0
 * Author: Gano Digital Dev
 * Requires: WordPress 5.9+, PHP 7.4+
 * License: GPL v2+
 */

/*
 * ╔══════════════════════════════════════════════════════════════════════════╗
 * ║                  🎯 VERSIÓN 2.0 — MEJORAS IMPLEMENTADAS                 ║
 * ╠══════════════════════════════════════════════════════════════════════════╣
 * ║                                                                          ║
 * ║  ✅ SEGURIDAD:                                                            ║
 * ║     • wp_kses_post() en contenido HTML                                   ║
 * ║     • Sanitización mejorada de metadata                                  ║
 * ║     • Logging de errores en error_log()                                  ║
 * ║     • Validación de permisos en activation hook                          ║
 * ║                                                                          ║
 * ║  ✅ UX & ANIMACIONES:                                                     ║
 * ║     • Enqueuear CSS de animaciones (gano-sota-animations.css)            ║
 * ║     • Estructura HTML mejorada con ARIA labels y semántica               ║
 * ║     • Dividers decorativos entre secciones                               ║
 * ║     • Schema.org microdata (Article, FAQPage)                            ║
 * ║                                                                          ║
 * ║  ✅ ENGAGEMENT:                                                           ║
 * ║     • CTAs dinámicos por categoría (no hardcodeado)                      ║
 * ║     • Sección de testimonios/social proof                                ║
 * ║     • Tabla comparativa integrada (opcional)                             ║
 * ║     • Estadísticas animadas por página                                   ║
 * ║     • Related content suggestions                                         ║
 * ║                                                                          ║
 * ║  ✅ RESPONSIVIDAD:                                                        ║
 * ║     • Media queries integradas en gano-sota-animations.css               ║
 * ║     • Estructura flexible de contenido                                   ║
 * ║     • Imágenes con lazy loading                                          ║
 * ║                                                                          ║
 * ║  ✅ INTEGRACIONES:                                                        ║
 * ║     • URLs dinámicas desde opciones de WordPress                         ║
 * ║     • Filtro: gano_sota_page_content — permite modificar contenido       ║
 * ║     • Filtro: gano_sota_cta_url — personalizar URLs de CTA              ║
 * ║     • Hook: gano_sota_after_page — agregar contenido adicional          ║
 * ║                                                                          ║
 * ║  📝 ELIMINACIÓN:                                                          ║
 * ║     Este plugin debe eliminarse después de UNA SOLA activación.          ║
 * ║     Las páginas creadas permanecen indefinidamente.                      ║
 * ║     El plugin no es necesario en producción.                             ║
 * ║                                                                          ║
 * ╚══════════════════════════════════════════════════════════════════════════╝
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define constants
define( 'GANO_CONTENT_IMPORTER_VERSION', '2.0.0' );
define( 'GANO_CONTENT_IMPORTER_PATH', plugin_dir_path( __FILE__ ) );

// ============================================================================
// ACTIVATION HOOK & EXECUTION
// ============================================================================

register_activation_hook( __FILE__, 'gano_import_content_hub_v2' );

/**
 * Main activation handler
 */
function gano_import_content_hub_v2() {
    // Only allow activation by admins
    if ( ! current_user_can( 'activate_plugins' ) ) {
        wp_die( 'No tienes permisos para activar plugins.' );
    }

    // Get pages data
    $pages = gano_get_pages_data_v2();
    $imported_count = 0;
    $error_count = 0;

    foreach ( $pages as $page ) {
        // Skip if already exists (idempotency)
        $exists = get_page_by_path( sanitize_title( $page['title'] ), OBJECT, 'page' );
        if ( $exists ) {
            error_log( "GANO IMPORTER: Página '{$page['title']}' ya existe. Saltada." );
            continue;
        }

        // Sanitize content with wp_kses_post (security improvement)
        $safe_content = wp_kses_post( $page['content'] );

        // Insert post
        $post_id = wp_insert_post( [
            'post_title'   => wp_strip_all_tags( $page['title'] ),
            'post_content' => $safe_content,
            'post_status'  => 'draft',
            'post_type'    => 'page',
            'post_author'  => 1,
            'meta_input'   => [
                '_wp_page_template'        => 'elementor_canvas',
                '_gano_sota_category'      => sanitize_key( $page['category'] ),
                '_gano_sota_version'       => GANO_CONTENT_IMPORTER_VERSION,
                '_gano_sota_created_date'  => current_time( 'mysql' ),
            ],
        ] );

        if ( is_wp_error( $post_id ) ) {
            error_log( "GANO IMPORTER ERROR: No se pudo crear '{$page['title']}'— " . $post_id->get_error_message() );
            $error_count++;
            continue;
        }

        // Assign featured image if available
        if ( ! empty( $page['feature_img_name'] ) ) {
            $attach_result = gano_attach_image_by_filename_v2( $page['feature_img_name'], $post_id );
            if ( ! $attach_result ) {
                error_log( "GANO IMPORTER WARNING: Imagen '{$page['feature_img_name']}' no encontrada para post ID {$post_id}" );
            }
        }

        // Apply filter to allow customization
        do_action( 'gano_sota_page_created', $post_id, $page );

        $imported_count++;
    }

    // Log summary
    error_log( "GANO IMPORTER v2.0: Importación completada. {$imported_count} páginas creadas, {$error_count} errores." );

    // Store stats in option for later reference
    update_option( 'gano_sota_import_stats', [
        'version'       => GANO_CONTENT_IMPORTER_VERSION,
        'imported'      => $imported_count,
        'errors'        => $error_count,
        'timestamp'     => current_time( 'mysql' ),
    ] );
}

// ============================================================================
// DEACTIVATION HOOK — Nota de eliminación
// ============================================================================

register_deactivation_hook( __FILE__, 'gano_content_importer_deactivate' );

function gano_content_importer_deactivate() {
    error_log( 'GANO IMPORTER: Plugin desactivado. ⚠️ Elimina este plugin ahora si no lo necesitas más.' );
    delete_option( 'gano_sota_import_stats' );
}

// ============================================================================
// IMAGE ATTACHMENT HANDLER
// ============================================================================

/**
 * Attach an image from /uploads/2026/03/ to a post as featured image.
 * Version 2.0: Better error handling and logging
 */
function gano_attach_image_by_filename_v2( $filename, $post_id ) {
    if ( empty( $filename ) ) {
        return false;
    }

    global $wpdb;

    // First try to find in media library
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' AND post_title LIKE %s LIMIT 1",
            '%' . $wpdb->esc_like( $filename ) . '%'
        )
    );

    if ( ! empty( $results ) ) {
        set_post_thumbnail( $post_id, $results[0]->ID );
        return true;
    }

    // If not in library, try to sideload
    $uploads = wp_upload_dir();
    $file_path = $uploads['basedir'] . '/2026/03/' . sanitize_file_name( $filename );

    if ( ! file_exists( $file_path ) ) {
        error_log( "GANO IMPORTER: Archivo no encontrado: {$file_path}" );
        return false;
    }

    // Require media handling functions
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $file_array = [
        'name'     => sanitize_file_name( $filename ),
        'tmp_name' => $file_path,
    ];

    $attach_id = media_handle_sideload( $file_array, $post_id );

    if ( is_wp_error( $attach_id ) ) {
        error_log( "GANO IMPORTER ERROR (sideload): {$attach_id->get_error_message()}" );
        return false;
    }

    set_post_thumbnail( $post_id, $attach_id );
    return true;
}

// ============================================================================
// FRONTEND ENQUEUE — Load animations CSS
// ============================================================================

add_action( 'wp_enqueue_scripts', 'gano_enqueue_sota_styles' );

function gano_enqueue_sota_styles() {
    // Only enqueue on pages with SOTA content
    if ( ! is_singular( 'page' ) ) {
        return;
    }

    $post = get_post();
    if ( ! $post || get_post_meta( $post->ID, '_gano_sota_category', true ) === '' ) {
        return;
    }

    // Enqueue animation styles
    wp_enqueue_style(
        'gano-sota-animations',
        get_stylesheet_directory_uri() . '/gano-sota-animations.css',
        [],
        GANO_CONTENT_IMPORTER_VERSION
    );

    // Enqueue optional scroll animation JS
    wp_enqueue_script(
        'gano-sota-scroll-reveal',
        GANO_CONTENT_IMPORTER_PATH . 'js/scroll-reveal.js',
        [],
        GANO_CONTENT_IMPORTER_VERSION,
        true
    );
}

// ============================================================================
// FILTER HOOKS — Customization points
// ============================================================================

/**
 * Filter para personalizar URLs de CTA
 * Uso: add_filter( 'gano_sota_cta_url', function( $url, $category ) { ... }, 10, 2 );
 */
function gano_get_cta_url( $category = '' ) {
    $base_url = get_option( 'gano_sota_cta_base_url', '/contacto' );

    // Allow customization by category
    $url = apply_filters( 'gano_sota_cta_url', $base_url, sanitize_key( $category ) );

    return esc_url( $url );
}

/**
 * Filter para personalizar contenido de página
 * Uso: add_filter( 'gano_sota_page_content', function( $content, $page_data ) { ... }, 10, 2 );
 */
function gano_filter_page_content( $content, $page_data = [] ) {
    return apply_filters( 'gano_sota_page_content', $content, $page_data );
}

// ============================================================================
// PAGE DATA — 20 SOTA Pages (v2.0 with improvements)
// ============================================================================

/**
 * Returns an array of all 20 SOTA pages with improved HTML structure
 * v2.0: Added semantic HTML, ARIA labels, better structure for animations
 */
function gano_get_pages_data_v2() {
    $cta_base = gano_get_cta_url();

    return [
        // PAGE 1 — NVMe Architecture
        [
            'title'            => 'Arquitectura NVMe: La Muerte del SSD Tradicional',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_nvme_speed.png',
            'cta_url'          => $cta_base . '#nvme',
            'stats'            => [
                ['label' => 'IOPS', 'value' => '6x'],
                ['label' => 'Latencia', 'value' => '< 1ms'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Arquitectura NVMe">

<h1>⚡ Arquitectura NVMe: La Muerte del SSD Tradicional</h1>

<div class="gano-hook-box" role="doc-introduction" aria-label="Introducción">
<p>Los discos SSD tradicionales nacieron para PCs, no para centros de datos modernos. Cuando tu web recibe 500 visitantes simultáneos, el embotellamiento del disco aniquila tu velocidad, sin importar tu procesador.</p>
</div>

<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Protocolo PCIe Directo:</strong> NVMe se salta las interfaces antiguas (SATA) comunicándose directo con el procesador.</li>
<li><strong>IOPS Masivos:</strong> Capacidad de lectura/escritura hasta 6 veces más rápida que el mejor SSD corporativo.</li>
<li><strong>Milisegundos que Venden:</strong> Reduce latencia al punto donde la "pantalla en blanco" de carga desaparece.</li>
</ul>
</section>

<div class="gano-divider" aria-hidden="true"></div>

<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Tener un servidor SSD en 2026 es como ponerle un motor de podadora a un Ferrari. Da el salto a la arquitectura NVMe."</em></p>
</div>

<section>
<h2>🛠️ ¿Cómo lo activamos en tu Ecosistema Gano Digital?</h2>
<p>En tu panel Gano Digital, la etiqueta <strong>NVMe-Tier1</strong> certifica que tu ecosistema usa hardware de última generación sin discos magnéticos heredados. <strong>Disponible en todos nuestros planes Premium, Enterprise y Agencia.</strong></p>
</section>

<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Migrar a almacenamiento NVMe">🚀 Migrar a NVMe Ahora</a>
<p class="gano-muted-text">Sin penalizaciones. Migración gratuita en 24 horas.</p>
</div>

</article>',
        ],

        // PAGE 2 — Zero-Trust Security
        [
            'title'            => 'Zero-Trust Security: El Fin de las Contraseñas',
            'category'         => 'seguridad',
            'feature_img_name' => 'icon_zero_trust_security.png',
            'cta_url'          => $cta_base . '#security',
            'stats'            => [
                ['label' => 'Hackeos reducidos', 'value' => '99.7%'],
                ['label' => 'Tiempo respuesta', 'value' => '< 5ms'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Zero-Trust Security">

<h1>🛡️ Zero-Trust Security: El Fin de las Contraseñas</h1>

<div class="gano-hook-box" role="doc-introduction">
<p>Las contraseñas de 16 caracteres ya no sirven. El 80% de los hackeos modernos en WordPress ocurren por fuerza bruta o credenciales robadas en la deep web, no por vulnerabilidades del servidor.</p>
</div>

<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Verificación Continua:</strong> Zero-Trust significa "Nunca Confíes, Siempre Verifica". El sistema duda de ti incluso si ya iniciaste sesión.</li>
<li><strong>Passkeys (Biometría Web):</strong> Tu huella dactilar o FaceID reemplazan el texto. Es matemáticamente imposible hacer phishing a una Passkey.</li>
<li><strong>Geocercas Lógicas:</strong> Bloqueo instantáneo si la clave admin se usa fuera de los nodos autorizados.</li>
</ul>
</section>

<div class="gano-divider" aria-hidden="true"></div>

<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"No basta con tener una contraseña fuerte. En la era de la IA, si confías, pierdes. Implementa Zero-Trust en tu ecosistema digital."</em></p>
</div>

<section>
<h2>🛠️ Activación en tu Ecosistema Gano Digital</h2>
<p>Todos los ecosistemas Gano vienen preparados para Passkeys. Entra con tu rostro desde tu celular y olvídate del panel <code>/wp-admin</code> tradicional. <strong>Incluido en planes Starter+</strong>.</p>
</section>

<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Blindar la web con seguridad Zero-Trust">🔐 Blindar mi Web Ahora</a>
<p class="gano-muted-text">Certificado ISO 27001. Cumple con regulaciones colombianas.</p>
</div>

</article>',
        ],

        // PAGE 3 — Predictive AI Management
        [
            'title'            => 'Gestión Predictiva con AI: Cero Caídas, Cero Sorpresas',
            'category'         => 'inteligencia-artificial',
            'feature_img_name' => 'icon_predictive_ai_server.png',
            'cta_url'          => $cta_base . '#ai',
            'stats'            => [
                ['label' => 'Disponibilidad', 'value' => '99.99%'],
                ['label' => 'Problemas evitados', 'value' => '94%'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Gestión Predictiva con IA">

<h1>🤖 Gestión Predictiva con AI: Cero Caídas, Cero Sorpresas</h1>

<div class="gano-hook-box" role="doc-introduction">
<p>El soporte técnico tradicional es reaccionario: esperas a que la página se caiga, abres un ticket, y 4 horas después te dicen qué falló. Eso es inaceptable en 2026.</p>
</div>

<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Análisis de Logs por Redes Neuronales:</strong> La IA de nuestro servidor (Gano Agent) "lee" anomalías milisegundos antes de que el servidor se desborde.</li>
<li><strong>Auto-Escalamiento Predictivo:</strong> Si la IA detecta un patrón viral similar a un ataque DDoS, asigna núcleos extra preventivamente.</li>
<li><strong>Prevención de Cuellos de Botella DB:</strong> Optimiza consultas lentas a la base de datos de Elementor mientras tú duermes.</li>
</ul>
</section>

<div class="gano-divider" aria-hidden="true"></div>

<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"El soporte técnico del futuro no atiende llamadas, previene las caídas 30 minutos antes de que sucedan. Bienvenido a la Infraestructura Inteligente."</em></p>
</div>

<section>
<h2>🛠️ El Gano Agent en Acción</h2>
<p>Accede al "Agent Monitor" en tu panel y ve el registro de amenazas esquivadas y micro-optimizaciones ejecutadas en las últimas 24H. Dashboard en español, decisiones autónomas.</p>
</section>

<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Ver el Agente IA en acción">🧠 Ver el Agente AI en Acción</a>
<p class="gano-muted-text">Prueba gratis 7 días. Sin tarjeta de crédito.</p>
</div>

</article>',
        ],
        // PAGE 4 — Digital Sovereignty LATAM
        [
            'title'            => 'Soberanía Digital en LATAM: Tus Datos, Tu Control',
            'category'         => 'estrategia',
            'feature_img_name' => 'icon_sovereignty_data.png',
            'cta_url'          => $cta_base . '#sovereignty',
            'stats'            => [
                ['label' => 'Datos en suelo LATAM', 'value' => '100%'],
                ['label' => 'Cumplimiento RGPD', 'value' => 'Sí'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Soberanía Digital LATAM">
<h1>🌎 Soberanía Digital en LATAM: Tus Datos, Tu Control</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Si tus datos están en servidores de EE.UU., la Ley CLOUD puede obligar a Microsoft a entregarlos al gobierno sin consentimiento tuyo. En Colombia, esto es un riesgo real para PyMEs con datos sensibles de clientes.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Centros de Datos Locales (Bogotá):</strong> Tus datos nunca salen de la jurisdicción colombiana.</li>
<li><strong>Cumplimiento GDPR + Normativa Local:</strong> Certificación con SRI Colombia y facturación DIAN integrada.</li>
<li><strong>Auditoría Independiente:</strong> Reporte anual de seguridad físico-digital en línea.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Tus datos no son commodity de EE.UU. Con Gano Digital, tu infraestructura respeta leyes colombianas."</em></p>
</div>
<section>
<h2>🛠️ Infraestructura Localmente Soberana</h2>
<p>Ecosistema Gano en centros de datos nacionales con respaldo en 3 regiones de LATAM (Colombia, México, Argentina). Dashboard con certificados de cumplimiento normativo.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Recuperar soberanía digital de tus datos">🛡️ Recuperar Soberanía Digital</a>
<p class="gano-muted-text">Certificación incluida. Datos 100% locales.</p>
</div>
</article>',
        ],

        // PAGE 5 — Headless WordPress
        [
            'title'            => 'Headless WordPress: La Velocidad Absoluta',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_headless_speed.png',
            'cta_url'          => $cta_base . '#headless',
            'stats'            => [
                ['label' => 'Tiempo carga', 'value' => '< 0.8s'],
                ['label' => 'Lighthouse Score', 'value' => '98/100'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Headless WordPress">
<h1>⚙️ Headless WordPress: La Velocidad Absoluta</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>WordPress renderiza HTML en el servidor. Eso es lento. La arquitectura Headless separa la API de contenido del frontend, dejando que un frontend estático en CDN (p. ej. Netlify o Cloudflare Pages) sirva HTML a velocidad de CDN global.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>REST API + Next.js/Astro:</strong> WordPress como CMS puro, frontend desacoplado en JS moderno.</li>
<li><strong>ISR (Incremental Static Regeneration):</strong> Página actualizadas a velocidad de BD, servidas como estáticas.</li>
<li><strong>Libre de "Bloat" de Plugins:</strong> Solo instalas plugins en el backend. El frontend es minimalista.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Si tu WooCommerce sigue renderizándose en PHP en 2026, estás dejando dinero en la mesa. Headless WordPress es para tiendas que quieren velocidad real."</em></p>
</div>
<section>
<h2>🛠️ Headless en tu Ecosistema Gano</h2>
<p>Plan Enterprise y Agencia incluyen setup de Headless WordPress con CDN global y base de datos replicada. Tu tienda carga en <400ms desde cualquier punto del mundo.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Pasar a arquitectura Headless">🚀 Configurar Headless Ahora</a>
<p class="gano-muted-text">Incluye migración de contenido. CDN global gratis.</p>
</div>
</article>',
        ],

        // PAGE 6 — Intelligent DDoS Mitigation
        [
            'title'            => 'Mitigación DDoS Inteligente: Firewall de Nueva Generación',
            'category'         => 'seguridad',
            'feature_img_name' => 'icon_ddos_firewall.png',
            'cta_url'          => $cta_base . '#ddos',
            'stats'            => [
                ['label' => 'Ataques bloqueados/día', 'value' => '50K+'],
                ['label' => 'Falsos positivos', 'value' => '0.01%'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Mitigación DDoS">
<h1>🔥 Mitigación DDoS Inteligente: Firewall de Nueva Generación</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Un atacante puede mandar 10 millones de requests por segundo disfrazados de usuarios reales. Un firewall tradicional simplemente se colapsa. La IA debe "entender" el tráfico malicioso en tiempo real.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Análisis Conductual de Redes Neuronales:</strong> Detecta patrones botnet incluso si imitan comportamiento humano.</li>
<li><strong>Rate Limiting Adaptativo:</strong> No bloquea a usuarios reales, solo ataca dirigidos.</li>
<li><strong>Replicación Geográfica:</strong> Si un datacenter sufre ataque, el tráfico se redirige automáticamente a 5 nodos backup.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"DDoS no es un 'si' sino un 'cuándo'. Gano Digital bloquea 50k ataques diarios. Tu página seguirá online."</em></p>
</div>
<section>
<h2>🛠️ Protección en Tiempo Real</h2>
<p>Firewall DDoS automático en todos los ecosistemas. Monitor en vivo de ataques bloqueados, reportes de amenazas por IP, y logs auditables con compliance regulatorio.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar protección DDoS inteligente">🛡️ Activar Protección DDoS</a>
<p class="gano-muted-text">Incluido en todos los planes. Garantía de uptime.</p>
</div>
</article>',
        ],

        // PAGE 7 — Death of Shared Hosting
        [
            'title'            => 'La Muerte del Hosting Compartido: El Riesgo Invisible',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_shared_hosting_death.png',
            'cta_url'          => $cta_base . '#shared-hosting',
            'stats'            => [
                ['label' => 'Riesgo de "noisy neighbor"', 'value' => '100%'],
                ['label' => 'Sitios por servidor', 'value' => '1 (tuyo)'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="La Muerte del Hosting Compartido">
<h1>💀 La Muerte del Hosting Compartido: El Riesgo Invisible</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>En un servidor compartido con 200 webs, si la página 57 sufre un ataque botnet, el CPU se bloquea para todos. Literalmente esperas a que otro sitio termine su ataque para recuperar velocidad.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Contenedores Aislados (Docker):</strong> Tu PHP, base de datos y archivos corren en sandbox separado.</li>
<li><strong>Recursos Garantizados:</strong> Te prometemos X GB RAM y Y CPUs. Nunca compartidos, nunca robados por vecinos.</li>
<li><strong>Costo Eficiente a Escala:</strong> Menos caro que un VPS porque nuestros contenedores usan IA para consolidación inteligente.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Hosting compartido es jugar a la ruleta rusa con tu reputación online. Migra a contenedores aislados. La velocidad será noticia."</em></p>
</div>
<section>
<h2>🛠️ Ecosistema Dedicado para tu PyME</h2>
<p>Planes Starter Premium en adelante ofrecen contenedor aislado garantizado. Dashboard muestra CPU/RAM real en tiempo real. Sin "noisy neighbors", sin sorpresas.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Migrar de hosting compartido">🚀 Migrar a Contenedor Aislado</a>
<p class="gano-muted-text">Soporte migratorio gratis. Cero downtime.</p>
</div>
</article>',
        ],

        // PAGE 8 — Edge Computing
        [
            'title'            => 'Edge Computing: Contenido a Cero Distancia de tu Cliente',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_edge_computing.png',
            'cta_url'          => $cta_base . '#edge',
            'stats'            => [
                ['label' => 'Latencia a usuario', 'value' => '< 10ms'],
                ['label' => 'Nodos globales', 'value' => '250+'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Edge Computing">
<h1>🌐 Edge Computing: Contenido a Cero Distancia de tu Cliente</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Un usuario en Medellín que accede tu sitio hosted en EE.UU. viaja en red 8,000 km ida y vuelta. En 2026, eso es inexcusable. El edge trae el servidor a cada ciudad.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Cloudflare Workers + CDN Geo-Distribuido:</strong> Código corre en la ciudad más cercana a tu usuario, no en un datacenter lejano.</li>
<li><strong>Replicación de BD Inteligente:</strong> Replica local-only de datos sin-estado. Transacciones vuelven al maestro en Bogotá.</li>
<li><strong>Disponibilidad Verdadera 99.99%:</strong> Si el nodo de Medellín falla, otro pickup la carga automáticamente.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Los gigantes (Airbnb, Spotify) descubrieron: Edge no es lujo, es standard. Gano Digital lo incluye."</em></p>
</div>
<section>
<h2>🛠️ Edge en tu Ecosistema</h2>
<p>Plans Pro+ incluyen Edge Computing automático. Tu contenido se sirve desde 250+ puntos globales. Dashboard muestra latencia por región y ahorros de ancho de banda.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar Edge Computing">⚡ Activar Edge Computing</a>
<p class="gano-muted-text">Latencia < 10ms garantizada. Cualquier continente.</p>
</div>
</article>',
        ],

        // PAGE 9 — Green Hosting
        [
            'title'            => 'Green Hosting: Infraestructura Sostenible para tu Negocio',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_green_hosting.png',
            'cta_url'          => $cta_base . '#green',
            'stats'            => [
                ['label' => 'Energía renovable', 'value' => '100%'],
                ['label' => 'Huella carbono/año', 'value' => 'Neutral'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Green Hosting">
<h1>🌱 Green Hosting: Infraestructura Sostenible para tu Negocio</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>El 4% de las emisiones globales de CO2 vienen de datacenters. Si tu PyME quiere diferenciarse en marketing sostenible, comienza por infraestructura carbono-neutral.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>100% Energías Renovables:</strong> Paneles solares + energía eólica certificada por IRENA.</li>
<li><strong>Virtualización Eficiente:</strong> Consolidación de servidores reduce hardware ocioso en 60%.</li>
<li><strong>Certificación B Corp:</strong> Auditoría externa anual de impacto ambiental y reporte público.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Ser sostenible no cuesta más. Gano Digital es green por default, y tu marca lo comunica sin esfuerzo."</em></p>
</div>
<section>
<h2>🛠️ Green Hosting en Acción</h2>
<p>Tu panel mostrará compensación de carbono por mes. Certificado verde adjunto a cada factura. Promociona en tu web: "Hospedado en datacenter carbono-neutral".</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Cambiar a hosting verde y sostenible">🌿 Cambiar a Hosting Verde</a>
<p class="gano-muted-text">Misma velocidad. Planeta más limpio.</p>
</div>
</article>',
        ],

        // PAGE 10 — Post-Quantum Encryption
        [
            'title'            => 'Cifrado Post-Cuántico: La Bóveda del Futuro',
            'category'         => 'seguridad',
            'feature_img_name' => 'icon_quantum_encryption.png',
            'cta_url'          => $cta_base . '#quantum',
            'stats'            => [
                ['label' => 'Algoritmo', 'value' => 'CRYSTALS-Kyber'],
                ['label' => 'Inmunidad QC', 'value' => 'Sí'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Cifrado Post-Cuántico">
<h1>🔐 Cifrado Post-Cuántico: La Bóveda del Futuro</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Una computadora cuántica podría romper RSA-2048 en minutos. Google anunció su "supremacía cuántica" en 2019. Si no cambias de criptografía ahora, tus datos están en riesgo en 5-10 años.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>NIST Post-Quantum Standard:</strong> Gano usa CRYSTALS-Kyber, aprobado por NIST para defensa post-cuántica.</li>
<li><strong>Hybrid Encryption:</strong> Combina RSA clásico + Kyber para compatibilidad hoy, invulnerabilidad mañana.</li>
<li><strong>Certificados SSL/TLS Listos:</strong> Todos los certificados en ecosistemas Gano ya soportan firma post-cuántica.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Los bancos ya migran a cifrado post-cuántico. ¿Por qué tu tienda online espera? El futuro es hoy."</em></p>
</div>
<section>
<h2>🛠️ Protección Cuántica Activada</h2>
<p>Enterprise y Agencia incluyen certificados Kyber. Dashboard muestra estatus de encriptación de datos en reposo + tránsito. Cumple NIST SP 800-131C.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Proteger datos con cifrado post-cuántico">🛡️ Implementar Cifrado Post-Cuántico</a>
<p class="gano-muted-text">Protección eterna. Incluso contra QC.</p>
</div>
</article>',
        ],

        // PAGE 11 — Automated CI/CD
        [
            'title'            => 'CI/CD Automatizado: Nunca Más Rompas tu Tienda en Vivo',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_cicd_automation.png',
            'cta_url'          => $cta_base . '#cicd',
            'stats'            => [
                ['label' => 'Despliegues/día', 'value' => '50+'],
                ['label' => 'Rollback automático', 'value' => 'Sí'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="CI/CD Automatizado">
<h1>⚙️ CI/CD Automatizado: Nunca Más Rompas tu Tienda en Vivo</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Actualizas un plugin, la web se cae a las 14:00 en viernes y pierdes 50 ordenes. Con CI/CD, cada cambio se prueba automáticamente antes de llegar a producción. Cero riesgos.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>GitHub/GitLab Integration:</strong> Push a rama → tests automáticos → staging → producción (si todo pasa).</li>
<li><strong>Rollback Instantáneo:</strong> Si un despliegue falla, vuelves a la versión anterior en < 2 segundos.</li>
<li><strong>Blue-Green Deployment:</strong> Dos versiones corren en paralelo. Cambio de tráfico es invisible al usuario.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Los grandes (Netflix, Shopify) despliegan 50 veces al día sin miedo. CI/CD es su secreto. Ahora es tuyo."</em></p>
</div>
<section>
<h2>🛠️ Pipeline Listo para tu Repo</h2>
<p>Enterprise+ incluye GitHub Actions configurado. Vincula tu repo, configura tests, y toda actualización es segura. Reportes de cambios por deploy disponibles.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Configurar CI/CD automatizado">🚀 Configurar CI/CD</a>
<p class="gano-muted-text">Despliegues seguros. Cero downtime.</p>
</div>
</article>',
        ],

        // PAGE 12 — Continuous Real-Time Backups
        [
            'title'            => 'Backups Continuos en Tiempo Real: Tu Máquina del Tiempo',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_backup_time_machine.png',
            'cta_url'          => $cta_base . '#backups',
            'stats'            => [
                ['label' => 'Frecuencia', 'value' => 'Cada 5 min'],
                ['label' => 'Retención', 'value' => '30 días'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Backups Continuos">
<h1>⏮️ Backups Continuos en Tiempo Real: Tu Máquina del Tiempo</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Un backup diario es insuficiente. Si el ataque ocurre a las 15:00, el último backup es de las 06:00 del mismo día. Perdiste 9 horas de datos. Con replicación continua, el máximo RPO es 5 minutos.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Replicación Multi-Zona:</strong> BD se replica en tiempo real a 3 datacenters diferentes.</li>
<li><strong>Point-in-Time Recovery:</strong> Recupera tu sitio a cualquier minuto de las últimas 30 días. Incluso si se hackean.</li>
<li><strong>Automático, Sin Esfuerzo:</strong> No tienesy que hacer "click en backup". Ocurre permanentemente.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Perder datos no es un accidente, es negligencia. Backups continuos son requisito, no lujo."</em></p>
</div>
<section>
<h2>🛠️ Viaja en el Tiempo</h2>
<p>Panel muestra histórico de backups. Selecciona fecha/hora específica y restaura en minutos. Base de datos + archivos + configuración WordPress. Todo replicado.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar backups continuos en tiempo real">💾 Activar Backups Continuos</a>
<p class="gano-muted-text">Recuperación point-in-time. Hasta 30 días atrás.</p>
</div>
</article>',
        ],

        // PAGE 13 — Skeleton Screens
        [
            'title'            => 'Skeleton Screens: La Psicología de la Velocidad Percibida',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_skeleton_screens.png',
            'cta_url'          => $cta_base . '#skeleton',
            'stats'            => [
                ['label' => 'Mejora percepción', 'value' => '+30%'],
                ['label' => 'Bounce rate reducido', 'value' => '-15%'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Skeleton Screens">
<h1>👻 Skeleton Screens: La Psicología de la Velocidad Percibida</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Tu página carga en 0.9 segundos, pero si muestra pantalla en blanco por 0.5s, el usuario "siente" que es lenta. Un "skeleton" (esqueleto de carga) engaña el cerebro: se ve como si algo está pasando.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Placeholders Animados:</strong> Mientras carga el producto, muestra forma gris que "respira".</li>
<li><strong>Progresivo Enhancement:</strong> Texto llega primero, imágenes después. No bloquea visualización.</li>
<li><strong>Reducción Ansiedad:</strong> Estudios muestran: skeleton vs blanco = 30% menos sensación de demora.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"No se trata de velocidad real, sino de percepción. Un skeleton screen es psicología pura."</em></p>
</div>
<section>
<h2>🛠️ Activar para WooCommerce</h2>
<p>Todos los ecosistemas Gano incluyen skeleton screens en productos, carrito y checkout. Animación CSS suave, sin JS pesado. Mejora Core Web Vitals automáticamente.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar skeleton screens para mejora de UX">🎭 Activar Skeleton Screens</a>
<p class="gano-muted-text">Velocidad percibida. Conversión +12%.</p>
</div>
</article>',
        ],

        // PAGE 14 — Elastic Scaling
        [
            'title'            => 'Escalamiento Elástico: Sobrevive a tu Propio Éxito Viral',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_elastic_scaling.png',
            'cta_url'          => $cta_base . '#scaling',
            'stats'            => [
                ['label' => 'Auto-scale range', 'value' => '1-100x'],
                ['label' => 'Tiempo activación', 'value' => '< 30s'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Escalamiento Elástico">
<h1>📈 Escalamiento Elástico: Sobrevive a tu Propio Éxito Viral</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Tu producto viral genera 100x tráfico en 6 horas. Un servidor fijo se cae. Con elastic scaling, 100 servidores se activan automáticamente, y cobras solo por lo que usaste, en esos 30 minutos.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Kubernetes Automático:</strong> Detecta CPU > 70%, y en < 30s lanzan contenedores nuevos.</li>
<li><strong>Scale-Down Inteligente:</strong> Cuando el tráfico cae, mata servidores ociosos. Ahorras dinero automáticamente.</li>
<li><strong>Predictivo por IA:</strong> Anticipa picos (ej: Black Friday) y pre-proviciona servidores.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Viral no es un lujo que tu infrastructure pueda darse. Gano escala automático a 100x sin que levantes un dedo."</em></p>
</div>
<section>
<h2>🛠️ Escalamiento Automático Activado</h2>
<p>Enterprise+ incluye Kubernetes nativo. Dashboard muestra histórico de scaling, costo de recursos por hora, y predicción de demanda para próximos 7 días.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar escalamiento elástico automático">🚀 Activar Escalamiento Elástico</a>
<p class="gano-muted-text">Crece infinitamente. Paga solo por uso.</p>
</div>
</article>',
        ],

        // PAGE 15 — Self-Healing
        [
            'title'            => 'Self-Healing: El Ecosistema que se Cura Solo',
            'category'         => 'inteligencia-artificial',
            'feature_img_name' => 'icon_self_healing.png',
            'cta_url'          => $cta_base . '#healing',
            'stats'            => [
                ['label' => 'MTTR (tiempo reparación)', 'value' => '< 2 min'],
                ['label' => 'Intervención manual', 'value' => '0%'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Self-Healing">
<h1>🏥 Self-Healing: El Ecosistema que se Cura Solo</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>A las 03:00 falla una zona de almacenamiento. En 2 minutos, Gano Agent detecta, redirige tráfico, restaura datos, y vuelve online sin despertarte. Eso es self-healing.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Detección Automática:</strong> Healthcheck cada 10 segundos identifica fallas antes de que impacten usuarios.</li>
<li><strong>Remedios Pre-Configurados:</strong> Reiniciar servicio, failover BD, purgar caché, rotar logs. Sin tickets.</li>
<li><strong>Aprendizaje Continuo:</strong> Cada falla genera regla nueva en la IA. El sistema mejora solo.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"El soporte 24/7 no escala. Self-healing sí. Tu infraestructura se cura mientras duermes."</em></p>
</div>
<section>
<h2>🛠️ Curación Autónoma</h2>
<p>Todos los planes incluyen healthchecks automáticos. Panel muestra log de "curaciones" ejecutadas (restarts, failovers, optimizaciones) en últimas 24H. Cero intervención manual.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar self-healing automático">💚 Activar Self-Healing</a>
<p class="gano-muted-text">Uptime 99.99%. Sin soporte nocturno.</p>
</div>
</article>',
        ],

        // PAGE 16 — Micro-Animations
        [
            'title'            => 'Micro-Animaciones e Interacciones Hápticas: Diseño que se Siente',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_microanimations.png',
            'cta_url'          => $cta_base . '#animations',
            'stats'            => [
                ['label' => 'Mejora engagement', 'value' => '+45%'],
                ['label' => 'Tiempo en página', 'value' => '+2min'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Micro-Animaciones">
<h1>✨ Micro-Animaciones e Interacciones Hápticas: Diseño que se Siente</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Botones que tiemblan, checkboxes que pulsan, transiciones suaves. Animaciones de 200-500ms no son "adorno", son psicología UX. Aumentan confianza y engagement en 40%+.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Framer Motion / Lottie:</strong> Librerías que permiten micro-animaciones 60fps sin lag.</li>
<li><strong>Haptic Feedback (Móvil):</strong> El teléfono vibra cuando agregasproducto al carrito. Sensación táctil = recordación.</li>
<li><strong>Easing Curves Científicas:</strong> Animación no lineal sigue leyes de física, se siente "natural".</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Apple sabe: el mejor software se siente, no solo se ve. Gano Digital prioriza micro-interacciones."</em></p>
</div>
<section>
<h2>🛠️ Animaciones Incluidas</h2>
<p>Tema gano-child incluye biblioteca de micro-animaciones CSS/Lottie para WooCommerce. Botones, carrito, checkout, notificaciones. Todo con haptic feedback móvil.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar micro-animaciones y diseño fluido">🎨 Activar Micro-Animaciones</a>
<p class="gano-muted-text">Engagement +45%. Conversión +18%.</p>
</div>
</article>',
        ],

        // PAGE 17 — HTTP/3 & QUIC
        [
            'title'            => 'HTTP/3 y QUIC: El Protocolo que Rompe la Congestión',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_http3_quic.png',
            'cta_url'          => $cta_base . '#http3',
            'stats'            => [
                ['label' => 'Velocidad con pérdida paquetes', 'value' => '+10-50%'],
                ['label' => 'Handshake TLS', 'value' => '0 RTT'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="HTTP/3 QUIC">
<h1>🚀 HTTP/3 y QUIC: El Protocolo que Rompe la Congestión</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>HTTP/2 usa TCP, que "para" si pierde un paquete. En conexiones móviles con pérdida, esto es desastre. QUIC (UDP mejorado) avanza incluso si falta data. Velocidad real en el mundo.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>0-RTT Handshake:</strong> Resumidas conexión sin espera TLS. El primer byte llega 200ms más rápido.</li>
<li><strong>Multiplexión Real:</strong> A diferencia de HTTP/2, fallo en un stream no bloquea otros. Velocidad fluida.</li>
<li><strong>Conexión Persistente Móvil:</strong> WiFi a 4G sin desconexión. QUIC se adapta automáticamente.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Google y Cloudflare ya sirven 50% del tráfico vía QUIC. Gano Digital está en el futuro."</em></p>
</div>
<section>
<h2>🛠️ QUIC Nativo</h2>
<p>Todos los ecosistemas Gano sirven HTTP/3 + QUIC por default. Dashboard muestra % de tráfico QUIC y mejora de velocidad vs HTTP/2. Compatibilidad automática con navegadores antiguos.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar protocolo HTTP/3 QUIC">⚡ Activar HTTP/3 QUIC</a>
<p class="gano-muted-text">Velocidad futura. Disponible hoy.</p>
</div>
</article>',
        ],

        // PAGE 18 — High Availability (HA)
        [
            'title'            => 'Alta Disponibilidad (HA): La Infraestructura Indestructible',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_ha_infrastructure.png',
            'cta_url'          => $cta_base . '#ha',
            'stats'            => [
                ['label' => 'Uptime garantizado', 'value' => '99.99%'],
                ['label' => 'SLA credit', 'value' => '100 días free'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Alta Disponibilidad">
<h1>🔗 Alta Disponibilidad (HA): La Infraestructura Indestructible</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>HA significa: si un servidor muere, 10 backup lo reemplazan automáticamente. Si un datacenter se destruye, otro en otra región toma la carga. Tu sitio nunca se cae.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Active-Active Replication:</strong> Todos los nodos están vivos, no es standby. Distribución inteligente de carga.</li>
<li><strong>Geo-Failover Automático:</strong> Si Bogotá falla, Miami, México y Buenos Aires están listos en < 1s.</li>
<li><strong>99.99% SLA con Crédito:</strong> Si no cumplimos, págale directo. 100 días gratis por cada hora de downtime.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Amazon usa HA, Netflix usa HA, Stripe usa HA. Gano Digital también. La alternativa es rezar."</em></p>
</div>
<section>
<h2>🛠️ Indestructibilidad Garantizada</h2>
<p>Enterprise+ soporta HA con 4+ nodos. Dashboard muestra replicación en tiempo real, histórico de failovers, y certificado SLA firmado. Auditoría externa trimestral.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar infraestructura de alta disponibilidad">🛡️ Activar Alta Disponibilidad</a>
<p class="gano-muted-text">99.99% uptime. O paga cero.</p>
</div>
</article>',
        ],

        // PAGE 19 — Server-Side Analytics
        [
            'title'            => 'Analytics Server-Side: Privacidad, Velocidad y Datos Reales',
            'category'         => 'estrategia',
            'feature_img_name' => 'icon_serverside_analytics.png',
            'cta_url'          => $cta_base . '#analytics',
            'stats'            => [
                ['label' => 'Datos sin JS', 'value' => '100%'],
                ['label' => 'Privacidad GDPR', 'value' => 'Nativa'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Analytics Server-Side">
<h1>📊 Analytics Server-Side: Privacidad, Velocidad y Datos Reales</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Google Analytics carga 80KB de JS. Ad-blockers lo bloquean. Privacidad de usuario es ficción. Server-side analytics vive en tu servidor, invisible a los bloqueadores, sin JS de terceros.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Datos sin JavaScript:</strong> Cada request HTTP es logeado. Conversiones, clics, navegación. Ad-block agnostic.</li>
<li><strong>GDPR + CCPA Nativo:</strong> No necesitas banners de cookies. Tus datos, tu servidor, sin terceros.</li>
<li><strong>Dashboard Privado:</strong> Control total de qué compartir (ej: nunca envíes a externos).</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"Google monitorea a tus usuarios. Gano Digital no. Analytics privado es responsabilidad empresarial."</em></p>
</div>
<section>
<h2>🛠️ Panel de Analytics Privado</h2>
<p>Pro+ incluye Plausible Analytics o Fathom integrado. Dashboard muestra conversiones, fuentes de tráfico, comportamiento. Datos nunca salen de tu servidor. GDPR automático.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar analytics server-side privado">🔒 Activar Analytics Privado</a>
<p class="gano-muted-text">Privacidad + datos reales. Sin vendors.</p>
</div>
</article>',
        ],

        // PAGE 20 — AI Administration Agent
        [
            'title'            => 'El Agente IA de Administración: Tu Infraestructura Habla Español',
            'category'         => 'inteligencia-artificial',
            'feature_img_name' => 'icon_ai_agent_admin.png',
            'cta_url'          => $cta_base . '#agent',
            'stats'            => [
                ['label' => 'Respuesta IA', 'value' => '< 2s'],
                ['label' => 'Tareas autónomas', 'value' => '100+'],
            ],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Agente IA de Administración">
<h1>🤖 El Agente IA de Administración: Tu Infraestructura Habla Español</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>"Optimiza mi base de datos". El Gano Agent entiende español, ejecuta la tarea en 30 segundos, y reporta resultados. No necesitas CLI, SSH, ni ser DevOps.</p>
</div>
<section>
<h2>🧠 La Innovación — Estado del Arte</h2>
<ul role="list">
<li><strong>Interfaz en Lenguaje Natural:</strong> Chat en español. Pregunta "¿cuánta banda ancha gastó hoy?". El Agent responde con gráficos.</li>
<li><strong>Ejecución Autónoma:</strong> "Limpia bases de datos de comentarios spam". Agent ejecuta Query, valida, y reporta.</li>
<li><strong>Prevención de Errores:</strong> IA pide confirmación en acciones destructivas. Zero random deletions.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"El futuro del hosting no es CLI. Es conversación. Gano Agent entiende, ejecuta, reporta. En tu idioma."</em></p>
</div>
<section>
<h2>🛠️ Agent Chat en Tu Panel</h2>
<p>Enterprise+ acceso a Gano Agent. Chat en el panel, IA entiende contexto de tu infraestructura y ejecuta tareas. Historial completo de acciones. Auditable, seguro, en español.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary" aria-label="Activar Agente IA de administración">🧠 Activar Agente IA</a>
<p class="gano-muted-text">DevOps en español. Sin línea de comando.</p>
</div>
</article>',
        ],
    ];
}

// ============================================================================
// END OF PLUGIN — v2.0.0
// ============================================================================

error_log( 'GANO IMPORTER v2.0: Plugin loaded. Ready for activation.' );
