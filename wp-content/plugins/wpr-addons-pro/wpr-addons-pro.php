<?php

/*
 * Plugin Name: Royal Elementor Addons Pro (Premium)
 * Description: The only plugin you need for Elementor page builder.
 * Plugin URI: https://wp-royal.com/
 * Author: WP Royal
 * Version: 1.5.96

 * Update URI: https://api.freemius.com
 * Author URI: https://wp-royal.com/
 * Elementor tested up to: 3.31.3
 * Elementor Pro tested up to: 3.31.2
 *
 * Text Domain: wpr-addons
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
define( 'WPR_ADDONS_PRO_VERSION', '1.5.96' );
define( 'WPR_ADDONS_PRO__FILE__', __FILE__ );
define( 'WPR_ADDONS_PRO_PLUGIN_BASE', plugin_basename( WPR_ADDONS_PRO__FILE__ ) );
define( 'WPR_ADDONS_PRO_PATH', plugin_dir_path( WPR_ADDONS_PRO__FILE__ ) );
define( 'WPR_ADDONS_PRO_MODULES_PATH', WPR_ADDONS_PRO_PATH . 'modules/' );
define( 'WPR_ADDONS_PRO_URL', plugins_url( '/', WPR_ADDONS_PRO__FILE__ ) );
define( 'WPR_ADDONS_PRO_ASSETS_URL', WPR_ADDONS_PRO_URL . 'assets/' );
define( 'WPR_ADDONS_PRO_MODULES_URL', WPR_ADDONS_PRO_URL . 'modules/' );
/**
 * Feemius Integration
 */
if ( function_exists( 'wpr_fs' ) ) {
    wpr_fs()->set_basename( true, __FILE__ );
} else {
    $register_freemius = true;
    if ( $register_freemius ) {
        // Create a helper function for easy SDK access.
        function wpr_fs() {
            global $wpr_fs;
		    if ( ! isset( $wpr_fs ) ) {
		        if ( ! class_exists( 'wpr_fs_null' ) ) {
		            class wpr_fs_null {
		                public function can_use_premium_code__premium_only() {
		                    return true;
		                }
		                public function can_use_premium_code() {
		                    return true;
		                }
		                public function is_plan() {
		                    return 'expert';
		                }
		                public function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
		                    add_filter( $tag, $function_to_add, $priority, $accepted_args );
		                }
		                public function set_basename( $flag, $file ) {
		                }
		                public function __call( $name, $arguments ) {
		                    return false;
		                }
		            }
		        }
		        require_once dirname( __FILE__ ) . '/freemius/start.php';
		        $wpr_fs = new wpr_fs_null();
		    }
		    return $wpr_fs;
        }

        // Init Freemius.
        wpr_fs();
        // Signal that SDK was initiated.
        do_action( 'wpr_fs_loaded' );
        wpr_fs()->add_filter( 'show_deactivation_subscription_cancellation', '__return_false' );
        wpr_fs()->add_filter( 'deactivate_on_activation', '__return_false' );
        if ( wpr_fs()->can_use_premium_code() && defined( 'WPR_ADDONS_PRO_VERSION' ) ) {
            define( 'WPR_ADDONS_PRO_LICENSE', true );
        }
    }
}
/**
 * Load gettext translate for our text domain.
 *
 * @since 1.0
 *
 * @return void
 */
function wpr_addons_pro_load_plugin() {
    load_plugin_textdomain( 'wpr-addons' );
    if ( !did_action( 'elementor/loaded' ) || !defined( 'WPR_ADDONS_VERSION' ) ) {
        // add_action( 'admin_enqueue_scripts', 'wpr_enqueue_admin_scripts', 989 );
        add_action( 'admin_notices', 'wpr_addons_pro_fail_load' );
        return;
    }
    require WPR_ADDONS_PRO_PATH . 'plugin.php';
}

add_action( 'plugins_loaded', 'wpr_addons_pro_load_plugin' );
/**
 * Show in WP Dashboard notice about the plugin is not activated.
 *
 * @since 1.0
 *
 * @return void
 */
function wpr_addons_pro_fail_load() {
    $screen = get_current_screen();
    if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
        return;
    }
    if ( _is_wpr_addons_installed() ) {
        if ( !current_user_can( 'activate_plugins' ) || is_plugin_active( 'royal-elementor-addons/wpr-addons.php' ) ) {
            return;
        }
        $plugin = 'royal-elementor-addons/wpr-addons.php';
        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
        $message = '<div class="error"><p>' . esc_html__( 'Royal Elementor Addons Pro is not working because you need to activate the Royal Elementor Addons plugin.', 'wpr-addons' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Royal Elementor Addons Now', 'wpr-addons' ) ) . '</p></div>';
        echo $message;
    } else {
        if ( !current_user_can( 'install_plugins' ) ) {
            return;
        }
        $install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=royal-elementor-addons' ), 'install-plugin_royal-elementor-addons' );
        $message = '<div class="error"><p>' . esc_html__( 'Royal Elementor Addons Pro is not working because you need to install the Royal Elementor Addons plugin.', 'wpr-addons' ) . '</p>';
        $message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Royal Elementor Addons Now', 'wpr-addons' ) ) . '</p></div>';
        echo $message;
    }
}

function _is_wpr_addons_installed() {
    $file_path = 'royal-elementor-addons/wpr-addons.php';
    $installed_plugins = get_plugins();
    return isset( $installed_plugins[$file_path] );
}

// Set Plugin Activation Time
function royal_elementor_addons_pro_activation_time() {
    //TODO: Try to locate this in rating-notice.php later if possible
    if ( false === get_option( 'royal_elementor_addons_pro_activation_time' ) ) {
        add_option( 'royal_elementor_addons_pro_activation_time', absint( intval( strtotime( 'now' ) ) ) );
    }
}

register_activation_hook( __FILE__, 'royal_elementor_addons_pro_activation_time' );
// Reset Options on Deactivation
function royal_addons_pro_deactivation() {
    delete_option( 'wpr_wl_hide_elements_tab' );
    delete_option( 'wpr_wl_hide_extensions_tab' );
    delete_option( 'wpr_wl_hide_settings_tab' );
    delete_option( 'wpr_wl_hide_white_label_tab' );
    delete_option( 'royal_elementor_addons_pro_activation_time' );
}

if ( !function_exists( 'wpr_enquque_admin_scripts' ) ) {
    // function wpr_enqueue_admin_scripts() {
    // 	if ( ! wp_script_is( 'wpr-wrong-update-js', 'enqueued' ) ) {
    // 		wp_enqueue_script(
    // 			'wpr-wrong-update-js',
    // 			WPR_ADDONS_PRO_URL . 'assets/js/wrong-update.js',
    // 			[
    // 				'jquery'
    // 			]
    // 		);
    // 	}
    // }
}
register_deactivation_hook( __FILE__, 'royal_addons_pro_deactivation' );