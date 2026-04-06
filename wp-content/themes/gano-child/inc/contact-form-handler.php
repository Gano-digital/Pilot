<?php
/**
 * Formulario de contacto nativo (page-contacto.php) → admin-post.php.
 *
 * @package gano-child
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Destinatario del correo. Sobreescribir con:
 *   add_filter( 'gano_contacto_mail_to', fn() => 'otro@ejemplo.com' );
 */
function gano_contacto_default_mail_to(): string {
	$email = apply_filters( 'gano_contacto_mail_to', 'hola@gano.digital' );
	return is_email( $email ) ? $email : get_option( 'admin_email', 'hola@gano.digital' );
}

/**
 * Maneja POST del formulario de contacto (usuarios logueados y no).
 */
function gano_handle_contacto_form(): void {
	if ( ! isset( $_POST['gano_contacto_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['gano_contacto_nonce'] ) ), 'gano_contacto_form' ) ) {
		wp_safe_redirect( home_url( '/contacto/?contacto_error=nonce' ) );
		exit;
	}

	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '0';
	$rl_key = 'gano_contacto_rl_' . md5( $ip );
	if ( get_transient( $rl_key ) ) {
		wp_safe_redirect( home_url( '/contacto/?contacto_error=rate' ) );
		exit;
	}
	set_transient( $rl_key, 1, 60 );

	$nombre  = isset( $_POST['nombre'] ) ? sanitize_text_field( wp_unslash( $_POST['nombre'] ) ) : '';
	$empresa = isset( $_POST['empresa'] ) ? sanitize_text_field( wp_unslash( $_POST['empresa'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$asunto  = isset( $_POST['asunto'] ) ? sanitize_text_field( wp_unslash( $_POST['asunto'] ) ) : '';
	$mensaje = isset( $_POST['mensaje'] ) ? sanitize_textarea_field( wp_unslash( $_POST['mensaje'] ) ) : '';

	if ( '' === $nombre || ! is_email( $email ) || '' === $asunto || '' === $mensaje ) {
		wp_safe_redirect( home_url( '/contacto/?contacto_error=fields' ) );
		exit;
	}

	$to      = gano_contacto_default_mail_to();
	$subject = sprintf( '[Gano Digital — Contacto] %s (%s)', $asunto, $nombre );
	$body    = sprintf(
		"Nombre: %s\nEmpresa: %s\nEmail: %s\nAsunto: %s\n\nMensaje:\n%s\n",
		$nombre,
		$empresa ? $empresa : '—',
		$email,
		$asunto,
		$mensaje
	);

	$headers = array(
		'Content-Type: text/plain; charset=UTF-8',
		'Reply-To: ' . $email,
	);

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( ! $sent ) {
		wp_safe_redirect( home_url( '/contacto/?contacto_error=mail' ) );
		exit;
	}

	wp_safe_redirect( home_url( '/contacto/gracias/' ) );
	exit;
}

add_action( 'admin_post_gano_contacto_submit', 'gano_handle_contacto_form' );
add_action( 'admin_post_nopriv_gano_contacto_submit', 'gano_handle_contacto_form' );

/**
 * Mensajes de error en la página de contacto (?contacto_error=).
 * Llamar desde templates/page-contacto.php tras el título del formulario.
 */
function gano_contacto_print_error_notice(): void {
	if ( ! isset( $_GET['contacto_error'] ) ) {
		return;
	}
	$code = sanitize_text_field( wp_unslash( $_GET['contacto_error'] ) );
	$map  = array(
		'nonce'  => 'La sesión de seguridad expiró. Intenta enviar el formulario de nuevo.',
		'rate'   => 'Espera un momento antes de enviar otro mensaje.',
		'fields' => 'Revisa que nombre, correo, asunto y mensaje estén completos.',
		'mail'   => 'No pudimos enviar el mensaje en este momento. Escríbenos a hola@gano.digital.',
	);
	if ( ! isset( $map[ $code ] ) ) {
		return;
	}
	echo '<div class="gano-form-notice gano-form-notice--error" role="alert"><p>' . esc_html( $map[ $code ] ) . '</p></div>';
}
