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
            // No publicar páginas marcadas como "coming soon"
            $is_coming_soon = get_post_meta($page->ID, '_gano_coming_soon', true);
            if ($is_coming_soon === '1') {
                continue;
            }

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
 * Helper to get attachment ID by filename.
 * Tries two strategies:
 *   1. Meta value _wp_attached_file LIKE %filename%
 *   2. post_title LIKE %filename% on attachment posts
 * If neither finds a record and the file exists on disk, imports it automatically.
 */
function gano_p7_get_attachment_id_by_filename( $filename ) {
    global $wpdb;

    // Strategy 1: match via _wp_attached_file meta.
    $attachment = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_wp_attached_file' AND meta_value LIKE %s",
            '%' . $wpdb->esc_like( $filename ) . '%'
        )
    );
    if ( ! empty( $attachment ) ) {
        return (int) $attachment[0];
    }

    // Strategy 2: match via post_title on attachment records.
    $result = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' AND post_title LIKE %s LIMIT 1",
            '%' . $wpdb->esc_like( $filename ) . '%'
        )
    );
    if ( $result ) {
        return (int) $result;
    }

    // Strategy 3: file exists on disk but has no DB record — import it.
    return gano_p7_import_file_as_attachment( $filename );
}

/**
 * Imports a file from the wp-content/uploads directory as a WordPress attachment.
 * Scans the uploads base dir and any immediate subdirectory (yyyy/mm pattern) for the file.
 * Returns the new attachment ID on success, or false on failure.
 *
 * @param string $filename Bare filename, e.g. "hero_digital_garden.png".
 * @return int|false
 */
function gano_p7_import_file_as_attachment( $filename ) {
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $upload_dir = wp_upload_dir();
    $base_dir   = $upload_dir['basedir'];

    // Build candidate paths: uploads root + all yyyy/mm subdirs.
    $candidates = [ trailingslashit( $base_dir ) . $filename ];
    $subdirs    = glob( $base_dir . '/[0-9][0-9][0-9][0-9]/[0-9][0-9]', GLOB_ONLYDIR );
    if ( is_array( $subdirs ) ) {
        foreach ( $subdirs as $subdir ) {
            $candidates[] = trailingslashit( $subdir ) . $filename;
        }
    }

    $found_path = '';
    foreach ( $candidates as $candidate ) {
        if ( file_exists( $candidate ) ) {
            $found_path = $candidate;
            break;
        }
    }

    if ( ! $found_path ) {
        return false;
    }

    $filetype   = wp_check_filetype( $found_path );
    $attachment = [
        'guid'           => trailingslashit( $upload_dir['baseurl'] ) . _wp_relative_upload_path( $found_path ),
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
        'post_status'    => 'inherit',
    ];

    $attachment_id = wp_insert_attachment( $attachment, $found_path );
    if ( is_wp_error( $attachment_id ) ) {
        return false;
    }

    $metadata = wp_generate_attachment_metadata( $attachment_id, $found_path );
    wp_update_attachment_metadata( $attachment_id, $metadata );

    return (int) $attachment_id;
}
