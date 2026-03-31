<?php
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Data_Tag as Data_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_ACF_URL extends Data_Tag {

	public function get_name() {
		return 'wpr-acf-url';
	}

	public function get_title() {
		return __( 'Custom Field URL', 'wpr-addons' );
	}

	public function get_group() {
		return 'wpr_addons_acf';
	}

	public function get_categories() {
		return [
			'url',
			'post_meta'
		];
	}

	protected function register_controls() {
		Wpr_Dynamic_Tags_Module::add_field_select_control( $this );
	}

	public function is_settings_required() {
		return true;
	}

	public function get_supported_fields() {
		return [
			'url',
			'link',
			'text',
			'email',
			'image',
			'file',
			'page_link',
			'post_object',
			'relationship',
			'taxonomy'
		];
	}

	public function get_value( array $options = [] ) {
		$field_key = $this->get_settings( 'wpr_acf_field' );

		if ( empty( $field_key ) ) {
			return;
		}

		// Get Custom Field Object Data
		$field_object = Wpr_Dynamic_Tags_Module::get_field_data( $this );

		if ( empty( $field_object ) ) {
			return;
		}

		$field_type = $field_object['type'];
		$value = $field_object['value'];

		if ( is_array( $value ) && isset( $value[0] ) ) {
			$value = $value[0];
		}

		if ( 'link' === $field_type ) {
			if ( 'array' === $field_object['return_format'] ) {
				if ( is_array($value) && isset( $value['url'] ) ) {
					$value = $value['url'];
				}
            }
        } elseif ( 'email' === $field_type ) {
            $value = 'mailto:'. $value;
        } elseif ( 'image' === $field_type || 'file' === $field_type ) {
            if ( 'array' === $field_object['return_format'] ) {
                $value = $value['url'];
            } elseif ( 'id' === $field_object['return_format'] ) {
                if ( 'image' === $field_object['type'] ) {
                    $src = wp_get_attachment_image_src( $value, 'full' );
                    $value = $src[0];
                } else {
                    $value = wp_get_attachment_url( $value );
                }
            }
        } elseif ( 'post_object' === $field_type || 'relationship' === $field_type ) {
            $value = get_permalink( $value );
        } elseif ( 'taxonomy' === $field_type ) {
            $value = get_term_link( $value );
        } else {
            $value = get_field( $field_key, get_queried_object_id(), true );
        }

		return wp_kses_post( $value );
	}
}
