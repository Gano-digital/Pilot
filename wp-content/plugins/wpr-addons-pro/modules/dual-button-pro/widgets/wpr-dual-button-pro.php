<?php
namespace WprAddonsPro\Modules\DualButtonPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Dual_Button_Pro extends \WprAddons\Modules\DualButton\Widgets\Wpr_Dual_Button {
	
	public function add_control_middle_badge() {
		$this->add_control(
			'middle_badge',
			[
				'label' => esc_html__( 'Middle Badge', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes',
				'separator' => 'before',
			]
		);
	}
	
	public function add_control_middle_badge_type() {
		$this->add_control(
			'middle_badge_type',
			[
				'label' => esc_html__( 'Select Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'text' => esc_html__( 'Text', 'wpr-addons' ),
					'icon' => esc_html__( 'Icon', 'wpr-addons' ),
				],
				'condition' => [
					'middle_badge' => 'yes'
				],
			]
		);
	}
	
	public function add_control_middle_badge_text() {
		$this->add_control(
			'middle_badge_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Or',
				'condition' => [
					'middle_badge' => 'yes',
					'middle_badge_type' => 'text',
				],
			]
		);
	}
	
	public function add_control_middle_badge_icon() {
		$this->add_control(
			'middle_badge_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-paper-plane',
					'library' => 'fa-solid',
				],
				'condition' => [
					'middle_badge' => 'yes',
					'middle_badge_type' => 'icon',
				],
			]
		);
	}

	public function add_section_style_middle_badge() {
		$this->start_controls_section(
			'section_style_middle_badge',
			[
				'label' => esc_html__( 'Middle Badge', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'middle_badge' => 'yes'
				],
			]
		);

		$this->add_control(
			'middle_badge_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'default' => '#605BE5',				
				'selectors' => [
					'{{WRAPPER}} .wpr-button-middle-badge' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button-middle-badge svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'middle_badge_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-button-middle-badge' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'middle_badge_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button-middle-badge',
			]
		);

		$this->add_responsive_control(
			'middle_badge_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-middle-badge' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'middle_badge_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'middle_badge_typography',
				'selector' => '{{WRAPPER}} .wpr-button-middle-badge',
				'condition' => [
					'middle_badge_type' => 'text'
				],
			]
		);

		$this->add_responsive_control(
			'middle_badge_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-middle-badge i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-middle-badge svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'middle_badge_type' => 'icon',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'middle_badge_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-button-middle-badge',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'middle_badge_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 50,
					'right' => 50,
					'bottom' => 50,
					'left' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-middle-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function add_section_tooltip_a() {
		$this->start_controls_section(
			'section_tooltip_a',
			[
				'label' => esc_html__( 'First Button Tooltip', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tooltip_a',
			[
				'label' => esc_html__( 'Show Tooltip', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'tooltip_a_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-button-tooltip-a-position-',
				'condition' => [
					'tooltip_a' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_a_text',
			[
				'label' => '',
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem Ipsum is simply dumy text of the printing typesetting industry lorem ipsum.',
				'condition' => [
					'tooltip_a' => 'yes',
				],
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section(); // End Controls Section
	}

	public function add_section_tooltip_b() {
		$this->start_controls_section(
			'section_tooltip_b',
			[
				'label' => esc_html__( 'Second Button Tooltip', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tooltip_b',
			[
				'label' => esc_html__( 'Show Tooltip', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'tooltip_b_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-button-tooltip-b-position-',
				'condition' => [
					'tooltip_b' => 'yes',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_b_text',
			[
				'label' => '',
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Lorem Ipsum is simply dumy text of the printing typesetting industry lorem ipsum.',
				'condition' => [
					'tooltip_b' => 'yes',
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
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'tooltip_a',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'tooltip_b',
							'operator' => '=',
							'value' => 'yes',
						],
					],
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
					'{{WRAPPER}} .wpr-button-tooltip-a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button-tooltip-b' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .wpr-button-tooltip-a' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button-tooltip-a:before' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button-tooltip-b' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-button-tooltip-b:before' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'tooltip_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-button-tooltip-a,{{WRAPPER}} .wpr-button-tooltip-b',
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
					'{{WRAPPER}} .wpr-button-tooltip-a' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-button-tooltip-b' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
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
						'max' => 800,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 210,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-button-tooltip-a' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-tooltip-b' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
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
				'selector' => '{{WRAPPER}} .wpr-button-tooltip-a,{{WRAPPER}} .wpr-button-tooltip-b',
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
					'{{WRAPPER}}.wpr-button-tooltip-a-position-top .wpr-button-tooltip-a' => 'top: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-a-position-bottom .wpr-button-tooltip-a' => 'bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-a-position-left .wpr-button-tooltip-a' => 'left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-a-position-right .wpr-button-tooltip-a' => 'right: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-b-position-top .wpr-button-tooltip-b' => 'top: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-b-position-bottom .wpr-button-tooltip-b' => 'bottom: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-b-position-left .wpr-button-tooltip-b' => 'left: -{{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-button-tooltip-b-position-right .wpr-button-tooltip-b' => 'right: -{{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-button-tooltip-a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-tooltip-b' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-button-tooltip-a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-button-tooltip-b' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function render_pro_element_tooltip_a() {
		$settings = $this->get_settings();

		if ( $settings['tooltip_a'] === 'yes' && ! empty( $settings['tooltip_a_text'] ) ) {
			echo '<div class="wpr-button-tooltip-a">'. $settings['tooltip_a_text'] .'</div>';			
		}
	}

	public function render_pro_element_tooltip_b() {
		$settings = $this->get_settings();
		
		if ( $settings['tooltip_b'] === 'yes' && ! empty( $settings['tooltip_b_text'] ) ) {
			echo '<div class="wpr-button-tooltip-b">'. $settings['tooltip_b_text'] .'</div>';			
		}
	}

	public function render_pro_element_middle_badge() {
		$settings = $this->get_settings();

		if ( 'yes' === $settings['middle_badge'] ) : ?>

		<span class="wpr-button-middle-badge">
			<?php if ( 'text' === $settings['middle_badge_type'] ) : ?>
				<?php echo esc_html( $settings['middle_badge_text'] ); ?>
			<?php else: ?>
				<?php \Elementor\Icons_Manager::render_icon( $settings['middle_badge_icon'] ); ?>
			<?php endif; ?>
		</span>

		<?php endif;
	}

}