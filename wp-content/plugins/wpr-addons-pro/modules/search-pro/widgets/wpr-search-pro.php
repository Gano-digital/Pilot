<?php
namespace WprAddonsPro\Modules\SearchPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Search_Pro extends \WprAddons\Modules\Search\Widgets\Wpr_Search {

	public function add_control_search_query() {
		$search_post_type = Utilities::get_custom_types_of( 'post', false );
		$search_post_type = array_merge( [ 'all' => esc_html__( 'All', 'wpr-addons' ) ], $search_post_type );

		foreach ( $search_post_type as $key => $value ) {
			if ( 'all' != $key && 'post' != $key && 'page' != $key && 'product' != $key && 'e-landing-page' != $key && !wpr_fs()->is_plan( 'expert' ) ) {
				$search_post_type['pro-'. $key] = $value .' (Expert)';
				unset($search_post_type[$key]);
			} else {
				$search_post_type[$key] = $value .'';
			}
		}

		$this->add_control(
			'search_query',
			[
				'label' => esc_html__( 'Select Query', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'options' => $search_post_type,
				'default' => 'all',
			]
		);
	}

	public function add_control_select_category() {
		$this->add_control(
			'select_category',
			[
				'label' => esc_html__( 'Enable Taxonomy Filter', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'search_query!' => 'all'
				]
			]
		);
	}

	public function add_control_all_cat_text() {
		$this->add_control(
			'all_cat_text',
			[
				'label' => esc_html__( 'All Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'All Categories', 'wpr-addons' ),
				'condition' => [
					'search_query!' => 'all',
					'select_category' => 'yes'
				]
			]
		);

		$search_post_type = Utilities::get_custom_types_of( 'post', false );
		$search_post_type = array_merge( [ 'all' => esc_html__( 'All', 'wpr-addons' ) ], $search_post_type );

		foreach ( $search_post_type as $key => $value ) {
			if ( 'all' != $key && 'post' != $key && 'page' != $key && 'product' != $key && 'e-landing-page' != $key && !wpr_fs()->is_plan( 'expert' ) ) {
				$search_post_type['pro-'. $key] = $value .' (Expert)';
				unset($search_post_type[$key]);
			} else {
				$search_post_type[$key] = $value .'';
			}
		}

		// Taxonomies
		foreach ( $search_post_type as $slug => $title ) {
			$this->add_control(
				'query_taxonomy_'. $slug,
				[
					'label' => esc_html__( $title. ' Taxonomies', 'wpr-addons' ),
					'type' => 'wpr-ajax-select2',
					'options' => 'ajaxselect2/get_post_type_taxonomies',
					'query_slug' => $slug,
					'multiple' => true,
					'label_block' => true,
					'condition' => [
						'search_query!' => 'all',
						'select_category' => 'yes',
						'search_query' => $slug,
					],
				]
			);
		}
	}

	public function add_control_number_of_results() {
        $this->add_control(
            'number_of_results',
            [
                'label' => __( 'Number of Results', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'step' => 1,
                'default' => 10,
                'render_type' => 'template',
                'condition' => [
                    'ajax_search' => 'yes'
                ]
            ]
        );
	}

	public function render_search_pagination($settings) {
		if ( 'yes' === $settings['ajax_search'] ) :

			echo '<div class="wpr-ajax-search-pagination elementor-clearfix wpr-ajax-search-pagination-load-more">';
			echo '<button class="wpr-load-more-results">'. esc_html__($settings['pagination_load_more_text'], 'wpr-addons') .'</button>';
			echo '<div class="wpr-pagination-loading">';
				switch ( $settings['pagination_animation'] ) {
					case 'loader-1':
						echo '<div class="wpr-double-bounce">';
							echo '<div class="wpr-child wpr-double-bounce1"></div>';
							echo '<div class="wpr-child wpr-double-bounce2"></div>';
						echo '</div>';
						break;
					case 'loader-2':
						echo '<div class="wpr-wave">';
							echo '<div class="wpr-rect wpr-rect1"></div>';
							echo '<div class="wpr-rect wpr-rect2"></div>';
							echo '<div class="wpr-rect wpr-rect3"></div>';
							echo '<div class="wpr-rect wpr-rect4"></div>';
							echo '<div class="wpr-rect wpr-rect5"></div>';
						echo '</div>';
						break;
					case 'loader-3':
						echo '<div class="wpr-spinner wpr-spinner-pulse"></div>';
						break;
					case 'loader-4':
						echo '<div class="wpr-chasing-dots">';
							echo '<div class="wpr-child wpr-dot1"></div>';
							echo '<div class="wpr-child wpr-dot2"></div>';
						echo '</div>';
						break;
					case 'loader-5':
						echo '<div class="wpr-three-bounce">';
							echo '<div class="wpr-child wpr-bounce1"></div>';
							echo '<div class="wpr-child wpr-bounce2"></div>';
							echo '<div class="wpr-child wpr-bounce3"></div>';
						echo '</div>';
						break;
					case 'loader-6':
						echo '<div class="wpr-fading-circle">';
							echo '<div class="wpr-circle wpr-circle1"></div>';
							echo '<div class="wpr-circle wpr-circle2"></div>';
							echo '<div class="wpr-circle wpr-circle3"></div>';
							echo '<div class="wpr-circle wpr-circle4"></div>';
							echo '<div class="wpr-circle wpr-circle5"></div>';
							echo '<div class="wpr-circle wpr-circle6"></div>';
							echo '<div class="wpr-circle wpr-circle7"></div>';
							echo '<div class="wpr-circle wpr-circle8"></div>';
							echo '<div class="wpr-circle wpr-circle9"></div>';
							echo '<div class="wpr-circle wpr-circle10"></div>';
							echo '<div class="wpr-circle wpr-circle11"></div>';
							echo '<div class="wpr-circle wpr-circle12"></div>';
						echo '</div>';
						break;
					
					default:
						break;
				}
			echo '</div>';

			echo '<p class="wpr-no-more-results">'. esc_html($settings['pagination_finish_text']) .'</p>';

			echo '</div>';

		endif;
	}

	public function add_section_ajax_pagination() {

		// Tab: Content ==============
		// Section: Pagination -------
		$this->start_controls_section(
			'section_ajax_search_pagination',
			[
				'label' => esc_html__( 'Ajax Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'ajax_search' => 'yes'
				]
			]
		);

		$this->add_control(
			'pagination_load_more_text',
			[
				'label' => esc_html__( 'Load More Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Load More'
			]
		);

		$this->add_control(
			'pagination_finish_text',
			[
				'label' => esc_html__( 'Finish Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'End of Content.'
			]
		);

		$this->add_control(
			'pagination_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'loader-1',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'loader-1' => esc_html__( 'Loader 1', 'wpr-addons' ),
					'loader-2' => esc_html__( 'Loader 2', 'wpr-addons' ),
					'loader-3' => esc_html__( 'Loader 3', 'wpr-addons' ),
					'loader-4' => esc_html__( 'Loader 4', 'wpr-addons' ),
					'loader-5' => esc_html__( 'Loader 5', 'wpr-addons' ),
					'loader-6' => esc_html__( 'Loader 6', 'wpr-addons' ),
				]
			]
		);

		$this->add_control(
			'pagination_align',
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
				'default' => 'center',
				'prefix_class' => 'wpr-ajax-search-pagination-',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}

	public function add_section_style_ajax_pagination() {

		// Styles ====================
		// Section: Pagination -------
		$this->start_controls_section(
			'section_style_pagination',
			[
				'label' => esc_html__( 'AJAX Pagination', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'ajax_search' => 'yes'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_pagination_style' );

		$this->start_controls_tab(
			'tab_grid_pagination_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-ajax-search-pagination svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wpr-ajax-search-pagination > div > span' => 'color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'pagination_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-ajax-search-pagination > div > span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-no-more-results' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-ajax-search-pagination > div > span' => 'border-color: {{VALUE}}'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results, {{WRAPPER}} .wpr-ajax-search-pagination > div > span',
			]
		);

		$this->add_control(
			'pagination_loader_color',
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
			'pagination_wrapper_color',
			[
				'label'  => esc_html__( 'Wrapper Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination' => 'background-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_pagination_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#4A45D2',
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'pagination_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results:hover' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'pagination_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results:hover',
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-ajax-search-pagination svg' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-ajax-search-pagination > div > span' => 'transition-duration: {{VALUE}}s',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_typography',
				'selector' => '{{WRAPPER}} .wpr-ajax-search-pagination, {{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results'
			]
		);

		$this->add_control(
			'pagination_border_type',
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
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-ajax-search-pagination > div > span' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'pagination_border_width',
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
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-ajax-search-pagination > div > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_distance_from_grid',
			[
				'label' => esc_html__( 'Distance From List', 'wpr-addons' ),
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
					'{{WRAPPER}} .wpr-ajax-search-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 8,
					'right' => 20,
					'bottom' => 8,
					'left' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-ajax-search-pagination > div > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_wrapper_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-ajax-search-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_radius',
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
					'{{WRAPPER}} .wpr-ajax-search-pagination .wpr-load-more-results' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-ajax-search-pagination > div > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

	}
	
	// Recursive function to print categories and their children
	protected function print_categories($category, $taxonomy, $level = 0) {
		$args = array(
			'taxonomy' => $taxonomy,
			'hide_empty' => false,
			'parent' => $category->term_id, // get children of this category
		);

		$children = get_terms($args);

		$output = '';

		if ( 'uncategorized' !== $category->slug ) {
			$output = '<option value="' . $category->term_id . '" data-post-type="'. $category->taxonomy .'">' . str_repeat('&nbsp;', $level * 3) . $category->name . '</option>';
		}

		foreach ($children as $child) {
			$output .= $this->print_categories($child, $taxonomy, $level + 1);
		}

		return $output;
	}

	protected function render_categories_select($post_type, $all_cat_text, $taxonomy) {
		// Define taxonomy based on post_type
		if ( $post_type == 'all' ) {
			// $post_types = array_keys(Utilities::get_custom_types_of( 'post', false ));
			$post_types = ['post', 'product'];
			if ( class_exists('WooCommerce') ) {
				$post_types = ['post', 'product'];
			} else {
				$post_types = ['post'];
			}

			$output = '<div class="wpr-category-select-wrap">';
			
			// Start the select
			$output .= '<select name="category" class="wpr-category-select">';

			$output .= '<option value="0">'. $all_cat_text .'</option>';
	
			// Fetch and add options for each post type
			foreach ($post_types as $post_type) {
				$taxonomy = ($post_type == 'product') ? 'product_cat' : 'category';
		
				$args = array(
					'taxonomy' => $taxonomy,
					'hide_empty' => false,
					'parent' => 0, // get top level categories
				);
				
				$categories = get_terms($args);
		
				// Group options by post type
				// $output .= '<optgroup label="' . ucfirst($post_type) . ' Categories">';
		
				foreach ($categories as $category) {
					$output .= $this->print_categories($category, $taxonomy);
				}
		
				// $output .= '</optgroup>';
			}
		
			// Close the select
			$output .= '</select>';
			$output .= '</div>';
		
			// Return the generated select
			return $output;
		} else {
			$taxonomy = $taxonomy;
	
			// Prepare the arguments for wp_dropdown_categories
			$args = array(
				'show_option_all'    => $all_cat_text,
				'show_option_none'   => '',
				'option_none_value'  => '-1',
				'orderby'            => 'name',
				'order'              => 'ASC',
				'show_count'         => 0,
				'hide_empty'         => 0,
				'child_of'           => 0,
				'include'            => '',
				'echo'               => 0,
				'selected'           => 0,
				'hierarchical'       => 1, 
				'name'               => 'category',
				'class'              => 'wpr-category-select',
				'depth'              => 0,
				'tab_index'          => 0,
				'taxonomy'           => $taxonomy,
				'hide_if_empty'      => false,
				'value_field'	     => 'term_id',	 
			);

			if ( 'product_cat' == $taxonomy ) {
				$uncategorized_product_cat = get_term_by('slug', 'uncategorized', 'product_cat');
				$exclude_id = ($uncategorized_product_cat) ? $uncategorized_product_cat->term_id : '';
				$args['exclude'] = $exclude_id;
			} else {
				$args['exclude'] = 1;
			}

			$select_name = 'wpr_select_category';

			if ( $post_type == 'product' ) {
				$select_name = 'wpr_select_product_cat';
			}

			// $output = '<div class="wpr-category-select-wrap">';
		
			// // Get the categories dropdown
			// $output .= wp_dropdown_categories($args);

			// $output .= '</div>';
			
			// // Return the generated select
			// return $output;

			$output = '<div class="wpr-category-select-wrap">';
			
			// Start the select
			$output .= '<select name="'. $select_name .'" class="wpr-category-select">';

			$output .= '<option value="0">'. $all_cat_text .'</option>';
	
			// Fetch and add options for each post type_url_form_audio()
				if ( is_array($taxonomy) && count($taxonomy) > 1) {
					$categories = [];
					$taxonomies = [];

					foreach($taxonomy as $taxonomy_type) {

						$args = array(
							'taxonomy' => $taxonomy_type,
							'hide_empty' => false,
							'parent' => 0, // get top level categories
						);
						
						$taxonomies = get_terms($args);
						$categories = $taxonomies;
						
						// Group options by post type
						$output .= '<optgroup label="' . ucfirst(get_taxonomy($taxonomy_type)->label) . '">';
				
						foreach ($categories as $category) {
							$output .= $this->print_categories($category, $taxonomy);
						}
				
						$output .= '</optgroup>';
					}
				} else {
					$args = array(
						'taxonomy' => $taxonomy,
						'hide_empty' => false,
						'parent' => 0, // get top level categories
					);

					$categories = get_terms($args);
		
					foreach ($categories as $category) {
						$output .= $this->print_categories($category, $taxonomy);
					}
				}
		
			// Close the select
			$output .= '</select>';
			$output .= '</div>';
		
			// Return the generated select
			return $output;
		}
	}	
	
	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		// $hidden_input = 'yes' === $settings['open_search_input_on_btn_click'] ? 'wpr-search-input-hidden' : '';

		$this->add_render_attribute(
			'input', [
				'class' => 'wpr-search-form-input',
				'placeholder' => $settings['search_placeholder'],
				'aria-label' => $settings['search_aria_label'],
				'type' => 'search',
				'name' => 's',
				'title' => esc_html__( 'Search', 'wpr-addons' ),
				'value' => get_search_query(),
				'wpr-query-type' => $settings['search_query'],
				'wpr-taxonomy-type' => isset($settings['query_taxonomy_'. $settings['search_query']]) ? $settings['query_taxonomy_'. $settings['search_query']] : '',
				'number-of-results' => isset($settings['number_of_results']) ? $settings['number_of_results'] : -1,
				'ajax-search' => isset($settings['ajax_search']) ? $settings['ajax_search'] : '',
				'show-description' => isset($settings['show_description']) ? $settings['show_description'] : '',
				'number-of-words' => isset($settings['number_of_words_in_excerpt']) ? $settings['number_of_words_in_excerpt'] : '',
				'show-ajax-thumbnails' => isset($settings['show_ajax_thumbnails']) ? $settings['show_ajax_thumbnails'] : '',
				'show-view-result-btn' => isset($settings['show_view_result_btn']) ? $settings['show_view_result_btn'] : '',
				'show-product-price' => isset($settings['show_product_price']) ? $settings['show_product_price'] : '',
				'view-result-text' => isset($settings['show_ajax_thumbnails']) ? $settings['view_result_text'] : '',
				'no-results' => isset($settings['no_results_text']) ? esc_html__($settings['no_results_text']) : '',
				'exclude-without-thumb' => isset($settings['exclude_posts_without_thumbnail']) ? $settings['exclude_posts_without_thumbnail'] : '',
				'link-target' => isset($settings['ajax_search_link_target']) && ( 'yes' === $settings['ajax_search_link_target'] ) ? '_blank'  : '_self',
				'password-protected' => isset($settings['ajax_show_ps_pt']) ? $settings['ajax_show_ps_pt'] : 'no',	
				'attachments' => isset($settings['show_attachments']) ? $settings['show_attachments'] : 'no',
				// 'ajax-search-img-size' => isset($settings['ajax_search_img_size']) ? $settings['ajax_search_img_size'] : ''
			]
		);

		?>

		<form role="search" method="get" class="wpr-search-form" action="<?php echo home_url(); ?>">
			<?php if ( 'all' !== $settings['search_query'] ) : ?>
				<input type="hidden" name="post_type" value="<?php echo esc_attr( $settings['search_query'] ); ?>" />
			<?php endif; ?>

			<div class="wpr-search-form-input-wrap elementor-clearfix">
				<?php
					// If the search_query is not 'all', render the categories select
					if ( 'all' !== $settings['search_query'] && 'yes' == $settings['select_category'] ) {
						if ( !isset($settings['query_taxonomy_'. $settings['search_query']]) || empty($settings['query_taxonomy_'. $settings['search_query']]) ) {
							if ( 'product' === $settings['search_query'] ) {
								$query_taxonomy = 'product_cat';
							} else {
								$query_taxonomy = 'category';
							}
						} else {
							$query_taxonomy = $settings['query_taxonomy_'. $settings['search_query']];
						}
						echo $this->render_categories_select(esc_attr( $settings['search_query'] ), esc_html__($settings['all_cat_text']), $query_taxonomy);
					}
				?>
				<input <?php echo $this->get_render_attribute_string( 'input' ); ?>>
				<?php
				if ( $settings['search_btn_style'] === 'inner' ) {
					$this->render_search_submit_btn();
				}
				?>
			</div>

			<?php

			if ( $settings['search_btn_style'] === 'outer' ) {
				$this->render_search_submit_btn();
			}

			?>
			
		</form>
		<div class="wpr-data-fetch">
        	<span class="wpr-close-search"></span>
			<ul></ul>
			<?php echo $this->render_search_pagination($settings); ?>
		</div>

		<?php

	}
	
}