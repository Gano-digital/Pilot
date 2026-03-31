<?php
namespace WprAddonsPro\Modules\ButtonPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Button_Pro extends \WprAddons\Modules\Button\Widgets\Wpr_Button {

	public function add_control_icon_style() {
		$this->add_control(
			'icon_style',
			[
				'label' => esc_html__( 'Select Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'wpr-addons' ),
					'block' => esc_html__( 'Block', 'wpr-addons' ),
					'inline-block' => esc_html__( 'Inline Block', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-button-icon-style-',
				'separator' => 'before',
			]
		);
	}

	public function add_control_icon_width() {
		$this->add_control(
			'icon_width',
			[
				'label' => esc_html__( 'Icon Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 38,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-icon' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'icon_style!' => 'inline',
				],
			]
		);
	}

	public function add_section_tooltip() {
		$this->start_controls_section(
			'section_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tooltip',
			[
				'label' => esc_html__( 'Show Tooltip', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'tooltip_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-button-tooltip-position-',
				'condition' => [
					'tooltip' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tooltip_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 210,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-tooltip' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tooltip' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_duration',
			[
				'label' => esc_html__( 'Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-button-tooltip' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'tooltip' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_text',
			[
				'label' => '',
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem Ipsum is simply dumy text of the printing typesetting industry lorem ipsum.',
				'condition' => [
					'tooltip' => 'yes',
				],
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section(); // End Controls Section
	}

	public function add_section_style_icon() {
		$this->start_controls_section(
			'section_style_icon',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'icon_style!' => 'inline',
				],
			]
		);
		
		$this->start_controls_tabs( 'tabs_icon_colors' );

		$this->start_controls_tab(
			'tab_icon_normal_colors',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button-icon',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover_colors',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'icon_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button:hover .wpr-button-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-button:hover .wpr-button-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_hover_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-button:hover .wpr-button-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-button:hover .wpr-button-icon' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'icon_hover_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button:hover .wpr-button-icon',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_border_type',
			[
				'label' => esc_html__( 'Border Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'solid' => esc_html__( 'Solid', 'wpr-addons' ),
					'double' => esc_html__( 'Double', 'wpr-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
					'groove' => esc_html__( 'Groove', 'wpr-addons' ),
				],
				'default' => 'none',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-icon' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'icon_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);


		$this->end_controls_section(); // End Controls Section
	}

	public function add_section_style_tooltip() {
		$this->start_controls_section(
			'section_style_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-tooltip' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tooltip_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#3f3f3f',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-tooltip' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button-tooltip:before' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button-tooltip',
			]
		);

		$this->add_control(
			'tooltip_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);


		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tooltip_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-button-tooltip',
			]
		);

		$this->add_responsive_control(
			'tooltip_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-button-tooltip-position-top .wpr-button-tooltip' => 'top: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-position-bottom .wpr-button-tooltip' => 'bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-position-left .wpr-button-tooltip' => 'left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-position-right .wpr-button-tooltip' => 'right: -{{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tooltip_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 6,
					'right' => 10,
					'bottom' => 6,
					'left' => 10,
				],
				'selectors' => [					
					'{{WRAPPER}} .wpr-button-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tooltip_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section	
	}

	public function render_pro_element_tooltip( $settings ) {
		if ( $settings['tooltip'] === 'yes' && ! empty( $settings['tooltip_text'] ) ) {
			echo '<div class="wpr-button-tooltip">'. $settings['tooltip_text'] .'</div>';
		}
	}
}