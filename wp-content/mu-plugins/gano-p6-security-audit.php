<?php
/**
 * Plugin Name: Gano Digital — Phase 6 Security Audit
 * Description: Motor de auditoría para el blindaje del flujo de pagos y e-commerce.
 * Version: 1.0.0
 * Author: Gano Digital Dev
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Gano_P6_Audit {

    public static function run_audit() {
        $results = [
            'status' => 'success',
            'timestamp' => current_time('mysql'),
            'checks' => []
        ];

        // Checks are performed here
        $results['checks'][] = self::check_api_keys();
        $results['checks'][] = self::check_ssl();
        $results['checks'][] = self::check_csp_headers();
        $results['checks'][] = self::check_wc_security();

        // Determine overall status
        foreach ($results['checks'] as $check) {
            if ($check['level'] === 'CRITICAL') {
                $results['status'] = 'danger';
            } elseif ($check['level'] === 'WARNING' && $results['status'] !== 'danger') {
                $results['status'] = 'warning';
            }
        }

        return $results;
    }

    private static function check_api_keys() {
        $key = get_option('gano_wompi_private_key');
        $is_sandbox = get_option('gano_wompi_mode') === 'sandbox';
        
        if (!$key) {
            return ['name' => 'Wompi API Keys', 'status' => 'Missing', 'level' => 'WARNING', 'msg' => 'No se detectó llave privada instalada.'];
        }
        
        if (strpos($key, 'prv_test_') !== false && !$is_sandbox) {
            return ['name' => 'Wompi API Keys', 'status' => 'Mismatched', 'level' => 'CRITICAL', 'msg' => 'Llave de TEST detectada en modo PRODUCCIÓN.'];
        }

        return ['name' => 'Wompi API Keys', 'status' => 'OK', 'level' => 'SAFE', 'msg' => 'Configuración de llaves correcta.'];
    }

    private static function check_ssl() {
        $is_ssl = is_ssl();
        return [
            'name' => 'SSL Enforcement',
            'status' => $is_ssl ? 'Active' : 'Missing',
            'level' => $is_ssl ? 'SAFE' : 'CRITICAL',
            'msg' => $is_ssl ? 'Conexión segura detectada.' : '¡ALERTA! El sitio no usa SSL. Los pagos Wompi fallarán.'
        ];
    }

    private static function check_csp_headers() {
        $headers = headers_list();
        $csp_found = false;
        foreach ($headers as $h) {
            if (stripos($h, 'Content-Security-Policy') !== false) {
                $csp_found = true;
                break;
            }
        }
        
        return [
            'name' => 'Content Security Policy',
            'status' => $csp_found ? 'Detected' : 'Missing',
            'level' => $csp_found ? 'SAFE' : 'WARNING',
            'msg' => $csp_found ? 'Cabeceras CSP activas.' : 'CSP no detectada en headers dinámicos. Revisar gano-security mu-plugin.'
        ];
    }

    private static function check_wc_security() {
        $is_https_checkout = get_option('woocommerce_force_ssl_checkout') === 'yes' || is_ssl();
        return [
            'name' => 'WooCommerce Checkout Security',
            'status' => $is_https_checkout ? 'Locked' : 'Open',
            'level' => $is_https_checkout ? 'SAFE' : 'CRITICAL',
            'msg' => $is_https_checkout ? 'Checkout forzado a HTTPS.' : 'Checkout vulnerable a interceptación (No HTTPS).'
        ];
    }
}

// REST API Endpoint for Gano Agent to trigger audit
add_action('rest_api_init', function () {
    register_rest_route('gano/v1', '/audit-p6', array(
        'methods' => 'GET',
        'callback' => function() {
            // Key resolution priority: wp-config constant > wp_options > none.
            // Sin key configurada, el endpoint responde 503 (no 403) para indicar
            // "servicio no configurado" vs "credencial inválida".
            $expected_key = defined( 'GANO_AGENT_API_KEY' ) ? (string) GANO_AGENT_API_KEY : (string) get_option( 'gano_agent_api_key', '' );
            if ( '' === $expected_key ) {
                return new WP_Error( 'service_unavailable', 'Auditoría no configurada: define GANO_AGENT_API_KEY en wp-config.php.', array( 'status' => 503 ) );
            }
            $agent_key = isset( $_SERVER['HTTP_X_GANO_AGENT_KEY'] ) ? (string) $_SERVER['HTTP_X_GANO_AGENT_KEY'] : '';
            if ( ! hash_equals( $expected_key, $agent_key ) ) {
                return new WP_Error( 'forbidden', 'Unauthorized', array( 'status' => 403 ) );
            }
            return Gano_P6_Audit::run_audit();
        },
        'permission_callback' => '__return_true'
    ));
});

// Admin notice: alerta si la key del agente de auditoría no está configurada.
add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    $has_constant = defined( 'GANO_AGENT_API_KEY' ) && '' !== (string) GANO_AGENT_API_KEY;
    $has_option   = '' !== (string) get_option( 'gano_agent_api_key', '' );
    if ( $has_constant || $has_option ) {
        return;
    }
    echo '<div class="notice notice-warning"><p><strong>Gano P6 Audit:</strong> <code>GANO_AGENT_API_KEY</code> no está definida en <code>wp-config.php</code> — el endpoint <code>/gano/v1/audit-p6</code> responderá 503 hasta que se configure.</p></div>';
});
