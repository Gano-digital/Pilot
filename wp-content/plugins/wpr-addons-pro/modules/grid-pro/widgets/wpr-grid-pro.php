<?php
namespace WprAddonsPro\Modules\GridPro\Widgets;

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Grid_Pro extends \WprAddons\Modules\Grid\Widgets\Wpr_Grid {

	public function add_option_query_source() {
		$post_types = [];
		$post_types['post'] = esc_html__( 'Posts', 'wpr-addons' );
		$post_types['page'] = esc_html__( 'Pages', 'wpr-addons' );

		$custom_post_types = Utilities::get_custom_types_of( 'post', true );
		foreach( $custom_post_types as $slug => $title ) {
			if ( 'product' === $slug || 'e-landing-page' === $slug ) {
				continue;
			}

			if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
				$post_types[$slug] = esc_html( $title );
			} else {
				$post_types['pro-'. substr($slug, 0, 2)] = esc_html( $title ) .' (Expert)';
			}
		}

		$post_types['related'] = esc_html__( 'Related Query', 'wpr-addons' );
		$post_types['current'] = esc_html__( 'Current Query', 'wpr-addons' );
		
		return $post_types;
	}

	public function get_available_taxonomies() {
		$post_taxonomies = [];
		$post_taxonomies['category'] = esc_html__( 'Categories', 'wpr-addons' );
		$post_taxonomies['post_tag'] = esc_html__( 'Tags', 'wpr-addons' );

		$custom_post_taxonomies = Utilities::get_custom_types_of( 'tax', true );
		foreach( $custom_post_taxonomies as $slug => $title ) {
			if ( 'product_tag' === $slug || 'product_cat' === $slug ) {
				continue;
			}

			if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
				$post_taxonomies[$slug] = esc_html( $title );
			} else {
				$post_taxonomies['pro-'. substr($slug, 0, 2)] = esc_html( $title ) .' (Expert)';
			}
		}

		return $post_taxonomies;
	}

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
				'render_type' => 'template'
			]
		);
	}

	public function add_control_grid_lazy_loading() {
		$this->add_control(
			'grid_lazy_loading',
			[
				'label' => esc_html__( 'Lazy Loading', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template'
			]
		);
	}

	public function add_control_display_scheduled_posts() { 
		$this->add_control(
			'display_scheduled_posts',
			[
				'label' => esc_html__( 'Display Only Scheduled Posts', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);	
	}

	public function add_control_query_randomize() {
		$this->add_control(
			'query_randomize',
			[
				'label' => esc_html__( 'Randomize Query', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'rand',
			]
		);
	}

	public function add_control_order_posts() {
        $this->add_control(
			'order_posts',
			[
				'label' => esc_html__( 'Order By', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'label_block' => false,
				'options' => [
					'date' => esc_html__( 'Date', 'wpr-addons'),
					'title' => esc_html__( 'Title', 'wpr-addons'),
					'modified' => esc_html__( 'Last Modified', 'wpr-addons'),
					'ID' => esc_html__( 'Post ID', 'wpr-addons' ),
					'author' => esc_html__( 'Post Author', 'wpr-addons' ),
					'comment_count' => esc_html__( 'Comment Count', 'wpr-addons' ),
					'meta_value' => esc_html__( 'Custom Field', 'wpr-addons' )
				],
				'condition' => [
					'query_randomize!' => 'rand',
				]
			]
		);
	}
	
	public function add_control_order_posts_by_acf( $meta ) {
        $this->add_control(
			'order_posts_by_acf',
			[
				'label' => esc_html__( 'Select Custom Field', 'wpr-addons' ),
				// 'type' => Controls_Manager::SELECT2,
				'type' => 'wpr-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
				// 'options' => $meta,
				'options' => 'ajaxselect2/get_custom_meta_keys',
				'condition' => [
					'order_posts' => 'meta_value'
				],
			]
		);
	}

	public function add_control_query_slides_to_show() {
		$this->add_control(
			'query_slides_to_show',
			[
				'label' => esc_html__( 'Slides to Show', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 0,
				'condition' => [
					'layout_select' => 'slider',
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
				'default' => 'masonry',
				'options' => [
					'fitRows' => esc_html__( 'FitRows - Equal Height', 'wpr-addons' ),
					'masonry' => esc_html__( 'Masonry - Unlimited Height', 'wpr-addons' ),
					'list' => esc_html__( 'List Style', 'wpr-addons' ),
					'slider' => esc_html__( 'Slider / Carousel', 'wpr-addons' ),
				],
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
					'default' => esc_html__( 'None', 'wpr-addons' ),
					'zoom' => esc_html__( 'Zoom', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'fade-slide' => esc_html__( 'Fade + SlideUp', 'wpr-addons' ),
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
	
	public function add_control_layout_slider_autoplay() {
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
	}

	public function add_controls_group_layout_slider_autoplay() {
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
			'content' => esc_html__( 'Content', 'wpr-addons' ),
			'excerpt' => esc_html__( 'Excerpt', 'wpr-addons' ),
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'time' => esc_html__( 'Time', 'wpr-addons' ),
			'author' => esc_html__( 'Author', 'wpr-addons' ),
			'comments' => esc_html__( 'Comments', 'wpr-addons' ),
			'read-more' => esc_html__( 'Read More', 'wpr-addons' ),
			'likes' => esc_html__( 'Likes', 'wpr-addons' ),
			'sharing' => esc_html__( 'Sharing', 'wpr-addons' ),
			'lightbox' => esc_html__( 'Lightbox', 'wpr-addons' ),
			'separator' => esc_html__( 'Separator', 'wpr-addons' ),
		];

		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
			$options['custom-field'] = esc_html__( 'Custom Field', 'wpr-addons' );
		} else {
			$options['pro-cf'] = esc_html__( 'Custom Field (Expert)', 'wpr-addons' );
		}

		return $options;
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
	
	public function add_repeater_args_element_custom_field( $meta ) {
		return [
			'label' => esc_html__( 'Select Custom Field', 'wpr-addons' ),
			'type' => 'wpr-ajax-select2',
			'label_block' => true,
			'default' => 'default',
			'description' => '<strong>Note:</strong> This option only accepts String(Text) or Numeric Custom Field Values.',
			'options' => 'ajaxselect2/get_custom_meta_keys',
			'condition' => [
				'element_select' => 'custom-field'
			],
		];
	}
	
	public function add_repeater_args_element_custom_field_img_ID() {
		return [
			'label' => esc_html__( 'Use Value as Image ID', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'condition' => [
				'element_select' => 'custom-field'
			],
		];
	}
	
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
	
	public function add_repeater_args_element_custom_field_new_tab() {
		return [
			'label' => esc_html__( 'Open Link in a New Tab', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'condition' => [
				'element_select' => 'custom-field',
				'element_custom_field_btn_link' => 'yes',
				'element_custom_field_img_ID' => ''
			],
		];
	}
	
	// public function add_repeater_args_custom_field_wrapper_html_divider1() {
	// 	return [
	// 		'type' => Controls_Manager::DIVIDER,
	// 		'style' => 'thick',
	// 		'condition' => [
	// 			'element_select' => 'custom-field',
	// 			'element_custom_field_btn_link' => 'yes',
	// 			'element_custom_field_img_ID' => ''
	// 		],
	// 	];
	// }
	
	public function add_repeater_args_element_custom_field_wrapper() {
		return [
			'label' => esc_html__( 'Wrap with HTML', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'condition' => [
				'element_select' => 'custom-field',
				'element_custom_field_img_ID' => '',
				'element_custom_field_btn_link' => '',
			],
		];
	}
	
	public function add_repeater_args_element_custom_field_wrapper_html() {
		return [
			'label' => esc_html__( 'Custom HTML Wrapper', 'wpr-addons' ),
			'description' => 'Insert <strong>*cf_value*</strong> to dislpay your Custom Field.',
			'placeholder'=> 'For Ex: <span>*cf_value*</span>',
			'type' => Controls_Manager::TEXTAREA,
			'dynamic' => [
				'active' => true,
			],
			'condition' => [
				'element_select' => 'custom-field',
				'element_custom_field_wrapper' => 'yes',
				'element_custom_field_img_ID' => '',
				'element_custom_field_btn_link' => '',
			],
		];
	}
	
	public function add_repeater_args_custom_field_wrapper_html_divider2() {
		return [
			'type' => Controls_Manager::DIVIDER,
			'style' => 'thick',
			'condition' => [
				'element_select' => 'custom-field',
			],
		];
	}
	
	public function add_repeater_args_element_cf_tag() {
		return [
			'label' => esc_html__( 'HTML Tag', 'wpr-addons' ),
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
			'default' => 'span',
			'condition' => [
				'element_select' => 'custom-field',
			]
		];
	}
	
	public function add_repeater_args_element_custom_field_style() {
		return [
			'label' => esc_html__( 'Select Styling', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'wpr-grid-cf-style-1',
			'options' => [
				'wpr-grid-cf-style-1' => esc_html__( 'Custom Field Style 1', 'wpr-addons' ),
				'wpr-grid-cf-style-2' => esc_html__( 'Custom Field Style 2', 'wpr-addons' ),
				'wpr-grid-cf-style-3' => esc_html__( 'Custom Field Style 3', 'wpr-addons' ),
				'wpr-grid-cf-style-4' => esc_html__( 'Custom Field Style 4', 'wpr-addons' ),
			],
			'condition' => [
				'element_select' => 'custom-field',
			],
			'separator' => 'after'
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
				'separator' => 'after'
			]
		);
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
					'bottom' => 0,
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
					'{{WRAPPER}} .wpr-grid-item-sharing .inner-block a' => 'transition-duration: {{VALUE}}s;',
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

	public function add_section_style_custom_field3() {
		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
			$this->start_controls_section(
				'section_style_custom_field3',
				[
					'label' => esc_html__( 'Custom Field Style 3', 'wpr-addons' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				]
			);

			$this->start_controls_tabs( 'tabs_grid_custom_field3_style' );

			$this->start_controls_tab(
				'tab_grid_custom_field3_normal',
				[
					'label' => esc_html__( 'Normal', 'wpr-addons' ),
				]
			);

			$this->add_control(
				'custom_field3_color',
				[
					'label'  => esc_html__( 'Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#605BE5',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block a' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'custom_field3_bg_color',
				[
					'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span' => 'background-color: {{VALUE}}',
					]
				]
			);

			$this->add_control(
				'custom_field3_border_color',
				[
					'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span' => 'border-color: {{VALUE}}',
					],
					'separator' => 'after',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_grid_custom_field3_hover',
				[
					'label' => esc_html__( 'Hover', 'wpr-addons' ),
				]
			);

			$this->add_control(
				'custom_field3_color_hr',
				[
					'label'  => esc_html__( 'Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#4A45D2',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a:hover a' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span:hover a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'custom_field3_bg_color_hr',
				[
					'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a:hover' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span:hover' => 'background-color: {{VALUE}}',
					]
				]
			);

			$this->add_control(
				'custom_field3_border_color_hr',
				[
					'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a:hover' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span:hover' => 'border-color: {{VALUE}}',
					],
					'separator' => 'after',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'custom_field3_transition_duration',
				[
					'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 0.1,
					'min' => 0,
					'max' => 5,
					'step' => 0.1,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block a' => 'transition-duration: {{VALUE}}s',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span' => 'transition-duration: {{VALUE}}s',
					],
					'separator' => 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'custom_field3_typography',
					'selector' => '{{WRAPPER}} .wpr-grid-cf-style-3'
				]
			);

			$this->add_control(
				'custom_field3_border_type',
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
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a' => 'border-style: {{VALUE}};',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span' => 'border-style: {{VALUE}};',
					],
					'render_type' => 'template',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'custom_field3_border_width',
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
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
					'condition' => [
						'custom_field3_border_type!' => 'none',
					],
				]
			);

			$this->add_control(
				'custom_field3_text_spacing',
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
						'{{WRAPPER}} .wpr-grid-cf-style-3 .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_control(
				'custom_field3_icon_spacing',
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
						'{{WRAPPER}} .wpr-grid-cf-style-3 .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'custom_field3_padding',
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
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_responsive_control(
				'custom_field3_margin',
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
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_control(
				'custom_field3_radius',
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
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-3 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->end_controls_section();
		}
	}

	public function add_section_style_custom_field4() {
		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
			$this->start_controls_section(
				'section_style_custom_field4',
				[
					'label' => esc_html__( 'Custom Field Style 4', 'wpr-addons' ),
					'tab' => Controls_Manager::TAB_STYLE,
					'show_label' => false,
				]
			);

			$this->start_controls_tabs( 'tabs_grid_custom_field4_style' );

			$this->start_controls_tab(
				'tab_grid_custom_field4_normal',
				[
					'label' => esc_html__( 'Normal', 'wpr-addons' ),
				]
			);

			$this->add_control(
				'custom_field4_color',
				[
					'label'  => esc_html__( 'Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#605BE5',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block a' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'custom_field4_bg_color',
				[
					'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span' => 'background-color: {{VALUE}}',
					]
				]
			);

			$this->add_control(
				'custom_field4_border_color',
				[
					'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span' => 'border-color: {{VALUE}}',
					],
					'separator' => 'after',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_grid_custom_field4_hover',
				[
					'label' => esc_html__( 'Hover', 'wpr-addons' ),
				]
			);

			$this->add_control(
				'custom_field4_color_hr',
				[
					'label'  => esc_html__( 'Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#4A45D2',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a:hover a' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span:hover' => 'color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span:hover a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'custom_field4_bg_color_hr',
				[
					'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a:hover' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span:hover' => 'background-color: {{VALUE}}',
					]
				]
			);

			$this->add_control(
				'custom_field4_border_color_hr',
				[
					'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
					'type' => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a:hover' => 'border-color: {{VALUE}}',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span:hover' => 'border-color: {{VALUE}}',
					],
					'separator' => 'after',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_control(
				'custom_field4_transition_duration',
				[
					'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 0.1,
					'min' => 0,
					'max' => 5,
					'step' => 0.1,
					'selectors' => [
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block a' => 'transition-duration: {{VALUE}}s',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span' => 'transition-duration: {{VALUE}}s',
					],
					'separator' => 'after',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'custom_field4_typography',
					'selector' => '{{WRAPPER}} .wpr-grid-cf-style-4'
				]
			);

			$this->add_control(
				'custom_field4_border_type',
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
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a' => 'border-style: {{VALUE}};',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span' => 'border-style: {{VALUE}};',
					],
					'render_type' => 'template',
					'separator' => 'before',
				]
			);

			$this->add_control(
				'custom_field4_border_width',
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
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
					'condition' => [
						'custom_field4_border_type!' => 'none',
					],
				]
			);

			$this->add_control(
				'custom_field4_text_spacing',
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
						'{{WRAPPER}} .wpr-grid-cf-style-4 .wpr-grid-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .wpr-grid-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->add_control(
				'custom_field4_icon_spacing',
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
						'{{WRAPPER}} .wpr-grid-cf-style-4 .wpr-grid-extra-icon-left' => 'padding-right: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .wpr-grid-extra-icon-right' => 'padding-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'custom_field4_padding',
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
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_responsive_control(
				'custom_field4_margin',
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
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'render_type' => 'template',
				]
			);

			$this->add_control(
				'custom_field4_radius',
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
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .wpr-grid-cf-style-4 .inner-block > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator' => 'before',
				]
			);

			$this->end_controls_section();
		}
	}

	public function render_post_custom_field( $settings, $class, $post_id ) {
		$custom_field_value = get_post_meta( $post_id, $settings['element_custom_field'], true );
		$custom_field_html = $settings['element_custom_field_wrapper_html'];

		// Check if the custom field is a date and format it
		if ( !is_array($custom_field_value) && strtotime( $custom_field_value ) !== false ) {
			if ( function_exists('get_field_object') && get_field_object($settings['element_custom_field'], $post_id) && isset(get_field_object($settings['element_custom_field'], $post_id)['display_format']) ) {
				$date_format = get_field_object($settings['element_custom_field'], $post_id)['display_format'];
			} else {
				$date_format = get_option('date_format');
			}
			
			if ( \DateTime::createFromFormat($date_format, $custom_field_value) !== false ) {
				$custom_field_value = date_i18n( $date_format, strtotime( $custom_field_value ) );
			}
		}

		if ( has_filter('wpr_update_custom_field_value') ) {
			ob_start();
			apply_filters('wpr_update_custom_field_value', $custom_field_value, $post_id, $settings['element_custom_field']);
			$custom_field_value = ob_get_clean();
		}

		// Get First Value if Array (works only for single value checkboxes)
		if ( is_array($custom_field_value) && 1 === count($custom_field_value) ) {
			$custom_field_value = $custom_field_value[0];
		}

		// Erase if Array or Object
		if ( ! is_string( $custom_field_value ) && ! is_numeric( $custom_field_value ) ) {
			$custom_field_value = '';
		}

		// Return if Empty
		if ( '' === $custom_field_value ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .' '. $settings['element_custom_field_style'] .'">';
			echo '<div class="inner-block">';
				if ( 'yes' === $settings['element_custom_field_btn_link'] ) {
					$target = 'yes' === $settings['element_custom_field_new_tab'] ? '_blank' : '_self';
					echo '<a href="'. esc_url($custom_field_value) .'" target="'. esc_attr($target) .'">';
				} else {
					echo '<span>';
				}

				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="wpr-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Custom Field
				if ( 'yes' === $settings['element_custom_field_img_ID'] ) {
					$cf_img = wp_get_attachment_image_src( $custom_field_value, 'full' );
					if ( isset($cf_img) && is_array($cf_img) ) {
						echo '<img src="'. esc_url($cf_img[0]) .'" alt="" width="'. esc_attr($cf_img[1]) .'" height="'. esc_attr($cf_img[2]) .'">';
					}
				} else {
					if ( 'yes' !== $settings['element_custom_field_btn_link'] ) {
						$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
						$element_cf_tag = Utilities::validate_html_tags_wl( $settings['element_cf_tag'], 'span', $tags_whitelist );

						echo '<'. esc_attr($element_cf_tag) .'>';
							if ( 'yes' === $settings['element_custom_field_wrapper'] ) {
								echo str_replace( '*cf_value*', $custom_field_value, $custom_field_html );
							} else {
								echo $custom_field_value;
							}
						echo '</'. esc_attr($element_cf_tag) .'>';
					}
				}

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="wpr-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}
				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				if ( 'yes' === $settings['element_custom_field_btn_link'] ) {
					echo '</a>';
				} else {
					echo '</span>';
				}
			echo '</div>';
		echo '</div>';
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
				]
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
					'{{WRAPPER}} .wpr-grid-media-hover-bg' => 'mix-blend-mode: {{VALUE}}', // Wasn't working because of the {{CURRENT_ITEM}} selector
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
	
	public function add_control_read_more_animation() {
		$this->add_control(
			'read_more_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-button-animations',
				'default' => 'wpr-button-none',
			]
		);
	}
	
	public function add_control_read_more_animation_height() {
		$this->add_control(
			'read_more_animation_height',
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
					'read_more_animation' => [ 
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
	
	public function add_control_tax1_custom_colors($meta) {
		$this->add_control(
			'tax1_custom_color_switcher',
			[
				'label' => esc_html__( 'Enable Custom Colors', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'tax1_custom_color_field_text',
			[
				'label' => esc_html__( 'Select Text Color Field', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => 'default',
				'description' => '<strong>Note:</strong> This option only accepts Color Custom Field Values.',
				'options' => $meta,
				'condition' => [
					'tax1_custom_color_switcher' => 'yes'
				],
			]
		);

		$this->add_control(
			'tax1_custom_color_field_bg',
			[
				'label' => esc_html__( 'Select Background Color Field', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'default' => 'default',
				'description' => '<strong>Note:</strong> This option only accepts Color Custom Field Values.',
				'options' => $meta,
				'condition' => [
					'tax1_custom_color_switcher' => 'yes'
				],
			]
		);
	}
	
	public function add_control_tax1_pointer_color_hr() {
		$this->add_control(
			'tax1_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_tax1_pointer() {
		$this->add_control(
			'tax1_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'overline' => esc_html__( 'Overline', 'wpr-addons' ),
				],
			]
		);
	}
	
	public function add_control_tax1_pointer_height() {
		$this->add_control(
			'tax1_pointer_height',
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
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-tax-style-1 .wpr-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'tax1_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_tax1_pointer_animation() {
		$this->add_control(
			'tax1_pointer_animation',
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
					'tax1_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_tax2_pointer_color_hr() {
		$this->add_control(
			'tax2_pointer_color_hr',
			[
				'label'  => esc_html__( 'Hover Effect Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-pointer-item:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-pointer-item:after' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);
	}
	
	public function add_control_tax2_pointer() {
		$this->add_control(
			'tax2_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'overline' => esc_html__( 'Overline', 'wpr-addons' ),
				],
			]
		);
	}
	
	public function add_control_tax2_pointer_height() {
		$this->add_control(
			'tax2_pointer_height',
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
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-pointer-item:before' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-grid-tax-style-2 .wpr-pointer-item:after' => 'height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'tax2_pointer' => [ 'underline', 'overline' ],
				],
			]
		);
	}
	
	public function add_control_tax2_pointer_animation() {
		$this->add_control(
			'tax2_pointer_animation',
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
					'tax2_pointer' => [ 'underline', 'overline' ],
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