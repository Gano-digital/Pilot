<?php
/**
 * Gano_Reseller_Smoke_Test
 *
 * Página de smoke test en wp-admin para verificar el flujo CTA → carrito
 * marca blanca del reseller de GoDaddy, sin usar datos de pago reales.
 *
 * Acceso: wp-admin > Herramientas > Smoke Test Reseller
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gano_Reseller_Smoke_Test {

	/**
	 * Registra el submenú en Herramientas.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_menu' ) );
		add_action( 'admin_post_gano_reseller_smoke_test', array( __CLASS__, 'handle_form' ) );
	}

	/**
	 * Agrega la página bajo "Herramientas".
	 */
	public static function add_menu() {
		add_management_page(
			__( 'Smoke Test Reseller', 'gano-reseller' ),
			__( 'Smoke Test Reseller', 'gano-reseller' ),
			'manage_options',
			'gano-reseller-smoke-test',
			array( __CLASS__, 'render_page' )
		);
	}

	/**
	 * Procesa el formulario de ajuste sandbox y redirige con resultado.
	 */
	public static function handle_form() {
		check_admin_referer( 'gano_reseller_smoke_test_nonce', '_wpnonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Acceso denegado.', 'gano-reseller' ) );
		}

		$sandbox = isset( $_POST['gano_reseller_sandbox'] ) ? '1' : '';
		update_option( Gano_Reseller_Sandbox::OPTION_KEY, (bool) $sandbox );

		wp_safe_redirect(
			add_query_arg(
				array( 'page' => 'gano-reseller-smoke-test', 'updated' => '1' ),
				admin_url( 'tools.php' )
			)
		);
		exit;
	}

	/**
	 * Ejecuta todas las comprobaciones y devuelve un array de resultados.
	 *
	 * @return array[] Cada elemento: ['label' => string, 'ok' => bool, 'detail' => string]
	 */
	private static function run_checks() {
		$results = array();

		// ── 1. pl_id configurado ──────────────────────────────────────────────
		$pl_id = function_exists( 'rstore_get_option' ) ? rstore_get_option( 'pl_id' ) : '';
		$results[] = array(
			'label'  => __( 'pl_id (Reseller Private Label ID) configurado', 'gano-reseller' ),
			'ok'     => ! empty( $pl_id ),
			'detail' => ! empty( $pl_id )
				? sprintf( __( 'ID detectado: %s', 'gano-reseller' ), esc_html( $pl_id ) )
				: __( 'pl_id vacío. Configúralo en Ajustes del plugin Reseller Store.', 'gano-reseller' ),
		);

		// ── 2. Plugin Reseller Store activo ───────────────────────────────────
		$rstore_active = function_exists( 'rstore' ) || class_exists( 'Reseller_Store\\Plugin' );
		$results[] = array(
			'label'  => __( 'Plugin GoDaddy Reseller Store activo', 'gano-reseller' ),
			'ok'     => $rstore_active,
			'detail' => $rstore_active
				? __( 'Plugin detectado correctamente.', 'gano-reseller' )
				: __( 'Instala y activa el plugin "Reseller Store" de GoDaddy.', 'gano-reseller' ),
		);

		// ── 3. Modo sandbox ───────────────────────────────────────────────────
		$sandbox = Gano_Reseller_Sandbox::is_enabled();
		$results[] = array(
			'label'  => __( 'Modo sandbox/staging', 'gano-reseller' ),
			'ok'     => true, // informativo, no es fallo
			'detail' => $sandbox
				? sprintf(
					/* translators: URL del carrito sandbox */
					__( 'ACTIVO — carrito apunta a <code>%s</code>', 'gano-reseller' ),
					esc_html( Gano_Reseller_Sandbox::get_cart_base_url() )
				)
				: sprintf(
					/* translators: URL del carrito de producción */
					__( 'DESACTIVADO — carrito apunta a <code>%s</code> (producción)', 'gano-reseller' ),
					esc_html( Gano_Reseller_Sandbox::get_cart_base_url() )
				),
		);

		// ── 4. URL del carrito con pl_id ──────────────────────────────────────
		$cart_url = '';
		if ( ! empty( $pl_id ) ) {
			$cart_base = Gano_Reseller_Sandbox::get_cart_base_url();
			$cart_url  = add_query_arg(
				array(
					'plid' => $pl_id,
					'isc'  => 'smoke_test',
				),
				trailingslashit( $cart_base ) . 'go/checkout/'
			);
		}
		$results[] = array(
			'label'  => __( 'URL de checkout construida correctamente', 'gano-reseller' ),
			'ok'     => ! empty( $cart_url ),
			'detail' => ! empty( $cart_url )
				? sprintf( '<a href="%1$s" target="_blank" rel="noopener noreferrer">%1$s</a>', esc_url( $cart_url ) )
				: __( 'No se puede construir la URL sin pl_id.', 'gano-reseller' ),
		);

		// ── 5. PFIDs configurados ────────────────────────────────────────────
		$pfid_fields = array(
			'gano_pfid_hosting_economia'  => 'WordPress Hosting Economy (Núcleo Prime)',
			'gano_pfid_hosting_deluxe'    => 'WordPress Hosting Deluxe (Fortaleza Delta)',
			'gano_pfid_hosting_premium'   => 'WordPress Hosting Premium (Bastión SOTA)',
			'gano_pfid_hosting_ultimate'  => 'WordPress Hosting Ultimate (Ultimate WP)',
			'gano_pfid_ssl_deluxe'        => 'SSL DV Deluxe',
			'gano_pfid_security_ultimate' => 'Website Security Premium',
			'gano_pfid_m365_premium'      => 'Microsoft 365 Business Premium',
			'gano_pfid_online_storage'    => 'Online Storage 1 TB',
		);
		$pfid_configured = 0;
		$pfid_detail_rows = '';
		foreach ( $pfid_fields as $opt_key => $pfid_label ) {
			$pfid_val = get_option( $opt_key, 'PENDING_RCC' );
			$is_set   = ( 'PENDING_RCC' !== $pfid_val && '' !== $pfid_val );
			if ( $is_set ) {
				$pfid_configured++;
			}
			$status_icon      = $is_set ? '✅' : '⏳';
			$pfid_detail_rows .= sprintf(
				'<tr><td>%s</td><td>%s</td><td><code>%s</code></td></tr>',
				esc_html( $status_icon ),
				esc_html( $pfid_label ),
				esc_html( $is_set ? $pfid_val : 'PENDING_RCC' )
			);
		}
		$pfid_total  = count( $pfid_fields );
		$pfid_all_ok = ( $pfid_configured === $pfid_total );
		$pfid_table  = sprintf(
			'<table class="widefat" style="margin-top:4px;"><tbody>%s</tbody></table>',
			$pfid_detail_rows
		);
		$results[] = array(
			'label'  => sprintf(
				/* translators: 1: configured count, 2: total count */
				__( 'PFIDs configurados en Ajustes → Gano Reseller (%1$d / %2$d)', 'gano-reseller' ),
				$pfid_configured,
				$pfid_total
			),
			'ok'     => $pfid_all_ok,
			'detail' => $pfid_all_ok
				? __( 'Todos los PFIDs están listos. Ejecuta un CTA real en /ecosistemas.', 'gano-reseller' ) . $pfid_table
				: sprintf(
					/* translators: link to PFID settings page */
					__( 'Faltan %1$d PFID(s). Ve a <a href="%2$s">Ajustes → Gano Reseller</a> para completarlos.', 'gano-reseller' ),
					$pfid_total - $pfid_configured,
					esc_url( admin_url( 'options-general.php?page=gano-reseller-pfid' ) )
				) . $pfid_table,
		);

		// ── 6. Bundles SKU → URL ──────────────────────────────────────────────
		$bundle_skus = array( 'GANO-STARTER-3YR', 'GANO-PRO-3YR', 'GANO-ENTERPRISE-3YR' );
		foreach ( $bundle_skus as $sku ) {
			$bundle_url = add_query_arg(
				array( 'gano_add_bundle' => $sku ),
				home_url( '/' )
			);
			$results[] = array(
				'label'  => sprintf( __( 'Bundle SKU %s — URL de activación', 'gano-reseller' ), esc_html( $sku ) ),
				'ok'     => true,
				'detail' => sprintf(
					'<a href="%1$s" target="_blank" rel="noopener noreferrer">%1$s</a>',
					esc_url( $bundle_url )
				),
			);
		}

		// ── 7. Shortcode [rstore_product] registrado ──────────────────────────
		$sc_ok = shortcode_exists( 'rstore_product' );
		$results[] = array(
			'label'  => __( 'Shortcode [rstore_product] registrado', 'gano-reseller' ),
			'ok'     => $sc_ok,
			'detail' => $sc_ok
				? __( 'Disponible para usar en páginas y widgets.', 'gano-reseller' )
				: __( 'Shortcode no encontrado. Activa el plugin Reseller Store.', 'gano-reseller' ),
		);

		return $results;
	}

	/**
	 * Renderiza la página de smoke test.
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$sandbox  = Gano_Reseller_Sandbox::is_enabled();
		$results  = self::run_checks();
		$pass_all = array_reduce( $results, function( $carry, $item ) {
			return $carry && $item['ok'];
		}, true );

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Smoke Test — Checkout Reseller', 'gano-reseller' ); ?></h1>
			<p><?php esc_html_e( 'Verifica el flujo CTA → carrito marca blanca sin usar datos de pago reales.', 'gano-reseller' ); ?></p>

			<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
				<div class="notice notice-success is-dismissible">
					<p><?php esc_html_e( 'Ajuste guardado.', 'gano-reseller' ); ?></p>
				</div>
			<?php endif; ?>

			<!-- Ajuste de modo sandbox -->
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="gano_reseller_smoke_test">
				<?php wp_nonce_field( 'gano_reseller_smoke_test_nonce' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="gano_reseller_sandbox">
								<?php esc_html_e( 'Modo sandbox (test-godaddy.com)', 'gano-reseller' ); ?>
							</label>
						</th>
						<td>
							<input
								type="checkbox"
								id="gano_reseller_sandbox"
								name="gano_reseller_sandbox"
								value="1"
								<?php checked( $sandbox ); ?>
							>
							<p class="description">
								<?php esc_html_e( 'Activa para redirigir el carrito a test-godaddy.com. Desactiva antes de ir a producción.', 'gano-reseller' ); ?>
							</p>
						</td>
					</tr>
				</table>
				<?php submit_button( __( 'Guardar modo', 'gano-reseller' ) ); ?>
			</form>

			<hr>

			<!-- Resultados de las comprobaciones -->
			<h2><?php esc_html_e( 'Resultados de comprobaciones', 'gano-reseller' ); ?></h2>

			<?php if ( $pass_all ) : ?>
				<div class="notice notice-success"><p>✅ <?php esc_html_e( 'Todas las comprobaciones pasaron.', 'gano-reseller' ); ?></p></div>
			<?php else : ?>
				<div class="notice notice-warning"><p>⚠️ <?php esc_html_e( 'Hay comprobaciones que requieren atención (ver detalles abajo).', 'gano-reseller' ); ?></p></div>
			<?php endif; ?>

			<table class="widefat striped" style="max-width:900px;margin-top:1em;">
				<thead>
					<tr>
						<th style="width:40px;"><?php esc_html_e( 'Estado', 'gano-reseller' ); ?></th>
						<th><?php esc_html_e( 'Comprobación', 'gano-reseller' ); ?></th>
						<th><?php esc_html_e( 'Detalle', 'gano-reseller' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $results as $row ) : ?>
						<tr>
							<td style="text-align:center;font-size:1.3em;">
								<?php echo $row['ok'] ? '✅' : '❌'; ?>
							</td>
							<td><?php echo esc_html( $row['label'] ); ?></td>
							<td>
								<?php
								// El campo 'detail' puede contener HTML seguro (enlaces, código, tabla de PFIDs).
								echo wp_kses(
									$row['detail'],
									array(
										'a'     => array( 'href' => true, 'target' => true, 'rel' => true ),
										'code'  => array(),
										'table' => array( 'class' => true, 'style' => true ),
										'tbody' => array(),
										'tr'    => array(),
										'td'    => array( 'style' => true ),
									)
								);
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<h2 style="margin-top:2em;"><?php esc_html_e( 'Pasos para smoke test manual', 'gano-reseller' ); ?></h2>
			<ol>
				<li><?php esc_html_e( 'Activa "Modo sandbox" arriba y guarda.', 'gano-reseller' ); ?></li>
				<li><?php esc_html_e( 'Confirma que el pl_id esté configurado (check 1 en verde).', 'gano-reseller' ); ?></li>
				<li><?php esc_html_e( 'Confirma que los 8 PFIDs están configurados (check 5 en verde). Si faltan, ve a Ajustes → Gano Reseller.', 'gano-reseller' ); ?></li>
				<li><?php esc_html_e( 'Haz clic en la URL de checkout generada (check 4) — debe abrir el carrito de test-godaddy.com sin requerir pago real.', 'gano-reseller' ); ?></li>
				<li><?php esc_html_e( 'Prueba la URL de activación de cada bundle (checks 6a-c) — debe redirigir al carrito sandbox con los productos incluidos.', 'gano-reseller' ); ?></li>
				<li><?php esc_html_e( 'Anota cualquier fallo (pl_id incorrecto, redirección a URL de producción, 404 en carrito) en el issue de GitHub.', 'gano-reseller' ); ?></li>
				<li><?php esc_html_e( 'Desactiva "Modo sandbox" antes de ir a producción.', 'gano-reseller' ); ?></li>
			</ol>
		</div>
		<?php
	}
}

Gano_Reseller_Smoke_Test::init();
