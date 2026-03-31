<?php
namespace WprAddonsPro\Modules\PricingTablePro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Pricing_Table_Pro',
		];
	}

	public function get_name() {
		return 'wpr-pricing-table-pro';
	}
}
