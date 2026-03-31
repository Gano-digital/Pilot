<?php
namespace WprAddonsPro\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_White_Label {
	public function __construct() {
		add_action('wpr_white_label_tab', [$this, 'tab_label']);
		add_action('wpr_white_label_tab_content', [$this, 'tab_content']);

		add_filter('all_plugins', [$this, 'update_branding']);

		// Remove Links Across Plugin
		add_filter('plugin_row_meta', [$this, 'plugin_row_meta'], 500, 2);
		add_filter( 'plugin_action_links_'. WPR_ADDONS_PLUGIN_BASE, [$this, 'remove_free_plugin_action_links'], 11, 2 );
		add_filter( 'plugin_action_links_'. WPR_ADDONS_PRO_PLUGIN_BASE, [$this, 'remove_pro_plugin_action_links'], 11, 2 );
	}
	
	public static function get_plugin_name( $full ) {
		$default = $full ? 'Royal Elementor Addons' : 'Royal Addons';
		return !empty(get_option('wpr_wl_plugin_name')) ? get_option('wpr_wl_plugin_name') : $default;
	}

	public function update_branding( $all_plugins ) {
		if ( defined( 'WPR_ADDONS_PRO_PLUGIN_BASE' ) ) {
			$all_plugins[WPR_ADDONS_PLUGIN_BASE]['Name'] = !empty(get_option('wpr_wl_plugin_name')) ? get_option('wpr_wl_plugin_name') : $all_plugins[WPR_ADDONS_PLUGIN_BASE]['Name'];
			$all_plugins[WPR_ADDONS_PLUGIN_BASE]['Title'] = !empty(get_option('wpr_wl_plugin_name')) ? get_option('wpr_wl_plugin_name') : $all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['Title'];
			$all_plugins[WPR_ADDONS_PLUGIN_BASE]['Description'] = !empty(get_option('wpr_wl_plugin_desc')) ? get_option('wpr_wl_plugin_desc') : $all_plugins[WPR_ADDONS_PLUGIN_BASE]['Description'];
			$all_plugins[WPR_ADDONS_PLUGIN_BASE]['Author'] = !empty(get_option('wpr_wl_plugin_author')) ? get_option('wpr_wl_plugin_author') : $all_plugins[WPR_ADDONS_PLUGIN_BASE]['Author'];
			$all_plugins[WPR_ADDONS_PLUGIN_BASE]['AuthorName'] = !empty(get_option('wpr_wl_plugin_author')) ? get_option('wpr_wl_plugin_author') : $all_plugins[WPR_ADDONS_PLUGIN_BASE]['AuthorName'];
			$all_plugins[WPR_ADDONS_PLUGIN_BASE]['PluginURI'] = !empty(get_option('wpr_wl_plugin_website')) ? get_option('wpr_wl_plugin_website') : $all_plugins[WPR_ADDONS_PLUGIN_BASE]['PluginURI'];
			$all_plugins[WPR_ADDONS_PLUGIN_BASE]['AuthorURI'] = !empty(get_option('wpr_wl_plugin_website')) ? get_option('wpr_wl_plugin_website') : $all_plugins[WPR_ADDONS_PLUGIN_BASE]['AuthorURI'];
			
			$all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['Name'] = !empty(get_option('wpr_wl_plugin_name')) ? get_option('wpr_wl_plugin_name') .' Pro' : $all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['Name'];
			$all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['Title'] = !empty(get_option('wpr_wl_plugin_name')) ? get_option('wpr_wl_plugin_name') .' Pro' : $all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['Title'];
			$all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['Description'] = !empty(get_option('wpr_wl_plugin_desc')) ? get_option('wpr_wl_plugin_desc') : $all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['Description'];
			$all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['Author'] = !empty(get_option('wpr_wl_plugin_author')) ? get_option('wpr_wl_plugin_author') : $all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['Author'];
			$all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['AuthorName'] = !empty(get_option('wpr_wl_plugin_author')) ? get_option('wpr_wl_plugin_author') : $all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['AuthorName'];
			$all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['PluginURI'] = !empty(get_option('wpr_wl_plugin_website')) ? get_option('wpr_wl_plugin_website') : $all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['PluginURI'];
			$all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['AuthorURI'] = !empty(get_option('wpr_wl_plugin_website')) ? get_option('wpr_wl_plugin_website') : $all_plugins[WPR_ADDONS_PRO_PLUGIN_BASE]['AuthorURI'];

		}

		return $all_plugins;
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {

		if ( !empty(get_option('wpr_wl_plugin_links')) ) {
			if ( defined( 'WPR_ADDONS_PLUGIN_BASE' ) && WPR_ADDONS_PLUGIN_BASE === $plugin_file ) {
				$plugin_meta = array( $plugin_meta[0], $plugin_meta[1] );
			}

			if ( defined( 'WPR_ADDONS_PRO_PLUGIN_BASE' ) && WPR_ADDONS_PRO_PLUGIN_BASE === $plugin_file ) {
				$plugin_meta = array( $plugin_meta[0], $plugin_meta[1] );
			}
		}

		return $plugin_meta;
	}

	public function remove_free_plugin_action_links( $links, $file ) {
		if ( !empty(get_option('wpr_wl_plugin_links')) ) {
			unset($links['0']);
			unset($links['deactivate']);
		}

	    return $links;
	}

	public function remove_pro_plugin_action_links( $links, $file ) {
		if ( !empty(get_option('wpr_wl_plugin_links')) ) {
			unset($links['activate-license wpr-addons']);
			unset($links['opt-in-or-opt-out wpr-addons']);
			unset($links['deactivate']);
		}

	    return $links;
	}

	public function tab_label() {
	    // Active Tab
	    $active_tab = isset( $_GET['tab'] ) ? esc_attr($_GET['tab']) : 'wpr_tab_elements';
		?>

		<a href="?page=wpr-addons&tab=wpr_tab_white_label" data-title="White Label" class="nav-tab <?php echo $active_tab == 'wpr_tab_white_label' ? 'nav-tab-active' : ''; ?>">
            <?php esc_html_e( 'White Label', 'wpr-addons' ); ?>
        </a>

		<?php
	}

	public function tab_content() {

        // Settings
        settings_fields( 'wpr-wh-settings' );
        do_settings_sections( 'wpr-wh-settings' );

		?>

		<div class="wpr-settings wpr-wl-tab-content">

	        <div class="wpr-settings-group">
	            <h3 class="wpr-settings-group-title"><?php esc_html_e( 'Custom Branding', 'wpr-addons' ); ?></h3>

	            <div class="wpr-setting wpr-setting-custom-img-upload">
	                <h4>
	                    <span><?php esc_html_e( 'Logo Image', 'wpr-addons' ); ?></span>
	                </h4>

	                <div>
	                	<button>
	                		<?php if ( empty(get_option('wpr_wl_plugin_logo')) ) : ?>
		                		<i class="dashicons dashicons-cloud-upload"></i>
		                		<span><?php esc_html_e( 'Upload Image', 'wpr-addons' ); ?></span>
		                	<?php else: ?>
		                		<img src="<?php echo esc_url( wp_get_attachment_image_src(get_option('wpr_wl_plugin_logo'), 'full')[0] ); ?>">
		                		<span><?php esc_html_e( 'Remove Image', 'wpr-addons' ); ?></span>
		                	<?php endif; ?>
	                	</button>
	                	<input type="hidden" name="wpr_wl_plugin_logo" id="wpr_wl_plugin_logo" value="<?php echo esc_attr(get_option('wpr_wl_plugin_logo')); ?>">
	                </div>
	            </div>


	            <div class="wpr-setting">
	                <h4>
	                    <span><?php esc_html_e( 'Plugin Name', 'wpr-addons' ); ?></span>
	                </h4>

	                <input type="text" name="wpr_wl_plugin_name" id="wpr_wl_plugin_name" value="<?php echo esc_attr(get_option('wpr_wl_plugin_name')); ?>">
	            </div>

	            <div class="wpr-setting">
	                <h4>
	                    <span><?php esc_html_e( 'Plugin Description', 'wpr-addons' ); ?></span>
	                </h4>

	                <input type="text" name="wpr_wl_plugin_desc" id="wpr_wl_plugin_desc" value="<?php echo esc_attr(get_option('wpr_wl_plugin_desc')); ?>">
	            </div>

	            <div class="wpr-setting">
	                <h4>
	                    <span><?php esc_html_e( 'Developer/Agency Name', 'wpr-addons' ); ?></span>
	                </h4>

	                <input type="text" name="wpr_wl_plugin_author" id="wpr_wl_plugin_author" value="<?php echo esc_attr(get_option('wpr_wl_plugin_author')); ?>">
	            </div>

	            <div class="wpr-setting">
	                <h4>
	                    <span><?php esc_html_e( 'Website URL', 'wpr-addons' ); ?></span>
	                </h4>

	                <input type="text" name="wpr_wl_plugin_website" id="wpr_wl_plugin_website" value="<?php echo esc_attr(get_option('wpr_wl_plugin_website')); ?>">
	            </div>

	        </div>

	        <div class="wpr-settings-group">
	            <h3 class="wpr-settings-group-title"><?php esc_html_e( 'Show/Hide Features', 'wpr-addons' ); ?></h3>

	            <div class="wpr-setting wpr-setting-custom-ckbox">
	                <h4>
	                	<span><?php esc_html_e( 'Hide Plugin Links - Support, Affiliate, Widget Demos, Deactivation, etc... ', 'wpr-addons' ); ?></span>
	                    <input type="checkbox" name="wpr_wl_plugin_links" id="wpr_wl_plugin_links" <?php checked( get_option('wpr_wl_plugin_links'), 'on', true ); ?>>
	                    <label for="wpr_wl_plugin_links"></label>
	                </h4>
	            </div>

	            <div class="wpr-setting wpr-setting-custom-ckbox">
	                <h4>
	                	<span><?php esc_html_e( 'Hide Elements Tab', 'wpr-addons' ); ?></span>
	                    <input type="checkbox" name="wpr_wl_hide_elements_tab" id="wpr_wl_hide_elements_tab" <?php checked( get_option('wpr_wl_hide_elements_tab'), 'on', true ); ?>>
	                    <label for="wpr_wl_hide_elements_tab"></label>
	                </h4>
	            </div>

	            <div class="wpr-setting wpr-setting-custom-ckbox">
	                <h4>
	                	<span><?php esc_html_e( 'Hide Extensions Tab', 'wpr-addons' ); ?></span>
	                    <input type="checkbox" name="wpr_wl_hide_extensions_tab" id="wpr_wl_hide_extensions_tab" <?php checked( get_option('wpr_wl_hide_extensions_tab'), 'on', true ); ?>>
	                    <label for="wpr_wl_hide_extensions_tab"></label>
	                </h4>
	            </div>

	            <div class="wpr-setting wpr-setting-custom-ckbox">
	                <h4>
	                	<span><?php esc_html_e( 'Hide Settings Tab', 'wpr-addons' ); ?></span>
	                    <input type="checkbox" name="wpr_wl_hide_settings_tab" id="wpr_wl_hide_settings_tab" <?php checked( get_option('wpr_wl_hide_settings_tab'), 'on', true ); ?>>
	                    <label for="wpr_wl_hide_settings_tab"></label>
	                </h4>
	            </div>

	            <div class="wpr-setting wpr-setting-custom-ckbox">
	                <h4>
	                	<span><?php esc_html_e( 'Hide White Label Tab', 'wpr-addons' ); ?></span>
	                    <input type="checkbox" name="wpr_wl_hide_white_label_tab" id="wpr_wl_hide_white_label_tab" <?php checked( get_option('wpr_wl_hide_white_label_tab'), 'on', true ); ?>>
	                    <label for="wpr_wl_hide_white_label_tab"></label>
	                </h4>
	                <p>
		                <em>
		                	<?php esc_html_e( 'You can still access disabled tabs via direct admin links, for example:', 'wpr-addons' ); ?><br>
		                	<?php echo admin_url('admin.php?page=wpr-addons&tab=wpr_tab_white_label'); ?>
		                	<?php echo admin_url('admin.php?page=wpr-addons&tab=wpr_tab_elements'); ?>
		                </em>
	                </p>
	            </div>

	        </div>

	    </div>

		<?php

		submit_button( '', 'wpr-options-button' );

	}

}

if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
	new Wpr_White_Label();
}