<?php
namespace WprAddons\Modules\WidgetBuilder\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Endpoints {

	private $namespace = 'wpr-addons/v1';
	private $base      = 'widget-builder';

	public function __construct() {
		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->base . '/save/(?P<id>[\d]+)', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'save_widget' ],
			'permission_callback' => [ $this, 'check_permissions' ],
			'args'                => [
				'id' => [
					'validate_callback' => function( $param ) {
						return is_numeric( $param );
					},
				],
			],
		]);

		register_rest_route( $this->namespace, '/' . $this->base . '/save', [
			'methods'             => 'POST',
			'callback'            => [ $this, 'save_widget' ],
			'permission_callback' => [ $this, 'check_permissions' ],
		]);

		register_rest_route( $this->namespace, '/' . $this->base . '/load/(?P<id>[\d]+)', [
			'methods'             => 'GET',
			'callback'            => [ $this, 'load_widget' ],
			'permission_callback' => [ $this, 'check_permissions' ],
			'args'                => [
				'id' => [
					'validate_callback' => function( $param ) {
						return is_numeric( $param );
					},
				],
			],
		]);

		register_rest_route( $this->namespace, '/' . $this->base . '/delete/(?P<id>[\d]+)', [
			'methods'             => 'DELETE',
			'callback'            => [ $this, 'delete_widget' ],
			'permission_callback' => [ $this, 'check_permissions' ],
			'args'                => [
				'id' => [
					'validate_callback' => function( $param ) {
						return is_numeric( $param );
					},
				],
			],
		]);
	}

	public function check_permissions() {
		return current_user_can( 'manage_options' );
	}

	public function save_widget( $request ) {
		$id   = $request->get_param( 'id' );
		$body = $request->get_json_params();

		if ( empty( $body ) ) {
			return new \WP_REST_Response([
				'success' => false,
				'message' => esc_html__( 'Invalid data.', 'wpr-addons' ),
			], 400 );
		}

		$title = ! empty( $body['title'] ) ? sanitize_text_field( $body['title'] ) : 'Custom Widget #' . time();

		$post_data = [
			'post_title'  => $title,
			'post_status' => 'publish',
			'post_type'   => 'wpr_custom_widget',
		];

		// Update existing or create new
		$existing = $id ? get_post( $id ) : null;
		if ( $existing && $existing->post_type === 'wpr_custom_widget' ) {
			$post_data['ID'] = $id;
			wp_update_post( $post_data );
		} else {
			$id = wp_insert_post( $post_data );
			if ( is_wp_error( $id ) ) {
				return new \WP_REST_Response([
					'success' => false,
					'message' => $id->get_error_message(),
				], 500 );
			}
		}

		// Build the widget data to store
		$category = ! empty( $body['category'] ) ? sanitize_text_field( $body['category'] ) : 'wpr-widgets';

		$widget_data = [
			'title'        => $title,
			'icon'         => ! empty( $body['icon'] ) ? sanitize_text_field( $body['icon'] ) : 'eicon-cog',
			'categories'   => [ $category ],
			'push_id'      => $id,
			'markup'       => isset( $body['markup'] ) ? str_replace( [ '<?php', '<?=', '<?', '?>' ], '', $body['markup'] ) : '',
			'css'          => isset( $body['css'] ) ? wp_strip_all_tags( $body['css'] ) : '',
			'js'           => isset( $body['js'] ) ? str_replace( '</script>', '', $body['js'] ) : '',
			'css_includes' => ! empty( $body['css_includes'] ) ? array_map( 'esc_url_raw', (array) $body['css_includes'] ) : [],
			'js_includes'  => ! empty( $body['js_includes'] ) ? array_map( 'esc_url_raw', (array) $body['js_includes'] ) : [],
			'tabs'         => isset( $body['tabs'] ) ? $body['tabs'] : [
				'content'  => [],
				'style'    => [],
				'advanced' => [],
			],
		];

		// Sanitize control tabs data
		$widget_data['tabs'] = $this->sanitize_tabs( $widget_data['tabs'] );

		update_post_meta( $id, 'wpr_custom_widget_data', $widget_data );

		// Generate widget files (widget.php, style.css, script.js)
		$writer = new \WprAddons\Modules\WidgetBuilder\Controls\WidgetWriter( $widget_data, $id );
		$writer->generate();

		return new \WP_REST_Response([
			'success' => true,
			'message' => esc_html__( 'Widget saved successfully!', 'wpr-addons' ),
			'post_id' => $id,
		], 200 );
	}

	public function load_widget( $request ) {
		$id = intval( $request->get_param( 'id' ) );

		$post = get_post( $id );
		if ( ! $post || $post->post_type !== 'wpr_custom_widget' ) {
			return new \WP_REST_Response([
				'success' => false,
				'message' => esc_html__( 'Widget not found.', 'wpr-addons' ),
			], 404 );
		}

		$widget_data = get_post_meta( $id, 'wpr_custom_widget_data', true );

		if ( empty( $widget_data ) ) {
			$widget_data = [
				'title'        => $post->post_title ?: 'New Widget',
				'icon'         => 'eicon-cog',
				'categories'   => [ 'wpr-widgets' ],
				'push_id'      => $id,
				'markup'       => '',
				'css'          => '',
				'js'           => '',
				'css_includes' => [],
				'js_includes'  => [],
				'tabs'         => [
					'content'  => [],
					'style'    => [],
					'advanced' => [],
				],
			];
		}

		return new \WP_REST_Response([
			'success' => true,
			'data'    => $widget_data,
		], 200 );
	}

	/**
	 * Sanitize the controls tabs data to prevent code injection in generated PHP.
	 *
	 * New format: tabs.content = [ { key, label, controls: [ {type, key, label, ...} ] } ]
	 */
	private function sanitize_tabs( $tabs ) {
		if ( ! is_array( $tabs ) ) {
			return [ 'content' => [], 'style' => [], 'advanced' => [] ];
		}

		$clean_tabs = [];
		foreach ( [ 'content', 'style', 'advanced' ] as $tab_key ) {
			$sections = isset( $tabs[ $tab_key ] ) ? (array) $tabs[ $tab_key ] : [];
			$clean_sections = [];

			foreach ( $sections as $section ) {
				$section = (array) $section;
				$clean_section = [
					'key'         => isset( $section['key'] ) ? sanitize_key( $section['key'] ) : '',
					'label'       => isset( $section['label'] ) ? sanitize_text_field( $section['label'] ) : 'Section',
					'description' => isset( $section['description'] ) ? sanitize_text_field( $section['description'] ) : '',
					'controls'    => [],
				];

				if ( empty( $clean_section['key'] ) ) {
					continue;
				}

				$controls = isset( $section['controls'] ) ? (array) $section['controls'] : [];
				foreach ( $controls as $control ) {
					$clean_ctrl = $this->sanitize_control( $control );
					if ( ! empty( $clean_ctrl['key'] ) ) {
						$clean_section['controls'][] = $clean_ctrl;
					}
				}

				$clean_sections[] = $clean_section;
			}

			$clean_tabs[ $tab_key ] = $clean_sections;
		}

		return $clean_tabs;
	}

	/**
	 * Sanitize a single control's data.
	 */
	private function sanitize_control( $control ) {
		$control = (array) $control;

		$clean = [
			'key'     => isset( $control['key'] ) ? sanitize_key( $control['key'] ) : '',
			'label'   => isset( $control['label'] ) ? sanitize_text_field( $control['label'] ) : '',
			'type'    => isset( $control['type'] ) ? sanitize_text_field( $control['type'] ) : 'text',
			'default' => isset( $control['default'] ) ? sanitize_text_field( $control['default'] ) : '',
		];

		// Selector (group controls)
		if ( ! empty( $control['selector'] ) ) {
			$clean['selector'] = sanitize_text_field( $control['selector'] );
		}

		// Separator
		if ( ! empty( $control['separator'] ) && in_array( $control['separator'], [ 'before', 'after' ], true ) ) {
			$clean['separator'] = $control['separator'];
		}

		// Options (select, select2, choose)
		if ( ! empty( $control['options'] ) && is_array( $control['options'] ) ) {
			$clean_options = [];
			foreach ( $control['options'] as $opt_key => $opt_value ) {
				$safe_key = sanitize_key( $opt_key );
				if ( is_array( $opt_value ) || is_object( $opt_value ) ) {
					$opt_value = (array) $opt_value;
					$clean_options[ $safe_key ] = [
						'title' => isset( $opt_value['title'] ) ? sanitize_text_field( $opt_value['title'] ) : $safe_key,
						'icon'  => isset( $opt_value['icon'] ) ? sanitize_text_field( $opt_value['icon'] ) : '',
					];
				} else {
					$clean_options[ $safe_key ] = sanitize_text_field( (string) $opt_value );
				}
			}
			$clean['options'] = $clean_options;
		}

		// Condition
		if ( ! empty( $control['condition'] ) && is_array( $control['condition'] ) ) {
			$cond = $control['condition'];
			if ( ! empty( $cond['key'] ) ) {
				$clean['condition'] = [
					'key'   => sanitize_key( $cond['key'] ),
					'value' => isset( $cond['value'] ) ? sanitize_text_field( $cond['value'] ) : '',
				];
			}
		}

		// Slider min/max
		if ( isset( $control['slider_min'] ) && $control['slider_min'] !== '' ) {
			$clean['slider_min'] = floatval( $control['slider_min'] );
		}
		if ( isset( $control['slider_max'] ) && $control['slider_max'] !== '' ) {
			$clean['slider_max'] = floatval( $control['slider_max'] );
		}
		if ( isset( $control['slider_step'] ) && $control['slider_step'] !== '' ) {
			$clean['slider_step'] = floatval( $control['slider_step'] );
		}

		// No units (slider)
		if ( ! empty( $control['no_units'] ) ) {
			$clean['no_units'] = true;
		}

		// Allowed dimensions
		if ( ! empty( $control['allowed_dimensions'] ) && in_array( $control['allowed_dimensions'], [ 'vertical', 'horizontal' ], true ) ) {
			$clean['allowed_dimensions'] = $control['allowed_dimensions'];
		}

		// Code language
		if ( ! empty( $control['language'] ) ) {
			$allowed_langs = [ 'html', 'css', 'sass', 'scss', 'javascript', 'json', 'less', 'markdown', 'php', 'python', 'mysql', 'sql', 'svg', 'text', 'twig', 'typescript' ];
			$lang = sanitize_text_field( $control['language'] );
			if ( in_array( $lang, $allowed_langs, true ) ) {
				$clean['language'] = $lang;
			}
		}

		// Selectors (CSS mapping)
		if ( ! empty( $control['selectors'] ) && is_array( $control['selectors'] ) ) {
			$clean_selectors = [];
			foreach ( $control['selectors'] as $sel_key => $sel_value ) {
				$clean_selectors[ sanitize_text_field( $sel_key ) ] = sanitize_text_field( (string) $sel_value );
			}
			$clean['selectors'] = $clean_selectors;
		}

		return $clean;
	}

	public function delete_widget( $request ) {
		$id = intval( $request->get_param( 'id' ) );

		$post = get_post( $id );
		if ( ! $post || $post->post_type !== 'wpr_custom_widget' ) {
			return new \WP_REST_Response([
				'success' => false,
				'message' => esc_html__( 'Widget not found.', 'wpr-addons' ),
			], 404 );
		}

		wp_delete_post( $id, true );

		return new \WP_REST_Response([
			'success' => true,
			'message' => esc_html__( 'Widget deleted successfully!', 'wpr-addons' ),
		], 200 );
	}
}
