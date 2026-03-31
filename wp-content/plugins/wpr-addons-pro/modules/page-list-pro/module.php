<?php
namespace WprAddonsPro\Modules\PageListPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Page_List_Pro',
		];
	}

	public function get_name() {
		return 'wpr-page-list-pro';
	}
}
