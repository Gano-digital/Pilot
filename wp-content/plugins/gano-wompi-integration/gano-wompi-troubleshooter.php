<?php
/**
 * Wompi Troubleshooting Tool
 * Permite sincronizar manualmente el estado de una transacción si el webhook falló.
 *
 * V-CSRF Fix: Auditoria Gano Digital, Fase 2, Marzo 2026.
 *   — Formulario protegido con wp_nonce_field() + check_admin_referer().
 *   — Todo output escapado con esc_html() / esc_attr().
 *   — Usa claves correctas (gano_wompi_private_key) y las descifra con gano_wompi_decrypt_key().
 *   — URL de API resuelto según gano_wompi_mode (sandbox / production).
 *   — Acceso restringido a manage_options (administradores).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'admin_menu', 'gano_wompi_troubleshooter_menu' );

function gano_wompi_troubleshooter_menu() {
    add_submenu_page(
        'woocommerce',
        'Gano Wompi Troubleshooter',
        'Wompi Reset/Sync',
        'manage_options',
        'gano-wompi-troubleshooter',
        'gano_wompi_troubleshooter_page'
    );
}

function gano_wompi_troubleshooter_page() {
    // Verificar capacidad (doble check además de add_submenu_page)
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'No tienes permisos para acceder a esta página.' );
    }

    $result_html = '';

    // ── Procesar formulario si se envió ───────────────────────────────────────
    if ( isset( $_POST['sync_wompi'] ) ) {
        // CSRF: verificar nonce — aborta si es inválido
        check_admin_referer( 'gano_wompi_sync_action', 'gano_wompi_sync_nonce' );

        $wompi_id = sanitize_text_field( $_POST['wompi_id'] ?? '' );

        if ( empty( $wompi_id ) ) {
            $result_html = '<div class="notice notice-error"><p>Debes ingresar un ID de transacción.</p></div>';
        } else {
            // Obtener ambiente y descifrar clave privada
            $mode    = get_option( 'gano_wompi_mode', 'sandbox' );
            $enc_key = get_option( 'gano_wompi_private_key', '' );
            $key     = function_exists( 'gano_wompi_decrypt_key' )
                ? gano_wompi_decrypt_key( $enc_key )
                : $enc_key; // Fallback si la función no está cargada

            if ( empty( $key ) ) {
                $result_html = '<div class="notice notice-error"><p>'
                    . 'La clave privada de Wompi no está configurada. '
                    . 'Ve a <a href="' . esc_url( admin_url( 'options-general.php?page=gano-wompi' ) ) . '">Ajustes Wompi</a> y configura la clave.'
                    . '</p></div>';
            } else {
                // URL correcta según ambiente
                $api_base = ( 'production' === $mode )
                    ? 'https://production.wompi.co/v1'
                    : 'https://sandbox.wompi.co/v1';

                $url = trailingslashit( $api_base ) . 'transactions/' . rawurlencode( $wompi_id );

                $response = wp_remote_get( $url, array(
                    'headers' => array( 'Authorization' => 'Bearer ' . $key ),
                    'timeout' => 15,
                ) );

                if ( is_wp_error( $response ) ) {
                    $result_html = '<div class="notice notice-error"><p>'
                        . 'Error de conexión con Wompi: '
                        . esc_html( $response->get_error_message() )
                        . '</p></div>';
                } else {
                    $body = wp_remote_retrieve_body( $response );
                    $data = json_decode( $body, true );

                    if ( isset( $data['data']['status'] ) ) {
                        $status    = $data['data']['status'];
                        $reference = $data['data']['reference'] ?? '';

                        // La referencia tiene formato 'GD-{order_id}-{timestamp}'
                        $parts    = explode( '-', $reference );
                        $order_id = isset( $parts[1] ) ? intval( $parts[1] ) : 0;
                        $order    = $order_id ? wc_get_order( $order_id ) : null;

                        if ( $order ) {
                            if ( 'APPROVED' === $status ) {
                                if ( ! $order->is_paid() ) {
                                    $order->payment_complete( $wompi_id );
                                    $order->add_order_note(
                                        sprintf(
                                            'Pago sincronizado manualmente desde Troubleshooter. TX Wompi: %s',
                                            esc_html( $wompi_id )
                                        )
                                    );
                                    $result_html = '<div class="notice notice-success"><p>'
                                        . '¡Éxito! Pedido <strong>#' . esc_html( (string) $order_id ) . '</strong> '
                                        . 'marcado como PAGADO. TX: ' . esc_html( $wompi_id )
                                        . '</p></div>';
                                } else {
                                    $result_html = '<div class="notice notice-info"><p>'
                                        . 'El pedido <strong>#' . esc_html( (string) $order_id ) . '</strong> '
                                        . 'ya estaba marcado como pagado. No se hicieron cambios.'
                                        . '</p></div>';
                                }
                            } else {
                                $result_html = '<div class="notice notice-warning"><p>'
                                    . 'Wompi reporta estado: <strong>' . esc_html( $status ) . '</strong>. '
                                    . 'Solo se sincronizan transacciones APPROVED. No se hicieron cambios.'
                                    . '</p></div>';
                            }
                        } else {
                            $result_html = '<div class="notice notice-error"><p>'
                                . 'No se encontró el pedido asociado a la referencia: '
                                . esc_html( $reference )
                                . '</p></div>';
                        }
                    } else {
                        $result_html = '<div class="notice notice-error"><p>'
                            . 'ID de transacción no válido o respuesta inesperada de Wompi. '
                            . 'Verifica el ID e intenta de nuevo.'
                            . '</p></div>';
                    }
                }
            }
        }
    }

    // ── Renderizar página ─────────────────────────────────────────────────────
    $mode = get_option( 'gano_wompi_mode', 'sandbox' );
    ?>
    <div class="wrap">
        <h1>🛠️ Gano Wompi Troubleshooter</h1>
        <p>Usa esta herramienta si un pago fue exitoso en Wompi pero el pedido sigue
            <em>"Pendiente"</em> en WooCommerce porque el webhook no llegó o falló.</p>
        <p><strong>Ambiente activo:</strong> <code><?php echo esc_html( strtoupper( $mode ) ); ?></code></p>

        <?php echo $result_html; // Ya escapado arriba ?>

        <form method="post" style="background:#fff;padding:20px;border-radius:8px;box-shadow:0 2px 5px rgba(0,0,0,.1);max-width:500px;">
            <?php
            // CSRF: Nonce de WordPress — check_admin_referer() lo verifica al enviar
            wp_nonce_field( 'gano_wompi_sync_action', 'gano_wompi_sync_nonce' );
            ?>
            <label for="wompi_id"><strong>ID de Transacción Wompi</strong> (ej. <code>12345-abcde-6789</code>):</label><br>
            <input
                type="text"
                id="wompi_id"
                name="wompi_id"
                placeholder="12345-abcde-6789"
                style="width:100%;margin:8px 0 16px;"
                pattern="[a-zA-Z0-9\-]+"
                title="Solo letras, números y guiones"
                required
            ><br>
            <input type="submit" name="sync_wompi" class="button button-primary" value="Sincronizar Estado">
        </form>

        <hr>
        <p style="color:#666;font-size:0.85rem;">
            ⚠️ Esta herramienta realiza una petición directa a la API de Wompi usando la clave privada configurada.
            Úsala solo cuando el webhook automático haya fallado y necesites recuperar un pedido manualmente.
        </p>
    </div>
    <?php
}
