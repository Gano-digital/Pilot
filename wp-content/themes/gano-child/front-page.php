<?php
/**
 * Template Name: Gano Digital — Homepage v2
 * Description: Modular homepage with soberanía digital focus
 *
 * @package Gano_Digital
 */

get_header();

// Load component helpers
require_once get_template_directory() . '/template-parts/components.php';

?>

<main id="primary" class="site-main gano-homepage-v2">

	<!-- SECTION 1: Hero + Trust Badge -->
	<section id="hero" class="hero-section">
		<?php get_template_part( 'template-parts/sections/hero' ); ?>
	</section>

	<!-- SECTION 2: Ecosystems (Pricing Tiers) -->
	<section id="ecosystems" class="ecosystems-section">
		<?php get_template_part( 'template-parts/sections/ecosystems' ); ?>
	</section>

	<!-- SECTION 3: Features (What's Included) -->
	<section id="features" class="features-section">
		<?php get_template_part( 'template-parts/sections/features' ); ?>
	</section>

	<!-- SECTION 4: Trust Signals (Testimonials, Metrics, Certifications) -->
	<section id="trust" class="trust-signals-section">
		<?php get_template_part( 'template-parts/sections/trust-signals' ); ?>
	</section>

	<!-- SECTION 5: Competitive Comparison -->
	<section id="comparison" class="comparison-section">
		<?php get_template_part( 'template-parts/sections/comparison' ); ?>
	</section>

	<!-- SECTION 6: FAQ Accordion -->
	<section id="faq" class="faq-section">
		<?php get_template_part( 'template-parts/sections/faq' ); ?>
	</section>

	<!-- SECTION 7: CTA Final (Lead Capture) -->
	<section id="cta-final" class="cta-final-section">
		<?php get_template_part( 'template-parts/sections/cta-final' ); ?>
	</section>

</main>

<?php get_footer(); ?>
