<?php
namespace WprAddonsPro\Modules\SharingButtonsPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Sharing_Buttons_Pro extends \WprAddons\Modules\SharingButtons\Widgets\Wpr_Sharing_Buttons {

	public function add_repeater_args_sharing_custom_label() {
		return [
			'label' => esc_html__( 'Custom Label', 'wpr-addons' ),
			'type' => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
		];
	}

	public function add_control_sharing_columns() {
		$this->add_responsive_control(
			'sharing_columns',
			[
				'label' => esc_html__( 'Columns', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'0' => esc_html( 'Auto', 'wpr-addons' ),
					'1' => esc_html( '1', 'wpr-addons' ),
					'2' => esc_html( '2', 'wpr-addons' ),
					'3' => esc_html( '3', 'wpr-addons' ),
					'4' => esc_html( '4', 'wpr-addons' ),
					'5' => esc_html( '5', 'wpr-addons' ),
					'6' => esc_html( '6', 'wpr-addons' ),
				],
				'default' => '0',
				'prefix_class' => 'elementor-grid%s-',
			]
		);
	}

	public function add_control_sharing_show_label() {
		$this->add_control(
			'sharing_show_label',
			[
				'label' => esc_html__( 'Show Label', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
	}

	public function add_control_sharing_show_icon() {
		$this->add_control(
			'sharing_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors_dictionary' => [
					'' => 'center',
					'yes' => 'left'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-sharing-buttons .wpr-sharing-label' => 'text-align: {{VALUE}};',
				],
				'render_type' => 'template'
			]
		);
	}

	public function add_control_sharing_icon_border_radius() {
		$this->add_control(
			'sharing_icon_border_radius',
			[
				'label' => esc_html__( 'Icon Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-sharing-buttons .wpr-sharing-icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	}

}