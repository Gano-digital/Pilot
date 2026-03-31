<?php
/**
 * Plugin Name: Gano Digital — Wompi Integration
 * Description: Integración con la pasarela Wompi Colombia (PSE/Tarjetas/Nequi).
 *              V-02/V-03 Fix: Auditoria Gano Digital, Marzo 2026.
 *              — Eliminada clave hardcodeada de sandbox.
 *              — Implementada verificación HMAC-SHA256 en webhooks.
 * Version: 1.1.0
 * Author: Gano Digital Dev
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// 1. REGISTRO DE SETTINGS
// =============================================================================

add_action( 'admin_init', function () {
    register_setting( 'gano_wompi_options', 'gano_wompi_public_key' );
    register_setting( 'gano_wompi_options', 'gano_wompi_private_key' );
    register_setting( 'gano_wompi_options', 'gano_wompi_integrity_secret' );
    register_setting( 'gano_wompi_options', 'gano_wompi_events_key' );
    register_setting( 'gano_wompi_options', 'gano_wompi_mode' ); // 'sandbox' | 'production'
} );

// =============================================================================
// 2. CLASE NÚCLEO WOMPI — sin claves hardcodeadas
// =============================================================================

class Gano_Wompi {

    /**
     * Obtener URL de checkout de Wompi.
     * Las claves se leen SIEMPRE desde wp_options — nunca hay valores default aquí.
     */
    public static function get_checkout_url( $amount_cop, $reference, $email ) {
        $public_key = get_option( 'gano_wompi_public_key', '' );
        $mode       = get_option( 'gano_wompi_mode', 'sandbox' );

        // Sin clave pública configurada: no generar URL de pago.
        if ( empty( $public_key ) ) {
            return false;
        }

        // Wompi siempre usa el mismo endpoint de checkout — la clave define el ambiente.
        $base_url     = 'https://checkout.wompi.co/p/';
        $amount_cents = intval( $amount_cop ) * 100; // centavos de COP

        $params = array(
            'public-key'          => $public_key,
            'currency'            => 'COP',
            'amount-in-cents'     => $amount_cents,
            'reference'           => sanitize_text_field( $reference ),
            'customer-data:email' => sanitize_email( $email ),
            'redirect-url'        => home_url( '/thank-you/' ),
        );

        return $base_url . '?' . http_build_query( $params );
    }

    /**
     * Obtener URL base de la API Wompi según el ambiente configurado.
     */
    public static function get_api_base() {
        $mode = get_option( 'gano_wompi_mode', 'sandbox' );
        return ( $mode === 'production' )
            ? 'https://production.wompi.co/v1'
            : 'https://sandbox.wompi.co/v1';
    }
}

// =============================================================================
// 3. HELPERS DE CIFRADO PARA CLAVE PRIVADA — Fase 2
// =============================================================================

/**
 * Cifra la clave privada de Wompi antes de guardarla en wp_options.
 * Usa AES-256-CBC con IV aleatorio. La clave de cifrado se deriva de wp_salt().
 *
 * REQUIERE: extensión PHP openssl (estándar en todas las instalaciones modernas).
 *
 * @param  string $plaintext  Clave privada en texto plano.
 * @return string             Texto cifrado en base64, o vacío si el input está vacío.
 */
function gano_wompi_encrypt_key( string $plaintext ): string {
    if ( empty( $plaintext ) ) {
        return '';
    }
    if ( ! extension_loaded( 'openssl' ) ) {
        // Si no hay openssl, guardar sin cifrar para no perder la clave.
        return $plaintext;
    }
    $key    = substr( hash( 'sha256', wp_salt( 'auth' ) ), 0, 32 );
    $iv     = openssl_random_pseudo_bytes( 16 );
    $cipher = openssl_encrypt( $plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv );
    if ( false === $cipher ) {
        return $plaintext; // Fallback: no perder el valor si openssl falla
    }
    // Prefijo 'ENC:' identifica valores cifrados y facilita migración futura
    return 'ENC:' . base64_encode( $iv . $cipher );
}

/**
 * Descifra la clave privada de Wompi al leerla de wp_options.
 * Si el valor no tiene el prefijo 'ENC:', se asume texto plano (sin migrar).
 *
 * @param  string $encrypted  Valor almacenado en wp_options.
 * @return string             Clave privada en texto plano.
 */
function gano_wompi_decrypt_key( string $encrypted ): string {
    if ( empty( $encrypted ) ) {
        return '';
    }
    // Compatibilidad con valores sin cifrar (instalaciones previas o sin openssl)
    if ( strpos( $encrypted, 'ENC:' ) !== 0 ) {
        return $encrypted;
    }
    if ( ! extension_loaded( 'openssl' ) ) {
        return ''; // No se puede descifrar — mejor no usar que usar algo incorrecto
    }
    $data    = base64_decode( substr( $encrypted, 4 ) );
    $iv      = substr( $data, 0, 16 );
    $cipher  = substr( $data, 16 );
    $key     = substr( hash( 'sha256', wp_salt( 'auth' ) ), 0, 32 );
    $result  = openssl_decrypt( $cipher, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv );
    return ( false !== $result ) ? $result : '';
}

/**
 * Cifrar automáticamente la clave privada al guardar en wp_options.
 * Aplica a 'gano_wompi_private_key' y 'gano_wompi_events_key'.
 */
add_filter( 'pre_update_option_gano_wompi_private_key', function( $new_value, $old_value ) {
    // Solo cifrar si el valor cambió y no está ya cifrado
    if ( ! empty( $new_value ) && strpos( $new_value, 'ENC:' ) !== 0 ) {
        return gano_wompi_encrypt_key( $new_value );
    }
    return $new_value;
}, 10, 2 );

add_filter( 'pre_update_option_gano_wompi_events_key', function( $new_value, $old_value ) {
    if ( ! empty( $new_value ) && strpos( $new_value, 'ENC:' ) !== 0 ) {
        return gano_wompi_encrypt_key( $new_value );
    }
    return $new_value;
}, 10, 2 );

add_filter( 'pre_update_option_gano_wompi_integrity_secret', function( $new_value, $old_value ) {
    if ( ! empty( $new_value ) && strpos( $new_value, 'ENC:' ) !== 0 ) {
        return gano_wompi_encrypt_key( $new_value );
    }
    return $new_value;
}, 10, 2 );

// =============================================================================
// 3b. REGISTRO DEL GATEWAY EN WOOCOMMERCE
// =============================================================================

add_filter( 'woocommerce_payment_gateways', 'add_gano_wompi_gateway' );
function add_gano_wompi_gateway( $gateways ) {
    if ( class_exists( 'WC_Payment_Gateway' ) ) {
        require_once plugin_dir_path( __FILE__ ) . 'class-gano-wompi-gateway.php';
        $gateways[] = 'WC_Gano_Wompi_Gateway';
    }
    return $gateways;
}

// =============================================================================
// 4. ENDPOINT REST PARA WEBHOOK DE WOMPI — V-03 Fix
// =============================================================================

add_action( 'rest_api_init', 'gano_wompi_register_webhook_route' );
function gano_wompi_register_webhook_route() {
    register_rest_route(
        'gano-wompi/v1',
        '/webhook',
        array(
            'methods'             => 'POST',
            'callback'            => 'gano_wompi_handle_webhook',
            'permission_callback' => '__return_true', // La verificación se hace internamente con HMAC
        )
    );
}

/**
 * Verificar la firma HMAC-SHA256 del webhook de Wompi.
 *
 * Wompi firma cada evento con SHA256 sobre la concatenación dinámica de:
 *   - valores del array "properties" (en el orden que vienen en el payload)
 *   - timestamp del evento
 *   - integrity_secret configurado en el dashboard de Wompi
 *
 * Referencia: https://docs.wompi.co/en/docs/colombia/eventos/
 */
function gano_wompi_verify_signature( string $raw_body, string $received_checksum ): bool {
    $integrity_secret = gano_wompi_decrypt_key( get_option( 'gano_wompi_integrity_secret', '' ) );

    // Sin secret configurado o sin checksum: rechazar.
    if ( empty( $integrity_secret ) || empty( $received_checksum ) ) {
        return false;
    }

    $payload = json_decode( $raw_body, true );
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        return false;
    }

    // Construir el string a hashear dinámicamente según el array 'properties'
    $concat = '';
    if ( isset( $payload['properties'] ) && is_array( $payload['properties'] ) ) {
        foreach ( $payload['properties'] as $prop ) {
            $concat .= $payload['data']['transaction'][ $prop ] ?? '';
        }
    }
    $concat .= $payload['timestamp'] ?? '';
    $concat .= $integrity_secret;

    // hash_equals() es timing-safe (previene timing attacks de comparación)
    return hash_equals( hash( 'sha256', $concat ), $received_checksum );
}

/**
 * Handler principal del webhook Wompi.
 * SIEMPRE verifica la firma antes de cualquier acción sobre órdenes.
 */
function gano_wompi_handle_webhook( WP_REST_Request $request ) {
    $raw_body          = $request->get_body();
    $received_checksum = $request->get_header( 'X-Event-Checksum' );

    // — Paso 1: Verificar firma HMAC. Si falla, detener sin revelar detalles.
    if ( ! gano_wompi_verify_signature( $raw_body, $received_checksum ?? '' ) ) {
        gano_wompi_log( 'WEBHOOK_SIGNATURE_FAILED', array(
            'ip'       => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'checksum' => $received_checksum ? 'present_but_invalid' : 'missing',
        ) );
        return new WP_REST_Response( null, 401 );
    }

    $payload = json_decode( $raw_body, true );
    $event   = $payload['event'] ?? '';

    // — Paso 2: Solo procesar eventos de transacción
    if ( 'transaction.updated' !== $event ) {
        return new WP_REST_Response( array( 'received' => true ), 200 );
    }

    $transaction = $payload['data']['transaction'] ?? array();
    $status      = $transaction['status']    ?? '';
    $reference   = $transaction['reference'] ?? '';
    $tx_id       = $transaction['id']        ?? '';

    if ( empty( $reference ) ) {
        return new WP_REST_Response( null, 400 );
    }

    // — Paso 3: Buscar la orden WooCommerce por referencia
    // La referencia tiene formato 'GD-{order_id}-{timestamp}'
    $order_id = intval( explode( '-', $reference )[1] ?? 0 );
    $order    = wc_get_order( $order_id );

    if ( ! $order ) {
        gano_wompi_log( 'WEBHOOK_ORDER_NOT_FOUND', array( 'reference' => $reference ) );
        return new WP_REST_Response( null, 404 );
    }

    // — Paso 4: Mapear estado Wompi → WooCommerce
    // Mapeo: Wompi APPROVED → WC payment_complete (processing → completed si entrega digital)
    //        Wompi DECLINED/VOIDED/ERROR → WC failed/cancelled
    //        Wompi PENDING/PROCESSING → sin cambio (esperar siguiente evento)
    switch ( $status ) {
        case 'APPROVED':
            if ( ! $order->is_paid() ) {
                $order->payment_complete( $tx_id );
                $order->add_order_note(
                    sprintf( 'Pago aprobado por Wompi. Transacción ID: %s', esc_html( $tx_id ) )
                );
                gano_wompi_log( 'PAYMENT_APPROVED', array( 'order_id' => $order_id, 'tx_id' => $tx_id ) );
            }
            break;

        case 'DECLINED':
            $order->update_status( 'failed', sprintf( 'Pago rechazado por Wompi (TX: %s).', esc_html( $tx_id ) ) );
            gano_wompi_log( 'PAYMENT_DECLINED', array( 'order_id' => $order_id, 'tx_id' => $tx_id ) );
            break;

        case 'VOIDED':
            $order->update_status( 'cancelled', sprintf( 'Pago anulado en Wompi (TX: %s).', esc_html( $tx_id ) ) );
            gano_wompi_log( 'PAYMENT_VOIDED', array( 'order_id' => $order_id, 'tx_id' => $tx_id ) );
            break;

        case 'ERROR':
            $order->update_status( 'failed', sprintf( 'Error reportado por Wompi (TX: %s).', esc_html( $tx_id ) ) );
            gano_wompi_log( 'PAYMENT_ERROR', array( 'order_id' => $order_id, 'tx_id' => $tx_id ) );
            break;

        // PENDING y PROCESSING: PSE puede tardar horas. No cambiar estado, esperar.
        default:
            gano_wompi_log( 'WEBHOOK_STATUS_PENDING', array( 'order_id' => $order_id, 'status' => $status ) );
            break;
    }

    return new WP_REST_Response( array( 'received' => true ), 200 );
}

// =============================================================================
// 5. LOGGER DE TRANSACCIONES
// =============================================================================

/**
 * Registrar eventos de transacciones Wompi en el log de WooCommerce.
 * Logs en: wp-content/uploads/wc-logs/gano-wompi-{fecha}-{hash}.log
 */
function gano_wompi_log( string $event_type, array $data = array() ): void {
    if ( ! function_exists( 'wc_get_logger' ) ) {
        return;
    }
    $logger  = wc_get_logger();
    $context = array( 'source' => 'gano-wompi' );
    $entry   = array_merge( array( 'event' => $event_type, 'timestamp' => current_time( 'c' ) ), $data );
    $logger->info( wp_json_encode( $entry ), $context );
}

// =============================================================================
// 6. AGREGAR ENDPOINT WEBHOOK A LA LISTA BLANCA DEL MU PLUGIN DE SEGURIDAD
// =============================================================================

/**
 * El MU plugin gano-security.php bloquea la REST API para usuarios no autenticados.
 * El webhook de Wompi llega sin autenticación de usuario, por lo que necesita
 * estar en la lista de rutas permitidas. Esto se agrega aquí para mantener cohesión.
 */
add_filter( 'rest_authentication_errors', function ( $result ) {
    if ( ! empty( $result ) ) {
        return $result;
    }
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    if ( strpos( $request_uri, '/wp-json/gano-wompi/v1/webhook' ) !== false ) {
        return null; // Permitir — la verificación HMAC se hace en el handler
    }
    return $result;
}, 5 ); // Prioridad 5 — antes del filtro del MU plugin (que usa prioridad por defecto 10)
