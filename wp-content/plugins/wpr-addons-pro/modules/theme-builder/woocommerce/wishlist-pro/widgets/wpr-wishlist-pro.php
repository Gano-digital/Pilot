<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\WishlistPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Wishlist_Pro extends Widget_Base {
	
	public function get_name() {
		return 'wpr-wishlist-pro';
	}

	public function get_title() {
		return esc_html__( 'Wishlist Table', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-heart';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_archive') ? ['wpr-woocommerce-builder-widgets'] : ['wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'royal', 'wishlist', 'table', 'grid' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ------------
		$this->start_controls_section(
			'section_wishlist_settings',
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
				'separator' => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
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

		// Add to Cart
		$this->add_control(
			'layout_list_media_section',
			[
				'label' => esc_html__( 'Add to Cart', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'element_addcart_simple_txt',
			[
				'label' => esc_html__( 'Simple Item Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Add to Cart',
				'frontend_available' => true
			]
		);

		$this->add_control(
			'element_addcart_grouped_txt',
			[
				'label' => esc_html__( 'Grouped Item Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Select Options',
				'frontend_available' => true
			]
		);

		$this->add_control(
			'element_addcart_variable_txt',
			[
				'label' => esc_html__( 'Variable Item Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'View Products',
				'separator' => 'after',
				'frontend_available' => true
			]
		);

		$this->end_controls_section();
        
		// Tab: Style ==============
		// Section: General ------------
		$this->start_controls_section(
			'section_wishlist_styles_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wishlist_table_border_style',
			[
				'label' => esc_html__('Border', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-wishlist-table' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-wishlist-table th' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-wishlist-table td' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'wishlist_table_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E4E4E4',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-table' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-wishlist-table th' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-wishlist-table td' => 'border-color: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'wishlist_table_border_width',
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
					'{{WRAPPER}} .wpr-wishlist-table' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-wishlist-table th' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-wishlist-table td' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'wishlist_table_border_color!' => 'none',
				]
			]
		);

		$this->add_control(
			'wishlist_table_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 4,
					'right' => 4,
					'bottom' => 4,
					'left' => 4,
				],
				'selectors' => [
					'{{WRAPPER}} table thead tr:first-of-type th:first-of-type' => 'border-top-left-radius: {{TOP}}{{UNIT}} !important;',
					'{{WRAPPER}} table thead tr:first-of-type th:last-of-type' => 'border-top-right-radius: {{RIGHT}}{{UNIT}} !important;',
					'{{WRAPPER}} table tbody tr:last-of-type td:first-of-type' => 'border-bottom-left-radius: {{LEFT}}{{UNIT}} !important;',
					'{{WRAPPER}} table tbody tr:last-of-type td:last-of-type' => 'border-bottom-right-radius: {{BOTTOM}}{{UNIT}} !important;',
					'{{WRAPPER}} .wpr-wishlist-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;'
				]
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Headings ------
		$this->start_controls_section(
			'section_style_headings',
			[
				'label' => esc_html__( 'Headings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'table_headings_color',
			[
				'label'  => esc_html__( 'Headings Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-table tr th' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_headings_bg_color',
			[
				'label'  => esc_html__( 'Headings Bg Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-table tr th' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'headings_typography',
				'selector' => '{{WRAPPER}}  .wpr-wishlist-table tr th'
			]
		);

		$this->add_responsive_control(
			'headings_padding',
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
					'{{WRAPPER}} .wpr-wishlist-table th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'wishlist_headings_alignment_hr',
			[
				'label' => esc_html__( 'Headings Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-table th' => 'text-align: {{VALUE}};',
				]
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Content ------
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'table_content_odd_color',
			[
				'label'  => esc_html__( 'Color (Odd)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-table tr:nth-child(odd) td' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_content_even_color',
			[
				'label'  => esc_html__( 'Color (Even)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-table tr:nth-child(even) td' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_row_odd_bg_color',
			[
				'label'  => esc_html__( 'Bg Color (Odd)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-table tr:nth-child(odd) td' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'table_row_even_bg_color',
			[
				'label'  => esc_html__( 'Bg Color (Even)', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-table tr:nth-child(even) td' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}}  .wpr-wishlist-table tr td',
			]
		);

		$this->add_responsive_control(
			'content_padding',
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
					'{{WRAPPER}} .wpr-wishlist-table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'wishlist_content_alignment_hr',
			[
				'label' => esc_html__( 'Content Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-table td' => 'text-align: {{VALUE}};'
				]
			]
		);

		$this->add_control(
			'wishlist_product_name_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Title', 'wpr-addons' ),
			]
		);
		
		$this->add_control(
			'wishlist_product_name_color',
			[
				'label'     => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#787878',
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-name' => 'color: {{VALUE}}',
				],
				'separator' => 'after'
			]
		);

		$this->add_control(
			'wishlist_product_image_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Product Image', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-wishlist-img-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'wishlist_remove_icon_heading',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Remove Icon', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'wishlist_remove_icon_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#FF4F40',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove' => 'color: {{VALUE}}!important;',
				]
			]
		);

		$this->add_control(
			'wishlist_remove_icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#FFFFFF',
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove' => 'background-color: {{VALUE}}!important;',
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_remove_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
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
					'size' => 12
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-remove::before' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'wishlist_remove_icon_bg_size',
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
					'{{WRAPPER}} .wpr-wishlist-remove' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				]
			]
		);

        $this->end_controls_section();

		// Styles ====================
		// Section: Add to Cart ------
		$this->start_controls_section(
			'section_style_add_to_cart',
			[
				'label' => esc_html__( 'Add to Cart', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_add_to_cart_style' );

		$this->start_controls_tab(
			'tab_add_to_cart_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-atc a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-atc a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-atc a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-wishlist-product-atc a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_add_to_cart_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'add_to_cart_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-atc a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'add_to_cart_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-atc a.wpr-button-none:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-product-atc a.added_to_cart:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-product-atc a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-product-atc a:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-wishlist-product-atc a:after' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'add_to_cart_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-atc a:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'add_to_cart_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-wishlist-product-atc :hover a',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'add_to_cart_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		// $this->add_control_add_to_cart_animation();

		$this->add_control(
			'add_to_cart_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-atc a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-wishlist-product-atc a:before' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-wishlist-product-atc a:after' => 'transition-duration: {{VALUE}}s',
				],
			]
		);

		// $this->add_control_add_to_cart_animation_height();

		$this->add_control(
			'add_to_cart_typo_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'add_to_cart_typography',
				'selector' => '{{WRAPPER}} .wpr-wishlist-product-atc a'
			]
		);

		$this->add_control(
			'add_to_cart_border_type',
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
					'{{WRAPPER}} .wpr-wishlist-product-atc a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'add_to_cart_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-atc a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'add_to_cart_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'add_to_cart_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-wishlist-product-atc a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'add_to_cart_margin',
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
					'{{WRAPPER}} .wpr-wishlist-product-atc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'add_to_cart_radius',
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
					'{{WRAPPER}} .wpr-wishlist-product-atc a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
    }

	// Render Add To Cart
	public function render_product_add_to_cart( $settings, $product ) {

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		ob_start();

		// Get Button Class
		$button_class = implode( ' ', array_filter( [
			'product_type_'. $product->get_type(),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
		] ) );

		$attributes = [
			'rel="nofollow"',
			'class="'. esc_attr($button_class) .' wpr-button-effect '. (!$product->is_in_stock() && 'simple' === $product->get_type() ? 'wpr-atc-not-clickable' : '').'"',
			'aria-label="'. esc_attr($product->add_to_cart_description()) .'"',
			'data-product_id="'. esc_attr($product->get_id()) .'"',
			'data-product_sku="'. esc_attr($product->get_sku()) .'"',
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();

		// Button Text
		if ( 'simple' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_simple_txt'];

			if ( 'yes' === get_option('woocommerce_enable_ajax_add_to_cart') ) {
				array_push( $attributes, 'href="'. esc_url( get_permalink( $page_id ) .'/?add-to-cart='. get_the_ID() ) .'"' );
			} else {
				array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
			}
		} elseif ( 'grouped' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_grouped_txt'];
			array_push( $attributes, 'href="'. esc_url( $product->get_permalink() ) .'"' );
		} elseif ( 'variable' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_variable_txt'];
			array_push( $attributes, 'href="'. esc_url( $product->get_permalink() ) .'"' );
		} else {
			array_push( $attributes, 'href="'. esc_url( $product->get_product_url() ) .'"' );
			$button_HTML .= get_post_meta( get_the_ID(), '_button_text', true ) ? get_post_meta( get_the_ID(), '_button_text', true ) : 'Buy Product';
		}

			// Button HTML
		echo '<a '. implode( ' ', $attributes ) .'><span>'. $button_HTML .'</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		return \ob_get_clean();
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

		$this->add_render_attribute(
			'wrapper',
			[
				'class' => ['wpr-wishlist-products'],
				'element_addcart_simple_txt' => $settings['element_addcart_simple_txt'],
				'element_addcart_grouped_txt' => $settings['element_addcart_grouped_txt'],
				'element_addcart_variable_txt' => $settings['element_addcart_variable_txt']
			]
		);

		if ($user_id > 0) {
			$wishlist = get_user_meta( get_current_user_id(), 'wpr_wishlist', true );
		
			if ( ! $wishlist ) {
				$wishlist = array();
			}
		} else {
			$wishlist = $this->get_wishlist_from_cookie();
		}

        if ( ! $wishlist ) {
			echo '<p class="wpr-wishlist-empty">'. $settings['wishlist_empty_text'].'</p>'; 
			$this->add_render_attribute('wrapper', 'class', 'wpr-wishlist-empty-hidden');
        } else {
			echo '<p class="wpr-wishlist-empty wpr-wishlist-empty-hidden">'. $settings['wishlist_empty_text'].'</p>';
		}
		
		echo '<div '. $this->get_render_attribute_string('wrapper') .'>';
		echo '<table class="wpr-wishlist-table">';
			echo '<thead>';
				echo '<tr>';
					echo '<th></th>';
					echo '<th>'. esc_html__('Product', 'wpr-addons') .'</th>';
					echo '<th>'. esc_html__('Name', 'wpr-addons') .'</th>';
					echo '<th>'. esc_html__('Price', 'wpr-addons') .'</th>';
					echo '<th>'. esc_html__('Stock Status', 'wpr-addons') .'</th>';
					echo '<th></th>';
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
						echo '<td><a class="wpr-wishlist-img-wrap" href="' . $product->get_permalink() . '">' . $product->get_image() . '</a></td>';
						echo '<td><a class="wpr-wishlist-product-name" href="' . $product->get_permalink() . '">' . $product->get_name() . '</a></td>';
						echo '<td><div class="wpr-wishlist-product-price">' . $product->get_price_html() . '</div></td>';
						echo '<td><div class="wpr-wishlist-product-status">' . $stock_status . '</div></td>';
						echo '<td><div class="wpr-wishlist-product-atc">' . $this->render_product_add_to_cart( $settings, $product ) . '</div></td>';
					echo '</tr>';
				}
			echo '</tbody>';
		echo '</table>';
		echo '</div>';
    }
}