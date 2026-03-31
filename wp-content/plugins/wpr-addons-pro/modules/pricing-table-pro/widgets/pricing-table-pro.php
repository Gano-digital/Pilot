<?php
namespace WprAddonsPro\Modules\PricingTablePro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Pricing_Table_Pro extends \WprAddons\Modules\PricingTable\Widgets\Pricing_Table {

	public function add_repeater_args_feature_tooltip() {
		return [
			'label' => esc_html__( 'Show Tooltip', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'condition' => [
				'type_select' => 'feature',
			],
		];
	}

	public function add_repeater_args_feature_tooltip_text() {
		return [
			'label' => esc_html__( 'Description', 'wpr-addons' ),
			'type' => Controls_Manager::TEXTAREA,
			'dynamic' => [
				'active' => true,
			],
			'default' => 'Tooltip Text',
			'description' => esc_html__( 'This field accepts HTML.', 'wpr-addons' ),
			'condition' => [
				'type_select' => 'feature',
				'feature_tooltip' => 'yes',
			],
		];
	}

	public function add_repeater_args_feature_tooltip_show_icon() {
		return [
			'label' => esc_html__( 'Show Tooltip Icon', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'condition' => [
				'type_select' => 'feature',
				'feature_tooltip' => 'yes',
			],
		];
	}

	public function add_contro_stack_feature_tooltip_section() {
		$this->add_control(
			'feature_tooltip_section',
			[
				'label' => esc_html__( 'Tooltip', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'feature_tooltip_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature-tooltip' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'feature_tooltip_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#3f3f3f',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature-tooltip' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-pricing-table-feature-tooltip:before' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'feature_tooltip_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#ffffff',				
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table-feature-tooltip' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'feature_tooltip_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-pricing-table-feature-tooltip',
			]
		);
	}

	public function add_controls_group_feature_even_bg() {
		$this->add_control(
			'feature_even_bg',
			[
				'label' => esc_html__( 'Enable Even Color', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'feature_even_bg_color',
			[
				'label' => esc_html__( 'Even Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f4f4f4',
				'selectors' => [
					'{{WRAPPER}} .wpr-pricing-table section:nth-of-type(even)' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'feature_even_bg' => 'yes',
				],
			]
		);
	}
}