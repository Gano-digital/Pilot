<?php
namespace WprAddons\Classes\Modules;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Woo_Grid_Helpers setup
 *
 * @since 3.4.6
 */

 class WPR_Woo_Grid_Helpers {

    public function __construct() {
		add_action('wp_ajax_wpr_woo_grid_filters_ajax', [$this, 'wpr_woo_grid_filters_ajax']);
		add_action('wp_ajax_nopriv_wpr_woo_grid_filters_ajax', [$this, 'wpr_woo_grid_filters_ajax']);
		add_action('wp_ajax_wpr_get_filtered_count_products', [$this, 'wpr_get_filtered_count_products']);
		add_action('wp_ajax_nopriv_wpr_get_filtered_count_products', [$this, 'wpr_get_filtered_count_products']);
    }
    
	public static $my_upsells;
	public static $crossell_ids;

	// Get Max Pages
	public static function get_max_num_pages( $settings ) {
		if ( isset($_POST['wpr_url_params']) ) {
			$query = new \WP_Query( WPR_Woo_Grid_Helpers::get_main_query_args($settings, []) );
			$max_num_pages = intval( ceil( $query->max_num_pages ) );
		} else if ( isset($_POST['grid_settings']) ) {
			$query = new \WP_Query( WPR_Woo_Grid_Helpers::get_main_query_args($settings, []) );
			$max_num_pages = intval( ceil( $query->max_num_pages ) );
			
			$adjustedTotalPosts = max(0, $query->found_posts - $query->query_vars['offset']); // Ensuring it doesn't go below 0
			$numberOfPages = ceil($adjustedTotalPosts / $query->query_vars['posts_per_page']);

			wp_send_json_success([
				'page_count' => $numberOfPages,
				'max_num_pages' => $max_num_pages,
				'query_found' => $query->found_posts,
				'post_count' => $query->post_count,
				'query_offset' => $query->query_vars['offset'],
				'query_num' => $query->query_vars['posts_per_page']
			]);
		} else {
			$query = new \WP_Query( WPR_Woo_Grid_Helpers::get_main_query_args($settings, []) );
			$max_num_pages = intval( ceil( $query->max_num_pages ) );
		}

		// Reset
		wp_reset_postdata();

		// $max_num_pages
		return $max_num_pages;
	}

    // Main Query Args
	public static function get_main_query_args($settings, $params) {
		$tax_query = [];

		if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ) {
			$settings['query_selection'] = 'pro-cr' == $settings['query_selection'] ? 'dynamic' : $settings['query_selection'];
			$settings['query_orderby'] = 'pro-rn' == $settings['query_orderby'] ? 'date' : $settings['query_orderby'];
		}

		// Get Paged
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}
		
		if ( empty($settings['query_offset']) ) {
			$settings[ 'query_offset' ] = 0;
		}
		
		if ( 'current' !== $settings['query_selection'] ) {
			$query_posts_per_page = $settings['query_posts_per_page'];
		}

		if (!isset($query_posts_per_page) || empty($query_posts_per_page) ) {
			$query_posts_per_page = -1;
		}
		
		$offset = ( $paged - 1 ) * $query_posts_per_page + $settings[ 'query_offset' ];

		// Dynamic
		$args = [
			'post_type' => 'product',
			'tax_query' => WPR_Woo_Grid_Helpers::get_tax_query_args($settings),
			'meta_query' => WPR_Woo_Grid_Helpers::get_meta_query_args(),
			'post__not_in' => $settings[ 'query_exclude_products' ],
			'posts_per_page' => $settings['query_posts_per_page'],
			'orderby' => 'date',
			'paged' => $paged,
			'offset' => $offset
		];

		// Featured
		if ( 'featured' === $settings['query_selection'] ) {
			$args['tax_query'][] = [
				'taxonomy' => 'product_visibility',
				'field' => 'term_taxonomy_id',
				'terms' => wc_get_product_visibility_term_ids()['featured'],
			];
		}

		// On Sale
		if ( 'onsale' === $settings['query_selection'] ) {
			// $args['post__in'] = wc_get_product_ids_on_sale();
			$args['meta_query'] = array(
				'relation' => 'OR',
				array( // Simple products type
					'key'           => '_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				),
				array( // Variable products type
					'key'           => '_min_variation_sale_price',
					'value'         => 0,
					'compare'       => '>',
					'type'          => 'numeric'
				)
			);
		}
		
		if ( 'upsell' === $settings['query_selection'] ) {
			// Get Product
			$product = wc_get_product();
	
			if ( ! $product ) {
				return;
			}
	
			$meta_query = WC()->query->get_meta_query();
	
			WPR_Woo_Grid_Helpers::$my_upsells = $product->get_upsell_ids();
			
			if ( !empty(WPR_Woo_Grid_Helpers::$my_upsells) ) {
				$args = array(
					'post_type' => 'product',
					'post__not_in' => $settings[ 'query_exclude_products' ],
					'ignore_sticky_posts' => 1,
					// 'no_found_rows' => 1,
					'posts_per_page' => $settings['query_posts_per_page'],
					'orderby' => 'post__in',
					'order' => $settings['order_direction'],
					'paged' => $paged,
					'post__in' => WPR_Woo_Grid_Helpers::$my_upsells,
					'meta_query' => $meta_query
				);
			} else {
				$args['post_type'] = ['none'];
			}
		}

		if ( 'cross-sell' === $settings['query_selection'] ) {
			// Get Product
			WPR_Woo_Grid_Helpers::$crossell_ids = [];
			
			if( is_cart() ) {
				$items = WC()->cart->get_cart();
	
				foreach($items as $item => $values) {
					$product = $values['data'];
					$cross_sell_products = $product->get_cross_sell_ids();
					foreach($cross_sell_products as $cs_product) {
						array_push(WPR_Woo_Grid_Helpers::$crossell_ids, $cs_product);
					}
				  }
			}

			if ( is_single() ) {
				$product = wc_get_product();
		
				if ( ! $product ) {
					return;
				}

				WPR_Woo_Grid_Helpers::$crossell_ids = $product->get_cross_sell_ids();
			}
	
			// $meta_query = WC()->query->get_meta_query();
			
			if ( !empty(WPR_Woo_Grid_Helpers::$crossell_ids) ) {
				$args = [
					'post_type' => 'product',
					'post__not_in' => $settings[ 'query_exclude_products' ],
					'tax_query' => WPR_Woo_Grid_Helpers::get_tax_query_args($settings),
					'ignore_sticky_posts' => 1,
					// 'no_found_rows' => 1,
					'posts_per_page' => $settings['query_posts_per_page'],
					// 'orderby' => 'post__in',
					'order' => $settings['order_direction'],
					'paged' => $paged,
					'post__in' => WPR_Woo_Grid_Helpers::$crossell_ids,
					// 'meta_query' => $meta_query
				];
			} else {
				$args['post_type'] = 'none';
			}
		}

		// Default Order By
		if ( 'sales' === $settings['query_orderby'] ) {
			$args['meta_key'] = 'total_sales';
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'rating' === $settings['query_orderby'] ) {
			$args['meta_key'] = '_wc_average_rating';
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'price-low' === $settings['query_orderby'] ) {
			$args['meta_key'] = '_price';
			$args['order'] = $settings['order_direction'];
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'price-high' === $settings['query_orderby'] ) {
			$args['meta_key'] = '_price';
			$args['order'] = $settings['order_direction'];
			$args['orderby']  = 'meta_value_num';
		} elseif ( 'random' === $settings['query_orderby'] ) {
			$args['orderby']  = 'rand';
		} elseif ( 'date' === $settings['query_orderby'] ) {
			$args['orderby']  = 'date';
			$args['order'] = $settings['order_direction'];
		} else {
			$args['orderby']  = 'menu_order';
			$args['order']  = $settings['order_direction'];
		}

		// Exclude Items without F/Image
		if ( 'yes' === $settings['query_exclude_no_images'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}

		// Exclude Out Of Stock
		if ( 'yes' === $settings['query_exclude_out_of_stock'] ) {
			$args['meta_query'] = [
				[
					'key'     => '_stock_status',
					'value'   => 'outofstock',
					'compare' => 'NOT LIKE',
				]
			];
		}

		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_products' ]) ) {
				$post_ids = $settings[ 'query_manual_products' ];
			}

			$args = [
				'post_type' => 'product',
				'post__in' => $post_ids,
				'posts_per_page' => $settings['query_posts_per_page'],
				'orderby' => $settings[ 'query_randomize' ],
				'paged' => $paged,
			];
		}

		// Get Post Type
		if ( 'current' === $settings[ 'query_selection' ] && true !== \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			global $wp_query;

			// Products Per Page
			if ( is_product_category() ) {
				$posts_per_page = intval(get_option('wpr_woo_shop_cat_ppp', 9));
			} elseif ( is_product_tag() ) {
				$posts_per_page = intval(get_option('wpr_woo_shop_tag_ppp', 9));
			} else {
				$posts_per_page = intval(get_option('wpr_woo_shop_ppp', 9));
			}
			$args = $wp_query->query_vars;
			$args['tax_query'] = WPR_Woo_Grid_Helpers::get_tax_query_args($settings);
			$args['meta_query'] = WPR_Woo_Grid_Helpers::get_meta_query_args();
			$args['posts_per_page'] = $posts_per_page;
			if (!empty($settings['query_randomize'])) {
				$args['orderby'] = $settings['query_randomize'];
			} else {
				$args['orderby'] = get_query_var('orderby') ? get_query_var('orderby') : $settings['current_query_orderby'];
				$args['order'] = $settings['order_direction'] ? $settings['order_direction'] : $settings['current_query_order'];
			}
			$args['post_type'] = 'product'; // GOGA: needs check
		}

		// Sorting
		if ( isset( $_GET['orderby'] ) || ( isset($_POST['orderby']) && !empty($_POST['orderby']) ) ) {
			$orderby_value = isset( $_GET['orderby'] ) ? $_GET['orderby'] : $_POST['orderby'];

			if ( 'popularity' === $orderby_value ) {
				$args['meta_key'] = 'total_sales';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'rating' === $orderby_value ) {
				$args['meta_key'] = '_wc_average_rating';
				$args['order'] = $settings['order_direction'];
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'price' === $orderby_value ) {
				$args['meta_key'] = '_price';
				$args['order'] = 'ASC';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'price-desc' === $orderby_value ) {
				$args['meta_key'] = '_price';
				$args['order'] = 'DESC';
				$args['orderby']  = 'meta_value_num';
			} elseif ( 'random' === $orderby_value ) {
				$args['orderby']  = 'rand';
			} elseif ( 'date' === $orderby_value ) {
				$args['orderby']  = 'date';
			} else if ( 'title' === $orderby_value ){
				$args['orderby']  = 'title';
				$args['order'] = 'ASC';
			} else if ( 'title-desc' === $orderby_value ) {
				$args['orderby']  = 'title';
				$args['order'] = 'DESC';
			} else {
				$args['order'] = $settings['order_direction'];
				if ( 'current' === $settings[ 'query_selection' ] ) {
					$args['orderby']  = 'menu_order title';
				} else {
					$args['orderby']  = $settings['query_orderby'];
				}
			}
		}

		// Search
		if ( isset( $_GET['psearch'] ) ) {
			$args['s'] = $_GET['psearch'];
		}

		if ( isset($_POST['wpr_offset']) ) {
			$args['offset'] = $_POST['wpr_offset'];
		}

		if ( isset($_POST['wpr_taxonomy']) ) {
			return $args;
		}

		if ( !isset($args['tax_query']) ) {
			$args['tax_query'] = [];
		}

		if ( isset($args['tax_query']) ) {

			$tax_query = ['relation' => 'AND'];
            $meta_query = ['relation' => 'AND'];

			$prev_cleaned_key = '';

			$wpr_url_params = isset($params) && !empty($params) ? $params : (isset($_POST['wpr_url_params']) ? $_POST['wpr_url_params'] : []);

			if ( empty($wpr_url_params) && isset($_GET) && !empty($_GET) ) {
				$wpr_url_params = $_GET;
			}

			if ( isset($wpr_url_params) && !empty($wpr_url_params) ) {
				// Iterate through the POST array
				foreach ( $wpr_url_params as $key => $value ) {
					// Check if the variable name contains "wpr_af_"
					if (strpos($key, 'wpr_af_') !== false) {
						// Need to setup logic to get relation from filters separately
						$cleanedKey = str_replace('wpr_af_', '', $key);
						$prev_cleaned_key = $cleanedKey;

						if ( isset($wpr_url_params[$key]) ) {
							if ( $cleanedKey == 'date_range' ) {
								$date = $wpr_url_params[$key];
								
								$args['date_query'] = [];

								if ( str_contains($date, ',') ) {
									$date = explode(',', $date);

									if (false) {
										$args['date_query'] = ['relation' => 'or'];

										list($year1, $month1, $day1) = explode("-", $date[0]);
										list($year2, $month2, $day2) = explode("-", $date[1]);

										array_push( $args['date_query'], [
											'year' => $year1,
											'month' => $month1,
											'day' => $day1,
										] );

										array_push( $args['date_query'], [
											'year' => $year2,
											'month' => $month2,
											'day' => $day2,
										] );

									} else {
										array_push( $args['date_query'], [
											'after'     => $date[0],
											'before'    => $date[1],
											'inclusive' => true
										] );
									}
								} 
							} elseif ( $cleanedKey == 'date' ) {

								$date = $wpr_url_params[$key];
								
								$args['date_query'] = [];

								if ( str_contains($date, '-') && explode("-", $date) ) {
									list($year, $month, $day) = explode("-", $date);

									array_push( $args['date_query'], [
										'year' => $year,
										'month' => $month,
										'day' => $day,
									]);
								}
							} else if ( 'rating' == $cleanedKey ) {
								$selected_ratings = explode( ',', $wpr_url_params[$key] );

								$rating_meta_query = ['relation' => 'OR'];

								foreach ( $selected_ratings as $rating ) {
									$rating = (int) $rating;
									$lower = $rating - 0.5;
									$upper = ($rating === 5) ? 5.0 : $rating + 0.5 - 0.00001;

									$rating_meta_query[] = [
										'key'     => '_wc_average_rating',
										'value'   => [ $lower, $upper ],
										'compare' => 'BETWEEN',
										'type'    => 'DECIMAL',
									];
								}

								$meta_query[] = $rating_meta_query;
							} else if ( 'price' == $cleanedKey || '_price' == $cleanedKey ) {
									// Price range can be separated by '-' or ','
									if (strpos($wpr_url_params[$key], '-') !== false) {
										$price_range = explode('-', $wpr_url_params[$key]);
									} elseif (strpos($wpr_url_params[$key], ',') !== false) {
										$price_range = explode(',', $wpr_url_params[$key]);
									}

									if ( count( $price_range ) === 2 ) {
										$min_price = floatval( $price_range[0] );
										$max_price = floatval( $price_range[1] );

										$meta_query[] = [
											'key'     => '_price',
											'value'   => [ $min_price, $max_price ],
											'compare' => 'BETWEEN',
											'type'    => 'NUMERIC',
										];
									}
							} else {
								if ( $wpr_url_params[$key] != '0' ) {
									// Get category from URL
									if ( str_contains($wpr_url_params[$key], ',') ) {
			
										// Example usage
										$key_type = WPR_Woo_Grid_Helpers::identify_key_type($cleanedKey);
										$filtervalues = explode(',', $wpr_url_params[$key]);

										if ( ('meta_field' == $key_type || 'custom_field' == $key_type) ) {
											if ( is_numeric($filtervalues[0]) && isset($wpr_url_params['wpr_aft_' . $cleanedKey]) && $wpr_url_params['wpr_aft_' . $cleanedKey] == 'range' ) {
												$minValue = min(array_values($filtervalues));
												$maxValue = max(array_values($filtervalues));
												
												if ( isset($meta_query) ) {
													array_push($meta_query, [
														[
															'key'     => $cleanedKey,
															'value'   => [$minValue, $maxValue],
															'type'    => 'NUMERIC',
															'compare' => 'BETWEEN',
														],
													]);
												} else {
													$meta_query = [
														[
															'key'     => $cleanedKey,
															'value'   => [$minValue, $maxValue],
															'type'    => 'NUMERIC',
															'compare' => 'BETWEEN',
														],
													];
												}
											} else {
												if ( isset($meta_query) ) {
													if ( isset($wpr_url_params['wpr_afr_' . $cleanedKey]) && !empty(explode(',', $wpr_url_params['wpr_afr_'. $cleanedKey])[0]) ) {
														$meta_relation = explode(',', $wpr_url_params['wpr_afr_'. $cleanedKey])[0];
													} else if ( isset($wpr_url_params['wpr_afr_'. $cleanedKey]) && !empty(explode(',', $wpr_url_params['wpr_afr_'. $cleanedKey])[0]) ) {
														$meta_relation = explode(',', $wpr_url_params['wpr_afr_'. $cleanedKey])[0];
													} else {
														$meta_relation = '';
													}
													
													$for_meta_query = [ // needs check if overrides somethings
														'relation' => $meta_relation,
													];
				
													foreach ($filtervalues as $filtervalue) {
														$filtervalue = sanitize_text_field($filtervalue);
														
													
														array_push($for_meta_query, [
															[
																'key'     => $cleanedKey,
																'value'   => $filtervalue
															],
														]);
													}
				
													array_push( $meta_query, $for_meta_query );
												} else {
													$meta_query = [ // needs check if overrides something
														'relation' => explode(',', $_POST['wpr_afr_'. $cleanedKey])[0] ? explode(',', $_POST['wpr_afr_'. $cleanedKey])[0] : explode(',', $wpr_url_params['wpr_afr_'. $cleanedKey])[0],
													];
		
													if (is_array($filtervalues)) {
														foreach ($filtervalues as $filtervalue) {
															$meta_query[] = [
																'key'     => $cleanedKey,
																'value'   => $filtervalue,
																'compare' => '=',
															];
														}
													}
												}
											}
										} else { // if != 'meta_field'
											// if ( isset($_POST['wpr_afr_'. $cleanedKey]) ) {
												$for_tax_query = [ // needs check if overrides something
													// 'relation' => isset($_POST['wpr_afr_' . $cleanedKey]) && !empty(explode(',', $_POST['wpr_afr_' . $cleanedKey])[0]) ? explode(',', $_POST['wpr_afr_' . $cleanedKey])[0] : '',
													'relation' => isset($wpr_url_params['wpr_afr_' . $cleanedKey]) && !empty(explode(',', $wpr_url_params['wpr_afr_' . $cleanedKey])[0]) ? explode(',', $wpr_url_params['wpr_afr_' . $cleanedKey])[0] : '',
												];
											// } else {
											// 	$for_tax_query = [];
											// }
		
											foreach ($filtervalues as $filtervalue) {
												$filtervalue = sanitize_text_field($filtervalue);
												
												array_push( $for_tax_query, [
													'taxonomy' => $cleanedKey,
													'field' => 'id',
													'terms' => $filtervalue
												] );
											}

											array_push($tax_query, $for_tax_query);
										}
									} else { // not str_contains($wpr_url_params[$key], ',')
										$key_type = WPR_Woo_Grid_Helpers::identify_key_type($cleanedKey);
										$filtervalues = sanitize_text_field($wpr_url_params[$key]);
		
										if ( $key_type == 'meta_field'  || $key_type == 'custom_field' ) {
											if ( isset($meta_query) ) {
												array_push($meta_query, [
													[
														'key'     => $cleanedKey,
														'value'   => [$filtervalues],
														// 'type'    => 'NUMERIC',
														// 'compare' => 'BETWEEN',
													],
												]);
											} else {
												$meta_query = [
													[
														'key'     => $cleanedKey,
														'value'   => [$filtervalues],
														// 'type'    => 'NUMERIC',
														// 'compare' => 'BETWEEN',
													],
												];
											}
										} else {
											if (isset($wpr_url_params[$key])) {
						
												array_push( $tax_query, [
													'taxonomy' => $cleanedKey,
													'field' => 'id',
													'terms' => $filtervalues
												] );
											}
										}
									}
								}
							}
						}
					}
				}

				if ( !empty($tax_query) ) {
					if ( !empty($args['tax_query']) ) {
						$args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
					} else {
						$args['tax_query'] = $tax_query;
					}
				}

				if ( !empty($meta_query) )  {
					if ( !empty($args['meta_query']) ) {
						$args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
					} else {
						$args['meta_query'] = $meta_query;
					}
				}
			}
		}

		return $args;
	}
	
	public static function identify_key_type($key) {
		// Check if it's a built-in taxonomy
		$builtin_taxonomies = array('product_cat', 'product_tag'); // Add more if needed
		if (in_array($key, $builtin_taxonomies)) {
			return 'taxonomy';
		}
	
		// Check if it's a custom taxonomy
		$custom_taxonomies = get_taxonomies(['_builtin' => false]);
		if (in_array($key, $custom_taxonomies)) {
			return 'taxonomy';
		}
	
		// Check if it's a custom field key - WHY?
		$custom_field_keys = get_post_custom_keys();
		if ( is_array($custom_field_keys) && in_array($key, $custom_field_keys) ) {
			return 'custom_field';
		}
	
		// Add more checks if needed...
	
		// If none of the checks match, assume it's a meta field
		return 'meta_field';
	}

	// Meta Query Args
	public static function get_meta_query_args(){
        $meta_query = WC()->query->get_meta_query();

		// Price Filter Args
        if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) {
            $meta_query = array_merge( ['relation' => 'AND'], $meta_query );
            $meta_query[] = [
                [
                    'key' => '_price',
                    'value' => [ $_GET['min_price'], $_GET['max_price'] ],
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                ],
            ];
        }

		return $meta_query;
    }

	// Taxonomy Query Args
	public static function get_tax_query_args($settings) {
		$tax_query = [];

		// Filters Query
		if ( isset($_GET['wprfilters']) ) {
			$selected_filters = WC()->query->get_layered_nav_chosen_attributes();

			if ( !empty($selected_filters) ) {
				foreach ( $selected_filters as $taxonomy => $data ) {
					array_push($tax_query, [
						'taxonomy' => $taxonomy,
						'field' => 'slug',
						'terms' => $data['terms'],
						'operator' => 'and' === $data['query_type'] ? 'AND' : 'IN',
						'include_children' => false,
					]);
				}
			}

			// Product Categories
			if ( isset($_GET['filter_product_cat']) ) {
				array_push($tax_query, [
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => explode( ',', $_GET['filter_product_cat'] ),
					'operator' => 'IN',
					'include_children' => true, // test this needed or not for hierarchy
				]);
			}

			// Product Tags
			if ( isset($_GET['filter_product_tag']) ) {
				array_push($tax_query, [
					'taxonomy' => 'product_tag',
					'field' => 'slug',
					'terms' => explode( ',', $_GET['filter_product_tag'] ),
					'operator' => 'IN',
					'include_children' => true, // test this needed or not for hierarchy
				]);
			}
		// Grid Query
		} else {
			if ( isset($_GET['wpr_select_product_cat']) ) {
				if ( $_GET['wpr_select_product_cat'] != '0' ) {
					// Get category from URL
					$category = sanitize_text_field($_GET['wpr_select_product_cat']);
				
					array_push( $tax_query, [
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'terms' => $category
					] );
				}
			}

			if ( isset($_GET['product_cat']) ) {
				if ( $_GET['product_cat'] != '0' ) {
					// Get category from URL
					$category = sanitize_text_field($_GET['product_cat']);
				
					array_push( $tax_query, [
						'taxonomy' => 'product_cat',
						'field' => 'id',
						'terms' => $category
					] );
				}
			} else {
				if ( 'current' != $settings['query_selection'] ) {
					foreach ( get_object_taxonomies( 'product' ) as $tax ) {
						if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
							array_push( $tax_query, [
								'taxonomy' => $tax,
								'field' => 'id',
								'terms' => $settings[ 'query_taxonomy_'. $tax ]
							] );
						}
					}
				}
			}

			if ( isset($_POST['wpr_taxonomy']) ) {
				$settings = $_POST['grid_settings'];
				$taxonomy = $_POST['wpr_taxonomy'];
				$term = $_POST['wpr_filter'];

				if ( $term != '*' ) {
				    if ( 'tag' === $taxonomy ) {
				        $taxonomy = 'product_' . $_POST['wpr_taxonomy'];
				    }
				    array_push( $tax_query, [
				        'taxonomy' => $taxonomy,
				        'field' => 'slug',
				        'terms' => $term
				    ] );
				}
			}

			if ( isset($settings['current_query_source']) && !empty($settings['current_query_source']) ) {
				array_push( $tax_query, [
					'taxonomy' => $settings['current_query_tax'],
					'field' => 'slug',
					'terms' => $settings['current_query_source']
				] );
			}
		}

		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		// Filter by rating.
		if ( isset( $_GET['filter_rating'] ) ) {

			$product_visibility_terms  = wc_get_product_visibility_term_ids();
			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$filter_rating = array_filter( array_map( 'absint', explode( ',', wp_unslash( $_GET['filter_rating'] ) ) ) );
			$rating_terms  = array();
			for ( $i = 1; $i <= 5; $i ++ ) {
				if ( in_array( $i, $filter_rating, true ) && isset( $product_visibility_terms[ 'rated-' . $i ] ) ) {
					$rating_terms[] = $product_visibility_terms[ 'rated-' . $i ];
				}
			}
			if ( ! empty( $rating_terms ) ) {
				$tax_query[] = array(
					'taxonomy'      => 'product_visibility',
					'field'         => 'term_taxonomy_id',
					'terms'         => $rating_terms,
					'operator'      => 'IN',
				);
			}
		}

		return $tax_query;
	}

	// Get Animation Class
	public static function get_animation_class( $data, $object ) {
		$class = '';

		// Disable Animation on Mobile
		if ( 'overlay' !== $object ) {
			if ( 'yes' === $data[$object .'_animation_disable_mobile'] && wp_is_mobile() ) {
				return $class;
			}
		}

		// Animation Class
		if ( 'none' !== $data[ $object .'_animation'] ) {
			$class .= ' wpr-'. $object .'-'. $data[ $object .'_animation'];
			$class .= ' wpr-anim-size-'. $data[ $object .'_animation_size'];
			$class .= ' wpr-anim-timing-'. $data[ $object .'_animation_timing'];

			if ( 'yes' === $data[ $object .'_animation_tr'] ) {
				$class .= ' wpr-anim-transparency';
			}
		}

		return $class;
	}

	// Get Image Effect Class
	public static function get_image_effect_class( $settings ) {
		$class = '';

		if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ) {
			if ( 'pro-zi' ==  $settings['image_effects'] || 'pro-zo' ==  $settings['image_effects'] || 'pro-go' ==  $settings['image_effects'] || 'pro-bo' ==  $settings['image_effects'] ) {
				$settings['image_effects'] = 'none';
			}
		}

		// Animation Class
		if ( 'none' !== $settings['image_effects'] ) {
			$class .= ' wpr-'. $settings['image_effects'];
		}
		
		// Slide Effect
		if ( 'slide' !== $settings['image_effects'] ) {
			$class .= ' wpr-effect-size-'. $settings['image_effects_size'];
		} else {
			$class .= ' wpr-effect-dir-'. $settings['image_effects_direction'];
		}

		return $class;
	}

	// Render Password Protected Input
	public static function render_password_protected_input( $settings ) {
		if ( ! post_password_required() ) {
			return;
		}

		add_filter( 'the_password_form', function () {
			$output  = '<form action="'. esc_url(home_url( 'wp-login.php?action=postpass' )) .'" method="post">';
			$output .= '<i class="fas fa-lock"></i>';
			$output .= '<p>'. esc_html(get_the_title()) .'</p>';
			$output .= '<input type="password" name="post_password" id="post-'. esc_attr(get_the_id()) .'" placeholder="'. esc_html__( 'Type and hit Enter...', 'wpr-addons' ) .'">';
			$output .= '</form>';

			return $output;
		} );

		echo '<div class="wpr-grid-item-protected wpr-cv-container">';

			echo '<div class="wpr-cv-outer">';
				echo '<div class="wpr-cv-inner">';
					echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Thumbnail
	public static function render_product_thumbnail( $settings ) {
		$id = get_post_thumbnail_id();
		$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'layout_image_crop', $settings );
		$alt = '' === wp_get_attachment_caption( $id ) ? get_the_title() : wp_get_attachment_caption( $id );
		
		if ( get_post_meta(get_the_ID(), 'wpr_secondary_image_id') && !empty(get_post_meta(get_the_ID(), 'wpr_secondary_image_id')) ) {
			$src2 = Group_Control_Image_Size::get_attachment_image_src( get_post_meta(get_the_ID(), 'wpr_secondary_image_id')[0], 'layout_image_crop', $settings );
		} else {
			$src2 = '';
		}

		if ( has_post_thumbnail() ) {
			echo '<div class="wpr-grid-image-wrap" data-src="'. esc_url( $src ) .'"  data-img-on-hover="'. esc_attr( $settings['secondary_img_on_hover'] ) .'" data-src-secondary="'. esc_url( $src2 ) .'">';
				echo '<img src="'. esc_url( $src ) .'" alt="'. esc_attr( $alt ) .'" class="wpr-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
				if ( 'yes' == $settings['secondary_img_on_hover'] ) {
					echo '<img src="'. esc_url( $src2 ) . '" alt="'. esc_attr( $alt ) .'" class="wpr-hidden-img wpr-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
				}
			echo '</div>';
		}
	}

	// Render Media Overlay
	public static function render_media_overlay( $settings ) {
		echo '<div class="wpr-grid-media-hover-bg '. esc_attr(WPR_Woo_Grid_Helpers::get_animation_class( $settings, 'overlay' )) .'" data-url="'. esc_url( get_the_permalink( get_the_ID() ) ) .'">';

			if ( defined('WPR_ADDONS_PRO_VERSION') && wpr_fs()->can_use_premium_code() ) {
				if ( '' !== $settings['overlay_image']['url'] ) {
					echo '<img data-no-lazy="1" src="'. esc_url( $settings['overlay_image']['url'] ) .'">';
				}
			}

		echo '</div>';
	}

	// Render Post Title
	public static function render_product_title( $settings, $class, $general_settings ) {
		$title_pointer = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'none' : $general_settings['title_pointer'];
		$title_pointer_animation = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'fade' : $general_settings['title_pointer_animation'];
		$pointer_item_class = (isset($general_settings['title_pointer']) && 'none' !== $general_settings['title_pointer']) ? 'class="wpr-pointer-item"' : '';
		$open_links_in_new_tab = 'yes' === $general_settings['open_links_in_new_tab'] ? '_blank' : '_self';

		$class .= ' wpr-pointer-'. $title_pointer;
		$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $title_pointer_animation;

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$element_title_tag = Utilities::validate_html_tags_wl( $settings['element_title_tag'], 'h2', $tags_whitelist );

		echo '<'. esc_attr($element_title_tag) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a target="'. $open_links_in_new_tab .'"  '. $pointer_item_class .' href="'. esc_url( get_the_permalink() ) .'">';
				if ( 'word_count' === $settings['element_trim_text_by'] ) {
					echo esc_html(wp_trim_words( get_the_title(), $settings['element_word_count'] ));
				} else {
					echo substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...';
				}
				echo '</a>';
			echo '</div>';
		echo '</'. esc_attr($element_title_tag) .'>';
	}

	// Render Post Excerpt
	public  static function render_product_excerpt( $settings, $class ) {
		if ( '' === get_the_excerpt() ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			if ( 'word_count' === $settings['element_trim_text_by']) {
			  echo '<p>'. esc_html(wp_trim_words( get_the_excerpt(), $settings['element_word_count'] )) .'</p>';
			} else if ( 'letter_count' === $settings['element_trim_text_by'] ) {
			  // echo '<p>'. substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...' . '</p>';
			  echo '<p>'. esc_html(implode('', array_slice( str_split(get_the_excerpt()), 0, $settings['element_letter_count'] ))) .'...' .'</p>';
			} else {
				echo '<p>'.  get_the_excerpt()  .'</p>';	
			}
			echo '</div>';
		echo '</div>';
	}
	
	// Render Post Categories
	public static function render_product_categories( $settings, $class, $post_id, $general_settings ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		// Pointer Class
		$categories_pointer = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'none' : $general_settings['categories_pointer'];
		$categories_pointer_animation = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'fade' : $general_settings['categories_pointer_animation'];
		$pointer_item_class = (isset($general_settings['categories_pointer']) && 'none' !== $general_settings['categories_pointer']) ? 'class="wpr-pointer-item"' : '';

		$class .= ' wpr-pointer-'. $categories_pointer;
		$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $categories_pointer_animation;

		echo '<div class="'. esc_attr($class) .' wpr-grid-product-categories">';
			echo '<div class="inner-block">';
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

				// Taxonomies
				foreach ( $terms as $term ) {
					echo '<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $term->term_id )) .'">'. esc_html( $term->name );
						if ( ++$count !== count( $terms ) ) {
							echo '<span class="tax-sep">'. esc_html($settings['element_tax_sep']) .'</span>';
						}
					echo '</a>';
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
			echo '</div>';
		echo '</div>';
	}

	// Render Post Tags
	public static function render_product_tags( $settings, $class, $post_id, $general_settings ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		// Pointer Class
		$tags_pointer = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'none' : $general_settings['tags_pointer'];
		$tags_pointer_animation = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'fade' : $general_settings['tags_pointer_animation'];
		$pointer_item_class = (isset($general_settings['tags_pointer']) && 'none' !== $general_settings['tags_pointer']) ? 'class="wpr-pointer-item"' : '';

		$class .= ' wpr-pointer-'. $tags_pointer;
		$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $tags_pointer_animation;

		echo '<div class="'. esc_attr($class) .' wpr-grid-product-tags">';
			echo '<div class="inner-block">';
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

				// Taxonomies
				foreach ( $terms as $term ) {
					echo '<a '. $pointer_item_class .' href="'. esc_url(get_term_link( $term->term_id )) .'">'. esc_html( $term->name );
						if ( ++$count !== count( $terms ) ) {
							echo '<span class="tax-sep">'. esc_html($settings['element_tax_sep']) .'</span>';
						}
					echo '</a>';
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
			echo '</div>';
		echo '</div>';
	}

	public static function render_product_likes( $settings, $class, $post_id ) {
		$post_likes = new WPR_Post_Likes();

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				echo $post_likes->get_button( $post_id, $settings );

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}
	
	public static function render_product_sharing_icons( $settings, $class ) {
		$args = [
			'icons' => 'yes',
			'tooltip' => $settings['element_sharing_tooltip'],
			'url' => esc_url( get_the_permalink() ),
			'title' => esc_html( get_the_title() ),
			'text' => esc_html( get_the_excerpt() ),
			'image' => esc_url( get_the_post_thumbnail_url() ),
		];

		$hidden_class = '';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				echo '<span class="wpr-post-sharing">';

					if ( 'yes' === $settings['element_sharing_trigger'] ) {
						$hidden_class = ' wpr-sharing-hidden';
						$attributes  = ' data-action="'. esc_attr( $settings['element_sharing_trigger_action'] ) .'"';
						$attributes .= ' data-direction="'. esc_attr( $settings['element_sharing_trigger_direction'] ) .'"';

						echo '<a class="wpr-sharing-trigger wpr-sharing-icon"'. $attributes .'>';
							if ( 'yes' === $settings['element_sharing_tooltip'] ) {
								echo '<span class="wpr-sharing-tooltip wpr-tooltip">'. esc_html__( 'Share', 'wpr-addons' ) .'</span>';
							}

							echo Utilities::get_wpr_icon( $settings['element_sharing_trigger_icon'], '' );
						echo '</a>';
					}


					echo '<span class="wpr-post-sharing-inner'. $hidden_class .'">';

					for ( $i = 1; $i < 7; $i++ ) {
						$args['network'] = $settings['element_sharing_icon_'. $i];

						echo Utilities::get_post_sharing_icon( $args );
					}

					echo '</span>';

				echo '</span>';

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Lightbox
	public static function render_product_lightbox( $settings, $class, $post_id ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				$lightbox_source = get_the_post_thumbnail_url( $post_id );

				// Audio Post Type
				if ( 'audio' === get_post_format() ) {
					// Load Meta Value
					if ( 'meta' === $settings['element_lightbox_pfa_select'] ) {
						$utilities = new Utilities();
						$meta_value = get_post_meta( $post_id, $settings['element_lightbox_pfa_meta'], true );

						// URL
						if ( false === strpos( $meta_value, '<iframe ' ) ) {
							add_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50, 3 );
								$track_url = wp_oembed_get( $meta_value );
							remove_filter( 'oembed_result', [ $utilities, 'filter_oembed_results' ], 50 );

						// Iframe
						} else {
							$track_url = Utilities::filter_oembed_results( $meta_value );
						}

						$lightbox_source = $track_url;
					}

				// Video Post Type
				} elseif ( 'video' === get_post_format() ) {
					// Load Meta Value
					if ( 'meta' === $settings['element_lightbox_pfv_select'] ) {
						$meta_value = get_post_meta( $post_id, $settings['element_lightbox_pfv_meta'], true );

						// URL
						if ( false === strpos( $meta_value, '<iframe ' ) ) {
							$video = \Elementor\Embed::get_video_properties( $meta_value );

						// Iframe
						} else {
							$video = \Elementor\Embed::get_video_properties( Utilities::filter_oembed_results($meta_value) );
						}

						// Provider URL
						if ( 'youtube' === $video['provider'] ) {
							$video_url = '//www.youtube.com/embed/'. $video['video_id'] .'?feature=oembed&autoplay=1&controls=1';
						} elseif ( 'vimeo' === $video['provider'] ) {
							$video_url = 'https://player.vimeo.com/video/'. $video['video_id'] .'?autoplay=1#t=0';
						}

						// Add Lightbox Attributes
						if ( isset( $video_url ) ) {
							$lightbox_source = $video_url;
						}
					}
				}

				// Lightbox Button
				echo '<span data-src="'. esc_url( $lightbox_source ) .'">';
				
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Lightbox Icon
					echo '<i class="'. esc_attr( $settings['element_extra_icon']['value'] ) .'"></i>';

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

				echo '</span>';

				// Media Overlay
				if ( 'yes' === $settings['element_lightbox_overlay'] ) {
					echo '<div class="wpr-grid-lightbox-overlay"></div>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Element Separator
	public static function render_product_element_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class .' '. $settings['element_separator_style']) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Render Status
	public static function render_product_status( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';

			// Sale
			 if ( $product->is_on_sale() ) {
				echo '<span class="wpr-woo-onsale">'. esc_html__( 'Sale', 'wpr-addons' ) .'</span>';
			}

			// Stock Status
			if ( 'yes' === $settings['element_status_offstock'] && $product->is_in_stock() == false && 
				 ! ( $product->is_type( 'variable' ) && $product->get_stock_quantity() > 0 ) ) {
				echo '<span class="wpr-woo-outofstock">'. esc_html__( 'Out of Stock', 'wpr-addons' ) .'</span>';
			}

			// Featured
			if ( 'yes' === $settings['element_status_featured'] && $product->is_featured() ) {
				echo '<span class="wpr-woo-featured">'. esc_html__( 'Featured', 'wpr-addons' ) .'</span>';
			}

			echo '</div>';
		echo '</div>';
	}

	// Render Price
	public static function render_product_price( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';

			echo '<span>'. wp_kses_post($product->get_price_html()) .'</span>';

			$sale_price_dates_to = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		
			// Apply filter to $sale_price_dates_to
			$sale_price_dates_to = apply_filters( 'wpr_custom_sale_price_dates_to_filter', $sale_price_dates_to, $product );
            
			echo $sale_price_dates_to;

			echo '</div>';
		echo '</div>';
	}

	public static function render_product_sale_dates( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		// $sale_price_dates_from  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_from', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		// $sale_price_dates_to  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n( 'Y-m-d', $date ) : '';
		$sale_price_dates_from  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_from', true ) ) ? date_i18n(get_option('date_format'), $date ) : '';
		$sale_price_dates_to  = ( $date = get_post_meta( $product->get_id(), '_sale_price_dates_to', true ) ) ? date_i18n(get_option('date_format'), $date ) : '';
		
		if ( ( 'yes' == $settings['show_sale_starts_date'] && !empty($sale_price_dates_from) ) || ( 'yes' == $settings['show_sale_ends_date'] && !empty($sale_price_dates_to) ) ) {
			echo '<div class="'. esc_attr($class) .'">';
				echo '<div class="inner-block">';

					echo '<span class="wpr-sale-dates">';
		
						// Text: Before
						if ( '' !== $settings['element_sale_starts_text'] && !empty($sale_price_dates_from) ) {
							echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_sale_starts_text'] ) .'</span> ';
						}
						
						if ( !empty($sale_price_dates_from) ) {
							echo  '<span>'. $sale_price_dates_from .'</span>';
						}


						if ( !empty($settings['element_sale_dates_sep']) && 'inline' == $settings['element_sale_dates_layout'] ) {
							if ( !empty($sale_price_dates_from) && !empty($sale_price_dates_to) ) {
								echo $settings['element_sale_dates_sep'];
							}
						}

						if ( 'block' == $settings['element_sale_dates_layout'] && !empty($sale_price_dates_form) && !empty($sale_price_dates_to) ) {
							echo '<br>';
						}
		
						// Text: Before
						if ( '' !== $settings['element_sale_ends_text'] && !empty($sale_price_dates_to) ) {
							echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_sale_ends_text'] ) .'</span> ';
						}

						if ( !empty($sale_price_dates_to) ) {
							echo  '<span>'. $sale_price_dates_to .'</span>';
						}

					echo '</span>';
	
				echo '</div>';
			echo '</div>';
		}
	}

    public static function render_rating_icon( $class, $unmarked_style ) {
        ?>

        <span class="wpr-rating-icon <?php echo esc_attr($class); ?>">
            <span class="wpr-rating-marked">
                <?php \Elementor\Icons_Manager::render_icon( [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] ); ?>
            </span>

            <span class="wpr-rating-unmarked">
                <?php 
                    if ( 'outline' === $unmarked_style ) {
                        \Elementor\Icons_Manager::render_icon( [ 'value' => 'far fa-star', 'library' => 'fa-regular' ], [ 'aria-hidden' => 'true' ] );
                    } else {
                        \Elementor\Icons_Manager::render_icon( [ 'value' => 'fas fa-star', 'library' => 'fa-solid' ], [ 'aria-hidden' => 'true' ] );
                    }
                 ?>
            </span>
        </span>

        <?php
    }

	// Render Rating
	public static function render_product_rating( $settings, $class ) {

		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		$rating_amount = floatval( $product->get_average_rating() );
		$round_rating = (int)$rating_amount;
		$rating_icon = '&#xE934;';

		if ( 'style-1' === $settings['element_rating_style'] ) {
			$style_class = ' wpr-woo-rating-style-1';
			if ( 'outline' === $settings['element_rating_unmarked_style'] ) {
				$rating_icon = '&#xE933;';
			}
		} elseif ( 'style-2' === $settings['element_rating_style'] ) {
			$rating_icon = '&#9733;';
			$style_class = ' wpr-woo-rating-style-2';

			if ( 'outline' === $settings['element_rating_unmarked_style'] ) {
				$rating_icon = '&#9734;';
			}
		}

		echo '<div class="'. esc_attr($class . $style_class) .'">';
			echo '<div class="inner-block">';

				echo '<div class="wpr-woo-rating">';

				if ( 'yes' === $settings['element_rating_score'] ) {
					if ( $rating_amount == 1 || $rating_amount == 2 || $rating_amount == 3 || $rating_amount == 4 || $rating_amount == 5 )  {
						$rating_amount = $rating_amount .'.0';
					}

					echo '<i class="wpr-rating-icon-10">'. esc_html($rating_icon) .'</i>';
					echo '<span>'. esc_html($rating_amount) .'</span>';
				} else {

                    if ( \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) && 'style-1' == $settings['element_rating_style'] ) {
                        for ( $b = 1; $b <= 5;  $b++ ) {
                        
                            if ( $b <= $rating_amount ) :
                                WPR_Woo_Grid_Helpers::render_rating_icon( 'wpr-rating-icon-full', $settings['element_rating_unmarked_style'] );
                            elseif ( $b === $round_rating + 1 && $rating_amount !== $round_rating ) :
                                WPR_Woo_Grid_Helpers::render_rating_icon( 'wpr-rating-icon-'. (( $rating_amount - $round_rating ) * 10), $settings['element_rating_unmarked_style'] );
                            else :
                                WPR_Woo_Grid_Helpers::render_rating_icon( 'wpr-rating-icon-0', $settings['element_rating_unmarked_style'] );
                            endif;

                        }
                    } else {
                        for ( $i = 1; $i <= 5; $i++ ) {

                            if ( $i <= $rating_amount ) {
                                echo '<i class="wpr-rating-icon-full">'. esc_html($rating_icon) .'</i>';
                            } elseif ( $i === $round_rating + 1 && $rating_amount !== $round_rating ) {
                                echo '<i class="wpr-rating-icon-'. esc_attr((( $rating_amount - $round_rating ) * 10)) .'">'. esc_html($rating_icon) .'</i>';
                            } else {
                                echo '<i class="wpr-rating-icon-empty">'. esc_html($rating_icon) .'</i>';
                            }
							
                        }
                    }
				}

				echo '</div>';

			echo '</div>';
		echo '</div>';
	}

	// Render Add To Cart
	public static function render_product_add_to_cart( $settings, $class, $general_settings ) {
		global $product;

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

		// Get Button Class
		$button_class = implode( ' ', array_filter( [
			'product_type_'. $product->get_type(),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
		] ) );

		$add_to_cart_animation = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'wpr-button-none' : $general_settings['add_to_cart_animation'];

		$popup_notification_animation = isset($general_settings['popup_notification_animation']) ? $general_settings['popup_notification_animation'] : '';
		$popup_notification_fade_out_in = isset($general_settings['popup_notification_fade_out_in']) ? $general_settings['popup_notification_fade_out_in'] : '';
		$popup_notification_animation_duration = isset($general_settings['popup_notification_animation_duration']) ? $general_settings['popup_notification_animation_duration'] : '';
		
		$attributes = [
			'rel="nofollow"',
			'class="'. esc_attr($button_class) .' wpr-button-effect '. esc_attr($add_to_cart_animation) .' '. (!$product->is_in_stock() && 'simple' === $product->get_type() ? 'wpr-atc-not-clickable' : '').'"',
			'aria-label="'. esc_attr($product->add_to_cart_description()) .'"',
			'data-product_id="'. esc_attr($product->get_id()) .'"',
			'data-product_sku="'. esc_attr($product->get_sku()) .'"',
			'data-atc-popup="'. esc_attr( $settings['element_show_added_tc_popup'] ) .'"',
			'data-atc-animation="'. esc_attr($popup_notification_animation)  .'"',
			'data-atc-fade-out-in="'. esc_attr($popup_notification_fade_out_in)  .'"',
			'data-atc-animation-time="'. esc_attr($popup_notification_animation_duration)  .'"'
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();

		// Icon: Before
		if ( 'before' === $settings['element_extra_icon_pos'] ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
			$extra_icon = ob_get_clean();

			$button_HTML .= '<span class="wpr-grid-extra-icon-left">'. $extra_icon .'</span>';
		}

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
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} elseif ( 'variable' === $product->get_type() ) {
			$button_HTML .= $settings['element_addcart_variable_txt'];
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} else if ( 'pw-gift-card' === $product->get_type() ) {
			$button_HTML .= esc_html__('Select Amount', 'wpr-addons');
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} else if ( 'ywf_deposit' === $product->get_type() ) {
			$button_HTML .= esc_html__('Select Amount', 'wpr-addons');
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} else if ( 'stm_lms_product' === $product->get_type() ) {
			$button_HTML .= esc_html__('View Product', 'wpr-addons');
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} else if ( 'redq_rental' === $product->get_type() ) {
			$button_HTML .= esc_html__('View Product', 'wpr-addons');
			array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
		} else {
			if ( !is_callable( array( $product, 'get_product_url' ) ) ) {
				$button_HTML .= esc_html__('View Product', 'wpr-addons');
				array_push( $attributes, 'href="'. esc_url( get_permalink() ) .'"' );
			} else {
				array_push( $attributes, 'href="'. esc_url( $product->get_product_url() ) .'"' );
				$button_HTML .= get_post_meta( get_the_ID(), '_button_text', true ) ? get_post_meta( get_the_ID(), '_button_text', true ) : esc_html__('Buy Product');
			}
		}

		// Icon: After
		if ( 'after' === $settings['element_extra_icon_pos'] ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
			$extra_icon = ob_get_clean();

			$button_HTML .= '<span class="wpr-grid-extra-icon-right">'. $extra_icon .'</span>';
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
			
			// WooCommerce Hook: Before Add to Cart Button
			// do_action('woocommerce_before_shop_loop_item');

			if ( $button_HTML != apply_filters( 'woocommerce_loop_add_to_cart_link', $button_HTML, $product ) ) {
				echo apply_filters( 'woocommerce_loop_add_to_cart_link', $button_HTML, $product );
			} else {
				// Button HTML
				echo '<a '. implode( ' ', $attributes ) .'><span>'. $button_HTML .'</span></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		
			// WooCommerce Hook: After Add to Cart Button
			// do_action('woocommerce_after_shop_loop_item');

			echo '</div>';
		echo '</div>';
	}

	// Add two new functions for handling cookies
	public static function get_wishlist_from_cookie() {
        if (isset($_COOKIE['wpr_wishlist'])) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist']), true);
        } else if ( isset($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_wishlist_'. get_current_blog_id() .'']), true);
        }
        return array();
	}

	// Render Wishlist Button
	public static function render_product_wishlist_button( $settings, $class, $general_settings ) {
		global $product;
		
		if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->is_plan( 'expert' ) ) {
			return;
		}

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

        $user_id = get_current_user_id();
		
		if ($user_id > 0) {
			$wishlist = get_user_meta( get_current_user_id(), 'wpr_wishlist', true );
		} else {
			$wishlist = WPR_Woo_Grid_Helpers::get_wishlist_from_cookie();
		}
		
		if ( ! $wishlist ) {
			$wishlist = array();
		}

		$popup_notification_animation = isset($general_settings['popup_notification_animation']) ? $general_settings['popup_notification_animation'] : '';
		$popup_notification_fade_out_in = isset($general_settings['popup_notification_fade_out_in']) ? $general_settings['popup_notification_fade_out_in'] : '';
		$popup_notification_animation_duration = isset($general_settings['popup_notification_animation_duration']) ? $general_settings['popup_notification_animation_duration'] : '';

		$wishlist_attributes = [
			'data-wishlist-url' => get_option('wpr_wishlist_page') ? get_option('wpr_wishlist_page') : '',
			'data-atw-popup="'. esc_attr($settings['element_show_added_to_wishlist_popup'])  .'"',
			'data-atw-animation="'. esc_attr($popup_notification_animation)  .'"',
			'data-atw-fade-out-in="'. esc_attr($popup_notification_fade_out_in)  .'"',
			'data-atw-animation-time="'. esc_attr($popup_notification_animation_duration)  .'"',
			'data-open-in-new-tab="'. esc_attr($settings['element_open_links_in_new_tab']) .'"'
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();
		
		$button_add_title = '';
		$button_remove_title = '';
		$add_to_wishlist_content = '';
		$remove_from_wishlist_content = '';
		

		if ( 'yes' === $settings['show_icon'] ) {
			$add_to_wishlist_content .= '<i class="far fa-heart"></i>';
			$remove_from_wishlist_content .= '<i class="fas fa-heart"></i>';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$add_to_wishlist_content .= ' <span>'. esc_html__($settings['add_to_wishlist_text']) .'</span>';
		} else {
			$button_add_title = 'title="'. esc_html__($settings['add_to_wishlist_text']) .'"';
			$button_remove_title = 'title="'. esc_html__($settings['remove_from_wishlist_text']) .'"';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$remove_from_wishlist_content .= ' <span>'. esc_html__($settings['remove_from_wishlist_text']) .'</span>';
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
	
			$remove_button_hidden = !in_array( $product->get_id(), $wishlist ) ? 'wpr-button-hidden' : '';
			$add_button_hidden = in_array( $product->get_id(), $wishlist ) ? 'wpr-button-hidden' : '';
		
			// '. implode( ' ', $wishlist_attributes ) .'
			echo '<button class="wpr-wishlist-add '. $add_button_hidden .'" '. $button_add_title .' data-product-id="' . $product->get_id() . '"'. ' ' . implode( ' ', $wishlist_attributes ) .' >'. $add_to_wishlist_content .'</button>';
			echo '<button class="wpr-wishlist-remove '. $remove_button_hidden .'" '. $button_remove_title .' data-product-id="' . $product->get_id() . '">'. $remove_from_wishlist_content .'</button>';

			echo '</div>';
		echo '</div>';
	}
	
	// Add two new functions for handling cookies
	public static function get_compare_from_cookie() {
        if (isset($_COOKIE['wpr_compare'])) {
            return json_decode(stripslashes($_COOKIE['wpr_compare']), true);
        } else if ( isset($_COOKIE['wpr_compare_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_compare_'. get_current_blog_id() .'']), true);
        }
        return array();
	}

	// Render Compare Button
	public static function render_product_compare_button( $settings, $class, $general_settings ) {
		global $product;
		
		if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->is_plan( 'expert' ) ) {
			return;
		}

		// If NOT a Product
		if ( is_null( $product ) ) {
			return;
		}

        $user_id = get_current_user_id();
		
		if ($user_id > 0) {
			$compare = get_user_meta(  $user_id, 'wpr_compare', true );
		
			if ( ! $compare ) {
				$compare = array();
			}
		} else {
			$compare = WPR_Woo_Grid_Helpers::get_compare_from_cookie();
		}

		$popup_notification_animation = isset($general_settings['popup_notification_animation']) ? $general_settings['popup_notification_animation'] : '';
		$popup_notification_fade_out_in = isset($general_settings['popup_notification_fade_out_in']) ? $general_settings['popup_notification_fade_out_in'] : '';
		$popup_notification_animation_duration = isset($general_settings['popup_notification_animation_duration']) ? $general_settings['popup_notification_animation_duration'] : '';

		$compare_attributes = [
			'data-compare-url' => get_option('wpr_compare_page') ? get_option('wpr_compare_page') : '',
			'data-atcompare-popup="'. esc_attr($settings['element_show_added_to_compare_popup'])  .'"',
			'data-atcompare-animation="'. esc_attr($popup_notification_animation)  .'"',
			'data-atcompare-fade-out-in="'. esc_attr($popup_notification_fade_out_in)  .'"',
			'data-atcompare-animation-time="'. esc_attr($popup_notification_animation_duration)  .'"',
			'data-open-in-new-tab="'. esc_attr($settings['element_open_links_in_new_tab']) .'"'
		];

		$button_HTML = '';
		$page_id = get_queried_object_id();
		
		$add_to_compare_content = '';
		$remove_from_compare_content = '';
		$button_add_title = '';
		$button_remove_title = '';
		

		if ( 'yes' === $settings['show_icon'] ) {
			$add_to_compare_content .= '<i class="fas fa-exchange-alt"></i>';
			$remove_from_compare_content .= '<i class="fas fa-exchange-alt"></i>';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$add_to_compare_content .= ' <span>'. esc_html__($settings['add_to_compare_text']) .'</span>';
		} else {
			$button_add_title = 'title="'. esc_html__($settings['add_to_compare_text']) .'"';
			$button_remove_title = 'title="'. esc_html__($settings['remove_from_compare_text']) .'"';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$remove_from_compare_content .= ' <span>'. esc_html__($settings['remove_from_compare_text']) .'</span>';
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
	
			$remove_button_hidden = !in_array( $product->get_id(), $compare ) ? 'wpr-button-hidden' : '';
			$add_button_hidden = in_array( $product->get_id(), $compare ) ? 'wpr-button-hidden' : '';
		
			// '. implode( ' ', $compare_attributes ) .'
			echo '<button class="wpr-compare-add '. $add_button_hidden .'" '. $button_add_title .' data-product-id="' . $product->get_id() . '"'. ' ' . implode( ' ', $compare_attributes ) .' >'. $add_to_compare_content .'</button>';
			echo '<button class="wpr-compare-remove '. $remove_button_hidden .'" '. $button_remove_title .' data-product-id="' . $product->get_id() . '">'. $remove_from_compare_content .'</button>';

			echo '</div>';
		echo '</div>';
	}

	// Render Post Read More
	public static function render_product_read_more( $settings, $class, $general_settings ) {
		$read_more_animation = 'wpr-button-none';
		$open_links_in_new_tab = 'yes' === $general_settings['open_links_in_new_tab'] ? '_blank' : '_self';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a target="'. $open_links_in_new_tab .'" href="'. esc_url( get_the_permalink() ) .'" class="wpr-button-effect '. esc_attr($read_more_animation) .'">';

				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="wpr-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}

				// Read More Text
				echo '<span>'. esc_html( $settings['element_read_more_text'] ) .'</span>';

				// Icon: After
				if ( 'after' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();
		
					echo '<span class="wpr-grid-extra-icon-right">';
						echo $extra_icon;
					echo '</span>';
				}

				echo '</a>';
			echo '</div>';
		echo '</div>';
	}

	// Render Custom Fields/Attributes
	public static function render_product_custom_fields( $settings, $class, $post_id ) {
		$custom_field_value = get_post_meta( $post_id, $settings['element_custom_field'], true );
		$custom_field_html = '';
		// $custom_field_html = $settings['element_custom_field_wrapper_html'];
		
		// Check if custom field value is empty
		if ( empty( $custom_field_value ) ) {
			// If custom field is empty, try to get the product attribute
			$product = wc_get_product( $post_id );

			// Replace 'attribute_name' with the actual attribute name you want to retrieve
			$attribute_name = $settings['element_custom_field'];

			// Check if the product has the specified attribute
			if ( $product && $product->get_attribute( $attribute_name ) ) {
				$custom_field_value = $product->get_attribute( $attribute_name );
			}
		}


		if ( has_filter('wpr_update_custom_field_value') ) {
			ob_start();
			apply_filters('wpr_update_custom_field_value', $custom_field_value, $post_id, $settings['element_custom_field']);
			$custom_field_value = ob_get_clean();
		}

		// Get First Value if Array (works only for single value checkboxes)
		if ( is_array($custom_field_value) && 1 === count($custom_field_value) ) {
			if ( isset($custom_field_value[0]) && !empty($custom_field_value[0]) ) {
				$custom_field_value = $custom_field_value[0];
			} else {
				$custom_field_value = '';
			}
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
				if ( isset($settings['element_custom_field_img_ID']) && 'yes' === $settings['element_custom_field_img_ID'] ) {
					$cf_img = wp_get_attachment_image_src( $custom_field_value, 'full' );
					if ( isset($cf_img) ) {
						echo '<img src="'. esc_url($cf_img[0]) .'" alt="" width="'. esc_attr($cf_img[1]) .'" height="'. esc_attr($cf_img[2]) .'">';
					}
				} else {
					if ( 'yes' !== $settings['element_custom_field_btn_link'] ) {
						echo '<span>';
							// if ( 'yes' === $settings['element_custom_field_wrapper'] ) {
							if ( false ) {
								echo str_replace( '*cf_value*', $custom_field_value, $custom_field_html );
							} else {
								echo $custom_field_value;
							}
						echo '</span>';
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

				if ( isset($settings['element_custom_field_btn_link']) && 'yes' === $settings['element_custom_field_btn_link'] ) {
					echo '</a>';
				} else {
					echo '</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Get Elements
	public static function get_elements( $type, $settings, $class, $post_id, $general_settings ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-sd' == $type || 'pro-ws' == $type || 'pro-cm' == $type || 'pro-cfa' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
			case 'title':
				WPR_Woo_Grid_Helpers::render_product_title( $settings, $class, $general_settings );
				do_action( 'wpr_woo_grid_after_title', $post_id );
				break;

			case 'excerpt':
				WPR_Woo_Grid_Helpers::render_product_excerpt( $settings, $class );
				break;

			case 'product_cat':
				WPR_Woo_Grid_Helpers::render_product_categories( $settings, $class, $post_id, $general_settings );
				break;

			case 'product_tag':
				WPR_Woo_Grid_Helpers::render_product_tags( $settings, $class, $post_id, $general_settings );
				break;

			case 'likes':
				WPR_Woo_Grid_Helpers::render_product_likes( $settings, $class, $post_id );
				break;

			case 'sharing':
				WPR_Woo_Grid_Helpers::render_product_sharing_icons( $settings, $class );
				break;

			case 'lightbox':
				WPR_Woo_Grid_Helpers::render_product_lightbox( $settings, $class, $post_id );
				break;

			case 'separator':
				WPR_Woo_Grid_Helpers::render_product_element_separator( $settings, $class );
				break;

			case 'status':
				WPR_Woo_Grid_Helpers::render_product_status( $settings, $class );
				break;

			case 'price':
				WPR_Woo_Grid_Helpers::render_product_price( $settings, $class );
				break;

			case 'sale_dates':
				WPR_Woo_Grid_Helpers::render_product_sale_dates( $settings, $class );
				break;

			case 'rating':
				WPR_Woo_Grid_Helpers::render_product_rating( $settings, $class );
				break;

			case 'add-to-cart':
				WPR_Woo_Grid_Helpers::render_product_add_to_cart( $settings, $class, $general_settings );
				break;
			case 'wishlist-button':
				if ( defined('WPR_ADDONS_PRO_VERSION') && wpr_fs()->is_plan( 'expert' ) ) {
					WPR_Woo_Grid_Helpers::render_product_wishlist_button( $settings, $class, $general_settings );
				}
				break;
			case 'compare-button':
				if ( defined('WPR_ADDONS_PRO_VERSION') && wpr_fs()->is_plan( 'expert' ) ) {
					WPR_Woo_Grid_Helpers::render_product_compare_button( $settings, $class, $general_settings );
				}
				break;
			case 'custom-field':
				WPR_Woo_Grid_Helpers::render_product_custom_fields( $settings, $class, $post_id );
				break;

			case 'read-more':
				WPR_Woo_Grid_Helpers::render_product_read_more( $settings, $class, $general_settings );
			default:
				WPR_Woo_Grid_Helpers::render_product_categories( $settings, $class, $post_id, $general_settings );
				break;
		}

	}

	// Get Elements by Location
	public static function get_elements_by_location( $location, $settings, $post_id ) {
		$locations = [];

		foreach ( $settings['grid_elements'] as $data ) {
			$place = $data['element_location'];
			$align_vr = $data['element_align_vr'];

			if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ) {
				$align_vr = 'middle';
			}

			if ( ! isset($locations[$place]) ) {
				$locations[$place] = [];
			}
			
			if ( 'over' === $place ) {
				if ( ! isset($locations[$place][$align_vr]) ) {
					$locations[$place][$align_vr] = [];
				}

				array_push( $locations[$place][$align_vr], $data );
			} else {
				array_push( $locations[$place], $data );
			}
		}

		if ( ! empty( $locations[$location] ) ) {

			if ( 'over' === $location ) {
				foreach ( $locations[$location] as $align => $elements ) {
					if ( 'middle' === $align ) {
						echo '<div class="wpr-cv-container"><div class="wpr-cv-outer"><div class="wpr-cv-inner">';
					}

					echo '<div class="wpr-grid-media-hover-'. esc_attr($align) .' elementor-clearfix">';
						foreach ( $elements as $data ) {
							
							// Get Class
							$class  = 'wpr-grid-item-'. $data['element_select'];
							$class .= ' elementor-repeater-item-'. $data['_id'];
							$class .= ' wpr-grid-item-display-'. $data['element_display'];
							$class .= ' wpr-grid-item-align-'. $data['element_align_hr'];
							$class .= WPR_Grid_Helpers::get_animation_class( $data, 'element' );

							// Element
							WPR_Woo_Grid_Helpers::get_elements( $data['element_select'], $data, $class, $post_id, $settings );
						}
					echo '</div>';

					if ( 'middle' === $align ) {
						echo '</div></div></div>';
					}
				}
			} else {
				echo '<div class="wpr-grid-item-'. esc_attr($location) .'-content elementor-clearfix">';
					foreach ( $locations[$location] as $data ) {

						// Get Class
						$class  = 'wpr-grid-item-'. $data['element_select'];
						$class .= ' elementor-repeater-item-'. $data['_id'];
						$class .= ' wpr-grid-item-display-'. $data['element_display'];
						$class .= ' wpr-grid-item-align-'. $data['element_align_hr'];

						// Element
						WPR_Woo_Grid_Helpers::get_elements( $data['element_select'], $data, $class, $post_id, $settings );
					}
				echo '</div>';
			}

		}
	}

	public static function get_hidden_filter_class($slug, $settings) {
		$posts = new \WP_Query( WPR_Woo_Grid_Helpers::get_main_query_args($settings, []) );
		$visible_categories = [];

		if ( $posts->have_posts() ) {
			while ( $posts->have_posts() ) {
				$posts->the_post();
				$categories = get_the_category();

				foreach ($categories as $key => $category) {
					array_push($visible_categories, $category->slug);
				}
			}

			$visible_categories = array_unique($visible_categories);

			wp_reset_postdata();
		}

		return ( ! in_array($slug, $visible_categories) && 'yes' == $settings['filters_hide_empty'] ) ? ' wpr-hidden-element' : '';
	}

	// Render Grid Pagination
	public static function render_grid_pagination( $settings ) {
		// Return if Disabled
		if ( 'yes' !== $settings['layout_pagination'] || 'slider' === $settings['layout_select'] ) {
			return;
		}

		if ( 'yes' !== $settings['advanced_filters'] && 1 === WPR_Woo_Grid_Helpers::get_max_num_pages( $settings ) ) {
			return;
		}
		
		if ( (isset(WPR_Woo_Grid_Helpers::$my_upsells) && (count(WPR_Woo_Grid_Helpers::$my_upsells) <= $settings['query_posts_per_page'])) || (isset(WPR_Woo_Grid_Helpers::$crossell_ids) && (count(WPR_Woo_Grid_Helpers::$crossell_ids) <= $settings['query_posts_per_page'])) ) {
			return;
		}

		global $paged;
		$pages = WPR_Woo_Grid_Helpers::get_max_num_pages( $settings );
		$paged = empty( $paged ) ? 1 : $paged;

		if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ) {
			$settings['pagination_type'] = 'pro-is' == $settings['pagination_type'] ? 'default' : $settings['pagination_type'];
		}

		echo '<div class="wpr-grid-pagination elementor-clearfix wpr-grid-pagination-'. esc_attr($settings['pagination_type']) .'" data-pages="'. esc_attr($pages) .'">';

		// Default
		if ( 'default' === $settings['pagination_type'] ) {
			if ( $paged < $pages ) {
				echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="wpr-prev-post-link">';
					echo Utilities::get_wpr_icon( $settings['pagination_on_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo esc_html($settings['pagination_older_text']);
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="wpr-prev-post-link wpr-disabled-arrow">';
					echo Utilities::get_wpr_icon( $settings['pagination_on_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					echo esc_html($settings['pagination_older_text']);
				echo '</span>';
			}

			if ( $paged > 1 ) {
				echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="wpr-next-post-link">';
					echo esc_html($settings['pagination_newer_text']);
					echo Utilities::get_wpr_icon( $settings['pagination_on_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</a>';
			} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
				echo '<span class="wpr-next-post-link wpr-disabled-arrow">';
					echo esc_html($settings['pagination_newer_text']);
					echo Utilities::get_wpr_icon( $settings['pagination_on_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				echo '</span>';
			}

		// Numbered
		} elseif ( 'numbered' === $settings['pagination_type'] ) {
			$range = $settings['pagination_range'];
			$showitems = ( $range * 2 ) + 1;

			if ( 1 !== $pages ) {

			    if ( 'yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last'] ) {
			    	echo '<div class="wpr-grid-pagi-left-arrows">';

				    if ( 'yes' === $settings['pagination_first_last'] ) {
				    	if ( $paged >= 2 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( 1, true )) .'" class="wpr-first-page">';
					    	// echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="wpr-first-page">';
					    	// echo '<a href="'. esc_url(substr(get_pagenum_link( $paged + 1, true ), 0, strpos(get_pagenum_link( $paged + 1, true ), '?orderby'))) .'" class="wpr-first-page">';
					    		echo Utilities::get_wpr_icon( $settings['pagination_fl_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    		echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="wpr-first-page wpr-disabled-arrow">';
					    		echo Utilities::get_wpr_icon( $settings['pagination_fl_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    		echo '<span>'. esc_html($settings['pagination_first_text']) .'</span>';
					    	echo '</span>';
				    	}
				    }

				    if ( 'yes' === $settings['pagination_prev_next'] ) {
				    	if ( $paged > 1 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $paged - 1, true )) .'" class="wpr-prev-page">';
					    		echo Utilities::get_wpr_icon( $settings['pagination_pn_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    		echo '<span>'. esc_html($settings['pagination_prev_text']) .'</span>';
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="wpr-prev-page wpr-disabled-arrow">';
					    		echo Utilities::get_wpr_icon( $settings['pagination_pn_icon'], 'left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    		echo '<span>'. esc_html($settings['pagination_prev_text']) .'</span>';
					    	echo '</span>';
				    	}
				    }

				    echo '</div>';
			    }

			    for ( $i = 1; $i <= $pages; $i++ ) {
			        if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
						if ( $paged === $i ) {
							echo '<span class="wpr-grid-current-page">'. esc_html($i) .'</span>';
						} else {
							// var_dump(get_pagenum_link( $i, true ), substr(get_pagenum_link( $i, true ), 0, strpos(get_pagenum_link( $i, true ), '?orderby')));
							echo '<a href="'. esc_url(get_pagenum_link( $i, true )) .'">'. esc_html($i) .'</a>';
						}
			        }
			    }

			    if ( 'yes' === $settings['pagination_prev_next'] || 'yes' === $settings['pagination_first_last'] ) {
			    	echo '<div class="wpr-grid-pagi-right-arrows">';

				    if ( 'yes' === $settings['pagination_prev_next'] ) {
				    	if ( $paged < $pages ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="wpr-next-page">';
					    		echo '<span>'. esc_html($settings['pagination_next_text']) .'</span>';
					    		echo Utilities::get_wpr_icon( $settings['pagination_pn_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="wpr-next-page wpr-disabled-arrow">';
					    		echo '<span>'. esc_html($settings['pagination_next_text']) .'</span>';
					    		echo Utilities::get_wpr_icon( $settings['pagination_pn_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    	echo '</span>';
				    	}
				    }

				    if ( 'yes' === $settings['pagination_first_last'] ) {
				    	if ( $paged <= $pages - 1 ) {
					    	echo '<a href="'. esc_url(get_pagenum_link( $pages, true )) .'" class="wpr-last-page">';
					    		echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
					    		echo Utilities::get_wpr_icon( $settings['pagination_fl_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    	echo '</a>';
				    	} elseif ( 'yes' === $settings['pagination_disabled_arrows'] ) {
					    	echo '<span class="wpr-last-page wpr-disabled-arrow">';
					    		echo '<span>'. esc_html($settings['pagination_last_text']) .'</span>';
					    		echo Utilities::get_wpr_icon( $settings['pagination_fl_icon'], 'right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					    	echo '</span>';
				    	}
				    }

				    echo '</div>';
			    }
			}

		// Load More / Infinite Scroll
		} else {
			echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="wpr-load-more-btn" data-e-disable-page-transition>';
			// echo '<a href="'. esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ) .'" class="wpr-load-more-btn" data-e-disable-page-transition>';
			// echo '<a href="'. esc_url(get_next_posts_page_link()) .'" class="wpr-load-more-btn" data-e-disable-page-transition>';
				echo esc_html($settings['pagination_load_more_text']);
			echo '</a>';

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

			echo '<p class="wpr-pagination-finish">'. esc_html($settings['pagination_finish_text']) .'</p>';
		}

		echo '</div>';
	}

	public function wpr_get_filtered_count_products() {
		$nonce = $_POST['nonce'];

		if (!isset($nonce) || !wp_verify_nonce($nonce, 'wpr-addons-js')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}
		
		if ( isset($_POST['wpr_url_params']) ) {
			$results = [];

			// Loop through each set of parameters
			foreach ($_POST['wpr_url_params'] as $params) {
				$query_args = WPR_Woo_Grid_Helpers::get_main_query_args($_POST['grid_settings'], $params);
				$query = new \WP_Query($query_args);
		
				// Add the count of found posts to the results array
				$results[] = [
					'found_posts' => $query->found_posts,
					'post_count' => $query->post_count,
					'params' => $params
				];
		
				wp_reset_postdata();
			}

			// Send the array of results
			wp_send_json_success($results);
		} else {
			$settings = $_POST['grid_settings'];
			$page_count = $this->get_max_num_pages( $settings );

			wp_reset_postdata();
		
			wp_send_json_success([
				'page_count' => $page_count,
			]);
		}

		wp_die();
	}

	public function wpr_woo_grid_filters_ajax() {
		$start = microtime(true);
		// Get Settings
		$settings = $_POST['grid_settings'];

		ob_start();

		// Get Posts
		$posts = new \WP_Query( WPR_Woo_Grid_Helpers::get_main_query_args($settings, []) );

		// Loop: Start
		if ( $posts->have_posts() ) :

		while ( $posts->have_posts() ) : $posts->the_post();

			// Post Class
			$post_class = implode( ' ', get_post_class( 'wpr-grid-item elementor-clearfix', get_the_ID() ) );

			// Grid Item
			echo '<article class="'. esc_attr( $post_class ) .'">';

			// Password Protected Form
			WPR_Woo_Grid_Helpers::render_password_protected_input( $settings );

			// Inner Wrapper
			echo '<div class="wpr-grid-item-inner">';

			// Content: Above Media
			WPR_Woo_Grid_Helpers::get_elements_by_location( 'above', $settings, get_the_ID() );

			// Media
			if ( has_post_thumbnail() ) {
				echo '<div class="wpr-grid-media-wrap'. esc_attr(WPR_Woo_Grid_Helpers::get_image_effect_class( $settings )) .' " data-overlay-link="'. esc_attr( $settings['overlay_post_link'] ) .'">';
					// Post Thumbnail
					if ( !empty($settings) ) {
						WPR_Woo_Grid_Helpers::render_product_thumbnail( $settings, get_the_ID() );
					}

					// Media Hover
					echo '<div class="wpr-grid-media-hover wpr-animation-wrap">';
						// Media Overlay
						WPR_Woo_Grid_Helpers::render_media_overlay( $settings );

						// Content: Over Media
						WPR_Woo_Grid_Helpers::get_elements_by_location( 'over', $settings, get_the_ID() );

					echo '</div>';
				echo '</div>';
			}

			// Content: Below Media
			WPR_Woo_Grid_Helpers::get_elements_by_location( 'below', $settings, get_the_ID() );

			echo '</div>'; // End .wpr-grid-item-inner

			echo '</article>'; // End .wpr-grid-item

		endwhile;

		// reset
		wp_reset_postdata();

		// Loop: End
		else :

			if ( 'dynamic' === $settings['query_selection'] || 'current' === $settings['query_selection'] ) {
				echo '<h2>'. esc_html($settings['query_not_found_text']) .'</h2>';
			}

		endif;

		// Get the buffered content
		$output = ob_get_clean();
	
		// Return the output
		$end = microtime(true);
		$duration = round(($end - $start) * 1000, 2); // in ms
		wp_send_json_success([
			'output' => $output,
			'duration' => $duration,
			'found_posts' => $posts->found_posts,
			'post_count' => $posts->post_count
		]);

		wp_die();
	}

}

new WPR_Woo_Grid_Helpers();