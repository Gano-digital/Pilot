<?php
namespace WprAddonsPro\Modules\PromoBoxPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Promo_Box_Pro',
		];
	}

	public function get_name() {
		return 'wpr-promo-box-pro';
	}
}
