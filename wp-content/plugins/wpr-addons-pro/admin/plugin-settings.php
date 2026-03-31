<?php
namespace WprAddonsPro\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Plugin_Settings {
	public function __construct() {
		add_action('wpr_woocommerce_settings', [$this, 'get_woocommerce_settings']);
		add_action('wpr_cpt_settings', [$this, 'get_cpt_settings']);
	}

	public function get_woocommerce_settings() {
		?>

			<div class="wpr-setting">
				<h4>
					<span><?php esc_html_e( 'Shop Page: Products Per Page', 'wpr-addons' ); ?></span>
					<br>
				</h4>

				<input type="text" name="wpr_woo_shop_ppp" id="wpr_woo_shop_ppp" value="<?php echo esc_attr(get_option('wpr_woo_shop_ppp', 9)); ?>">
			</div>

			<div class="wpr-setting">
				<h4>
					<span><?php esc_html_e( 'Product Category: Products Per Page', 'wpr-addons' ); ?></span>
					<br>
				</h4>

				<input type="text" name="wpr_woo_shop_cat_ppp" id="wpr_woo_shop_cat_ppp" value="<?php echo esc_attr(get_option('wpr_woo_shop_cat_ppp', 9)); ?>">
			</div>

			<div class="wpr-setting">
				<h4>
					<span><?php esc_html_e( 'Product Tag: Products Per Page', 'wpr-addons' ); ?></span>
					<br>
				</h4>

				<input type="text" name="wpr_woo_shop_tag_ppp" id="wpr_woo_shop_tag_ppp" value="<?php echo esc_attr(get_option('wpr_woo_shop_tag_ppp', 9)); ?>">
			</div>

		<?php
	}

	public function get_cpt_settings() {
		
	}

}

new Wpr_Plugin_Settings();