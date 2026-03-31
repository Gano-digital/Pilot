<?php
namespace WprAddonsPro\Modules\ThemeBuilder\PostNavigationPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Post_Navigation_Pro extends \WprAddons\Modules\ThemeBuilder\PostNavigation\Widgets\Wpr_Post_Navigation {

	public function add_control_display_on_separate_lines() {
		$this->add_responsive_control(
			'display_on_separate_lines',
			[
				'label' => esc_html__( 'Display on Separate Lines', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'selectors_dictionary' => [
					'' => 'display: flex;',
					'yes' => 'display: block;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation-wrap' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-post-navigation' => 'width: 100%;'
				],
				'condition' => [
					'post_nav_layout' => 'static'
				]
			]
		);
	}

	public function add_control_post_nav_layout() {
		$this->add_control(
			'post_nav_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'static',
				'options' => [
					'static' => esc_html__( 'Static Left/Right', 'wpr-addons' ),
					'fixed-default' => esc_html__( 'Fixed Default', 'wpr-addons' ),
					'fixed' => esc_html__( 'Fixed Left/Right', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_post_nav_fixed_default_align() {
		$this->add_control(
            'post_nav_fixed_default_align',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'selectors_dictionary' => [
					'left' => 'left: 0;',
					'center' => 'left: 50%;-webkit-transform: translateX(-50%);transform: translateX(-50%);',
					'right' => 'right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-fixed-default-wrap' => '{{VALUE}}',
				],
				'condition' => [
					'post_nav_layout' => 'fixed-default',
				]
            ]
        );
	}

	public function add_control_post_nav_fixed_vr() {
		$this->add_responsive_control(
			'post_nav_fixed_vr',
			[
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-fixed.wpr-post-navigation' => 'top: {{SIZE}}%;',
				],
				'condition' => [
					'post_nav_layout' => 'fixed',
				],
			]
		);
	}

	public function add_control_post_nav_arrows_loc() {
		$this->add_control(
			'post_nav_arrows_loc',
			[
				'label' => esc_html__( 'Arrows Location', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'separate',
				'options' => [
					'separate' => esc_html__( 'Separate', 'wpr-addons' ),
					'label' => esc_html__( 'Next to Label', 'wpr-addons' ),
					'title' => esc_html__( 'Next to Title', 'wpr-addons' ),
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'post_nav_arrows',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'post_nav_layout',
							'operator' => '!=',
							'value' => 'fixed',
						],
					],
				],
			]
		);
	}

	public function add_control_post_nav_title() {
		$this->add_control(
			'post_nav_title',
			[
				'label' => esc_html__( 'Show Title', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout!' => 'fixed'
				]
			]
		);
	}

	public function add_controls_group_post_nav_image() {
		$this->add_control(
			'post_nav_image',
			[
				'label' => esc_html__( 'Show Post Thumbnail', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'post_nav_image_bg',
			[
				'label' => esc_html__( 'Set as Background Image', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'post_nav_image',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'post_nav_layout',
							'operator' => '!=',
							'value' => 'fixed',
						],
					],
				],
			]
		);

		$this->add_control(
			'post_nav_image_hover',
			[
				'label' => esc_html__( 'Show Image on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'post_nav_image',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'post_nav_layout',
							'operator' => '==',
							'value' => 'fixed',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'post_nav_image_width_crop',
				'default' => 'medium',
				'condition' => [
					'post_nav_image' => 'yes',
					// 'post_nav_layout!' => 'fixed'
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_image_width',
			[
				'label' => __( 'Image Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 140,
				],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation img' => 'width: {{SIZE}}px;',
				],
				'condition' => [
					'post_nav_image' => 'yes',
					'post_nav_image_bg!' => 'yes',
					'post_nav_layout!' => 'fixed'
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_image_distance',
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
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-prev img' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-next img' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'post_nav_image' => 'yes',
					'post_nav_image_bg!' => 'yes',
					'post_nav_layout!' => 'fixed'
				],
			]
		);
	}

	public function add_controls_group_post_nav_back() {
		$this->add_control(
			'post_nav_back',
			[
				'label' => esc_html__( 'Show Back Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'condition' => [
					'post_nav_layout!' => 'fixed',
				]
			]
		);

		$this->add_control(
			'post_nav_back_link',
			[
				'label' => esc_html__( 'Back Button Link', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'post_nav_back' => 'yes',
					'post_nav_layout!' => 'fixed',
				]
			]
		);
	}

	public function add_control_post_nav_query() {
		// Get Available Taxonomies
		$post_taxonomies = Utilities::get_custom_types_of( 'tax', false );
		$post_taxonomies['all'] = esc_html__( 'All', 'wpr-addons' );

		$this->add_control(
			'post_nav_query',
			[
				'label' => esc_html__( 'Navigate Through', 'wpr-addons' ),
				'description' => esc_html__( 'If you select a taxonomy, Next and Previous posts will be in the same taxonomy term as the current post.', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => array_reverse($post_taxonomies),
				'default' => 'all',
				'separator' => 'before',
			]
		);
	}

	public function add_control_post_nav_align_vr() {
		$this->add_control(
			'post_nav_align_vr',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
                'default' => 'center',
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation a' => 'align-items: {{VALUE}}',
				],
				'separator' => 'before'
			]
		);
	}

	public function add_controls_group_post_nav_overlay_style() {
		$this->start_controls_tabs(
			'tabs_post_nav_overlay_style',
			[
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'post_nav_image',
							'operator' => '!=',
							'value' => '',
						],
						[
							'name' => 'post_nav_image_bg',
							'operator' => '!=',
							'value' => '',
						],
					],
				],
			]
		);

		$this->start_controls_tab(
			'tab_post_nav_overlay_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_overlay_color',
			[
				'label'  => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'post_nav_background_filters',
				'selector' => '{{WRAPPER}} .wpr-post-nav-overlay',
				'condition' => [
					'post_nav_image' => 'yes',
					'post_nav_image_bg' => 'yes'
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_post_nav_overlay_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);


		$this->add_control(
			'post_nav_overlay_color_hover',
			[
				'label'  => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-navigation:hover .wpr-post-nav-overlay' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'post_nav_background_filters_hover',
				'selector' => '{{WRAPPER}} .wpr-post-navigation:hover .wpr-post-nav-overlay',
				'condition' => [
					'post_nav_image' => 'yes',
					'post_nav_image_bg' => 'yes'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
	}

	public function add_section_style_post_nav_back_btn() {
		$this->start_controls_section(
			'section_style_post_nav_back_btn',
			[
				'label' => esc_html__( 'Back Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_back' => 'yes',
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_back_btn_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_back_btn_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_back_btn_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_back_btn_fill_color',
			[
				'label'  => esc_html__( 'Fill Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_back_btn_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_back_btn_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back a:hover span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'post_nav_back_btn_fill_color_ht',
			[
				'label'  => esc_html__( 'Fill Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back a:hover span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_nav_back_btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'transition: background-color {{VALUE}}s, color {{VALUE}}s',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'post_nav_back_btn_size',
			[
				'label' => esc_html__( 'Box Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back a' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-nav-back span' => 'width: calc({{SIZE}}px / 2 - {{post_nav_back_btn_gutter.SIZE}}px); height: calc({{SIZE}}px / 2 - {{post_nav_back_btn_gutter.SIZE}}px);',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'post_nav_back_btn_border_width',
			[
				'label' => esc_html__( 'Box Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_nav_back_btn_gutter',
			[
				'label' => esc_html__( 'Box Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back span' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_nav_back_btn_distance',
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
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-back' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	public function add_section_style_post_nav_title() {
		$this->start_controls_section(
			'section_style_post_nav_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_nav_title' => 'yes',
					'post_nav_layout!' => 'fixed'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_post_nav_title_style' );

		$this->start_controls_tab(
			'tab_grid_post_nav_title_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels h5' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_post_nav_title_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'post_nav_title_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#54595f',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels h5:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'post_nav_title_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-nav-labels h5' => 'transition: color {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'post_nav_title_typography',
				'selector' => '{{WRAPPER}} .wpr-post-nav-labels h5'
			]
		);

		$this->end_controls_section();
	}

}