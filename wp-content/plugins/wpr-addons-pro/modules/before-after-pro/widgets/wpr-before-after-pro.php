<?php
namespace WprAddonsPro\Modules\BeforeAfterPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Before_After_Pro extends \WprAddons\Modules\BeforeAfter\Widgets\Wpr_Before_After {

	public function add_control_direction() {
		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
					'vertical' => esc_html__( 'Vertical', 'wpr-addons' ),
				],
				'default' => 'horizontal',
				'separator' => 'before',
			]
		);
	}

	public function add_control_trigger() {
		$this->add_control(
			'trigger',
			[
				'label' => esc_html__( 'Trigger', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'mouse' => esc_html__( 'Mouse Hover', 'wpr-addons' ),
					'drag' => esc_html__( 'Click & Drag', 'wpr-addons' ),
				],
				'default' => 'drag',
			]
		);
	}

	public function add_control_divider_position() {
		$this->add_control(
			'divider_position',
			[
				'label' => esc_html__( 'Divider Position', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 50,
				'min' => 0,
				'max' => 100,
				'step' => 1,
			]
		);
	}

	public function add_control_label_display() {
		$this->add_control(
			'label_display',
			[
				'label' => esc_html__( 'Display', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'default' => esc_html__( 'by Default', 'wpr-addons' ),
					'hover' => esc_html__( 'on Hover', 'wpr-addons' ),
				],
				'default' => 'default',
				'separator' => 'after',
			]
		);
	}

}