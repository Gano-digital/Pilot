<?php
namespace WprAddonsPro;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Base_Tag;
use Elementor\Modules\DynamicTags;

if ( ! defined( 'ABSPATH' ) || ! wpr_fs()->is_plan( 'expert' ) || ! defined('WPR_ADDONS_PRO_VERSION') ) {
	exit; // Exit if accessed directly
}

class Wpr_Dynamic_Tags_Module extends DynamicTags\Module {
	public static function get_control_options( $types ) {
		$groups = [];

		// Get all ACF field groups
		$field_groups = acf_get_field_groups();

		// Loop through each field group
		foreach ($field_groups as $field_group) {
			$fields = acf_get_fields($field_group);
			$field_keys = [];

			foreach ( $fields as $field ) {
				if ( in_array( $field['type'], $types, true ) ) {
					$field_keys[$field['name']] = $field['label'];
				}
			}

			if ( ! empty($field_keys) ) {
				$groups[] = [
					'label' => $field_group['title'],
					'options' => $field_keys
				];
			}
		}

		return $groups;
	}

	public static function add_field_select_control( Base_Tag $tag ) {
		$tag->add_control(
			'wpr_acf_field',
			[
				'label' => esc_html__( 'Select Field', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'groups' => self::get_control_options( $tag->get_supported_fields() ),
			]
		);
	}

	public static function get_field_data( Base_Tag $tag ) {
		$acf_field = $tag->get_settings( 'wpr_acf_field' );
		$acf_field_object = get_field_object($acf_field, get_queried_object_id());

		return ! empty( $acf_field ) ? $acf_field_object : [];
	}

	public function get_groups() {
		$suffix = defined( 'ELEMENTOR_PRO_VERSION' ) ? ' - '. esc_html__( 'Royal Addons', 'wpr-addons' ) : '';

		return [
			'wpr_addons_acf' => [
				'title' => esc_html__( 'ACF', 'wpr-addons' ) . $suffix,
			],
			'wpr_addons_custom_field' => [
				'title' => esc_html__( 'Custom Field', 'wpr-addons' ) . $suffix,
			],
			'wpr_addons_current_date_time' => [
				'title' => esc_html__( 'Current Date Time', 'wpr-addons' ) . $suffix,
			],
			'wpr_addons_featured_image' => [
				'title' => esc_html__( 'Featured Image', 'wpr-addons' ) . $suffix,
			],
		];
	}

	public function get_tag_classes_names() {
		return [
			// ACF
			'Wpr_ACF_Text',
			'Wpr_ACF_Number',
			'Wpr_ACF_Color',
			'Wpr_ACF_URL',
			'Wpr_ACF_Image',
			'Wpr_ACF_File',
			'Wpr_ACF_Gallery',

			// Rest
			'Wpr_Custom_Field',
			'Wpr_Current_Date_Time',
			'Wpr_Featured_Image'
		];
	}
	
	public function register_tags( $dynamic_tags ) {
		foreach ( $this->get_tag_classes_names() as $tag_class ) {
			$file = str_replace( 'Wpr_', '', $tag_class );
			$file = str_replace( '_', '-', strtolower( $file ) ) . '.php';
			$folderpath = str_contains($file, 'acf') ? 'acf' : 'tags';
			$filepath = WPR_ADDONS_PRO_PATH . 'includes/dynamic-tags/'. $folderpath .'/'. $file;

			if ( file_exists( $filepath ) ) {
				require $filepath;
			}

			if ( class_exists( $tag_class ) ) {
				$dynamic_tags->register( new $tag_class );
			}
		}
	}
}