<?php
namespace WprAddonsPro\Modules\TeamMemberPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Team_Member_Pro',
		];
	}

	public function get_name() {
		return 'wpr-team-member-pro';
	}
}
