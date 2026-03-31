<?php
namespace WprAddonsPro\Extensions;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Particles_Pro {

	public static function add_control_which_particle($element) {
		$element->add_control (
			'which_particle',
			[
				'label' => __( 'Select Style', 'plugin-domain' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'wpr_particle_json_custom',
				'options' => [
					'wpr_particle_json_custom'  => __( 'Custom', 'plugin-domain' ),
					'wpr_particle_json' => __( 'Predefined', 'plugin-domain' ),
				],
				'condition' => [
					'wpr_enable_particles' => 'yes'
				]
			]
		);
	}

	public static function add_control_group_predefined_particles($element) {
		$element->add_control (
			'wpr_particle_json',
			[
				'label' => __( 'Select Effect', 'plugin-domain' ),
				'type' => Controls_Manager::SELECT,
				'default' => Wpr_Particles_Pro::array_of_particles()['default'],
				'options' => [
					Wpr_Particles_Pro::array_of_particles()['default']  => esc_html__( 'Default', 'plugin-domain' ),

					Wpr_Particles_Pro::array_of_particles()['snow'] => esc_html__( 'Snow', 'plugin-domain' ),
	
					Wpr_Particles_Pro::array_of_particles()['nasa'] => esc_html__('Nasa', 'wpr-addons'),
				],
				'condition'   => [
					'which_particle' => 'wpr_particle_json',
					'wpr_enable_particles' => 'yes'
				]
			]
		);

		$element->add_control (
			'particles_shape',
			[
				'label' => __( 'Select Shape', 'plugin-domain' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => [
					'circle' => esc_html__( 'Circle', 'plugin-domain' ),

					'edge' => esc_html__( 'Edge', 'plugin-domain' ),

					'triangle' => esc_html__( 'Triangle', 'plugin-domain' ),

					'polygon' => esc_html__('Polygon', 'wpr-addons'),

					'star' => esc_html__('Star', 'wpr-addons'),
				],
				'condition'   => [
					'which_particle' => 'wpr_particle_json',
					'wpr_enable_particles' => 'yes'
				]
			]
		);

		$element->add_control(
			'quantity',
			[
				'label' => __( 'Particles Quantity', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 600,
				'step' => 5,
				'default' => 80,
				'render_type' => 'template',
				'condition'   => [
					'which_particle' => 'wpr_particle_json',
					'wpr_enable_particles' => 'yes'
				]
			]
		);

		$element->add_control(
			'particles_size',
			[
				'label' => __( 'Particles Size', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 500,
				'step' => 2,
				'default' => 3,
				'render_type' => 'template',
				'condition'   => [
					'which_particle' => 'wpr_particle_json',
					'wpr_enable_particles' => 'yes'
				]
			]
		);

		$element->add_control(
			'particles_speed',
			[
				'label' => __( 'Animation Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 3,
				'default' => 6,
				'render_type' => 'template',
				'condition'   => [
					'which_particle' => 'wpr_particle_json',
					'wpr_enable_particles' => 'yes'
				]
			]
		);

		$element->add_control(
			'particles_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'condition'   => [
					'which_particle' => 'wpr_particle_json',
					'wpr_enable_particles' => 'yes'
				]
			]
		);
	}

	public static function array_of_particles() {  
		return [
			'default' => '{"particles":{"number":{"value":80,"density":{"enable":true,"value_area":800}},"color":{"value":"#000000"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":false,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":150,"color":"#000000","opacity":0.4,"width":1},"move":{"enable":true,"speed":6,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"window","events":{"onhover":{"enable":true,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
			'snow' => '{"particles":{"number":{"value":400,"density":{"enable":true,"value_area":800}},"color":{"value":"#000000"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":10,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":false,"distance":500,"color":"#000000","opacity":0.4,"width":2},"move":{"enable":true,"speed":6,"direction":"bottom","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"window","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":0.5}},"bubble":{"distance":400,"size":4,"duration":0.3,"opacity":1,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
			'bubble' => '{"particles":{"number":{"value":6,"density":{"enable":true,"value_area":800}},"color":{"value":"#000000"},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":6},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.3,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":160,"random":false,"anim":{"enable":true,"speed":10,"size_min":40,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#000000","opacity":1,"width":2},"move":{"enable":true,"speed":8,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"window","events":{"onhover":{"enable":false,"mode":"grab"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
			'nasa' => '{"particles":{"number":{"value":160,"density":{"enable":true,"value_area":800}},"color":{"value":"#000000"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":1,"random":true,"anim":{"enable":true,"speed":1,"opacity_min":0,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":4,"size_min":0.3,"sync":false}},"line_linked":{"enable":false,"distance":150,"color":"#000000","opacity":0.4,"width":1},"move":{"enable":true,"speed":2,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":600}}},"interactivity":{"detect_on":"window","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":250,"size":0,"duration":2,"opacity":0,"speed":3},"repulse":{"distance":400,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true}',
		];
	}
	
}
