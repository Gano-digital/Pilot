<?php
use Elementor\Controls_Manager;
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Tag as Base_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_Custom_Field extends Base_Tag {

	public function get_name() {
		return 'wpr-custom-field';
	}

	public function get_title() {
		return __( 'Custom Field', 'wpr-addons' );
	}

	public function get_group() {
		return 'wpr_addons_custom_field';
	}

	public function get_categories() {
		return [
			'text',
			'post_meta',
            'url',
            'color'
		];
	}

	public function is_settings_required() {
		return true;
	}

	protected function register_controls() {
		$this->add_control(
			'wpr_custom_field',
			[
				'label' => esc_html__( 'Select Field', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->get_custom_keys_array(),
			]
		);
	}

	private function get_custom_keys_array() {
		$custom_keys = get_post_custom_keys();
		$options = [];

		if ( ! empty( $custom_keys ) ) {
			foreach ( $custom_keys as $custom_key ) {
				if ( '_' !== substr( $custom_key, 0, 1 ) ) {
					$options[ $custom_key ] = $custom_key;
				}
			}
		}

		return $options;
	}

	public function render() {
		$field_key = $this->get_settings( 'wpr_custom_field' );

		if ( empty( $field_key ) ) {
			return;
		}

		$value = get_post_meta( get_the_ID(), $field_key, true );

		echo wp_kses_post( $value );
	}
}
