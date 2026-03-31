<?php
namespace WprAddons\Modules\WidgetBuilder\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Maps user-defined control configurations to Elementor control PHP code.
 * Each method returns a string of PHP code to be written into the generated widget file.
 */
class ControlFactory {

	/**
	 * Group control types that use Group_Control_* classes instead of Controls_Manager::*.
	 */
	private static $group_types = [
		'typography',
		'background',
		'border',
		'box_shadow',
		'text_shadow',
		'image_size',
	];

	/**
	 * Maps our simple type names to Elementor Controls_Manager constants.
	 */
	private static $type_map = [
		'text'                => 'TEXT',
		'number'              => 'NUMBER',
		'textarea'            => 'TEXTAREA',
		'wysiwyg'             => 'WYSIWYG',
		'code'                => 'CODE',
		'select'              => 'SELECT',
		'select2'             => 'SELECT2',
		'switcher'            => 'SWITCHER',
		'choose'              => 'CHOOSE',
		'color'               => 'COLOR',
		'slider'              => 'SLIDER',
		'dimensions'          => 'DIMENSIONS',
		'url'                 => 'URL',
		'media'               => 'MEDIA',
		'gallery'             => 'GALLERY',
		'icons'               => 'ICONS',
		'font'                => 'FONT',
		'date_time'           => 'DATE_TIME',
		'entrance_animation'  => 'ANIMATION',
		'hover_animation'     => 'HOVER_ANIMATION',
		'hidden'              => 'HIDDEN',
		'image_dimensions'    => 'IMAGE_DIMENSIONS',
	];

	/**
	 * Maps our group type names to Elementor Group_Control_* classes.
	 */
	private static $group_map = [
		'typography'  => '\\Elementor\\Group_Control_Typography',
		'background'  => '\\Elementor\\Group_Control_Background',
		'border'      => '\\Elementor\\Group_Control_Border',
		'box_shadow'  => '\\Elementor\\Group_Control_Box_Shadow',
		'text_shadow' => '\\Elementor\\Group_Control_Text_Shadow',
		'image_size'  => '\\Elementor\\Group_Control_Image_Size',
	];

	/**
	 * Check if a type is a group control.
	 */
	public static function is_group_type( $type ) {
		return in_array( $type, self::$group_types, true );
	}

	/**
	 * Generate PHP code for a single control's add_control() call.
	 *
	 * @param object|array $conf Control configuration from the builder UI.
	 * @return string PHP code string.
	 */
	public static function generate_control_code( $conf ) {
		$conf = (object) $conf;
		$type = isset( $conf->type ) ? $conf->type : 'text';

		if ( self::is_group_type( $type ) ) {
			return self::generate_group_control_code( $conf );
		}

		$elementor_type = isset( self::$type_map[ $type ] ) ? self::$type_map[ $type ] : 'TEXT';
		$key            = isset( $conf->key ) ? sanitize_key( $conf->key ) : 'control_' . wp_rand();

		$code  = "\t\t\$this->add_control(" . PHP_EOL;
		$code .= "\t\t\t'" . $key . "'," . PHP_EOL;
		$code .= "\t\t\t[" . PHP_EOL;

		// Label
		if ( ! empty( $conf->label ) ) {
			$code .= "\t\t\t\t'label' => esc_html__( '" . self::escape_string( $conf->label ) . "', 'wpr-addons' )," . PHP_EOL;
		}

		// Type
		$code .= "\t\t\t\t'type' => \\Elementor\\Controls_Manager::" . $elementor_type . "," . PHP_EOL;

		// Type-specific args
		$code .= self::generate_type_args( $conf, $type );

		$code .= "\t\t\t]" . PHP_EOL;
		$code .= "\t\t);" . PHP_EOL . PHP_EOL;

		return $code;
	}

	/**
	 * Generate PHP code for a group control.
	 */
	private static function generate_group_control_code( $conf ) {
		$conf = (object) $conf;
		$type = $conf->type;
		$key  = isset( $conf->key ) ? sanitize_key( $conf->key ) : 'group_' . wp_rand();

		$group_class = isset( self::$group_map[ $type ] ) ? self::$group_map[ $type ] : self::$group_map['border'];

		$code  = "\t\t\$this->add_group_control(" . PHP_EOL;
		$code .= "\t\t\t" . $group_class . "::get_type()," . PHP_EOL;
		$code .= "\t\t\t[" . PHP_EOL;
		$code .= "\t\t\t\t'name' => '" . $key . "'," . PHP_EOL;

		// Label
		if ( ! empty( $conf->label ) ) {
			$code .= "\t\t\t\t'label' => esc_html__( '" . self::escape_string( $conf->label ) . "', 'wpr-addons' )," . PHP_EOL;
		}

		// Selector
		if ( ! empty( $conf->selector ) ) {
			$selector = self::escape_string( $conf->selector );
			$code    .= "\t\t\t\t'selector' => '{{WRAPPER}} " . $selector . "'," . PHP_EOL;
		}

		// Separator
		if ( ! empty( $conf->separator ) ) {
			$code .= "\t\t\t\t'separator' => '" . self::escape_string( $conf->separator ) . "'," . PHP_EOL;
		}

		$code .= "\t\t\t]" . PHP_EOL;
		$code .= "\t\t);" . PHP_EOL . PHP_EOL;

		return $code;
	}

	/**
	 * Generate type-specific arguments.
	 */
	private static function generate_type_args( $conf, $type ) {
		$code = '';

		// Default value
		if ( isset( $conf->default ) && $conf->default !== '' ) {
			$code .= self::generate_default( $conf, $type );
		}

		// Options (for select, select2, choose)
		if ( ! empty( $conf->options ) && in_array( $type, [ 'select', 'select2', 'choose' ], true ) ) {
			$code .= self::generate_options( $conf, $type );
		}

		// Switcher-specific
		if ( $type === 'switcher' ) {
			$code .= "\t\t\t\t'return_value' => 'yes'," . PHP_EOL;
			if ( ! isset( $conf->default ) || $conf->default === '' ) {
				$code .= "\t\t\t\t'default' => ''," . PHP_EOL;
			}
		}

		// Code language
		if ( $type === 'code' && ! empty( $conf->language ) ) {
			$code .= "\t\t\t\t'language' => '" . self::escape_string( $conf->language ) . "'," . PHP_EOL;
		}

		// Slider-specific
		if ( $type === 'slider' ) {
			$code .= self::generate_slider_args( $conf );
		}

		// Number-specific min/max/step
		if ( $type === 'number' ) {
			if ( isset( $conf->slider_min ) && $conf->slider_min !== '' ) {
				$code .= "\t\t\t\t'min' => " . floatval( $conf->slider_min ) . "," . PHP_EOL;
			}
			if ( isset( $conf->slider_max ) && $conf->slider_max !== '' ) {
				$code .= "\t\t\t\t'max' => " . floatval( $conf->slider_max ) . "," . PHP_EOL;
			}
			if ( isset( $conf->slider_step ) && $conf->slider_step !== '' ) {
				$code .= "\t\t\t\t'step' => " . floatval( $conf->slider_step ) . "," . PHP_EOL;
			}
		}

		// Dimensions-specific
		if ( $type === 'dimensions' ) {
			$code .= self::generate_dimensions_args( $conf );
		}

		// Media default
		if ( $type === 'media' && empty( $conf->default ) ) {
			$code .= "\t\t\t\t'default' => [" . PHP_EOL;
			$code .= "\t\t\t\t\t'url' => ''," . PHP_EOL;
			$code .= "\t\t\t\t]," . PHP_EOL;
		}

		// Selectors (any control type)
		if ( ! empty( $conf->selectors ) ) {
			$code .= self::generate_selectors( $conf, $type );
		}

		// Condition
		if ( ! empty( $conf->condition ) ) {
			$cond = (object) $conf->condition;
			if ( ! empty( $cond->key ) ) {
				$cond_key   = self::escape_string( $cond->key );
				$cond_value = isset( $cond->value ) ? self::escape_string( $cond->value ) : '';
				$code .= "\t\t\t\t'condition' => [" . PHP_EOL;
				$code .= "\t\t\t\t\t'" . $cond_key . "' => '" . $cond_value . "'," . PHP_EOL;
				$code .= "\t\t\t\t]," . PHP_EOL;
			}
		}

		// Separator
		if ( ! empty( $conf->separator ) && in_array( $conf->separator, [ 'before', 'after' ], true ) ) {
			$code .= "\t\t\t\t'separator' => '" . $conf->separator . "'," . PHP_EOL;
		}

		// Label block
		if ( in_array( $type, [ 'text', 'textarea', 'wysiwyg', 'select', 'select2', 'url', 'code', 'slider', 'dimensions' ], true ) ) {
			$code .= "\t\t\t\t'label_block' => true," . PHP_EOL;
		}

		return $code;
	}

	/**
	 * Generate default value code.
	 */
	private static function generate_default( $conf, $type ) {
		$default = $conf->default;

		if ( $type === 'media' ) {
			$url = is_object( $default ) && isset( $default->url ) ? $default->url : (string) $default;
			$code  = "\t\t\t\t'default' => [" . PHP_EOL;
			$code .= "\t\t\t\t\t'url' => '" . esc_url( $url ) . "'," . PHP_EOL;
			$code .= "\t\t\t\t]," . PHP_EOL;
			return $code;
		}

		if ( $type === 'slider' ) {
			if ( is_object( $default ) ) {
				$unit = isset( $default->unit ) ? self::escape_string( $default->unit ) : 'px';
				$size = isset( $default->size ) ? floatval( $default->size ) : 0;
			} else {
				$unit = 'px';
				$size = floatval( $default );
			}
			$code  = "\t\t\t\t'default' => [" . PHP_EOL;
			$code .= "\t\t\t\t\t'unit' => '" . $unit . "'," . PHP_EOL;
			$code .= "\t\t\t\t\t'size' => " . $size . "," . PHP_EOL;
			$code .= "\t\t\t\t]," . PHP_EOL;
			return $code;
		}

		return "\t\t\t\t'default' => '" . self::escape_string( (string) $default ) . "'," . PHP_EOL;
	}

	/**
	 * Generate options code for select/choose controls.
	 */
	private static function generate_options( $conf, $type ) {
		$options = (array) $conf->options;
		$code    = "\t\t\t\t'options' => [" . PHP_EOL;

		if ( $type === 'choose' ) {
			foreach ( $options as $key => $label ) {
				$key = self::escape_string( (string) $key );
				if ( is_object( $label ) || is_array( $label ) ) {
					$label = (array) $label;
					$title = isset( $label['title'] ) ? self::escape_string( $label['title'] ) : $key;
					$icon  = isset( $label['icon'] ) ? self::escape_string( $label['icon'] ) : '';
				} else {
					$title = self::escape_string( (string) $label );
					$icon  = '';
				}
				$code .= "\t\t\t\t\t'" . $key . "' => [" . PHP_EOL;
				$code .= "\t\t\t\t\t\t'title' => esc_html__( '" . $title . "', 'wpr-addons' )," . PHP_EOL;
				$code .= "\t\t\t\t\t\t'icon' => '" . $icon . "'," . PHP_EOL;
				$code .= "\t\t\t\t\t]," . PHP_EOL;
			}
		} else {
			foreach ( $options as $key => $label ) {
				$key   = self::escape_string( (string) $key );
				$label = self::escape_string( (string) $label );
				$code .= "\t\t\t\t\t'" . $key . "' => esc_html__( '" . $label . "', 'wpr-addons' )," . PHP_EOL;
			}
		}

		$code .= "\t\t\t\t]," . PHP_EOL;
		return $code;
	}

	/**
	 * Generate slider-specific args.
	 */
	private static function generate_slider_args( $conf ) {
		$code = '';
		$no_units = ! empty( $conf->no_units );

		$min  = isset( $conf->slider_min ) && $conf->slider_min !== '' ? floatval( $conf->slider_min ) : 0;
		$max  = isset( $conf->slider_max ) && $conf->slider_max !== '' ? floatval( $conf->slider_max ) : 1000;
		$step = isset( $conf->slider_step ) && $conf->slider_step !== '' ? floatval( $conf->slider_step ) : 1;

		if ( $no_units ) {
			// No unit selector — plain numeric slider
			$code .= "\t\t\t\t'size_units' => [ 'px' ]," . PHP_EOL;
			$code .= "\t\t\t\t'range' => [" . PHP_EOL;
			$code .= "\t\t\t\t\t'px' => [" . PHP_EOL;
			$code .= "\t\t\t\t\t\t'min' => " . $min . "," . PHP_EOL;
			$code .= "\t\t\t\t\t\t'max' => " . $max . "," . PHP_EOL;
			$code .= "\t\t\t\t\t\t'step' => " . $step . "," . PHP_EOL;
			$code .= "\t\t\t\t\t]," . PHP_EOL;
			$code .= "\t\t\t\t]," . PHP_EOL;
		} else {
			// Size units
			$code .= "\t\t\t\t'size_units' => [ 'px', 'em', '%' ]," . PHP_EOL;

			// Range
			$code .= "\t\t\t\t'range' => [" . PHP_EOL;
			$code .= "\t\t\t\t\t'px' => [" . PHP_EOL;
			$code .= "\t\t\t\t\t\t'min' => " . $min . "," . PHP_EOL;
			$code .= "\t\t\t\t\t\t'max' => " . $max . "," . PHP_EOL;
			$code .= "\t\t\t\t\t\t'step' => " . $step . "," . PHP_EOL;
			$code .= "\t\t\t\t\t]," . PHP_EOL;
			$code .= "\t\t\t\t\t'%' => [" . PHP_EOL;
			$code .= "\t\t\t\t\t\t'min' => 0," . PHP_EOL;
			$code .= "\t\t\t\t\t\t'max' => 100," . PHP_EOL;
			$code .= "\t\t\t\t\t]," . PHP_EOL;
			$code .= "\t\t\t\t\t'em' => [" . PHP_EOL;
			$code .= "\t\t\t\t\t\t'min' => 0," . PHP_EOL;
			$code .= "\t\t\t\t\t\t'max' => 100," . PHP_EOL;
			$code .= "\t\t\t\t\t]," . PHP_EOL;
			$code .= "\t\t\t\t]," . PHP_EOL;
		}

		return $code;
	}

	/**
	 * Generate dimensions-specific args.
	 */
	private static function generate_dimensions_args( $conf ) {
		$code = '';

		$code .= "\t\t\t\t'size_units' => [ 'px', 'em', '%' ]," . PHP_EOL;

		// Allowed dimensions
		if ( ! empty( $conf->allowed_dimensions ) ) {
			$mode = $conf->allowed_dimensions;
			if ( $mode === 'vertical' ) {
				$code .= "\t\t\t\t'allowed_dimensions' => [ 'top', 'bottom' ]," . PHP_EOL;
			} elseif ( $mode === 'horizontal' ) {
				$code .= "\t\t\t\t'allowed_dimensions' => [ 'right', 'left' ]," . PHP_EOL;
			}
		}

		if ( ! empty( $conf->selectors ) ) {
			return $code;
		}

		// Default selectors for dimensions
		$code .= "\t\t\t\t'selectors' => [" . PHP_EOL;
		$code .= "\t\t\t\t\t'{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'," . PHP_EOL;
		$code .= "\t\t\t\t]," . PHP_EOL;

		return $code;
	}

	/**
	 * Generate selectors code.
	 */
	private static function generate_selectors( $conf, $type = '' ) {
		$selectors = (array) $conf->selectors;
		$code      = "\t\t\t\t'selectors' => [" . PHP_EOL;

		foreach ( $selectors as $selector => $value ) {
			$value = (string) $value;

			// Auto-convert {{VALUE}} for controls that use different placeholders
			if ( $type === 'slider' && strpos( $value, '{{VALUE}}' ) !== false ) {
				$no_units_check = ! empty( $conf->no_units );
				$value = str_replace( '{{VALUE}}{{UNIT}}', '{{VALUE}}', $value );
				$value = str_replace( '{{VALUE}}', $no_units_check ? '{{SIZE}}' : '{{SIZE}}{{UNIT}}', $value );
			}

			// Auto-append {{UNIT}} to bare {{SIZE}} (unless units are disabled)
			$no_units = ! empty( $conf->no_units );
			if ( $type === 'slider' && ! $no_units && strpos( $value, '{{SIZE}}' ) !== false ) {
				$value = preg_replace( '/\{\{SIZE\}\}(?!\{\{UNIT\}\})/', '{{SIZE}}{{UNIT}}', $value );
			}

			if ( $type === 'dimensions' && strpos( $value, '{{VALUE}}' ) !== false ) {
				$value = str_replace( '{{VALUE}}{{UNIT}}', '{{VALUE}}', $value );
				$value = str_replace( '{{VALUE}}', '{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}', $value );
			}

			// Auto-append {{UNIT}} to bare dimension placeholders
			if ( $type === 'dimensions' ) {
				foreach ( [ 'TOP', 'RIGHT', 'BOTTOM', 'LEFT' ] as $dir ) {
					// Match {{DIR}} not already followed by {{UNIT}}
					$value = preg_replace( '/\{\{' . $dir . '\}\}(?!\{\{UNIT\}\})/', '{{' . $dir . '}}{{UNIT}}', $value );
				}
			}

			$selector = str_replace( ',', ', {{WRAPPER}} ', $selector );
			$code    .= "\t\t\t\t\t'{{WRAPPER}} " . self::escape_string( $selector ) . "' => '" . self::escape_string( $value ) . "'," . PHP_EOL;
		}

		$code .= "\t\t\t\t]," . PHP_EOL;
		return $code;
	}

	/**
	 * Escape a string for safe use in generated PHP code.
	 * Prevents PHP injection by escaping quotes and removing PHP tags.
	 */
	public static function escape_string( $str ) {
		// Remove any PHP tags that could break out of strings
		$str = str_replace( [ '<?php', '<?=', '<?', '?>' ], '', $str );
		// Escape backslashes and single quotes
		return addcslashes( $str, "\\'" );
	}
}
