<?php
namespace WprAddonsPro\Modules\ContentTickerPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Content_Ticker_Pro',
		];
	}

	public function get_name() {
		return 'wpr-content-ticker-pro';
	}
}
