<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\MiniComparePro;

use WprAddonsPro\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-mini-compare-pro';
	}

	public function get_widgets() {
		return [
			'Wpr_Mini_Compare_Pro', // This should match the widget/element class.
		];
	}
	
}