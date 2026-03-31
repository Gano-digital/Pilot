<?php
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Tag as Base_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_ACF_Text extends Base_Tag {

	public function get_name() {
		return 'wpr-acf-text';
	}

	public function get_title() {
		return __( 'Custom Field', 'wpr-addons' );
	}

	public function get_group() {
		return 'wpr_addons_acf';
	}

	public function get_categories() {
		return [
			'text',
			'post_meta',
			'datetime'
		];
	}

	public function is_settings_required() {
		return true;
	}

	protected function register_controls() {
		Wpr_Dynamic_Tags_Module::add_field_select_control( $this );
	}

	public function get_supported_fields() {
		return [
			'text',
			'textarea',
			'number',
			'email',
			'password',
			'wysiwyg',
			'select',
			'checkbox',
			'radio',
			'true_false',
			'oembed',
			'google_map',
			'date_picker',
			'time_picker',
			'date_time_picker',
			'color_picker',
			'button_group',
		];
	}

	private function get_queried_object_meta( $meta_key ) {
		$value = '';

		if ( is_singular() ) {
			$value = get_post_meta( get_the_ID(), $meta_key, true );
		} elseif ( is_tax() || is_category() || is_tag() ) {
			$value = get_term_meta( get_queried_object_id(), $meta_key, true );
		}

		return $value;
	}

	public function render() {
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

		if ( 'select' === $field_type ) {
			if ( $field_object['multiple'] ) {
				$value = implode( ', ', $value );
			}			
		} elseif ( 'checkbox' === $field_type ) {
			$value = implode( ', ', $value );
		} elseif ( 'oembed' === $field_type ) {
			$value = $this->get_queried_object_meta($field_key);
		} else {
			$value = get_field( $field_key, get_queried_object_id(), true );
		}
		
		echo wp_kses_post( $value );
	}
}
