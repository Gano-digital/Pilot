<?php
namespace WprAddonsPro\Modules\PopupTriggerPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Popup_Trigger_Pro',
		];
	}

	public function get_name() {
		return 'wpr-popup-trigger-pro';
	}
}
