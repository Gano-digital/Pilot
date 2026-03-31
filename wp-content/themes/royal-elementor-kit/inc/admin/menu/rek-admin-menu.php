<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !is_admin() ) {
    return;
}

class REK_Admin_Menu {

    public function __construct() {
        add_action( 'admin_menu', [$this, 'admin_menu'] );
		add_action( 'admin_init', [$this, 'enqueue_scripts'] );
    }

    public function admin_menu() {
        add_menu_page(
            esc_html__( 'Royal Elementor', 'royal-elementor-kit' ),
            esc_html__( 'Royal Elementor', 'royal-elementor-kit' ),
            'manage_options',
            'rek-options',
            [$this, 'options_page'],
            'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iOTciIGhlaWdodD0iNzUiIHZpZXdCb3g9IjAgMCA5NyA3NSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTAuMDM2NDA4NiAyMy4yODlDLTAuNTc1NDkgMTguNTIxIDYuNjg4NzMgMTYuMzY2NiA5LjU0OSAyMC40Njc4TDQyLjgzNjUgNjguMTk3MkM0NC45MTgxIDcxLjE4MiA0Mi40NDk0IDc1IDM4LjQzNzggNzVIMTEuMjc1NkM4LjY1NDc1IDc1IDYuNDUyNjQgNzMuMjg1NSA2LjE2MTcgNzEuMDE4NEwwLjAzNjQwODYgMjMuMjg5WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTk2Ljk2MzYgMjMuMjg5Qzk3LjU3NTUgMTguNTIxIDkwLjMxMTMgMTYuMzY2NiA4Ny40NTEgMjAuNDY3OEw1NC4xNjM1IDY4LjE5NzJDNTIuMDgxOCA3MS4xODIgNTQuNTUwNiA3NSA1OC41NjIyIDc1SDg1LjcyNDRDODguMzQ1MiA3NSA5MC41NDc0IDczLjI4NTUgOTAuODM4MyA3MS4wMTg0TDk2Ljk2MzYgMjMuMjg5WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTUzLjI0MTIgNC40ODUyN0M1My4yNDEyIC0wLjI3MDc2MSA0NS44NDg1IC0xLjc0ODAzIDQzLjQ2NTEgMi41MzE3NEw2LjY4OTkxIDY4LjU2NzdDNS4wMzM0OSA3MS41NDIxIDcuNTIyNzIgNzUgMTEuMzIwMyA3NUg0OC4wOTU1QzUwLjkzNzQgNzUgNTMuMjQxMiA3Mi45OTQ4IDUzLjI0MTIgNzAuNTIxMlY0LjQ4NTI3WiIgZmlsbD0id2hpdGUiLz4KPHBhdGggZD0iTTQzLjc1ODggNC40ODUyN0M0My43NTg4IC0wLjI3MDc2MSA1MS4xNTE1IC0xLjc0ODAzIDUzLjUzNDkgMi41MzE3NEw5MC4zMTAxIDY4LjU2NzdDOTEuOTY2NSA3MS41NDIxIDg5LjQ3NzMgNzUgODUuNjc5NyA3NUg0OC45MDQ1QzQ2LjA2MjYgNzUgNDMuNzU4OCA3Mi45OTQ4IDQzLjc1ODggNzAuNTIxMlY0LjQ4NTI3WiIgZmlsbD0id2hpdGUiLz4KPC9zdmc+Cg==',
            59
        );
    }

    public function options_page() {
        $welcome_notice = new REK_Welcome_Notice();
        echo '<div class="rek-options-page">';
            echo '<div class="rek-notice notice">';
                $welcome_notice->render_notice_content();
            echo '</div>';
        echo '</div>';
    }

    public function enqueue_scripts() {
        if ( isset($_GET['page']) && 'rek-options' === $_GET['page'] ) {
            // Enqueue Styles
            wp_enqueue_style( 'rek-admin-menu', get_template_directory_uri() .'/assets/css/admin-menu.css', [], '1.0.132' );

            // Enqueue Scripts
            wp_enqueue_script( 'rek-admin-menu', get_template_directory_uri() .'/assets/js/admin-menu.js', ['jquery', 'updates'], '1.0.132', true );

            // Localize Theme Scripts
            wp_localize_script( 'rek-admin-menu', 'REKAdminMenu', [
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('rek-activate-required-plugins'),
                ]
            );
        }
    }
}

if ( ! function_exists('is_plugin_active') ) {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( ! is_plugin_active( 'royal-elementor-addons/wpr-addons.php' ) ) {
    new REK_Admin_Menu();
}
