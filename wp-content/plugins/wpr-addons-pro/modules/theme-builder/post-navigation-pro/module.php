<?php
namespace WprAddonsPro\Modules\ThemeBuilder\PostNavigationPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-post-navigation-pro';
	}

	public function get_widgets() {
		return [
			'Wpr_Post_Navigation_Pro', // This should match the widget/element class.
		];
	}
	
}