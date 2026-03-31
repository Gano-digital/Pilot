<?php
namespace WprAddonsPro\Modules\AdvancedTextPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Advanced_Text_Pro',
		];
	}

	public function get_name() {
		return 'wpr-advanced-text-pro';
	}
}
