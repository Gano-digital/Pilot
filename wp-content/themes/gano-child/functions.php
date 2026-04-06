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

// =============================================================================
// 1. ENQUEUE DE ESTILOS Y SCRIPTS
// =============================================================================

add_action( 'wp_enqueue_scripts', 'gano_child_enqueue_styles' );
function gano_child_enqueue_styles() {
    // Estilos del tema padre y hijo
    wp_enqueue_style( 'royal-elementor-kit-parent', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'gano-child-style', get_stylesheet_uri(), array( 'royal-elementor-kit-parent' ), wp_get_theme()->get( 'Version' ) );

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

    // GSAP 3 Core & Plugins (Phase 5 - SOTA Animation)
    wp_enqueue_script( 'gsap-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true );
    wp_enqueue_script( 'gsap-scroll-trigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array( 'gsap-js' ), '3.12.5', true );
    wp_enqueue_script( 'gsap-text-plugin', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js', array( 'gsap-js' ), '3.12.5', true );

    // Gano SOTA FX Handler (The "Vivid & Animated" Experience)
    wp_enqueue_script( 'gano-sota-fx', get_stylesheet_directory_uri() . '/js/gano-sota-fx.js', array( 'gsap-scroll-trigger' ), '1.0.0', true );
    wp_enqueue_style( 'gano-sota-animations', get_stylesheet_directory_uri() . '/gano-sota-animations.css', array(), '2.0.0' );

    // Ecosistemas — catálogo de planes (cd-content-002)
    if ( is_page_template( 'templates/page-ecosistemas.php' ) ) {
        wp_enqueue_style( 'gano-ecosistemas-css', get_stylesheet_directory_uri() . '/css/ecosistemas.css', array( 'gano-child-style' ), '1.0.0' );
    }

    // Animaciones de tienda y Quiz de Descubrimiento — solo en templates de tienda
    if ( is_page_template( 'templates/shop-premium.php' ) || is_page_template( 'templates/sota-single-template.php' ) ) {
        wp_enqueue_script( 'gano-shop-animations', get_stylesheet_directory_uri() . '/js/shop-animations.js', array( 'gsap-scroll-trigger' ), '1.1.0', true );
        
        // Ecosystem Discovery Quiz (Phase 4)
        wp_enqueue_style( 'gano-bundle-quiz-css', get_stylesheet_directory_uri() . '/css/gano-bundle-quiz.css', array(), '1.0.0' );
        wp_enqueue_script( 'gano-bundle-quiz-js', get_stylesheet_directory_uri() . '/js/gano-bundle-quiz.js', array(), '1.0.0', true );
    }
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
    $page_check = get_page_by_title( $page_title );
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
     * Si GANO_API_TOKEN está definido en wp-config.php y no es el valor por defecto,
     * se intenta una llamada a un LLM (modelo: gpt-3.5-turbo o superior).
     */
    $api_token = defined( 'GANO_API_TOKEN' ) ? GANO_API_TOKEN : '';

    if ( ! empty( $api_token ) && 'TU_TOKEN_AQUÍ' !== $api_token ) {
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
// TODO: Reemplazar los valores 'PENDING_RCC' con los pfids reales desde el RCC
//       antes de activar el flujo de checkout en producción.
// =============================================================================

// --- Hosting WordPress ---
// Fuente: RCC → Productos → Web Hosting → WordPress Hosting
if ( ! defined( 'GANO_PFID_HOSTING_ECONOMIA' ) ) {
	define( 'GANO_PFID_HOSTING_ECONOMIA', 'PENDING_RCC' ); // WordPress Hosting Economy  — Núcleo Prime
}
if ( ! defined( 'GANO_PFID_HOSTING_DELUXE' ) ) {
	define( 'GANO_PFID_HOSTING_DELUXE', 'PENDING_RCC' );   // WordPress Hosting Deluxe   — Fortaleza Delta
}
if ( ! defined( 'GANO_PFID_HOSTING_PREMIUM' ) ) {
	define( 'GANO_PFID_HOSTING_PREMIUM', 'PENDING_RCC' );  // WordPress Hosting Premium  — Bastión SOTA
}
if ( ! defined( 'GANO_PFID_HOSTING_ULTIMATE' ) ) {
	define( 'GANO_PFID_HOSTING_ULTIMATE', 'PENDING_RCC' ); // WordPress Hosting Ultimate — Ultimate WP
}

// --- Seguridad / SSL ---
// Fuente: RCC → Productos → SSL & Seguridad
if ( ! defined( 'GANO_PFID_SSL_DELUXE' ) ) {
	define( 'GANO_PFID_SSL_DELUXE', 'PENDING_RCC' );        // SSL DV Deluxe              — SSL Deluxe
}
if ( ! defined( 'GANO_PFID_SECURITY_ULTIMATE' ) ) {
	define( 'GANO_PFID_SECURITY_ULTIMATE', 'PENDING_RCC' ); // Website Security Premium   — Security Ultimate
}

// --- Email / Colaboración ---
// Fuente: RCC → Productos → Email & Office
if ( ! defined( 'GANO_PFID_M365_PREMIUM' ) ) {
	define( 'GANO_PFID_M365_PREMIUM', 'PENDING_RCC' );   // Microsoft 365 Business Premium — M365 Premium
}
if ( ! defined( 'GANO_PFID_ONLINE_STORAGE' ) ) {
	define( 'GANO_PFID_ONLINE_STORAGE', 'PENDING_RCC' ); // Online Storage 1 TB            — Online Storage
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
