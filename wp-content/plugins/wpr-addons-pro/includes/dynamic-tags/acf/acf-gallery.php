<?php
use Elementor\Controls_Manager;
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Data_Tag as Data_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_ACF_Gallery extends Data_Tag {

	public function get_name() {
		return 'wpr-acf-gallery';
	}

	public function get_title() {
		return __( 'Custom Field Gallery', 'wpr-addons' );
	}

	public function get_group() {
		return 'wpr_addons_acf';
	}

	public function get_categories() {
		return [
			'gallery'
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
			'gallery'
		];
	}

	public function get_value( array $options = [] ) {
		$field_key = $this->get_settings( 'wpr_acf_field' );

		if ( empty( $field_key ) ) {
			return;
		}

        $images = [];
        $value = get_field( $field_key, get_queried_object_id(), true );

		if ( is_array( $value ) && ! empty( $value ) ) {
			foreach ( $value as $image ) {

				if ( !is_array($image) ) {
					if ( 'string' === gettype($image) ) {
						$image_id = attachment_url_to_postid( $image );
					} elseif ( 'integer' === gettype($image) ) {
						$image_id = $image;
					}
				} else {
					$image_id = $image['ID'];
				}

				$images[] = [
					'id' => $image_id,
				];
			}
		}

        return $images;
	}
}
