<?php
/**
 * Template Name: Hub de Innovación SOTA
 * Template Post Type: page
 *
 * Muestra todas las páginas del Hub de Innovación SOTA agrupadas por categoría.
 * Páginas en draft aparecen como "En Preparación"; en publish como tarjetas completas.
 *
 * @package gano-child
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$categories = gano_get_sota_categories();

$sota_pages = gano_get_sota_hub_pages();
?>

<main id="gano-sota-hub" class="gano-sota-surface" role="main" aria-label="Hub de Innovación SOTA">
	<header class="gano-sota-hero">
		<div class="gano-sota-hero__bg" aria-hidden="true"></div>
		<div class="gano-sota-hero__content">
			<span class="gano-sota-label">SOTA Knowledge Hub</span>
			<h1 class="gano-sota-hero__title"><?php echo esc_html( get_the_title() ); ?></h1>
			<p class="gano-sota-hero__sub">
				Ingeniería de vanguardia aplicada a la soberanía digital del siglo XXI.
				<?php echo esc_html( count( $sota_pages ) ); ?> artículos técnicos.
			</p>
		</div>
	</header>

	<section class="gano-sota-section sota-hub-body">
		<div class="gano-sota-container">
			<?php if ( empty( $sota_pages ) ) : ?>
				<p class="sota-hub-empty">Las páginas SOTA están en preparación. Vuelve pronto.</p>
			<?php else : ?>
				<?php foreach ( $categories as $cat_slug => $cat_label ) :
					$pages_in_cat = array_filter( $sota_pages, function ( $p ) use ( $cat_slug ) {
						return get_post_meta( $p->ID, '_gano_sota_category', true ) === $cat_slug;
					} );

					if ( empty( $pages_in_cat ) ) {
						continue;
					}
					?>
					<section class="sota-hub-category" aria-labelledby="cat-<?php echo esc_attr( $cat_slug ); ?>">
						<h2 id="cat-<?php echo esc_attr( $cat_slug ); ?>" class="sota-hub-category__title">
							<?php echo esc_html( $cat_label ); ?>
						</h2>

						<div class="sota-hub-grid">
							<?php foreach ( $pages_in_cat as $page ) :
								$is_published = ( 'publish' === $page->post_status );
								$page_url     = get_permalink( $page->ID );
								$card_classes = 'sota-hub-card gano-sota-glass-card';
								if ( ! $is_published ) {
									$card_classes .= ' sota-hub-card--draft';
								}
								?>
								<article
									class="<?php echo esc_attr( $card_classes ); ?>"
									<?php if ( ! $is_published ) : ?>
									aria-label="En preparación: <?php echo esc_attr( $page->post_title ); ?>"
									<?php endif; ?>
								>
									<?php if ( ! $is_published ) : ?>
										<span class="sota-hub-card__draft-label">En Preparación</span>
									<?php endif; ?>

									<?php if ( has_post_thumbnail( $page->ID ) ) : ?>
										<div class="sota-hub-card__thumb">
											<?php
											echo get_the_post_thumbnail(
												$page->ID,
												'medium',
												array(
													'class'   => 'sota-hub-card__img',
													'alt'     => esc_attr( $page->post_title ),
													'loading' => 'lazy',
												)
											);
											?>
										</div>
									<?php endif; ?>

									<div class="sota-hub-card__content">
										<h3 class="sota-hub-card__title"><?php echo esc_html( $page->post_title ); ?></h3>
										<?php if ( $is_published && $page->post_excerpt ) : ?>
											<p class="sota-hub-card__excerpt">
												<?php echo esc_html( wp_trim_words( $page->post_excerpt, 20 ) ); ?>
											</p>
										<?php endif; ?>
									</div>

									<?php if ( $is_published ) : ?>
										<a href="<?php echo esc_url( $page_url ); ?>" class="sota-hub-card__link" aria-label="Leer artículo: <?php echo esc_attr( $page->post_title ); ?>">
											LEER ARTÍCULO <span aria-hidden="true">→</span>
										</a>
									<?php else : ?>
										<span class="sota-hub-card__pending">Próximamente</span>
									<?php endif; ?>
								</article>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endforeach; ?>
			<?php endif; ?>

			<div class="sota-hub-cta gano-sota-glass-card">
				<h3>¿Listo para activar tu ecosistema SOTA?</h3>
				<p>Infraestructura seria, soporte en español y soberanía digital total.</p>
				<a href="<?php echo esc_url( home_url( '/contacto' ) ); ?>" class="gano-btn">
					Hablar con un especialista
				</a>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
