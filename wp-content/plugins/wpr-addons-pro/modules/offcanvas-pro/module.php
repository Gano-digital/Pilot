<?php
namespace WprAddonsPro\Modules\OffcanvasPro;

use WprAddonsPro\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-offcanvas-pro';
	}

	public function get_widgets() {
		return [
			'Wpr_Offcanvas_Pro', // This should match the widget/element class.
		];
	}
	
}