<?php
namespace WprAddonsPro\Classes;

use WprAddons\Classes\Utilities;
use \WprAddons\Admin\Includes\WPR_Templates_Modal_Popups;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Pro_Modules {

	/**
	** Get Available Modules
	*/
	public static function get_registered_modules() {
		$pro_widgets = [];

		if ( wpr_fs()->can_use_premium_code() && defined('WPR_ADDONS_PRO_VERSION') ) {
			$pro_widgets = [
				'Nav Menu Pro' => 'nav-menu-pro',
				'Mega Menu Pro' => 'mega-menu-pro',
				'Advanced Slider Pro' => 'advanced-slider-pro',
				'Data Table Pro' => 'data-table-pro',
				'Advanced Text Pro' => 'advanced-text-pro',
				'Before After Pro' => 'before-after-pro',
				'Business Hours Pro' => 'business-hours-pro',
				'Button Pro' => 'button-pro',
				'Dual Button Pro' => 'dual-button-pro',
				'Content Ticker Pro' => 'content-ticker-pro',
				'Content Toggle Pro' => 'content-toggle-pro',
				'Countdown Pro' => 'countdown-pro',
				'Flip Box Pro' => 'flip-box-pro',
				'Image Hotspots Pro' => 'image-hotspots-pro',
				'Mailchimp Pro' => 'mailchimp-pro',
				'Onepage Nav Pro' => 'onepage-nav-pro',
				'Price List Pro' => 'price-list-pro',
				'Progress Bar Pro' => 'progress-bar-pro',
				'Promo Box Pro' => 'promo-box-pro',
				'Search Pro' => 'search-pro',
				'Sharing Buttons Pro' => 'sharing-buttons-pro',
				'Tabs Pro' => 'tabs-pro',
				'Team Member Pro' => 'team-member-pro',
				'Testimonial Carousel Pro' => 'testimonial-pro',
				'Pricing Table Pro' => 'pricing-table-pro',
				'Grid Pro' => 'grid-pro',
				'Image Grid Pro' => 'media-grid-pro',
				'Woo Grid Pro' => 'woo-grid-pro',
				'Magazine Grid Pro' => 'magazine-grid-pro',
				'Popup Trigger Pro' => 'popup-trigger-pro',
				'Posts Timeline Pro' => 'posts-timeline-pro',
				'Taxonomy List Pro' => 'taxonomy-list-pro',
				'Flip Carousel Pro' => 'flip-carousel-pro',
				'Image Accordion Pro' => 'image-accordion-pro',
				'Advanced Accordion Pro' => 'advanced-accordion-pro',
				'Charts Pro' => 'charts-pro',
				'Page List Pro' => 'page-list-pro',
				'Instagram Feed Pro' => 'instagram-feed-pro',
				'Twitter Feed Pro' => 'twitter-feed-pro',
				'Offcanvas Pro' => 'offcanvas-pro',
				'Form Builder Pro' => 'form-builder-pro',
				'Breadcrumbs Pro' => 'breadcrumbs-pro',
			];

			if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
				$pro_widgets = array_merge( $pro_widgets, [
					'Category Grid Pro' => 'category-grid-pro',
					'Advanced Filters Pro' => 'advanced-filters-pro',
				] );
			}
		}

		return $pro_widgets;
	}

	/**
	** Get Enabled Modules
	*/
	public static function get_available_modules( $modules ) {
		foreach ( $modules as $title => $slug ) {
			$slug = str_replace('-pro', '', $slug);
			if ( 'on' !== get_option('wpr-element-'. $slug, 'on') ) {
				unset($modules[$title]);
			}
		}

		return $modules;
	}

	/**
	** Get Theme Builder Modules
	*/
	public static function get_theme_builder_modules() {
		$pro_theme_builder_modules = [
			'Post Info Pro' => 'post-info-pro',
			'Post Navigation Pro' => 'post-navigation-pro',
			'Author Box Pro' => 'author-box-pro',
			'Post Comments Pro' => 'post-comments-pro',
			'Archive Title Pro' => 'archive-title-pro',
		];

		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
			$pro_theme_builder_modules = array_merge( $pro_theme_builder_modules, [
				'Custom Field Pro' => 'custom-field-pro'
			] );
		}

		return $pro_theme_builder_modules;
	}

	/**
	** Get WooCommerce Builder Modules
	*/
	public static function get_woocommerce_builder_modules() {
		$pro_modules = [
			'Product Media Pro' => 'product-media-pro',
			'Product Mini Cart Pro' => 'product-mini-cart-pro',
			'Product Filters Pro' => 'product-filters-pro',
			'Page My Account Pro' => 'page-my-account-pro',
			'Woo Category Grid Pro' => 'woo-category-grid-pro',
			'Product Breadcrumbs Pro' => 'product-breadcrumbs-pro',
			'Product Tabs Pro' => 'product-tabs-pro',
		];

		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
			$pro_modules = array_merge( $pro_modules, [
				'Wishlist Button Pro' => 'wishlist-button-pro',
				'Mini Wishlist Pro' => 'mini-wishlist-pro',
				'Wishlist Pro' => 'wishlist-pro',
				'Compare Button Pro' => 'compare-button-pro',
				'Mini Compare Pro' => 'mini-compare-pro',
				'Compare Pro' => 'compare-pro'
			] );
		}

		return $pro_modules;
	}

    /**
    ** Archive Pages Templates Conditions
    */
    public static function archive_templates_conditions( $conditions ) {
    	$term_id = '';
    	$term_name = '';
    	$queried_object = get_queried_object();

    	// Get Terms
    	if ( ! is_null( $queried_object ) ) {
    		if ( isset( $queried_object->term_id ) && isset( $queried_object->taxonomy ) ) {
		        $term_id   = $queried_object->term_id;
		        $term_name = $queried_object->taxonomy;
    		}
    	}

        // Reset
        $template = NULL;

		// Archive Pages (includes search)
		if ( is_archive() || is_search() ) {
			if ( ! is_search() ) {
				// Author
				if ( is_author() ) {
	    			$template = Utilities::get_template_slug( $conditions, 'archive/author' );
				// Date
				} elseif ( is_date() ) {
	    			$template = Utilities::get_template_slug( $conditions, 'archive/date' );
				// Category
				} elseif ( is_category() ) {
					$template = Utilities::get_template_slug( $conditions, 'archive/categories', $term_id );
				// Tag
				} elseif ( is_tag() ) {
					$template = Utilities::get_template_slug( $conditions, 'archive/tags', $term_id );
				// Products
				} elseif ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
					if ( isset($conditions['caller-header-footer']) ) {
						$template = Utilities::get_template_slug( $conditions, 'archive/product' );
	
						// Product Categories
						if ( is_product_category() && null !== Utilities::get_template_slug( $conditions, 'product_archive/product_cat', $term_id ) ) {
							$template = Utilities::get_template_slug( $conditions, 'archive/product_cat', $term_id );
						}
						
						// Product Tags
						if ( is_product_tag() && null !== Utilities::get_template_slug( $conditions, 'product_archive/product_tag', $term_id ) ) {
							$template = Utilities::get_template_slug( $conditions, 'archive/product_tag', $term_id );
						}
					} else {
						$template = Utilities::get_template_slug( $conditions, 'product_archive/products' );
	
						// Product Categories
						if ( is_product_category() && null !== Utilities::get_template_slug( $conditions, 'product_archive/product_cat', $term_id ) ) {
							$template = Utilities::get_template_slug( $conditions, 'product_archive/product_cat', $term_id );
						}
						
						// Product Tags
						if ( is_product_tag() && null !== Utilities::get_template_slug( $conditions, 'product_archive/product_tag', $term_id ) ) {
							$template = Utilities::get_template_slug( $conditions, 'product_archive/product_tag', $term_id );
						}
					}
                
				// Custom Post Types
				} elseif ( is_post_type_archive() ) {
					$template = Utilities::get_template_slug( $conditions, 'archive/'. get_post_type() );

				// Custom Taxonomies
				} elseif ( is_tax() ) {
					$template = Utilities::get_template_slug( $conditions, 'archive/'. $term_name, $term_id );
				}

			// Search Page
			} else {
				if ( is_post_type_archive('product') ) {
					$template = Utilities::get_template_slug( $conditions, 'product_archive/product_search' );
				} else {
					$template = Utilities::get_template_slug( $conditions, 'archive/search' );
				}
	        }

	    // Posts Page
		} elseif ( Utilities::is_blog_archive() ) {
			$template = Utilities::get_template_slug( $conditions, 'archive/posts' );
		}

		// Global - For All Archives
		if ( is_null($template) ) {
	        $all_archives = Utilities::get_template_slug( $conditions, 'archive/all_archives' );

	    	if ( ! is_null($all_archives) ) {
                if ( class_exists( 'WooCommerce' ) && is_shop() ) {
                    $template = null;
                } else {
                    if ( is_archive() || is_search() || Utilities::is_blog_archive() ) {
                        $template = $all_archives;
                    }
                }
	    	}
		}

	    return $template;
    }

    /**
    ** Single Pages Templates Conditions
    */
    public static function single_templates_conditions( $conditions ) {
        global $post;

        // Get Posts
        $post_id   = is_null($post) ? '' : $post->ID;
        $post_type = is_null($post) ? '' : $post->post_type;

        // Reset
        $template = NULL;

		// Single Pages
		if ( is_single() || is_front_page() || is_page() || is_404() ) {

			if ( is_single() ) {

				// Blog Posts
				if ( 'post' == $post_type ) {
	    			$template = Utilities::get_template_slug( $conditions, 'single/posts', $post_id );
				// Product
				} elseif ( 'product' == $post_type ) {
					if ( isset($conditions['caller-header-footer']) ) {
						$template = Utilities::get_template_slug( $conditions, 'single/product', $post_id );
					} else {
						$template = Utilities::get_template_slug( $conditions, 'product_single/product', $post_id );
					}
				// CPT
				} else {
	    			$template = Utilities::get_template_slug( $conditions, 'single/'. $post_type, $post_id );
		        }
			} else {
				// Front page
				if ( is_front_page() && ! Utilities::is_blog_archive() ) {//TODO: is it a good check? - is_blog_archive()
	    			$template = Utilities::get_template_slug( $conditions, 'single/front_page' );
				// Error 404 Page
				} elseif ( is_404() ) {
	    			$template = Utilities::get_template_slug( $conditions, 'single/page_404' );
				// Single Page
				} elseif ( is_page() ) {
					if ( isset( $conditions['single/pages/all'] ) ) {
						$template = Utilities::get_template_slug( $conditions, 'single/pages/all' );
					} else {
						$template = Utilities::get_template_slug( $conditions, 'single/pages', $post_id );
					}
		        }
			}

        }

	    return $template;
    }


    /**
    ** Archive Pages Popup Conditions
    */
    public static function archive_pages_popup_conditions( $conditions ) {
    	$term_id = '';
    	$term_name = '';
    	$queried_object = get_queried_object();

    	// Get Terms
    	if ( ! is_null( $queried_object ) ) {
    		if ( isset( $queried_object->term_id ) && isset( $queried_object->taxonomy ) ) {
		        $term_id   = $queried_object->term_id;
		        $term_name = $queried_object->taxonomy;
    		}
    	}

		// Archive Pages (includes search)
		if ( is_archive() || is_search() ) {
			if ( is_archive() && ! is_search() ) {
				if ( isset( $conditions['archive/all_archives'] ) ) {
					WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/all_archives' );
				}

				// Author
				if ( is_author() ) {
					if ( isset( $conditions['archive/author'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/author' );
					}
				}

				// Date
				if ( is_date() ) {
					if ( isset( $conditions['archive/date'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/date' );
					}
				}

				// Category
				if ( is_category() ) {
					if ( isset( $conditions['archive/categories/'. $term_id] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/categories/'. $term_id );
					}

					if ( isset( $conditions['archive/categories/all'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/categories/all' );
					}
				}

				// Tag
				if ( is_tag() ) {
					if ( isset( $conditions['archive/tags/'. $term_id] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/tags/'. $term_id );
					}

					if ( isset( $conditions['archive/tags/all'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/tags/all' );
					}
				}

				// Custom Taxonomies
				if ( is_tax() ) {
					if ( isset( $conditions['archive/'. $term_name .'/'. $term_id] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/'. $term_name .'/'. $term_id );
					}

					if ( isset( $conditions['archive/'. $term_name .'/all'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/'. $term_name .'/all' );
					}
				}

				// Products
				if ( class_exists( 'WooCommerce' ) && is_shop() ) {
					if ( isset( $conditions['archive/product'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/product' );
					}
		        }

			// Search Page
			} else {
				if ( isset( $conditions['archive/search'] ) ) {
    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/search' );
				}
	        }

	    // Posts Page
		} elseif ( Utilities::is_blog_archive() ) {
			if ( isset( $conditions['archive/posts'] ) ) {
				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'archive/posts' );
			}
		}
    }

    /**
    ** Single Pages Popup Conditions
    */
    public static function single_pages_popup_conditions( $conditions ) {
        global $post;

        // Get Posts
        $post_id   = is_null($post) ? '' : $post->ID;
        $post_type = is_null($post) ? '' : $post->post_type;

		// Single Pages
		if ( is_single() || is_front_page() || is_page() || is_404() ) {

			if ( is_single() ) {
				// Blog Posts
				if ( 'post' == $post_type ) {
					if ( isset( $conditions['single/posts/'. $post_id] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'single/posts/'. $post_id );
					} else {
						// Step 1: Loop through all keys in the array
						foreach ($conditions as $key => $value) {
							// Convert key into an array of values by splitting on ","
							$values = array_map('trim', explode(',', $key));

							// Step 2: Check if "single/pages/{post_id}" exists
							if (in_array("single/posts/$post_id", $values)) {
								WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, $key );
							}

							// Step 3: Check for any standalone `{post_id}` that matches
							if (in_array($post_id, $values)) {
								WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, $key );
							}
						}
					}

					if ( isset( $conditions['single/posts/all'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'single/posts/all' );
					}

				// CPT
		        } else {
					if ( isset( $conditions['single/'. $post_type .'/'. $post_id] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'single/'. $post_type .'/'. $post_id );
					} else {
						// Step 1: Loop through all keys in the array
						foreach ($conditions as $key => $value) {
							// Convert key into an array of values by splitting on ","
							$values = array_map('trim', explode(',', $key));

							// Step 2: Check if "single/pages/{post_id}" exists
							if (in_array("single/'. $post_type .'/$post_id", $values)) {
								WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, $key );
							}

							// Step 3: Check for any standalone `{post_id}` that matches
							if (in_array($post_id, $values)) {
								WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, $key );
							}
						}
					}

					if ( isset( $conditions['single/'. $post_type .'/all'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'single/'. $post_type .'/all' );
					}
		        }
			} else {
				// Front page
				if ( is_front_page() ) {
					if ( isset( $conditions['single/front_page'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'single/front_page' );
					}
				// Error 404 Page
				} elseif ( is_404() ) {
					if ( isset( $conditions['single/page_404'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'single/page_404' );
					}
				// Single Page
				} elseif ( is_page() ) {
					if ( isset( $conditions['single/pages/'. $post_id] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'single/pages/'. $post_id );
					} else {
						// Step 1: Loop through all keys in the array
						foreach ($conditions as $key => $value) {
							// Convert key into an array of values by splitting on ","
							$values = array_map('trim', explode(',', $key));

							// Step 2: Check if "single/pages/{post_id}" exists
							if (in_array("single/pages/$post_id", $values)) {
								WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, $key );
							}

							// Step 3: Check for any standalone `{post_id}` that matches
							if (in_array($post_id, $values)) {
								WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, $key );
							}
						}
					}

					if ( isset( $conditions['single/pages/all'] ) ) {
	    				WPR_Templates_Modal_Popups::display_popups_by_location( $conditions, 'single/pages/all' );
					}
		        }
			}

        }
    }

}