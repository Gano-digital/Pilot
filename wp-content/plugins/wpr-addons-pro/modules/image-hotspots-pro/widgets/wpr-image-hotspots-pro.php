<?php
namespace WprAddonsPro\Modules\ImageHotspotsPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Image_Hotspots_Pro extends \WprAddons\Modules\ImageHotspots\Widgets\Wpr_Image_Hotspots {

	public function add_control_tooltip_trigger() {
		$this->add_control(
			'tooltip_trigger',
			[
				'label' => esc_html__( 'Show Tooltips', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hover',
				'options' => [
					'none' => esc_html__( 'by Default', 'wpr-addons' ),
					'click' => esc_html__( 'on Click', 'wpr-addons' ),
					'hover' => esc_html__( 'on Hover', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-hotspot-trigger-',
				'render_type' => 'template',
				'separator' => 'after',
			]
		);
	}

	public function add_control_tooltip_position() {
		$this->add_control(
			'tooltip_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-hotspot-tooltip-position-',
				'render_type' => 'template',
			]
		);
	}

}