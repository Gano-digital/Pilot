<?php
namespace WprAddonsPro\Modules\PopupTriggerPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Popup_Trigger_Pro extends \WprAddons\Modules\PopupTrigger\Widgets\Wpr_Popup_Trigger {

	public function add_control_popup_trigger_show_again_delay() {
		$this->add_control(
			'popup_trigger_show_again_delay',
			[
				'label'   => esc_html__( 'Show Again Delay', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'No Delay', 'wpr-addons' ),
					'60000' => esc_html__( '1 Minute', 'wpr-addons' ),
					'180000' => esc_html__( '3 Minute', 'wpr-addons' ),
					'300000' => esc_html__( '5 Minute', 'wpr-addons' ),
					'600000' => esc_html__( '10 Minute', 'wpr-addons' ),
					'1800000' => esc_html__( '30 Minute', 'wpr-addons' ),
					'3600000' => esc_html__( '1 Hour', 'wpr-addons' ),
					'10800000' => esc_html__( '3 Hour', 'wpr-addons' ),
					'21600000' => esc_html__( '6 Hour', 'wpr-addons' ),
					'43200000' => esc_html__( '12 Hour', 'wpr-addons' ),
					'86400000' => esc_html__( '1 Day', 'wpr-addons' ),
					'259200000' => esc_html__( '3 Days', 'wpr-addons' ),
					'432000000' => esc_html__( '5 Days', 'wpr-addons' ),
					'604800000' => esc_html__( '7 Days', 'wpr-addons' ),
					'864000000' => esc_html__( '10 Days', 'wpr-addons' ),
					'1296000000' => esc_html__( '15 Days', 'wpr-addons' ),
					'1728000000' => esc_html__( '20 Days', 'wpr-addons' ),
					'2628000000' => esc_html__( '1 Month', 'wpr-addons' ),
				],
				'description' => esc_html__( 'This option determines when to show popup again to a visitor after it is closed.', 'wpr-addons' ),
				'separator' => 'before',
				'condition' => [
					'popup_trigger_type!' => 'close-permanently'
				]
			]
		);
	}

}