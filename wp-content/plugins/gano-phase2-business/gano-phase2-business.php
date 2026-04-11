<?php
/**
 * Plugin Name: Gano Digital — Phase 2 Business Setup
 * Plugin URI:  https://gano.digital
 * Description: Configura WooCommerce, marca, moneda COP, categorías de productos y ajustes generales del negocio.
 *              Activar una sola vez y eliminar después.
 * Version:     1.0.0
 * Author:      DevSecOps / Gano Digital
 * Requires WC: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─── ACTIVACIÓN ───────────────────────────────────────────────────────────────
register_activation_hook( __FILE__, 'gano_p2_activate' );

function gano_p2_activate() {
    $log = [];

    // 1. IDENTIDAD DEL SITIO ───────────────────────────────────────────────────
    update_option( 'blogname',        'Gano Digital' );
    update_option( 'blogdescription', 'Tu presencia digital, tu victoria.' );
    update_option( 'admin_email',     'hola@gano.digital' );
    $log[] = '✅ Identidad del sitio configurada.';

    // 2. ZONA HORARIA Y FORMATO ────────────────────────────────────────────────
    update_option( 'timezone_string', 'America/Bogota' );
    update_option( 'date_format',     'd/m/Y' );
    update_option( 'time_format',     'H:i' );
    update_option( 'start_of_week',   1 );   // Lunes
    $log[] = '✅ Zona horaria América/Bogotá configurada.';

    // 3. WOOCOMMERCE — TIENDA ──────────────────────────────────────────────────
    $wc_settings = [
        // Dirección
        'woocommerce_store_address'   => 'Calle 184 #18-22',
        'woocommerce_store_city'      => 'Bogotá',
        'woocommerce_default_country' => 'CO:CUN',  // Colombia, Cundinamarca
        'woocommerce_store_postcode'  => '111156',

        // Moneda
        'woocommerce_currency'              => 'COP',
        'woocommerce_currency_pos'          => 'left',   // $ 50.000
        'woocommerce_price_thousand_sep'    => '.',
        'woocommerce_price_decimal_sep'     => ',',
        'woocommerce_price_num_decimals'    => '0',      // Sin decimales en COP

        // Unidades de medida
        'woocommerce_weight_unit'     => 'kg',
        'woocommerce_dimension_unit'  => 'cm',

        // Productos digitales (servicios de hosting = descargables/virtuales)
        'woocommerce_virtual_add_to_cart_text'       => 'Contratar',
        'woocommerce_digital_goods_taxable'          => 'yes',

        // Carrito
        'woocommerce_cart_page_id'     => gano_p2_get_page_id( 'Cart' ),
        'woocommerce_checkout_page_id' => gano_p2_get_page_id( 'Checkout' ),
        'woocommerce_myaccount_page_id'=> gano_p2_get_page_id( 'My account' ),
        'woocommerce_shop_page_id'     => gano_p2_get_page_id( 'Shop' ),

        // Emails
        'woocommerce_email_from_name'    => 'Gano Digital',
        'woocommerce_email_from_address' => 'noreply@gano.digital',

        // Checkout
        'woocommerce_registration_generate_password' => 'yes',
        'woocommerce_checkout_registration_enabled'  => 'yes',
        'woocommerce_checkout_guest_checkout'        => 'yes',

        // Inventario (productos digitales no necesitan stock)
        'woocommerce_manage_stock'    => 'no',
        'woocommerce_notify_low_stock'=> 'no',

        // Impuestos (IVA Colombia 19%)
        'woocommerce_calc_taxes'          => 'yes',
        'woocommerce_prices_include_tax'  => 'no',
        'woocommerce_tax_display_shop'    => 'excl',
        'woocommerce_tax_display_cart'    => 'incl',
        'woocommerce_tax_total_display'   => 'single',
    ];

    foreach ( $wc_settings as $key => $value ) {
        update_option( $key, $value );
    }
    $log[] = '✅ WooCommerce configurado (COP, Colombia, sin decimales).';

    // 4. CATEGORÍAS DE PRODUCTOS ───────────────────────────────────────────────
    $categories = [
        'dominios'       => [ 'name' => 'Dominios',            'desc' => 'Registra y administra tu nombre de dominio web.',                  'icon' => '🌐' ],
        'hosting'        => [ 'name' => 'Hosting',             'desc' => 'Planes de alojamiento web para todo tipo de proyectos.',           'icon' => '🖥️' ],
        'hosting-wp'     => [ 'name' => 'Hosting WordPress',   'desc' => 'Hosting optimizado para WordPress, con instalación automática.',   'icon' => '🔷' ],
        'vps'            => [ 'name' => 'Servidores VPS',      'desc' => 'Servidores virtuales privados con recursos dedicados.',            'icon' => '⚡' ],
        'dedicados'      => [ 'name' => 'Servidores Dedicados','desc' => 'Máxima potencia con servidor físico exclusivo para tu negocio.',   'icon' => '🔐' ],
        'ssl'            => [ 'name' => 'Certificados SSL',    'desc' => 'Protege tu sitio y transmite confianza a tus clientes.',           'icon' => '🔒' ],
        'email-prof'     => [ 'name' => 'Email Profesional',   'desc' => 'Correo corporativo con tu dominio. Google Workspace y M365.',      'icon' => '📧' ],
        'seguridad'      => [ 'name' => 'Seguridad Web',       'desc' => 'Protege tu sitio contra malware, ataques y vulnerabilidades.',     'icon' => '🛡️' ],
        'seo'            => [ 'name' => 'SEO & Marketing',     'desc' => 'Posicionamiento en buscadores y campañas de email marketing.',     'icon' => '📈' ],
        'backups'        => [ 'name' => 'Backups & Respaldo',  'desc' => 'Copias de seguridad automáticas de tu sitio web.',                 'icon' => '💾' ],
        'constructores'  => [ 'name' => 'Constructores Web',   'desc' => 'Crea tu sitio web sin programar con herramientas visuales.',      'icon' => '🏗️' ],
    ];

    $created = 0;
    foreach ( $categories as $slug => $cat ) {
        $existing = get_term_by( 'slug', $slug, 'product_cat' );
        if ( ! $existing ) {
            $result = wp_insert_term(
                $cat['name'],
                'product_cat',
                [
                    'slug'        => $slug,
                    'description' => $cat['desc'],
                ]
            );
            if ( ! is_wp_error( $result ) ) {
                $created++;
                // Guardar ícono como meta
                update_term_meta( $result['term_id'], 'gano_cat_icon', $cat['icon'] );
            }
        }
    }
    $log[] = "✅ {$created} categorías de productos creadas.";

    // 5. IVA COLOMBIA 19% ──────────────────────────────────────────────────────
    global $wpdb;
    $tax_table = $wpdb->prefix . 'woocommerce_tax_rates';
    $existing_tax = $wpdb->get_var( "SELECT tax_rate_id FROM {$tax_table} WHERE tax_rate_name = 'IVA Colombia' LIMIT 1" );

    if ( ! $existing_tax ) {
        $wpdb->insert( $tax_table, [
            'tax_rate_country'  => 'CO',
            'tax_rate_state'    => '*',
            'tax_rate'          => '19.0000',
            'tax_rate_name'     => 'IVA Colombia',
            'tax_rate_priority' => 1,
            'tax_rate_compound' => 0,
            'tax_rate_shipping' => 1,
            'tax_rate_order'    => 1,
            'tax_rate_class'    => '',
        ] );
        $log[] = '✅ IVA Colombia 19% configurado.';
    } else {
        $log[] = 'ℹ️ IVA Colombia ya existía.';
    }

    // 6. OPCIONES DE LECTURA / PERMALINKS ──────────────────────────────────────
    update_option( 'show_on_front',  'page' );
    update_option( 'page_on_front',  gano_p2_get_page_id( 'Inicio' ) ?: gano_p2_get_page_id( 'Home' ) );
    update_option( 'page_for_posts', gano_p2_get_page_id( 'Blog' ) );
    update_option( 'posts_per_page', 9 );

    // Permalinks a /%postname%/
    update_option( 'permalink_structure', '/%postname%/' );
    flush_rewrite_rules( true );
    $log[] = '✅ Permalinks configurados a /%postname%/.';

    // 7. CONFIGURACIÓN ELEMENTOR ──────────────────────────────────────────────
    update_option( 'elementor_disable_color_schemes',  'yes' );
    update_option( 'elementor_disable_typography_schemes', 'yes' );
    update_option( 'elementor_container_width', 1200 );
    update_option( 'elementor_space_between_widgets', 0 );
    $log[] = '✅ Elementor configurado (ancho 1200px, sin esquemas de color por defecto).';

    // 8. GUARDAR LOG ──────────────────────────────────────────────────────────
    update_option( 'gano_p2_log',    $log );
    update_option( 'gano_p2_ran_at', current_time( 'mysql' ) );
}

// ─── HELPER: buscar ID de página por título ──────────────────────────────────
function gano_p2_get_page_id( $title ) {
    $found_pages = get_posts([
        'post_type'      => 'page',
        'title'          => $title,
        'posts_per_page' => 1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ]);
    return !empty($found_pages) ? (int) $found_pages[0] : 0;
}

// ─── MOSTRAR RESULTADOS EN ADMIN ─────────────────────────────────────────────
add_action( 'admin_notices', 'gano_p2_notice' );
function gano_p2_notice() {
    $log = get_option( 'gano_p2_log', [] );
    if ( empty( $log ) ) return;

    $errors = array_filter( $log, fn($r) => str_starts_with( $r, '❌' ) );
    $class  = empty( $errors ) ? 'notice-success' : 'notice-warning';

    echo '<div class="notice ' . esc_attr( $class ) . ' is-dismissible">';
    echo '<p><strong>⚙️ Gano Digital — Phase 2 Business Setup</strong> — ' . esc_html( get_option( 'gano_p2_ran_at', '' ) ) . '</p><ul>';
    foreach ( $log as $entry ) {
        echo '<li>' . esc_html( $entry ) . '</li>';
    }
    echo '</ul><p><em>Puedes desactivar y eliminar este plugin.</em></p></div>';
}

// ─── DESACTIVACIÓN ────────────────────────────────────────────────────────────
register_deactivation_hook( __FILE__, function() {
    delete_option( 'gano_p2_log' );
    delete_option( 'gano_p2_ran_at' );
} );
