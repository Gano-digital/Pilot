<?php
/**
 * Plugin Name: Gano Digital — Phase 7 Content Activator
 * Description: Publicación masiva de páginas SOTA y optimización SEO con imágenes premium.
 * Version: 1.1.0
 * Author: Gano Digital Dev
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action('admin_init', 'gano_p7_activate_hub');

function gano_p7_activate_hub() {
    if (get_option('gano_p7_activated') === 'yes') return;

    require_once(ABSPATH . 'wp-admin/includes/plugin.php');

    $args = array(
        'post_type'      => 'page',
        'post_status'    => array('draft', 'pending', 'publish'), // Include publish to re-run mapping if needed
        'posts_per_page' => -1,
    );
    
    $pages = get_posts($args);
    
    foreach ($pages as $page) {
        $is_sota = get_post_meta($page->ID, '_gano_sota_category', true);
        
        if ($is_sota) {
            // Update to Published if it was draft
            if ($page->post_status !== 'publish') {
                wp_update_post(array(
                    'ID'          => $page->ID,
                    'post_status' => 'publish'
                ));
            }
            
            // Assign Template
            update_post_meta($page->ID, '_wp_page_template', 'templates/sota-single-template.php');
            
            // Map Category to Image
            $image_map = [
                'infraestructura'       => 'hero_digital_garden.png',
                'seguridad'             => 'icon_zero_trust_security.png',
                'inteligencia-artificial'=> 'icon_predictive_ai_server.png',
                'rendimiento'           => 'icon_nvme_speed.png',
                'ux-movil'              => 'decorative_abstract_seed.png',
                'estrategia'            => 'icon_edge_computing.png'
            ];
            
            if (isset($image_map[$is_sota])) {
                $image_name = $image_map[$is_sota];
                $attachment_id = gano_p7_get_attachment_id_by_filename($image_name);
                if ($attachment_id) {
                    set_post_thumbnail($page->ID, $attachment_id);
                }
            }

            // Basic Rank Math SEO injection
            if (is_plugin_active('seo-by-rank-math/rank-math.php')) {
                update_post_meta($page->ID, 'rank_math_focus_keyword', strtolower($page->post_title));
            }
        }
    }

    update_option('gano_p7_activated', 'yes');
}

/**
 * Helper to get attachment ID by filename
 */
function gano_p7_get_attachment_id_by_filename($filename) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_wp_attached_file' AND meta_value LIKE %s", '%' . $filename . '%'));
    return !empty($attachment) ? $attachment[0] : false;
}
