<?php
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Data_Tag as Data_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_ACF_Color extends Data_Tag {

	public function get_name() {
		return 'wpr-acf-color';
	}

	public function get_title() {
		return __( 'Custom Field Color', 'wpr-addons' );
	}

	public function get_group() {
		return 'wpr_addons_acf';
	}

	public function get_categories() {
		return [
			'color'
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
			'color_picker'
		];
	}

	public function get_value( array $options = [] ) {
		$field_key = $this->get_settings( 'wpr_acf_field' );

		if ( empty( $field_key ) ) {
			return;
		}

		$value = get_field( $field_key, get_queried_object_id(), true );

		if ( empty( $value ) && $this->get_settings( 'fallback' ) ) {
			$value = $this->get_settings( 'fallback' );
		}

		return $value;
	}
}
