<?php
/**
 * Template Name: Hub de Innovación SOTA
 * Template Post Type: page
 *
 * Muestra todas las páginas del Hub de Innovación SOTA agrupadas por categoría.
 * Páginas en draft aparecen como "En Preparación"; en publish como tarjetas completas.
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$categories = [
	'infraestructura'      => 'Infraestructura',
	'seguridad'            => 'Seguridad',
	'inteligencia-artificial' => 'Inteligencia Artificial',
	'rendimiento'          => 'Rendimiento',
	'estrategia'           => 'Estrategia',
];

$sota_pages = gano_get_sota_hub_pages();
?>

<main id="gano-sota-hub" class="gano-sota-hub" role="main" aria-label="Hub de Innovación SOTA">

	<header class="sota-hub-hero" style="background: #05070a; color: #fff; padding: 120px 20px 80px; text-align: center; border-bottom: 2px solid rgba(212,175,55,0.2);">
		<div style="max-width: 900px; margin: 0 auto;">
			<span style="background: var(--gano-gold, #D4AF37); color: #000; padding: 4px 14px; border-radius: 4px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em;">SOTA KNOWLEDGE HUB</span>
			<h1 style="font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 900; margin: 24px 0 16px; color: #fff; line-height: 1.1;">
				<?php echo esc_html( get_the_title() ); ?>
			</h1>
			<p style="font-size: 1.15rem; color: #94a3b8; max-width: 640px; margin: 0 auto;">
				Ingeniería de vanguardia aplicada a la soberanía digital del siglo XXI. <?php echo esc_html( count( $sota_pages ) ); ?> artículos técnicos.
			</p>
		</div>
	</header>

	<div class="sota-hub-body" style="background: #05070a; padding: 60px 20px 100px; min-height: 60vh;">
		<div style="max-width: 1200px; margin: 0 auto;">

			<?php if ( empty( $sota_pages ) ) : ?>
				<p style="color: #64748b; text-align: center; padding: 60px 0; font-size: 1.1rem;">
					Las páginas SOTA están en preparación. Vuelve pronto.
				</p>
			<?php else : ?>

				<?php foreach ( $categories as $cat_slug => $cat_label ) :
					$pages_in_cat = array_filter( $sota_pages, function( $p ) use ( $cat_slug ) {
						return get_post_meta( $p->ID, '_gano_sota_category', true ) === $cat_slug;
					} );

					if ( empty( $pages_in_cat ) ) {
						continue;
					}
				?>

				<section class="sota-hub-category" style="margin-bottom: 60px;" aria-labelledby="cat-<?php echo esc_attr( $cat_slug ); ?>">
					<h2 id="cat-<?php echo esc_attr( $cat_slug ); ?>" style="color: var(--gano-gold, #D4AF37); font-size: 1rem; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 1px solid rgba(212,175,55,0.2);">
						<?php echo esc_html( $cat_label ); ?>
					</h2>

					<div class="sota-hub-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px;">
						<?php foreach ( $pages_in_cat as $page ) :
							$is_published = ( 'publish' === $page->post_status );
							$page_url     = get_permalink( $page->ID );
						?>
						<article
							class="sota-hub-card<?php echo $is_published ? '' : ' sota-hub-card--draft'; ?>"
							style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,<?php echo $is_published ? '0.1' : '0.05'; ?>); border-radius: 12px; padding: 28px; display: flex; flex-direction: column; gap: 16px; transition: border-color 0.25s;"
							<?php if ( ! $is_published ) : ?>aria-label="En preparación: <?php echo esc_attr( $page->post_title ); ?>"<?php endif; ?>
						>
							<?php if ( ! $is_published ) : ?>
								<span style="font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: #64748b; letter-spacing: 0.1em;">En Preparación</span>
							<?php endif; ?>

							<?php if ( has_post_thumbnail( $page->ID ) ) : ?>
								<div style="border-radius: 8px; overflow: hidden; height: 140px;">
									<?php echo get_the_post_thumbnail( $page->ID, 'medium', [ 'style' => 'width:100%;height:100%;object-fit:cover;', 'alt' => esc_attr( $page->post_title ), 'loading' => 'lazy' ] ); ?>
								</div>
							<?php endif; ?>

							<div style="flex: 1; display: flex; flex-direction: column; gap: 12px;">
								<h3 style="font-size: 1.05rem; font-weight: 700; color: <?php echo $is_published ? '#fff' : '#64748b'; ?>; margin: 0; line-height: 1.35;">
									<?php echo esc_html( $page->post_title ); ?>
								</h3>

								<?php if ( $is_published && $page->post_excerpt ) : ?>
									<p style="font-size: 0.9rem; color: #94a3b8; margin: 0; line-height: 1.6;">
										<?php echo esc_html( wp_trim_words( $page->post_excerpt, 20 ) ); ?>
									</p>
								<?php endif; ?>
							</div>

							<?php if ( $is_published ) : ?>
								<a
									href="<?php echo esc_url( $page_url ); ?>"
									style="display: inline-flex; align-items: center; gap: 8px; color: var(--gano-gold, #D4AF37); font-weight: 700; font-size: 0.85rem; text-decoration: none; letter-spacing: 0.04em;"
									aria-label="Leer artículo: <?php echo esc_attr( $page->post_title ); ?>"
								>
									LEER ARTÍCULO <span aria-hidden="true">→</span>
								</a>
							<?php else : ?>
								<span style="color: #475569; font-size: 0.8rem;">Próximamente</span>
							<?php endif; ?>
						</article>
						<?php endforeach; ?>
					</div>
				</section>

				<?php endforeach; ?>

			<?php endif; ?>

			<div class="sota-hub-cta" style="margin-top: 80px; background: #111; border: 1px solid var(--gano-gold, #D4AF37); padding: 50px; border-radius: 12px; text-align: center;">
				<h3 style="color: #fff; font-size: 2rem; margin: 0 0 12px;">¿Listo para activar tu ecosistema SOTA?</h3>
				<p style="color: #94a3b8; margin: 0 0 30px;">Infraestructura seria, soporte en español y soberanía digital total.</p>
				<a
					href="<?php echo esc_url( home_url( '/contacto' ) ); ?>"
					class="gano-btn-primary"
					style="display: inline-block; background: var(--gano-orange, #FF6B35); color: #fff; font-weight: 700; padding: 16px 36px; border-radius: 6px; text-decoration: none; font-size: 1rem; letter-spacing: 0.04em;"
				>
					Hablar con un especialista
				</a>
			</div>

		</div>
	</div>

</main>

<?php get_footer(); ?>
