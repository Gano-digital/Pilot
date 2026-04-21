<?php
/**
 * Card Component (Shadcn/ui inspired)
 *
 * Uso:
 * gano_card([
 *   'title' => 'Plan name',
 *   'content' => 'Card content HTML',
 *   'footer' => 'Card footer',
 *   'variant' => 'default|elevated|outline|dark',
 *   'class' => '',
 * ])
 *
 * O componente wrapper:
 * <div <?php gano_card_attrs() ?>>
 *   Content here
 * </div>
 */

function gano_card( $args = [] ) {
	$defaults = [
		'title'    => '',
		'content'  => '',
		'footer'   => '',
		'variant'  => 'default',
		'icon'     => null,
		'class'    => '',
		'featured' => false,
	];
	$args = wp_parse_args( $args, $defaults );

	$variant_classes = [
		'default'  => 'bg-white border border-gray-200 shadow-sm hover:shadow-md',
		'elevated' => 'bg-white shadow-lg hover:shadow-xl',
		'outline'  => 'bg-white border-2 border-[var(--gano-blue)]',
		'dark'     => 'bg-slate-900 border border-slate-800 text-white',
	];

	$base_classes = 'rounded-lg transition-all duration-200 overflow-hidden';
	$classes = $base_classes . ' ' . ( $variant_classes[ $args['variant'] ] ?? $variant_classes['default'] );

	if ( $args['featured'] ) {
		$classes .= ' ring-2 ring-[var(--gano-orange)]';
	}

	if ( $args['class'] ) {
		$classes .= ' ' . $args['class'];
	}

	$html = '<div class="' . esc_attr( $classes ) . '">';

	// Header con icon + title
	if ( $args['title'] || $args['icon'] ) {
		$html .= '<div class="px-6 py-4 border-b border-gray-200">';

		if ( $args['icon'] ) {
			$html .= gano_icon( [ 'name' => $args['icon'], 'size' => 24 ] ) . ' ';
		}

		if ( $args['title'] ) {
			$html .= '<h3 class="text-lg font-semibold inline-block">' . esc_html( $args['title'] ) . '</h3>';
		}

		$html .= '</div>';
	}

	// Content
	if ( $args['content'] ) {
		$html .= '<div class="px-6 py-4">' . wp_kses_post( $args['content'] ) . '</div>';
	}

	// Footer
	if ( $args['footer'] ) {
		$html .= '<div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-sm text-gray-600">';
		$html .= wp_kses_post( $args['footer'] );
		$html .= '</div>';
	}

	$html .= '</div>';

	return $html;
}

/**
 * Retorna string de atributos class para envolver content
 */
function gano_card_attrs( $variant = 'default', $class = '' ) {
	$variant_classes = [
		'default'  => 'bg-white border border-gray-200 shadow-sm hover:shadow-md',
		'elevated' => 'bg-white shadow-lg',
		'dark'     => 'bg-slate-900 border border-slate-800 text-white',
	];

	$base_classes = 'rounded-lg p-6 transition-all duration-200';
	$classes = $base_classes . ' ' . ( $variant_classes[ $variant ] ?? $variant_classes['default'] );

	if ( $class ) {
		$classes .= ' ' . $class;
	}

	echo 'class="' . esc_attr( $classes ) . '"';
}

/**
 * Pricing Card (para planes de hosting)
 */
function gano_pricing_card( $args = [] ) {
	$defaults = [
		'name'       => 'Plan',
		'price'      => '$0',
		'period'     => '/mes',
		'description' => '',
		'features'   => [],
		'cta_text'   => 'Elegir',
		'cta_url'    => '#',
		'featured'   => false,
		'tag'        => '',
	];
	$args = wp_parse_args( $args, $defaults );

	$featured_class = $args['featured'] ? 'ring-2 ring-[var(--gano-orange)] md:scale-105' : '';

	$html = '<div class="gano-pricing-card ' . esc_attr( $featured_class ) . '">';

	// Tag badge
	if ( $args['tag'] ) {
		$html .= '<span class="gano-badge gano-badge--orange text-xs mb-4 inline-block">' . esc_html( $args['tag'] ) . '</span>';
	}

	// Name
	$html .= '<h3 class="text-2xl font-bold text-[var(--gano-dark)] mb-2">' . esc_html( $args['name'] ) . '</h3>';

	// Description
	if ( $args['description'] ) {
		$html .= '<p class="text-gray-600 text-sm mb-4">' . esc_html( $args['description'] ) . '</p>';
	}

	// Price
	$html .= '<div class="mb-6">';
	$html .= '<span class="text-4xl font-extrabold text-[var(--gano-blue)]">' . esc_html( $args['price'] ) . '</span>';
	$html .= '<span class="text-gray-500 text-sm">' . esc_html( $args['period'] ) . '</span>';
	$html .= '</div>';

	// Features list
	if ( ! empty( $args['features'] ) ) {
		$html .= '<ul class="space-y-3 mb-6">';
		foreach ( $args['features'] as $feature ) {
			$html .= '<li class="flex gap-3 text-sm">';
			$html .= gano_icon( [ 'name' => 'check', 'size' => 18, 'color' => 'var(--gano-green)' ] );
			$html .= '<span>' . esc_html( $feature ) . '</span>';
			$html .= '</li>';
		}
		$html .= '</ul>';
	}

	// CTA Button
	$html .= gano_button( [
		'text'    => $args['cta_text'],
		'href'    => $args['cta_url'],
		'variant' => $args['featured'] ? 'primary' : 'outline',
		'class'   => 'w-full',
	] );

	$html .= '</div>';

	return $html;
}
