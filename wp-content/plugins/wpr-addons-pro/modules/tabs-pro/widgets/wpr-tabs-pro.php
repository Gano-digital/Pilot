<?php
namespace WprAddonsPro\Modules\TabsPro\Widgets;

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Tabs_Pro extends \WprAddons\Modules\Tabs\Widgets\Wpr_Tabs {

	public function add_repeater_args_tab_custom_color() {
		return [
			'label' => esc_html__( 'Use Custom Color', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
		];
	}

	public function add_repeater_args_tab_content_type() {
		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
			$options = [
                'editor' => esc_html__( 'Editor', 'wpr-addons' ),
                'acf' => esc_html__( 'Custom Field', 'wpr-addons' ),
                'template' => esc_html__( 'Elementor Template', 'wpr-addons' ),
            ];
		} else {
			$options = [
                'editor' => esc_html__( 'Editor', 'wpr-addons' ),
                'pro-cf' => esc_html__( 'Custom Field (Expert)', 'wpr-addons' ),
                'template' => esc_html__( 'Elementor Template', 'wpr-addons' ),
            ];
		}

		return [
            'label' => esc_html__( 'Content Type', 'wpr-addons' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'editor',
            'options' => $options,
			'separator' => 'before',
        ];
	}

	public function add_control_tabs_hr_position() {
		$this->add_control(
            'tabs_hr_position',
            [
                'label' => esc_html__( 'Horizontal Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'justify',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                    'justify' => [
						'title' => esc_html__( 'Stretch', 'wpr-addons' ),
						'icon' => 'eicon-h-align-stretch',
					],
                ],
				'prefix_class' => 'wpr-tabs-hr-position-',
				'render_type' => 'template',
				'condition' => [
					'tabs_position' => 'above',
				],
            ]
        );
	}

	public function add_section_settings() {
		// CSS Selectors
		$css_selector = [
			'general' => '> .elementor-widget-container > .wpr-tabs',
			'control_list' => '> .elementor-widget-container > .wpr-tabs > .wpr-tabs-wrap > .wpr-tab',
			'content_wrap' => '> .elementor-widget-container > .wpr-tabs > .wpr-tabs-content-wrap',
			'content_list' => '> .elementor-widget-container > .wpr-tabs > .wpr-tabs-content-wrap > .wpr-tab-content',
			'control_icon' => '.wpr-tab-icon',
			'control_image' => '.wpr-tab-image',
		];

		if ( ! $this->has_widget_inner_wrapper() ) {
			$css_selector['general'] = '> .wpr-tabs';
			$css_selector['control_list'] = '> .wpr-tabs > .wpr-tabs-wrap > .wpr-tab';
			$css_selector['content_wrap'] = '> .wpr-tabs > .wpr-tabs-content-wrap';
			$css_selector['content_list'] = '> .wpr-tabs > .wpr-tabs-content-wrap > .wpr-tab-content';
		}

		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'tabs_trigger',
			[
				'label' => esc_html__( 'Trigger', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'click',
				'options' => [
					'click' => esc_html__( 'Click', 'wpr-addons' ),
					'hover' => esc_html__( 'Hover', 'wpr-addons' ),
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'active_tab',
			[
				'label' => esc_html__( 'Active Tab Index', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'min' => 1,
				'default' => 1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'content_animation',
			[
				'label' => esc_html__( 'Content Animation', 'wpr-addons' ),
				'type' => 'wpr-animations-alt',
				'default' => 'fade-in',
			]
		);
		
		$this->add_control(
			'content_anim_size',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'default' => 'large',
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'condition' => [
					'content_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} '. $css_selector['content_list']. ' > .wpr-tab-content-inner' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-tabs-triangle-type-inner '. $css_selector['control_list'] .':before' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s',
				],
				'condition' => [
					'content_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'autoplay_duration',
			[
				'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 0,
				'max' => 15,
				'step' => 0.1,
				'frontend_available' => true,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
	}
	
}