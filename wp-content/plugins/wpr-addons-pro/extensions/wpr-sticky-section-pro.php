<?php
namespace WprAddonsPro\Extensions;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Sticky_Section_Pro {

	public static function add_control_group_sticky_advanced_options($element) {

        $element->add_control(
            'sticky_advanced_options_heading',
            [
                'label' => esc_html__( 'Advanced', 'wpr-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'position_location'	=> 'top'
                ],
            ]
        );

        // All pro
        $element->add_control (
            'sticky_advanced_options',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Enable Advanced Options', 'wpr-addons' ),
                'description' => 'Please note that <strong>Advanced Options</strong> are designed to work only with <strong>Header Sections</strong>.',
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'position_location'	=> 'top'
                ]
            ]
        );

		$element->add_control(
			'sticky_video_tutorial',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => '<a href="https://www.youtube.com/watch?v=ORay3VWrWuc" target="_blank" style="color: #f51f3d;">Watch Video Tutorial</a>',
                'condition' => [
                    'enable_sticky_section' => 'yes'
                ]
			]
		);
        
        $element->add_responsive_control(
            'wpr_sticky_effects_offset',
            [
                'label' => __( 'Scroll Top Distance', 'wpr-addons' ), // SHOULD WORK AFTER STICKING
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Set the distance to start the effect when the Top of the page touches the Sticky section.', 'wpr-addons'),
                'min' => 0,
                'required' => true,
                'frontend_available' => true,
                'render_type' => 'template',
                'default' => 0,
                'widescreen_default' => 0,
                'laptop_default' => 0,
                'tablet_extra_default' => 0,
                'tablet_default' => 0,
                'mobile_extra_default' => 0,
                'mobile_default' => 0,
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_control ( // NEXT HIDDEN SECTION
            'sticky_replace_header',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Replace with New Section', 'wpr-addons' ),
                'description' => esc_html__('After enabling this option, the next section will replace this section when it becomes sticky. The next section will be automatically hidden on page load.', 'wpr-addons'),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top'
                ],
                'prefix_class' => 'wpr-sticky-replace-header-',
                'separator' => 'before',
                'render_type' => 'template',
            ]
        );

        $element->add_control ( // NEXT HIDDEN SECTION
            'sticky_shrink_section',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Custom Height', 'wpr-addons' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before',
                'prefix_class' => 'wpr-sticky-custom-height-'
            ]
        );

        $element->add_responsive_control(
            'sticky_shrink_size',
            [
                'type' => Controls_Manager::SLIDER,
                'label' => esc_html__( 'Section Height', 'wpr-addons' ),
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ]
                ],
                'default' => [
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-header .elementor-container' => 'min-height: {{SIZE}}{{UNIT}} !important;',
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_advanced_options' => 'yes',
                    'sticky_shrink_section' => 'yes',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        $element->add_control (
            'sticky_background',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Custom Colors (Beta)', 'wpr-addons' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before',
                'prefix_class' => 'wpr-sticky-custom-colors-'
            ]
        );

        $element->add_control(
            'sticky_text_color',
            [
                'label' => esc_html__( 'Text Color', 'wpr-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-header *:not(.sub-menu *)' => 'color: {{VALUE}} !important;', // CHECK SELECTORS - LOGO MENU & maybe BUTTON
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_background' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'sticky_link_color',
            [
                'label' => esc_html__( 'Link Color', 'wpr-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-header a:not(.sub-menu a)' => 'color: {{VALUE}} !important;', // CHECK SELECTORS - LOGO MENU & maybe BUTTON
                    '{{WRAPPER}}.wpr-sticky-header a:not(.sub-menu a) *' => 'color: {{VALUE}} !important;'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_background' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        // $element->add_control(
        // 	'sticky_logo_color',
        // 	[
        // 		'label' => esc_html__( 'Logo Color', 'wpr-addons' ),
        // 		'type' => Controls_Manager::COLOR,
        // 		'selectors' => [
        // 			'{{WRAPPER}}.wpr-sticky-header .wpr-logo' => 'color: {{VALUE}} !important;', // CHECK SELECTORS - LOGO MENU & maybe BUTTON
        // 			'{{WRAPPER}}.wpr-sticky-header .wpr-logo *' => 'color: {{VALUE}} !important;'
        // 		],
        // 		'condition' => [
        // 			'sticky_background' => 'yes',
        // 			'sticky_advanced_options' => 'yes'
        // 		]
        // 	]
        // );

        $element->add_control(
            'sticky_background_color',
            [
                'label' => esc_html__( 'Background Color', 'wpr-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-header' => 'background-color: {{VALUE}} !important; z-index: 9999 !important;',
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_background' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        $element->add_control (
            'sticky_logo_scale',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Logo Scale', 'wpr-addons' ), // Show Number Input 0.7 default
                'description' => esc_html__( 'Works with Royal Addons Logo widget.', 'wpr-addons' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before',
                'prefix_class' => 'wpr-sticky-scale-logo-'
            ]
        );

        $element->add_control(
            'sticky_logo_scale_size',
            [
                'label' => esc_html__( 'Logo Size %', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 70,
                'min' => 10,
                'max' => 100,
                'step' => 5,
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-header .wpr-logo-image' => 'width: {{VALUE}}%', // ADD CONTROL FOR ANIMATION TIMINGS
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_logo_scale' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'sticky_trans_duration',
            [
                'label' => esc_html__( 'Transition Time', 'wpr-addons' ),
                'description' => esc_html__('Set a trinsition time for Custom Height animation, Custom Colors and Logo Scale.', 'wpr-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-custom-height-yes' => 'transition: all {{VALUE}}s linear !important;', // ADD CONTROL FOR ANIMATION TIMINGS
                    '{{WRAPPER}}.wpr-sticky-scale-logo-yes .wpr-logo' => 'transition: all {{VALUE}}s linear !important;', // ADD CONTROL FOR ANIMATION TIMINGS
                    '{{WRAPPER}}.wpr-sticky-custom-colors-yes span' => 'transition: all {{VALUE}}s linear !important;',
                    '{{WRAPPER}}.wpr-sticky-custom-colors-yes a' => 'transition: all {{VALUE}}s linear !important;',
                    '{{WRAPPER}}.wpr-sticky-custom-colors-yes button' => 'transition: all {{VALUE}}s linear !important;',
                    '{{WRAPPER}}.wpr-sticky-custom-colors-yes *::before' => 'transition: all {{VALUE}}s linear !important;'
                    // '{{WRAPPER}}' => 'transition: background {{VALUE}}s, border {{VALUE}}s, border-radius {{VALUE}}s, box-shadow {{VALUE}}s;',
                    // '{{WRAPPER}} *' => 'transition: background {{VALUE}}s, border {{VALUE}}s, border-radius {{VALUE}}s, box-shadow {{VALUE}}s;'

                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_control ( // NEXT HIDDEN SECTION
            'wpr_sticky_section_border',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Custom Border', 'wpr-addons' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_control(
            'wpr_sticky_section_border_type',
            [
                'label' => esc_html__( 'Border Type', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__( 'None', 'wpr-addons' ),
                    'solid' => esc_html__( 'Solid', 'wpr-addons' ),
                    'double' => esc_html__( 'Double', 'wpr-addons' ),
                    'dotted' => esc_html__( 'Dotted', 'wpr-addons' ),
                    'dashed' => esc_html__( 'Dashed', 'wpr-addons' ),
                    'groove' => esc_html__( 'Groove', 'wpr-addons' ),
                ],
                'default' => 'none',
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-header' => 'border-style: {{VALUE}};'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'wpr_sticky_section_border' => 'yes'
                ]
            ]
        );

        $element->add_control(
            'wpr_sticky_section_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-header' => 'border-color: {{VALUE}}'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'wpr_sticky_section_border' => 'yes',
                    'wpr_sticky_section_border_type!' => 'none'
                ]
            ]
        );

        $element->add_control(
            'wpr_sticky_section_border_width',
            [
                'label' => esc_html__( 'Border Width', 'wpr-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'default' => [
                    'top' => 1,
                    'right' => 1,
                    'bottom' => 1,
                    'left' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-header' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'wpr_sticky_section_border' => 'yes',
                    'wpr_sticky_section_border_type!' => 'none'
                ]
            ]
        );

        $element->add_control(
            'wpr_sticky_section_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}}.wpr-sticky-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'wpr_sticky_section_border' => 'yes',
                    'wpr_sticky_section_border_type!' => 'none'
                ]
            ]
        );

        $element->add_control ( // NEXT HIDDEN SECTION
            'wpr_sticky_section_bs',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Custom Shadow', 'wpr-addons' ),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wpr_sticky_section_box_shadow',
                'selector' => '{{WRAPPER}}.wpr-sticky-header',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top',
                    'sticky_replace_header!' => 'yes',
                    'wpr_sticky_section_bs' => 'yes'
                ]
            ]
        );

        $element->add_control (
            'sticky_hide',
            [
                'type' => Controls_Manager::SWITCHER,
                'label' => esc_html__( 'Show on Scrolling Up', 'wpr-addons' ),
                'description' => esc_html__('If the section is sticky and page is scrolled Down, this section will be hidden and will only show up when the page is scrolled Up.', 'wpr-addons'),
                'default' => 'no',
                'return_value' => 'yes',
                'condition' => [
                    'enable_sticky_section' => 'yes',
                    'sticky_advanced_options' => 'yes',
                    'position_location'	=> 'top'
                ],
                'separator' => 'before'
            ]
        );

        $element->add_control( // TRANSITION
            'sticky_animation',
            [
                'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => 'None',
                    'fade' => 'Fade',
                    'slide' => 'Slide'
                ],
                'frontend_available' => true,
				'conditions' => [
       		    	'relation' => 'and',
					'terms' => [
						[
							'name' => 'enable_sticky_section',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'sticky_advanced_options',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'position_location',
							'operator' => '=',
							'value' => 'top',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'sticky_replace_header',
									'operator' => '=',
									'value' => 'yes',
								],
								[
									'name' => 'sticky_hide',
									'operator' => '=',
									'value' => 'yes',
								],
							],
						],
					],
				],
                'separator' => 'before',
                'render_type' => 'template'
            ]
        );

        $element->add_control(
            'sticky_animation_duration',
            [
                'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 0.3,
                'min' => 0,
                'max' => 5,
                'step' => 0.1,
                'selectors' => [
                    '{{WRAPPER}}' => '--wpr-animation-duration: {{VALUE}}s', // ADD CONTROL FOR ANIMATION TIMINGS

                ],
				'conditions' => [
       		    	'relation' => 'and',
					'terms' => [
						[
							'name' => 'enable_sticky_section',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'sticky_advanced_options',
							'operator' => '=',
							'value' => 'yes',
						],
						[
							'name' => 'position_location',
							'operator' => '=',
							'value' => 'top',
						],
						[
							'name' => 'sticky_animation',
							'operator' => '!=',
							'value' => 'none',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'name' => 'sticky_replace_header',
									'operator' => '=',
									'value' => 'yes',
								],
								[
									'name' => 'sticky_hide',
									'operator' => '=',
									'value' => 'yes',
								],
							],
						],
					],
				],
                'render_type' => 'template',
            ]
        );
	}

}