<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\MiniWishlistPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Mini_Wishlist_Pro extends Widget_Base {
	
	public function get_name() {
		return 'wpr-mini-wishlist-pro';
	}

	public function get_title() {
		return esc_html__( 'Mini Wishlist', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-heart';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_archive') || Utilities::show_theme_buider_widget_on('product_single') ? ['wpr-woocommerce-builder-widgets'] : ['wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'wishlist count', 'mini wishlist' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ------------
		$this->start_controls_section(
			'section_wishlist_count_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'wishlist_notice_video_tutorial',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Build Wishlist & Compare features <strong>completely with Elementor and Royal Elementor Addons !</strong> <ul><li><a href="https://www.youtube.com/watch?v=wis1rQTn1tg" target="_blank" style="color: #93003c;"><strong>Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></strong></a></li></ul>', 'wpr-addons' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

        $this->add_control(
            'wishlist_notice',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' =>  sprintf( __( '<strong>Note:</strong> Navigate to <a href="%s" target="_blank">Royal Addons > Settings</a><br> to choose your <strong>Wishlist Page</strong>.', 'wpr-addons' ), admin_url( 'admin.php?page=wpr-addons&tab=wpr_tab_settings' ) ),
                'separator' => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

		$this->add_control(
			'wishlist_button_icon_style',
			[
				'label' => esc_html__( 'Icon Style', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'regular',
				'options' => [
					'regular' => [
						'title' => esc_html__( 'Regular', 'wpr-addons' ),
						'icon' => 'eicon-heart-o',
					],
					'solid' => [
						'title' => esc_html__( 'Solid', 'wpr-addons' ),
						'icon' => 'eicon-heart',
					]
				],
				'render_type' => 'template'
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
			'view_wishlist_text',
			[
				'label' => esc_html__( 'Wishlist Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'View Wishlist', 'wpr-addons' ),
				'default' => esc_html__( 'View Wishlist', 'wpr-addons' ),
				// 'render_type' => 'template'
			]
		);

		$this->add_control(
			'wishlist_empty_text',
			[
				'label' => esc_html__( 'Empty Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'No Products in the Wishlist', 'wpr-addons' ),
				'default' => esc_html__( 'No Products in the Wishlist', 'wpr-addons' ),
				// 'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'wishlist_button_alignment',
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
					'{{WRAPPER}} .wpr-wishlist-wrap' => 'text-align: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'wishlist_style',
			[
				'label' => esc_html__( 'Wishlist Content', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'render_type' => 'template',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'dropdown' => esc_html__( 'Dropdown', 'wpr-addons' ),
					'sidebar' => esc_html__( 'Sidebar', 'wpr-addons' )
				],
				'prefix_class' => 'wpr-wishlist-',
				'default' => 'dropdown'
			]
		);

		$this->add_control(
			'wishlist_entrance',
			[
				'label' => esc_html__( 'Entrance Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-wishlist-',
				'condition' => [
						'wishlist_style' => 'dropdown'
				]
			]
		);

        $this->add_control(
            'wishlist_entrance_speed',
            [
                'label' => __( 'Entrance Speed', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 600,
                'render_type' => 'template',
				'condition' => [
					'wishlist_style!' => 'none'
				]
            ]
        );

		$this->add_responsive_control(
			'wishlist_alignment',
			[
				'label' => esc_html__( 'Wishlist Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'right',
				'render_type' => 'template',
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
				'prefix_class' => 'wpr-wishlist-align-',
				'selectors_dictionary' => [
					'left' => 'left: 0;',
					'right' => 'right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist' => '{{VALUE}}',
					'{{WRAPPER}}.wpr-wishlist-sidebar .wpr-wishlist-inner-wrap' => '{{VALUE}}', // configure
				],
				'condition' => [
					'wishlist_style!' => 'none'
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
			'wishlist_close_btn',
			[
				'label'     => esc_html__('Close Button', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'wishlist_style' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'show_wishlist_close_btn',
			[
				'label' => esc_html__( 'Show', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'wpr-close-btn-',
				'condition' => [
					'wishlist_style' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'close_wishlist_heading',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Wishlist', 'wpr-addons' ),
				'default' => esc_html__( 'Wishlist', 'wpr-addons' ),
				// 'render_type' => 'template',
				'condition' => [
					'wishlist_style' => 'sidebar',
					'show_wishlist_close_btn' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_heading_align',
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
					'{{WRAPPER}} .wpr-close-wishlist' => '{{VALUE}}',
				],
				'condition' => [
					'wishlist_style' => 'sidebar',
					'show_wishlist_close_btn' => 'yes'
				]
			]
		);

		$this->end_controls_section();
		
		// Tab: Styles ==============
		// Section: Toggle Button ----------
		$this->start_controls_section(
			'section_wishlist_button',
			[
				'label' => esc_html__( 'Wishlist Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'toggle_btn_wishlist_icon',
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
					'{{WRAPPER}} .wpr-wishlist-toggle-btn i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-toggle-btn svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'toggle_btn_icon_color_hover',
			[
				'label'  => esc_html__( 'Color (Hover)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-toggle-btn:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-toggle-btn:hover svg' => 'fill: {{VALUE}}'
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
					'{{WRAPPER}} .wpr-wishlist-toggle-btn i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-wishlist-toggle-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_control(
			'toggle_btn_wishlist_title',
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
			'wishlist_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-text' => 'color: {{VALUE}}',
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
                'selector' => '{{WRAPPER}} .wpr-wishlist-toggle-btn, {{WRAPPER}} .wpr-wishlist-count',
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
					'{{WRAPPER}} .wpr-wishlist-toggle-btn .wpr-wishlist-text' => 'margin-right: {{SIZE}}{{UNIT}};'
                ],
				'condition' => [
					'toggle_text!' => 'none'
				]
			]
		);

		$this->add_control(
			'wishlist_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-toggle-btn' => 'background-color: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'wishlist_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-toggle-btn' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wishlist_btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-wishlist-toggle-btn',
			]
		);

		$this->add_responsive_control(
			'wishlist_btn_padding',
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
					'{{WRAPPER}} .wpr-wishlist-toggle-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before'
			]
		);

		$this->add_control(
			'wishlist_btn_border_type',
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
					'{{WRAPPER}} .wpr-wishlist-toggle-btn' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'wishlist_btn_border_width',
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
					'{{WRAPPER}} .wpr-wishlist-toggle-btn' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'wishlist_btn_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'wishlist_btn_border_radius',
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
					'{{WRAPPER}} .wpr-wishlist-toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-wishlist-count' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .wpr-wishlist-count' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .wpr-wishlist-count' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-wishlist-count' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wpr-wishlist-count' => 'bottom: {{SIZE}}{{UNIT}}; left: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		$this->start_controls_section(
			'section_style_wishlist',
			[
				'label' => esc_html__( 'Wishlist Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wishlist_image',
			[
				'label'     => esc_html__('Image', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'wishlist_image_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 40,
					],
				],
				'default' => [
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-wrap .wpr-wishlist-product' => 'grid-template-columns: {{SIZE}}% auto;'
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_image_distance',
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product .wpr-wishlist-product-img' => 'margin-right: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'wishlist_close_btn_styles',
			[
				'label'     => esc_html__('Close Button', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'wishlist_style' => 'sidebar',
					'show_wishlist_close_btn' => 'yes'
				]
			]
		);

		$this->add_control(
			'wishlist_close_btn_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-close-wishlist span:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'wishlist_style' => 'sidebar',
					'show_wishlist_close_btn' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_close_btn_size',
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
					'size' => 22,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-close-wishlist span:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'wishlist_style' => 'sidebar',
					'show_wishlist_close_btn' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_close_btn_distance',
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-close-wishlist' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'wishlist_style' => 'sidebar',
					'show_wishlist_close_btn' => 'yes'
				]
			]
		);

		$this->add_control(
			'wishlist_sidebar_heading',
			[
				'label'     => esc_html__('Heading', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'wishlist_style' => 'sidebar',
					'show_wishlist_close_btn' => 'yes',
					'close_wishlist_heading!' => ''
				]
			]
		);

		$this->add_control(
			'wishlist_sidebar_heading_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-close-wishlist h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'wishlist_style' => 'sidebar',
					'show_wishlist_close_btn' => 'yes',
					'close_wishlist_heading!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wishlist_sidebar_heading_typography',
				'selector' => '{{WRAPPER}} .wpr-close-wishlist h2',
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
						'wishlist_style' => 'sidebar',
						'show_wishlist_close_btn' => 'yes',
						'close_wishlist_heading!' => ''
					]
			]
		);

		$this->add_control(
			'wishlist_product_title',
			[
				'label'     => esc_html__('Title', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'wishlist_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product a' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'wishlist_title_color_hover',
			[
				'label'  => esc_html__( 'Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product a:hover' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wishlist_title_typography',
				'selector' => '{{WRAPPER}} .wpr-wishlist-product a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'line_height'    => [
						'default' => [
							'size' => '1.1',
							'unit' => 'em',
						],
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'wishlist_product_price',
			[
				'label'     => esc_html__('Price', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'wishlist_price_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-price *' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wishlist_price_typography',
				'selector' => '{{WRAPPER}} .wpr-wishlist-product-price *',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'wishlist_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-wishlist-sidebar .wpr-wishlist-inner-wrap ' => 'background-color: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'wishlist_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'wishlist_style!' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'wishlist_overlay_color',
			[
				'label'  => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#070707C4',
				'selectors' => [
					'{{WRAPPER}}.wpr-wishlist-sidebar .wpr-wishlist-content-wrap ' => 'background: {{VALUE}}'
				],
				'condition' => [
					'wishlist_style' => 'sidebar'
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
					'{{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar-thumb' => 'border-right-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wishlist_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-wishlist',
				'fields_options' => [
					'box_shadow_type' =>
						[ 
							'default' =>'yes' 
						],
					'box_shadow' => [
						'default' =>
							[
								'horizontal' => 0,
								'vertical' => 0,
								'blur' => 0,
								'spread' => 0,
								'color' => 'rgba(0,0,0,0.3)'
							]
					]
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 1500,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 375,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-wishlist-dropdown .wpr-wishlist' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-wishlist-sidebar .wpr-wishlist-inner-wrap ' => 'width: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_list_height',
			[
				'label' => esc_html__( 'List Max Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh'],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 1500,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 680,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-products' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_list_distance',
			[
				'label' => esc_html__( 'List Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product' => 'margin-bottom: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}; padding-top: 0;',
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_scrollbar_width',
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
					'{{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar-thumb' => 'border-right: {{SIZE}}{{UNIT}} solid;',
					'{{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar' => 'min-width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-mini-wishlist .woocommerce-mini-wishlist::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{wishlist_scrollbar_distance.SIZE}}{{wishlist_scrollbar_distance.UNIT}});'
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_scrollbar_distance',
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
					// '{{WRAPPER}} .wpr-mini-wishlist .woocommerce-mini-wishlist::-webkit-scrollbar-thumb' => 'border-left: {{SIZE}}{{UNIT}} solid transparent;',
					'{{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{wishlist_scrollbar_width.SIZE}}{{wishlist_scrollbar_width.UNIT}});',
					'[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{wishlist_scrollbar_width_widescreen.SIZE}}{{wishlist_scrollbar_width_widescreen.UNIT}});',
					'[data-elementor-device-mode="laptop"] {{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{wishlist_scrollbar_width_laptop.SIZE}}{{wishlist_scrollbar_width_laptop.UNIT}});',
					'[data-elementor-device-mode="tablet"] {{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{wishlist_scrollbar_width_tablet.SIZE}}{{wishlist_scrollbar_width_tablet.UNIT}});',
					'[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{wishlist_scrollbar_width_tablet_extra.SIZE}}{{wishlist_scrollbar_width_tablet_extra.UNIT}});',
					'[data-elementor-device-mode="mobile"] {{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{wishlist_scrollbar_width_mobile.SIZE}}{{wishlist_scrollbar_width_mobile.UNIT}});',
					'[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wpr-wishlist .wpr-wishlist-products::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{wishlist_scrollbar_width_mobile_extra.SIZE}}{{wishlist_scrollbar_width_mobile_extra.UNIT}});',
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_distance',
			[
				'label' => esc_html__( 'Top Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'wishlist_style' => 'dropdown'
				]

			]
		);

		$this->add_responsive_control(
			'wishlist_padding',
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
					'{{WRAPPER}}.wpr-wishlist-dropdown .wpr-wishlist' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-wishlist-sidebar .wpr-wishlist-inner-wrap' => 'padding: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-wishlist-sidebar .wpr-close-wishlist' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'wishlist_list_padding',
			[
				'label' => esc_html__( 'List Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-products' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'wishlist_border_type',
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
					'{{WRAPPER}} .wpr-wishlist' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'wishlist_style!' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'wishlist_border_width',
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
					'{{WRAPPER}} .wpr-wishlist' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'wishlist_border_type!' => 'none',
					'wishlist_style!' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'wishlist_border_radius',
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
					'{{WRAPPER}} .wpr-wishlist' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'wishlist_style!' => 'sidebar'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_remove_icon',
			[
				'label' => esc_html__( 'Remove Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'remove_icon_styles' );
		
		$this->start_controls_tab( 
			'remove_icon_styles_normal', 
			[ 
				'label' => esc_html__( 'Normal', 'wpr-addons' ) 
			] 
		);
		
		$this->add_control(
			'remove_icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#FF4F40',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove' => 'color: {{VALUE}} !important;',
				]
			]
		);
		
		$this->add_control(
			'remove_icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove' => 'background-color: {{VALUE}};',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 
			'remove_icon_styles_hover', 
			[ 
				'label' => esc_html__( 'Hover', 'wpr-addons' ) 
			] 
		);
		
		$this->add_control(
			'remove_icon_color_hover',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#FF4F40',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove:hover' => 'color: {{VALUE}} !important;',
				]
			]
		);
		
		$this->add_control(
			'remove_icon_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove:hover' => 'background-color: {{VALUE}} !important;',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'remove_icon_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'separator' => 'before',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'top: 0;',
					'middle' => 'top: 50%; transform: translateY(-50%);',
					'bottom' => 'bottom: 0;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove' => '{{VALUE}};',
				]
			]
		);
		
		$this->add_responsive_control(
			'remove_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 12
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove::before' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);
		
		$this->add_responsive_control(
			'remove_icon_bg_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
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
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				]
			]
		);
		
		$this->add_control(
			'remove_icon_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove' => 'transition-duration: {{VALUE}}s'
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_view_button',
			[
				'label' => esc_html__( 'View Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'wishlist_style' => ['dropdown', 'sidebar']
				]
			]
		);
		
		$this->start_controls_tabs( 'button_styles' );
		
		$this->start_controls_tab(
			'wishlist_buttons_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'buttons_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-view-wishlist a.wpr-wishlist-text' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'buttons_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-view-wishlist a.wpr-wishlist-text' => 'background-color: {{VALUE}}',
				]
			]
		);
		
		$this->add_control(
			'buttons_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}}  .wpr-view-wishlist' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow',
				'selector' => '{{WRAPPER}} .actions .button,
				{{WRAPPER}}  .wpr-view-wishlist',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'buttons_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'buttons_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}}  .wpr-view-wishlist a.wpr-wishlist-text:hover' => 'color: {{VALUE}}'
				],
			]
		);
		
		$this->add_control(
			'buttons_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}}  .wpr-view-wishlist a.wpr-wishlist-text:hover' => 'background-color: {{VALUE}}',
				]
			]
		);
		
		$this->add_control(
			'buttons_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}}  .wpr-view-wishlist:hover' => 'border-color: {{VALUE}}',
				]
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow_hr',
				'selector' => '{{WRAPPER}}  .wpr-view-wishlist:hover',
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_control(
			'buttons_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
		
		$this->add_control(
			'buttons_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}}  .wpr-view-wishlist a.wpr-wishlist-text' => 'transition-duration: {{VALUE}}s'
				],
			]
		);
		
		$this->add_control(
			'buttons_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'buttons_typography',
				'selector' => '{{WRAPPER}}  .wpr-view-wishlist a.wpr-wishlist-text',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_weight'    => [
						'default' => '600',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px',
						],
					]
				]
			]
		);
		
		$this->add_control(
			'buttons_border_type',
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
					'{{WRAPPER}}  .wpr-view-wishlist' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			'buttons_border_width',
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
					'{{WRAPPER}}  .wpr-view-wishlist' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'buttons_border_type!' => 'none',
				],
			]
		);
		
		$this->add_control(
			'buttons_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-view-wishlist' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}  .wpr-view-wishlist a.wpr-wishlist-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);
		
		$this->add_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 12,
					'right' => 12,
					'bottom' => 12,
					'left' => 12,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-view-wishlist a.wpr-wishlist-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);
		
		$this->add_responsive_control(
			'buttons_distance_vertical',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}}  .wpr-view-wishlist' => 'margin-top: {{SIZE}}{{UNIT}};'
				]
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
	public function get_wishlist_from_cookie() {
        if (isset($_COOKIE['wpr_wishlist'])) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist']), true);
        } else if ( isset($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']), true);
        }
        return array();
	}

    protected function render() {

		$settings = $this->get_settings_for_display();

        $user_id = get_current_user_id();

		$icon_style = 'regular' === $settings['wishlist_button_icon_style'] ? 'far' : 'fas';

		if ($user_id > 0) {
			$wishlist = get_user_meta( get_current_user_id(), 'wpr_wishlist', true );
		
			if ( ! $wishlist ) {
				$wishlist = array();
			}
		} else {
			$wishlist = $this->get_wishlist_from_cookie();
		}

        $wishlist_count = sizeof($wishlist);
		$link_target = 'yes' == $settings['open_in_new_tab'] ? '_blank' : '_self';
		// $wishlist_link = '#' !== $this->get_id_by_slug('wpr_wishlist') ? get_page_link($this->get_id_by_slug('wpr_wishlist')) : '#';

		$this->add_render_attribute(
			'wishlist_attributes',
			[
				'data-animation' => wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ? $settings['wishlist_entrance_speed'] : ''
			]
		);

		echo '<div class="wpr-wishlist-wrap "' . $this->get_render_attribute_string( 'wishlist_attributes' ) . '>';
		
			// Get the selected compare page ID
			$wishlist_page_id = get_option( 'wpr_wishlist_page' );

			// Get the permalink to the selected page
			$wishlist_page_link = get_permalink( $wishlist_page_id );

			echo '<div class="wpr-wishlist-toggle-btn">';
				echo '<a class="wpr-inline-flex-center" href="'. $wishlist_page_link .'" target="'. $link_target .'">';
					if ( 'yes' == $settings['toggle_text'] ) {
						echo '<span  class="wpr-wishlist-text">'. esc_html__($settings['view_wishlist_text']) .'</span>';
					}
					echo '<i class="'. $icon_style .' fa-heart" title="'. esc_html__($settings['view_wishlist_text']) .'">';
						echo '<span class="wpr-wishlist-count">'. $wishlist_count .'</span>';
					echo '</i>';
				echo '</a>';
			echo '</div>';
			
			if ( 'none' !== $settings['wishlist_style'] ) {

				echo '<div class="wpr-wishlist">';
					echo '<div class=wpr-wishlist-content-wrap>';
						echo '<div class=wpr-wishlist-inner-wrap>';
							echo '<div class="wpr-close-wishlist">';
								if ( isset($settings['close_wishlist_heading'] ) && '' !== $settings['close_wishlist_heading'] ) :
									echo '<h2>'. wp_kses_post(__($settings['close_wishlist_heading'])) .'</h2>';
								endif;
								echo '<span></span>';
							echo '</div>';
							
							echo '<ul class="wpr-wishlist-products">';
								if ( empty($wishlist) ) {
									$wishlist_hidden_class = '';
									$button_hidden_class = 'wpr-hidden-element';
								} else {
									$button_hidden_class = '';
									$wishlist_hidden_class = 'wpr-wishlist-empty-hidden';
								}
								echo '<p class="wpr-wishlist-empty '. $wishlist_hidden_class .'">'. esc_html__($settings['wishlist_empty_text']) .'</p>';
								foreach ( $wishlist as $product_id ) {
									$product = wc_get_product( $product_id );
									if ( !$product ) {
										continue;
									}
									echo '<li class="wpr-wishlist-product" data-product-id="' . $product->get_id() . '">';
										echo '<a class="wpr-wishlist-product-img" href="' . $product->get_permalink() . '">' . $product->get_image() . '</a>';
										echo '<div>';
											echo '<a href="' . $product->get_permalink() . '">' . $product->get_name() . '</a>';
											echo '<div class="wpr-wishlist-product-price">' . $product->get_price_html() . '</div>';
										echo '</div>';
										echo '<span class="wpr-wishlist-remove" data-product-id="' . $product->get_id() . '"></span>';
									echo '</li>';
								}
							echo '</ul>';

						echo '<div class="wpr-wishlist-separator"></div>';
						echo '<button class="wpr-view-wishlist '. $button_hidden_class .'"><a class="wpr-wishlist-text" href="'. $wishlist_page_link .'" target="'. $link_target .'">'. esc_html__($settings['view_wishlist_text']) .'</a></button>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			
			}

		echo '</div>';

        // function create_wishlist_button() {
        // }

        // add_action( 'woocommerce_after_add_to_wishlist_button', 'create_wishlist_button' );
    }
}