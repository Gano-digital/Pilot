<?php
namespace WprAddonsPro\Modules\AdvancedSliderPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Advanced_Slider_Pro',
		];
	}

	public function get_name() {
		return 'wpr-advanced-slider-pro';
	}
}
