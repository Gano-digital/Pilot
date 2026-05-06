<?php
/**
 * FAQ Accordion Section
 * Part of homepage v2 modular structure
 *
 * @package Gano_Digital
 */

if ( ! function_exists( 'gano_get_faq_items' ) ) {
	return;
}

$faq_items = gano_get_faq_items();
$faq_id    = 0;
?>

<div class="container faq-section">
	<h2><?php esc_html_e( 'Preguntas Frecuentes', 'gano-child' ); ?></h2>
	<p class="faq-subtitle"><?php esc_html_e( 'Respuestas a las dudas más comunes sobre Gano Digital', 'gano-child' ); ?></p>

	<div class="faq-accordion" id="faqAccordion" role="region" aria-label="<?php esc_attr_e( 'Preguntas frecuentes', 'gano-child' ); ?>">
		<?php foreach ( $faq_items as $category => $items ) : ?>
			<div class="faq-category">
				<h3 class="faq-category-title"><?php echo esc_html( ucfirst( str_replace( '_', ' ', $category ) ) ); ?></h3>

				<?php foreach ( $items as $item ) : ?>
					<div class="faq-item">
						<button
							class="faq-question"
							data-toggle="accordion"
							data-target="#<?php echo esc_attr( $item['id'] ); ?>"
							aria-expanded="false"
							aria-controls="<?php echo esc_attr( $item['id'] ); ?>"
							data-faq-id="<?php echo esc_attr( $item['id'] ); ?>">
							<span class="q-text"><?php echo esc_html( $item['question'] ); ?></span>
							<span class="toggle-icon" aria-hidden="true">+</span>
						</button>
						<div
							id="<?php echo esc_attr( $item['id'] ); ?>"
							class="faq-answer"
							hidden
							role="region">
							<div class="answer-content">
								<?php echo wp_kses_post( $item['answer'] ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="faq-cta">
		<p><?php esc_html_e( '¿No encuentras respuesta?', 'gano-child' ); ?></p>
		<a href="#" class="btn btn-primary" data-faq-contact><?php esc_html_e( 'Contactar Ventas', 'gano-child' ); ?></a>
	</div>

</div>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const faqAccordion = new FAQAccordion('#faqAccordion');
	});
</script>
