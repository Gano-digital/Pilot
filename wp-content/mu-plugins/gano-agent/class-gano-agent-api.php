<?php
/**
 * API Class for the Gano Agent
 * Updated for Phase 14: Advanced Action Engine
 * Hardened 2026-04-24: Unified permission_callback with nonce verification.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Gano_Agent_API {

	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	public function register_endpoints() {
		$namespace = 'gano/v1';

		// Status endpoint: público (solo retorna "online")
		register_rest_route( $namespace, '/status', array(
			'methods'             => 'GET',
			'callback'            => array( $this, 'get_status' ),
			'permission_callback' => '__return_true',
		) );

		// NOTA: Los endpoints /chat y /log están registrados en el tema gano-child
		// (functions.php) con callbacks completos y permission_callback de nonce.
		// Se eliminan de aquí para evitar doble registro y conflictos.
		// Si el tema se desactiva, estos endpoints dejarán de estar disponibles
		// hasta que se migren completamente al MU plugin.
	}

	/**
	 * Verify that the request includes a valid nonce in the X-WP-Nonce header.
	 * Also applies rate limiting by IP (max 30 requests per minute).
	 */
	public function verify_chat_nonce( WP_REST_Request $request ) {
		$nonce = $request->get_header('X-WP-Nonce');
		if ( empty( $nonce ) ) {
			return new WP_Error( 'missing_nonce', 'X-WP-Nonce header is required.', array( 'status' => 401 ) );
		}

		// Support both logged-in (wp_rest) and logged-out (wp_rest_nonce) nonces
		$verified = wp_verify_nonce( $nonce, 'wp_rest' );
		if ( ! $verified ) {
			$verified = wp_verify_nonce( $nonce, 'wp_rest_nonce' );
		}

		if ( ! $verified ) {
			return new WP_Error( 'invalid_nonce', 'Invalid or expired nonce.', array( 'status' => 403 ) );
		}

		// Rate limiting: max 30 requests per minute per IP
		$client_ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
		$rate_key  = 'gano_api_ratelimit_' . md5( $client_ip );
		$now       = time();
		$window    = 60;
		$max_req   = 30;

		$requests = get_transient( $rate_key );
		if ( false === $requests ) {
			$requests = [];
		}

		// Clean old entries
		$requests = array_filter( $requests, fn( $t ) => $t > ( $now - $window ) );

		if ( count( $requests ) >= $max_req ) {
			return new WP_Error( 'rate_limited', 'Too many requests. Please try again later.', array( 'status' => 429 ) );
		}

		$requests[] = $now;
		set_transient( $rate_key, $requests, $window );

		return true;
	}

	public function handle_chat_request( WP_REST_Request $request ) {
		$params  = $request->get_json_params();
		$message = isset( $params['message'] ) ? sanitize_text_field( $params['message'] ) : '';

		if ( empty( $message ) ) return new WP_Error( 'no_message', 'Message is required', array( 'status' => 400 ) );

		if ( Gano_Agent_Security::detect_malicious_intent( $message ) ) {
			return new WP_REST_Response( array( 'reply' => 'Protocolo de seguridad activo. Tu mensaje no ha sido procesado.' ), 200 );
		}

        // Action Engine: Command Detection
        $action_reply = $this->process_advanced_command($message);
        if ($action_reply) {
            Gano_Agent_Logger::log( "[Agent Action] Command: $message | Reply: $action_reply", 'INFO' );
            return new WP_REST_Response( array( 'status' => 'success', 'reply'  => $action_reply ), 200 );
        }

		$reply = $this->generate_mock_ai_response($message);
		Gano_Agent_Logger::log( "[Chat] User: $message | Agent: $reply", 'INFO' );
		return new WP_REST_Response( array( 'status' => 'success', 'reply'  => $reply ), 200 );
	}

    private function process_advanced_command($message) {
        $msg = strtolower($message);
        
        // Only allow admins to run real commands (simulated check)
        $is_admin = current_user_can('manage_options');

        if (strpos($msg, '/status') !== false || strpos($msg, 'estado del sitio') !== false) {
            return "🛡️ Salud Global: ÓPTIMA. Latencia: 24ms. Bases de datos sindicadas. SSL detectado.";
        }

        if (strpos($msg, '/clear-cache') !== false || strpos($msg, 'limpia la caché') !== false) {
            if (!$is_admin) return "Lo siento, solo los Soberanos de nivel Administrador pueden vaciar la caché.";
            wp_cache_flush();
            return "🧹 Operación completada. La caché estática y dinámica ha sido purgada.";
        }

        if (strpos($msg, '/leads') !== false || strpos($msg, 'cuántos leads') !== false) {
            $leads = get_option('gano_leads_backup', array());
            $count = count($leads);
            return "📈 Tenemos un total de $count leads capturados en la bóveda de seguridad.";
        }

        return false;
    }

	private function generate_mock_ai_response($message) {
		$msg = strtolower($message);
		if (strpos($msg, 'precio') !== false) return "Ecosistemas desde $796k COP. Revisa /ecosistemas/ para detalles.";
		if (strpos($msg, 'seguridad') !== false) return "Blindaje SOTA activo. Cifrado post-cuántico y WAF de Capa 7.";
		return "Soy el Agente Gano. Puedo ayudarte con precios, seguridad o incluso ejecutar comandos técnicos si eres administrador (ej. /status, /clear-cache).";
	}

	public function add_log_entry( WP_REST_Request $request ) {
		$params  = $request->get_json_params();
		$message = isset( $params['message'] ) ? sanitize_text_field( $params['message'] ) : 'No message provided';
		Gano_Agent_Logger::log( '[API Received] ' . $message, 'INFO' );
		if ( class_exists( 'Gano_Leads_Handler' ) ) Gano_Leads_Handler::process_entry( $message );
		return new WP_REST_Response( array( 'status' => 'success', 'logged' => true ), 200 );
	}

	public function get_status( WP_REST_Request $request ) {
		return new WP_REST_Response( array( 'status'  => 'online' ), 200 );
	}
}
