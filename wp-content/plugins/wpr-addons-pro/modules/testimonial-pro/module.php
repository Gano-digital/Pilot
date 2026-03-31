<?php
namespace WprAddonsPro\Modules\TestimonialPro;

use WprAddonsPro\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'Wpr_Testimonial_Carousel_Pro',
		];
	}

	public function get_name() {
		return 'wpr-testimonial-pro';
	}
}
