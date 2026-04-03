<?php
/**
 * Gano_Reseller_Sandbox
 *
 * Gestiona el modo sandbox/staging del carrito GoDaddy Reseller.
 * Cuando está activo, redirige todas las llamadas al TLD de pruebas
 * (test-godaddy.com) en lugar del TLD de producción (secureserver.net),
 * permitiendo smoke tests sin datos de pago reales.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gano_Reseller_Sandbox {

	/**
	 * TLD de producción del carrito GoDaddy.
	 *
	 * @var string
	 */
	const TLD_PROD = 'secureserver.net';

	/**
	 * TLD del entorno de pruebas (OTE) de GoDaddy.
	 *
	 * @var string
	 */
	const TLD_SANDBOX = 'test-godaddy.com';

	/**
	 * Nombre de la opción en la BD de WordPress.
	 *
	 * @var string
	 */
	const OPTION_KEY = 'gano_reseller_sandbox';

	/**
	 * Inicializa hooks.
	 */
	public static function init() {
		add_action( 'admin_init', array( __CLASS__, 'register_setting' ) );

		if ( self::is_enabled() ) {
			add_filter( 'rstore_api_tld', array( __CLASS__, 'override_tld' ) );
		}
	}

	/**
	 * Registra el ajuste en la API de Settings de WordPress.
	 */
	public static function register_setting() {
		register_setting(
			'gano_reseller_options',
			self::OPTION_KEY,
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'rest_sanitize_boolean',
				'default'           => false,
			)
		);
	}

	/**
	 * Indica si el modo sandbox está habilitado.
	 *
	 * Soporta tanto la constante PHP (para CI/staging) como la opción de BD.
	 *
	 * @return bool
	 */
	public static function is_enabled() {
		if ( defined( 'GANO_RESELLER_SANDBOX' ) && GANO_RESELLER_SANDBOX ) {
			return true;
		}
		return (bool) get_option( self::OPTION_KEY, false );
	}

	/**
	 * Reemplaza el TLD de producción por el de sandbox vía el filtro rstore_api_tld.
	 *
	 * @param  string $tld TLD actual.
	 * @return string
	 */
	public static function override_tld( $tld ) {
		return self::TLD_SANDBOX;
	}

	/**
	 * Devuelve la URL base del carrito según el entorno activo.
	 *
	 * @return string
	 */
	public static function get_cart_base_url() {
		$tld = self::is_enabled() ? self::TLD_SANDBOX : self::TLD_PROD;
		return sprintf( 'https://cart.%s/', $tld );
	}
}

Gano_Reseller_Sandbox::init();
