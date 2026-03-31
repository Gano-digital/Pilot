<?php
namespace WprAddonsPro\Modules\ThemeBuilder\ArchiveTitlePro;

use WprAddonsPro\Base\Module_Base;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'wpr-archive-title-pro';
	}

	public function get_widgets() {
		return [
			'Wpr_Archive_Title_Pro', // This should match the widget/element class.
		];
	}
	
}