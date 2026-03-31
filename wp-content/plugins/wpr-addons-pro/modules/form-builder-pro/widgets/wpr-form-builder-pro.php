<?php
namespace WprAddonsPro\Modules\FormBuilderPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Form_Builder_Pro extends \WprAddons\Modules\FormBuilder\Widgets\Wpr_Form_Builder {
	public function submit_action_args() {
		$actions_options = [
            'email' => 'Email',
			'redirect' => 'Redirect',
			'submissions' => 'Submission',
			'mailchimp' => 'Mailchimp',
			'webhook' => 'Webhook'
		];

		return $actions_options;
	}
}