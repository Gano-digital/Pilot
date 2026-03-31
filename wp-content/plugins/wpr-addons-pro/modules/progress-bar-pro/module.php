<?php
namespace WprAddonsPro\Modules\ProgressBarPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Progress_Bar_Pro',
		];
	}

	public function get_name() {
		return 'wpr-progress-bar-pro';
	}
}
