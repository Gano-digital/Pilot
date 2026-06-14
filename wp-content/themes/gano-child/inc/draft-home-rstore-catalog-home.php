<?php
/**
 * Borrador — bloque “4 planes” con shortcodes rstore en home (retirado del landing).
 *
 * El widget rstore suele traer estilos naranja de marca; lo dejamos aquí por si se
 * requiere de nuevo un teaser rápido en la portada.
 *
 * Uso (solo si lo necesitas):
 * 1. En `front-page.php`, tras el teaser comercial, llama:
 *    `gano_draft_render_home_rstore_catalog_teaser_block();`
 * 2. O engancha a un hook propio y devuelve true en el filtro:
 *    `add_filter( 'gano_show_draft_home_rstore_catalog', '__return_true' );`
 *
 * @package gano-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Imprime la sección draft (grid + rstore_product) si el filtro lo permite.
 *
 * @return void
 */
function gano_draft_render_home_rstore_catalog_teaser_block() {
	if ( ! apply_filters( 'gano_show_draft_home_rstore_catalog', false ) ) {
		return;
	}

	$url_ecosistemas = function_exists( 'gano_resolve_page_url' )
		? gano_resolve_page_url( 'ecosistemas' )
		: home_url( '/ecosistemas/' );

	?>
	<section class="section-gano gano-home-section gano-home-section--surface gano-draft-rstore-catalog" data-gano-catalog aria-labelledby="gano-draft-ecosistemas-heading">
		<div class="gano-shell">
			<div class="section-title-gano">
				<h2 id="gano-draft-ecosistemas-heading"><?php esc_html_e( '[Borrador] Planes destacados (rstore)', 'gano-child' ); ?></h2>
				<p><?php esc_html_e( 'Solo visible si gano_show_draft_home_rstore_catalog está activo.', 'gano-child' ); ?></p>
				<p><a class="btn-gano" href="<?php echo esc_url( $url_ecosistemas ); ?>"><?php esc_html_e( 'Ir al catálogo SOTA en Ecosistemas', 'gano-child' ); ?></a></p>
			</div>
			<div class="ecosistemas-grid" id="catalog-container">
				<?php
				$ecosistemas = array(
					array(
						'nombre'    => 'Núcleo Prime',
						'slug'      => 'wordpress-basico',
						'precio'    => '$196.000',
						'categoria' => 'hostingwebcpanel',
						'features'  => array(
							'Almacenamiento NVMe',
							'Soporte en español por ticket',
							'Activación rápida',
						),
					),
					array(
						'nombre'    => 'Fortaleza Delta',
						'slug'      => 'wordpress-deluxe',
						'precio'    => '$450.000',
						'categoria' => 'wordpressadministrado',
						'features'  => array(
							'Hardening activo incluido',
							'Mayor capacidad de recursos',
							'Respaldos automatizados',
						),
					),
					array(
						'nombre'    => 'Bastión SOTA',
						'slug'      => 'wordpress-ultimate',
						'precio'    => '$890.000',
						'categoria' => 'seguridadweb',
						'features'  => array(
							'Recursos dedicados',
							'SLA ≥ 99.9%',
							'Monitoreo proactivo',
						),
					),
					array(
						'nombre'    => 'Ultimate WP',
						'slug'      => 'cpanel-ultimate',
						'precio'    => '$1.200.000',
						'categoria' => 'servidoresvps',
						'features'  => array(
							'Máxima capacidad de recursos',
							'Blindaje ante picos masivos',
							'Soporte prioritario',
						),
					),
				);

				foreach ( $ecosistemas as $eco ) {
					$product_query = new WP_Query(
						array(
							'post_type'      => 'reseller_product',
							'name'           => $eco['slug'],
							'posts_per_page' => 1,
						)
					);
					$post_id = $product_query->have_posts() ? $product_query->posts[0]->ID : null;
					wp_reset_postdata();
					?>
					<article
						class="ecosystem-card"
						data-category="<?php echo esc_attr( $eco['categoria'] ); ?>"
						data-product-id="<?php echo esc_attr( sanitize_title( $eco['slug'] ) ); ?>"
						data-product-name="<?php echo esc_attr( $eco['nombre'] ); ?>"
						data-product-price="<?php echo esc_attr( $eco['precio'] ); ?>"
					>
						<h3><?php echo esc_html( $eco['nombre'] ); ?></h3>
						<div class="price"><?php echo esc_html( $eco['precio'] ); ?></div>
						<ul>
							<?php foreach ( $eco['features'] as $feature ) : ?>
								<li><?php echo esc_html( $feature ); ?></li>
							<?php endforeach; ?>
						</ul>
						<?php
						if ( $post_id ) {
							echo do_shortcode( "[rstore_product post_id={$post_id} show_price=1 redirect=1 button_label='Elegir Plan']" );
						} else {
							echo '<a href="' . esc_url( home_url( '/contacto/' ) ) . '" class="btn-gano">Consultar</a>';
						}
						?>
					</article>
					<?php
				}
				?>
			</div>
		</div>
	</section>
	<?php
}
