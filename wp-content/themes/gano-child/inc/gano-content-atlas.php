<?php
/**
 * Atlas de lectura — índice curado de páginas y entradas (shortcode + hero).
 *
 * Widget Elementor → Código corto: [gano_content_atlas]
 *
 * Opción `gano_atlas_curated_slugs`: slugs extra separados por comas.
 *
 * @package gano-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Slugs curados por defecto (orden por título en PHP).
 *
 * @return string[]
 */
function gano_atlas_default_curated_slugs(): array {
	return array_unique(
		array(
			'almacenamiento-nvme',
			'arquitectura-cloud',
			'blog',
			'ciber-resiliencia-fractal',
			'computacion-serverless',
			'como-elegir-nombre-dominio-negocio',
			'comenzar-aqui',
			'catalogo-sota',
			'contacto',
			'diagnostico-digital',
			'dominios',
			'ecosistemas',
			'ecosistemas-hibridos',
			'edge-computing-pro',
			'hosting',
			'hosting-compartido-vs-vps',
			'hub-sota',
			'inteligencia-sintetica',
			'manifiesto-nvme-velocidad-nueva-seguridad',
			'nosotros',
			'politica-de-cookies',
			'politica-de-privacidad',
			'por-que-empresa-necesita-pagina-web',
			'red-global-anycast',
			'seguridad-zero-trust',
			'seo-landing',
			'servicios',
			'shop-premium',
			'soberania-digital',
			'soberania-digital-derecho-propiedad-datos',
			'soporte',
			'sota-hub',
			'terminos-y-condiciones',
			'zero-trust-blindando-perimetro',
		)
	);
}

/**
 * @param WP_Post $post Post object.
 * @return string pilares|guias|legal
 */
function gano_atlas_classify_post( WP_Post $post ): string {
	$slug = $post->post_name;
	$legal = array( 'politica-de-privacidad', 'terminos-y-condiciones', 'politica-de-cookies' );
	if ( in_array( $slug, $legal, true ) ) {
		return 'legal';
	}
	$guias_slugs = array(
		'por-que-empresa-necesita-pagina-web',
		'como-elegir-nombre-dominio-negocio',
		'hosting-compartido-vs-vps',
		'comenzar-aqui',
	);
	if ( 'post' === $post->post_type || in_array( $slug, $guias_slugs, true ) ) {
		return 'guias';
	}
	return 'pilares';
}

/**
 * @return WP_Post[]
 */
function gano_atlas_get_curated_posts(): array {
	$extra = get_option( 'gano_atlas_curated_slugs', '' );
	$slugs = gano_atlas_default_curated_slugs();
	if ( is_string( $extra ) && $extra !== '' ) {
		$more  = array_filter( array_map( 'trim', explode( ',', $extra ) ) );
		$slugs = array_values( array_unique( array_merge( $slugs, $more ) ) );
	}

	$woo_block = array( 'shop', 'cart', 'checkout', 'my-account' );
	$by_id     = array();

	foreach ( $slugs as $slug ) {
		if ( in_array( $slug, $woo_block, true ) ) {
			continue;
		}
		$p = get_page_by_path( $slug, OBJECT, 'page' );
		if ( ! $p || 'publish' !== $p->post_status ) {
			$q = get_posts(
				array(
					'name'           => $slug,
					'post_type'      => 'post',
					'post_status'    => 'publish',
					'posts_per_page' => 1,
				)
			);
			$p = ! empty( $q[0] ) ? $q[0] : null;
		}
		if ( $p instanceof WP_Post ) {
			$by_id[ $p->ID ] = $p;
		}
	}

	$out = array_values( $by_id );
	usort(
		$out,
		static function ( WP_Post $a, WP_Post $b ) {
			return strcasecmp( $a->post_title, $b->post_title );
		}
	);
	return $out;
}

/**
 * Encola CSS/JS del Atlas (una vez por request).
 */
function gano_content_atlas_enqueue_assets(): void {
	static $done = false;
	if ( $done ) {
		return;
	}
	$done = true;

	$css = get_stylesheet_directory() . '/css/gano-content-atlas.css';
	$js  = get_stylesheet_directory() . '/js/gano-content-atlas.js';
	$ver = wp_get_theme()->get( 'Version' );
	if ( file_exists( $css ) ) {
		$ver = (string) filemtime( $css );
	}
	wp_enqueue_style(
		'gano-content-atlas',
		get_stylesheet_directory_uri() . '/css/gano-content-atlas.css',
		array( 'gano-child-style' ),
		$ver
	);
	$ver_js = file_exists( $js ) ? (string) filemtime( $js ) : $ver;
	wp_enqueue_script(
		'gano-content-atlas',
		get_stylesheet_directory_uri() . '/js/gano-content-atlas.js',
		array(),
		$ver_js,
		true
	);
}

/**
 * @param array<string,mixed> $args Unused; reservado.
 * @return string HTML.
 */
function gano_render_content_atlas( array $args = array() ): string {
	gano_content_atlas_enqueue_assets();

	$items = gano_atlas_get_curated_posts();
	if ( empty( $items ) ) {
		return '';
	}

	$uid = 'gano-atlas-' . wp_unique_id();

	$labels = array(
		'pilares' => __( 'Pilar', 'gano-child' ),
		'guias'   => __( 'Guía', 'gano-child' ),
		'legal'   => __( 'Legal', 'gano-child' ),
	);

	ob_start();
	?>
<div class="gano-atlas-root" data-gano-atlas-root>
<div class="gano-atlas-hero gano-atlas-hero--sota" data-gano-atlas-intro>
	<div class="gano-atlas-hero__text">
		<p class="gano-atlas-hero__eyebrow"><?php esc_html_e( 'SOTA — State of the Art', 'gano-child' ); ?></p>
		<p class="gano-atlas-hero__copy">
			<?php esc_html_e( 'En Gano Digital, SOTA es nuestro estándar: arquitectura y operación al nivel que el mercado considera “lo mejor disponible hoy”, sin humo ni promesas genéricas. Lo aplicamos a hosting, seguridad y acompañamiento comercial para que tu equipo sepa exactamente qué compra y por qué.', 'gano-child' ); ?>
		</p>
	</div>
	<div class="gano-atlas-hero__action">
		<button type="button" class="gano-atlas-open btn-holo" aria-expanded="false" aria-controls="<?php echo esc_attr( $uid ); ?>-sheet" data-gano-atlas-open>
			<span class="label"><?php esc_html_e( 'Mapa de lectura', 'gano-child' ); ?></span>
			<span class="arrow" aria-hidden="true">→</span>
		</button>
		<p class="gano-atlas-hero__hint">
			<?php
			$url_cat = function_exists( 'gano_resolve_page_url' )
				? gano_resolve_page_url( 'catalogo-sota', 'sota-hub' )
				: home_url( '/catalogo-sota/' );
			?>
			<a href="<?php echo esc_url( $url_cat ); ?>"><?php esc_html_e( 'Ver índice editorial completo →', 'gano-child' ); ?></a>
		</p>
	</div>
</div>

<div class="gano-atlas-backdrop" data-gano-atlas-backdrop hidden></div>
<div id="<?php echo esc_attr( $uid ); ?>-sheet" class="gano-atlas-sheet" data-gano-atlas-sheet hidden role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mapa de lectura del sitio', 'gano-child' ); ?>">
	<div class="gano-atlas-sheet__head">
		<h2 class="gano-atlas-sheet__title"><?php esc_html_e( 'Atlas de lectura', 'gano-child' ); ?></h2>
		<button type="button" class="gano-atlas-close" data-gano-atlas-close aria-label="<?php esc_attr_e( 'Cerrar', 'gano-child' ); ?>">×</button>
	</div>
	<div class="gano-atlas-chips" role="tablist" aria-label="<?php esc_attr_e( 'Filtrar por categoría', 'gano-child' ); ?>">
		<button type="button" class="gano-atlas-chip is-active" data-atlas-filter="todos"><?php esc_html_e( 'Todos', 'gano-child' ); ?></button>
		<button type="button" class="gano-atlas-chip" data-atlas-filter="pilares"><?php esc_html_e( 'Pilares SOTA', 'gano-child' ); ?></button>
		<button type="button" class="gano-atlas-chip" data-atlas-filter="guias"><?php esc_html_e( 'Guías', 'gano-child' ); ?></button>
		<button type="button" class="gano-atlas-chip" data-atlas-filter="legal"><?php esc_html_e( 'Legal', 'gano-child' ); ?></button>
	</div>
	<ul class="gano-atlas-list">
		<?php foreach ( $items as $post ) : ?>
			<?php
			$group = gano_atlas_classify_post( $post );
			$url   = get_permalink( $post );
			$type  = 'post' === $post->post_type ? __( 'Artículo', 'gano-child' ) : __( 'Página', 'gano-child' );
			$lab   = isset( $labels[ $group ] ) ? $labels[ $group ] : $group;
			?>
			<li class="gano-atlas-item" data-atlas-group="<?php echo esc_attr( $group ); ?>">
				<a class="gano-atlas-item__link" href="<?php echo esc_url( $url ); ?>">
					<span class="gano-atlas-item__title"><?php echo esc_html( get_the_title( $post ) ); ?></span>
					<span class="gano-atlas-item__meta"><?php echo esc_html( $type . ' · ' . $lab ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</div>
</div>
	<?php
	return (string) ob_get_clean();
}

add_shortcode(
	'gano_content_atlas',
	static function () {
		return gano_render_content_atlas();
	}
);
