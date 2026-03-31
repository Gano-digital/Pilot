<?php
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Tag as Base_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_ACF_Number extends Base_Tag {

	public function get_name() {
		return 'wpr-acf-number';
	}

	public function get_title() {
		return __( 'Custom Field Number', 'wpr-addons' );
	}

	public function get_group() {
		return 'wpr_addons_acf';
	}

	public function get_categories() {
		return [
			'number',
			'post_meta'
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
			'number',
			'range'
		];
	}

	public function render() {
		$field_key = $this->get_settings( 'wpr_acf_field' );

		if ( empty( $field_key ) ) {
			return;
		}

		$value = get_field( $field_key, get_queried_object_id(), true );

		echo wp_kses_post( $value );
	}
}
