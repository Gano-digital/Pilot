<?php
namespace WprAddonsPro\Modules\CountdownPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Countdown_Pro extends \WprAddons\Modules\Countdown\Widgets\Wpr_Countdown {

	public function add_control_countdown_type() {
		$this->add_control(
			'countdown_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'due-date',
				'options' => [
					'due-date' => esc_html__( 'Due Date', 'wpr-addons' ),
					'evergreen' => esc_html__( 'Evergreen', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_evergreen_hours() {
		$this->add_control(
			'evergreen_hours',
			[
				'label' => esc_html__( 'Hours', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'step' => 1,
				'condition' => [
					'countdown_type' => 'evergreen',
				],
			]
		);
	}

	public function add_control_evergreen_minutes() {
		$this->add_control(
			'evergreen_minutes',
			[
				'label' => esc_html__( 'Minutes', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 10,
				'min' => 0,
				'step' => 1,
				'condition' => [
					'countdown_type' => 'evergreen',
				],
			]
		);
	}

	public function add_control_evergreen_show_again_delay() {
		$this->add_control(
			'evergreen_show_again_delay',
			[
				'label'   => esc_html__( 'Show Again Delay', 'wpr-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '0',
				'options' => [
					'0' => esc_html__( 'No Delay', 'wpr-addons' ),
					'10000' => esc_html__( '10 Seconds', 'wpr-addons' ),//tmp
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
					'604800000' => esc_html__( '7 Days', 'wpr-addons' ),
					'2628000000' => esc_html__( 'Month', 'wpr-addons' ),
				],
				'separator' => 'before',
				'condition' => [
					'countdown_type' => 'evergreen',
				],
			]
		);
	}

	public function add_control_evergreen_stop_after_date() {
		$this->add_control(
			'evergreen_stop_after_date',
			[
				'label' => esc_html__( 'Stop Showing after Date', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'condition' => [
					'countdown_type' => 'evergreen',
				],
			]
		);
	}

	public function add_control_evergreen_stop_after_date_select() {
		$this->add_control(
			'evergreen_stop_after_date_select',
			[
				'label' => esc_html__( 'Select Date', 'wpr-addons' ),
				'label_block' => false,
				'type' => Controls_Manager::DATE_TIME,
				'default' => date( 'Y-m-d H:i', strtotime( '+1 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) ),
				'description' => sprintf( __( 'Set according to your WordPress timezone: %s.', 'wpr-addons' ), Utils::get_timezone_string() ),
				'condition' => [
					'evergreen_stop_after_date!' => '',
					'countdown_type' => 'evergreen',
				],
			]
		);
	}

	public function get_evergreen_interval( $settings ) {
		return  ( $settings['evergreen_hours'] * HOUR_IN_SECONDS ) + ( $settings['evergreen_minutes'] * MINUTE_IN_SECONDS );
	}
	
}