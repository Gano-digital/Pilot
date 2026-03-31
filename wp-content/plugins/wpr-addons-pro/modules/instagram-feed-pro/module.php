<?php
namespace WprAddonsPro\Modules\InstagramFeedPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Instagram_Feed_Pro',
		];
	}

	public function get_name() {
		return 'wpr-instagram-feed-pro';
	}
}