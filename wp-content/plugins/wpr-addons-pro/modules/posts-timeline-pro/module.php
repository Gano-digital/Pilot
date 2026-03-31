<?php
namespace WprAddonsPro\Modules\PostsTimelinePro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Posts_Timeline_Pro',
		];
	}

	public function get_name() {
		return 'wpr-posts-timeline-pro';
	}
}
