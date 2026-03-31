<?php
namespace WprAddonsPro\Modules\TwitterFeedPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Twitter_Feed_Pro',
		];
	}

	public function get_name() {
		return 'wpr-twitter-feed-pro';
	}
}