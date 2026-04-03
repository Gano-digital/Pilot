<?php
/**
 * Gano Digital Child Theme Functions
 * V-05 Fix: Auditoria Gano Digital, Marzo 2026.
 *   — Nonce CSRF agregado al script del chat IA.
 *   — Endpoint REST del chat registrado con permission_callback de nonce.
 *   — Endpoint del chat añadido a lista blanca del MU plugin de seguridad.
 */

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
// 1b. MENÚS — ubicación 'primary' (Elementor / muchos kits); el padre solo registra 'main'
// =============================================================================

add_action( 'after_setup_theme', 'gano_child_register_nav_menus', 20 );
function gano_child_register_nav_menus(): void {
    register_nav_menus(
        array(
            'primary' => esc_html__( 'Menú principal (header / Elementor)', 'gano-child' ),
        )
    );
}

/**
 * Fallback: si 'primary' no tiene menú asignado, copia desde 'main' o usa
 * el primer menú disponible. Cubre instalaciones donde gano-phase3-content
 * fue activado antes de que esta lógica existiera.
 * get_theme_mod() usa el caché de opciones de WP, así que el early-return
 * (caso ya configurado) no implica consulta a la BD.
 */
add_action( 'init', 'gano_child_assign_primary_menu_fallback' );
function gano_child_assign_primary_menu_fallback(): void {
    $locations = get_theme_mod( 'nav_menu_locations', [] );

    if ( ! empty( $locations['primary'] ) ) {
        return; // Ya está configurado — no hacer nada.
    }

    // Copiar desde 'main' si existe.
    if ( ! empty( $locations['main'] ) ) {
        $locations['primary'] = $locations['main'];
        set_theme_mod( 'nav_menu_locations', $locations );
        return;
    }

    // Último recurso: usar el primer menú registrado.
    $menus = wp_get_nav_menus();
    if ( ! empty( $menus ) ) {
        $locations['primary'] = $menus[0]->term_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
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

    // Respuesta dinámica básica — reemplazar con LLM real en Fase 4
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
// 4. WHITELIST: AGREGAR ENDPOINTS DEL CHAT A LA LISTA DEL MU PLUGIN
// =============================================================================

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
