<?php
/**
 * Lead Magnet Capture — REST Endpoint
 * Captura email para lead magnet (nativo WordPress)
 *
 * @package gano-child
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registra endpoint REST /gano/v1/lead-capture
 */
add_action( 'rest_api_init', 'gano_register_lead_capture_endpoint' );
function gano_register_lead_capture_endpoint(): void {
	register_rest_route(
		'gano/v1',
		'/lead-capture',
		array(
			'methods'             => 'POST',
			'callback'            => 'gano_handle_lead_capture',
			'permission_callback' => '__return_true', // Público, nonce verificado en callback
		)
	);
}

/**
 * Callback del endpoint — valida nonce, rate limit, email y guarda
 */
function gano_handle_lead_capture( WP_REST_Request $request ): WP_REST_Response {
	// Verificar nonce
	$nonce = $request->get_param( 'nonce' );
	if ( ! $nonce || ! wp_verify_nonce( sanitize_text_field( $nonce ), 'gano_lead_capture' ) ) {
		return new WP_REST_Response(
			array( 'error' => 'Nonce inválido' ),
			403
		);
	}

	// Rate limit por IP
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0.0.0.0';
	$rl_key = 'gano_lead_rl_' . md5( $ip );
	if ( get_transient( $rl_key ) ) {
		return new WP_REST_Response(
			array( 'error' => 'Espera antes de enviar otro lead' ),
			429
		);
	}
	set_transient( $rl_key, 1, 60 ); // 1 minuto entre intentos

	// Obtener y sanitizar datos
	$email = sanitize_email( (string) $request->get_param( 'email' ) );
	$plan  = sanitize_text_field( (string) $request->get_param( 'plan' ) ) ?: 'general';

	// Validar email
	if ( ! is_email( $email ) ) {
		return new WP_REST_Response(
			array( 'error' => 'Email inválido' ),
			400
		);
	}

	// Guardar en wp_options bajo clave gano_leads
	$leads = (array) get_option( 'gano_leads', array() );
	$lead_entry = array(
		'email' => $email,
		'plan'  => $plan,
		'time'  => current_time( 'mysql' ),
		'ip'    => $ip,
	);
	$leads[] = $lead_entry;
	update_option( 'gano_leads', $leads );

	// Enviar notificación email a admin
	$subject = '[Gano Digital — Lead Magnet] ' . $email;
	$body    = sprintf(
		"Email: %s\nPlan: %s\nFecha: %s\nIP: %s\n",
		$email,
		$plan,
		$lead_entry['time'],
		$ip
	);
	wp_mail(
		apply_filters( 'gano_lead_mail_to', 'hola@gano.digital' ),
		$subject,
		$body,
		array( 'Content-Type: text/plain; charset=UTF-8' )
	);

	return new WP_REST_Response(
		array(
			'success' => true,
			'message' => 'Lead capturado exitosamente',
			'email'   => $email,
		),
		200
	);
}

/**
 * Nonce para el formulario lead capture
 * Llamar desde front-page.php o shortcode
 */
function gano_lead_capture_nonce(): string {
	return wp_create_nonce( 'gano_lead_capture' );
}
