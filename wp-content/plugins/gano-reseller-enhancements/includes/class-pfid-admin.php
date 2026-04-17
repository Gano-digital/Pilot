<?php
/**
 * Gano_Reseller_PFID_Admin
 *
 * Panel de configuración (Ajustes → Gano Reseller) para pegar los 8 PFIDs del
 * catálogo GoDaddy Reseller sin editar functions.php.
 *
 * Cada campo escribe en wp_options con prefijo `gano_pfid_*`. Las constantes
 * `GANO_PFID_*` en functions.php leen estas options con fallback a 'PENDING_RCC',
 * así que el cambio es backwards-compatible.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gano_Reseller_PFID_Admin {

	const OPTION_GROUP = 'gano_reseller_pfids';
	const PAGE_SLUG    = 'gano-reseller-pfid';
	const CAPABILITY   = 'manage_options';

	/**
	 * Mapa option_key => [label, section, placeholder].
	 * Las secciones agrupan campos en el form.
	 */
	private static function fields() {
		return array(
			'gano_pfid_hosting_economia'  => array( 'WordPress Hosting Economy (Núcleo Prime)',    'hosting', 'ej. 123456' ),
			'gano_pfid_hosting_deluxe'    => array( 'WordPress Hosting Deluxe (Fortaleza Delta)',  'hosting', 'ej. 123457' ),
			'gano_pfid_hosting_premium'   => array( 'WordPress Hosting Premium (Bastión SOTA)',    'hosting', 'ej. 123458' ),
			'gano_pfid_hosting_ultimate'  => array( 'WordPress Hosting Ultimate (Ultimate WP)',    'hosting', 'ej. 123459' ),
			'gano_pfid_ssl_deluxe'        => array( 'SSL DV Deluxe',                               'security', 'ej. 234567' ),
			'gano_pfid_security_ultimate' => array( 'Website Security Premium',                    'security', 'ej. 234568' ),
			'gano_pfid_m365_premium'      => array( 'Microsoft 365 Business Premium',              'email',    'ej. 345678' ),
			'gano_pfid_online_storage'    => array( 'Online Storage 1 TB',                         'email',    'ej. 345679' ),
		);
	}

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	public static function register_menu() {
		add_options_page(
			'Gano Reseller — PFID',
			'Gano Reseller',
			self::CAPABILITY,
			self::PAGE_SLUG,
			array( __CLASS__, 'render_page' )
		);
	}

	public static function register_settings() {
		foreach ( self::fields() as $key => $meta ) {
			register_setting(
				self::OPTION_GROUP,
				$key,
				array(
					'type'              => 'string',
					'sanitize_callback' => array( __CLASS__, 'sanitize_pfid' ),
					'default'           => 'PENDING_RCC',
				)
			);
		}
	}

	/**
	 * Un PFID válido es 'PENDING_RCC' (placeholder) o 3-10 dígitos.
	 * Rechaza cualquier otro formato devolviendo el valor previo.
	 */
	public static function sanitize_pfid( $value ) {
		$value = trim( (string) $value );
		if ( '' === $value ) {
			return 'PENDING_RCC';
		}
		if ( 'PENDING_RCC' === $value ) {
			return 'PENDING_RCC';
		}
		if ( preg_match( '/^\d{3,10}$/', $value ) ) {
			return $value;
		}
		add_settings_error(
			self::OPTION_GROUP,
			'invalid_pfid',
			sprintf( 'PFID inválido: "%s". Debe ser numérico (3-10 dígitos) o "PENDING_RCC".', esc_html( $value ) ),
			'error'
		);
		return 'PENDING_RCC';
	}

	/**
	 * Cuenta cuántos PFIDs ya están configurados (no PENDING_RCC).
	 */
	private static function configured_count() {
		$count = 0;
		foreach ( array_keys( self::fields() ) as $key ) {
			$v = get_option( $key, 'PENDING_RCC' );
			if ( 'PENDING_RCC' !== $v && '' !== $v ) {
				$count++;
			}
		}
		return $count;
	}

	public static function render_page() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( 'Acceso denegado.' );
		}

		$configured = self::configured_count();
		$total      = count( self::fields() );
		$sections   = array(
			'hosting'  => 'Hosting WordPress',
			'security' => 'Seguridad y SSL',
			'email'    => 'Email y Almacenamiento',
		);
		?>
		<div class="wrap">
			<h1>Gano Reseller — Configuración de PFID</h1>

			<?php if ( $configured === $total ) : ?>
				<div class="notice notice-success">
					<p><strong>✓ Checkout listo.</strong> Los <?php echo (int) $total; ?> PFIDs están configurados. Verifica un CTA en <code>/ecosistemas</code> para confirmar que el carrito abre correctamente.</p>
				</div>
			<?php elseif ( $configured > 0 ) : ?>
				<div class="notice notice-warning">
					<p><strong><?php echo (int) $configured; ?> / <?php echo (int) $total; ?> PFIDs configurados.</strong> Los CTAs sin PFID redirigen a <code>/contacto</code> en lugar del carrito.</p>
				</div>
			<?php else : ?>
				<div class="notice notice-info">
					<p>Ningún PFID configurado todavía. Consulta <code>memory/commerce/rcc-pfid-checklist.md</code> en el repo para saber de dónde extraerlos en el RCC de GoDaddy.</p>
				</div>
			<?php endif; ?>

			<p>Pega aquí los <strong>Private Family ID</strong> (PFID) numéricos que obtienes en <em>GoDaddy Reseller Control Center → Products → Product Catalog</em>. Cada valor sobreescribe la constante <code>GANO_PFID_*</code> usada por el tema para construir el URL del carrito GoDaddy.</p>
			<p><em>Formato aceptado: 3–10 dígitos, o <code>PENDING_RCC</code> para dejarlo sin configurar.</em></p>

			<form method="post" action="options.php">
				<?php settings_fields( self::OPTION_GROUP ); ?>

				<?php foreach ( $sections as $section_key => $section_label ) : ?>
					<h2><?php echo esc_html( $section_label ); ?></h2>
					<table class="form-table" role="presentation">
						<tbody>
						<?php foreach ( self::fields() as $key => $meta ) :
							list( $label, $section, $placeholder ) = $meta;
							if ( $section !== $section_key ) {
								continue;
							}
							$value = get_option( $key, 'PENDING_RCC' );
							$is_configured = ( 'PENDING_RCC' !== $value && '' !== $value );
							?>
							<tr>
								<th scope="row">
									<label for="<?php echo esc_attr( $key ); ?>">
										<?php echo esc_html( $label ); ?>
									</label>
								</th>
								<td>
									<input type="text"
										id="<?php echo esc_attr( $key ); ?>"
										name="<?php echo esc_attr( $key ); ?>"
										value="<?php echo esc_attr( $value ); ?>"
										placeholder="<?php echo esc_attr( $placeholder ); ?>"
										class="regular-text"
										pattern="^(PENDING_RCC|\d{3,10})$"
										autocomplete="off" />
									<?php if ( $is_configured ) : ?>
										<span style="color:#00a32a;margin-left:8px;">✓ configurado</span>
									<?php endif; ?>
									<p class="description">Constante: <code><?php echo esc_html( strtoupper( $key ) ); ?></code></p>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				<?php endforeach; ?>

				<?php submit_button( 'Guardar PFIDs' ); ?>
			</form>

			<hr />
			<h2>Referencias</h2>
			<ul>
				<li>Guía de extracción desde RCC: <code>memory/commerce/rcc-pfid-checklist.md</code></li>
				<li>Runbook de activación de plugins: <code>memory/ops/runbook-activacion-wp-admin-2026-04-16.md</code></li>
				<li>Private Label ID del carrito: <a href="<?php echo esc_url( admin_url( 'options-general.php?page=reseller-store' ) ); ?>">Ajustes → Reseller Store</a></li>
			</ul>
		</div>
		<?php
	}
}

Gano_Reseller_PFID_Admin::init();
