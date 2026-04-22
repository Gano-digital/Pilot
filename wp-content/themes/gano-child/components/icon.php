<?php
/**
 * Icon Component (Lucide Icons)
 *
 * Uso:
 * gano_icon([
 *   'name' => 'lucide-check',
 *   'size' => 20,
 *   'class' => '',
 *   'fill' => false,
 * ])
 *
 * Requiere: npm install lucide-react (o usar CDN SVG)
 */

function gano_icon( $args = [] ) {
	$defaults = [
		'name'  => 'check',
		'size'  => 24,
		'class' => '',
		'fill'  => false,
		'color' => 'currentColor',
	];
	$args = wp_parse_args( $args, $defaults );

	// Map de iconos Lucide → SVG (para WordPress sin node_modules)
	$lucide_icons = [
		'check'         => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>',
		'server'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="8"></rect><rect x="2" y="14" width="20" height="8"></rect><line x1="6" y1="6" x2="6" y2="6.01"></line><line x1="6" y1="18" x2="6" y2="18.01"></line></svg>',
		'cloud'         => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"></path></svg>',
		'shield'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
		'zap'           => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
		'settings'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M12 1v6m0 6v6M4.22 4.22l4.24 4.24m2.12 2.12l4.24 4.24M1 12h6m6 0h6m-17.78 7.78l4.24-4.24m2.12-2.12l4.24-4.24"></path></svg>',
		'alert-circle' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>',
		'chevron-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>',
		'menu'          => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>',
		'search'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
		'loader'        => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="16.66" cy="15.34" r="1"></circle><circle cx="20.07" cy="20.07" r="1"></circle><circle cx="15.34" cy="16.66" r="1"></circle><circle cx="6.39" cy="20.07" r="1"></circle><circle cx="3.93" cy="15.34" r="1"></circle><circle cx="8.34" cy="6.39" r="1"></circle></svg>',
	];

	// Remapear si tiene prefijo lucide-
	$icon_name = str_replace( 'lucide-', '', $args['name'] );
	$svg = $lucide_icons[ $icon_name ] ?? null;

	if ( ! $svg ) {
		// Fallback: emoji icon
		return '<span class="' . esc_attr( $args['class'] ) . '" style="font-size: ' . intval( $args['size'] ) . 'px">❓</span>';
	}

	$html = sprintf(
		'<span class="gano-icon %s" style="width: %dpx; height: %dpx; display: inline-block; flex-shrink: 0; vertical-align: middle; color: %s;">%s</span>',
		esc_attr( $args['class'] ),
		intval( $args['size'] ),
		intval( $args['size'] ),
		esc_attr( $args['color'] ),
		$svg
	);

	return $html;
}

// Alias
function gano_lucide_icon( $name, $size = 24, $class = '' ) {
	return gano_icon( [
		'name'  => $name,
		'size'  => $size,
		'class' => $class,
	] );
}
