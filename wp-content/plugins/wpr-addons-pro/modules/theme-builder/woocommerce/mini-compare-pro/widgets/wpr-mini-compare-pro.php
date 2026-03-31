<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\MiniComparePro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Mini_Compare_Pro extends Widget_Base {
	
	public function get_name() {
		return 'wpr-mini-compare-pro';
	}

	public function get_title() {
		return esc_html__( 'Mini Compare', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-exchange';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_archive') || Utilities::show_theme_buider_widget_on('product_single') ? ['wpr-woocommerce-builder-widgets'] : ['wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'compare count' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ------------
		$this->start_controls_section(
			'section_compare_count_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'compare_notice_video_tutorial',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Build Wishlist & Compare features <strong>completely with Elementor and Royal Elementor Addons !</strong> <ul><li><a href="https://www.youtube.com/watch?v=wis1rQTn1tg" target="_blank" style="color: #93003c;"><strong>Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></strong></a></li></ul>', 'wpr-addons' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
			]
		);

        $this->add_control(
            'compare_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' =>  sprintf( __( '<strong>Note:</strong> Navigate to <a href="%s" target="_blank">Royal Addons > Settings</a><br> to choose your <strong>Compare Page</strong>.', 'wpr-addons' ), admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ) ),
                'separator' => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info'
            ]
        );

		$this->add_control(
			'toggle_text',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'view_compare_text',
			[
				'label' => esc_html__( 'Compare Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'View Compare', 'wpr-addons' ),
				'default' => esc_html__( 'View Compare', 'wpr-addons' ),
				// 'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'compare_button_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-wrap' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'compare_style',
			[
				'label' => esc_html__( 'Compare Content', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'render_type' => 'template',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'popup' => esc_html__( 'Pop-up', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-compare-style-',
				'default' => 'none'
			]
		);

		$this->add_control(
			'compare_entrance',
			[
				'label' => esc_html__( 'Entrance Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-compare-',
				'condition' => [
						'compare_style' => 'dropdown'
				]
			]
		);

        $this->add_control(
            'compare_entrance_speed',
            [
                'label' => __( 'Entrance Speed', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 600,
                'render_type' => 'template',
				'condition' => [
					'compare_style!' => 'none'
				]
            ]
        );

		$this->add_control(
			'open_in_new_tab',
			[
				'label' => esc_html__( 'Open in New Tab', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_close_btn',
			[
				'label'     => esc_html__('Close Button', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_control(
			'close_compare_heading',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Compare', 'wpr-addons' ),
				'default' => esc_html__( 'Compare', 'wpr-addons' ),
				// 'render_type' => 'template',
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_responsive_control(
			'compare_heading_align',
			[
				'label' => esc_html__( 'Title Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'selectors_dictionary' => [
					'left' => '',
					'right' => 'flex-direction: row-reverse;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-close-compare' => '{{VALUE}}',
				],
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->end_controls_section();
		
		// Tab: Styles ==============
		// Section: Toggle Button ----------
		$this->start_controls_section(
			'section_compare_button',
			[
				'label' => esc_html__( 'Compare Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'toggle_btn_compare_icon',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'toggle_btn_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-toggle-btn i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-toggle-btn svg' => 'fill: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'toggle_btn_icon_color_hover',
			[
				'label'  => esc_html__( 'Color (Hover)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-toggle-btn:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-toggle-btn:hover svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-toggle-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-compare-toggle-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_compare_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'separator' => 'before',
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'compare_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-text' => 'color: {{VALUE}}',
				],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __( 'Typography', 'wpr-addons' ),
                'selector' => '{{WRAPPER}} .wpr-compare-toggle-btn, {{WRAPPER}} .wpr-compare-count',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				],
				'condition' => [
					'toggle_text!' => 'none'
				]
            ]
        );

		$this->add_responsive_control(
			'toggle_text_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-toggle-btn .wpr-compare-text' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'compare_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-toggle-btn' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-toggle-btn' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'compare_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-compare-toggle-btn',
			]
		);

		$this->add_responsive_control(
			'compare_btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-toggle-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_btn_border_type',
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
					'{{WRAPPER}} .wpr-compare-toggle-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_btn_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-toggle-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'compare_btn_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'compare_btn_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_btn_item_count',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Item Count', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'toggle_btn_item_count_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-count' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'toggle_btn_item_count_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-count' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_font_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 25,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-compare-count' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_box_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'toggle_btn_item_count_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 20,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 65,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-count' => 'bottom: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_style_compare',
			[
				'label' => esc_html__( 'Compare Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_control(
			'compare_loader_color',
			[
				'label'  => esc_html__( 'Loader Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-double-bounce .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wave .wpr-rect' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-spinner-pulse' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-chasing-dots .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-three-bounce .wpr-child' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-fading-circle .wpr-circle:before' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'compare_close_btn_styles',
			[
				'label'     => esc_html__('Close Button', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_control(
			'compare_close_btn_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-close-compare' => 'color: {{VALUE}}',
				],
				'condition' => [
					'compare_style' => 'popup',
				]
			]
		);

		$this->add_responsive_control(
			'compare_close_btn_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-close-compare' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'compare_style' => 'popup',
				]
			]
		);

		$this->add_responsive_control(
			'compare_close_btn_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-close-compare' => 'top: {{SIZE}}{{UNIT}}; right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'compare_style' => 'popup',
				]
			]
		);

		$this->add_control(
			'compare_popup_heading',
			[
				'label'     => esc_html__('Heading', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'compare_style' => 'popup',
					'close_compare_heading!' => ''
				]
			]
		);

		$this->add_control(
			'compare_sidebar_heading_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-close-compare h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'compare_style' => 'popup',
					'close_compare_heading!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'compare_sidebar_heading_typography',
				'selector' => '{{WRAPPER}} .wpr-close-compare h2',
				'fields_options' => [
						'typography' => [
							'default' => 'custom',
						],
						'font_size' => [
							'default' => [
								'size' => '18',
								'unit' => 'px',
							],
						]
					],
					'condition' => [
						'compare_style' => 'popup',
						'close_compare_heading!' => ''
					]
			]
		);

		$this->add_control(
			'compare_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-popup' => 'background-color: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-popup' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'compare_overlay_color',
			[
				'label'  => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#070707C4',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-bg' => 'background-color: {{VALUE}}'
				],
				'condition' => [
					'compare_style' => 'popup'
				]
			]
		);

		$this->add_control(
			'scrollbar_color',
			[
				'label'  => esc_html__( 'ScrollBar Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar-thumb' => 'border-right-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_responsive_control(
			'compare_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 1500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'vw' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vw',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-popup' => 'width: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'compare_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vh'],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 1500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'vh',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-popup' => 'height: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'compare_scrollbar_width',
			[
				'label' => esc_html__( 'ScrollBar Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar-thumb' => 'border-right: {{SIZE}}{{UNIT}} solid;',
					'{{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar' => 'min-width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'compare_scrollbar_distance',
			[
				'label' => esc_html__( 'ScrollBar Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width.SIZE}}{{compare_scrollbar_width.UNIT}});',
					'[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_widescreen.SIZE}}{{compare_scrollbar_width_widescreen.UNIT}});',
					'[data-elementor-device-mode="laptop"] {{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_laptop.SIZE}}{{compare_scrollbar_width_laptop.UNIT}});',
					'[data-elementor-device-mode="tablet"] {{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_tablet.SIZE}}{{compare_scrollbar_width_tablet.UNIT}});',
					'[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_tablet_extra.SIZE}}{{compare_scrollbar_width_tablet_extra.UNIT}});',
					'[data-elementor-device-mode="mobile"] {{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_mobile.SIZE}}{{compare_scrollbar_width_mobile.UNIT}});',
					'[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wpr-compare-popup::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{compare_scrollbar_width_mobile_extra.SIZE}}{{compare_scrollbar_width_mobile_extra.UNIT}});',
				]
			]
		);
		
		$this->add_responsive_control(
			'compare_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 15,
					'right' => 15,
					'bottom' => 15,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_border_type',
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
					'{{WRAPPER}} .wpr-compare-popup' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'compare_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-popup' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'compare_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'compare_border_radius',
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
					'{{WRAPPER}} .wpr-compare-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

    public function get_id_by_slug($page_slug) {
        // $page_slug = "parent-page"; in case of parent page
        // $page_slug = "parent-page/sub-page"; in case of inner page
        $page = get_page_by_path($page_slug);
        if ($page) {
            return $page->ID;
        } else {
            return '#';
        }
    }
	
	// Add two new functions for handling cookies
	public function get_compare_from_cookie() {
        if (isset($_COOKIE['wpr_compare'])) {
            return json_decode(stripslashes($_COOKIE['wpr_compare']), true);
        } else if ( isset($_COOKIE['wpr_compare_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_compare_'. get_current_blog_id() .'']), true);
        }
        return array();
	}

    protected function render() {

		$settings = $this->get_settings_for_display();

        $user_id = get_current_user_id();

		if ($user_id > 0) {
			$compare = get_user_meta( get_current_user_id(), 'wpr_compare', true );
		
			if ( ! $compare ) {
				$compare = array();
			}
		} else {
			$compare = $this->get_compare_from_cookie();
		}
		
        $compare_count = sizeof($compare);
		$link_target = 'yes' == $settings['open_in_new_tab'] ? '_blank' : '_self';
		// $compare_link = '#' !== $this->get_id_by_slug('wpr_compare') ? get_page_link($this->get_id_by_slug('wpr_compare')) : '#';

		$this->add_render_attribute(
			'compare_attributes',
			[
				'data-animation' => wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ? $settings['compare_entrance_speed'] : ''
			]
		);

		echo '<div class="wpr-compare-wrap "' . $this->get_render_attribute_string( 'compare_attributes' ) . '>';
		
			// Get the selected compare page ID
			$compare_page_id = get_option( 'wpr_compare_page' );

			// Get the permalink to the selected page
			$compare_page_link = get_permalink( $compare_page_id );

			echo '<div class="wpr-compare-toggle-btn">';
				echo '<a class="wpr-inline-flex-center" href="'. $compare_page_link .'" target="'. $link_target .'">';
					if ( 'yes' == $settings['toggle_text'] ) {
						echo '<span class="wpr-compare-text">'. esc_html__($settings['view_compare_text']) .'</span>';
					}
					echo '<i class="fas fa-exchange-alt" title="'. esc_html__($settings['view_compare_text']) .'">';
						echo '<span class="wpr-compare-count">'. $compare_count .'</span>';
					echo '</i>';
				echo '</a>';
			echo '</div>';

			echo '<div class="wpr-compare-bg  wpr-compare-popup-hidden wpr-compare-fade-out">';
				echo '<div class="wpr-compare-popup  wpr-compare-fade-in">';
					echo '<span class="wpr-close-compare"></span>';
					echo '<div class="wpr-compare-popup-inner-wrap">';
					echo '</div>';
				echo '</div>';
			echo '</div>';

		echo '</div>';

        // function create_compare_button() {
        // }

        // add_action( 'woocommerce_after_add_to_compare_button', 'create_compare_button' );
    }
}