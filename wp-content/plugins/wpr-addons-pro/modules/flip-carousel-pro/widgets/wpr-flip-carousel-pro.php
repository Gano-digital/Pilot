<?php
namespace WprAddonsPro\Modules\FlipCarouselPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Flip_Carousel_Pro extends \WprAddons\Modules\FlipCarousel\Widgets\Wpr_Flip_Carousel {

	public function add_controls_group_autoplay() {
		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'autoplay_milliseconds',
			[
				'label' => __( 'Autoplay Interval', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 500,
				'default' => 3000,
				'step' => 20,
				'condition' => [
					'autoplay' => 'yes'
				]
			]
		);
		
		$this->add_control(
			'pause_on_hover',
			[
				'label' => __( 'Pause on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator' => 'before',
				'default' => 'yes',
			]
		);
	}

}