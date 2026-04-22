<?php
/**
 * Button Component (Shadcn/ui inspired)
 *
 * Uso:
 * gano_button([
 *   'text' => 'Elegir plan',
 *   'href' => '/contacto',
 *   'variant' => 'primary|secondary|outline|ghost',
 *   'size' => 'sm|md|lg',
 *   'icon' => 'lucide-check',
 *   'loading' => false,
 *   'disabled' => false,
 * ])
 */

function gano_button( $args = [] ) {
	$defaults = [
		'text'       => 'Button',
		'href'       => '#',
		'variant'    => 'primary', // primary, secondary, outline, ghost, destructive
		'size'       => 'md', // sm, md, lg
		'icon'       => null, // Lucide icon name
		'icon_pos'   => 'left', // left, right
		'loading'    => false,
		'disabled'   => false,
		'class'      => '',
		'data_attrs' => [],
	];
	$args = wp_parse_args( $args, $defaults );

	// Base styles (Tailwind)
	$base_classes = 'inline-flex items-center justify-center gap-2 font-medium rounded transition-all duration-200 cursor-pointer focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2';

	// Variant styles
	$variant_classes = [
		'primary'     => 'bg-[var(--gano-orange)] text-white hover:bg-[var(--gano-orange-dark)] focus-visible:ring-[var(--gano-orange)]',
		'secondary'   => 'bg-[var(--gano-blue)] text-white hover:bg-[var(--gano-blue-dark)] focus-visible:ring-[var(--gano-blue)]',
		'outline'     => 'border-2 border-[var(--gano-blue)] text-[var(--gano-blue)] hover:bg-[var(--gano-blue-light)]',
		'ghost'       => 'text-[var(--gano-blue)] hover:bg-[var(--gano-blue-light)]',
		'destructive' => 'bg-red-600 text-white hover:bg-red-700 focus-visible:ring-red-500',
	];

	// Size styles
	$size_classes = [
		'sm' => 'px-3 py-1.5 text-sm h-8 min-w-8',
		'md' => 'px-4 py-2.5 text-base h-10 min-w-10',
		'lg' => 'px-6 py-3 text-lg h-12 min-w-12',
	];

	$classes = $base_classes;
	$classes .= ' ' . ( $variant_classes[ $args['variant'] ] ?? $variant_classes['primary'] );
	$classes .= ' ' . ( $size_classes[ $args['size'] ] ?? $size_classes['md'] );

	if ( $args['disabled'] || $args['loading'] ) {
		$classes .= ' opacity-50 cursor-not-allowed pointer-events-none';
	}

	if ( $args['class'] ) {
		$classes .= ' ' . $args['class'];
	}

	// Data attributes
	$data_html = '';
	foreach ( $args['data_attrs'] as $key => $value ) {
		$data_html .= ' data-' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
	}

	// Icon HTML
	$icon_html = '';
	if ( $args['icon'] ) {
		$icon_html = gano_icon( [
			'name' => $args['icon'],
			'size' => $args['size'] === 'sm' ? 16 : ( $args['size'] === 'lg' ? 24 : 20 ),
		] );
	}

	// Loading spinner
	$loading_html = $args['loading'] ? '<span class="animate-spin">⌛</span>' : '';

	// Button text
	$button_text = $args['loading'] ? 'Cargando...' : $args['text'];

	// Icon position
	$icon_before = $args['icon_pos'] === 'left' ? $icon_html : '';
	$icon_after = $args['icon_pos'] === 'right' ? $icon_html : '';

	$html = sprintf(
		'<a href="%s" class="%s"%s%s>%s%s%s%s</a>',
		esc_url( $args['href'] ),
		esc_attr( $classes ),
		$args['disabled'] ? ' disabled' : '',
		$data_html,
		$loading_html,
		$icon_before,
		esc_html( $button_text ),
		$icon_after
	);

	return $html;
}

/**
 * Versión Button tag (para formularios)
 */
function gano_button_tag( $args = [] ) {
	$defaults = [
		'text'    => 'Button',
		'type'    => 'button', // button, submit, reset
		'variant' => 'primary',
		'size'    => 'md',
		'icon'    => null,
		'loading' => false,
		'id'      => '',
	];
	$args = wp_parse_args( $args, $defaults );

	$variant_classes = [
		'primary'     => 'bg-[var(--gano-orange)] text-white hover:bg-[var(--gano-orange-dark)]',
		'secondary'   => 'bg-[var(--gano-blue)] text-white hover:bg-[var(--gano-blue-dark)]',
		'outline'     => 'border-2 border-[var(--gano-blue)] text-[var(--gano-blue)]',
	];

	$size_classes = [
		'sm' => 'px-3 py-1.5 text-sm',
		'md' => 'px-4 py-2.5 text-base',
		'lg' => 'px-6 py-3 text-lg',
	];

	$base_classes = 'inline-flex items-center gap-2 font-medium rounded transition-all focus-visible:ring-2';
	$classes = $base_classes . ' ' . $variant_classes[ $args['variant'] ] . ' ' . $size_classes[ $args['size'] ];

	if ( $args['loading'] ) {
		$classes .= ' opacity-50';
	}

	$id_attr = $args['id'] ? 'id="' . esc_attr( $args['id'] ) . '"' : '';

	return sprintf(
		'<button type="%s" class="%s" %s %s>%s %s</button>',
		esc_attr( $args['type'] ),
		esc_attr( $classes ),
		$id_attr,
		$args['loading'] ? 'disabled' : '',
		$args['icon'] ? gano_icon( [ 'name' => $args['icon'] ] ) : '',
		esc_html( $args['text'] )
	);
}
