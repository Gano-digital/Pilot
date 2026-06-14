<?php
/**
 * SOTA Final Activator
 * Publishes all SOTA pages and assigns the V2 template.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function gano_activate_sota_production() {
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'post_status'    => array('publish', 'draft', 'pending', 'private')
    );

    $pages = get_posts($args);
    $count = 0;

    foreach ($pages as $page) {
        // We know our SOTA pages have a specific pattern or used the importer.
        // The importer assigns a specific meta _gano_sota_category (mocked here or based on titles).
        $is_sota = get_post_meta($page->ID, '_gano_sota_category', true);
        
        if ($is_sota) {
            wp_update_post(array(
                'ID'          => $page->ID,
                'post_status' => 'publish'
            ));
            update_post_meta($page->ID, '_wp_page_template', 'templates/sota-single-template.php');
            $count++;
        }
    }
    Gano_Agent_Logger::log( "SOTA Final Activator: $count pages published and assigned to V2 template.", 'INFO' );
}

// URL: /wp-admin/?gano_sota_release_1=1&_wpnonce=<nonce>
// Generate nonce: wp_create_nonce('gano_sota_release_action')
if (isset($_GET['gano_sota_release_1'])) {
    if (!current_user_can('manage_options') || !wp_verify_nonce($_GET['_wpnonce'] ?? '', 'gano_sota_release_action')) {
        wp_die('Acceso denegado. Se requiere nonce válido y capacidad de administrador.', '403');
    }
    add_action('admin_init', 'gano_activate_sota_production');
}
