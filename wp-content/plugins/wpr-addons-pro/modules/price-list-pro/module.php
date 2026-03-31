<?php
namespace WprAddonsPro\Modules\PriceListPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Price_List_Pro',
		];
	}

	public function get_name() {
		return 'wpr-price-list-pro';
	}
}
