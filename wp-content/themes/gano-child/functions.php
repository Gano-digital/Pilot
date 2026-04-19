<?php
/**
 * Gano Digital Child Theme Functions
 * Arquitectura Elementor: memory/content/elementor-architecture-wave3.md
 * V-05 Fix: Auditoria Gano Digital, Marzo 2026.
 *   — Nonce CSRF agregado al script del chat IA.
 *   — Endpoint REST del chat registrado con permission_callback de nonce.
 *   — Endpoint del chat añadido a lista blanca del MU plugin de seguridad.
 */

// =============================================================================
// 0. INCLUDES — bloques de homepage (shortcodes)
// =============================================================================

require_once get_stylesheet_directory() . '/inc/homepage-blocks.php';
require_once get_stylesheet_directory() . '/inc/contact-form-handler.php';
require_once get_stylesheet_directory() . '/inc/lead-magnet-handler.php';

// =============================================================================
// 0.5 CSS CRÍTICO — Prioridad 1 (inline en wp_head antes de Elementor)
// =============================================================================
/**
 * Inyecta estilos críticos inline con prioridad 1.
 * Esto asegura que nuestras variables y estilos fundamentales ganen
 * contra la especificidad de Elementor (que corre a prioridad 10).
 *
 * Solo en homepage para no contaminar el resto del sitio.
 */
add_action( 'wp_head', 'gano_font_preconnect', 1 );
function gano_font_preconnect() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}

add_action( 'wp_head', 'gano_critical_css', 1 );
function gano_critical_css() {
    if ( ! is_front_page() ) {
        return;
    }

    echo '<style id="gano-critical">';
    echo ':root {';
    echo '  --gc-primary: #1B4FD8;';
    echo '  --gc-secondary: #00C26B;';
    echo '  --gc-accent: #D4AF37;';
    echo '  --gc-dark: #05080b;';
    echo '  --gc-dark-card: rgba(255,255,255,0.03);';
    echo '  --gc-dark-border: rgba(255,255,255,0.08);';
    echo '  --gc-content-bg: #f8fafc;';
    echo '  --gc-text: #e2e8f0;';
    echo '  --gc-text-muted: #94a3b8;';
    echo '  --gc-text-light: #1e293b;';
    echo '  --gc-glow: rgba(27,79,216,0.4);';
    echo '}';
    echo '.gano-home { background: var(--gc-dark) !important; color: var(--gc-text) !important; }';
    echo '.hero-gano { background: radial-gradient(circle at 50% 50%, #1e2530 0%, var(--gc-dark) 100%) !important; }';
    echo '</style>';
}

// =============================================================================
// 0.6 ADMIN PAGE — Ver leads capturados
// =============================================================================

add_action( 'admin_menu', 'gano_add_leads_admin_page' );
function gano_add_leads_admin_page(): void {
    add_menu_page(
        'Leads Capturados',
        'Leads',
        'manage_options',
        'gano-leads',
        'gano_render_leads_page',
        'dashicons-email-alt',
        20
    );
}

function gano_render_leads_page(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'No tienes permiso para acceder a esta página.' );
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e( 'Leads Capturados', 'gano-child' ); ?></h1>
        <?php
        $leads = (array) get_option( 'gano_leads', array() );
        if ( empty( $leads ) ) {
            echo '<p><em>' . esc_html__( 'No hay leads capturados aún.', 'gano-child' ) . '</em></p>';
            return;
        }
        ?>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php esc_html_e( 'Email', 'gano-child' ); ?></th>
                    <th><?php esc_html_e( 'Plan', 'gano-child' ); ?></th>
                    <th><?php esc_html_e( 'Fecha', 'gano-child' ); ?></th>
                    <th><?php esc_html_e( 'IP', 'gano-child' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( array_reverse( $leads ) as $lead ) : ?>
                    <tr>
                        <td><?php echo esc_html( $lead['email'] ); ?></td>
                        <td><?php echo esc_html( $lead['plan'] ); ?></td>
                        <td><?php echo esc_html( $lead['time'] ); ?></td>
                        <td><?php echo esc_html( $lead['ip'] ); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p style="margin-top: 20px; font-size: 0.9rem; color: #666;">
            <?php echo esc_html( sprintf( __( 'Total: %d leads', 'gano-child' ), count( $leads ) ) ); ?>
        </p>
    </div>
    <?php
}

// =============================================================================
// 1. ENQUEUE DE ESTILOS Y SCRIPTS
// =============================================================================

add_action( 'wp_enqueue_scripts', 'gano_child_enqueue_styles' );
function gano_child_enqueue_styles() {
    // Estilos del tema padre y hijo
    wp_enqueue_style( 'royal-elementor-kit-parent', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'gano-child-style', get_stylesheet_uri(), array( 'royal-elementor-kit-parent' ), wp_get_theme()->get( 'Version' ) );

    // Homepage SOTA — solo en front page
    if ( is_front_page() ) {
        wp_enqueue_style( 'gano-homepage-css', get_stylesheet_directory_uri() . '/css/homepage.css', array( 'gano-child-style' ), '1.0.0' );
    }

    // Navegación sticky — todas las páginas
    wp_enqueue_style( 'gano-nav-css', get_stylesheet_directory_uri() . '/css/gano-nav.css', array( 'gano-child-style' ), '1.0.0' );

    // Chat IA — se carga con nonce CSRF (V-05 Fix)
    wp_enqueue_style( 'gano-chat-css', get_stylesheet_directory_uri() . '/css/gano-chat.css', array(), '1.2.0' );
    wp_enqueue_script( 'gano-chat-js', get_stylesheet_directory_uri() . '/js/gano-chat.js', array(), '1.4.0', true );

    // Pasar nonce y URLs al script del chat — evita CSRF y desacopla URLs hardcodeadas
    wp_localize_script( 'gano-chat-js', 'ganoChatConfig', array(
        'nonce'        => wp_create_nonce( 'gano_chat_nonce' ),
        'logEndpoint'  => rest_url( 'gano-agent/v1/log' ),
        'chatEndpoint' => rest_url( 'gano/v1/chat' ),
        'siteUrl'      => esc_url( get_site_url() ),
    ) );

    // Quiz de Soberanía Digital
    wp_enqueue_style( 'gano-quiz-css', get_stylesheet_directory_uri() . '/css/gano-quiz.css', array(), '1.0.0' );
    wp_enqueue_script( 'gano-quiz-js', get_stylesheet_directory_uri() . '/js/gano-sovereignty-quiz.js', array(), '1.0.0', true );

    // Custom Cursor (Phase 4 - Visual Polish)
    wp_enqueue_style( 'gano-cursor-style', get_stylesheet_directory_uri() . '/css/gano-cursor.css', array(), '1.1.0' );
    wp_enqueue_script( 'gano-cursor-js', get_stylesheet_directory_uri() . '/js/gano-cursor.js', array(), '1.1.0', true );

    // GSAP 3 Core & Plugins — Solo en páginas que lo necesitan (Phase 5 - SOTA Animation)
    $needs_gsap = is_front_page()
        || is_page_template( 'templates/page-sota-hub.php' )
        || is_page_template( 'templates/sota-single-template.php' )
        || is_page_template( 'templates/shop-premium.php' )
        || is_page_template( 'templates/page-ecosistemas.php' );

    if ( $needs_gsap ) {
        wp_enqueue_script( 'gsap-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true );
        wp_enqueue_script( 'gsap-scroll-trigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array( 'gsap-js' ), '3.12.5', true );
        wp_enqueue_script( 'gsap-text-plugin', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js', array( 'gsap-js' ), '3.12.5', true );

        // Gano SOTA FX Handler (The "Vivid & Animated" Experience)
        wp_enqueue_script( 'gano-sota-fx', get_stylesheet_directory_uri() . '/js/gano-sota-fx.js', array( 'gsap-scroll-trigger' ), '1.0.0', true );
    }
    wp_enqueue_style( 'gano-sota-animations', get_stylesheet_directory_uri() . '/gano-sota-animations.css', array(), '2.0.0' );

    // Constellation — HUD base (chips, paneles) con motion tokens unificados
    wp_enqueue_style( 'gano-constellation', get_stylesheet_directory_uri() . '/css/gano-constellation.css', array( 'gano-child-style' ), '1.0.0' );

    // Capa de convergencia visual SOTA (bridge entre identidad base y Kinetic Monolith)
    wp_enqueue_style(
        'gano-sota-convergence',
        get_stylesheet_directory_uri() . '/css/gano-sota-convergence.css',
        array( 'gano-child-style' ),
        '1.0.0'
    );

    // Ecosistemas — catálogo de planes (cd-content-002)
    if ( is_page_template( 'templates/page-ecosistemas.php' ) ) {
        wp_enqueue_style( 'gano-ecosistemas-css', get_stylesheet_directory_uri() . '/css/ecosistemas.css', array( 'gano-child-style' ), '1.0.0' );
    }

    // Constellation 3D Hero — se carga cuando el template o shortcode lo necesite
    // Detecta la clase .gano-constellation-wrap en páginas/templates que la incluyan.
    wp_register_script( 'gano-constellation', get_stylesheet_directory_uri() . '/js/gano-constellation.js', array(), '1.0.0', true );
    if (
        is_front_page() ||
        is_page_template( 'templates/page-sota-hub.php' ) ||
        is_page_template( 'templates/sota-single-template.php' )
    ) {
        wp_enqueue_script( 'gano-constellation' );
    }

    // Animaciones de tienda y Quiz de Descubrimiento — solo en templates de tienda
    if ( is_page_template( 'templates/shop-premium.php' ) || is_page_template( 'templates/sota-single-template.php' ) ) {
        wp_enqueue_script( 'gano-shop-animations', get_stylesheet_directory_uri() . '/js/shop-animations.js', array( 'gsap-scroll-trigger' ), '1.1.0', true );
        
        // Ecosystem Discovery Quiz (Phase 4)
        wp_enqueue_style( 'gano-bundle-quiz-css', get_stylesheet_directory_uri() . '/css/gano-bundle-quiz.css', array(), '1.0.0' );
        wp_enqueue_script( 'gano-bundle-quiz-js', get_stylesheet_directory_uri() . '/js/gano-bundle-quiz.js', array(), '1.0.0', true );
    }

    // Constellation SC Overlays — responsive TL/TR/modal/portals (cx-06)
    if ( is_page_template( 'templates/shop-premium.php' ) ) {
        wp_enqueue_style(
            'gano-constellation-overlay',
            get_stylesheet_directory_uri() . '/css/gano-constellation-overlay.css',
            array( 'gano-child-style' ),
            '1.0.0'
        );
        wp_enqueue_script(
            'gano-constellation-overlay',
            get_stylesheet_directory_uri() . '/js/gano-constellation-overlay.js',
            array(),
            '1.0.0',
            true
        );
    }

    // Navegación sticky + mega-dropdown — todas las páginas
    wp_enqueue_script( 'gano-nav', get_stylesheet_directory_uri() . '/js/gano-nav.js', array(), '1.0.0', true );
}

/**
 * Gano SOTA: Inyectar filtro SVG de grano orgánico en el footer.
 * Esto permite dar una textura "vívida y analógica" a los paneles de cristal.
 */
add_action( 'wp_footer', function() {
    ?>
    <svg style="display:none">
      <filter id="gano-organic-noise">
        <feTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch" />
        <feColorMatrix type="saturate" values="0" />
        <feComponentTransfer>
          <feFuncR type="linear" slope="0.05" />
          <feFuncG type="linear" slope="0.05" />
          <feFuncB type="linear" slope="0.05" />
        </feComponentTransfer>
        <feBlend in="SourceGraphic" mode="soft-light" />
      </filter>
    </svg>
    <?php
} );

// =============================================================================
// 1b. ACCESIBILIDAD — Skip link + anchor de destino (WCAG 2.4.1, 2.4.7)
// =============================================================================

/**
 * Inyecta el skip-link al inicio de <body> para usuarios de teclado/lectores de pantalla.
 * El CSS asociado está en style.css (.gano-skip-link) — no hay estilos inline
 * y por tanto no afecta la CSP del MU plugin gano-security.php.
 * Target: #gano-main-content (anchor inyectado en gano_main_content_anchor).
 *
 * Compatible con Hello Elementor y plantillas canvas de Elementor.
 * Hook wp_body_open requiere <?php wp_body_open(); ?> en el header del tema padre.
 * Hello Elementor lo soporta desde la versión 2.7.0.
 */
add_action( 'wp_body_open', 'gano_skip_link', 1 );
function gano_skip_link(): void {
    echo '<a class="gano-skip-link" href="#gano-main-content">'
        . esc_html__( 'Saltar al contenido principal', 'gano-child' )
        . '</a>';
}

/**
 * Inyecta el anchor receptor del skip link justo antes del contenido principal.
 * Se usa wp_before_admin_bar_render como fallback si wp_body_open no existe.
 * En el front-end, el anchor se coloca con elementor/frontend/the_excerpt/widget_before
 * o simplemente en wp_body_open con tabindex="-1" en el wrapper de Elementor.
 *
 * Para plantillas Elementor full-width: asigna el ID "gano-main-content" a la
 * primera sección del canvas desde el editor de Elementor (Advanced > CSS ID).
 */
add_action( 'wp_body_open', 'gano_main_content_anchor', 5 );
function gano_main_content_anchor(): void {
    echo '<span id="gano-main-content" tabindex="-1" class="gano-a11y-anchor"></span>';
}

// =============================================================================
// 1c. MENÚS — ubicaciones 'primary', 'header' y 'footer'
//     Hello Elementor registra 'main'; kits de Elementor usan 'primary' o 'header'.
//     Se registran las tres para que el widget Nav Menu de Elementor las muestre.
// =============================================================================

add_action( 'after_setup_theme', 'gano_child_register_nav_menus', 20 );
function gano_child_register_nav_menus(): void {
    register_nav_menus(
        array(
            'primary' => esc_html__( 'Menú principal (header / Elementor)', 'gano-child' ),
            'header'  => esc_html__( 'Menú de cabecera (Hello Elementor Kit)', 'gano-child' ),
            'footer'  => esc_html__( 'Menú de pie de página', 'gano-child' ),
        )
    );
}

/**
 * Asigna automáticamente el primer menú disponible a todas las ubicaciones
 * que estén vacías: 'primary', 'header' y 'footer'.
 *
 * Orden de preferencia para el ID de menú:
 *   1. Ubicación 'main' — registrada por Hello Elementor (tema padre); se reutiliza
 *      para no duplicar el menú si el usuario ya lo configuró allí.
 *   2. Primer menú registrado en la BD.
 *
 * Hooks:
 *   - init: cubre cualquier visita de frontend/admin antes de que el administrador
 *     asigne los menús manualmente. get_theme_mod() usa cache de opciones, por lo que
 *     el early-return (caso ya configurado) es una operación O(1) sin consulta a BD.
 *   - after_switch_theme: cubre activaciones en frío (instalación nueva). Cuando ambos
 *     hooks se disparan en la misma petición (cambio de tema), la segunda llamada
 *     hace early-return porque la primera ya guardó los valores.
 */
add_action( 'init', 'gano_child_assign_nav_menu_locations' );
add_action( 'after_switch_theme', 'gano_child_assign_nav_menu_locations' );
function gano_child_assign_nav_menu_locations(): void {
    $locations = get_theme_mod( 'nav_menu_locations', [] );

    // Ubicaciones gestionadas por este tema (child).
    $managed = [ 'primary', 'header', 'footer' ];

    // Early-return si todas las ubicaciones ya tienen un menú válido asignado.
    $empty_locs = array_filter(
        $managed,
        static fn( string $loc ) => empty( $locations[ $loc ] )
    );
    if ( empty( $empty_locs ) ) {
        return;
    }

    // Determinar el ID de menú a asignar.
    $menu_id = 0;

    // 1. Reutilizar 'main' si el tema padre ya lo tiene asignado.
    if ( ! empty( $locations['main'] ) ) {
        $menu_id = (int) $locations['main'];
    }

    // 2. Usar cualquier menú existente en la BD.
    if ( ! $menu_id ) {
        $menus = wp_get_nav_menus();
        if ( ! empty( $menus ) ) {
            $menu_id = (int) $menus[0]->term_id;
        }
    }

    if ( ! $menu_id ) {
        return; // No hay menús en el sistema; nada que asignar.
    }

    // Asignar $menu_id a todas las ubicaciones que estén vacías.
    foreach ( $empty_locs as $loc ) {
        $locations[ $loc ] = $menu_id;
    }

    set_theme_mod( 'nav_menu_locations', $locations );
}

// =============================================================================
// 2. CORE WEB VITALS & PERFORMANCE — Fase 3
// =============================================================================

/**
 * Eliminar recursos innecesarios que añaden peso sin beneficio.
 * Ahorro estimado: ~50-80 KB de JS/CSS del <head>.
 */
add_action( 'init', function() {
    // Emojis de WordPress — ~50 KB de JS que casi nadie usa en sitios de negocio
    remove_action( 'wp_head',             'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles',     'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles',  'print_emoji_styles' );
    remove_filter( 'the_content_feed',    'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss',    'wp_staticize_emoji' );
    remove_filter( 'wp_mail',             'wp_staticize_emoji_for_email' );

    // Head meta de bajo valor (seguridad + limpieza de HTML)
    remove_action( 'wp_head', 'rsd_link' );              // Really Simple Discovery
    remove_action( 'wp_head', 'wlwmanifest_link' );      // Windows Live Writer
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );  // Shortlink duplicado
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 ); // Solo útil en blogs

    // Feeds adicionales en <head> (los feeds del sitio siguen funcionando, solo se quita el <link> del head)
    remove_action( 'wp_head', 'feed_links_extra', 3 );
} );

/**
 * Ocultar la versión de WordPress del <head> y del feed RSS.
 * Exponer la versión facilita ataques dirigidos a CVEs específicos.
 */
add_filter( 'the_generator', '__return_empty_string' );
remove_action( 'wp_head', 'wp_generator' );

/**
 * Diferir scripts no críticos para mejorar TBT (Total Blocking Time) y FID.
 * Estos scripts son funcionales pero no necesitan bloquear el renderizado inicial.
 */
add_filter( 'script_loader_tag', function( string $tag, string $handle ): string {
    $defer_handles = array(
        'gano-chat-js',    // Chat IA — no crítico para el LCP
        'gano-quiz-js',    // Quiz Soberanía — interacción, no crítico
        'gano-shop-animations', // Animaciones tienda — presentacional
        'comment-reply',   // WordPress comments — no aplica en sitio de hosting
    );
    if ( in_array( $handle, $defer_handles, true ) ) {
        // Agregar defer si no lo tiene ya (no duplicar si el plugin ya lo añadió)
        if ( ! str_contains( $tag, ' defer' ) ) {
            $tag = str_replace( ' src=', ' defer src=', $tag );
        }
    }
    return $tag;
}, 10, 2 );

/**
 * Precargar fuentes críticas para evitar FOIT (flash of invisible text).
 * Ajustar las URLs si se cambia la tipografía en Elementor.
 */
add_action( 'wp_head', function() {
    // Solo en frontend (no wp-admin)
    if ( is_admin() ) {
        return;
    }
    // Fuente principal — si se usa Google Fonts vía Elementor, agregar aquí la URL del woff2
    // $font_url = 'https://fonts.gstatic.com/s/inter/v13/UcCO3FwrK3iLTeHuS_nVMrMxCp50SjIw2boKoduKmMEVuLyfAZ9hiA.woff2';
    // echo '<link rel="preload" href="' . esc_url( $font_url ) . '" as="font" type="font/woff2" crossorigin>' . "\n";

    // fetchpriority en img hero via JavaScript inline mínimo (Elementor no expone PHP hook para esto)
    // Solo se aplica en homepage para no afectar otras páginas ni el admin
    if ( is_front_page() ) {
        ?>
        <script>
        // Gano: LCP Optimization — marcar la primera imagen del hero como fetchpriority="high"
        // Compatible con Elementor Containers (e-con) y secciones clásicas (elementor-section)
        (function(){
            // Selectores en orden de especificidad: Containers primero, luego classic, luego fallback
            var HERO_SEL = [
                '.e-con .elementor-widget-image img',
                '.e-con-full .elementor-widget-image img',
                '.elementor-top-section .elementor-widget-image img',
                '.elementor-section .elementor-widget-image img',
                '.elementor-widget-image img'
            ].join(',');

            var found = false;
            function markHero() {
                var img = document.querySelector(HERO_SEL);
                if (img) {
                    img.setAttribute('fetchpriority', 'high');
                    img.loading = 'eager';
                    found = true;
                    return true;
                }
                return false;
            }

            // Intentar inmediatamente (imagen ya en DOM en carga parcial del parser)
            if (markHero()) return;

            var obs = new MutationObserver(function(_, o) {
                if (markHero()) { o.disconnect(); }
            });
            obs.observe(document.documentElement, {childList:true, subtree:true});

            // Desconectar al terminar de parsear el DOM o a los 2500ms (lo que ocurra primero)
            // Solo invocar markHero() si el observer nunca encontró la imagen
            function cleanup() { obs.disconnect(); if (!found) { markHero(); } }
            document.addEventListener('DOMContentLoaded', cleanup, {once:true});
            setTimeout(cleanup, 2500);
        })();
        </script>
        <?php
    }
}, 2 );

/**
 * Agregar etiqueta de idioma y charset explícito (mejora SEO y accesibilidad).
 */
add_filter( 'language_attributes', function( string $output ): string {
    // Asegurar que el lang tenga el formato Colombia (es-CO) para SEO local
    if ( strpos( $output, 'lang=' ) === false ) {
        $output .= ' lang="es-CO"';
    }
    return $output;
} );

// =============================================================================
// 3. REGISTRO DE PÁGINAS (SHOP)
// =============================================================================

add_action( 'admin_init', 'gano_register_shop_page' );
function gano_register_shop_page() {
    $page_title = 'Ecosistemas';
    $existing = get_posts( [
        'post_type'      => 'page',
        'title'          => $page_title,
        'posts_per_page' => 1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ] );
    $page_check = ! empty( $existing ) ? (object) [ 'ID' => $existing[0] ] : null;
    if ( ! isset( $page_check->ID ) ) {
        wp_insert_post( array(
            'post_type'   => 'page',
            'post_title'  => $page_title,
            'post_content'=> '',
            'post_status' => 'publish',
            'post_author' => 1,
            'meta_input'  => array( '_wp_page_template' => 'templates/shop-premium.php' ),
        ) );
    }
}

// =============================================================================
// 3. ENDPOINT REST DEL CHAT IA — V-05 Fix: con permission_callback de nonce
// =============================================================================

add_action( 'rest_api_init', 'gano_register_chat_rest_routes' );
function gano_register_chat_rest_routes() {

    // Endpoint de log de eventos del chat (apertura, leads capturados)
    register_rest_route( 'gano-agent/v1', '/log', array(
        'methods'             => 'POST',
        'callback'            => 'gano_agent_log_callback',
        'permission_callback' => 'gano_verify_chat_nonce',
    ) );

    // Endpoint de respuesta del chat IA
    register_rest_route( 'gano/v1', '/chat', array(
        'methods'             => 'POST',
        'callback'            => 'gano_chat_response_callback',
        'permission_callback' => 'gano_verify_chat_nonce',
    ) );
}

/**
 * Verificar nonce CSRF + rate limiting del chat — permission_callback compartida.
 * WordPress autentica la sesión del usuario via X-WP-Nonce automáticamente,
 * lo que permite al MU plugin saber que la petición es legítima.
 *
 * Rate limiting: max 20 peticiones por IP cada 60 segundos.
 * Devuelve WP_Error con 429 si se supera el límite (REST API lo serializa correctamente).
 *
 * @return true|WP_Error
 */
function gano_verify_chat_nonce( WP_REST_Request $request ) {
    // — Rate limiting por IP ——————————————————————————————————————————————————
    $ip        = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $transient = 'gano_chat_rate_' . md5( $ip );
    $window    = 60;  // segundos de ventana deslizante
    $max       = 20;  // max peticiones por ventana
    $hits      = (int) get_transient( $transient );

    if ( $hits >= $max ) {
        return new WP_Error(
            'gano_rate_limited',
            'Demasiadas peticiones. Intenta de nuevo en un momento.',
            array( 'status' => 429 )
        );
    }

    // Incrementar contador respetando la ventana existente
    if ( 0 === $hits ) {
        set_transient( $transient, 1, $window );
    } else {
        // get_transient no expone el TTL; set_transient reinicia la ventana.
        // Para ventana deslizante usamos un segundo transient con el tiempo de inicio.
        $window_start = get_transient( $transient . '_start' );
        if ( false === $window_start ) {
            set_transient( $transient . '_start', time(), $window );
        }
        $remaining = $window - ( time() - (int) $window_start );
        if ( $remaining > 0 ) {
            set_transient( $transient, $hits + 1, $remaining );
        } else {
            // Ventana expiró — reiniciar
            set_transient( $transient, 1, $window );
            set_transient( $transient . '_start', time(), $window );
        }
    }

    // — Verificar nonce CSRF ——————————————————————————————————————————————————
    $nonce = $request->get_header( 'X-WP-Nonce' );
    if ( ! wp_verify_nonce( $nonce, 'gano_chat_nonce' ) ) {
        return new WP_Error(
            'gano_invalid_nonce',
            'Nonce inválido o expirado.',
            array( 'status' => 403 )
        );
    }

    return true;
}

/**
 * Callback: registrar eventos del chat (leads, aperturas).
 * Sanitizar todos los inputs antes de loguear.
 */
function gano_agent_log_callback( WP_REST_Request $request ): WP_REST_Response {
    $body    = $request->get_json_params();
    $message = isset( $body['message'] ) ? sanitize_text_field( $body['message'] ) : '';
    $level   = isset( $body['level'] )   ? sanitize_text_field( $body['level'] )   : 'INFO';

    if ( empty( $message ) ) {
        return new WP_REST_Response( array( 'error' => 'Mensaje vacío.' ), 400 );
    }

    // Log con WC Logger si está disponible, si no usar error_log estándar
    if ( function_exists( 'wc_get_logger' ) ) {
        $logger  = wc_get_logger();
        $context = array( 'source' => 'gano-chat' );
        $logger->info( wp_json_encode( array(
            'event'     => $level,
            'message'   => $message,
            'timestamp' => current_time( 'c' ),
            'ip_hash'   => md5( $_SERVER['REMOTE_ADDR'] ?? '' ), // hash, no IP directa
        ) ), $context );
    }

    return new WP_REST_Response( array( 'logged' => true ), 200 );
}

/**
 * Callback: respuesta del chat IA.
 * TODO Fase 4: conectar a n8n / Make / LLM API real en lugar de respuesta estática.
 */
function gano_chat_response_callback( WP_REST_Request $request ): WP_REST_Response {
    $body    = $request->get_json_params();
    $message = isset( $body['message'] ) ? sanitize_text_field( $body['message'] ) : '';

    if ( empty( $message ) ) {
        return new WP_REST_Response( array( 'error' => 'Mensaje vacío.' ), 400 );
    }

    /**
     * ESTRATEGIA: IA DINÁMICA VS ESTÁTICA
     * Si GANO_API_TOKEN está definido en wp-config.php con un valor no vacío,
     * se intenta una llamada a un LLM (modelo: gpt-3.5-turbo o superior).
     * Sin token, cae al fallback estático al final de la función.
     */
    $api_token = defined( 'GANO_API_TOKEN' ) ? (string) GANO_API_TOKEN : '';

    if ( ! empty( $api_token ) ) {
        // --- LLAMADA A LLM (OpenAI API) ---
        $response = wp_remote_post( 'https://api.openai.com/v1/chat/completions', array(
            'timeout' => 15,
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_token,
                'Content-Type'  => 'application/json',
            ),
            'body'    => wp_json_encode( array(
                'model'    => 'gpt-3.5-turbo', // Cambiar por gpt-4o para mayor precisión
                'messages' => array(
                    array(
                        'role'    => 'system',
                        'content' => 'Eres el Agente Gano, un experto técnico en hosting WordPress, arquitecturas NVMe y seguridad Zero-Trust para Gano Digital Colombia. Responde de forma profesional, empática y técnica. Si te preguntan por precios, redirige a /ecosistemas. Siempre en español de Colombia.',
                    ),
                    array(
                        'role'    => 'user',
                        'content' => $message,
                    ),
                ),
            ) ),
        ) );

        if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
            $data = json_decode( wp_remote_retrieve_body( $response ), true );
            $reply = $data['choices'][0]['message']['content'] ?? '';
            if ( ! empty( $reply ) ) {
                return new WP_REST_Response( array( 'reply' => $reply ), 200 );
            }
        }
        // Si hay error en la API pero el token existía, loguear para debug.
        error_log( 'Gano Chat API Error: ' . ( is_wp_error( $response ) ? $response->get_error_message() : wp_remote_retrieve_response_code( $response ) ) );
    }

    // --- FALLBACK: RESPUESTA ESTÁTICA (Si no hay token o la API falla) ---
    $response_map = array(
        'precio'    => 'Nuestros planes van desde $196.000 COP/mes. ¿Quieres ver la comparativa completa? Visita gano.digital/ecosistemas',
        'hosting'   => 'Gano Digital ofrece hosting WordPress de alto rendimiento con NVMe Gen4, seguridad Zero-Trust y soporte 24/7 en español.',
        'seguridad' => 'Todos nuestros planes incluyen WAF, Wordfence, monitoreo de integridad y el MU Plugin de seguridad de Gano.',
        'soporte'   => 'Nuestro equipo está disponible 24/7. Puedes escribirnos a soporte@gano.digital o por WhatsApp.',
        'pse'       => 'Aceptamos PSE, Nequi, Daviplata y tarjetas débito/crédito vía Wompi Colombia.',
        'dominio'   => 'Sí, puedes registrar y transferir dominios directamente desde gano.digital.',
    );

    $reply = 'Gracias por tu mensaje. Un agente de Gano Digital te contactará pronto. ¿Prefieres que te contactemos por WhatsApp o email?';
    $msg_lower = mb_strtolower( $message );
    foreach ( $response_map as $keyword => $answer ) {
        if ( strpos( $msg_lower, $keyword ) !== false ) {
            $reply = $answer;
            break;
        }
    }

    return new WP_REST_Response( array( 'reply' => $reply ), 200 );
}

// =============================================================================
// 4. SHORTCODE [gano_pilares] — Cuatro pilares de la homepage
// Copy: memory/content/homepage-copy-2026-04.md — Sección "Cuatro pilares"
// Uso en Elementor: insertar widget "Código corto" y escribir [gano_pilares]
// =============================================================================

add_shortcode( 'gano_pilares', 'gano_render_pilares' );

/**
 * Renderiza las cuatro tarjetas de pilares de la homepage.
 * Cada tarjeta incluye un icono SVG accesible, título H3 y párrafo de copy.
 *
 * @return string HTML escapado de los cuatro pilares.
 */
function gano_render_pilares(): string {
    $pilares = array(
        array(
            'titulo' => 'NVMe que se nota en Core Web Vitals, no solo en el folleto.',
            'texto'  => 'Almacenamiento de nueva generación y stack optimizado para WordPress: menos espera, más conversión. Tu sitio carga cuando el cliente ya decidió quedarse.',
            'icono'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
            'label'  => 'Icono de velocidad NVMe',
        ),
        array(
            'titulo' => 'WordPress endurecida para el tráfico real de un negocio.',
            'texto'  => 'Hardening continuo, controles de acceso y visibilidad sobre lo que ocurre en tu instalación. Menos superficie de ataque, más tranquilidad operativa.',
            'icono'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
            'label'  => 'Icono de escudo de seguridad WordPress',
        ),
        array(
            'titulo' => 'Confianza cero por defecto: identidad, sesiones y permisos bajo control.',
            'texto'  => 'La seguridad no es un cartel: es política aplicada en capas. Menos suposiciones, más trazabilidad cuando importa.',
            'icono'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
            'label'  => 'Icono de candado Zero-Trust',
        ),
        array(
            'titulo' => 'Contenido más cerca del usuario, sin magia barata.',
            'texto'  => 'Arquitectura pensada para entregar experiencias rápidas donde vive tu audiencia. Menos saltos innecesarios, más respuesta perceptible.',
            'icono'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
            'label'  => 'Icono de red Edge y latencia global',
        ),
    );

    // Permitir solo las etiquetas SVG que usamos en los iconos
    $kses_svg = array(
        'svg'    => array(
            'xmlns'            => true,
            'viewbox'          => true,
            'fill'             => true,
            'stroke'           => true,
            'stroke-width'     => true,
            'stroke-linecap'   => true,
            'stroke-linejoin'  => true,
            'aria-hidden'      => true,
            'focusable'        => true,
        ),
        'path'   => array( 'd' => true ),
        'circle' => array( 'cx' => true, 'cy' => true, 'r' => true ),
        'line'   => array( 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true ),
        'rect'   => array( 'x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true, 'ry' => true ),
    );

    $html = '<div class="gano-pilares-grid">';

    foreach ( $pilares as $pilar ) {
        $html .= '<article class="gano-el-pillar">';
        $html .= '<span class="gano-pillar-icon" role="img" aria-label="' . esc_attr( $pilar['label'] ) . '">';
        $html .= wp_kses( $pilar['icono'], $kses_svg );
        $html .= '</span>';
        $html .= '<h3>' . esc_html( $pilar['titulo'] ) . '</h3>';
        $html .= '<p>' . esc_html( $pilar['texto'] ) . '</p>';
        $html .= '</article>';
    }

    $html .= '</div>';

    return $html;
}

// =============================================================================
// 4b. WHITELIST: AGREGAR ENDPOINTS DEL CHAT A LA LISTA DEL MU PLUGIN
// =============================================================================

// =============================================================================
// 5. SHORTCODE: [gano_cta_icons] — iconos reales del CTA final
// =============================================================================

/**
 * Renderiza la fila de iconos del bloque CTA final con Font Awesome 5/6.
 * Uso: inserta el shortcode [gano_cta_icons] en un widget Shortcode de Elementor
 * sobre el contenedor con clase gano-el-cta-icons o directamente como shortcode.
 *
 * Dependencia de iconos: Font Awesome es cargado por Elementor / Essential Addons.
 */
add_shortcode( 'gano_cta_icons', 'gano_render_cta_icons' );
function gano_render_cta_icons() {
    $items = array(
        array(
            'icon'  => 'fa-bolt',
            'label' => 'Velocidad NVMe',
            'url'   => home_url( '/arquitecturas/' ),
            'title' => 'Ver arquitecturas NVMe',
        ),
        array(
            'icon'  => 'fa-shield-halved',
            'label' => 'Zero-Trust',
            'url'   => home_url( '/seguridad/' ),
            'title' => 'Conocer el hardening de seguridad',
        ),
        array(
            'icon'  => 'fa-circle-check',
            'label' => 'Uptime 99,9 %',
            'url'   => '',
            'title' => '',
        ),
        array(
            'icon'  => 'fa-headset',
            'label' => 'Soporte en español',
            'url'   => home_url( '/contacto/' ),
            'title' => 'Hablar con el equipo de soporte',
        ),
        array(
            'icon'  => 'fa-coins',
            'label' => 'Facturación en COP',
            'url'   => '',
            'title' => '',
        ),
    );

    ob_start();
    echo '<ul class="gano-el-cta-icons">';
    foreach ( $items as $item ) {
        $has_link = ! empty( $item['url'] );
        echo '<li class="gano-el-cta-icons__item">';
        if ( $has_link ) {
            printf(
                '<a href="%s" title="%s" aria-label="%s">',
                esc_url( $item['url'] ),
                esc_attr( $item['title'] ),
                esc_attr( $item['label'] . ': ' . $item['title'] )
            );
        }
        printf(
            '<i class="fa-solid %s" aria-hidden="true"></i>',
            esc_attr( $item['icon'] )
        );
        printf(
            '<span class="gano-el-cta-icons__label">%s</span>',
            esc_html( $item['label'] )
        );
        if ( $has_link ) {
            echo '</a>';
        }
        echo '</li>';
    }
    echo '</ul>';
    return ob_get_clean();
}


/**
 * El MU plugin gano-security.php bloquea la REST API para no autenticados.
 * Los endpoints del chat usan nonce, que WordPress valida como autenticación,
 * por lo que is_user_logged_in() retorna true en esas peticiones.
 * Este filtro es un respaldo explícito en caso de conflicto de prioridades.
 */
add_filter( 'rest_authentication_errors', function ( $result ) {
    if ( ! empty( $result ) ) {
        return $result;
    }
    $request_uri  = $_SERVER['REQUEST_URI'] ?? '';
    $chat_routes  = array(
        '/wp-json/gano-agent/v1/log',
        '/wp-json/gano/v1/chat',
        '/wp-json/gano/v1/csp-report',
    );
    foreach ( $chat_routes as $route ) {
        if ( strpos( $request_uri, $route ) !== false ) {
            return null;
        }
    }
    return $result;
}, 5 );

// =============================================================================
// GANO RESELLER STORE — Constantes de mapeo de productos (pfids)
// =============================================================================
//
// Qué es un pfid: "Product Family ID" del catálogo GoDaddy Reseller.
// Es el identificador único que GoDaddy asigna a cada producto en el RCC.
//
// CÓMO OBTENER LOS pfids REALES:
//   1. Iniciar sesión en el Reseller Control Center (RCC):
//      https://www.godaddy.com/reseller/program/affiliate
//   2. Ir a: Productos → Catálogo de productos.
//   3. En cada producto, el "Product Family ID" es el pfid.
//   4. Alternativa rápida (WP-CLI, después de sincronizar):
//      wp reseller sync
//      wp post list --post_type=reseller_product --fields=ID,post_title
//      wp post meta get <POST_ID> rstore_id
//
// ESTRUCTURA DE URL DEL CARRITO:
//   https://cart.secureserver.net/?plid={PLID}&items=[{"id":"{PFID}","quantity":1}]
//   El PLID se configura en: wp-admin → Ajustes → Reseller Store → Private Label ID.
//
// NOTA: Los dominios (.CO / .COM) NO tienen pfid de carrito directo.
//   Para dominios usar el shortcode [rstore-domain-search].
//
// Los valores se leen desde wp_options (`gano_pfid_*`) gestionadas en
// Ajustes → Gano Reseller (plugin gano-reseller-enhancements). Si no hay option
// guardada, el fallback es 'PENDING_RCC' y `gano_rstore_cart_url()` devuelve '#'.
// Definir la constante en wp-config.php gana sobre la option (útil para staging).
// =============================================================================

// --- Hosting WordPress ---
// Fuente: RCC → Productos → Web Hosting → WordPress Hosting
if ( ! defined( 'GANO_PFID_HOSTING_ECONOMIA' ) ) {
	define( 'GANO_PFID_HOSTING_ECONOMIA', get_option( 'gano_pfid_hosting_economia', 'PENDING_RCC' ) ); // Núcleo Prime
}
if ( ! defined( 'GANO_PFID_HOSTING_DELUXE' ) ) {
	define( 'GANO_PFID_HOSTING_DELUXE', get_option( 'gano_pfid_hosting_deluxe', 'PENDING_RCC' ) );     // Fortaleza Delta
}
if ( ! defined( 'GANO_PFID_HOSTING_PREMIUM' ) ) {
	define( 'GANO_PFID_HOSTING_PREMIUM', get_option( 'gano_pfid_hosting_premium', 'PENDING_RCC' ) );   // Bastión SOTA
}
if ( ! defined( 'GANO_PFID_HOSTING_ULTIMATE' ) ) {
	define( 'GANO_PFID_HOSTING_ULTIMATE', get_option( 'gano_pfid_hosting_ultimate', 'PENDING_RCC' ) ); // Ultimate WP
}

// --- Seguridad / SSL ---
// Fuente: RCC → Productos → SSL & Seguridad
if ( ! defined( 'GANO_PFID_SSL_DELUXE' ) ) {
	define( 'GANO_PFID_SSL_DELUXE', get_option( 'gano_pfid_ssl_deluxe', 'PENDING_RCC' ) );
}
if ( ! defined( 'GANO_PFID_SECURITY_ULTIMATE' ) ) {
	define( 'GANO_PFID_SECURITY_ULTIMATE', get_option( 'gano_pfid_security_ultimate', 'PENDING_RCC' ) );
}

// --- Email / Colaboración ---
// Fuente: RCC → Productos → Email & Office
if ( ! defined( 'GANO_PFID_M365_PREMIUM' ) ) {
	define( 'GANO_PFID_M365_PREMIUM', get_option( 'gano_pfid_m365_premium', 'PENDING_RCC' ) );
}
if ( ! defined( 'GANO_PFID_ONLINE_STORAGE' ) ) {
	define( 'GANO_PFID_ONLINE_STORAGE', get_option( 'gano_pfid_online_storage', 'PENDING_RCC' ) );
}

/**
 * Genera la URL del carrito de GoDaddy Reseller para un pfid dado.
 *
 * Usa el pl_id configurado en el plugin Reseller Store (wp-admin → Ajustes →
 * Reseller Store → Private Label ID). Devuelve '#' cuando el pfid aún no está
 * configurado (valor 'PENDING_RCC') o cuando el plugin no está activo.
 *
 * @param string $pfid     Product Family ID del catálogo GoDaddy Reseller.
 * @param int    $duration Duración en meses (por defecto 12 = 1 año).
 * @return string URL escapada lista para usar en href.
 */
function gano_rstore_cart_url( $pfid, $duration = 12 ) {
	if ( empty( $pfid ) || 'PENDING_RCC' === $pfid ) {
		return '#';
	}

	$pl_id = function_exists( 'rstore_get_option' ) ? (int) rstore_get_option( 'pl_id' ) : 0;

	if ( empty( $pl_id ) ) {
		return '#';
	}

	$items = wp_json_encode(
		array(
			array(
				'id'       => $pfid,
				'quantity' => 1,
				'duration' => absint( $duration ),
			),
		)
	);

	return esc_url(
		add_query_arg(
			array(
				'plid'  => $pl_id,
				'items' => $items,
			),
			'https://cart.secureserver.net/'
		)
	);
}

/**
 * Returns normalized catalog status.
 *
 * @param mixed $raw_status Raw status value.
 * @return string active|pending|coming-soon
 */
function gano_normalize_catalog_status( $raw_status ) {
	$status = is_string( $raw_status ) ? sanitize_key( $raw_status ) : '';
	if ( in_array( $status, array( 'active', 'pending', 'coming-soon' ), true ) ) {
		return $status;
	}
	return 'pending';
}

/**
 * Returns canonical category labels for the premium catalog.
 *
 * @return array<string, string>
 */
function gano_get_reseller_catalog_categories() {
	return array(
		'hostingwebcpanel'      => 'Hosting Web (cPanel)',
		'webhostingplus'        => 'Web Hosting Plus',
		'wordpressadministrado' => 'WordPress Administrado',
		'servidoresvps'         => 'Servidores VPS',
		'vpshighperformance'    => 'VPS High Performance',
		'certificadosssl'       => 'Certificados SSL',
		'correomicrosoft365'    => 'Correo Microsoft 365',
		'seguridadweb'          => 'Seguridad Web',
		'creadordesitiosweb'    => 'Creador de Sitios Web',
		'marketingdigital'      => 'Marketing Digital',
		'dominios'              => 'Dominios',
	);
}

/**
 * Builds VPS catalog URL for the current reseller storefront.
 *
 * @return string
 */
function gano_rstore_vps_catalog_url() {
	$pl_id = function_exists( 'rstore_get_option' ) ? (int) rstore_get_option( 'pl_id' ) : 0;
	if ( $pl_id <= 0 ) {
		$pl_id = 599667;
	}
	return esc_url( add_query_arg( 'pl_id', $pl_id, 'https://www.secureserver.net/hosting/vps' ) );
}

/**
 * Returns the commercial catalog used by premium shop templates.
 *
 * Status conventions:
 * - active: CTA opens Reseller cart
 * - pending: CTA redirects to contacto while PFID/RCC is pending
 * - coming-soon: CTA disabled with "Próximamente"
 *
 * @return array<int, array<string, string>>
 */
function gano_get_reseller_catalog_products() {
	$vps_url = gano_rstore_vps_catalog_url();

	return array(
		array(
			'cat'           => 'hostingwebcpanel',
			'name'          => 'Hosting Deluxe',
			'desc'          => '1 sitio web, SSD ilimitado, 1 base MySQL, SSL gratuito y soporte 24/7.',
			'price'         => '$9.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-server',
			'pfid'          => '459',
			'status'        => 'active',
			'tip'           => 'Mejor si tienes agencia o diseñador propio.',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'hostingwebcpanel',
			'name'          => 'Hosting Ultimate',
			'desc'          => 'Sitios y bases ilimitadas, correo incluido y SSL gratuito.',
			'price'         => '$12.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-server',
			'pfid'          => '459',
			'status'        => 'active',
			'badge'         => 'Popular',
			'tip'           => 'Escala mejor que el cPanel estándar.',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'webhostingplus',
			'name'          => 'WHP Inicio',
			'desc'          => '1 dominio, 10 GB SSD, 10 correos y SSL para pequeñas empresas.',
			'price'         => '$21.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-rocket',
			'pfid'          => '459',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'webhostingplus',
			'name'          => 'WHP Mejora',
			'desc'          => '3 dominios, 50 GB SSD y 25 cuentas de correo para e-commerce.',
			'price'         => '$38.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-rocket',
			'pfid'          => '459',
			'status'        => 'active',
			'badge'         => 'Recomendado',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'webhostingplus',
			'name'          => 'WHP Crecimiento',
			'desc'          => '5 dominios, 100 GB SSD, correos ilimitados y backups diarios.',
			'price'         => '$54.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-rocket',
			'pfid'          => '459',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'webhostingplus',
			'name'          => 'WHP Expansión',
			'desc'          => 'Dominios ilimitados y 200 GB SSD para empresas consolidadas.',
			'price'         => '$76.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-rocket',
			'pfid'          => '459',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'wordpressadministrado',
			'name'          => 'WP Básico',
			'desc'          => '1 sitio WordPress con actualizaciones automáticas y soporte especializado.',
			'price'         => 'Desde $5.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-wordpress',
			'pfid'          => '457',
			'status'        => 'active',
			'tip'           => 'Ideal cuando no tienes equipo técnico interno.',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'wordpressadministrado',
			'name'          => 'WP Pro',
			'desc'          => 'Hasta 3 sitios, staging y backups automáticos con caching avanzado.',
			'price'         => 'Desde $9.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-wordpress',
			'pfid'          => '457',
			'status'        => 'active',
			'badge'         => 'Popular',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'wordpressadministrado',
			'name'          => 'WP Developer',
			'desc'          => 'Sitios ilimitados y panel multi-cliente para agencias.',
			'price'         => 'Desde $14.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-wordpress',
			'pfid'          => '457',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'servidoresvps',
			'name'          => 'VPS 1 vCPU / 1 GB',
			'desc'          => '20 GB SSD, 1 TB de transferencia y acceso root para desarrollo.',
			'price'         => '$4.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-microchip',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'servidoresvps',
			'name'          => 'VPS 2 vCPU / 4 GB',
			'desc'          => '80 GB SSD y 2 TB para aplicaciones de tráfico medio.',
			'price'         => '$25.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-microchip',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'servidoresvps',
			'name'          => 'VPS 4 vCPU / 8 GB',
			'desc'          => '160 GB SSD y 3 TB para tiendas online y apps empresariales.',
			'price'         => '$50.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-microchip',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'badge'         => 'Recomendado',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'servidoresvps',
			'name'          => 'VPS 8 vCPU / 16 GB',
			'desc'          => '320 GB SSD y 4 TB para alto tráfico y múltiples servicios.',
			'price'         => '$87.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-microchip',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'servidoresvps',
			'name'          => 'VPS 16 vCPU / 32 GB',
			'desc'          => '640 GB SSD y 5 TB para cargas críticas empresariales.',
			'price'         => '$146.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-microchip',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'vpshighperformance',
			'name'          => 'HP 2 vCPU / 8 GB',
			'desc'          => 'Infraestructura NVMe para apps sensibles a latencia.',
			'price'         => '$38.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-gauge-high',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'vpshighperformance',
			'name'          => 'HP 4 vCPU / 16 GB',
			'desc'          => 'Para e-commerce de alto tráfico, SaaS y APIs exigentes.',
			'price'         => '$63.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-gauge-high',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'badge'         => 'Popular',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'vpshighperformance',
			'name'          => 'HP 8 vCPU / 32 GB',
			'desc'          => 'Rendimiento enterprise para plataformas de misión crítica.',
			'price'         => '$123.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-gauge-high',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'vpshighperformance',
			'name'          => 'HP 16 vCPU / 64 GB',
			'desc'          => 'Para IA, ML y procesamiento masivo de datos.',
			'price'         => '$187.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-gauge-high',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'vpshighperformance',
			'name'          => 'HP 32 vCPU / 128 GB',
			'desc'          => 'Nivel datacenter para cargas corporativas máximas.',
			'price'         => '$255.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-gauge-high',
			'external_url'  => $vps_url,
			'status'        => 'active',
			'badge'         => 'Premium',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'certificadosssl',
			'name'          => 'SSL DV · 1 sitio',
			'desc'          => 'Candado HTTPS para dominio único, ideal para pymes.',
			'price'         => '$33.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-lock',
			'pfid'          => '75',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'certificadosssl',
			'name'          => 'SSL DV · 5 sitios',
			'desc'          => '5 dominios en un solo certificado y gestión centralizada.',
			'price'         => '$61.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-lock',
			'pfid'          => '75',
			'status'        => 'active',
			'badge'         => 'Popular',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'certificadosssl',
			'name'          => 'SSL DV Comodín',
			'desc'          => 'Protege subdominios ilimitados del dominio principal.',
			'price'         => '$214.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-lock',
			'pfid'          => '75',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'certificadosssl',
			'name'          => 'SSL EV · 1 sitio',
			'desc'          => 'Validación extendida para máxima confianza.',
			'price'         => '$110.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-lock',
			'pfid'          => '75',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'certificadosssl',
			'name'          => 'SSL EV · 5 sitios',
			'desc'          => '5 dominios con validación extendida para grupos empresariales.',
			'price'         => '$262.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-lock',
			'pfid'          => '75',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'certificadosssl',
			'name'          => 'SSL Administrado',
			'desc'          => 'GoDaddy instala, renueva y monitorea el certificado por ti.',
			'price'         => '$144.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-lock',
			'pfid'          => '75',
			'status'        => 'active',
			'badge'         => 'Sin complicaciones',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'correomicrosoft365',
			'name'          => 'Correo Esencial',
			'desc'          => 'Correo con dominio propio y buzón de 50 GB.',
			'price'         => 'Desde $1.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-envelope',
			'pfid'          => '466',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'correomicrosoft365',
			'name'          => 'M365 Business Basic',
			'desc'          => 'Email + Teams + SharePoint + 1 TB OneDrive.',
			'price'         => 'Desde $5.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-envelope',
			'pfid'          => '466',
			'status'        => 'active',
			'badge'         => 'Popular',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'correomicrosoft365',
			'name'          => 'M365 Business Standard',
			'desc'          => 'Suite Office completa + Teams para hasta 300 usuarios.',
			'price'         => 'Desde $12.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-envelope',
			'pfid'          => '466',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'correomicrosoft365',
			'name'          => 'M365 Business Premium',
			'desc'          => 'Incluye Intune, Defender y seguridad avanzada de identidad.',
			'price'         => 'Desde $22.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-envelope',
			'pfid'          => '466',
			'status'        => 'active',
			'badge'         => 'Premium',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'seguridadweb',
			'name'          => 'Seguridad Esencial',
			'desc'          => 'Escaneo diario de malware y alertas para sitios básicos.',
			'price'         => '$3.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-shield-halved',
			'pfid'          => '557',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'seguridadweb',
			'name'          => 'Seguridad Deluxe',
			'desc'          => 'Limpieza garantizada de malware con monitoreo continuo.',
			'price'         => '$7.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-shield-halved',
			'pfid'          => '557',
			'status'        => 'active',
			'badge'         => 'Popular',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'seguridadweb',
			'name'          => 'Seguridad Ultimate',
			'desc'          => 'WAF capa 7, anti-DDoS, reparación prioritaria y CDN.',
			'price'         => '$23.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-shield-halved',
			'pfid'          => '557',
			'status'        => 'active',
			'cta_label'     => 'Agregar al carrito',
		),
		array(
			'cat'           => 'creadordesitiosweb',
			'name'          => 'Constructor Básico',
			'desc'          => 'Plantillas profesionales con editor drag & drop.',
			'price'         => 'Desde $6.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-pen-ruler',
			'status'        => 'coming-soon',
			'badge'         => 'Pendiente PFID',
			'cta_label'     => 'Próximamente',
		),
		array(
			'cat'           => 'creadordesitiosweb',
			'name'          => 'Constructor Comercio',
			'desc'          => 'Tienda online completa con pagos e inventario.',
			'price'         => 'Desde $14.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-pen-ruler',
			'status'        => 'coming-soon',
			'badge'         => 'Pendiente PFID',
			'cta_label'     => 'Próximamente',
		),
		array(
			'cat'           => 'marketingdigital',
			'name'          => 'SEO Básico',
			'desc'          => 'Palabras clave guiadas y optimización on-page.',
			'price'         => 'Desde $6.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-chart-line',
			'status'        => 'coming-soon',
			'badge'         => 'Pendiente PFID',
			'cta_label'     => 'Próximamente',
		),
		array(
			'cat'           => 'marketingdigital',
			'name'          => 'Email Marketing Starter',
			'desc'          => 'Hasta 500 contactos y plantillas para campañas iniciales.',
			'price'         => 'Desde $9.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-chart-line',
			'status'        => 'coming-soon',
			'badge'         => 'Pendiente PFID',
			'cta_label'     => 'Próximamente',
		),
		array(
			'cat'           => 'marketingdigital',
			'name'          => 'Email Marketing Esencial',
			'desc'          => 'Automatizaciones, segmentación y A/B testing.',
			'price'         => 'Desde $19.99/mes',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-chart-line',
			'status'        => 'coming-soon',
			'badge'         => 'Pendiente PFID',
			'cta_label'     => 'Próximamente',
		),
		array(
			'cat'           => 'dominios',
			'name'          => '.com',
			'desc'          => 'El dominio más reconocido del mundo para tu marca.',
			'price'         => '$9.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-globe',
			'pfid'          => 'domain_search',
			'status'        => 'active',
			'badge'         => 'Más buscado',
			'cta_label'     => 'Buscar dominio',
		),
		array(
			'cat'           => 'dominios',
			'name'          => '.co',
			'desc'          => 'La extensión colombiana ideal para posicionamiento local.',
			'price'         => '$19.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-globe',
			'pfid'          => 'domain_search',
			'status'        => 'active',
			'badge'         => 'Colombia',
			'cta_label'     => 'Buscar dominio',
		),
		array(
			'cat'           => 'dominios',
			'name'          => '.net',
			'desc'          => 'Alternativa sólida para proyectos de tecnología y red.',
			'price'         => '$12.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-globe',
			'pfid'          => 'domain_search',
			'status'        => 'active',
			'cta_label'     => 'Buscar dominio',
		),
		array(
			'cat'           => 'dominios',
			'name'          => '.io',
			'desc'          => 'Preferido para startups tecnológicas y productos SaaS.',
			'price'         => '$39.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-globe',
			'pfid'          => 'domain_search',
			'status'        => 'active',
			'cta_label'     => 'Buscar dominio',
		),
		array(
			'cat'           => 'dominios',
			'name'          => '.store',
			'desc'          => 'Extensión ideal para tiendas online y marcas de comercio.',
			'price'         => '$2.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-globe',
			'pfid'          => 'domain_search',
			'status'        => 'active',
			'badge'         => 'Oferta',
			'cta_label'     => 'Buscar dominio',
		),
		array(
			'cat'           => 'dominios',
			'name'          => '.org',
			'desc'          => 'Ideal para organizaciones, fundaciones y proyectos sociales.',
			'price'         => '$9.99/año',
			'price_context' => 'Precio desde',
			'icon'          => 'fa-globe',
			'pfid'          => 'domain_search',
			'status'        => 'active',
			'cta_label'     => 'Buscar dominio',
		),
	);
}

/**
 * Resolves CTA metadata for a commercial catalog item.
 *
 * @param array<string, string> $product Product row from catalog.
 * @return array<string, string> url,label,target,status
 */
function gano_resolver_catalog_cta( $product ) {
	$pfid      = isset( $product['pfid'] ) ? (string) $product['pfid'] : '';
	$status    = gano_normalize_catalog_status( $product['status'] ?? '' );
	$cta_label = isset( $product['cta_label'] ) ? (string) $product['cta_label'] : '';
	$label     = '' !== $cta_label ? $cta_label : 'Adquirir Nodo';

	if ( ! empty( $product['external_url'] ) ) {
		return array(
			'url'    => esc_url( (string) $product['external_url'] ),
			'label'  => $label,
			'target' => 'target="_blank" rel="noopener noreferrer"',
			'status' => 'active',
		);
	}

	if ( 'domain_search' === $pfid ) {
		return array(
			'url'    => esc_url( home_url( '/dominios/' ) ),
			'label'  => '' !== $cta_label ? $cta_label : 'Buscar Dominio',
			'target' => '',
			'status' => 'active',
		);
	}

	if ( 'coming-soon' === $status ) {
		return array(
			'url'    => '#',
			'label'  => '' !== $cta_label ? $cta_label : 'Próximamente',
			'target' => '',
			'status' => 'coming-soon',
		);
	}

	if ( 'pending' === $status || 'PENDING_RCC' === $pfid ) {
		return array(
			'url'    => esc_url( home_url( '/contacto/' ) ),
			'label'  => '' !== $cta_label ? $cta_label : 'Hablar con ventas',
			'target' => '',
			'status' => 'pending',
		);
	}

	$cart_url = gano_rstore_cart_url( $pfid );
	if ( '#' === $cart_url ) {
		return array(
			'url'    => esc_url( home_url( '/contacto/' ) ),
			'label'  => 'Hablar con ventas',
			'target' => '',
			'status' => 'pending',
		);
	}

	return array(
		'url'    => $cart_url,
		'label'  => $label,
		'target' => 'target="_blank" rel="noopener noreferrer"',
		'status' => 'active',
	);
}

/**
 * Returns the SOTA hub page categories (slug => label).
 *
 * @return array<string, string>
 */
function gano_get_sota_categories(): array {
	return [
		'infraestructura'         => 'Infraestructura',
		'seguridad'               => 'Seguridad',
		'inteligencia-artificial' => 'Inteligencia Artificial',
		'rendimiento'             => 'Rendimiento',
		'estrategia'              => 'Estrategia',
	];
}

// =============================================================================
// CD-CONTENT-006 — WOOCOMMERCE MICROCOPY (carrito vacío)
//   Referencia: memory/content/microcopy-wave3-kit.md §4a
// =============================================================================

/**
 * Reemplaza el mensaje de carrito vacío de WooCommerce con copy de marca.
 * Pronombre: tú (contexto marketing). role="status" para lectores de pantalla.
 */
add_filter( 'woocommerce_empty_cart_message', 'gano_child_empty_cart_message' );
function gano_child_empty_cart_message(): string {
    ob_start();
    ?>
    <div class="gano-empty-cart" role="status">
      <p><strong>Tu carrito está vacío</strong></p>
      <p>Aún no has agregado ningún ecosistema a tu pedido.</p>
      <p style="color:#64748b; font-size:.9rem; margin-bottom:1.25rem;">
        ¿No sabes por dónde empezar? Compara los ecosistemas o cuéntanos sobre tu sitio y te orientamos.
      </p>
      <a href="<?php echo esc_url( home_url( '/ecosistemas' ) ); ?>" class="gano-btn">
        Ver arquitecturas y planes
      </a>
      &ensp;
      <a href="<?php echo esc_url( home_url( '/contacto' ) ); ?>"
         style="font-weight:600; color:var(--gano-blue,#2952CC);">
        Hablar con el equipo
      </a>
    </div>
    <?php
    return (string) ob_get_clean();
}

/**
 * Retrieves all SOTA hub pages ordered by menu order then date.
 * Returns published and draft pages so the hub can show "En Preparación" cards.
 *
 * @return WP_Post[]
 */
// =============================================================================
// CD-CONTENT-005 — FAQ SCHEMA (preguntas adicionales aprobadas para homepage)
// =============================================================================

/**
 * Agrega preguntas FAQ adicionales al schema JSON-LD de la homepage.
 *
 * Las preguntas pendientes de datos reales (SLA backups, NIT, SLA soporte)
 * están en memory/content/faq-schema-candidates-wave3.md como candidatos C01–C10.
 * Solo se activan aquí las que no dependen de placeholders.
 *
 * Para agregar más preguntas una vez Diego confirme los datos reales:
 *   $questions[] = array( 'question' => '¿…?', 'answer' => '…' );
 */
add_filter( 'gano_faq_questions', 'gano_child_faq_extra_questions' );
function gano_child_faq_extra_questions( array $questions ): array {

    // C04 — ¿Necesito conocimientos técnicos? (sin placeholders)
    $questions[] = array(
        'question' => '¿Necesito saber programación o administración de servidores para gestionar mi plan?',
        'answer'   => 'No. Los ecosistemas de Gano Digital están diseñados para que puedas operar tu WordPress sin tocar el servidor. El panel de control es visual, la configuración inicial la hacemos contigo y el monitoreo proactivo actúa antes de que notes un problema.',
    );

    // C07 — Escalabilidad: cambiar de ecosistema (sin placeholders)
    $questions[] = array(
        'question' => '¿Puedo cambiar de ecosistema si mi negocio crece?',
        'answer'   => 'Sí. El camino natural es Núcleo Prime → Fortaleza Delta → Bastión SOTA. El cambio de ecosistema se coordina con el equipo de Gano Digital; migramos los recursos sin afectar la disponibilidad de tu sitio. No hay penalización por escalar.',
    );

    // C09 — Diferencia Núcleo Prime vs Bastión SOTA (sin placeholders de precio)
    $questions[] = array(
        'question' => '¿Cuál es la diferencia principal entre el ecosistema Núcleo Prime y Bastión SOTA?',
        'answer'   => 'Núcleo Prime es la base de alto rendimiento: almacenamiento NVMe, WordPress optimizado y seguridad esencial, ideal para negocios en crecimiento. Bastión SOTA es la capa de resiliencia total: redundancia activa, respaldos con mayor frecuencia, soporte prioritario y monitoreo avanzado, pensado para operaciones críticas que no pueden tolerar interrupciones.',
    );

    // C10 — WordPress como plataforma (sin placeholders)
    $questions[] = array(
        'question' => '¿Por qué construir sobre WordPress en lugar de otra plataforma?',
        'answer'   => 'WordPress impulsa más del 43 % de la web mundial, cuenta con el ecosistema de plugins y temas más grande del mercado, y ofrece control total sobre tu contenido y datos. Gano Digital especializa su infraestructura exclusivamente en WordPress para llevar esa plataforma a niveles enterprise: optimizaciones a nivel de servidor, caching inteligente y hardening específico que una solución genérica no puede ofrecer.',
    );

    return $questions;
}

// =============================================================================
// CD-CONTENT-005 — FOOTER LEGAL: shortcode + acción wp_footer
// =============================================================================

/**
 * Shortcode [gano_footer_legal] para usar en Elementor HTML widget o footer template.
 *
 * Uso en Elementor: widget HTML → [gano_footer_legal]
 * Uso en PHP: echo do_shortcode('[gano_footer_legal]');
 *
 * @return string HTML del footer legal.
 */
add_shortcode( 'gano_footer_legal', 'gano_child_footer_legal_shortcode' );
function gano_child_footer_legal_shortcode(): string {
    $legal_links = array(
        array( 'label' => 'Términos y Condiciones', 'url' => '/legal/terminos-y-condiciones' ),
        array( 'label' => 'Política de Privacidad',  'url' => '/legal/politica-de-privacidad'  ),
        array( 'label' => 'SLA',                     'url' => '/legal/acuerdo-de-nivel-de-servicio' ),
        array( 'label' => 'Hosting WordPress Colombia', 'url' => '/hosting-wordpress-colombia' ),
    );

    $year     = gmdate( 'Y' );
    $site_url = get_site_url();

    ob_start();
    ?>
    <div class="gano-footer-legal">
      <nav aria-label="<?php esc_attr_e( 'Menú legal', 'gano-child' ); ?>">
        <ul class="gano-footer-legal__nav">
          <?php foreach ( $legal_links as $link ) : ?>
            <li>
              <a href="<?php echo esc_url( $site_url . $link['url'] ); ?>">
                <?php echo esc_html( $link['label'] ); ?>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </nav>
      <p class="gano-footer-legal__copy">
        &copy; <?php echo esc_html( $year ); ?>
        <a href="<?php echo esc_url( $site_url ); ?>">Gano Digital</a>.
        Hosting WordPress en Colombia — operado sobre infraestructura GoDaddy Reseller.
      </p>
    </div>
    <?php
    return (string) ob_get_clean();
}

function gano_get_sota_hub_pages(): array {
	$args = [
		'post_type'      => 'page',
		'post_status'    => [ 'publish', 'draft' ],
		'posts_per_page' => 100,
		'orderby'        => [ 'menu_order' => 'ASC', 'date' => 'ASC' ],
		'meta_query'     => [
			[
				'key'     => '_gano_sota_category',
				'compare' => 'EXISTS',
			],
		],
		'no_found_rows'  => true,
	];

	$query = new WP_Query( $args );

	return $query->posts;
}

/**
 * Localizar Reseller Store — Domain Search Widget
 * Aplica filtros específicos de rstore para traducir textos a español
 */
add_filter( 'rstore_domain_text_placeholder', function() {
	return esc_html__( 'Encuentra tu dominio perfecto', 'gano-child' );
});

add_filter( 'rstore_domain_text_search', function() {
	return esc_html__( 'Buscar', 'gano-child' );
});

add_filter( 'rstore_domain_text_available', function() {
	return esc_html__( '¡Felicidades! {domain_name} está disponible', 'gano-child' );
});

add_filter( 'rstore_domain_text_not_available', function() {
	return esc_html__( 'Lo sentimos, {domain_name} ya está en uso', 'gano-child' );
});

add_filter( 'rstore_domain_text_cart', function() {
	return esc_html__( 'Continuar al carrito', 'gano-child' );
});

add_filter( 'rstore_text_select', function() {
	return esc_html__( 'Seleccionar', 'gano-child' );
});

add_filter( 'rstore_text_selected', function() {
	return esc_html__( 'Seleccionado', 'gano-child' );
});

/**
 * Localizar Reseller Store — Product Widget
 */
add_filter( 'rstore_product_button_label', function() {
	return esc_html__( 'Añadir al carrito', 'gano-child' );
});

add_filter( 'rstore_product_text_cart', function() {
	return esc_html__( 'Continuar al carrito', 'gano-child' );
});

add_filter( 'rstore_product_text_more', function() {
	return esc_html__( 'Más información', 'gano-child' );
});
