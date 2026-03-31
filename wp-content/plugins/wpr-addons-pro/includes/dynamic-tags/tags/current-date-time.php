<?php
use Elementor\Controls_Manager;
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Tag as Base_Tag;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpr_Current_Date_Time extends Base_Tag {
	public function get_name() {
		return 'wpr-current-date-time';
	}

	public function get_title() {
		return __( 'Current Date & Time', 'wpr-addons' );
	}

	public function get_group() {
		return 'wpr_addons_current_date_time';
	}

	public function get_categories() {
		return [
			'text'
		];
	}

	protected function register_controls() {
		$this->add_control(
			'wpr_date_format',
			[
				'label' => esc_html__( 'Date Format', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'' => esc_html__( 'None', 'wpr-addons' ),
					'Y' => gmdate( 'Y' ),
					'F j, Y' => gmdate( 'F j, Y' ),
					'Y-m-d' => gmdate( 'Y-m-d' ),
					'Y, M, D' => gmdate( 'Y, M, D' ),
					'm/d/Y' => gmdate( 'm/d/Y' ),
					'd/m/Y' => gmdate( 'd/m/Y' ),
					'j. F Y' => gmdate( 'j. F Y' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'wpr_time_format',
			[
				'label' => esc_html__( 'Time Format', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'' => esc_html__( 'None', 'wpr-addons' ),
					'g:i a' => gmdate( 'g:i a' ),
					'g:i A' => gmdate( 'g:i A' ),
					'H:i' => gmdate( 'H:i' ),
				],
				'default' => 'default',
			]
		);
	}

	public function render() {
		$settings = $this->get_settings();

		if ( 'custom' === $settings['wpr_date_format'] ) {
			$format = $settings['wpr_custom_format'];
		} else {
			$date_format = $settings['wpr_date_format'];
			$time_format = $settings['wpr_time_format'];
			$format = '';

			if ( 'default' === $date_format ) {
				$date_format = get_option( 'date_format' );
			}

			if ( 'default' === $time_format ) {
				$time_format = get_option( 'time_format' );
			}

			if ( $date_format ) {
				$format = $date_format;
				$has_date = true;
			} else {
				$has_date = false;
			}

			if ( $time_format ) {
				if ( $has_date ) {
					$format .= ' ';
				}
				$format .= $time_format;
			}
		}

		$value = date_i18n( $format );

		echo wp_kses_post( $value );
	}
}
