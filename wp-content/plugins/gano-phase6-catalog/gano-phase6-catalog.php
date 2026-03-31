<?php
/**
 * Plugin Name: Gano Digital — Phase 6 Catalog Importer
 * Description: Crea los productos core de Gano Digital con metadatos técnicos y bundles.
 * Version: 1.0.0
 * Author: Gano Digital Dev
 */

if ( ! defined( 'ABSPATH' ) ) exit;

register_activation_hook( __FILE__, 'gano_p6_import_catalog' );

function gano_p6_import_catalog() {
    if ( ! class_exists( 'WooCommerce' ) ) return;

    $products = [
        [
            'sku'         => 'GD-STARTUP-01',
            'name'        => 'Startup Blueprint',
            'price'       => '796000', // ~$199 USD
            'description' => 'Solución ideal para landing pages de alto impacto y captación de leads. Incluye hosting NVMe y seguridad base.',
            'category'    => 'hosting-wp',
            'specs'       => [
                'Storage' => '10 GB NVMe Gen4',
                'RAM'     => '2 GB Dedicada',
                'Security'=> 'Blindaje Base',
                'Agent'   => 'Soporte IA Nivel 1'
            ],
            'monthly'     => '196000', // ~$49 USD
        ],
        [
            'sku'         => 'GD-BASIC-01',
            'name'        => 'Ecosistema Básico',
            'price'       => '4000000', // $1,000 USD
            'description' => 'Ecosistema completo para PyMEs. WordPress blindado con monitoreo 24/7 y optimización de velocidad premium.',
            'category'    => 'hosting-wp',
            'specs'       => [
                'Storage' => '30 GB NVMe Gen4',
                'RAM'     => '4 GB Dedicada',
                'Security'=> 'Zero-Trust Basic',
                'Agent'   => 'Monitoreo Predictivo'
            ],
            'monthly'     => '600000', // ~$150 USD
            'featured'    => true
        ],
        [
            'sku'         => 'GD-ADVANCED-01',
            'name'        => 'Ecosistema Avanzado',
            'price'       => '10000000', // $2,500 USD
            'description' => 'La opción definitiva para E-commerce. Escalamiento elástico y protección DDoS de Capa 7.',
            'category'    => 'hosting-wp',
            'specs'       => [
                'Storage' => '100 GB NVMe Gen4',
                'RAM'     => '8 GB Dedicada',
                'Security'=> 'WAF Capa 7 + Anti-DDoS',
                'Agent'   => 'Acciones Autónomas'
            ],
            'monthly'     => '1200000', // ~$300 USD
        ],
        [
            'sku'         => 'GD-SOBERANIA-01',
            'name'        => 'Soberanía Digital',
            'price'       => '20000000', // $5,000 USD
            'description' => 'Infraestructura dedicada con propiedad absoluta de datos y cifrado post-cuántico.',
            'category'    => 'vps',
            'specs'       => [
                'Storage' => '500 GB NVMe Gen4',
                'RAM'     => '16 GB Dedicada',
                'Security'=> 'Cifrado Post-Cuántico',
                'Agent'   => 'Admin IA Dedicado'
            ],
            'monthly'     => '2000000', // ~$500 USD
        ],
    ];

    foreach ( $products as $p ) {
        // Skip if SKU exists
        $product_id = wc_get_product_id_by_sku( $p['sku'] );
        if ( $product_id ) continue;

        $product = new WC_Product_Simple();
        $product->set_sku( $p['sku'] );
        $product->set_name( $p['name'] );
        $product->set_regular_price( $p['price'] );
        $product->set_description( $p['description'] );
        $product->set_short_description( 'Ecosistema digital de alto rendimiento.' );
        $product->set_status( 'publish' );
        $product->set_virtual( true );
        $product->set_downloadable( true );

        // Featured status
        if ( isset( $p['featured'] ) && $p['featured'] ) {
            $product->set_featured( true );
        }

        $id = $product->save();

        // Assign category
        $cat = get_term_by( 'slug', $p['category'], 'product_cat' );
        if ( $cat ) {
            wp_set_object_terms( $id, $cat->term_id, 'product_cat' );
        }

        // Custom Meta for Specs and Monthly Price
        update_post_meta( $id, '_gano_tech_specs', json_encode( $p['specs'] ) );
        update_post_meta( $id, '_gano_monthly_fee', $p['monthly'] );
    }
}
