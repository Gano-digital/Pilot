<?php
namespace WprAddonsPro\Modules\AdvancedSliderPro\Widgets;

use Elementor;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Advanced_Slider_Pro extends \WprAddons\Modules\AdvancedSlider\Widgets\Wpr_Advanced_Slider {
	
	public function add_control_slider_effect() {
		$this->add_control(
			'slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'wpr-addons' ),
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
					'slide_vertical' => esc_html__( 'Sl Vertical', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
				],
				'separator' => 'before'
			]
		);
	}

	public function add_control_slider_nav_hover() {
		$this->add_control(
			'slider_nav_hover',
			[
				'label' => esc_html__( 'Show on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'fade',
				'prefix_class' => 'wpr-slider-nav-',
				'render_type' => 'template',
				'condition' => [
					'slider_nav' => 'yes',
				],
			]
		);
	}

	public function add_control_slider_dots_layout() {
		$this->add_control(
			'slider_dots_layout',
			[
				'label' => esc_html__( 'Pagination Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
					'vertical' => esc_html__( 'Vertical', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-slider-dots-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_slider_scroll_btn() {
		$this->add_responsive_control(
			'slider_scroll_btn',
			[
				'label' => esc_html__( 'Scroll to Section Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'desktop_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_default' => 'yes',
				'selectors_dictionary' => [
					'' => 'none',
					'yes' => 'block'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn' => 'display:{{VALUE}};',
				],
				'separator' => 'before'
			]
		);
	}

	public function add_repeater_args_slider_item_bg_kenburns() {
		return [
			'label' => esc_html__( 'Ken Burn Effect', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'conditions' => [
				'terms' => [
					[	
						'name' => 'slider_content_type',
						'operator' => '!=',
						'value' => 'template',
					],
					[
						'name' => 'slider_item_bg_image[url]',
						'operator' => '!=',
						'value' => '',
					],
				],
			],
		];
	}

	public function add_repeater_args_slider_item_bg_zoom() {
		return [
			'label' => esc_html__( 'Zoom Direction', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'in',
			'options' => [
				'in' => esc_html__( 'In', 'wpr-addons' ),
				'out' => esc_html__( 'Out', 'wpr-addons' ),
			],
			'conditions' => [
				'terms' => [
					[	
						'name' => 'slider_content_type',
						'operator' => '!=',
						'value' => 'template',
					],
					[
						'name' => 'slider_item_bg_image[url]',
						'operator' => '!=',
						'value' => '',
					],
					[
						'name' => 'slider_item_bg_kenburns',
						'operator' => '!=',
						'value' => '',
					],
				],
			],
		];
	}

	public function add_repeater_args_slider_content_type() {
		return [
            'custom' => esc_html__( 'Custom', 'wpr-addons' ),
            'template' => esc_html__( 'Elementor Template', 'wpr-addons' ),
        ];
	}

	public function add_repeater_args_slider_select_template() {
		return [
			'label'	=> esc_html__( 'Select Template', 'wpr-addons' ),
			'type' => 'wpr-ajax-select2',
			'options' => 'ajaxselect2/get_elementor_templates',
			'label_block' => true,
			'condition' => [
				'slider_content_type' => 'template'
			]
		];
	}

	public function add_repeater_args_slider_item_link_type() {
		return [
			'label' => esc_html__( 'Link Type', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none' => esc_html__( 'None', 'wpr-addons' ),
				'custom' => esc_html__( 'Custom URL', 'wpr-addons' ),
				'video-youtube'  => esc_html__( 'Youtube', 'wpr-addons' ),
				'video-vimeo'  => esc_html__( 'Vimeo', 'wpr-addons' ),
				'video-media'  => esc_html__( 'Custom Video', 'wpr-addons' )
			],
			'condition' => [
				'slider_content_type' => 'custom'
			],
			'separator' => 'before'
		];
	}

	public function add_control_slider_amount() {
		$this->add_responsive_control(
			'slider_amount',
			[
				'label' => esc_html__( 'Columns (Carousel)', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 1,
				'widescreen_default' => 1,
				'laptop_default' => 1,
				'tablet_extra_default' => 1,
				'tablet_default' => 1,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'wpr-addons' ),
					2 => esc_html__( 'Two', 'wpr-addons' ),
					3 => esc_html__( 'Three', 'wpr-addons' ),
					4 => esc_html__( 'Four', 'wpr-addons' ),
					5 => esc_html__( 'Five', 'wpr-addons' ),
					6 => esc_html__( 'Six', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-adv-slider-columns-%s',
				'render_type' => 'template',
				'condition' => [
					'slider_effect!' => 'slide_vertical'
				]
			]
		);
	}

	public function add_control_slides_to_scroll() {
		$this->add_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 2,
				'prefix_class' => 'wpr-adv-slides-to-scroll-',
				'render_type' => 'template',
				'frontend_available' => true,
				'default' => 1,
				'condition' => [
					'slider_effect!' => 'slide_vertical'
				]
			]
		);
	}

	public function add_control_slider_autoplay() {
		$this->add_control(
			'slider_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before'
			]
		);
	}

	public function add_control_slider_autoplay_duration() {
		$this->add_control(
			'slider_autoplay_duration',
			[
				'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 0,
				'max' => 15,
				'step' => 0.5,
				'frontend_available' => true,
				'condition' => [
					'slider_autoplay' => 'yes',
				],
			]
		);
	}

	public function add_control_slider_pause_on_hover() {
		$this->add_control(
			'slider_pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'slider_autoplay' => 'yes',
				],
			]
		);
	}

	public function add_section_style_scroll_btn() {
		$this->start_controls_section(
			'wpr__section_style_scroll_btn',
			[
				'label' => esc_html__( 'Scroll Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_scroll_btn_style' );

		$this->start_controls_tab(
			'tab_scroll_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
	
		$this->add_control(
			'scroll_btn_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-slider-scroll-btn svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'scroll_btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,			
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_scroll_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'scroll_btn_hover_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpr-slider-scroll-btn:hover svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'scroll_btn_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,			
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'scroll_btn_font_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-slider-scroll-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'scroll_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 6,
					'right' => 7,
					'bottom' => 8,
					'left' => 7,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'scroll_btn_vr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 150,
					],
					'%' => [
						'min' => -20,
						'max' => 120,
					],
				],											
				'default' => [
					'unit' => 'px',
					'size' => 45,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'scroll_btn_border_type',
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
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'scroll_btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'scroll_btn_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'scroll_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 9,
					'right' => 9,
					'bottom' => 9,
					'left' => 9,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-scroll-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	public function render_pro_element_slider_scroll_btn() {
		$settings = $this->get_settings_for_display();
		
        if ( isset($settings['slider_scroll_btn_url']) ) {
            $this->add_render_attribute( 'slider_scroll_btn', 'href',  esc_url( $settings['slider_scroll_btn_url']['url'] ));
        }

		if ( isset($settings['slider_scroll_btn_url']) && $settings['slider_scroll_btn_url']['is_external'] ) {
			$this->add_render_attribute( 'slider_scroll_btn', 'target', '_blank' );
		}

		if (  isset($settings['slider_scroll_btn_url']) && $settings['slider_scroll_btn_url']['nofollow'] ) {
			$this->add_render_attribute( 'slider_scroll_btn', 'nofollow', '' );
		}

		$slider_scroll_btn_attr = $this->get_render_attribute_string( 'slider_scroll_btn' );

		echo '<a class="wpr-slider-scroll-btn" '. $slider_scroll_btn_attr .'>';
			Icons_Manager::render_icon( $settings['slider_scroll_btn_icon'], [ 'class' => 'wpr-scroll-animation', 'aria-hidden' => 'true' ] );
		echo '</a>';		
	}

	public function add_control_stack_slider_nav_position() {
		$this->add_control(
			'slider_nav_position',
			[
				'label' => esc_html__( 'Positioning', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'custom',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-slider-nav-position-',
			]
		);

		$this->add_control(
			'slider_nav_position_default',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'top-left',
				'options' => [
					'top-left' => esc_html__( 'Top Left', 'wpr-addons' ),
					'top-center' => esc_html__( 'Top Center', 'wpr-addons' ),
					'top-right' => esc_html__( 'Top Right', 'wpr-addons' ),
					'bottom-left' => esc_html__( 'Bottom Left', 'wpr-addons' ),
					'bottom-center' => esc_html__( 'Bottom Center', 'wpr-addons' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-slider-nav-align-',
				'condition' => [
					'slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_outer_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Outer Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}[class*="wpr-slider-nav-align-top"] .wpr-slider-arrow-container' => 'top: {{SIZE}}px;',
					'{{WRAPPER}}[class*="wpr-slider-nav-align-bottom"] .wpr-slider-arrow-container' => 'bottom: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-slider-nav-align-top-left .wpr-slider-arrow-container' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-slider-nav-align-bottom-left .wpr-slider-arrow-container' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-slider-nav-align-top-right .wpr-slider-arrow-container' => 'right: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-slider-nav-align-bottom-right .wpr-slider-arrow-container' => 'right: {{SIZE}}px;',
				],
				'condition' => [
					'slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_inner_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Inner Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow-container .wpr-slider-prev-arrow' => 'margin-right: {{SIZE}}px;',
				],
				'condition' => [
					'slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_position_top',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-arrow' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_position_left',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Left Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 120,
					],
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'slider_nav_position_right',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Right Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 120,
					],
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'slider_nav_position' => 'custom',
				],
			]
		);
	}

	public function add_control_slider_dots_hr() {
		$this->add_responsive_control(
			'slider_dots_hr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 2000,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-slider-dots' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}
	
}