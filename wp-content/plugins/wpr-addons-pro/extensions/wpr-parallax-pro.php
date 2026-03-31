<?php
namespace WprAddonsPro\Extensions;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Parallax_Scroll_Pro {

	public static function add_control_scroll_effect($element) {
        $element->add_control(
			'scroll_effect',
			[
				'label' => __( 'Scrolling Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'scroll',
				'options' => [
					'scroll' => esc_html__( 'Scroll', 'wpr-addons' ),
					'scale'  => esc_html__( 'Zoom', 'wpr-addons' ),
					'opacity' => esc_html__( 'Opacity', 'wpr-addons' ),
                    'scale-opacity' => esc_html__('Scale Opacity', 'wpr-addons'),
					'scroll-opacity' => esc_html__( 'Scroll Opacity', 'wpr-addons' )
				],
                'render_type' => 'template',
                'condition' => [
                    'wpr_enable_jarallax' => 'yes'
                ]
			]
		);
	}

}
