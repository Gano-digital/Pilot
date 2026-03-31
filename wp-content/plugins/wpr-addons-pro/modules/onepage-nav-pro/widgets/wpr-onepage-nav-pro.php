<?php
namespace WprAddonsPro\Modules\OnepageNavPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_OnepageNav_Pro extends \WprAddons\Modules\OnepageNav\Widgets\Wpr_OnepageNav {

	public function add_repeater_args_nav_item_icon_color() {
		return [
			'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
			'type' => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-onepage-nav-item i' => 'color: {{VALUE}};',
				'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-onepage-nav-item svg' => 'fill: {{VALUE}};',
			],
		];
	}

	public function add_repeater_args_nav_item_tooltip() {
		return [
			'label' => esc_html__( 'Section Tooltip', 'wpr-addons' ),
			'type' => Controls_Manager::TEXT,
			'default' => 'Section 1',
		];
	}

	public function add_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'nav_consider_header',
			[
				'label' => esc_html__( 'Consider Sticky Header', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'description' => esc_html__( 'Enable this to account for Royal Sticky Header when scrolling to sections.', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'nav_item_show_tooltip',
			[
				'label' => esc_html__( 'Show Tooltip', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'nav_item_highlight',
			[
				'label' => esc_html__( 'Highlight Active', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'nav_item_scroll_speed',
			[
				'label' => esc_html__( 'Scrolling Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'step' => 100,
				'min' => 0,
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function add_control_nav_item_stretch() {
		$this->add_control(
			'nav_item_stretch',
			[
				'label' => esc_html__( 'Stretch Vertically', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors_dictionary' => [
					'' => 'height: auto;',
					'yes' => 'height: 100%; top: 50%; transform: translateY(-50%); -webkit-transform: translateY(-50%);'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav' => '{{VALUE}}',
				],
				'render_type' => 'template'
			]
		);
	}

	public function add_condition_nav_item_stretch() {
		return [
			'nav_item_stretch!' => 'yes',
		];
	}

	public function add_section_nav_tooltip() {
		$this->start_controls_section(
			'section_nav_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'nav_tooltip_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item .wpr-tooltip' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'nav_tooltip_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#3F3F3F',
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item .wpr-tooltip' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-onepage-nav-item .wpr-tooltip:before' => 'border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'nav_tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-onepage-nav-item .wpr-tooltip',
			]
		);

		$this->add_control(
			'nav_tooltip_type_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'nav_item_tooltip_typography',
				'selector' => '{{WRAPPER}} .wpr-onepage-nav-item .wpr-tooltip'
			]
		);

		$this->add_responsive_control(
			'nav_tooltip_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 100,
				],
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item .wpr-tooltip' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'nav_tooltip_offset',
			[
				'label' => esc_html__( 'Offset', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-onepage-nav-hr-left .wpr-onepage-nav-item .wpr-tooltip' => 'transform: translate({{SIZE}}%,-50%); -webkit-transform: translate({{SIZE}}%,-50%);',
					'{{WRAPPER}}.wpr-onepage-nav-hr-left .wpr-onepage-nav-item:hover .wpr-tooltip' => 'transform: translate(calc({{SIZE}}% - 10%),-50%); -webkit-transform: translate(-webkit-calc({{SIZE}}% - 10%),-50%);',
					'{{WRAPPER}}.wpr-onepage-nav-hr-right .wpr-onepage-nav-item .wpr-tooltip' => 'transform: translate(calc(-{{SIZE}}% - 100%),-50%); -webkit-transform: translate(calc(-{{SIZE}}% - 100%),-50%);',
					'{{WRAPPER}}.wpr-onepage-nav-hr-right .wpr-onepage-nav-item:hover .wpr-tooltip' => 'transform: translate(calc(-{{SIZE}}% - 100% + 10%),-50%); -webkit-transform: translate(-webkit-calc(-{{SIZE}}% - 100% + 10%),-50%);',
				],
			]
		);

		$this->add_responsive_control(
			'nav_tooltip_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 5,
					'right' => 10,
					'bottom' => 5,
					'left' => 10,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item .wpr-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'nav_tooltip_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-onepage-nav-item .wpr-tooltip' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

}