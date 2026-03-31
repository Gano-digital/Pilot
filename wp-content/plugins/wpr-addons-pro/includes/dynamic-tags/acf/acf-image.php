<?php
use Elementor\Controls_Manager;
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Data_Tag as Data_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_ACF_Image extends Data_Tag {

	public function get_name() {
		return 'wpr-acf-image';
	}

	public function get_title() {
		return __( 'Custom Field Image', 'wpr-addons' );
	}

	public function get_group() {
		return 'wpr_addons_acf';
	}

	public function get_categories() {
		return [
			'image'
		];
	}

	public function is_settings_required() {
		return true;
	}

	protected function register_controls() {
		Wpr_Dynamic_Tags_Module::add_field_select_control( $this );

		$this->add_control(
			'fallback',
			[
				'label' => esc_html__( 'Fallback', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
			]
		);
	}

	public function get_supported_fields() {
		return [
			'image'
		];
	}

	public function get_value( array $options = [] ) {
		$field_key = $this->get_settings( 'wpr_acf_field' );

		if ( empty( $field_key ) ) {
			return;
		}

		// Get Custom Field Object Data
		$field_object = Wpr_Dynamic_Tags_Module::get_field_data( $this );
		$field_type = $field_object['type'];
		$value = $field_object['value'];

		$data = [
			'id' => null,
			'url' => '',
		];

        if ( 'array' === $field_object['return_format'] ) {
            $data = [
                'id' => $value['id'],
                'url' => $value['url'],
            ];
        } elseif ( 'url' === $field_object['return_format'] ) {
            $data = [
                'id' => 0,
                'url' => $value,
            ];
        } else {
            $src = wp_get_attachment_image_src( $value, $field_object['preview_size'] );
            $data = [
                'id' => is_array( $value ) ? $value['id'] : $value,
                'url' => $src[0],
            ];
        }

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );

            $data = [
                'id' => $value['id'],
                'url' => $value['url'],
            ];
		}

		return $data;
	}
}
