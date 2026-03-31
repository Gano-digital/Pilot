<?php
namespace WprAddonsPro\Modules\WooGridPro\Widgets;

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use WprAddons\Classes\Modules\WPR_Post_Likes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Woo_Grid_Pro extends \WprAddons\Modules\WooGrid\Widgets\Wpr_Woo_Grid {

	public function add_control_secondary_img_on_hover() {
		$this->add_control(
			'secondary_img_on_hover',
			[
				'label' => esc_html__( 'Secondary Img on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
			]
		);
	}

	public function add_control_open_links_in_new_tab() {
		$this->add_control(
			'open_links_in_new_tab',
			[
				'label' => esc_html__( 'Open Links in New Tab', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'render_type' => 'template',
			]
		);
	}
	
	public function add_control_query_selection() {
		$this->add_control(
			'query_selection',
			[
				'label' => esc_html__( 'Query Products', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dynamic',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'wpr-addons' ),
					'manual' => esc_html__( 'Manual', 'wpr-addons' ),
					'current' => esc_html__( 'Current Query', 'wpr-addons' ),
					'featured' => esc_html__( 'Featured', 'wpr-addons' ),
					'onsale' => esc_html__( 'On Sale', 'wpr-addons' ),
					'upsell' => esc_html__( 'Upsell', 'wpr-addons' ),
					'cross-sell' => esc_html__( 'Cross-sell', 'wpr-addons' ),
				],
			]
		);
	}
	
	public function add_control_query_orderby() {
		$this->add_control(
			'query_orderby',
			[
				'label' => esc_html__( 'Order By', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'date' => esc_html__( 'Date', 'wpr-addons' ),
					'sales' => esc_html__( 'Sales', 'wpr-addons' ),
					'rating' => esc_html__( 'Rating', 'wpr-addons' ),
					'price-low' => esc_html__( 'Price - Low to High', 'wpr-addons' ),
					'price-high' => esc_html__( 'Price - High to Low', 'wpr-addons' ),
					'random' => esc_html__( 'Random', 'wpr-addons' ),
				],
				'condition' => [
					'query_selection' => [ 'dynamic', 'onsale', 'featured', 'upsell', 'cross-sell' ], // conditions where missing
				],
			]
		);
	}
	
	public function add_control_layout_select() {
		$this->add_control(
			'layout_select',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fitRows',
				'options' => [
					'fitRows' => esc_html__( 'FitRows - Equal Height', 'wpr-addons' ),
					'masonry' => esc_html__( 'Masonry - Unlimited Height', 'wpr-addons' ),
					'list' => esc_html__( 'List Style', 'wpr-addons' ),
					'slider' => esc_html__( 'Slider / Carousel', 'wpr-addons' ),
				],
				'label_block' => true,
				'render_type' => 'template'
			]
		);
	}
	
	public function add_control_layout_columns() {
		$this->add_responsive_control(
			'layout_columns',
			[
				'label' => esc_html__( 'Columns', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 3,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'wpr-addons' ),
					2 => esc_html__( 'Two', 'wpr-addons' ),
					3 => esc_html__( 'Three', 'wpr-addons' ),
					4 => esc_html__( 'Four', 'wpr-addons' ),
					5 => esc_html__( 'Five', 'wpr-addons' ),
					6 => esc_html__( 'Six', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-grid-columns-%s',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select' => [ 'fitRows', 'masonry', 'list' ],
				]
			]
		);
	}
	
	public function add_control_layout_animation() {
		$this->add_control(
			'layout_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'fade-slide' => esc_html__( 'Fade + SlideUp', 'wpr-addons' ),
					'zoom' => esc_html__( 'Zoom', 'wpr-addons' ),
				],
				'selectors_dictionary' => [
					'default' => '',
					'fade' => 'opacity: 0',
					'fade-slide' => 'opacity: 0; top: 20px',
					'zoom' => 'opacity: 0; transform: scale(0.01)',
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-inner' => '{{VALUE}}',
				],
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);
	}

	public function add_control_sort_and_results_count() {
		$this->add_control(
			'layout_sort_and_results_count',
			[
				'label' => esc_html__( 'Show Sorting', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'separator' => 'before',
				'default' => 'no',
				'condition' => [
					'layout_select!' => 'slider',
				]
			]
		);
	}
	
	public function add_control_layout_slider_amount() {
		$this->add_responsive_control(
			'layout_slider_amount',
			[
				'label' => esc_html__( 'Columns (Carousel)', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 3,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'wpr-addons' ),
					2 => esc_html__( 'Two', 'wpr-addons' ),
					3 => esc_html__( 'Three', 'wpr-addons' ),
					4 => esc_html__( 'Four', 'wpr-addons' ),
					5 => esc_html__( 'Five', 'wpr-addons' ),
					6 => esc_html__( 'Six', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-grid-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);
	}
	
	public function add_control_layout_slider_nav_hover() {
		$this->add_control(
			'layout_slider_nav_hover',
			[
				'label' => esc_html__( 'Show on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'fade',
				'prefix_class' => 'wpr-grid-slider-nav-',
				'render_type' => 'template',
				'condition' => [
					'layout_slider_nav' => 'yes',
					'layout_select' => 'slider',

				],
			]
		);
	}
	
	public function add_control_layout_slider_dots_position() {
		$this->add_control(
			'layout_slider_dots_position',
			[
				'label' => esc_html__( 'Pagination Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
					'vertical' => esc_html__( 'Vertical', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-grid-slider-dots-',
				'render_type' => 'template',
				'condition' => [
					'layout_slider_dots' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);
	}
	
	public function add_control_stack_layout_slider_autoplay() {
		$this->add_control(
			'layout_slider_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
				'condition' => [
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'layout_slider_autoplay_duration',
			[
				'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 0,
				'max' => 15,
				'step' => 0.5,
				'frontend_available' => true,
				'condition' => [
					'layout_slider_autoplay' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);

		$this->add_control(
			'layout_slider_pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'layout_slider_autoplay' => 'yes',
					'layout_select' => 'slider',
				],
			]
		);
	}

	public function add_option_element_select() {
		$options = [
			'title' => esc_html__( 'Title', 'wpr-addons' ),
			'excerpt' => esc_html__( 'Excerpt', 'wpr-addons' ),
			'product_cat' => esc_html__( 'Categories', 'wpr-addons' ),
			'product_tag' => esc_html__( 'Tags', 'wpr-addons' ),
			'pro-cfa' => esc_html__( 'Custom Fields/Attributes (Expert)', 'wpr-addons' ),
			'status' => esc_html__( 'Status', 'wpr-addons' ),
			'price' => esc_html__( 'Price', 'wpr-addons' ),
			'sale_dates' => esc_html__( 'Sale Dates', 'wpr-addons' ),
			'rating' => esc_html__( 'Rating', 'wpr-addons' ),
			'add-to-cart' => esc_html__( 'Add to Cart', 'wpr-addons' ),
			'pro-ws' => esc_html__( 'Wishlist Button (Expert)', 'wpr-addons' ),
			'pro-cm' => esc_html__( 'Compare Button (Expert)', 'wpr-addons' ),
			'likes' => esc_html__( 'Likes', 'wpr-addons' ),
			'sharing' => esc_html__( 'Sharing', 'wpr-addons' ),
			'lightbox' => esc_html__( 'Lightbox', 'wpr-addons' ),
			'separator' => esc_html__( 'Separator', 'wpr-addons' ),
			'read-more' => esc_html__( 'View More', 'wpr-addons' )
		];

		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
			$options = [
				'title' => esc_html__( 'Title', 'wpr-addons' ),
				'excerpt' => esc_html__( 'Excerpt', 'wpr-addons' ),
				'product_cat' => esc_html__( 'Categories', 'wpr-addons' ),
				'product_tag' => esc_html__( 'Tags', 'wpr-addons' ),
				'custom-field' => esc_html__( 'Custom Fields/Attributes', 'wpr-addons' ),
				'status' => esc_html__( 'Status', 'wpr-addons' ),
				'price' => esc_html__( 'Price', 'wpr-addons' ),
				'sale_dates' => esc_html__( 'Sale Dates', 'wpr-addons' ),
				'rating' => esc_html__( 'Rating', 'wpr-addons' ),
				'add-to-cart' => esc_html__( 'Add to Cart', 'wpr-addons' ),
				'wishlist-button' => esc_html__( 'Wishlist Button', 'wpr-addons' ),
				'compare-button' => esc_html__( 'Compare Button', 'wpr-addons' ),
				'likes' => esc_html__( 'Likes', 'wpr-addons' ),
				'sharing' => esc_html__( 'Sharing', 'wpr-addons' ),
				'lightbox' => esc_html__( 'Lightbox', 'wpr-addons' ),
				'separator' => esc_html__( 'Separator', 'wpr-addons' ),
				'read-more' => esc_html__( 'View More', 'wpr-addons' )
			];
		}

		return $options;
	}
	
	public function add_repeater_args_element_custom_field() {
		return [
			'label' => esc_html__( 'Select Custom Field', 'wpr-addons' ),
			'type' => 'wpr-ajax-select2',
			'label_block' => true,
			'default' => 'default',
			'options' => 'ajaxselect2/get_custom_meta_keys_product',
			'query_slug' => 'product_cat',
			'condition' => [
				'element_select' => 'custom-field'
			],
		];
	}

	// public function add_repeater_args_element_custom_field($meta) {
	// 	return [
	// 		'label' => esc_html__( 'Select Custom Field', 'wpr-addons' ),
	// 		'type' => Controls_Manager::SELECT2,
	// 		'label_block' => true,
	// 		'default' => 'default',
	// 		'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
	// 		'options' => $meta,
	// 		'condition' => [
	// 			'element_select' => 'custom-field'
	// 		],
	// 	];
	// }

	public function add_repeater_args_element_custom_field_btn_link() {
		return [
			'label' => esc_html__( 'Use Value as Button Link', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'condition' => [
				'element_select' => 'custom-field',
				'element_custom_field_img_ID' => ''
			],
		];
	}
	
	public function add_repeater_args_element_custom_field_style() {
		return [
			'label' => esc_html__( 'Select Styling', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'wpr-grid-cf-style-1',
			'options' => [
				'wpr-grid-cf-style-1' => esc_html__( 'Custom Field Style 1', 'wpr-addons' ),
				'wpr-grid-cf-style-2' => esc_html__( 'Custom Field Style 2', 'wpr-addons' )
			],
			'condition' => [
				'element_select' => 'custom-field',
			],
			'separator' => 'after'
		];
	}
	
	public function add_repeater_args_element_custom_field_new_tab() {
		return [
			'label' => esc_html__( 'Open Link in a New Tab', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'condition' => [
				'element_select' => 'custom-field',
				'element_custom_field_btn_link' => 'yes',
			],
		];
	}

	public function add_repeater_args_element_like_icon() {
		return [
			'label' => esc_html__( 'Likes Icon', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'far fa-heart',
			'options' => [
				'fas fa-heart' => esc_html__( 'Heart', 'wpr-addons' ),
				'far fa-heart' => esc_html__( 'Heart Light', 'wpr-addons' ),
				'fas fa-thumbs-up' => esc_html__( 'Thumbs', 'wpr-addons' ),
				'far fa-thumbs-up' => esc_html__( 'Thumbs Light', 'wpr-addons' ),
				'fas fa-star' => esc_html__( 'Star', 'wpr-addons' ),
				'far fa-star' => esc_html__( 'Star Light', 'wpr-addons' ),
			],
			'condition' => [
				'element_select' => [ 'likes' ],
			]
		];
	}
	
	public function add_repeater_args_element_like_show_count() {
		return [
			'label' => esc_html__( 'Show Likes Count', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default' => 'yes',
			'selectors_dictionary' => [
				'' => 'display: none;',
				'yes' => ''
			],
			'selectors' => [
				'{{WRAPPER}} {{CURRENT_ITEM}} .wpr-post-like-count' => '{{VALUE}}',
			],
			'condition' => [
				'element_select' => [ 'likes' ],
			]
		];
	}
	
	public function add_repeater_args_element_like_text() {
		return [
			'label' => esc_html__( 'No Likes Text ', 'wpr-addons' ),
			'type' => Controls_Manager::TEXT,
			'dynamic' => [
				'active' => true,
			],
			'default' => '0',
			'separator' => 'after',
			'condition' => [
				'element_select' => [ 'likes' ],
				'element_like_show_count' => 'yes'
			],
		];
	}
	
	public function add_option_social_networks() {
		return [
			'none' => esc_html__( 'None', 'wpr-addons' ),
			'facebook-f' => esc_html__( 'Facebook', 'wpr-addons' ),
			'twitter' => esc_html__( 'Twitter', 'wpr-addons' ),
			'linkedin-in' => esc_html__( 'LinkedIn', 'wpr-addons' ),
			'pinterest-p' => esc_html__( 'Pinterest', 'wpr-addons' ),
			'reddit' => esc_html__( 'Reddit', 'wpr-addons' ),
			'tumblr' => esc_html__( 'Tumblr', 'wpr-addons' ),
			'digg' => esc_html__( 'Digg', 'wpr-addons' ),
			'xing' => esc_html__( 'Xing', 'wpr-addons' ),
			'stumbleupon' => esc_html__( 'StumpleUpon', 'wpr-addons' ),
			'vk' => esc_html__( 'vKontakte', 'wpr-addons' ),
			'odnoklassniki' => esc_html__( 'OdnoKlassniki', 'wpr-addons' ),
			'get-pocket' => esc_html__( 'Pocket', 'wpr-addons' ),
			'skype' => esc_html__( 'Skype', 'wpr-addons' ),
			'whatsapp' => esc_html__( 'WhatsApp', 'wpr-addons' ),
			'telegram' => esc_html__( 'Telegram', 'wpr-addons' ),
		];
	}
	
	public function add_repeater_args_element_sharing_icon_1() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'facebook-f',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_2() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'twitter',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_3() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'linkedin-in',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_4() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'reddit',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_5() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_6() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_trigger() {
		return [
			'label' => esc_html__( 'Trigger Button', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'condition' => [
				'element_select' => [ 'sharing' ],
			]
		];
	}
	
	public function add_repeater_args_element_sharing_trigger_icon() {
		return [
			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'fas fa-share',
			'options' => Utilities::get_svg_icons_array( 'sharing', [
				'fas fa-share' => esc_html__( 'Share', 'wpr-addons' ),
				'fas fa-share-square' => esc_html__( 'Share Square', 'wpr-addons' ),
				'far fa-share-square' => esc_html__( 'Share Sqaure Alt', 'wpr-addons' ),
				'fas fa-share-alt' => esc_html__( 'Share Alt', 'wpr-addons' ),
				'fas fa-share-alt-square' => esc_html__( 'Share Alt Square', 'wpr-addons' ),
				'fas fa-retweet' => esc_html__( 'Retweet', 'wpr-addons' ),
				'fas fa-paper-plane' => esc_html__( 'Paper Plane', 'wpr-addons' ),
				'far fa-paper-plane' => esc_html__( 'Paper Plane Alt', 'wpr-addons' ),
				'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
			] ),
			'condition' => [
				'element_select' => 'sharing',
				'element_sharing_trigger' => 'yes'
			]
		];
	}
	
	public function add_repeater_args_element_sharing_trigger_action() {
		return [
			'label' => esc_html__( 'Trigger Action', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'click',
			'options' => [
				'click' => esc_html__( 'Click', 'wpr-addons' ),
				'hover' => esc_html__( 'Hover', 'wpr-addons' ),
			],
			'condition' => [
				'element_select' => 'sharing',
				'element_sharing_trigger' => 'yes'
			]
		];
	}
	
	public function add_repeater_args_element_sharing_trigger_direction() {
		return [
			'label' => esc_html__( 'Trigger Direction', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'right',
			'options' => [
				'top' => esc_html__( 'Top', 'wpr-addons' ),
				'right' => esc_html__( 'Right', 'wpr-addons' ),
				'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
				'left' => esc_html__( 'Left', 'wpr-addons' ),
			],
			'condition' => [
				'element_select' => 'sharing',
				'element_sharing_trigger' => 'yes'
			],
			'separator' => 'after'
		];
	}
	
	public function add_repeater_args_element_sharing_tooltip() {
		return [
			'label' => esc_html__( 'Label Tooltip', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
			'separator' => 'after'
		];
	}

	public function add_repeater_args_element_show_added_tc_popup() {
		return [
			'label' => esc_html__( 'Added To Cart Action', 'wpr-addons' ),
			'description' => esc_html__( 'Open Pop-up or Mini-cart when Product added to cart ', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'none' => 'None',
				'popup' => 'Notification Popup',
				'sidebar' => 'Open Mini Cart/Sidebar'
			],
			'default' => 'none',
			'label_block' => true,
			'condition' => [
				'element_select' => 'add-to-cart'
			]
		];
	}

	public function add_repeater_args_element_show_added_to_wishlist_popup() {
		return [
			'label' => esc_html__( 'Add to Wishlist Action', 'wpr-addons' ),
			'description' => esc_html__( 'Show notification when Product added to Wishlist.', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'none' => 'None',
				'popup' => 'Notification Popup',
				'sidebar' => 'Open Mini Wishlist/Sidebar'
			],
			'default' => 'none',
			'label_block' => true,
			'condition' => [
				'element_select' => 'wishlist-button'
			]
		];
	}

	public function add_repeater_args_element_show_added_to_compare_popup() {
		return [
			'label' => esc_html__( 'Added To Compare Action', 'wpr-addons' ),
			'description' => esc_html__( 'Open Pop-up when Product added to Compare ', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'none' => 'None',
				'popup' => 'Notification Popup'
			],
			'default' => 'none',
			'label_block' => true,
			'condition' => [
				'element_select' => 'compare-button'
			]
		];
	}

	public function add_repeater_args_element_trim_text_by() {
		return [
			'word_count' => esc_html__( 'Word Count', 'wpr-addons' ),
			'letter_count' => esc_html__( 'Letter Count', 'wpr-addons' )
		];
	}
	
	public function add_control_overlay_animation_divider() {
		$this->add_control(
			'overlay_animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
	}
	
	public function add_control_overlay_image() {
		$this->add_control(
			'overlay_image',
			[
				'label' => esc_html__( 'Upload GIF', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				]
			]
		);
	}
	
	public function add_control_overlay_image_width() {
		$this->add_control(
			'overlay_image_width',
			[
				'label' => esc_html__( 'GIF Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 70,
				],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-media-hover-bg img' => 'max-width: {{SIZE}}px;',
				],
			]
		);
	}
	
	public function add_control_image_effects() {
		$this->add_control(
			'image_effects',
			[
				'label' => esc_html__( 'Select Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'zoom-in' => esc_html__( 'Zoom In', 'wpr-addons' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'wpr-addons' ),
					'grayscale-in' => esc_html__( 'Grayscale In', 'wpr-addons' ),
					'grayscale-out' => esc_html__( 'Grayscale Out', 'wpr-addons' ),
					'blur-in' => esc_html__( 'Blur In', 'wpr-addons' ),
					'blur-out' => esc_html__( 'Blur Out', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
				],
				'default' => 'none',
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails() {
		$this->add_control(
			'lightbox_popup_thumbnails',
			[
				'label' => esc_html__( 'Show Thumbnails', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails_default() {
		$this->add_control(
			'lightbox_popup_thumbnails_default',
			[
				'label' => esc_html__( 'Show Thumbs by Default', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_thumbnails' => 'true'
				]
			]
		);
	}
	
	public function add_control_lightbox_popup_sharing() {
		$this->add_control(
			'lightbox_popup_sharing',
			[
				'label' => esc_html__( 'Show Sharing Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);
	}
	
	public function add_control_filters_deeplinking() {
		$this->add_control(
			'filters_deeplinking',
			[
				'label' => esc_html__( 'Enable Deep Linking', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
			]
		);
	}
	
	public function add_control_filters_icon() {
		$this->add_control(
			'filters_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);
	}
	
	public function add_control_filters_icon_align() {
		$this->add_control(
			'filters_icon_align',
			[
				'label' => esc_html__( 'Icon Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-h-align-right',
					]
				],
				'condition' => [
					'filters_icon!' => '',
				],
			]
		);
	}
	
	public function add_control_filters_default_filter() {
		$this->add_control(
			'filters_default_filter',
			[
				'label' => esc_html__( 'Default Filter', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'description' => 'Enter your custom Category (Taxonomy) slug to filter Grid items by default.',
				'condition' => [
					'filters_linkable!' => 'yes',
				],
			]
		);
	}
	
	public function add_control_filters_count() {
		$this->add_control(
			'filters_count',
			[
				'label' => esc_html__( 'Show Count', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			]
		);
	}
	
	public function add_control_filters_count_superscript() {
		$this->add_control(
			'filters_count_superscript',
			[
				'label' => esc_html__( 'Count as Superscript', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'selectors_dictionary' => [
					'' => 'vertical-align:middle;font-size: inherit;top:0;',
					'yes' => 'vertical-align:super;font-size: x-smal;top:-3px;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters sup' => '{{VALUE}};',
				],
				'condition' => [
					'filters_count' => 'yes',
				],
			]
		);
	}
	
	public function add_control_filters_count_brackets() {
		$this->add_control(
			'filters_count_brackets',
			[
				'label' => esc_html__( 'Count Wrapper Brackets', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
				'condition' => [
					'filters_count' => 'yes',
				],
			]
		);
	}
	
	public function add_control_filters_animation() {
		$this->add_control(
			'filters_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'fade-slide' => esc_html__( 'Fade + SlideUp', 'wpr-addons' ),
					'zoom' => esc_html__( 'Zoom', 'wpr-addons' ),
				],
				'separator' => 'before',
			]
		);
	}
	
	public function add_control_pagination_type() {
		$this->add_control(
			'pagination_type',
			[
				'label' => esc_html__( 'Select Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'load-more',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'numbered' => esc_html__( 'Numbered', 'wpr-addons' ),
					'load-more' => esc_html__( 'Load More Button', 'wpr-addons' ),
					'infinite-scroll' => esc_html__( 'Infinite Scrolling', 'wpr-addons' ),
				],
				'render_type' => 'template',
				'separator' => 'after'
			]
		);
	}

	public function add_section_grid_sorting() {
		// Tab: Content ==============
		// Section: Sorting ----------
		$this->start_controls_section(
			'section_grid_sorting',
			[
				'label' => esc_html__( 'Sorting', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'query_selection!' => ['upsell', 'cross-sell'],
					'layout_select!' => 'slider',
					'layout_sort_and_results_count' => 'yes'
				],
			]
		);

		$this->add_control(
			'sort_heading',
			[
				'label' => esc_html__( 'Heading', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Shop'
			]
		);

		$this->add_control(
			'sort_heading_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h2',
				'sort_heading!' => ''
			]
		);

		$this->add_control(
			'sort_select_position',
			[
				'label' => esc_html__( 'Select Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'below',
				'options' => [
					'above' => [
						'title' => esc_html__( 'Above', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'below' => [
						'title' => esc_html__( 'Below', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					]
				],
				'render_type' => 'template',
				'prefix_class' => 'wpr-sort-select-position-',
				'sort_heading!' => ''
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function add_section_style_sort_and_results() {
		// Styles ====================
		// Section: sorting ----------
		$this->start_controls_section(
			'section_style_sort_and_results',
			[
				'label' => esc_html__( 'Sorting', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'query_selection!' => ['upsell', 'cross-sell'],
					'layout_select!' => 'slider',
					'layout_sort_and_results_count' => 'yes'
				]
			]
		);

		$this->add_control(
			'sort_and_results_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_responsive_control(
			'sort_and_results_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->add_responsive_control(
			'sort_and_results_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From Grid', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
				// 'separator' => 'before'
			]
		);

		// Results
		$this->add_control(
			'sort_title_style_heading',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'sort_title_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sort-heading :is(h1, h2, h3, h4, h5, h6)' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sort_title',
				'selector' => '{{WRAPPER}} .wpr-grid-sort-heading :is(h1, h2, h3, h4, h5, h6)',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					// 'font_size'      => [
					// 	'default'    => [
					// 		'size' => '14',
					// 		'unit' => 'px',
					// 	],
					// ]
				]
			]
		);

		$this->add_responsive_control(
			'sort_and_results_title_distance_from_grid',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sort-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				]
			]
		);

		// Results
		$this->add_control(
			'results_style_heading',
			[
				'label' => esc_html__( 'Results', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'results_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-inner-wrap .woocommerce-result-count' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'results',
				'selector' => '{{WRAPPER}} .wpr-grid-sorting-inner-wrap .woocommerce-result-count',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					// 'font_size'      => [
					// 	'default'    => [
					// 		'size' => '14',
					// 		'unit' => 'px',
					// 	],
					// ]
				]
			]
		);

		// Results
		$this->add_control(
			'sorting_style_heading',
			[
				'label' => esc_html__( 'Sorting', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'sorting_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .orderby' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .wpr-orderby-icon' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'sorting_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .orderby' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sorting_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .orderby' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sorting',
				'selector' => '{{WRAPPER}} .wpr-grid-sorting-wrap form .orderby, {{WRAPPER}} .wpr-grid-sorting-wrap form .orderby option'
			]
		);

		$this->add_responsive_control(
			'sorting_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .wpr-orderby-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				// 'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'sorting_select_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 150,
						'max' => 400,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .orderby' => 'width: {{SIZE}}{{UNIT}};',
				],
				// 'separator' => 'before'
			]
		);

		$this->add_control(
			'sorting_select_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 5,
					'right' => 15,
					'bottom' => 5,
					'left' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .orderby' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-sorting-wrap .wpr-orderby-icon' => 'right: {{LEFT}}{{UNIT}};'
				]
			]
		);

		$this->add_control(
			'sorting_border_type',
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
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .orderby' => 'border-style: {{VALUE}};',
				],
				// 'separator' => 'before',
			]
		);

		$this->add_control(
			'sorting_border_width',
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
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .orderby' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'sorting_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'sorting_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-sorting-wrap form .orderby' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();
	}

	public function add_section_added_to_cart_popup() {
		// Styles ====================
		// Section: Added to Cart Popup ------
		$this->start_controls_section(
			'section_style_added_to_cart_popup',
			[
				'label' => esc_html__( 'Popup Notifications', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
	
		// Added To Cart Text
		$this->add_control(
			'added_to_cart_popup_wrapper',
			[
				'label' => esc_html__( 'Wrapper', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
	
		$this->add_control(
			'added_to_cart_popup_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FCFCFC',
				'selectors' => [
					'{{WRAPPER}} .wpr-added-to-cart-popup' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-added-to-compare-popup' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup' => 'background-color: {{VALUE}}',
				]
			]
		);
	
		$this->add_control(
			'added_to_cart_popup_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-added-to-cart-popup' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-added-to-compare-popup' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup' => 'border-color: {{VALUE}}',
				]
			]
		);
	
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'added_to_cart_popup',
				'selector' => '{{WRAPPER}} .wpr-added-to-cart-popup, {{WRAPPER}} .wpr-added-to-compare-popup, {{WRAPPER}} .wpr-added-to-wishlist-popup',
			]
		);
	
		$this->add_responsive_control(
			'added_to_cart_popup_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'bottom',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					]
				],
				'prefix_class' => 'wpr-atc-popup-',
			]
		);

		$this->add_control(
			'popup_notification_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => 'Default',
					'scale-up' => 'Scale',
					'fade' => 'Fade',
					'slide-left' => 'Slide Left',
					'skew' => 'Skew',
				],
				'default' => 'default'
			]
		);
		
		$this->add_control(
			'popup_notification_fade_out_in',
			[
				'label' => esc_html__( 'Fade Out', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 0,
				'max' => 15
			]
		);
		
		$this->add_control(
			'popup_notification_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 15,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-added-to-cart-popup' => 'animation-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-added-to-cart-popup-hide' => 'animation-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup' => 'animation-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup-hide' => 'animation-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-added-to-compare-popup' => 'animation-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-added-to-compare-popup-hide' => 'animation-duration: {{VALUE}}s'
				]
			]
		);
	
		// Added To Cart Text
		$this->add_control(
			'added_to_cart_popup_title_heading',
			[
				'label' => esc_html__( 'Text', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
	
		$this->add_control(
			'added_to_cart_popup_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#222222',
				'selectors' => [
					'{{WRAPPER}} .wpr-added-tc-title p:first-child' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-added-tw-title p:first-child' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-added-tw-title p:first-child' => 'color: {{VALUE}}',
				],
			]
		);
	
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'added_to_cart_popup_texts',
				'selector' => '{{WRAPPER}} .wpr-added-tc-title p:first-child, {{WRAPPER}} .wpr-added-tw-title p:first-child, {{WRAPPER}} .wpr-added-tw-title p:first-child',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '',
							'unit' => 'px'
						]
					]
				]
			]
		);
	
		$this->add_control(
			'added_to_cart_text_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					]
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .wpr-added-tc-title p:first-child' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-added-tw-title p:first-child' => 'text-align: {{VALUE}};',
				]
			]
		);
	
		// Results
		$this->add_control(
			'added_to_cart_popup_link_heading',
			[
				'label' => esc_html__( 'Link', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
	
		$this->add_control(
			'added_to_cart_popup_link_hover_color',
			[
				'label'  => esc_html__( 'Link Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-added-tc-title a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-added-tw-title a' => 'color: {{VALUE}}'
				],
			]
		);
	
		$this->add_control(
			'added_to_cart_popup_link_color',
			[
				'label'  => esc_html__( 'Link Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-added-tc-title a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-added-tw-title a:hover' => 'color: {{VALUE}}'
				],
			]
		);
	
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'added_to_cart_popup_link',
				'selector' => '{{WRAPPER}} .wpr-added-tc-title a, {{WRAPPER}} .wpr-added-tw-title a',
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
			'added_to_cart_link_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-added-tc-title a' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-added-tw-title a' => 'transition-duration: {{VALUE}}s'
				]
			]
		);
	
		$this->add_control(
			'added_to_cart_link_alignment',
			[
				'label' => esc_html__( 'Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left'    => [
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
					]
				],
				'default' => 'left',
				'selectors' => [
					'{{WRAPPER}} .wpr-added-tc-title p:last-child' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wpr-added-tw-title p:last-child' => 'text-align: {{VALUE}};'
				]
			]
		);
	
		$this->add_control(
			'added_to_cart_popup_border_type',
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
					'{{WRAPPER}} .wpr-added-to-cart-popup' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-added-to-compare-popup' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);
	
		$this->add_control(
			'added_to_cart_popup_border_width',
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
					'{{WRAPPER}} .wpr-added-to-cart-popup' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-compare-popup' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'condition' => [
					'added_to_cart_popup_border_type!' => 'none',
				],
			]
		);
	
		$this->add_control(
			'added_to_cart_popup_border_radius',
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
					'{{WRAPPER}} .wpr-added-to-cart-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-compare-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
					// '{{WRAPPER}} .wpr-added-tc-popup-img' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}};',
					// '{{WRAPPER}} .wpr-added-tc-popup-img img' => 'border-top-left-radius: {{TOP}}{{UNIT}}; border-bottom-left-radius: {{LEFT}}{{UNIT}}',
					// '{{WRAPPER}} .wpr-added-tc-title' => 'border-top-right-radius: {{RIGHT}}{{UNIT}}; border-bottom-right-radius: {{BOTTOM}}{{UNIT}}'
				]
			]
		);
	
		$this->add_responsive_control(
			'added_to_cart_popup_text_padding',
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
					'{{WRAPPER}} .wpr-added-tc-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-tw-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);
	
		$this->add_responsive_control(
			'added_to_cart_popup_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-added-to-cart-popup' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-compare-popup' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	
		$this->add_responsive_control(
			'added_to_cart_popup_width',
			[
				'label' => esc_html__( 'Popup Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 350,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-added-to-cart-popup' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-compare-popup' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup' => 'width: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);
	
		$this->add_responsive_control(
			'added_to_cart_popup_img_size',
			[
				'label' => esc_html__( 'Img Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['%'],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 50,
					],
				],				
				'default' => [
					'unit' => '%',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-added-to-cart-popup .wpr-added-tc-popup-img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-compare-popup .wpr-added-tcomp-popup-img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-added-to-wishlist-popup .wpr-added-tw-popup-img' => 'width: {{SIZE}}{{UNIT}};'
				],
				'separator' => 'before'
			]
		);
	
		$this->end_controls_section();
	}

	public function add_section_style_likes() {
		$this->start_controls_section(
			'section_style_likes',
			[
				'label' => esc_html__( 'Likes', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_likes_style' );

		$this->start_controls_tab(
			'tab_grid_likes_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'likes_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'likes_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'likes_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'likes_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_likes_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'likes_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'likes_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'likes_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'likes_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'likes_typography',
				'selector' => '{{WRAPPER}} .wpr-grid-item-likes'
			]
		);

		$this->add_control(
			'likes_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'likes_border_width',
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
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'likes_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'likes_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-likes .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'likes_icon_spacing',
			[
				'label' => esc_html__( 'Icon Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes i' => 'padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'likes_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
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
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'likes_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
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
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'likes_margin',
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
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'likes_radius',
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
					'{{WRAPPER}} .wpr-grid-item-likes .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	public function add_section_style_sharing() {
		$this->start_controls_section(
			'section_style_sharing',
			[
				'label' => esc_html__( 'Sharing', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_grid_sharing_style' );

		$this->start_controls_tab(
			'tab_grid_sharing_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'sharing_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'sharing_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'sharing_tooltip_color',
			[
				'label'  => esc_html__( 'Tooltip Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-sharing-tooltip' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_tooltip_bg_color',
			[
				'label'  => esc_html__( 'Tooltip Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-sharing-tooltip' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-sharing-tooltip:before' => 'border-top-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block span[class*="wpr-grid-extra-text"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_sharing_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'sharing_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'sharing_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sharing_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sharing_typography',
				'selector' => '{{WRAPPER}} .wpr-grid-item-sharing'
			]
		);

		$this->add_control(
			'sharing_border_type',
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
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sharing_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'sharing_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'sharing_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-sharing .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'sharing_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'sharing_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
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
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'sharing_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
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
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'sharing_margin',
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
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sharing_radius',
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
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	public function add_section_style_custom_field1() {
		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {

			$this->start_controls_section(
				'section_style_custom_field1',
				[
					'label' => esc_html__( 'Custom Field Style 1', 'wpr-addons' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				]
			);

			$this->start_controls_tabs( 'tabs_grid_custom_field1_style' );

			$this->start_controls_tab(
				'tab_grid_custom_field1_normal',
				[
					'label' => esc_html__( 'Normal', 'wpr-addons' ),
				]
			);

			$this->add_control(
				'custom_field1_color',
				[
					'label'  => esc_html__( 'Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#9C9C9C',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block a' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'custom_field1_bg_color',
				[
					'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span' => 'background-color: {{VALUE}}',
					]
				]
			);

			$this->add_control(
				'custom_field1_border_color',
				[
					'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span' => 'border-color: {{VALUE}}',
					],
					'separator' => 'after',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_grid_custom_field1_hover',
				[
					'label' => esc_html__( 'Hover', 'wpr-addons' ),
				]
			);

			$this->add_control(
				'custom_field1_color_hr',
				[
					'label'  => esc_html__( 'Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#9C9C9C',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a:hover a' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span:hover a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'custom_field1_bg_color_hr',
				[
					'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a:hover' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span:hover' => 'background-color: {{VALUE}}',
					]
				]
			);

			$this->add_control(
				'custom_field1_border_color_hr',
				[
					'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a:hover' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span:hover' => 'border-color: {{VALUE}}',
					],
					'separator' => 'after',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'custom_field1_transition_duration',
				[
					'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 0.1,
					'min' => 0,
					'max' => 5,
					'step' => 0.1,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block a' => 'transition-duration: {{VALUE}}s',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span' => 'transition-duration: {{VALUE}}s',
					],
					'separator' => 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'custom_field1_typography',
					'selector' => '{{WRAPPER}} .wpr-grid-cf-style-1'
				]
			);

			$this->add_control(
				'custom_field1_border_type',
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
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a' => 'border-style: {{VALUE}};',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span' => 'border-style: {{VALUE}};',
					],
					'render_type' => 'template',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'custom_field1_border_width',
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
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
					'condition' => [
						'custom_field1_border_type!' => 'none',
					],
				]
			);

			$this->add_control(
				'custom_field1_text_spacing',
				[
					'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 25,
						],
					],				
					'default' => [
						'unit' => 'px',
						'size' => 5,
					],
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_control(
				'custom_field1_icon_spacing',
				[
					'label' => esc_html__( 'Extra Icon Spacing', 'wpr-addons' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 25,
						],
					],				
					'default' => [
						'unit' => 'px',
						'size' => 5,
					],
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'custom_field1_padding',
				[
					'label' => esc_html__( 'Padding', 'wpr-addons' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_responsive_control(
				'custom_field1_margin',
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
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_control(
				'custom_field1_radius',
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
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->end_controls_section();
		}
	}

	public function add_section_style_custom_field2() {
		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
			$this->start_controls_section(
				'section_style_custom_field2',
				[
					'label' => esc_html__( 'Custom Field Style 2', 'wpr-addons' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				]
			);

			$this->start_controls_tabs( 'tabs_grid_custom_field2_style' );

			$this->start_controls_tab(
				'tab_grid_custom_field2_normal',
				[
					'label' => esc_html__( 'Normal', 'wpr-addons' ),
				]
			);

			$this->add_control(
				'custom_field2_color',
				[
					'label'  => esc_html__( 'Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#605BE5',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block a' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'custom_field2_bg_color',
				[
					'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span' => 'background-color: {{VALUE}}',
					]
				]
			);

			$this->add_control(
				'custom_field2_border_color',
				[
					'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span' => 'border-color: {{VALUE}}',
					],
					'separator' => 'after',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_grid_custom_field2_hover',
				[
					'label' => esc_html__( 'Hover', 'wpr-addons' ),
				]
			);

			$this->add_control(
				'custom_field2_color_hr',
				[
					'label'  => esc_html__( 'Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#4A45D2',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a:hover a' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span:hover a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'custom_field2_bg_color_hr',
				[
					'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a:hover' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span:hover' => 'background-color: {{VALUE}}',
					]
				]
			);

			$this->add_control(
				'custom_field2_border_color_hr',
				[
					'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a:hover' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span:hover' => 'border-color: {{VALUE}}',
					],
					'separator' => 'after',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'custom_field2_transition_duration',
				[
					'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 0.1,
					'min' => 0,
					'max' => 5,
					'step' => 0.1,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block a' => 'transition-duration: {{VALUE}}s',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span' => 'transition-duration: {{VALUE}}s',
					],
					'separator' => 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'custom_field2_typography',
					'selector' => '{{WRAPPER}} .wpr-grid-cf-style-2'
				]
			);

			$this->add_control(
				'custom_field2_border_type',
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
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a' => 'border-style: {{VALUE}};',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span' => 'border-style: {{VALUE}};',
					],
					'render_type' => 'template',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'custom_field2_border_width',
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
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
					'condition' => [
						'custom_field2_border_type!' => 'none',
					],
				]
			);

			$this->add_control(
				'custom_field2_text_spacing',
				[
					'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 25,
						],
					],				
					'default' => [
						'unit' => 'px',
						'size' => 5,
					],
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_control(
				'custom_field2_icon_spacing',
				[
					'label' => esc_html__( 'Extra Icon Spacing', 'wpr-addons' ),
					'type' => Controls_Manager::SLIDER,
					'size_units' => ['px'],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 25,
						],
					],				
					'default' => [
						'unit' => 'px',
						'size' => 5,
					],
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'custom_field2_padding',
				[
					'label' => esc_html__( 'Padding', 'wpr-addons' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'default' => [
						'top' => 0,
						'right' => 0,
						'bottom' => 0,
						'left' => 0,
					],
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_responsive_control(
				'custom_field2_margin',
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
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_control(
				'custom_field2_radius',
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
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-2 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->end_controls_section();
		}
	}

	public function render_grid_sorting( $settings, $posts ) {
		if (isset($settings['layout_sort_and_results_count']) && 'yes' === $settings['layout_sort_and_results_count']) {
			$catalog_orderby_options = [
				'menu_order' => esc_html__('Default Sorting', 'wpr-addons'),
				'date' => esc_html__('Latest', 'wpr-addons'),
				'popularity' => esc_html__('Popularity', 'wpr-addons'),
				'rating' => esc_html__('Average Rating', 'wpr-addons'),
				'price' => esc_html__('Price: Low to High', 'wpr-addons'),
				'price-desc' => esc_html__('Price: High to Low', 'wpr-addons'),
				'title' => esc_html__('Title: A to Z', 'wpr-addons'),
				'title-desc' => esc_html__('Title: Z to A', 'wpr-addons'),
			];

			$orderby = '';

			if ( get_option('woocommerce_default_catalog_orderby') ) {
				$orderby = get_option('woocommerce_default_catalog_orderby');
			}

			if ( isset( $_GET['orderby'] ) ) {
				$orderby = $_GET['orderby'];
			}
			
			echo '<div class="wpr-grid-sorting-wrap">';
			
			if ( '' !== $settings['sort_heading'] ) {
				$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
				$sort_heading_tag = Utilities::validate_html_tags_wl( $settings['sort_heading_tag'], 'h2', $tags_whitelist );

				echo '<div class="wpr-grid-sort-heading">';
					echo '<'. $sort_heading_tag .'>'. esc_html__( $settings['sort_heading'] ) .'</'. $sort_heading_tag .'>';
				
					if ( 'above' === $settings['sort_select_position'] ) {
						?>
						<div class="wpr-grid-orderby">
							<form action="<?php echo Utilities::get_shop_url([]); ?>" method="get">
							<!-- DROPDOWN STYLE -->
								<span>
									<i class="wpr-orderby-icon fas fa-angle-down"></i>
									<select name="orderby" class="orderby" aria-label="<?php echo esc_attr__('Shop order', 'wpr-addons'); ?>">
										<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
											<option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
										<?php endforeach; ?>
									</select>
								</span>

								<?php
								// Preserve search parameters
								if ( isset( $_GET['s'] ) ) {
									echo '<input type="hidden" name="s" value="'. esc_attr($_GET['s']) .'"/>';
								}
								
								if ( isset( $_GET['post_type'] ) ) {
									echo '<input type="hidden" name="post_type" value="'. esc_attr($_GET['post_type']) .'"/>';
								}

								if ( isset( $_GET['psearch'] ) ) {
									echo '<input type="hidden" name="psearch" value="'. esc_attr($_GET['psearch']) .'"/>';
								}
								
								if ( isset( $_GET['filter_rating'] ) ) {
									echo '<input type="hidden" name="filter_rating" value="'. esc_attr($_GET['filter_rating']) .'"/>';
								}
								
								if ( isset( $_GET['filter_product_cat'] ) ) {
									echo '<input type="hidden" name="filter_product_cat" value="'. esc_attr($_GET['filter_product_cat']) .'"/>';
								}
								
								if ( isset( $_GET['filter_product_tag'] ) ) {
									echo '<input type="hidden" name="filter_product_tag" value="'. esc_attr($_GET['filter_product_tag']) .'"/>';
								}
								
								if ( isset( $_GET['min_price'] ) ) {
									echo '<input type="hidden" name="min_price" value="'. esc_attr($_GET['min_price']) .'"/>';
								}
								
								if ( isset( $_GET['max_price'] ) ) {
									echo '<input type="hidden" name="max_price" value="'. esc_attr($_GET['max_price']) .'"/>';
								}

								// Handle attribute filters
								if ( $_chosen_attributes = WC()->query->get_layered_nav_chosen_attributes() ) {
									foreach ( $_chosen_attributes as $name => $data ) {
										$filter_name = wc_attribute_taxonomy_slug( $name );
										reset($_chosen_attributes);
										if ( $name === key($_chosen_attributes) ) {
											echo '<input type="hidden" name="wprfilters" value="sort"/>';
										}
										
										if ( isset($_GET['query_type_' . $filter_name]) ) {
											echo '<input type="hidden" name="query_type_'. esc_attr($filter_name) .'" value="or"/>';
										}

										if ( isset($_GET['filter_' . $filter_name]) ) {
											echo '<input type="hidden" name="filter_'. esc_attr($filter_name) .'" value="'. esc_attr($_GET['filter_' . $filter_name]) .'"/>';
										}
									}
								}
								?>
							</form>
						</div>
						<?php
					}
				echo '</div>';
			}
			
			echo '<div class="wpr-grid-sorting-inner-wrap">';
					?>
					<div class="wpr-products-result-count">
						<p class="woocommerce-result-count">
							<?php echo sprintf(esc_html__("Showing 1–1 of %u results", 'wpr-addons'), $posts->found_posts); ?>
						</p>
					</div>
					<?php
				
				if ( 'below' === $settings['sort_select_position'] ) {
					?>
					<div class="wpr-grid-orderby">
						<form action="<?php echo Utilities::get_shop_url([]); ?>" method="get">
							<span>
								<i class="wpr-orderby-icon fas fa-angle-down"></i>
								<select name="orderby" class="orderby" aria-label="<?php echo esc_attr__('Shop order', 'wpr-addons'); ?>">
									<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
										<option value="<?php echo esc_attr($id); ?>" <?php selected($orderby, $id); ?>><?php echo esc_html($name); ?></option>
									<?php endforeach; ?>
								</select>
							</span>

							<?php
							// Preserve search parameters
							if ( isset($_GET['s']) ) {
								echo '<input type="hidden" name="s" value="'. esc_attr($_GET['s']) .'"/>';
							}
							
							if ( isset( $_GET['post_type'] ) ) {
								echo '<input type="hidden" name="post_type" value="'. esc_attr($_GET['post_type']) .'"/>';
							}

							if ( isset( $_GET['psearch'] ) ) {
								echo '<input type="hidden" name="psearch" value="'. esc_attr($_GET['psearch']) .'"/>';
							}
							
							if ( isset( $_GET['filter_rating'] ) ) {
								echo '<input type="hidden" name="filter_rating" value="'. esc_attr($_GET['filter_rating']) .'"/>';
							}
							
							if ( isset( $_GET['filter_product_cat'] ) ) {
								echo '<input type="hidden" name="filter_product_cat" value="'. esc_attr($_GET['filter_product_cat']) .'"/>';
							}
							
							if ( isset( $_GET['filter_product_tag'] ) ) {
								echo '<input type="hidden" name="filter_product_tag" value="'. esc_attr($_GET['filter_product_tag']) .'"/>';
							}
							
							if ( isset( $_GET['min_price'] ) ) {
								echo '<input type="hidden" name="min_price" value="'. esc_attr($_GET['min_price']) .'"/>';
							}
							
							if ( isset( $_GET['max_price'] ) ) {
								echo '<input type="hidden" name="max_price" value="'. esc_attr($_GET['max_price']) .'"/>';
							}
							
							if ( isset( $_GET['wprfilters'] ) ) {
								echo '<input type="hidden" name="wprfilters" value="sort"/>';
							}

							// Handle attribute filters
							if ( $_chosen_attributes = WC()->query->get_layered_nav_chosen_attributes() ) {
								foreach ( $_chosen_attributes as $name => $data ) {
									$filter_name = wc_attribute_taxonomy_slug( $name );
									reset($_chosen_attributes);
									if ( $name === key($_chosen_attributes) ) {
										echo '<input type="hidden" name="wprfilters" value="sort"/>';
									}
									
									if ( isset($_GET['query_type_' . $filter_name]) ) {
										echo '<input type="hidden" name="query_type_'. esc_attr($filter_name) .'" value="or"/>';
									}

									if ( isset($_GET['filter_' . $filter_name]) ) {
										echo '<input type="hidden" name="filter_'. esc_attr($filter_name) .'" value="'. esc_attr($_GET['filter_' . $filter_name]) .'"/>';
									}
								}
							}
							?>
						</form>
					</div>
					<?php
				}
			
			echo '</div>';
			echo '</div>';
		}
	}

	public function add_control_grid_item_even_bg_color() {
		$this->add_control(
			'grid_item_even_bg_color',
			[
				'label'  => esc_html__( 'Even Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item:nth-child(2n) .wpr-grid-item-above-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-item:nth-child(2n) .wpr-grid-item-below-content' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-item-styles-wrapper .wpr-grid-item:nth-child(2n)' => 'background-color: {{VALUE}}'
				],
			]
		);
	}
	
	public function add_control_grid_item_even_border_color() {
		$this->add_control(
			'grid_item_even_border_color',
			[
				'label'  => esc_html__( 'Even Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.wpr-item-styles-inner .wpr-grid-item:nth-child(2n) .wpr-grid-item-above-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-item-styles-inner .wpr-grid-item:nth-child(2n) .wpr-grid-item-below-content' => 'border-color: {{VALUE}}',
					'{{WRAPPER}}.wpr-item-styles-wrapper .wpr-grid-item:nth-child(2n)' => 'border-color: {{VALUE}}'
				]
			]
		);
	}
	
	public function add_control_overlay_color() {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => 'rgba(0, 0, 0, 0.25)',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-grid-media-hover-bg'
			]
		);
	}
	
	public function add_control_overlay_blend_mode() {
		$this->add_control(
			'overlay_blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => [
					'normal' => esc_html__( 'Normal', 'wpr-addons' ),
					'multiply' => esc_html__( 'Multiply', 'wpr-addons' ),
					'screen' => esc_html__( 'Screen', 'wpr-addons' ),
					'overlay' => esc_html__( 'Overlay', 'wpr-addons' ),
					'darken' => esc_html__( 'Darken', 'wpr-addons' ),
					'lighten' => esc_html__( 'Lighten', 'wpr-addons' ),
					'color-dodge' => esc_html__( 'Color-dodge', 'wpr-addons' ),
					'color-burn' => esc_html__( 'Color-burn', 'wpr-addons' ),
					'hard-light' => esc_html__( 'Hard-light', 'wpr-addons' ),
					'soft-light' => esc_html__( 'Soft-light', 'wpr-addons' ),
					'difference' => esc_html__( 'Difference', 'wpr-addons' ),
					'exclusion' => esc_html__( 'Exclusion', 'wpr-addons' ),
					'hue' => esc_html__( 'Hue', 'wpr-addons' ),
					'saturation' => esc_html__( 'Saturation', 'wpr-addons' ),
					'color' => esc_html__( 'Color', 'wpr-addons' ),
					'luminosity' => esc_html__( 'luminosity', 'wpr-addons' ),
				],
				'selectors' => [
					// '{{WRAPPER}} {{CURRENT_ITEM}} .wpr-grid-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_overlay_border_color() {
		$this->add_control(
			'overlay_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'border-color: {{VALUE}}',
				],
			]
		);
	}
	
	public function add_control_overlay_border_type() {
		$this->add_control(
			'overlay_border_type',
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
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'border-style: {{VALUE}};',
				],
			]
		);
	}
	
	public function add_control_overlay_border_width() {
		$this->add_control(
			'overlay_border_width',
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
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'overlay_border_type!' => 'none',
				],
			]
		);
	}
	
	public function add_control_title_pointer_color_hr() {
		$this->add_control(
			'title_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-item-title .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_title_pointer() {
		$this->add_control(
			'title_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'overline' => esc_html__( 'Overline', 'wpr-addons' ),
				],
			]
		);
	}
	
	public function add_control_title_pointer_height() {
		$this->add_control(
			'title_pointer_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
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
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-item-title .wpr-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-item-title .wpr-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'title_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_title_pointer_animation() {
		$this->add_control(
			'title_pointer_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'condition' => [
					'title_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_categories_pointer_color_hr() {
		$this->add_control(
			'categories_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_categories_pointer() {
		$this->add_control(
			'categories_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'overline' => esc_html__( 'Overline', 'wpr-addons' ),
				],
			]
		);
	}
	
	public function add_control_categories_pointer_height() {
		$this->add_control(
			'categories_pointer_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
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
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-product-categories .wpr-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'categories_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_categories_pointer_animation() {
		$this->add_control(
			'categories_pointer_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'condition' => [
					'categories_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_tags_pointer_color_hr() {
		$this->add_control(
			'tags_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_tags_pointer() {
		$this->add_control(
			'tags_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'overline' => esc_html__( 'Overline', 'wpr-addons' ),
				],
			]
		);
	}
	
	public function add_control_tags_pointer_height() {
		$this->add_control(
			'tags_pointer_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
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
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-product-tags .wpr-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'tags_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_tags_pointer_animation() {
		$this->add_control(
			'tags_pointer_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'condition' => [
					'tags_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_add_to_cart_animation() {
		$this->add_control(
			'add_to_cart_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-button-animations',
				'default' => 'wpr-button-none',
			]
		);
	}
	
	public function add_control_add_to_cart_animation_height() {
		$this->add_control(
			'add_to_cart_animation_height',
			[
				'label' => esc_html__( 'Animation Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [					
					'{{WRAPPER}} [class*="wpr-button-underline"]:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} [class*="wpr-button-overline"]:before' => 'height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'add_to_cart_animation' => [ 
						'wpr-button-underline-from-left',
						'wpr-button-underline-from-center',
						'wpr-button-underline-from-right',
						'wpr-button-underline-reveal',
						'wpr-button-overline-reveal',
						'wpr-button-overline-from-left',
						'wpr-button-overline-from-center',
						'wpr-button-overline-from-right'
					]
				],
			]
		);
	}
	
	public function add_control_filters_pointer_color_hr() {
		$this->add_control(
			'filters_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-filters .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_filters_pointer() {
		$this->add_control(
			'filters_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'overline' => esc_html__( 'Overline', 'wpr-addons' ),
				],
			]
		);
	}
	
	public function add_control_filters_pointer_height() {
		$this->add_control(
			'filters_pointer_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
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
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-filters .wpr-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-filters .wpr-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'filters_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_filters_pointer_animation() {
		$this->add_control(
			'filters_pointer_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'condition' => [
					'filters_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_stack_grid_slider_nav_position() {
		$this->add_control(
			'grid_slider_nav_position',
			[
				'label' => esc_html__( 'Positioning', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'custom',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-grid-slider-nav-position-',
			]
		);

		$this->add_control(
			'grid_slider_nav_position_default',
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
				'prefix_class' => 'wpr-grid-slider-nav-align-',
				'condition' => [
					'grid_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_outer_distance',
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
					'{{WRAPPER}}[class*="wpr-grid-slider-nav-align-top"] .wpr-grid-slider-arrow-container' => 'top: {{SIZE}}px;',
					'{{WRAPPER}}[class*="wpr-grid-slider-nav-align-bottom"] .wpr-grid-slider-arrow-container' => 'bottom: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-grid-slider-nav-align-top-left .wpr-grid-slider-arrow-container' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-grid-slider-nav-align-bottom-left .wpr-grid-slider-arrow-container' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-grid-slider-nav-align-top-right .wpr-grid-slider-arrow-container' => 'right: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-grid-slider-nav-align-bottom-right .wpr-grid-slider-arrow-container' => 'right: {{SIZE}}px;',
				],
				'condition' => [
					'grid_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_inner_distance',
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
					'{{WRAPPER}} .wpr-grid-slider-arrow-container .wpr-grid-slider-prev-arrow' => 'margin-right: {{SIZE}}px;',
				],
				'condition' => [
					'grid_slider_nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_position_top',
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
					'{{WRAPPER}} .wpr-grid-slider-arrow' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'grid_slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_position_left',
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
					'{{WRAPPER}} .wpr-grid-slider-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'grid_slider_nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'grid_slider_nav_position_right',
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
					'{{WRAPPER}} .wpr-grid-slider-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'grid_slider_nav_position' => 'custom',
				],
			]
		);
	}
	
	public function add_control_grid_slider_dots_hr() {
		$this->add_responsive_control(
			'grid_slider_dots_hr',
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
					'{{WRAPPER}} .wpr-grid-slider-dots' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

}