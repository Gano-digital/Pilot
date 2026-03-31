<?php
namespace WprAddonsPro\Modules\ThemeBuilder\PostCommentsPro;

use WprAddonsPro\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-post-comments-pro';
	}

	public function get_widgets() {
		return [
			'Wpr_Post_Comments_Pro', // This should match the widget/element class.
		];
	}
	
}