<?php

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Popup_Pro extends Wpr_Popup {

	public function add_control_popup_trigger() {
		$this->add_control(
			'popup_trigger',
			[
				'label'   => esc_html__( 'Open Popup', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'load',
				'options' => [
					'load' => esc_html__( 'On Page Load', 'wpr-addons' ),
					'scroll' => esc_html__( 'On Page Scroll', 'wpr-addons' ),
					'element-scroll' => esc_html__( 'On Scroll to Element', 'wpr-addons' ),
					'date' => esc_html__( 'After Specific Date', 'wpr-addons' ),
					'inactivity'  => esc_html__( 'After User Inactivity', 'wpr-addons' ),
					'exit' => esc_html__( 'After User Exit Intent', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom Trigger (Button Click)', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_popup_show_again_delay() {
		$this->add_control(
			'popup_show_again_delay',
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
				'separator' => 'before'
			]
		);
	}

	public function add_controls_group_popup_settings() {

		$this->add_control(
			'popup_stop_after_date',
			[
				'label' => esc_html__( 'Stop Showing After Date', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_stop_after_date_select',
			[
				'label' => esc_html__( 'Select Date', 'wpr-addons' ),
				'label_block' => false,
				'type' => Controls_Manager::DATE_TIME,
				'default' => date( 'Y-m-d H:i', strtotime( '+1 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				'description' => sprintf( __( 'Set according to your WordPress timezone: %s.', 'wpr-addons' ), Elementor\Utils::get_timezone_string() ),
				'condition' => [
					'popup_stop_after_date!' => '',
				],
			]
		);

		$this->add_control(
			'popup_automatic_close_switch',
			[
				'label' => esc_html__( 'Automatic Closing Delay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_automatic_close_delay',
			[
				'label' => esc_html__( 'Set Closing Delay (sec)', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'condition' => [
					'popup_automatic_close_switch!' => '',
				],
			]
		);

		$this->add_control(
			'popup_disable_esc_key',
			[
				'label' => esc_html__( 'Prevent Closing on "ESC" Key', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_show_for_roles',
			[
				'label' => esc_html__( 'Show For Roles', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT2,
				'options' => Utilities::get_user_roles(),
				'multiple' => 'true',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_show_via_referral',
			[
				'label' => esc_html__( 'Show according to URL Keyword', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'popup_referral_keyword',
			[
				'label' => esc_html__( 'Enter Keyword', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'description' => 'Popup will show up if the URL contains this Keyword.',
				'condition' => [
					'popup_show_via_referral' => 'yes',
				]
			]
		);

		$this->add_responsive_control(
			'popup_show_on_device',
			[
				'label' => esc_html__( 'Show on this Device', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'widescreen_default' => 'yes',
				'laptop_default' => 'yes',
				'tablet_extra_default' => 'yes',
				'tablet_default' => 'yes',
				'mobile_extra_default' => 'yes',
				'mobile_default' => 'yes',
				'separator' => 'before'
			]
		);
	}

}