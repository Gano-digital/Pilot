<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\PageMyAccountPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Page_My_Account_Pro extends Widget_Base {
	
	public function get_name() {
		return 'wpr-my-account-pro';
	}

	public function get_title() {
		return esc_html__( 'My Account', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-my-account';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [] : ['wpr-woocommerce-builder-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'account', 'product', 'page', 'account page', 'page account', 'My Account', 'woocommerce' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	public function is_reload_preview_required() {
		return true;
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ---------
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'tabs_layout',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'vertical' => esc_html__( 'Vertical', 'wpr-addons' ),
					'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
				],
				'default' => 'vertical',
				'render_type' => 'template',
				'prefix_class' => 'wpr-my-account-tabs-',
			]
		);

		$this->add_control( //TODO: change approach
			'text_align',
			[
				'label' => esc_html__( 'Text Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control( //TODO: change approach
			'tabs_alignment',
			[
				'label' => esc_html__( 'Tabs Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-h-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					],
					'stretch' => [
						'title' => esc_html__( 'Stretch', 'wpr-addons' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'prefix_class' => 'wpr-account-tabs-',
				'selectors_dictionary' => [
					'flex-start' => 'display: flex; justify-content: flex-start',
					'center' => 'display: flex; justify-content: center',
					'flex-end' => 'display: flex; justify-content: flex-end;',
					'stretch' => 'display: flex; justify-content: space-between;'
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation ul' => '{{VALUE}};'
				],
				'condition' => [
					'tabs_layout' => 'horizontal'
				]
			]
		);

		$this->add_responsive_control(
			'tabs_spacing',
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
					'size' => 5,
				],
				'selectors' => [
					// '{{WRAPPER}}.wpr-my-account-tabs-horizontal .woocommerce-MyAccount-navigation' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-navigation' => 'margin-right: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-content' => 'width: calc(70% - {{SIZE}}px);'
					'{{WRAPPER}}.wpr-my-account-tabs-horizontal .woocommerce-MyAccount-content' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-content' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-content' => 'width: calc(100% - {{tab_width.SIZE}}px - {{SIZE}}px);',
					'[data-elementor-device-mode="mobile"] {{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-content' => 'width: calc(100%  - {{tab_width_mobile.SIZE}}px - {{SIZE}}px);'
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Tab: Content ==============
		// Section: Tab Labels -----
		$this->start_controls_section(
			'section_tab_labels',
			[
				'label' => esc_html__( 'Tab Labels', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$repeater = new Repeater();
		
		$repeater->add_control(
			'tab_label_name',
			[
				'label' => esc_html__( 'Tab Name', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
			]
		);
		
		$this->add_control(
			'tab_labels',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'item_actions' => [
					'add' => false,
					'duplicate' => false,
					'remove' => false,
					'sort' => false,
				],
				'default' => [
					[
						'field_key' => 'dashboard',
						'field_label' => esc_html__( 'Dashboard', 'wpr-addons' ),
						'tab_label_name' => esc_html__( 'Dashboard', 'wpr-addons' ),
					],
					[
						'field_key' => 'orders',
						'field_label' => esc_html__( 'Orders', 'wpr-addons' ),
						'tab_label_name' => esc_html__( 'Orders', 'wpr-addons' ),
					],
					[
						'field_key' => 'downloads',
						'field_label' => esc_html__( 'Downloads', 'wpr-addons' ),
						'tab_label_name' => esc_html__( 'Downloads', 'wpr-addons' ),
					],
					[
						'field_key' => 'edit-address',
						'field_label' => esc_html__( 'Addresses', 'wpr-addons' ),
						'tab_label_name' => esc_html__( 'Addresses', 'wpr-addons' ),
					],
					[
						'field_key' => 'edit-account',
						'field_label' => esc_html__( 'Account Details', 'wpr-addons' ),
						'tab_label_name' => esc_html__( 'Account Details', 'wpr-addons' ),
					],
					[
						'field_key' => 'wishlist',
						'field_label' => esc_html__( 'Wishlist', 'wpr-addons' ),
						'tab_label_name' => esc_html__( 'Wishlist', 'wpr-addons' ),
					],
					[
						'field_key' => 'customer-logout',
						'field_label' => esc_html__( 'Logout', 'wpr-addons' ),
						'tab_label_name' => esc_html__( 'Logout', 'wpr-addons' ),
					],
				],
				'title_field' => '{{{ tab_label_name }}}',
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Tab Labels -----
		$this->start_controls_section(
			'section_tab_styles',
			[
				'label' => esc_html__( 'Tab Labels', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'tabs_style' );

		$this->start_controls_tab( 
			'normal_tabs_style',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tabs_text_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation-link a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_control(
			'tab_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'frontend_available' => true,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s'
				]
			]
		);

		$this->add_control(
			'tab_border_type',
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
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_border_width',
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
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'tab_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'tab_border_radius',
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
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a'=> 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tab_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'tab_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				// 'allowed_dimensions' => ['left', 'right'],
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 6,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tab_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-my-account-tabs-vertical .woocommerce-MyAccount-navigation' => 'width: {{SIZE}}px'
				],
				'condition' => [
					'tabs_layout' => 'vertical'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 
			'hover_tabs_style',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'hover_tab_text_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link:hover a' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'hover_tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'alpha' => true,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link:hover a' => 'border-bottom-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'tab_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link:hover a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography_hover',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation-link:hover a',
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

		$this->end_controls_tab();

		$this->start_controls_tab( 
			'active_tabs_style',
			[
				'label' => esc_html__( 'Active', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'active_tab_text_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link.is-active a' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'active_tab_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'alpha' => true,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link.is-active a' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_active_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-navigation-link.is-active a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography_active',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-navigation-link.is-active a',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '16',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Tab Content ---------
		$this->start_controls_section(
			'section_tab_content_styles',
			[
				'label' => esc_html__( 'Tab Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tab_content_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'tab_content_link_color',
			[
				'label' => esc_html__( 'Link Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-form-login .woocommerce-LostPassword a' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'tab_content_link_color_hover',
			[
				'label' => esc_html__( 'Link Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-form-login .woocommerce-LostPassword a:hover' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'tab_content_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_content_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_content_typography',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content-wrapper',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px'
						]
					]
				]
			]
		);

		$this->add_control(
			'tab_content_title_paragraph_distance',
			[
				'label' => esc_html__( 'Headings Distance', 'wpr-addons' ),
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
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper>p:first-of-type' => 'margin-bottom: {{SIZE}}px'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_content_border_type',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tab_content_border_width',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'my_account_table_border!' => 'none',
				]
			]
		);

		$this->add_responsive_control(
			'tab_content_border_radius',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'tab_content_padding',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Orders ---------
		$this->start_controls_section(
			'my_account_order_styles',
			[
				'label' => __( 'Orders Table', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'my_account_table_heading_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Table Heading', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'my_account_table_heading_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table thead th' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table tfoot th' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'my_account_table_heading_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table thead th' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table tfoot th' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'my_account_table_heading_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.woocommerce-orders-table th, {{WRAPPER}} table.shop_table thead th, {{WRAPPER}} table.shop_table tfoot th',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px'
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'my_account_table_heading_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.shop_table thead th' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.shop_table tfoot th' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'my_account_table_heading_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 8,
					'right' => 8,
					'bottom' => 8,
					'left' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} table.woocommerce-orders-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table tfoot th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'my_account_table_description_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Table Description', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'my_account_table_description_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table td a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'my_account_table_description_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
				'type'      => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'my_account_table_description_typography',
				'label'    => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} table.shop_table td',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px'
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'my_account_table_description_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => [
					'start' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} table.shop_table .variation' => 'justify-content: {{VALUE}};',
					'{{WRAPPER}} table.shop_table .wc-item-meta' => 'justify-content: {{VALUE}};'
				]
			]
		);

		$this->add_responsive_control(
			'my_account_table_description_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 10,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} table.shop_table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'my_account_table_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} table.shop_table th' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} table.shop_table td' => 'border-color: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'my_account_table_border',
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
					'{{WRAPPER}} table.shop_table th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} table.shop_table td' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'my_account_table_border_width',
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
					'{{WRAPPER}} table.shop_table th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} table.shop_table td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'my_account_table_border!' => 'none'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Wishlist ---------
		$this->start_controls_section(
			'my_account_wishlist_styles',
			[
				'label' => __( 'Wishlist', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'product_image_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-products img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Addresses ---------
		$this->start_controls_section(
			'my_account_addresses_styles',
			[
				'label' => __( 'Addresses', 'wpr-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'addresses_titles',
				'selector' => '{{WRAPPER}} .woocommerce-Address-title h3',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '18',
							'unit' => 'px',
						]
					]
				]
			]
		);

		$this->add_responsive_control(
			'my_account_addresses_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0
				],
				'selectors' => [
					'{{WRAPPER}} address' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					// '{{WRAPPER}} .woocommerce-Address' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Button ---------
		$this->start_controls_section(
			'section_style_my_account_button',
			[
				'label' => esc_html__( 'Table Buttons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'my_account_button_styles' );

		$this->start_controls_tab(
			'my_account_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'my_account_button_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'my_account_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'my_account_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'my_account_button_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button, {{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'my_account_button_typography',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button, {{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
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
			'my_account_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'my_account_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'my_account_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button:hover' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'my_account_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'my_account_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'my_account_button_box_shadow_hr',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button:hover, {{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'my_account_button_border_type',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button' => 'border-style: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button' => 'border-style: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'my_account_button_border_width',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'my_account_button_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'my_account_button_radius',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'my_account_button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 15,
					'bottom' => 10,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'my_account_button_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content .download-file a.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content .woocommerce-orders-table__cell a.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'button_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
                'selectors' => [
					'{{WRAPPER}} .woocommerce-pagination' => 'text-align: {{VALUE}};'
				],
				'separator' => 'before'
			]
		); // TODO: determine location and selectors

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Forms ---------
		$this->start_controls_section(
			'section_account_forms',
			[
				'label' => esc_html__( 'Forms', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'form_labels_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Heading', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'form_headings_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h2' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_headings_typography',
				'selector' => '{{WRAPPER}} h2'
			]
		);
		
		$this->add_control(
			'form_labels_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Labels', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'form_labels_color',
			[
				'label' => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-form-login label' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-form-register label' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_labels_typography',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content-wrapper label, {{WRAPPER}} .woocommerce-form-login label, {{WRAPPER}} .woocommerce-form-register label',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
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

		$this->add_responsive_control(
			'my_account_form_labels_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 5,
					'bottom' => 5,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-login label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-register label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'form_sub_labels_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Sub-Labels', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->add_control(
			'form_sub_labels_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#D8D8D8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-form-row em' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'form_sub_labels_typography',
				'selector' => '{{WRAPPER}} .woocommerce-form-row em',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_control(
			'form_inputs_title',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Inputs', 'wpr-addons' ),
				'separator' => 'before'
			]
		);

		$this->start_controls_tabs( 'forms_fields_styles' );

		$this->start_controls_tab( 
            'forms_fields_normal_styles',
            [ 
                'label' => esc_html__( 'Normal', 'wpr-addons' ) 
            ] 
        );

		$this->add_control(
			'forms_fields_normal_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-login .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-register .input-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text::placeholder' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered' => 'color: {{VALUE}} !important;'
				]
			]
		);

		$this->add_control(
			'forms_fields_normal_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-login .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-register .input-text' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered' => 'border-color: {{VALUE}} !important;'
				]
			]
		);

		$this->add_control(
			'forms_fields_normal_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-login .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-register .input-text' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered' => 'background-color: {{VALUE}} !important;'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_normal_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text, {{WRAPPER}} .woocommerce-form-login .input-text, {{WRAPPER}} .woocommerce-form-register .input-text, {{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'form_fields_typography',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text, {{WRAPPER}} .woocommerce-form-login .input-text, {{WRAPPER}} .woocommerce-form-register .input-text, {{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '14',
							'unit' => 'px'
						]
					]
				]
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'forms_fields_focus_styles', 
			[ 
				'label' => esc_html__( 'Focus', 'wpr-addons' )
			] 
		);

		$this->add_control(
			'forms_fields_focus_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-login .input-text:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-register .input-text:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered:focus' => 'color: {{VALUE}};'
				],
			]
		);
		

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'forms_fields_focus_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text:focus, {{WRAPPER}} select:focus, {{WRAPPER}} .woocommerce-form-login .input-text:focus, {{WRAPPER}} .woocommerce-form-register .input-text:focus, {{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered:focus',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'form_fields_border_type',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-login .input-text' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-form-register .input-text' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'form_fields_border_width',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-login .input-text' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-register .input-text' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'form_fields_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'form_fields_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-login .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-register .input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_control(
			'form_fields_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-login .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-form-register .input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper .select2-selection__rendered' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'fieldset_distance',
			[
				'label' => esc_html__( 'Change Password Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content-wrapper fieldset' => 'margin-top: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Button ---------
		$this->start_controls_section(
			'section_style_account_forms_button',
			[
				'label' => esc_html__( 'Form Buttons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'account_forms_button_styles' );

		$this->start_controls_tab(
			'account_forms_button_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'account_forms_button_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} button.button' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'account_forms_button_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} button.button' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'account_forms_button_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} button.button' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'account_forms_button_box_shadow',
				'selector' => '{{WRAPPER}} button.button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'account_forms_button_typography',
				'selector' => '{{WRAPPER}} button.button',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px'
						]
					]
				]
			]
		);

		$this->add_control(
			'account_forms_button_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} button.button' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'account_forms_button_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'account_forms_button_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} button.button:hover' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'account_forms_button_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#342ECB',
				'selectors' => [
					'{{WRAPPER}} button.button:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'account_forms_button_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} button.button:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'account_forms_button_box_shadow_hr',
				'selector' => '{{WRAPPER}} button.button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'account_forms_button_border_type',
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
					'{{WRAPPER}} button.button' => 'border-style: {{VALUE}}'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'account_forms_button_border_width',
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
					'{{WRAPPER}} button.button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'account_forms_button_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'account_forms_button_radius',
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
					'{{WRAPPER}} button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'account_forms_button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 12,
					'right' => 25,
					'bottom' => 12,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'account_forms_button_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} button.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'forms_button_align',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
                'selectors' => [
					'{{WRAPPER}} .edit-account>p:last-child' => 'text-align: {{VALUE}};'
				],
				'separator' => 'before'
			]
		); // TODO: determine location and selectors

		$this->end_controls_section();

		$this->start_controls_section(
			'section_notice_styles',
			[
				'label' => esc_html__( 'Notice', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'notice_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-info' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-error' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'notice_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-info' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-error' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'success_notice_accent_color',
			[
				'label' => esc_html__( 'Success Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#8fae1b',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-message::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'info_notice_accent_color',
			[
				'label' => esc_html__( 'Info Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#30B5FF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-info' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-info::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'error_notice_accent_color',
			[
				'label' => esc_html__( 'Error Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF19FD',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-error' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-error::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'notice_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .woocommerce-message, {{WRAPPER}} .woocommerce-info, {{WRAPPER}} .woocommerce-error',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'notice_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 18,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message::before' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .woocommerce-error::before' => 'font-size: {{SIZE}}px;',
					'{{WRAPPER}} .woocommerce-info::before' => 'font-size: {{SIZE}}px;'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'notice_border_type',
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
					'{{WRAPPER}} .woocommerce-message' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-Message' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-info' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .woocommerce-error' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'notice_border_width',
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
					'{{WRAPPER}} .woocommerce-message' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-Message' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'notice_border_type!' => 'none',
				]
			]
		);

		$this->add_control(
			'notice_border_radius',
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
					'{{WRAPPER}} .woocommerce-message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-Message' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'notice_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 13,
					'right' => 25,
					'bottom' => 13,
					'left' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + {{notice_icon_size.SIZE}}px + 20px);',
					'{{WRAPPER}} .woocommerce-error' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + {{notice_icon_size.SIZE}}px + 20px);',
					'{{WRAPPER}} .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} calc({{LEFT}}{{UNIT}} + {{notice_icon_size.SIZE}}px + 20px);',
					'{{WRAPPER}} .woocommerce-message::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-error::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .woocommerce-info::before' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

        $this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_btn',
			[
				'label' => esc_html__( 'Notice Buttons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_style' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#696969',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content a.button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'btn_typography',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content a.button',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '15',
							'unit' => 'px'
						]
					]
				]
			]
		);

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_color_hr',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#696969',
				'selectors' => [
					'{{WRAPPER}}  .woocommerce-MyAccount-content a.button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_border_color_hr',
			[
				'label' => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} .woocommerce-MyAccount-content a.button:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_border_type',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'btn_border_width',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'btn_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'btn_radius',
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
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 30,
					'bottom' => 10,
					'left' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-MyAccount-content a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

        $this->end_controls_section();
    }

	public function before_account_content() {
		$wrapper_class = $this->get_account_content_wrapper( [ 'context' => 'frontend' ] );

		echo '<div class="' . sanitize_html_class( $wrapper_class ) . '">';
	}

	public function after_account_content() {
		echo '</div>';
	}

	public function woocommerce_get_myaccount_page_permalink( $bool ) {
		return get_permalink();
	}

	public function woocommerce_logout_default_redirect_url( $redirect ) {
		return $redirect . '?wpr-addons_wc_logout=true&wpr-addons_my_account_redirect=' . esc_url( get_permalink() );
	} // TODO: what to use instead wpr-addons prefix
	
	private function render_html_front_end() {
		$current_endpoint = $this->get_current_endpoint();
		?>
		<div class="wpr-my-account-tab wpr-my-account-tab__<?php echo sanitize_html_class( $current_endpoint ); ?>">
			<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
		</div>
		<?php
	}

	private function render_html_editor() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="wpr-my-account-tab">
			<div class="woocommerce">
			<?php
				wc_get_template( 'myaccount/navigation.php' );

			// In the editor, output all the tabs in order to allow for switching between them via JS.
			$pages = $this->get_account_pages();

			global $wp_query;
			foreach ( $pages as $page => $page_value ) {
				foreach ( $pages as $unset_tab => $unset_tab_value ) {
					unset( $wp_query->query_vars[ $unset_tab ] );
				}
				$wp_query->query_vars[ $page ] = $page_value;

				$wrapper_class = $this->get_account_content_wrapper( [
					'context' => 'editor',
					'page' => $page,
				] );
				?>
				<div class="woocommerce-MyAccount-content" <?php echo $page ? 'wpr-my-account-page="' . esc_attr( $page ) . '"' : ''; ?>>
					<div class="<?php echo sanitize_html_class( $wrapper_class ); ?>">
						<?php
						if ( 'dashboard' === $page ) {
							wc_get_template(
								'myaccount/dashboard.php',
								[
									'current_user' => get_user_by( 'id', get_current_user_id() ),
								]
							);
						} else {
							do_action( 'woocommerce_account_' . $page . '_endpoint', $page_value );
						}
						?>
					</div>
				</div>
			<?php } ?>
			</div>
		</div>
		<?php
	}

	private function get_current_endpoint() {
		global $wp_query;
		$current = '';

		$pages = $this->get_account_pages();

		foreach ( $pages as $page => $val ) {
			if ( isset( $wp_query->query[ $page ] ) ) {
				$current = $page;
				break;
			}
		}

		if ( '' === $current && isset( $wp_query->query_vars['page'] ) ) {
			$current = 'dashboard'; // Dashboard is not an endpoint so it needs a custom check.
		}

		return $current;
	}

	private function get_account_content_wrapper( $args ) {
		$user_id = get_current_user_id();
		$num_orders = wc_get_customer_order_count( $user_id );
		$num_downloads = count( wc_get_customer_available_downloads( $user_id ) );
		$class = 'woocommerce-MyAccount-content-wrapper';

		/* we need to render a different css class if there are no orders/downloads to display
		 * as the no orders/downloads screen should not have the default padding and border
		 * around it but show the 'no orders/downloads' notification only
		 */
		if ( 'frontend' === $args['context'] ) { // Front-end display
			global $wp_query;
			if ( ( 0 === $num_orders && isset( $wp_query->query_vars['orders'] ) ) || ( 0 === $num_downloads && isset( $wp_query->query_vars['downloads'] ) ) ) {
				$class .= '-no-data';
			}
		} else { // Editor display
			if ( ( 0 === $num_orders && 'orders' === $args['page'] ) || ( 0 === $num_downloads && 'downloads' === $args['page'] ) ) {
				$class .= '-no-data';
			}
		}

		return $class;
	}
	
	private function get_account_pages() {
		$pages = [
			'dashboard' => '',
			'orders' => '',
			'downloads' => '',
			'edit-address' => '',
		];

		// Check if payment gateways support add new payment methods.
		$support_payment_methods = false;
		foreach ( WC()->payment_gateways->get_available_payment_gateways() as $gateway ) {
			if ( $gateway->supports( 'add_payment_method' ) || $gateway->supports( 'tokenization' ) ) {
				$support_payment_methods = true;
				break;
			}
		}

		if ( $support_payment_methods ) {
			$pages['payment-methods'] = '';
		}

		// Edit account.
		$pages['edit-account'] = '';

		// Get the latest order (if there is one) for view-order (order preview) page.
		$recent_order = wc_get_orders( [
			'limit' => 1,
			'orderby'  => 'date',
			'order'    => 'DESC',
		] );

		if ( ! empty( $recent_order ) ) {
			$pages['view-order'] = $recent_order[0]->get_id();
		}

		return $pages;
	}
		
	public function wishlist_content() {
	
		$wishlist = get_user_meta( get_current_user_id(), 'wpr_wishlist', true );
		if ( ! $wishlist ) {
			$wishlist = array();
		}
		echo '<h3>' . __( 'My Wishlist', 'wpr-addons' ) . '</h3>';
		echo '<table class="wpr-wishlist-products">';
			echo '<thead>';
				echo '<tr>';
					echo '<th></th>';
					echo '<th>'. esc_html__('Product', 'wpr-addons') .'</th>';
					echo '<th>'. esc_html__('Name', 'wpr-addons') .'</th>';
					echo '<th>'. esc_html__('Price', 'wpr-addons') .'</th>';
					echo '<th>'. esc_html__('Stock Status', 'wpr-addons') .'</th>';
				echo '</tr>';
			echo '</thead>';
			echo '<tbody>';
				foreach ( $wishlist as $product_id ) {
					$product = wc_get_product( $product_id );
					if ( ! $product ) {
						continue;
					}
					
					$stock_status = $product->get_stock_status() == 'instock' ? esc_html__('In Stock', 'wpr-addons') : esc_html__('Out of Stock', 'wpr-addons');

					echo '<tr class="wpr-wishlist-product" data-product-id="' . $product->get_id() . '">';
						echo '<td><span class="wpr-wishlist-remove" data-product-id="' . $product->get_id() . '"></span></td>';
						echo '<td><a href="' . $product->get_permalink() . '">' . $product->get_image() . '</a></td>';
						echo '<td><a href="' . $product->get_permalink() . '">' . $product->get_name() . '</a></td>';
						echo '<td><div class="wpr-wishlist-product-price">' . $product->get_price_html() . '</div></td>';
						echo '<td><div class="wpr-wishlist-product-status">' . $stock_status . '</div></td>';
					echo '</tr>';
				}
			echo '</tbody>';
		echo '</table>';
	}
	
	public function reorder_my_account_menu_items( $items ) {
		$new_order = array();
		// loop through the menu items
		foreach ( $items as $name => $item ) {
			if ( $name == 'customer-logout' ) {
				$new_order['wishlist'] = $items['wishlist'];
			}
			$new_order[ $name ] = $item;
		}
		return $new_order;
	}


	public function modify_menu_items($items, $endpoints) {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['tab_labels'] ) ) {
			foreach ( $settings['tab_labels'] as $tab ) {
				if ( isset( $tab['tab_label_name'] ) && isset( $items[ $tab['field_key'] ] ) ) {
					$items[ $tab['field_key'] ] = $tab['tab_label_name'];
				}
			}
		}

		return $items;
	}

    protected function render() {
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();
		$settings = $this->get_settings_for_display();

		// Simulate a logged out user so that all WooCommerce sections will render in the Editor
		if ( $is_editor ) {
			$store_current_user = wp_get_current_user()->ID;
			wp_set_current_user( 0 );
		}

		// Add actions & filters before displaying our Widget.

		add_filter( 'woocommerce_account_menu_items', [ $this, 'modify_menu_items' ], 10, 2 );
		add_action( 'woocommerce_account_content', [ $this, 'before_account_content' ], 2 );
		add_action( 'woocommerce_account_content', [ $this, 'after_account_content' ], 95 );
		add_filter( 'woocommerce_get_myaccount_page_permalink', [ $this, 'woocommerce_get_myaccount_page_permalink' ], 10, 1 );
		add_filter( 'woocommerce_logout_default_redirect_url', [ $this, 'woocommerce_logout_default_redirect_url' ], 10, 1 );
		
		if ( get_option('wpr_add_wishlist_to_my_account') == 'on' && defined('WPR_ADDONS_PRO_VERSION') && wpr_fs()->is_plan( 'expert' ) ) {
			add_action( 'woocommerce_account_wishlist_endpoint', [$this, 'wishlist_content'] );
			add_filter( 'woocommerce_account_menu_items', [$this, 'reorder_my_account_menu_items'] );
		}

		// Display our Widget.
		if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$this->render_html_front_end();
		} else {
			$this->render_html_editor();
			?>

			<form class="woocommerce-form woocommerce-form-login login" method="post">
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="username">Username or email address&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="">			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="password">Password&nbsp;<span class="required">*</span></label>
				<span class="password-input"><input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password"><span class="show-password-input"></span></span>
			</p>

			
			<p class="form-row">
				<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever"> <span>Remember me</span>
				</label>
				<input type="hidden" id="woocommerce-login-nonce" name="woocommerce-login-nonce" value="7e7ee3206a"><input type="hidden" name="_wp_http_referer" value="/royal-wp/my-account/">				
				<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="Log in">Log in</button>
			</p>
			<p class="woocommerce-LostPassword lost_password">
				<a href="#">Lost your password?</a>
			</p>
			</form>

			<?php
		}

		// Remove actions & filters after displaying our Widget.
		remove_action( 'woocommerce_account_menu_items', [ $this, 'modify_menu_items' ], 10 );
		remove_action( 'woocommerce_account_content', [ $this, 'before_account_content' ], 5 );
		remove_action( 'woocommerce_account_content', [ $this, 'after_account_content' ], 95 );
		remove_filter( 'woocommerce_get_myaccount_page_permalink', [ $this, 'woocommerce_get_myaccount_page_permalink' ], 10, 1 );
		// remove_filter( 'woocommerce_logout_default_redirect_url', [ $this, 'woocommerce_logout_default_redirect_url' ], 10, 1 );

		// Return to existing logged-in user after widget is rendered.
		if ( $is_editor ) {
			wp_set_current_user( $store_current_user );
		}
    }
}