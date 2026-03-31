<?php
namespace WprAddons\Modules\WidgetBuilder\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates Elementor widget PHP class files from user-defined widget configurations.
 * Also writes style.css and script.js files.
 */
class WidgetWriter {

	private $widget_id;
	private $widget_data;
	private $name_prefix  = 'wpr_wb_';
	private $class_prefix = 'Wpr_Wb_';

	public function __construct( $widget_data, $widget_id ) {
		$this->widget_data = (object) $widget_data;
		$this->widget_id   = $widget_id;
	}

	/**
	 * Generate all widget files and write them to disk.
	 *
	 * @return bool|WP_Error
	 */
	public function generate() {
		$dir = $this->get_widget_dir();

		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}

		// Protect the custom widgets base directory from direct access
		$base_dir = dirname( $dir ) . '/';
		$htaccess = $base_dir . '.htaccess';
		if ( ! file_exists( $htaccess ) ) {
			$this->write_file( $htaccess, "Options -Indexes\n<Files *.php>\nOrder Deny,Allow\nDeny from all\n</Files>" );
		}

		$index = $base_dir . 'index.php';
		if ( ! file_exists( $index ) ) {
			$this->write_file( $index, '<?php // Silence is golden.' );
		}

		// Write CSS file
		$has_css = $this->write_file( $dir . 'style.css', isset( $this->widget_data->css ) ? $this->widget_data->css : '' );

		// Write JS file
		$has_js = $this->write_file( $dir . 'script.js', isset( $this->widget_data->js ) ? $this->widget_data->js : '' );

		// Generate and write widget.php
		$php_content = $this->generate_widget_php( $has_css, $has_js );

		return $this->write_file( $dir . 'widget.php', $php_content );
	}

	/**
	 * Delete all widget files.
	 */
	public static function delete_widget( $widget_id ) {
		$upload = wp_upload_dir();
		$dir    = $upload['basedir'] . '/wpr-addons/custom_widgets/wpr_wb_' . $widget_id . '/';

		if ( is_dir( $dir ) ) {
			$files = glob( $dir . '*' );
			if ( $files ) {
				foreach ( $files as $file ) {
					if ( is_file( $file ) ) {
						wp_delete_file( $file );
					}
				}
			}
			rmdir( $dir );
		}
	}

	/**
	 * Get the upload directory path for this widget.
	 */
	private function get_widget_dir() {
		$upload = wp_upload_dir();
		return $upload['basedir'] . '/wpr-addons/custom_widgets/' . $this->name_prefix . $this->widget_id . '/';
	}

	/**
	 * Get the upload URL path for this widget.
	 */
	private function get_widget_url() {
		$upload = wp_upload_dir();
		return $upload['baseurl'] . '/wpr-addons/custom_widgets/' . $this->name_prefix . $this->widget_id;
	}

	/**
	 * Write content to a file if it's not empty.
	 *
	 * @return bool True if file was written with content.
	 */
	private function write_file( $path, $content ) {
		$content = is_string( $content ) ? trim( $content ) : '';

		if ( empty( $content ) ) {
			// Remove old file if content is now empty
			if ( file_exists( $path ) ) {
				wp_delete_file( $path );
			}
			return false;
		}

		// Use WP_Filesystem for compatibility
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
			WP_Filesystem();
		}

		$wp_filesystem->put_contents( $path, $content, FS_CHMOD_FILE );
		return true;
	}

	/**
	 * Generate the complete widget PHP class file content.
	 */
	private function generate_widget_php( $has_css, $has_js ) {
		$widget_name  = $this->name_prefix . $this->widget_id;
		$class_name   = $this->class_prefix . $this->widget_id;
		$handle       = 'wpr-wb-' . $this->widget_id;
		$url          = $this->get_widget_url();
		$title        = isset( $this->widget_data->title ) ? $this->widget_data->title : 'Custom Widget';
		$icon         = isset( $this->widget_data->icon ) ? $this->widget_data->icon : 'eicon-cog';
		$categories   = ! empty( $this->widget_data->categories ) ? (array) $this->widget_data->categories : [ 'basic' ];
		$tabs         = isset( $this->widget_data->tabs ) ? $this->widget_data->tabs : new \stdClass();
		$markup       = isset( $this->widget_data->markup ) ? $this->widget_data->markup : '';
		$css_includes = ! empty( $this->widget_data->css_includes ) ? (array) $this->widget_data->css_includes : [];
		$js_includes  = ! empty( $this->widget_data->js_includes ) ? (array) $this->widget_data->js_includes : [];

		$has_assets = $has_css || $has_js || ! empty( $css_includes ) || ! empty( $js_includes );

		// Start building PHP
		$php  = '<?php' . PHP_EOL . PHP_EOL;
		$php .= 'namespace Elementor;' . PHP_EOL . PHP_EOL;
		$php .= "defined('ABSPATH') || exit;" . PHP_EOL . PHP_EOL;
		$php .= 'class ' . $class_name . ' extends Widget_Base {' . PHP_EOL . PHP_EOL;

		// Constructor (if assets needed)
		if ( $has_assets ) {
			$php .= $this->build_constructor( $handle, $url, $has_css, $has_js, $css_includes, $js_includes );
		}

		// get_name()
		$php .= "\t" . 'public function get_name() {' . PHP_EOL;
		$php .= "\t\t" . "return '" . ControlFactory::escape_string( $widget_name ) . "';" . PHP_EOL;
		$php .= "\t" . '}' . PHP_EOL . PHP_EOL;

		// get_title()
		$php .= "\t" . 'public function get_title() {' . PHP_EOL;
		$php .= "\t\t" . "return esc_html__( '" . ControlFactory::escape_string( $title ) . "', 'wpr-addons' );" . PHP_EOL;
		$php .= "\t" . '}' . PHP_EOL . PHP_EOL;

		// get_icon()
		$php .= "\t" . 'public function get_icon() {' . PHP_EOL;
		$php .= "\t\t" . "return '" . ControlFactory::escape_string( $icon ) . "';" . PHP_EOL;
		$php .= "\t" . '}' . PHP_EOL . PHP_EOL;

		// get_categories()
		$cat_str = "'" . implode( "', '", array_map( [ ControlFactory::class, 'escape_string' ], $categories ) ) . "'";
		$php    .= "\t" . 'public function get_categories() {' . PHP_EOL;
		$php    .= "\t\t" . "return [ " . $cat_str . " ];" . PHP_EOL;
		$php    .= "\t" . '}' . PHP_EOL . PHP_EOL;

		// get_style_depends()
		if ( $has_css ) {
			$php .= "\t" . 'public function get_style_depends() {' . PHP_EOL;
			$php .= "\t\t" . "return [ '" . $handle . "-style' ];" . PHP_EOL;
			$php .= "\t" . '}' . PHP_EOL . PHP_EOL;
		}

		// get_script_depends()
		if ( $has_js ) {
			$php .= "\t" . 'public function get_script_depends() {' . PHP_EOL;
			$php .= "\t\t" . "return [ '" . $handle . "-script' ];" . PHP_EOL;
			$php .= "\t" . '}' . PHP_EOL . PHP_EOL;
		}

		// register_controls()
		$php .= $this->build_register_controls( $tabs );

		// render()
		$code_keys = $this->collect_control_keys_by_type( $tabs, 'code' );
		$php .= $this->build_render_method( $markup, $code_keys );

		// Close class
		$php .= '}' . PHP_EOL;

		return $php;
	}

	/**
	 * Build the __construct method for asset registration.
	 */
	private function build_constructor( $handle, $url, $has_css, $has_js, $css_includes, $js_includes ) {
		$php  = "\t" . 'public function __construct($data = [], $args = null) {' . PHP_EOL;
		$php .= "\t\t" . 'parent::__construct($data, $args);' . PHP_EOL;

		if ( $has_css ) {
			$php .= "\t\t" . "wp_register_style( '" . $handle . "-style', '" . $url . "/style.css' );" . PHP_EOL;
		}

		if ( $has_js ) {
			$php .= "\t\t" . "wp_register_script( '" . $handle . "-script', '" . $url . "/script.js', [ 'elementor-frontend' ], '1.0.0', true );" . PHP_EOL;
		}

		foreach ( $css_includes as $idx => $css_url ) {
			$php .= "\t\t" . "wp_enqueue_style( '" . $handle . "-ext-css-" . $idx . "', '" . esc_url( $css_url ) . "' );" . PHP_EOL;
		}

		foreach ( $js_includes as $idx => $js_url ) {
			$php .= "\t\t" . "wp_enqueue_script( '" . $handle . "-ext-js-" . $idx . "', '" . esc_url( $js_url ) . "', [ 'elementor-frontend' ], '1.0.0', true );" . PHP_EOL;
		}

		$php .= "\t" . '}' . PHP_EOL . PHP_EOL;
		return $php;
	}

	/**
	 * Build register_controls() method.
	 *
	 * Data format: tabs.content = [ { key, label, controls: [ {type, key, label, ...} ] } ]
	 */
	private function build_register_controls( $tabs ) {
		$tabs = (object) $tabs;

		$php = "\t" . 'protected function register_controls() {' . PHP_EOL;

		$tab_map = [
			'content'  => 'Controls_Manager::TAB_CONTENT',
			'style'    => 'Controls_Manager::TAB_STYLE',
			'advanced' => 'Controls_Manager::TAB_ADVANCED',
		];

		foreach ( $tab_map as $tab_key => $tab_const ) {
			$sections = isset( $tabs->$tab_key ) ? (array) $tabs->$tab_key : [];

			if ( empty( $sections ) ) {
				continue;
			}

			foreach ( $sections as $section ) {
				$section    = (object) $section;
				$section_id = $tab_key . '_' . ( ! empty( $section->key ) ? sanitize_key( $section->key ) : 'section' );
				$label      = ! empty( $section->label ) ? $section->label : 'Section';
				$controls   = ! empty( $section->controls ) ? (array) $section->controls : [];

				if ( empty( $controls ) ) {
					continue;
				}

				// start_controls_section
				$php .= PHP_EOL;
				$php .= "\t\t" . '$this->start_controls_section(' . PHP_EOL;
				$php .= "\t\t\t'" . $section_id . "'," . PHP_EOL;
				$php .= "\t\t\t[" . PHP_EOL;
				$php .= "\t\t\t\t'label' => esc_html__( '" . ControlFactory::escape_string( $label ) . "', 'wpr-addons' )," . PHP_EOL;
				$php .= "\t\t\t\t'tab' => " . $tab_const . "," . PHP_EOL;
				$php .= "\t\t\t]" . PHP_EOL;
				$php .= "\t\t);" . PHP_EOL;

				// Add controls
				foreach ( $controls as $control ) {
					$php .= PHP_EOL;
					$php .= ControlFactory::generate_control_code( $control );
				}

				// end_controls_section
				$php .= PHP_EOL;
				$php .= "\t\t" . '$this->end_controls_section();' . PHP_EOL;
			}
		}

		$php .= "\t" . '}' . PHP_EOL . PHP_EOL;
		return $php;
	}

	/**
	 * Build the render() method with template tag replacement.
	 */
	private function build_render_method( $markup, $code_keys = [] ) {
		$php = "\t" . 'protected function render() {' . PHP_EOL;

		if ( ! empty( $markup ) ) {
			// Replace template tags
			$markup = $this->replace_template_tags( $markup, $code_keys );

			$php .= "\t\t" . '$settings = $this->get_settings_for_display();' . PHP_EOL . PHP_EOL;
			$php .= "\t\t" . '?>' . PHP_EOL;
			$php .= $markup . PHP_EOL;
			$php .= "\t\t" . '<?php' . PHP_EOL;
		}

		$php .= "\t" . '}' . PHP_EOL . PHP_EOL;
		return $php;
	}

	/**
	 * Replace {{tags}} in markup with PHP echo statements.
	 *
	 * Supported formats:
	 *   {{key}}          -> Simple value output
	 *   {{key.subkey}}   -> Nested array access (e.g., image.url)
	 *   {{icon(key)}}    -> Elementor icon rendering
	 */
	private function replace_template_tags( $markup, $code_keys = [] ) {
		// Step 1: Process conditional blocks {{#if key}}, {{#if key == value}}, {{else}}, {{/if}}
		$markup = $this->replace_conditionals( $markup );

		// Step 2: Process value tags {{key}}, {{key.subkey}}, {{icon(key)}}
		return preg_replace_callback(
			'/\{\{([^{}]+)\}\}/',
			function( $matches ) use ( $code_keys ) {
				$tag = trim( $matches[1] );

				// Check for function call: icon(key)
				$fn_parts = explode( '(', $tag );
				if ( count( $fn_parts ) === 2 ) {
					$method = trim( $fn_parts[0] );
					$arg    = trim( rtrim( $fn_parts[1], ')' ) );

					if ( $method === 'icon' ) {
						return '<?php \Elementor\Icons_Manager::render_icon( $settings[\'' . ControlFactory::escape_string( $arg ) . '\'] ); ?>';
					}
				}

				// Check for dot notation: key.subkey
				$parts = explode( '.', $tag );
				$access = '';
				foreach ( $parts as $part ) {
					$access .= '[\'' . ControlFactory::escape_string( trim( $part ) ) . '\']';
				}

				// Code controls: escape output and wrap in <pre><code>
				$root_key = ControlFactory::escape_string( trim( $parts[0] ) );
				if ( in_array( $root_key, $code_keys, true ) ) {
					return '<?php echo \'<pre><code>\' . esc_html( isset($settings' . $access . ') ? $settings' . $access . ' : \'\' ) . \'</code></pre>\'; ?>';
				}

				return '<?php $__v = isset($settings' . $access . ') ? $settings' . $access . ' : \'\'; if (is_array($__v)) { if (isset($__v[\'url\'])) { echo esc_url($__v[\'url\']); } elseif (isset($__v[0])) { foreach ($__v as $__item) { $__url = esc_url($__item[\'url\']); $__ext = strtolower(pathinfo($__url, PATHINFO_EXTENSION)); if (in_array($__ext, [\'mp4\',\'webm\',\'ogg\',\'mov\'])) { echo \'<video src="\' . $__url . \'" controls></video>\'; } elseif (in_array($__ext, [\'mp3\',\'wav\',\'ogg\',\'flac\'])) { echo \'<audio src="\' . $__url . \'" controls></audio>\'; } else { echo \'<img src="\' . $__url . \'">\'; } } } } else { echo wp_kses_post($__v); } ?>';
			},
			$markup
		);
	}

	/**
	 * Replace conditional template tags with PHP if/else blocks.
	 *
	 * Supported syntax:
	 *   {{#if key}}         — truthy check (non-empty, not null)
	 *   {{#if key == val}}  — equality check
	 *   {{#if key != val}}  — inequality check
	 *   {{else}}            — else branch
	 *   {{/if}}             — end block
	 */
	private function replace_conditionals( $markup ) {
		// {{/if}}
		$markup = str_replace( '{{/if}}', '<?php endif; ?>', $markup );

		// {{else}}
		$markup = str_replace( '{{else}}', '<?php else: ?>', $markup );

		// {{#if key == value}} or {{#if key != value}}
		$markup = preg_replace_callback(
			'/\{\{#if\s+([a-zA-Z0-9_.]+)\s*(==|!=)\s*(.+?)\}\}/',
			function( $m ) {
				$key = ControlFactory::escape_string( trim( $m[1] ) );
				$op  = $m[2];
				$val = ControlFactory::escape_string( trim( $m[3], " \t\"'" ) );
				return '<?php if ( isset($settings[\'' . $key . '\']) && $settings[\'' . $key . '\'] ' . $op . ' \'' . $val . '\' ): ?>';
			},
			$markup
		);

		// {{#if key}} — truthy check
		$markup = preg_replace_callback(
			'/\{\{#if\s+([a-zA-Z0-9_.]+)\}\}/',
			function( $m ) {
				$key = ControlFactory::escape_string( trim( $m[1] ) );
				return '<?php if ( ! empty($settings[\'' . $key . '\']) ): ?>';
			},
			$markup
		);

		return $markup;
	}

	/**
	 * Collect control keys of a specific type from all tabs.
	 */
	private function collect_control_keys_by_type( $tabs, $type ) {
		$tabs = (object) $tabs;
		$keys = [];

		foreach ( [ 'content', 'style', 'advanced' ] as $tab_key ) {
			$sections = isset( $tabs->$tab_key ) ? (array) $tabs->$tab_key : [];
			foreach ( $sections as $section ) {
				$section  = (object) $section;
				$controls = ! empty( $section->controls ) ? (array) $section->controls : [];
				foreach ( $controls as $control ) {
					$control = (object) $control;
					if ( ! empty( $control->type ) && $control->type === $type && ! empty( $control->key ) ) {
						$keys[] = sanitize_key( $control->key );
					}
				}
			}
		}

		return $keys;
	}
}
