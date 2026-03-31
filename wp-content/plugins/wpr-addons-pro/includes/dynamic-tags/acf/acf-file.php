<?php
use Elementor\Controls_Manager;
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Data_Tag as Data_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_ACF_File extends Wpr_ACF_Image {

	public function get_name() {
		return 'wpr-acf-file';
	}

	public function get_title() {
		return __( 'Custom Field File', 'wpr-addons' );
	}

	public function get_categories() {
		return [
			'media'
		];
	}

	public function get_supported_fields() {
		return [
			'file'
		];
	}
}
