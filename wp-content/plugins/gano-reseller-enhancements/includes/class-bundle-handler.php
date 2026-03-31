<?php
/**
 * Gano_Bundle_Handler
 * Handles multi-product bundles and 3rd-year term redirection.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Gano_Bundle_Handler {

    /**
     * Bundle Mapping: Business Tier => Array of GoDaddy IDs
     */
    private static $bundles = [
        'GANO-STARTER-3YR' => [
            'domain'  => '301', // Example IDs
            'builder' => '320', // Website Builder
            'email'   => '600'  // Basic Email
        ],
        'GANO-PRO-3YR'   => [
            'domain'  => '301',
            'hosting' => '102', // Managed WP
            'm365'    => '601'  // M365 Business Essentials
        ],
        'GANO-ENTERPRISE-3YR' => [
            'domain'  => '301',
            'vps'     => '504', // Tier 4
            'm365'    => '602', // M365 Business Professional
            'ssl'     => '203'  // Premium SSL
        ]
    ];

    public static function init() {
        add_action( 'init', [ __CLASS__, 'handle_bundle_redirect' ] );
    }

    /**
     * Listen for ?gano_add_bundle=SKU
     */
    public static function handle_bundle_redirect() {
        if ( ! isset( $_GET['gano_add_bundle'] ) ) return;

        $sku = sanitize_text_field( $_GET['gano_add_bundle'] );
        
        if ( ! isset( self::$bundles[$sku] ) ) {
            wp_die( 'Bundle no encontrado.' );
        }

        $items = [];
        foreach ( self::$bundles[$sku] as $id ) {
            $items[] = [
                'id'       => $id,
                'quantity' => 1,
                'period'   => 36 // 3 Years
            ];
        }

        $pl_id = rstore_get_option( 'pl_id', '599667' );
        $checkout_url = sprintf(
            'https://cart.secureserver.net/?plid=%s&items=%s',
            $pl_id,
            urlencode( json_encode( $items ) )
        );

        wp_redirect( $checkout_url );
        exit;
    }

    /**
     * Helper to get current catalog IDs for mapping confirmation.
     */
    public static function get_catalog_diagnostic() {
        $products = get_posts([
            'post_type' => 'reseller_product',
            'posts_per_page' => -1
        ]);
        
        $output = [];
        foreach($products as $p) {
            $output[] = [
                'title' => $p->post_title,
                'id'    => get_post_meta($p->ID, 'rstore_id', true)
            ];
        }
        return $output;
    }
}

Gano_Bundle_Handler::init();
