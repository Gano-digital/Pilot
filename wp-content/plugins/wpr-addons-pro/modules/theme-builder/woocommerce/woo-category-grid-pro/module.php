<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\WooCategoryGridPro;

use WprAddonsPro\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-woo-category-grid-pro';
	}

	public function get_widgets() {
		return [
			'Wpr_Woo_Category_Grid_Pro', // This should match the widget/element class.
		];
	}
	
}