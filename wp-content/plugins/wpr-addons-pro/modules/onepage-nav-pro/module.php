<?php
namespace WprAddonsPro\Modules\OnepageNavPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_OnepageNav_Pro',
		];
	}

	public function get_name() {
		return 'wpr-onepage-nav-pro';
	}
}
