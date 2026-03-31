<?php
namespace WprAddonsPro\Modules\ContentTogglePro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Content_Toggle_Pro',
		];
	}

	public function get_name() {
		return 'wpr-content-toggle-pro';
	}
}
