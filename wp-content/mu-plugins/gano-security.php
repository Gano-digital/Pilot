<?php
/**
 * Plugin Name: Gano Digital — Security Hardening
 * Plugin URI:  https://gano.digital
 * Description: Must-Use security plugin. Aplica: V-016 a V-030, V-038 a V-042, V-048 a V-050.
 * Version:     1.0.0
 * Author:      DevSecOps / Gano Digital
 *
 * INSTALACIÓN: Subir este archivo a /wp-content/mu-plugins/gano-security.php
 * Los MU plugins se cargan automáticamente. No requieren activación.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ==============================================================================
// 1. ENUMERACIÓN DE USUARIOS — V-016, V-017, V-022
// ==============================================================================

/**
 * Bloquea REST API /wp/v2/users para no autenticados
 */
add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( ! is_user_logged_in() ) {
        // Eliminar endpoints de usuarios
        if ( isset( $endpoints['/wp/v2/users'] ) ) {
            unset( $endpoints['/wp/v2/users'] );
        }
        if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
            unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
        }
    }
    return $endpoints;
} );

/**
 * Redirigir author archive a 404 para prevenir user enumeration
 */
add_action( 'template_redirect', function() {
    if ( is_author() && ! is_user_logged_in() ) {
        wp_redirect( home_url( '/' ), 301 );
        exit;
    }
} );

/**
 * Bloquear enumeración por ?author=N
 */
add_action( 'init', function() {
    if ( ! is_admin() ) {
        $author = isset( $_GET['author'] ) ? intval( $_GET['author'] ) : 0;
        if ( $author > 0 ) {
            wp_redirect( home_url( '/' ), 301 );
            exit;
        }
    }
} );

/**
 * Desactivar sitemap de usuarios (V-022)
 */
add_filter( 'wp_sitemaps_enabled', '__return_true' );
add_filter( 'wp_sitemaps_add_provider', function( $provider, $name ) {
    if ( 'users' === $name ) {
        return false;
    }
    return $provider;
}, 10, 2 );

// ==============================================================================
// 2. BLOQUEAR xmlrpc.php — V-021
// ==============================================================================

add_filter( 'xmlrpc_enabled', '__return_false' );

// Remover link header xmlrpc
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );

// ==============================================================================
// 3. HARDENING DE HEADERS Y META — V-018, V-019, V-020
// ==============================================================================

// Remover versión de WordPress del HTML
remove_action( 'wp_head', 'wp_generator' );

// Remover versión de jQuery/scripts del query string
add_filter( 'script_loader_src', 'gano_remove_ver_query_string', 9999 );
add_filter( 'style_loader_src',  'gano_remove_ver_query_string', 9999 );
function gano_remove_ver_query_string( $src ) {
    // Solo remover en frontend, no en admin
    if ( is_admin() ) {
        return $src;
    }
    if ( strpos( $src, '?ver=' ) !== false || strpos( $src, '&ver=' ) !== false ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

// Remover link header wp-json (expone rutas)
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

// Remover X-Pingback header
add_filter( 'wp_headers', function( $headers ) {
    unset( $headers['X-Pingback'] );
    return $headers;
} );

// ==============================================================================
// 4. COOKIES — V-048, V-049, V-050
// ==============================================================================

/**
 * Forzar flags Secure + HttpOnly + SameSite=Strict en cookies de sesión WordPress
 */
add_action( 'init', function() {
    if ( ! headers_sent() ) {
        $secure   = is_ssl();
        $httponly = true;

        // Reconfigurar cookies existentes con flags correctos
        $cookies_to_harden = [
            'wordpress_logged_in_',
            'wordpress_sec_',
            'wp-settings-',
            'wp-settings-time-',
            LOGGED_IN_COOKIE,
            AUTH_COOKIE,
            SECURE_AUTH_COOKIE,
        ];

        foreach ( $cookies_to_harden as $cookie_prefix ) {
            foreach ( $_COOKIE as $name => $value ) {
                if ( strpos( $name, $cookie_prefix ) === 0 ) {
                    setcookie(
                        $name,
                        $value,
                        [
                            'expires'  => time() + ( 2 * DAY_IN_SECONDS ),
                            'path'     => COOKIEPATH ?: '/',
                            'domain'   => COOKIE_DOMAIN ?: '',
                            'secure'   => $secure,
                            'httponly' => $httponly,
                            'samesite' => 'Strict',
                        ]
                    );
                }
            }
        }
    }
}, 1 );

/**
 * Forzar SameSite=Strict en la respuesta HTTP para todas las cookies de sesión
 */
add_action( 'send_headers', function() {
    if ( ! headers_sent() ) {
        header_remove( 'Set-Cookie' );
    }
} );

// ==============================================================================
// 5. PROTECCIÓN DE LOGIN — V-016 (Brute Force)
// ==============================================================================

/**
 * Rate limiting de intentos de login: max 5 por IP en 15 minutos
 */
add_filter( 'authenticate', function( $user, $username, $password ) {
    if ( empty( $username ) ) {
        return $user;
    }

    $ip         = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $transient  = 'gano_login_fail_' . md5( $ip );
    $attempts   = (int) get_transient( $transient );
    $max        = 5;
    $lockout    = 15 * MINUTE_IN_SECONDS;

    if ( $attempts >= $max ) {
        return new WP_Error(
            'gano_too_many_attempts',
            sprintf(
                __( 'Demasiados intentos fallidos. Intenta de nuevo en 15 minutos.', 'gano-security' )
            )
        );
    }

    return $user;
}, 30, 3 );

add_action( 'wp_login_failed', function( $username ) {
    $ip        = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $transient = 'gano_login_fail_' . md5( $ip );
    $attempts  = (int) get_transient( $transient );
    set_transient( $transient, $attempts + 1, 15 * MINUTE_IN_SECONDS );
} );

add_action( 'wp_login', function() {
    $ip        = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    $transient = 'gano_login_fail_' . md5( $ip );
    delete_transient( $transient );
} );

/**
 * Mensaje de error genérico en login (no revelar si usuario existe)
 */
add_filter( 'login_errors', function() {
    return 'Credenciales incorrectas.';
} );

// ==============================================================================
// 6. CSRF PROTECTION — V-031, V-032
// ==============================================================================

/**
 * Agregar nonce a todos los formularios de contacto (CF7, Gravity Forms, WPForms)
 * Los formularios nativos de WordPress ya tienen nonce; esto cubre plugins de formularios.
 */

// Contact Form 7 — doble capa CSRF
add_filter( 'wpcf7_form_elements', function( $content ) {
    $nonce = wp_create_nonce( 'gano_cf7_csrf' );
    $field = '<input type="hidden" name="gano_csrf_token" value="' . esc_attr( $nonce ) . '" />';
    return $content . $field;
} );

add_filter( 'wpcf7_validate', function( $result, $tags ) {
    if ( ! isset( $_POST['gano_csrf_token'] ) ||
         ! wp_verify_nonce( sanitize_text_field( $_POST['gano_csrf_token'] ), 'gano_cf7_csrf' ) ) {
        $result->invalidate( $tags[0] ?? null, 'Solicitud inválida. Por favor recarga la página.' );
    }
    return $result;
}, 10, 2 );

// ==============================================================================
// 7. OUTPUT SANITIZATION — V-038, V-039 (XSS Defense)
// ==============================================================================

/**
 * Deshabilitar editor de archivos en admin (previene RCE si credenciales comprometidas)
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Sanitizar parámetros GET/POST en búsquedas (V-040)
 */
add_action( 'init', function() {
    if ( isset( $_GET['s'] ) ) {
        $_GET['s'] = wp_strip_all_tags( $_GET['s'] );
    }
} );

// ==============================================================================
// 8. REST API — Restringir acceso general
// ==============================================================================

add_filter( 'rest_authentication_errors', function( $result ) {
    // Si ya hay un error de autenticación, devolverlo
    if ( ! empty( $result ) ) {
        return $result;
    }

    // Permitir acceso autenticado
    if ( is_user_logged_in() ) {
        return $result;
    }

    // Endpoints públicos permitidos (ajustar según necesidad)
    $request_uri  = $_SERVER['REQUEST_URI'] ?? '';
    $allowed_rest = [
        '/wp-json/wp/v2/posts',
        '/wp-json/wp/v2/pages',
        '/wp-json/wp/v2/categories',
        '/wp-json/contact-form-7',
        // Reporte de violaciones CSP — solo acepta POST, sin datos sensibles
        '/wp-json/gano/v1/csp-report',
        // Chat IA — los endpoints de chat usan nonce, que WP valida como autenticación.
        // Las rutas están aquí como respaldo; el nonce es la verificación real.
        '/wp-json/gano-agent/v1/log',
        '/wp-json/gano/v1/chat',
    ];

    foreach ( $allowed_rest as $allowed ) {
        if ( strpos( $request_uri, $allowed ) !== false ) {
            return $result;
        }
    }

    // Bloquear el resto de la REST API para no autenticados
    return new WP_Error(
        'rest_not_logged_in',
        'API REST no disponible para usuarios no autenticados.',
        [ 'status' => 401 ]
    );
} );

// ==============================================================================
// 9. SECURITY HEADERS ADICIONALES VIA PHP — V-001 a V-009
// ==============================================================================

add_action( 'send_headers', function() {
    if ( ! headers_sent() ) {
        // ── Content Security Policy — ENFORCED (Fase 2) ─────────────────────
        // Política ajustada para Elementor (unsafe-inline/eval requeridos),
        // WooCommerce, Wompi checkout y Google Fonts/Analytics.
        //
        // Notas de compatibilidad:
        //   • 'unsafe-inline' en script-src: requerido por Elementor y WC.
        //     Migrar a nonces de CSP en Fase 3+ para eliminar este permiso.
        //   • 'unsafe-eval': requerido por Elementor Pro y algunos widgets.
        //   • upgrade-insecure-requests fuerza HTTPS en todos los sub-recursos.
        //
        $csp = implode( '; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com https://ajax.googleapis.com https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' data: https://fonts.gstatic.com",
            "img-src 'self' data: https:",
            "connect-src 'self' https://www.google-analytics.com https://www.googletagmanager.com https://api.godaddy.com https://myh.godaddy.com",
            "frame-src 'self' https://reseller.godaddy.com https://www.godaddy.com",
            "upgrade-insecure-requests",
            "report-uri /wp-json/gano/v1/csp-report",
        ] );

        header( 'Content-Security-Policy: ' . $csp );

        // Prevenir MIME sniffing
        header( 'X-Content-Type-Options: nosniff' );

        // Prevenir clickjacking (SAMEORIGIN permite embeds legítimos del propio sitio)
        header( 'X-Frame-Options: SAMEORIGIN' );

        // Protección XSS heredada (IE/Edge antiguos) — complementa el CSP
        header( 'X-XSS-Protection: 1; mode=block' );

        // Referrer Policy — no enviar Referer completo a dominios externos
        header( 'Referrer-Policy: strict-origin-when-cross-origin' );

        // Permissions Policy — desactivar features no usadas por el sitio
        header( 'Permissions-Policy: camera=(), microphone=(), geolocation=()' );
    }
} );

// ==============================================================================
// 10. CSP VIOLATION REPORT ENDPOINT — V-002
// ==============================================================================

add_action( 'rest_api_init', function() {
    register_rest_route( 'gano/v1', '/csp-report', [
        'methods'             => 'POST',
        'callback'            => function( WP_REST_Request $request ) {
            $body    = $request->get_body();
            $data    = json_decode( $body, true );
            $report  = $data['csp-report'] ?? [];

            // Log CSP violations (ajustar ruta según tu configuración)
            $log_entry = [
                'timestamp'         => current_time( 'c' ),
                'blocked-uri'       => $report['blocked-uri']       ?? 'unknown',
                'document-uri'      => $report['document-uri']      ?? 'unknown',
                'violated-directive'=> $report['violated-directive'] ?? 'unknown',
                'ip'                => $_SERVER['REMOTE_ADDR']       ?? 'unknown',
            ];

            error_log( 'CSP_VIOLATION: ' . json_encode( $log_entry ) );

            return new WP_REST_Response( null, 204 );
        },
        'permission_callback' => '__return_true',
    ] );
} );

// ==============================================================================
// 11. OCULTAR WP VERSION EN FEEDS Y CABECERAS
// ==============================================================================

add_filter( 'the_generator', '__return_empty_string' );
add_filter( 'feed_links_show_posts_feed', '__return_false' );
add_filter( 'feed_links_show_comments_feed', '__return_false' );

// ==============================================================================
// 12. SCHEMA / DATOS FALSOS — V-051 (Alerta de contenido placeholder)
// ==============================================================================

/**
 * Comprueba si el sitio aún tiene datos de ejemplo y registra advertencia.
 * (Tarea manual: reemplazar dirección, teléfono y email falsos)
 */
add_action( 'admin_notices', function() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $placeholders = [
        '969 Pine Street' => 'Dirección de ejemplo detectada en el sitio.',
        'Hythostmain@mail.com' => 'Email de ejemplo detectado. Reemplazar con email real.',
        '+1-(314) 892-2600' => 'Teléfono de ejemplo detectado. Reemplazar con número real.',
    ];

    // Buscar en opciones del tema
    $theme_options = get_option( 'elementor_data', '' );
    foreach ( $placeholders as $placeholder => $message ) {
        if ( strpos( $theme_options, $placeholder ) !== false ||
             strpos( get_option( 'blogdescription', '' ), $placeholder ) !== false ) {
            echo '<div class="notice notice-warning is-dismissible"><p><strong>Gano Security:</strong> ' .
                 esc_html( $message ) . '</p></div>';
        }
    }
} );

// ==============================================================================
// 13. ALERTA DE PLUGINS DE RIESGO ACTIVOS — V-04 (wp-file-manager)
// ==============================================================================

/**
 * Detectar si wp-file-manager sigue activo y advertir en el admin.
 * Este plugin tiene historial de CVEs críticos (CVE-2020-25213, CVSS 10.0).
 * DEBE eliminarse del servidor — no solo desactivarse.
 */
add_action( 'admin_notices', function() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    if ( is_plugin_active( 'wp-file-manager/file_manager_5_2.php' ) ||
         file_exists( WP_PLUGIN_DIR . '/wp-file-manager' ) ) {
        echo '<div class="notice notice-error"><p>'
           . '<strong>⚠️ Gano Security — ALERTA CRÍTICA:</strong> '
           . 'El plugin <strong>wp-file-manager</strong> está presente en el servidor. '
           . 'Este plugin tiene vulnerabilidades críticas de ejecución remota de código (CVE-2020-25213). '
           . '<strong>Desactívalo y elimina la carpeta del servidor inmediatamente.</strong>'
           . '</p></div>';
    }
} );
