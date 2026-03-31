<?php
namespace WprAddonsPro\Modules\ImageHotspotsPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Image_Hotspots_Pro',
		];
	}

	public function get_name() {
		return 'wpr-image-hotspots-pro';
	}
}
