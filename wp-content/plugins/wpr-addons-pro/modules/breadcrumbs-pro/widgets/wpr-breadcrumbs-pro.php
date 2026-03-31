<?php
namespace WprAddonsPro\Modules\BreadcrumbsPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Breadcrumbs_Pro extends Widget_Base {
	
	public function get_name() {
		return 'wpr-breadcrumbs-pro';
	}

	public function get_title() {
		return esc_html__( 'Breadcrumbs', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-breadcrumbs';
	}

	public function get_categories() {
		return ['wpr-widgets'];
	}

	public function get_keywords() {
		return [ 'qq', 'product-breadcrumbs', 'breadcrumbs' ];//tmp
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_breadcrumb_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'breadcrumb_homepage',
			[
				'label' => esc_html__( 'Show Home Page', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'breadcrumb_separator',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '/',
			]
		);

		$this->add_responsive_control(
            'breadcrumb_align',
            [
                'label' => esc_html__( 'Alignment', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'label_block' => false,
                'options' => [
					'left'    => [
						'title' => __( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors_dictionary' => [
					'left' => 'text-align: left; justify-content: flex-start !important;',
					'center' => 'text-align: center; justify-content: center !important;',
					'right' => 'text-align: right; justify-content: flex-end !important;'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-post-breadcrumbs' => '{{VALUE}}',
					'{{WRAPPER}} .wpr-breadcrumbs' => '{{VALUE}}'
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Style ------------
		$this->start_controls_section(
			'section_style_breadcrumb',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'breadcrumb_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-breadcrumbs' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-breadcrumbs a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'breadcrumb_color_hr',
			[
				'label'  => esc_html__( 'Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-breadcrumbs a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'breadcrumb_typography',
				'selector' => '{{WRAPPER}} .wpr-post-breadcrumbs',
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

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$args = [
			'delimiter' => ' '. $settings['breadcrumb_separator'] .' ',
			'wrap_before' => '',
			'wrap_after' => '',
			'before' => '',
			'after' => '',
		];

		if ( '' === $settings['breadcrumb_homepage'] ) {
			$args['home'] = false;
		}

		// Output
		echo '<div class="wpr-post-breadcrumbs">';
			$settings = $this->get_settings_for_display();
		
			$show_home = $settings['breadcrumb_homepage'];
			$separator = $settings['breadcrumb_separator'];
		
			$breadcrumb_html = $this->generate_breadcrumbs( $show_home, $separator );
		
			echo $breadcrumb_html;
		echo '</div>';

	}
	
	public function post_breadcrumbs( $show_home, $separator ) {
		global $post;
		$breadcrumb = '<ul class="wpr-breadcrumbs">';
	
		// Add home link
		if ( 'yes' === $show_home ) {  
			$front_page_id = get_option( 'page_on_front' );

			if ( 'posts' === get_option( 'show_on_front' ) ) {
				$home_title = esc_html__('Home');
			} else {
				$home_title = get_the_title( $front_page_id );
			}

			if (!is_home() || (is_home() && Utilities::is_blog_archive()) ) {
				// $breadcrumb .= '<li><a href="' . home_url() . '">' . $home_title . '</a></li>';
				$breadcrumb .= '<li><a href="' . home_url() . '">' . $home_title . '</a></li>';
				$breadcrumb .= '<li>' . esc_html( $separator ) . '</li>';
			}
		}

		// Check if it's a single post, page, or custom post type
		// if (is_singular()) {
		// 	$ancestors = array_reverse( get_post_ancestors( $post->ID ) );
		// 	foreach ( $ancestors as $ancestor ) {
		// 		$breadcrumb .= '<li><a href="' . get_permalink( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></li>';
		// 		$breadcrumb .= '<li>' . esc_html( $separator ) . '</li>';
		// 	}

		// 	if ( is_single() && 'post' === $post->post_type ) {
		// 		$categories = get_the_category( $post->ID );
		// 		if ( $categories ) {
		// 			$main_category = $categories[0];
		// 			$category_parents = get_category_parents( $main_category, true, $separator );
		// 			$breadcrumb .= '<li>' . rtrim( $category_parents, $separator ) . '</li>';
		// 			$breadcrumb .= '<li>' . esc_html( $separator ) . '</li>';
		// 		}
		// 	}

		// 	$breadcrumb .= '<li>' . get_the_title( $post->ID ) . '</li>';
		// } 

		// Check if it's a single post or custom post type
		if ( is_singular() ) {
			// Check if it's a WooCommerce product
			if ( 'product' === $post->post_type && function_exists( 'wc_get_product_terms' ) ) {
				$product_categories = wc_get_product_terms( $post->ID, 'product_cat' );
				
				if ( $product_categories ) {
					$main_category = $product_categories[0];
					$breadcrumb .= $this->wpr_get_category_hierarchy( $main_category->term_id, $separator, 'product_cat' );
				}
			} elseif ( 'page' === $post->post_type ) {
				// If it's a page, display the hierarchy
				$ancestors = get_post_ancestors( $post->ID );
				if ( $ancestors ) {
					// Display ancestors in breadcrumb (oldest first, newest last)
					$ancestors = array_reverse( $ancestors );
					foreach ( $ancestors as $ancestor ) {
						$breadcrumb .= '<li><a href="' . get_permalink( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></li>';
						$breadcrumb .= '<li>'. $separator .'</li>';
					}
				}
			} else {
				// Get the post's category
				$categories = get_the_category( $post->ID );

				if ( $categories && ! empty( $categories ) ) {
					// Get the first category and display the hierarchy
					$breadcrumb .= $this->wpr_get_category_hierarchy( $categories[0]->term_id, $separator, 'category' );
				}
			}

			$breadcrumb .= '<li>' . get_the_title( $post->ID ) . '</li>';
		}

		// Check if it's an archive page (category, tag, date, or custom taxonomy)
		elseif (is_archive()) {
			$term = get_queried_object();
			$term_parents = get_ancestors( $term->term_id, $term->taxonomy, 'taxonomy' );
			$term_parents = array_reverse( $term_parents );
			foreach ( $term_parents as $parent_term_id ) {
				$parent_term = get_term( $parent_term_id );
				$breadcrumb .= '<li><a href="' . get_term_link( $parent_term_id ) . '">' . $parent_term->name . '</a></li>';
				$breadcrumb .= '<li>' . esc_html( $separator ) . '</li>';
			}
			$breadcrumb .= '<li>' . $term->name . '</li>';
		}
	
		// Check if it's a search results page
		elseif (is_search()) {
			$breadcrumb .= '<li>Search results for "' . get_search_query() . '"</li>';
		}
	
		// Check if it's a 404 page
		elseif (is_404()) {
			$breadcrumb .= '<li>404 Not Found</li>';
		}

		elseif ( Utilities::is_blog_archive() ) {
			$breadcrumb .= '<li>' . esc_html__( get_the_title(get_option('page_for_posts')), 'wpr-addons' ) . '</li>';
		}
	
		$breadcrumb .= '</ul>';
	
		return $breadcrumb;
	}
	
	public function wpr_get_category_hierarchy( $category_id, $separator, $taxonomy = 'category' ) {
		$category_chain = '';
		$current_category = get_term( $category_id, $taxonomy );
	
		if ( $current_category && ! is_wp_error( $current_category ) ) {
			$category_chain .= '<li><a href="' . get_term_link( $current_category->term_id ) . '">' . $current_category->name . '</a></li>';
			$category_chain .= '<li>' . esc_html( $separator ) . '</li>';
	
			if ( $current_category->parent ) {
				$category_chain = $this->wpr_get_category_hierarchy( $current_category->parent, $separator, $taxonomy ) . $category_chain;
			}
		}
	
		return $category_chain;
	}

	public function generate_breadcrumbs( $show_home, $separator ) {
		$breadcrumb_html = $this->post_breadcrumbs( $show_home, $separator );
		return $breadcrumb_html;
	}
}