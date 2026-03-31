<?php
/**
 * Plugin Name: Gano Digital — Phase 6 Final Setup
 * Description: Asegura que todos los productos y páginas de la Fase 6 estén creados y configurados.
 * Version: 1.0.0
 * Author: Gano Digital Dev
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action('admin_init', 'gano_p6_final_setup_check');

function gano_p6_final_setup_check() {
    // 1. Ensure WooCommerce is active
    if ( ! class_exists( 'WooCommerce' ) ) return;

    // 2. Create Products (delegated to our importer)
    if ( file_exists( WP_PLUGIN_DIR . '/gano-phase6-catalog/gano-phase6-catalog.php' ) ) {
        if ( ! function_exists( 'gano_p6_import_catalog' ) ) {
            require_once WP_PLUGIN_DIR . '/gano-phase6-catalog/gano-phase6-catalog.php';
        }
        gano_p6_import_catalog();
    }

    // 3. Register Shop Page if it doesn't exist
    $page_title = 'Ecosistemas';
    $page_check = get_page_by_title($page_title);
    if(!isset($page_check->ID)){
        $new_page = array(
            'post_type'     => 'page',
            'post_title'    => $page_title,
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'meta_input'    => array(
                '_wp_page_template' => 'templates/shop-premium.php'
            )
        );
        wp_insert_post($new_page);
    }

    // 4. Update WooCommerce Shop Page setting
    $shop_page = get_page_by_title('Ecosistemas');
    if ($shop_page) {
        update_option('woocommerce_shop_page_id', $shop_page->ID);
    }
}
