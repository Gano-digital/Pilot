<?php
namespace WprAddonsPro\Modules\TabsPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Tabs_Pro',
		];
	}

	public function get_name() {
		return 'wpr-tabs-pro';
	}
}