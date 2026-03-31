<?php
namespace WprAddonsPro\Modules\BusinessHoursPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Business_Hours_Pro extends \WprAddons\Modules\BusinessHours\Widgets\Wpr_Business_Hours {

	public function add_repeater_args_icon() {
		return [
			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
			'type' => Controls_Manager::ICONS,
			'skin' => 'inline',
			'label_block' => false,
		];
	}

	public function add_repeater_args_highlight() {
		return [
			'label' => esc_html__( 'Highlight', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
		];
	}

	public function add_repeater_args_highlight_color() {
		return [
			'label' => esc_html__( 'Text Color', 'wpr-addons' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-business-hours-item .wpr-business-day' => 'color: {{VALUE}}!important;',
				'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-business-hours-item .wpr-business-time' => 'color: {{VALUE}}!important;',
				'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-business-hours-item .wpr-business-closed' => 'color: {{VALUE}}!important;',
			],
			'condition' => [
				'highlight' => 'yes',
			],
		];
	}

	public function add_repeater_args_highlight_bg_color() {
		return [
			'label' => esc_html__( 'Background Color', 'wpr-addons' ),
			'type' => Controls_Manager::COLOR,
			'default' => '#61ce70',
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}}.wpr-business-hours-item' => 'background-color: {{VALUE}}!important;',
			],
			'condition' => [
				'highlight' => 'yes',
			],
		];
	}

	public function add_control_general_even_bg() {
		$this->add_control(
			'general_even_bg',
			[
				'label' => esc_html__( 'Enable Even Color', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);
	}

	public function add_control_general_even_bg_color() {
		$this->add_control(
			'general_even_bg_color',
			[
				'label' => esc_html__( 'Even Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#F9F9F9',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours-item:nth-of-type(even)' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'general_even_bg' => 'yes',
				],
			]
		);
	}

	public function add_control_general_icon_color() {
		$this->add_control(
			'general_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-business-day i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-business-day svg' => 'fill: {{VALUE}}'
				],
			]
		);
	}

	public function add_control_general_hover_icon_color() {
		$this->add_control(
			'general_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-business-hours .wpr-business-hours-item:hover .wpr-business-day i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-business-hours .wpr-business-hours-item:hover .wpr-business-day svg' => 'fill: {{VALUE}}',
				],
			]
		);
	}

	public function add_control_general_icon_size() {
		$this->add_control(
			'general_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 14,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-business-day i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-business-day svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);
	}

}