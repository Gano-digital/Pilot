<?php
/**
 * Trust Signals Section — Testimonials, Metrics, Certifications
 * Part of homepage v2 modular structure
 *
 * @package Gano_Digital
 */

if ( ! function_exists( 'gano_get_trust_metrics' ) ) {
	return;
}

$metrics        = gano_get_trust_metrics();
$testimonials   = gano_get_testimonials();
$certifications = gano_get_certifications();
?>

<div class="container trust-signals">

	<!-- Metrics Row -->
	<div class="metrics-row">
		<div class="metric">
			<strong><?php echo esc_html( $metrics['customer_count'] ); ?></strong>
			<span><?php esc_html_e( 'Clientes empresariales', 'gano-child' ); ?></span>
		</div>
		<div class="metric">
			<strong><?php echo intval( $metrics['countries'] ); ?></strong>
			<span><?php esc_html_e( 'Países en LATAM', 'gano-child' ); ?></span>
		</div>
		<div class="metric">
			<strong><?php echo esc_html( $metrics['uptime_guarantee'] ); ?></strong>
			<span><?php esc_html_e( 'Uptime garantizado', 'gano-child' ); ?></span>
		</div>
		<div class="metric">
			<strong><?php echo floatval( $metrics['avg_rating'] ); ?>/5</strong>
			<span><?php echo intval( $metrics['total_reviews'] ); ?> <?php esc_html_e( 'reseñas', 'gano-child' ); ?></span>
		</div>
	</div>

	<!-- Testimonials Section -->
	<div class="testimonials-section">
		<h2><?php esc_html_e( 'Lo que dicen nuestros clientes', 'gano-child' ); ?></h2>
		<div class="testimonials-carousel">
			<?php foreach ( $testimonials as $testimonial ) : ?>
				<div class="testimonial-card">
					<div class="rating">
						<?php
						$rating = intval( $testimonial['rating'] );
						for ( $i = 0; $i < 5; $i++ ) :
							?>
							<span class="star <?php echo $i < $rating ? 'filled' : 'empty'; ?>">★</span>
						<?php endfor; ?>
					</div>
					<blockquote><?php echo wp_kses_post( $testimonial['quote'] ); ?></blockquote>
					<footer>
						<strong><?php echo esc_html( $testimonial['name'] ); ?></strong><br>
						<small><?php echo esc_html( $testimonial['role'] ); ?> @ <?php echo esc_html( $testimonial['company'] ); ?></small>
					</footer>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<!-- Certifications -->
	<div class="certifications-section">
		<h3><?php esc_html_e( 'Certificaciones & Compliance', 'gano-child' ); ?></h3>
		<div class="cert-grid">
			<?php foreach ( $certifications as $cert ) : ?>
				<div class="cert-badge">
					<img
						src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/icons/' . $cert['icon'] ); ?>"
						alt="<?php echo esc_attr( $cert['name'] ); ?>"
						class="cert-icon"
					>
					<span><?php echo esc_html( $cert['name'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

</div>
