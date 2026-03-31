<?php
namespace WprAddonsPro\Modules\OffcanvasPro\Widgets;

use Elementor;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Widget_Base;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Offcanvas_Pro extends \WprAddons\Modules\Offcanvas\Widgets\Wpr_Offcanvas {

	public function add_control_offcanvas_position() {
		$this->add_control(
            'offcanvas_position',
            [
                'label'        => esc_html__('Position', 'wpr-addons'), 
                'type'         => Controls_Manager::SELECT,
                'label_block'  => false,
                'default'      => 'right',
				'render_type' => 'template',
                'options'      => [
                    'right' => esc_html__('Right', 'wpr-addons'),
                    'left'  => esc_html__('Left', 'wpr-addons'),
                    'top'   => esc_html__('Top', 'wpr-addons'),
                    'bottom'  => esc_html__('Bottom', 'wpr-addons'),
                    'middle'  => esc_html__('Middle', 'wpr-addons'),
                    'relative'  => esc_html__('Relative', 'wpr-addons')
				]
            ]
        );
	}

	public function add_responsive_control_offcanvas_box_width() {
		$this->add_responsive_control(
			'offcanvas_box_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vw'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 3000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-content' => 'width: {{SIZE}}{{UNIT}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-offcanvas-wrap.wpr-offcanvas-wrap-relative' => 'width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'offcanvas_position' => ['left', 'right', 'middle', 'relative']
				]
			]
		);
	}

	public function add_responsive_control_offcanvas_box_height() {
		$this->add_responsive_control(
			'offcanvas_box_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'vh'],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 3000,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					]
				],
				'default' => [
					'unit' => 'vh',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-offcanvas-content' => 'height: {{SIZE}}{{UNIT}};',
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'height: {{SIZE}}{{UNIT}};',
					'.wpr-offcanvas-wrap-{{ID}}.wpr-offcanvas-content-wrap' => 'height: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'offcanvas_position' => ['top', 'bottom', 'middle', 'relative']
				]
			]
		);
	}

	public function add_control_offcanvas_entrance_animation() {
		$this->add_control(
			'offcanvas_entrance_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'wpr-addons' ),
				'description' => esc_html__('Only fade animation works with Position Relative', 'wpr-addons'),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
					'grow' => esc_html__( 'Grow', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-offcanvas-entrance-animation-'
			]
		);
	}

	public function add_control_offcanvas_entrance_type() {
		$this->add_control(
			'offcanvas_entrance_type',
			[
				'label' => esc_html__( 'Entrance Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'options' => [
					'cover' => esc_html__( 'Cover', 'wpr-addons' ),
					'push' => esc_html__( 'Push', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-offcanvas-entrance-type-',
				'default' => 'cover',
				'condition' => [
					'offcanvas_position' => ['top', 'left', 'right'],
					// 'offcanvas_entrance_animation' => ['slide', 'grow']
				]
			]
		);
	}

	public function add_control_offcanvas_animation_duration() {
		$this->add_control(
			'offcanvas_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'render_type' => 'template',
				'default' => 0.6,
				'min' => 0,
				'max' => 15,
				'step' => 0.1,
				'selectors' => [
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-content' => 'animation-duration: {{VALUE}}s !important',
					'{{WRAPPER}} .wpr-offcanvas-content' => 'animation-duration: {{VALUE}}s !important',
					// '.wpr-offcanvas-wrap-{{ID}}' => 'transition-duration: {{VALUE}}s !important',
					// '{{WRAPPER}} .wpr-offcanvas-wrap' => 'transition-duration: {{VALUE}}s !important',
					// '.wpr-offcanvas-wrap-{{ID}}.wpr-offcanvas-wrap-active' => 'transition-duration: {{VALUE}}s !important',
					// '{{WRAPPER}} .wpr-offcanvas-wrap-active' => 'transition-duration: {{VALUE}}s !important',
				]
			]
		);
	}

	public function add_control_offcanvas_open_by_default() {
		$this->add_control(
			'offcanvas_open_by_default',
			[
				'label' => esc_html__( 'Open by Default', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template'
				// 'separator' => 'before',
			]
		);
	}

	public function add_control_offcanvas_reverse_header () {
		$this->add_control(
			'offcanvas_reverse_header',
			[
				'label' => esc_html__( 'Reverse Header', 'wpr-addons' ),
				'description' => esc_html__('Reverse Close Icon and Title Locations'),
				'type' => Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'prefix_class' => 'wpr-offcanvas-reverse-header-',
				'selectors_dictionary' => [
					'yes' => 'flex-direction: row-reverse'
				],
				'selectors' => [
					'.wpr-offcanvas-wrap-{{ID}} .wpr-offcanvas-header' => '{{VALUE}}',
				]
			]
		);
	}

	// public function add_control_offcanvas_button_icon() {
	// 	$this->add_control(
	// 		'offcanvas_button_icon',
	// 		[
	// 			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
	// 			'type' => Controls_Manager::ICONS,
	// 			'skin' => 'inline',
	// 			'label_block' => false,
	// 			'default' => [
	// 				'value' => 'fas fa-bars',
	// 				'library' => 'fa-solid',
	// 			],
	// 			'condition' => [
	// 				'offcanvas_show_button_icon' => 'yes'
	// 			]
	// 		]
	// 	);
	// }
}