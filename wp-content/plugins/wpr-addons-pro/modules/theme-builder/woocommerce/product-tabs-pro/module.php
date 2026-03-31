<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\ProductTabsPro;

use WprAddonsPro\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-product-tabs-pro';
	}

	public function get_widgets() {
		return [
			'Wpr_Product_Tabs_Pro', // This should match the widget/element class.
		];
	}
	
}