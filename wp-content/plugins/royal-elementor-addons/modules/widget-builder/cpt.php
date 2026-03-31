<?php
namespace WprAddons\Modules\WidgetBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Cpt {

	public function __construct() {
		add_action( 'init', [ $this, 'register_post_type' ] );
		add_action( 'admin_menu', [ $this, 'add_submenu' ] );
		add_action( 'admin_head', [ $this, 'badge_style' ] );
	}

	public function register_post_type() {
		$labels = [
			'name'               => esc_html__( 'Custom Widgets', 'wpr-addons' ),
			'singular_name'      => esc_html__( 'Custom Widget', 'wpr-addons' ),
			'menu_name'          => esc_html__( 'Widget Builder', 'wpr-addons' ),
			'name_admin_bar'     => esc_html__( 'Custom Widget', 'wpr-addons' ),
			'add_new'            => esc_html__( 'Add New', 'wpr-addons' ),
			'add_new_item'       => esc_html__( 'Add New Widget', 'wpr-addons' ),
			'new_item'           => esc_html__( 'New Widget', 'wpr-addons' ),
			'edit_item'          => esc_html__( 'Edit Widget', 'wpr-addons' ),
			'view_item'          => esc_html__( 'View Widget', 'wpr-addons' ),
			'all_items'          => esc_html__( 'All Widgets', 'wpr-addons' ),
			'search_items'       => esc_html__( 'Search Widgets', 'wpr-addons' ),
			'parent_item_colon'  => esc_html__( 'Parent Widgets:', 'wpr-addons' ),
			'not_found'          => esc_html__( 'No widgets found.', 'wpr-addons' ),
			'not_found_in_trash' => esc_html__( 'No widgets found in Trash.', 'wpr-addons' ),
		];

		$args = [
			'labels'              => $labels,
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'page',
			'hierarchical'        => false,
			'supports'            => [ 'title' ],
			'rewrite'             => false,
		];

		register_post_type( 'wpr_custom_widget', $args );
	}

	public function badge_style() {
		echo '<style>.wpr-new-badge{padding:0 4px 2px;margin-left:2px;border-radius:2px;background:#00a32a;color:#fff;font-size:8px;font-weight:700;line-height:14px;letter-spacing:.3px;vertical-align:middle;position:relative;top:-2px;}</style>';
	}

	public function add_submenu() {
		add_submenu_page(
			'wpr-addons',
			esc_html__( 'Widget Builder', 'wpr-addons' ),
			esc_html__( 'Widget Builder', 'wpr-addons' ) . ' <span class="wpr-new-badge">NEW</span>',
			'manage_options',
			'edit.php?post_type=wpr_custom_widget'
		);
	}
}
