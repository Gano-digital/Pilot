<?php
namespace WprAddons\Classes\Modules;

use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;
use WprAddons\Classes\Modules\WPR_Post_Likes;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Grid_Helpers setup
 *
 * @since 3.4.6
 */

 class WPR_Grid_Helpers {

    public function __construct() {
		add_action('wp_ajax_wpr_grid_filters_ajax', [$this, 'wpr_grid_filters_ajax']);
		add_action('wp_ajax_nopriv_wpr_grid_filters_ajax', [$this, 'wpr_grid_filters_ajax']);
		add_action('wp_ajax_wpr_get_filtered_count_posts', [$this, 'wpr_get_filtered_count_posts']);
		add_action('wp_ajax_nopriv_wpr_get_filtered_count_posts', [$this, 'wpr_get_filtered_count_posts']);
		add_action('wp_ajax_wpr_get_dependent_terms', [$this, 'get_dependent_terms']);
		add_action('wp_ajax_nopriv_wpr_get_dependent_terms', [$this, 'get_dependent_terms']);
    }

	public function get_dependent_terms() {
		// check_ajax_referer('wpr_addons_elementor', 'nonce');

		if ( empty($_POST['taxonomy']) || empty($_POST['parent_term']) ) {
			wp_send_json_error('Missing data');
		}

		$taxonomy    = sanitize_text_field($_POST['taxonomy']);
		$parent_raw  = sanitize_text_field($_POST['parent_term']);

		// Determine if parent_term is ID or slug
		if ( is_numeric($parent_raw) ) {
			$related_term = get_term(intval($parent_raw));
		} else {
			// Optional: detect the related taxonomy (requires an extra POST param or default)
			$related_taxonomy = sanitize_text_field($_POST['related_taxonomy'] ?? '');
			if ( empty($related_taxonomy) ) {
				wp_send_json_error('Missing related taxonomy for slug');
			}
			$related_term = get_term_by('slug', $parent_raw, $related_taxonomy);
		}

		if ( ! $related_term || is_wp_error($related_term) ) {
			wp_send_json_error('Invalid parent term');
		}

		$related_taxonomy = $related_term->taxonomy;
		$tax_array = [];

		if ( isset($_POST['tax_array']) ) {
			$related_taxonomies = $_POST['tax_array'];
			$related_terms = $_POST['parent_terms'];

			// Add relation AND
			$tax_array['relation'] = 'AND';

			foreach ( $related_taxonomies as $index => $tax ) {
				if ( isset($related_terms[$index]) && $related_terms[$index] !== '' ) {
					$tax_array[] = [
						'taxonomy' => sanitize_text_field($tax),
						'field'    => 'term_id',
						'terms'    => intval($related_terms[$index]),
					];
				}
			}
		} else {
			$tax_array[] = [
					'taxonomy' => $related_taxonomy,
					'field'    => 'term_id',
					'terms'    => $related_term->term_id,
			];
		}

		// Get all posts with the related term
		$posts = get_posts([
			'post_type'      => 'any',
			'posts_per_page' => -1,
			'tax_query'      => $tax_array,
			'fields' => 'ids',
		]);

		if ( empty($posts) ) {
			wp_send_json_success([]);
		}

		// Get all terms from the target taxonomy used in these posts
		$terms = wp_get_object_terms($posts, $taxonomy, [
			'hide_empty' => true,
		]);

		$options = [];
		foreach ( $terms as $term ) {
			$options[] = [
				'id' => $term->term_id,
				'name' => $term->name,
				// 'posts' => $posts,
				// 'related_tax' => $related_taxonomy,
				// 'related_term' => $related_term->slug,
				// 'taxonomy' => $taxonomy,
			];
		}

		wp_send_json_success($options);
	}
    
	// Get Taxonomies Related to Post Type
	public static function get_related_taxonomies() {
		$relations = [];
		$post_types = Utilities::get_custom_types_of( 'post', false );

		foreach ( $post_types as $slug => $title ) {
			$relations[$slug] = [];

			foreach ( get_object_taxonomies( $slug ) as $tax ) {
				array_push( $relations[$slug], $tax );
			}
		}

		return json_encode( $relations );
	}

	// Get Max Pages
	public static function get_max_num_pages( $settings ) {
		if ( isset($_POST['wpr_url_params']) ) {	
			$query = new \WP_Query( WPR_Grid_Helpers::get_main_query_args($settings, []) );
			$max_num_pages = intval( ceil( $query->max_num_pages ) );

			// Reset
			wp_reset_postdata();

			// $max_num_pages
			return $max_num_pages;
		} else if ( isset($_POST['grid_settings']) ) {
			$query = new \WP_Query(WPR_Grid_Helpers::get_main_query_args($settings, []) );
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

			// Reset
			wp_reset_postdata();

			// $max_num_pages
			return $max_num_pages;
		} else {
			$query = new \WP_Query( WPR_Grid_Helpers::get_main_query_args($settings, []) );
			$max_num_pages = intval( ceil( $query->max_num_pages ) );

			// Reset
			wp_reset_postdata();

			// $max_num_pages
			return $max_num_pages;
		}
	}

	/**
	 * Check if the current user is allowed to query the post type(s) in grid settings.
	 * Prevents unauthenticated disclosure of non-public post types (e.g. flamingo_inbound, shop_coupon).
	 *
	 * @param array $settings Grid settings (e.g. $_POST['grid_settings']).
	 * @return bool True if allowed, false otherwise.
	 */
	public static function is_allowed_grid_query_post_type( $settings ) {
		if ( empty( $settings ) || ! is_array( $settings ) ) {
			return false;
		}

		$query_source = isset( $settings['query_source'] ) ? $settings['query_source'] : '';

		// Resolve effective post type(s) to validate
		if ( 'current' === $query_source && ! empty( $settings['current_query_source'] ) ) {
			$post_types = (array) $settings['current_query_source'];
		} elseif ( 'related' !== $query_source && '' !== $query_source ) {
			$post_types = (array) $query_source;
		} else {
			// 'related' uses get_post_type(get_the_ID()) in get_main_query_args; no user-controlled type
			return true;
		}

		$is_logged_in = is_user_logged_in();

		// For unauthenticated requests, only allow post types that are publicly queryable
		if ( ! $is_logged_in ) {
			$allowed_types = get_post_types( array( 'publicly_queryable' => true ), 'names' );
			foreach ( $post_types as $post_type ) {
				$post_type = sanitize_key( $post_type );
				if ( '' === $post_type ) {
					continue;
				}
				if ( ! in_array( $post_type, $allowed_types, true ) ) {
					return false;
				}
			}
			return true;
		}

		// Logged-in: allow if publicly_queryable OR user has capability to read that post type
		foreach ( $post_types as $post_type ) {
			$post_type = sanitize_key( $post_type );
			if ( '' === $post_type ) {
				continue;
			}
			$obj = get_post_type_object( $post_type );
			if ( ! $obj ) {
				return false;
			}
			if ( $obj->publicly_queryable ) {
				continue;
			}
			$cap = isset( $obj->cap->read_private_posts ) ? $obj->cap->read_private_posts : 'read';
			if ( ! current_user_can( $cap ) ) {
				return false;
			}
		}
		return true;
	}

	// Main Query Args
	public static function get_main_query_args($settings, $params) {
		$author = ! empty( $settings[ 'query_author' ] ) ? implode( ',', $settings[ 'query_author' ] ) : '';

		// if ( is_user_logged_in() ){
		// 	$logged_in_user = wp_get_current_user();
		// 	$author = '1' . ',' . $logged_in_user->ID;
		// }

		// Get Paged
		if ( get_query_var( 'paged' ) ) {
			$paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$paged = get_query_var( 'page' );
		} else {
			$paged = 1;
		}

		// Change Posts Per Page for Slider Layout
		if ( 'slider' === $settings['layout_select'] && Utilities::is_new_free_user() ) {
			$settings['query_posts_per_page'] = $settings['query_slides_to_show'] ? $settings['query_slides_to_show'] : -1;
			$settings['query_posts_per_page'] = $settings['query_posts_per_page'] > 4 ? 4 : $settings['query_posts_per_page'];
		}

		if ( 'slider' === $settings['layout_select'] ) {
			$paged = 1;
		}
		
		if ( empty($settings['query_offset']) ) {
			$settings[ 'query_offset' ] = 0;
		}

		$offset = ( $paged - 1 ) * intval($settings['query_posts_per_page']) + intval($settings[ 'query_offset' ]);

		if ( empty($settings['query_posts_per_page']) ) {
			if ( !('slider' === $settings['layout_select'] && Utilities::is_new_free_user()) ) {
				$settings['query_posts_per_page'] = 999;
			}
		}

		if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ) {
			$settings[ 'query_randomize' ] = '';
			$settings['order_posts'] = 'date';
		}

		$query_order_by = '' != $settings['query_randomize'] ? $settings['query_randomize'] : $settings['order_posts'];
		$post__not_in = isset($settings[ 'query_exclude_'. $settings[ 'query_source' ] ]) && !empty($settings[ 'query_exclude_'. $settings[ 'query_source' ] ]) ? $settings[ 'query_exclude_'. $settings[ 'query_source' ] ] : [];

		// Dynamic
		$args = [
			'post_type' => $settings[ 'query_source' ],
			'tax_query' => WPR_Grid_Helpers::get_tax_query_args($settings),
			'post__not_in' => $post__not_in,
			'posts_per_page' => $settings['query_posts_per_page'],
			'orderby' => $query_order_by,
			'author' => $author,
			'paged' => $paged,
			'offset' => $offset
		];

		// if ( isset($_POST['wpr_item_length']) ) {
		// 	$args['posts_per_page'] == $_POST['wpr_item_length'];
		// } check before uncomenting (may conflict)

		if ( $query_order_by == 'meta_value' ) {
			$args['meta_key'] = $settings['order_posts_by_acf'];
		}

		// Display Scheduled Posts
		if ( 'yes' === $settings['display_scheduled_posts'] && (defined('WPR_ADDONS_PRO_VERSION') && wpr_fs()->can_use_premium_code()) ) {
			$args['post_status'] = 'future';
		} else {
			$args['post_status'] = 'publish';
		}

		// Exclude Items without F/Image
		if ( 'yes' === $settings['query_exclude_no_images'] ) {
			$args['meta_key'] = '_thumbnail_id';
		}

		// Manual
		if ( 'manual' === $settings[ 'query_selection' ] ) {
			$post_ids = [''];

			if ( ! empty($settings[ 'query_manual_'. $settings[ 'query_source' ] ]) ) {
				$post_ids = $settings[ 'query_manual_'. $settings[ 'query_source' ] ];
			}

			$args = [
				'post_type' => $settings[ 'query_source' ],
				'post__in' => $post_ids,
				'ignore_sticky_posts' => 1,
				'posts_per_page' => $settings['query_posts_per_page'],
				'orderby' => $query_order_by,
				'paged' => $paged,
			];
		}

		// Current
		if ( 'current' === $settings[ 'query_source' ] ) {
			global $wp_query;

			$tax_query = [];

			$args = $wp_query->query_vars;

			if ( is_post_type_archive() ) {
				$posts_per_page = intval(get_option('wpr_cpt_ppp_'. $args['post_type']), 10);
			} else {
				$posts_per_page = intval(get_option('posts_per_page'));
			}

			if ( isset($settings['current_query_source']) ) {
				$args['post_type'] = $settings['current_query_source'];
				if ( $args['post_type'] != 'post' ) {
					$posts_per_page = intval(get_option('wpr_cpt_ppp_'. $args['post_type']), 10);
					$args['posts_per_page'] = $posts_per_page;
				}
			}

			$args['orderby'] = $query_order_by;

			$args['offset'] = ( $paged - 1 ) * $posts_per_page + intval($settings[ 'query_offset' ]);
			
			if ( isset($_GET['category']) ) {
				
				if ( $_GET['category'] != '0' ) {
					// Get category from URL
					$category = sanitize_text_field($_GET['category']);
				
					array_push( $tax_query, [
						'taxonomy' => 'category',
						'field' => 'id',
						'terms' => $category
					] );
				}
			}
						
			if ( isset($_GET['wpr_select_category']) ) {
				
				if ( $_GET['wpr_select_category'] != '0' ) {
					// Get category from URL
					$category = sanitize_text_field($_GET['wpr_select_category']);
					$taxonomy_name = 'category';
	
	                $term = get_term($category);
	
	                // Check if the term is valid
	                if (!is_wp_error($term)) {
	                    // Get the taxonomy name
	                    $taxonomy_name = $term->taxonomy;
	                }
				
					array_push( $tax_query, [
						'taxonomy' => $taxonomy_name,
						'field' => 'id',
						'terms' => $category
					] );
				}
			}
            // Get category from URL (CHECK BELOW FOR FILTERS)

			if ( !empty($tax_query) ) {
				$args['tax_query'] = $tax_query;
			}
		}

		// Related
		if ( 'related' === $settings[ 'query_source' ] ) {
			$args = [
				'post_type' => get_post_type( get_the_ID() ),
				'tax_query' => WPR_Grid_Helpers::get_tax_query_args($settings),
				'post__not_in' => [ get_the_ID() ],
				'ignore_sticky_posts' => 1,
				'posts_per_page' => $settings['query_posts_per_page'],
				'orderby' => $query_order_by,
				'offset' => $offset,
			];
		}

		if ( 'rand' !== $query_order_by ) {
			$args['order'] = $settings['order_direction'];
		}

		if ( isset($_POST['wpr_offset']) ) { // Check if causes issues with grid itself
			$args['offset'] = $_POST['wpr_offset'];
		}

		if ( !isset($args['tax_query']) ) {
			$args['tax_query'] = [];
		}

		if ( isset($_POST['wpr_taxonomy'] ) ) {
			$settings = $_POST['grid_settings'];
			$taxonomy = $_POST['wpr_taxonomy'];
			$term = $_POST['wpr_filter'];
			$tax_query = [];

			if ( $term != '*' ) {
				if ( 'tag' === $taxonomy ) {
					$taxonomy = 'post_' . $_POST['wpr_taxonomy'];
				}
				array_push( $tax_query, [
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $term
				] );
			}

			if ( !empty($tax_query) ) {
				$args['tax_query'] = $tax_query;
			}

			if ( isset($_POST['wpr_offset']) ) {
				$args['offset'] = $_POST['wpr_offset'];
			}

			return $args;
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
							} else {
								if ( $wpr_url_params[$key] != '0' ) {
									// Get category from URL
									if ( str_contains($wpr_url_params[$key], ',') ) {

										// Example usage
										$key_type = WPR_Grid_Helpers::identify_key_type($cleanedKey);
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
													if ( isset($_POST['wpr_afr_' . $cleanedKey]) && !empty(explode(',', $_POST['wpr_afr_'. $cleanedKey])[0]) ) {
														$meta_relation = explode(',', $_POST['wpr_afr_'. $cleanedKey])[0];
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
										$key_type = WPR_Grid_Helpers::identify_key_type($cleanedKey);
										$filtervalues = sanitize_text_field($wpr_url_params[$key]);
		
										if ( $key_type == 'meta_field' || $key_type == 'custom_field' ) {
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
		$builtin_taxonomies = array('category', 'post_tag'); // Add more if needed
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

	// Taxonomy Query Args
	public static function get_tax_query_args($settings) {
		$settings = $settings;
		$tax_query = [];

		if ( isset($_POST['wpr_taxonomy']) ) {	
			$taxonomy = $_POST['wpr_taxonomy'];
			$term = $_POST['wpr_filter'];
		
			if ( $term != '*' ) {
				if ( 'tag' === $taxonomy ) {
					$taxonomy = 'post_' . $_POST['wpr_taxonomy'];
				}
				array_push( $tax_query, [
					'taxonomy' => $taxonomy,
					'field' => 'slug',
					'terms' => $term
				] );
			}
		}

		if ( 'related' === $settings[ 'query_source' ] ) {
			$tax_query = [
				[
					'taxonomy' => $settings['query_tax_selection'],
					'field' => 'term_id',
					'terms' => wp_get_object_terms( get_the_ID(), $settings['query_tax_selection'], array( 'fields' => 'ids' ) ),
				]
			];
		} else {
			foreach ( get_object_taxonomies($settings[ 'query_source' ]) as $tax ) {
				if ( ! empty($settings[ 'query_taxonomy_'. $tax ]) ) {
					array_push( $tax_query, [
						'taxonomy' => $tax,
						'field' => 'id',
						'terms' => $settings[ 'query_taxonomy_'. $tax ]
					] );
				}
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
	public static function render_post_thumbnail( $settings ) {
		$id = get_post_thumbnail_id();
		
		if ( isset($settings['check_ajax_filter']) && $settings['check_ajax_filter'] == 'yes' ) {
			$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'layout_image_crop', $settings['layout_image_crop'] );
		} else {
			$src = Group_Control_Image_Size::get_attachment_image_src( $id, 'layout_image_crop', $settings );
		}
		
		if ( get_post_meta(get_the_ID(), 'wpr_secondary_image_id') && !empty(get_post_meta(get_the_ID(), 'wpr_secondary_image_id')) ) {
			if ( isset($settings['check_ajax_filter']) && $settings['check_ajax_filter'] == 'yes' ) {
				$src2 = Group_Control_Image_Size::get_attachment_image_src( get_post_meta(get_the_ID(), 'wpr_secondary_image_id')[0], 'layout_image_crop', $settings['layout_image_crop'] );
			} else {
				$src2 = Group_Control_Image_Size::get_attachment_image_src( get_post_meta(get_the_ID(), 'wpr_secondary_image_id')[0], 'layout_image_crop', $settings );
			}
		} else {
			$src2 = '';
		}

		if ( !empty( get_post_meta( $id, '_wp_attachment_image_alt', true ) ) ) {
			$alt = get_post_meta( $id, '_wp_attachment_image_alt', true );
		} else {
			$alt = '' === wp_get_attachment_caption( $id ) ? get_the_title() : wp_get_attachment_caption( $id );
		}

		if ( has_post_thumbnail() ) {
			echo '<div class="wpr-grid-image-wrap" data-src="'. esc_url( $src ) .'" data-img-on-hover="'. esc_attr( $settings['secondary_img_on_hover'] ) .'"  data-src-secondary="'. esc_url( $src2 ) .'">';
				if ( 'yes' == $settings['grid_lazy_loading'] ) {
					echo '<img data-no-lazy="1" src="'. WPR_ADDONS_ASSETS_URL . 'img/icon-256x256.png" alt="'. esc_attr( $alt ) .'" class="wpr-hidden-image wpr-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
					if ( 'yes' == $settings['secondary_img_on_hover'] ) {
						echo '<img data-no-lazy="1" src="'. esc_url( $src2 ) . '" alt="'. esc_attr( $alt ) .'" class="wpr-hidden-img wpr-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
					}
				} else {
					echo '<img data-no-lazy="1" src="'. esc_url( $src ) . '" alt="'. esc_attr( $alt ) .'" class="wpr-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
					if ( 'yes' == $settings['secondary_img_on_hover'] ) {
						echo '<img data-no-lazy="1" src="'. esc_url( $src2 ) . '" alt="'. esc_attr( $alt ) .'" class="wpr-hidden-img wpr-anim-timing-'. esc_attr($settings[ 'image_effects_animation_timing']) .'">';
					}
				}
			echo '</div>';
		}
	}

	// Render Media Overlay
	public static function render_media_overlay( $settings ) {
		echo '<div class="wpr-grid-media-hover-bg '. esc_attr(WPR_Grid_Helpers::get_animation_class( $settings, 'overlay' )) .'" data-url="'. esc_attr( get_the_permalink( get_the_ID() ) ) .'">'; // changed esc_url to esc_attr (why?)

			if ( defined('WPR_ADDONS_PRO_VERSION') && wpr_fs()->can_use_premium_code() ) {
				if ( '' !== $settings['overlay_image']['url'] ) {
					echo '<img data-no-lazy="1" src="'. esc_url( $settings['overlay_image']['url'] ) .'">';
				}
			}

		echo '</div>';
	}

	// Render Post Title
	public static function render_post_title( $settings, $class, $general_settings = '' ) {
		$title_pointer = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'none' : $general_settings['title_pointer'];
		$title_pointer_animation = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'fade' : $general_settings['title_pointer_animation'];
		$pointer_item_class = (isset($general_settings['title_pointer']) && 'none' !==$general_settings['title_pointer']) ? 'class="wpr-pointer-item"' : '';
		$open_links_in_new_tab = 'yes' === $general_settings['open_links_in_new_tab'] ? '_blank' : '_self';

		$class .= ' wpr-pointer-'. $title_pointer;
		$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $title_pointer_animation;

		$tags_whitelist = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p'];
		$element_title_tag = Utilities::validate_html_tags_wl( $settings['element_title_tag'], 'h2', $tags_whitelist );

		echo '<'. esc_attr($element_title_tag) .' class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<a target="'. $open_links_in_new_tab .'" '. $pointer_item_class .' href="'. esc_url( get_the_permalink() ) .'">';
					if ( 'word_count' === $settings['element_trim_text_by'] ) {
						echo esc_html(wp_trim_words( get_the_title(), $settings['element_word_count'] ));
					} else {
						echo substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...';
					}
				echo '</a>';
			echo '</div>';
		echo '</'. esc_attr($element_title_tag) .'>';
	}

	// Render Post Content
	public static function render_post_content( $settings, $class ) {
		$dropcap_class = 'yes' === $settings['element_dropcap'] ? ' wpr-enable-dropcap' : '';
		$class .= $dropcap_class;

		if ( '' === get_the_content() ) {
			return;
		}

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo wp_kses_post(get_the_content());
			echo '</div>';
		echo '</div>';
	}

	// Render Post Excerpt
	public static function render_post_excerpt( $settings, $class ) {
		$dropcap_class = 'yes' === $settings['element_dropcap'] ? ' wpr-enable-dropcap' : '';
		$class .= $dropcap_class;

		if ( '' === get_the_excerpt() ) {
			return;
		}

		$excerpt = get_the_excerpt();

		// Convert HTML entities to their respective characters
		$decoded_excerpt = html_entity_decode($excerpt, ENT_QUOTES | ENT_HTML5, 'UTF-8');

		// Trim the string to the desired length
		$trimmed_excerpt = mb_substr($decoded_excerpt, 0, $settings['element_letter_count'], 'UTF-8');

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				if ( 'word_count' === $settings['element_trim_text_by'] ) {
					$show_dots = $settings['element_show_dots'] === 'yes' ? '...' : '';
					$the_excerpt = str_replace('Edit Template', '', get_the_excerpt());
					echo '<p>'. esc_html(wp_trim_words( $the_excerpt, $settings['element_word_count'], $show_dots )) .'</p>';
				} else {
					// echo '<p>'. substr(html_entity_decode(get_the_title()), 0, $settings['element_letter_count']) .'...' . '</p>';
					// echo '<p>'. esc_html(implode('', array_slice( str_split(get_the_excerpt()), 0, $settings['element_letter_count'] ))) .'...' .'</p>';	
					echo '<p>' . esc_html($trimmed_excerpt) . '...' . '</p>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Date
	public static function render_post_date( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
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

				// Date
				if ( 'yes' === $settings['show_last_update_date'] ) {
					echo esc_html(get_the_modified_time(get_option( 'date_format' )));
				} else {
					echo esc_html(apply_filters( 'the_date', get_the_date( '' ), get_option( 'date_format' ), '', '' ));
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
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Time
	public static function render_post_time( $settings, $class ) {
		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				echo '<span>';
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

				// Time
				echo esc_html(get_the_time(''));

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
				echo '</span>';
			echo '</div>';
		echo '</div>';
	}

	// Render Post Author
	public static function render_post_author( $settings, $class ) {
		$author_id =  get_post_field( 'post_author' );

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				// Author
				echo '<a href="'. esc_url( get_author_posts_url( $author_id ) ) .'">';

				// Icon: Before
				if ( 'before' === $settings['element_extra_icon_pos'] ) {
					ob_start();
					\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
					$extra_icon = ob_get_clean();

					echo '<span class="wpr-grid-extra-icon-left">';
						echo $extra_icon;
					echo '</span>';
				}
					if ( 'yes' === $settings['element_show_avatar'] ) {
						echo get_avatar( $author_id, $settings['element_avatar_size'] );
					}

					echo '<span>'. esc_html(get_the_author_meta( 'display_name', $author_id )) .'</span>';

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

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}

	// Render Post Comments
	public static function render_post_comments( $settings, $class ) {
		$count = get_comments_number();

		if ( comments_open() ) {
			if ( $count == 1 ) {
				$text = $count .'&nbsp;'. $settings['element_comments_text_2'];
			} elseif ( $count > 1 ) {
				$text = $count .'&nbsp;'. $settings['element_comments_text_3'];
			} else {
				$text = $settings['element_comments_text_1'];
			}

			echo '<div class="'. esc_attr($class) .'">';
				echo '<div class="inner-block">';
					// Text: Before
					if ( 'before' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-grid-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}

					// Comments
					echo '<a href="'. esc_url( get_comments_link() ) .'">';

					// Icon: Before
					if ( 'before' === $settings['element_extra_icon_pos'] ) {
						ob_start();
						\Elementor\Icons_Manager::render_icon($settings['element_extra_icon'], ['aria-hidden' => 'true']);
						$extra_icon = ob_get_clean();
		
						echo '<span class="wpr-grid-extra-icon-left">';
							echo $extra_icon;
						echo '</span>';
					}

					echo '<span>'. esc_html($text) .'</span>';

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

					// Text: After
					if ( 'after' === $settings['element_extra_text_pos'] ) {
						echo '<span class="wpr-grid-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
					}
				echo '</div>';
			echo '</div>';
		}
	}

	// Render Post Read More
	public static function render_post_read_more( $settings, $class, $general_settings ) {
		$read_more_animation = !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ? 'wpr-button-none' : $general_settings['read_more_animation'];
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

	// Render Post Likes (Pro)
	public static function render_post_likes( $settings, $class, $post_id ) {
		
		if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ) {
			return;
		}

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

	// Render Post Sharing Icons (Pro)
	public static function render_post_sharing_icons( $settings, $class ) {

		if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ) {
			return;
		}

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
	public static function render_post_lightbox( $settings, $class, $post_id ) {
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

	public static function render_post_custom_field( $settings, $class, $post_id ) {

		if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ) {
			return;
		}

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

	// Render Post Element Separator
	public static function render_post_element_separator( $settings, $class ) {
		echo '<div class="'. esc_attr($class .' '. $settings['element_separator_style']) .'">';
			echo '<div class="inner-block"><span></span></div>';
		echo '</div>';
	}

	// Render Post Taxonomies
	public static function render_post_taxonomies( $settings, $class, $post_id, $general_settings ) {
		$terms = wp_get_post_terms( $post_id, $settings['element_select'] );
		$count = 0;

		$tax1_pointer = ! wpr_fs()->can_use_premium_code() ? 'none' : $general_settings['tax1_pointer'];
		$tax1_pointer_animation = ! wpr_fs()->can_use_premium_code() ? 'fade' : $general_settings['tax1_pointer_animation'];
		$tax2_pointer = ! wpr_fs()->can_use_premium_code() ? 'none' : $general_settings['tax2_pointer'];
		$tax2_pointer_animation = ! wpr_fs()->can_use_premium_code() ? 'fade' : $general_settings['tax2_pointer_animation'];
		$pointer_item_class = (isset($general_settings['tax1_pointer']) && 'none' !== $general_settings['tax1_pointer']) || (isset($general_settings['tax2_pointer']) && 'none' !== $general_settings['tax2_pointer']) ? 'wpr-pointer-item' : '';

		// Pointer Class
		if ( 'wpr-grid-tax-style-1' === $settings['element_tax_style'] ) {
			$class .= ' wpr-pointer-'. $tax1_pointer;
			$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $tax1_pointer_animation;
		} else {
			$class .= ' wpr-pointer-'. $tax2_pointer;
			$class .= ' wpr-pointer-line-fx wpr-pointer-fx-'. $tax2_pointer_animation;
		}

		echo '<div class="'. esc_attr($class .' '. $settings['element_tax_style']) .'">';
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

					// Custom Colors
					$enable_custom_colors = ! wpr_fs()->can_use_premium_code() ? '' : $general_settings['tax1_custom_color_switcher'];
					
					if ( 'yes' === $enable_custom_colors ) {
						$custom_tax_styles = '';
						$cfc_text = get_term_meta($term->term_id, $general_settings['tax1_custom_color_field_text'], true);
						$cfc_bg = get_term_meta($term->term_id, $general_settings['tax1_custom_color_field_bg'], true);
						$color_styles = 'color:'. $cfc_text .'; background-color:'. $cfc_bg .'; border-color:'. $cfc_bg .';';
						// $css_selector = '.elementor-element'. $this->get_unique_selector() .' .wpr-grid-tax-style-1 .inner-block a.wpr-tax-id-'. esc_attr($term->term_id);
						$css_selector = '.elementor-element .wpr-grid-tax-style-1 .inner-block a.wpr-tax-id-'. esc_attr($term->term_id); // TODO: get_unique_selector()
						$custom_tax_styles .= $css_selector .'{'. $color_styles .'}';
						echo '<style>'. esc_html($custom_tax_styles) .'</style>'; // TODO: take out of loop if possible
					}

					echo '<a class="'. $pointer_item_class .' wpr-tax-id-'. esc_attr($term->term_id) .'" href="'. esc_url(get_term_link( $term->term_id )) .'">'. esc_html( $term->name );
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

	// Get Elements
	public static function get_elements( $type, $settings, $class, $post_id, $general_settings ) {
		if ( 'pro-lk' == $type || 'pro-shr' == $type || 'pro-cf' == $type ) {
			$type = 'title';
		}

		switch ( $type ) {
			case 'title':
				WPR_Grid_Helpers::render_post_title( $settings, $class, $general_settings );
				break;

			case 'content':
				WPR_Grid_Helpers::render_post_content( $settings, $class );
				break;

			case 'excerpt':
				WPR_Grid_Helpers::render_post_excerpt( $settings, $class, $general_settings );
				break;

			case 'date':
				WPR_Grid_Helpers::render_post_date( $settings, $class );
				break;

			case 'time':
				WPR_Grid_Helpers::render_post_time( $settings, $class );
				break;

			case 'author':
				WPR_Grid_Helpers::render_post_author( $settings, $class );
				break;

			case 'comments':
				WPR_Grid_Helpers::render_post_comments( $settings, $class );
				break;

			case 'read-more':
				WPR_Grid_Helpers::render_post_read_more( $settings, $class, $general_settings );
				break;

			case 'likes':
				WPR_Grid_Helpers::render_post_likes( $settings, $class, $post_id );
				break;

			case 'sharing':
				WPR_Grid_Helpers::render_post_sharing_icons( $settings, $class );
				break;

			case 'lightbox':
				WPR_Grid_Helpers::render_post_lightbox( $settings, $class, $post_id );
				break;

			case 'custom-field':
				WPR_Grid_Helpers::render_post_custom_field( $settings, $class, $post_id );
				break;

			case 'separator':
				WPR_Grid_Helpers::render_post_element_separator( $settings, $class );
				break;
			
			default:
				WPR_Grid_Helpers::render_post_taxonomies( $settings, $class, $post_id, $general_settings );
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
							WPR_Grid_Helpers::get_elements( $data['element_select'], $data, $class, $post_id, $settings );
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
						WPR_Grid_Helpers::get_elements( $data['element_select'], $data, $class, $post_id, $settings );
					}
				echo '</div>';
			}

		}
	}

	public static function get_hidden_filter_class($slug, $settings) {
		$posts = new \WP_Query( WPR_Grid_Helpers::get_main_query_args($settings, []) );
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

		if ( 'yes' !== $settings['advanced_filters'] && 1 === WPR_Grid_Helpers::get_max_num_pages( $settings ) ) {
			return;
		}

		global $paged;
		$pages = WPR_Grid_Helpers::get_max_num_pages( $settings );
		
		// $paged = empty( $paged ) ? 1 : $paged;
		if ( get_query_var('paged') ) {
			$paged = get_query_var('paged');
		} elseif ( get_query_var('page') ) {
			$paged = get_query_var('page');
		} else {
			$paged = 1;
		}

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
			echo '<a href="'. esc_url(get_pagenum_link( $paged + 1, true )) .'" class="wpr-load-more-btn" data-e-disable-page-transition >';
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

	public function wpr_get_filtered_count_posts() {
		$nonce = $_POST['nonce'];

		if (!isset($nonce) || !wp_verify_nonce($nonce, 'wpr-addons-js')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

		if ( ! empty( $_POST['grid_settings'] ) && ! WPR_Grid_Helpers::is_allowed_grid_query_post_type( $_POST['grid_settings'] ) ) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

		if ( isset($_POST['wpr_url_params']) ) {
			$results = [];
		
			// Loop through each set of parameters
			foreach ($_POST['wpr_url_params'] as $params) {
				$query_args = WPR_Grid_Helpers::get_main_query_args($_POST['grid_settings'], $params);
				$query = new \WP_Query($query_args);
		
				// Add the count of found posts to the results array
				$results[] = [
					'found_posts' => $query->found_posts,
					'post_count' => $query->post_count,
				];
		
				wp_reset_postdata();
			}
		
			// Send the array of results
			wp_send_json_success($results);
		} else if ( isset($_POST['grid_settings']) ) {
			$settings = $_POST['grid_settings'];
			$page_count =  WPR_Grid_Helpers::get_max_num_pages( $settings );
		
			wp_send_json_success([
				'page_count' => $page_count,
			]);
		}
		
		wp_die();
	}

	public function wpr_grid_filters_ajax() {
		$nonce = $_POST['nonce'];

		if (!isset($nonce) || !wp_verify_nonce($nonce, 'wpr-addons-js')) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

		$settings = isset( $_POST['grid_settings'] ) && is_array( $_POST['grid_settings'] ) ? $_POST['grid_settings'] : array();
		if ( ! WPR_Grid_Helpers::is_allowed_grid_query_post_type( $settings ) ) {
			wp_send_json_error(array(
				'message' => esc_html__('Security check failed.', 'wpr-addons'),
			));
		}

		$start = microtime(true);
		// Get Settings
	
		// Create a unique cache key based on the settings
		$cache_key = 'wpr_grid_filters_' . md5(serialize(WPR_Grid_Helpers::get_main_query_args($settings, [])));
		// wp_send_json_success($cache_key);
		// wp_die();
	
		// Try to get cached data
		// $cached_data = get_transient($cache_key);
		
		// if ($cached_data !== false) {
		// 	$end = microtime(true);
		// 	$duration = round(($end - $start) * 1000, 2); // in ms
		// 	wp_send_json_success([
		// 		'output' => $cached_data,
		// 		'duration' => $duration
		// 	]);
		// 	wp_die();
		// }
	
		// Start output buffering to capture the HTML output
		ob_start();
	
		// Get Posts
		$posts = new \WP_Query(WPR_Grid_Helpers::get_main_query_args($settings, []));
	
		// Loop: Start
		if ($posts->have_posts()) :
	
			while ($posts->have_posts()) : $posts->the_post();
	
				// Post Class
				$post_class = implode(' ', get_post_class('wpr-grid-item elementor-clearfix', get_the_ID()));
	
				// Grid Item
				echo '<article class="' . esc_attr($post_class) . '">';
	
				// Password Protected Form
				WPR_Grid_Helpers::render_password_protected_input($settings);
	
				// Inner Wrapper
				echo '<div class="wpr-grid-item-inner">';
	
				// Content: Above Media
				WPR_Grid_Helpers::get_elements_by_location('above', $settings, get_the_ID());
	
				// Media
				if (has_post_thumbnail()) {
					echo '<div class="wpr-grid-media-wrap' . esc_attr(WPR_Grid_Helpers::get_image_effect_class($settings)) . '" data-overlay-link="' . esc_attr($settings['overlay_post_link']) . '">';
					// Post Thumbnail
					WPR_Grid_Helpers::render_post_thumbnail($settings, get_the_ID());
	
					// Media Hover
					echo '<div class="wpr-grid-media-hover wpr-animation-wrap">';
					// Media Overlay
					WPR_Grid_Helpers::render_media_overlay($settings);
	
					// Content: Over Media
					WPR_Grid_Helpers::get_elements_by_location('over', $settings, get_the_ID());
	
					echo '</div>';
					echo '</div>';
				}
	
				// Content: Below Media
				WPR_Grid_Helpers::get_elements_by_location('below', $settings, get_the_ID());
	
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
	
		// Cache the output
		// set_transient($cache_key, $output, HOUR_IN_SECONDS);
	
		// Return the output
		$end = microtime(true);
		$duration = round(($end - $start) * 1000, 2); // in ms
		wp_send_json_success([
			'output' => $output,
			'duration' => $duration,
			'found_posts' => $posts->found_posts,
			'post_count' => $posts->post_count,
		]);

		wp_die();
	}

}

new WPR_Grid_Helpers();