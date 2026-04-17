<?php
/**
 * Plugin Name: Gano Digital — Reseller Store Enhancements
 * Description: Añade funcionalidades avanzadas al plugin GoDaddy Reseller Store: persistencia de precios, metadatos ACF y ajustes de API.
 * Version: 1.0.0
 * Author: Gano Digital Dev
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define plugin constants
define( 'GANO_RESELLER_PATH', plugin_dir_path( __FILE__ ) );

// Include ACF field registration and core classes
require_once GANO_RESELLER_PATH . 'includes/acf-reseller-fields.php';
require_once GANO_RESELLER_PATH . 'includes/class-sandbox.php';
require_once GANO_RESELLER_PATH . 'includes/class-bundle-handler.php';

// Load smoke-test page and PFID admin panel only in admin context.
if ( is_admin() ) {
	require_once GANO_RESELLER_PATH . 'includes/class-smoke-test.php';
	require_once GANO_RESELLER_PATH . 'includes/class-pfid-admin.php';
}

/**
 * Filter: rstore_sync_properties
 * Prevents synchronization of prices if 'override_price' is enabled for the product.
 */
add_filter( 'rstore_sync_properties', 'gano_reseller_filter_sync_properties', 10, 2 );

function gano_reseller_filter_sync_properties( $properties, $post_id = 0 ) {
    // Check if ACF is active and the override flag is set
    if ( function_exists( 'get_field' ) ) {
        $override = get_field( 'override_price', $post_id );
        
        if ( $override ) {
            // Remove 'listPrice' and 'salePrice' from the properties to sync
            if ( isset( $properties['listPrice'] ) ) unset( $properties['listPrice'] );
            if ( isset( $properties['salePrice'] ) ) unset( $properties['salePrice'] );
            
            // Optionally remove 'title' or 'content' if needed, but let's stick to prices as requested
        }
    }
    
    return $properties;
}

/**
 * Filter: rstore_api_query_args
 * Customizes the API request arguments for market and currency.
 */
add_filter( 'rstore_api_query_args', 'gano_reseller_filter_api_args' );

function gano_reseller_filter_api_args( $args ) {
    // Mercado colombiano: es-CO y COP
    $args['marketId']     = 'es-CO';
    $args['currencyType'] = 'COP';

    return $args;
}
