<?php
/**
 * Reusable Component Helpers
 * Used across homepage v2 modular template structure
 *
 * @package Gano_Digital
 */

/**
 * Render trust badge (metric card)
 *
 * @param int    $count Count/number
 * @param string $label Label text
 * @return string HTML
 */
function gano_trust_badge( $count, $label ) {
	return sprintf(
		'<div class="trust-badge"><strong>%d</strong> %s</div>',
		intval( $count ),
		esc_html( $label )
	);
}

/**
 * Render feature card
 *
 * @param string $icon Icon URL or class
 * @param string $title Feature title
 * @param string $description Feature description
 * @param string $cta_text CTA button text (optional)
 * @param string $cta_url CTA button URL (optional)
 * @return string HTML
 */
function gano_feature_card( $icon, $title, $description, $cta_text = '', $cta_url = '' ) {
	$cta_html = '';
	if ( $cta_text && $cta_url ) {
		$cta_html = sprintf(
			'<a href="%s" class="btn btn-secondary">%s</a>',
			esc_url( $cta_url ),
			esc_html( $cta_text )
		);
	}

	return sprintf(
		'<div class="feature-card">
			<img src="%s" alt="" class="feature-icon">
			<h3>%s</h3>
			<p>%s</p>
			%s
		</div>',
		esc_url( $icon ),
		esc_html( $title ),
		wp_kses_post( $description ),
		$cta_html
	);
}

/**
 * Render CTA button
 *
 * @param string $text Button text
 * @param string $url Button URL
 * @param string $style Button style: 'primary', 'secondary', 'ghost'
 * @return string HTML
 */
function gano_cta_button( $text, $url, $style = 'primary' ) {
	$class = 'btn btn-' . esc_attr( $style );
	return sprintf(
		'<a href="%s" class="%s">%s</a>',
		esc_url( $url ),
		$class,
		esc_html( $text )
	);
}

/**
 * Render certification badge
 *
 * @param string $icon Icon URL
 * @param string $name Badge name
 * @return string HTML
 */
function gano_cert_badge( $icon, $name ) {
	return sprintf(
		'<div class="cert-badge">
			<img src="%s" alt="" class="cert-icon">
			<span>%s</span>
		</div>',
		esc_url( $icon ),
		esc_html( $name )
	);
}

/**
 * Render comparison table row
 *
 * @param string $feature Feature name
 * @param string $gano_value Gano value
 * @param string $competitor_value Competitor value
 * @param string $competitor_name Competitor name (optional)
 * @return string HTML
 */
function gano_comparison_row( $feature, $gano_value, $competitor_value, $competitor_name = 'Competitor' ) {
	return sprintf(
		'<tr>
			<td class="feature-name">%s</td>
			<td class="gano-value"><strong>%s</strong></td>
			<td class="competitor-value" data-competitor="%s">%s</td>
		</tr>',
		esc_html( $feature ),
		wp_kses_post( $gano_value ),
		esc_attr( $competitor_name ),
		wp_kses_post( $competitor_value )
	);
}

/**
 * Render testimonial card
 *
 * @param string $quote Quote text
 * @param string $name Customer name
 * @param string $role Customer role
 * @param string $company Company name
 * @param float  $rating Star rating (0-5)
 * @return string HTML
 */
function gano_testimonial_card( $quote, $name, $role, $company, $rating = 5 ) {
	$stars = '';
	$rating_int = intval( $rating );
	for ( $i = 0; $i < 5; $i++ ) {
		$filled = $i < $rating_int ? 'filled' : 'empty';
		$stars .= '<span class="star ' . $filled . '">★</span>';
	}

	return sprintf(
		'<div class="testimonial-card">
			<div class="rating">%s</div>
			<blockquote>"%s"</blockquote>
			<footer>
				<strong>%s</strong><br>
				<small>%s @ %s</small>
			</footer>
		</div>',
		$stars,
		wp_kses_post( $quote ),
		esc_html( $name ),
		esc_html( $role ),
		esc_html( $company )
	);
}

/**
 * Render FAQ accordion item
 *
 * @param string $question Question text
 * @param string $answer Answer text
 * @param string $id Unique ID for the accordion item
 * @return string HTML
 */
function gano_faq_item( $question, $answer, $id ) {
	return sprintf(
		'<div class="faq-item">
			<button class="faq-question" aria-expanded="false" aria-controls="faq-answer-%s">
				<span>%s</span>
				<i class="fas fa-chevron-down"></i>
			</button>
			<div id="faq-answer-%s" class="faq-answer" hidden>
				%s
			</div>
		</div>',
		esc_attr( $id ),
		esc_html( $question ),
		esc_attr( $id ),
		wp_kses_post( $answer )
	);
}
