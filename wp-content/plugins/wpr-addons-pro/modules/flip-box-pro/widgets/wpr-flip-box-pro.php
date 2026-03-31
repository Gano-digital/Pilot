<?php
namespace WprAddonsPro\Modules\FlipBoxPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Flip_Box_Pro extends \WprAddons\Modules\FlipBox\Widgets\Wpr_Flip_Box {

	public function add_control_front_trigger() {
		$this->add_control(
			'front_trigger',
			[
				'label' => esc_html__( 'Trigger', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'btn' => esc_html__( 'Button', 'wpr-addons' ),
					'box' => esc_html__( 'Box', 'wpr-addons' ),
					'hover' => esc_html__( 'Hover', 'wpr-addons' ),
				],
				'separator' => 'before',
			]
		);
	}

	public function add_control_back_link_type() {
		$this->add_control(
			'back_link_type',
			[
				'label' => esc_html__( 'Link Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'title' => esc_html__( 'Title', 'wpr-addons' ),
					'btn' => esc_html__( 'Button', 'wpr-addons' ),
					// 'btn-title' => esc_html__( 'Title & Button', 'wpr-addons' ), TODO: add or remove?
					'box' => esc_html__( 'Box', 'wpr-addons' ),
				],
				'default' => 'btn',
				'separator' => 'before',
			]
		);
	}

	public function add_control_box_animation() {
		$this->add_control(
			'box_animation',
			[
				'label' => esc_html__( 'Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'flip',
				'options' => [
		     		'fade'     => esc_html__( 'Fade', 'wpr-addons' ),
					'flip'     => esc_html__( 'Flip', 'wpr-addons' ),
		     		'slide'    => esc_html__( 'Slide', 'wpr-addons' ),
		     		'push'     => esc_html__( 'Push', 'wpr-addons' ),
		     		'zoom-in'  => esc_html__( 'Zoom In', 'wpr-addons' ),
		     		'zoom-out' => esc_html__( 'Zoom Out', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-flip-box-animation-',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);
	}

}