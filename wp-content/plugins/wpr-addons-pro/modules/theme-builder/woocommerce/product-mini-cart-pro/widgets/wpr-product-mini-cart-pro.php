<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\ProductMiniCartPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Mini_Cart_Pro extends \WprAddons\Modules\ThemeBuilder\Woocommerce\ProductMiniCart\Widgets\Wpr_Product_Mini_Cart {

	public function add_control_mini_cart_style() {
		$this->add_control(
			'mini_cart_style',
			[
				'label' => esc_html__( 'Cart Content', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'separator' => 'before',
				'render_type' => 'template',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'dropdown' => esc_html__( 'Dropdown', 'wpr-addons' ),
					'sidebar' => esc_html__( 'Sidebar', 'wpr-addons' )
				],
				'prefix_class' => 'wpr-mini-cart-',
				'default' => 'dropdown'
			]
		); 
	}
	
	public function add_controls_group_mini_cart_style() {
		$this->add_control(
			'mini_cart_entrance',
			[
				'label' => esc_html__( 'Entrance Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
                'render_type' => 'template',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-mini-cart-',
				'condition' => [
						'mini_cart_style' => 'dropdown'
				]
			]
		);

        $this->add_control(
            'mini_cart_entrance_speed',
            [
                'label' => __( 'Entrance Speed', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 10,
                'default' => 600,
                'render_type' => 'template',
				'condition' => [
					'mini_cart_style!' => 'none'
				]
            ]
        );

		$this->add_responsive_control(
			'mini_cart_subtotal_alignment',
			[
				'label' => esc_html__( 'Subtotal & Buttons', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'bottom',
				'render_type' => 'template',
				'options' => [
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
					'auto' => [
						'title' => esc_html__( 'Auto', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					]
				],
				'prefix_class' => 'wpr-subtotal-align-',
				'condition' => [
					'mini_cart_style' => 'sidebar'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_alignment',
			[
				'label' => esc_html__( 'Cart Position', 'wpr-addons' ),
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
				'prefix_class' => 'wpr-mini-cart-align-',
				'selectors_dictionary' => [
					'left' => 'left: 0;',
					'right' => 'right: 0;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart' => '{{VALUE}}',
					// '{{WRAPPER}}.wpr-mini-cart-sidebar .widget_shopping_cart_content' => '{{VALUE}}',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .wpr-shopping-cart-inner-wrap' => '{{VALUE}}',
				],
				'condition' => [
					'mini_cart_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'mini_cart_separators',
			[
				'label'     => esc_html__('Separator', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mini_cart_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item' => 'border-bottom-color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-mini-cart__total'	=> 'border-bottom-color: {{VALUE}}'
				],
				'condition' => [
					'mini_cart_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'separator_style',
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
					'{{WRAPPER}} .woocommerce-mini-cart-item' => 'border-bottom-style: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-mini-cart__total'	=> 'border-bottom-style: {{VALUE}}'
				],
				'condition' => [
					'mini_cart_style!' => 'none'
				]
			]
		);

		$this->add_responsive_control(
			'separator_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 5
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors'    => [
					'{{WRAPPER}} .woocommerce-mini-cart-item' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .woocommerce-mini-cart__total'	=> 'border-bottom-width: {{SIZE}}{{UNIT}}'
				],
				'condition' => [
					'separator_style!' => 'none'
				],
				'condition' => [
					'mini_cart_style!' => 'none'
				]
			]
		);

		$this->add_control(
			'mini_cart_close_btn',
			[
				'label'     => esc_html__('Close Button', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mini_cart_style' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'show_mini_cart_close_btn',
			[
				'label' => esc_html__( 'Show', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'wpr-close-btn-',
				'condition' => [
					'mini_cart_style' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'close_cart_heading',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Shopping Cart', 'wpr-addons' ),
				'default' => esc_html__( 'Shopping Cart', 'wpr-addons' ),
				// 'render_type' => 'template',
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_heading_align',
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
					'{{WRAPPER}} .wpr-close-cart' => '{{VALUE}}',
				],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);
	}

	public function add_section_style_mini_cart() {
		$this->start_controls_section(
			'section_style_mini_cart',
			[
				'label' => esc_html__( 'Cart Content', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'mini_cart_image',
			[
				'label'     => esc_html__('Image', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'mini_cart_image_size',
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
					'{{WRAPPER}} .wpr-mini-cart-wrap .woocommerce-mini-cart-item' => 'grid-template-columns: {{SIZE}}% auto;'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_image_distance',
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
					'{{WRAPPER}} .wpr-mini-cart-image' => 'margin-right: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'mini_cart_close_btn_styles',
			[
				'label'     => esc_html__('Close Button', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_control(
			'mini_cart_close_btn_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .wpr-close-cart span:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_close_btn_size',
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
					'{{WRAPPER}} .wpr-close-cart span:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_close_btn_distance',
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
					'{{WRAPPER}} .wpr-close-cart' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes'
				]
			]
		);

		$this->add_control(
			'mini_cart_sidebar_heading',
			[
				'label'     => esc_html__('Heading', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes',
					'close_cart_heading!' => ''
				]
			]
		);

		$this->add_control(
			'mini_cart_sidebar_heading_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-close-cart h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'mini_cart_style' => 'sidebar',
					'show_mini_cart_close_btn' => 'yes',
					'close_cart_heading!' => ''
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_sidebar_heading_typography',
				'selector' => '{{WRAPPER}} .wpr-close-cart h2',
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
						'mini_cart_style' => 'sidebar',
						'show_mini_cart_close_btn' => 'yes',
						'close_cart_heading!' => ''
					]
			]
		);

		$this->add_control(
			'mini_cart_product_title',
			[
				'label'     => esc_html__('Title', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item a' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_title_color_hover',
			[
				'label'  => esc_html__( 'Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart-item a:hover' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_title_typography',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart-item a',
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
			'mini_cart_product_attributes',
			[
				'label'     => esc_html__('Attributes', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_attributes_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} dl.variation dt' => 'color: {{VALUE}}',
					'{{WRAPPER}} dl.variation dd' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_attributes_typography',
				'selector' => '{{WRAPPER}} dl.variation',
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
			'mini_cart_product_quantity',
			[
				'label'     => esc_html__('Quantity & Price ', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_quantity_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#777777',
				'selectors' => [
					'{{WRAPPER}} .quantity' => 'color: {{VALUE}}',
					'{{WRAPPER}} .quantity .woocommerce-Price-amount' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_quantity_typography',
				'selector' => '{{WRAPPER}} .quantity, {{WRAPPER}} .quantity .woocommerce-Price-amount',
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
			'mini_cart_subtotal',
			[
				'label'     => esc_html__('Subtotal', 'wpr-addons'),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_subtotal_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart__total' => 'color: {{VALUE}}',
					'{{WRAPPER}} .woocommerce-mini-cart__empty-message' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'mini_cart_subtotal_typography',
				'selector' => '{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart__total, {{WRAPPER}} .woocommerce-mini-cart__empty-message',
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

		$this->add_responsive_control(
			'subtotal_align',
			[
				'label'     => esc_html__('Alignment', 'wpr-addons'),
				'type'      => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options'   => [
					'left'   => [
						'title' => esc_html__('Left', 'wpr-addons'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'wpr-addons'),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__('Right', 'wpr-addons'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart__total' => 'text-align: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'mini_cart_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .widget_shopping_cart_content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .wpr-shopping-cart-inner-wrap ' => 'background-color: {{VALUE}}'
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'mini_cart_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-mini-cart' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'mini_cart_style!' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'mini_cart_overlay_color',
			[
				'label'  => esc_html__( 'Overlay Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#070707C4',
				'selectors' => [
					'{{WRAPPER}}.wpr-mini-cart-sidebar .wpr-shopping-cart-wrap ' => 'background: {{VALUE}}'
				],
				'condition' => [
					'mini_cart_style' => 'sidebar'
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
					'{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar-thumb' => 'border-right-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'mini_cart_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-mini-cart',
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
			'mini_cart_width',
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
					'{{WRAPPER}}.wpr-mini-cart-dropdown .wpr-mini-cart' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .widget_shopping_cart_content' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .wpr-shopping-cart-inner-wrap ' => 'width: {{SIZE}}{{UNIT}};'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_list_height',
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
					'size' => 300,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart' => 'max-height: {{SIZE}}{{UNIT}}; overflow-y: auto;',
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_list_distance',
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
					'{{WRAPPER}} .woocommerce-mini-cart-item' => 'margin-bottom: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}; padding-top: 0;',
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_scrollbar_width',
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
					'{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar-thumb' => 'border-right: {{SIZE}}{{UNIT}} solid;',
					'{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar' => 'min-width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{mini_cart_scrollbar_distance.SIZE}}{{mini_cart_scrollbar_distance.UNIT}});'
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_scrollbar_distance',
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
					// '{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar-thumb' => 'border-left: {{SIZE}}{{UNIT}} solid transparent;',
					'{{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{mini_cart_scrollbar_width.SIZE}}{{mini_cart_scrollbar_width.UNIT}});',
					'[data-elementor-device-mode="widescreen"] {{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{mini_cart_scrollbar_width_widescreen.SIZE}}{{mini_cart_scrollbar_width_widescreen.UNIT}});',
					'[data-elementor-device-mode="laptop"] {{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{mini_cart_scrollbar_width_laptop.SIZE}}{{mini_cart_scrollbar_width_laptop.UNIT}});',
					'[data-elementor-device-mode="tablet"] {{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{mini_cart_scrollbar_width_tablet.SIZE}}{{mini_cart_scrollbar_width_tablet.UNIT}});',
					'[data-elementor-device-mode="tablet_extra"] {{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{mini_cart_scrollbar_width_tablet_extra.SIZE}}{{mini_cart_scrollbar_width_tablet_extra.UNIT}});',
					'[data-elementor-device-mode="mobile"] {{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{mini_cart_scrollbar_width_mobile.SIZE}}{{mini_cart_scrollbar_width_mobile.UNIT}});',
					'[data-elementor-device-mode="mobile_extra"] {{WRAPPER}} .wpr-mini-cart .woocommerce-mini-cart::-webkit-scrollbar' => 'width: calc({{SIZE}}{{UNIT}} + {{mini_cart_scrollbar_width_mobile_extra.SIZE}}{{mini_cart_scrollbar_width_mobile_extra.UNIT}});',
				]
			]
		);

		$this->add_responsive_control(
			'mini_cart_distance',
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
					'{{WRAPPER}} .wpr-mini-cart' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'mini_cart_style' => 'dropdown'
				]

			]
		);

		$this->add_responsive_control(
			'mini_cart_padding',
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
					'{{WRAPPER}} .wpr-mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .widget_shopping_cart_content' => 'padding: 0 {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.wpr-mini-cart-sidebar .wpr-close-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'mini_cart_list_padding',
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
					'{{WRAPPER}} .wpr-woo-mini-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'mini_cart_border_type',
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
					'{{WRAPPER}} .wpr-mini-cart' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'mini_cart_style!' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'mini_cart_border_width',
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
					'{{WRAPPER}} .wpr-mini-cart' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'mini_cart_border_type!' => 'none',
					'mini_cart_style!' => 'sidebar'
				]
			]
		);

		$this->add_control(
			'mini_cart_border_radius',
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
					'{{WRAPPER}} .wpr-mini-cart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'mini_cart_style!' => 'sidebar'
				]
			]
		);

		$this->end_controls_section();
	}

	public function add_section_style_remove_icon() {
		$this->start_controls_section(
			'section_style_remove_icon',
			[
				'label' => esc_html__( 'Cart Content: Remove Icon', 'wpr-addons' ),
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
					'{{WRAPPER}} a.remove' => 'color: {{VALUE}} !important;',
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
					'{{WRAPPER}} a.remove' => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} a.remove:hover' => 'color: {{VALUE}} !important;',
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
					'{{WRAPPER}} a.remove:hover' => 'background-color: {{VALUE}} !important;',
				]
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'mini_cart_remove_icon_align_vr',
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
					'{{WRAPPER}} .wpr-mini-cart-remove' => '{{VALUE}};',
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
					'{{WRAPPER}} a.remove::before' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} a.remove' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-mini-cart-remove' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} a.remove' => 'transition-duration: {{VALUE}}s'
				],
			]
		);
		
		$this->end_controls_section();
	}

	public function add_section_style_buttons() {
		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => esc_html__( 'Cart Content: Buttons', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'button_styles' );

		$this->start_controls_tab(
			'cart_buttons_normal',
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow',
				'selector' => '{{WRAPPER}} .actions .button,
				{{WRAPPER}} .woocommerce-mini-cart__buttons a.button',
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:hover' => 'color: {{VALUE}}'
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'buttons_box_shadow_hr',
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:hover',
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'transition-duration: {{VALUE}}s'
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
				'selector' => '{{WRAPPER}} .woocommerce-mini-cart__buttons a.button',
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'border-style: {{VALUE}};'
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons' => 'margin-top: {{SIZE}}{{UNIT}};'
                ]
			]
		);

		$this->add_responsive_control(
			'buttons_inner_gutter',
			[
				'label' => esc_html__( 'Inner Gutter', 'wpr-addons' ),
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
					'{{WRAPPER}} .woocommerce-mini-cart__buttons a.button:first-child' => 'margin-right: {{SIZE}}{{UNIT}};'
                ]
			]
		);

		$this->add_responsive_control(
			'buttons_outer_gutter',
			[
				'label' => esc_html__( 'Outer Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-mini-cart__buttons' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};'
                ]
			]
		);

		$this->end_controls_section();
	}

	public static function render_mini_cart($settings) {
		if ( null === WC()->cart ) {
			return;
		}

		$close_cart_heading = isset($settings['close_cart_heading']) ? $settings['close_cart_heading'] : 'Shopping Cart';
		$close_cart_heading = str_replace(' ', '-', $close_cart_heading);

		$args = ['close_cart_heading' => $settings['close_cart_heading']];

		add_filter( 'woocommerce_widget_cart_is_hidden', '__return_false' );
		$widget_cart_is_hidden = apply_filters( 'woocommerce_widget_cart_is_hidden', false );
		remove_filter( 'woocommerce_widget_cart_is_hidden', '__return_false' );

		?>
		<div class="wpr-mini-cart" data-close-cart-heading="<?php echo esc_attr($close_cart_heading); ?>">
			<?php if ( !$widget_cart_is_hidden ) : ?>
				<div class="">
					<div class="" aria-hidden="true">
						<div class="wpr-shopping-cart-wrap" aria-hidden="true">
							<div class="wpr-shopping-cart-inner-wrap" aria-hidden="true">
								<div class="wpr-close-cart">
									<?php if ( isset($settings['close_cart_heading']) && '' !== $settings['close_cart_heading'] ) : ?>
										<h2><?php echo wp_kses_post($settings['close_cart_heading']); ?></h2>
									<?php endif; ?>
									<span></span>
								</div>
								<div class="widget_shopping_cart_content">
									<?php 
									woocommerce_mini_cart();
									 ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
	
}
