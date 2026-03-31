<?php
/**
 * WC_Gano_Wompi_Gateway — Gateway de pago Wompi para WooCommerce.
 * V-02/V-03 Fix: Auditoria Gano Digital, Marzo 2026.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WC_Gano_Wompi_Gateway extends WC_Payment_Gateway {

    public function __construct() {
        $this->id                 = 'gano_wompi';
        $this->icon               = ''; // TODO: agregar logo SVG de Wompi
        $this->has_fields         = false;
        $this->method_title       = 'Wompi Colombia';
        $this->method_description = 'Acepta pagos con PSE, Nequi, Daviplata y Tarjetas vía Wompi Colombia.';

        $this->init_form_fields();
        $this->init_settings();

        $this->title       = $this->get_option( 'title', 'Pago con PSE / Tarjetas (Wompi)' );
        $this->description = $this->get_option( 'description', 'Serás redirigido a la plataforma segura de Wompi para completar tu pago.' );

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
    }

    /**
     * Campos de configuración del gateway en WooCommerce > Ajustes > Pagos
     */
    public function init_form_fields() {
        $mode = get_option( 'gano_wompi_mode', 'sandbox' );

        $this->form_fields = array(
            'enabled' => array(
                'title'   => 'Habilitar',
                'type'    => 'checkbox',
                'label'   => 'Activar pagos con Wompi Colombia',
                'default' => 'no',
            ),
            'title' => array(
                'title'   => 'Título en checkout',
                'type'    => 'text',
                'default' => 'Pago con PSE / Tarjetas (Wompi)',
            ),
            'description' => array(
                'title'   => 'Descripción en checkout',
                'type'    => 'textarea',
                'default' => 'Serás redirigido a la plataforma segura de Wompi para completar tu pago.',
            ),
            'ambiente_info' => array(
                'title'       => 'Ambiente activo',
                'type'        => 'title',
                'description' => sprintf(
                    'Ambiente: <strong>%s</strong>. Las claves se configuran en <a href="%s">Ajustes Wompi</a>.',
                    esc_html( strtoupper( $mode ) ),
                    admin_url( 'options-general.php?page=gano-wompi' )
                ),
            ),
        );
    }

    /**
     * Procesar el pago: generar referencia única, guardar en meta de orden, redirigir a Wompi.
     */
    public function process_payment( $order_id ) {
        $order = wc_get_order( $order_id );

        if ( ! $order ) {
            wc_add_notice( 'Error al procesar el pedido. Intenta de nuevo.', 'error' );
            return array( 'result' => 'failure' );
        }

        $total     = $order->get_total();
        $email     = $order->get_billing_email();
        // Referencia única: prefijo GD, ID de orden, timestamp para evitar duplicados
        $reference = 'GD-' . $order_id . '-' . time();

        // Guardar la referencia en el meta de la orden para reconciliar con el webhook
        $order->update_meta_data( '_gano_wompi_reference', $reference );
        $order->update_meta_data( '_gano_wompi_status', 'PENDING' );
        $order->save();

        $checkout_url = Gano_Wompi::get_checkout_url( $total, $reference, $email );

        // Si no hay clave pública configurada, mostrar error al cliente
        if ( false === $checkout_url ) {
            wc_add_notice( 'El método de pago no está disponible en este momento. Contacta soporte.', 'error' );
            return array( 'result' => 'failure' );
        }

        // Marcar la orden como pendiente (esperando confirmación de Wompi)
        $order->update_status( 'pending', 'Orden creada. Esperando confirmación de pago en Wompi.' );

        return array(
            'result'   => 'success',
            'redirect' => $checkout_url,
        );
    }
}
