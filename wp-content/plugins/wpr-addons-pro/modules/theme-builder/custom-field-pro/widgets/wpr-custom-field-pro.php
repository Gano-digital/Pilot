<?php
namespace WprAddonsPro\Modules\ThemeBuilder\CustomFieldPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use Elementor\Repeater;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Wpr_Custom_Field_Pro extends Widget_Base {
	
	public function get_name() {
		return 'wpr-custom-field-pro';
	}

	public function get_title() {
		return esc_html__( 'Custom Field', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-database';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('single') ? [ 'wpr-theme-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'custom field', 'custom-field', 'dynamic field', 'dynamic-field', 'meta', 'meta field', 'meta-field' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

    protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_custom_field',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'custom_fields_list',
			[
				'label' => esc_html__( 'Select Custom Field', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'label_block' => true,
				'default' => 'default',
				'options' => 'ajaxselect2/get_custom_meta_keys',
			]
		);

		// $this->add_control(
		// 	'custom_fields_source',
		// 	[
		// 		'label' => esc_html__( 'Source', 'wpr-addons' ),
		// 		'type' => Controls_Manager::SELECT,
		// 		'label_block' => true,
		// 		'default' => 'default',
		// 		'options' => [
		// 			'default' => esc_html__( 'Default', 'wpr-addons' ),
		// 			'acf' => esc_html__( 'ACF', 'wpr-addons' ),
		// 		],
		// 	]
		// );

		$this->add_control(
			'custom_fields_callback',
			[
				'label' => esc_html__( 'Callback (Filter)', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'checkbox' => esc_html__( 'Checkbox', 'wpr-addons' ),
					'truefalse' => esc_html__( 'TrueFalse', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-custom-field-callback-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'custom_field_tf_enabled_text',
			[
				'label' => esc_html__( 'Text if Enabled', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
                'condition' => [
                    'custom_fields_callback' => 'truefalse',
                ],
			]
		);

		$this->add_control(
			'custom_field_tf_disabled_text',
			[
				'label' => esc_html__( 'Text if Disabled', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
                'condition' => [
                    'custom_fields_callback' => 'truefalse',
                ],
			]
		);

		$this->add_control(
			'custom_field_hide_empty',
			[
				'label' => esc_html__( 'Hide if Value is Empty', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);

		$this->add_control(
			'custom_field_extra_icon',
			[
				'label' => esc_html__( 'Extra Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_field_extra_text',
			[
				'label' => esc_html__( 'Extra Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
			]
		);

		$this->add_responsive_control(
            'custom_field_align',
            [
                'label' => esc_html__( 'Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'wpr-addons' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-text-align-right',
                    ]
                ],
				'selectors' => [
					'{{WRAPPER}} .wpr-custom-field' => 'justify-content: {{VALUE}}',
				],
				'prefix_class' => 'wpr-post-info-align-',
				'separator' => 'before'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_custom_field1',
            [
                'label' => esc_html__( 'Custom Field Style', 'wpr-addons' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'custom_field1_color',
            [
                'label'  => esc_html__( 'Color', 'wpr-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#9C9C9C',
                'selectors' => [
                    '{{WRAPPER}} .wpr-custom-field .wpr-custom-field-value' => 'color: {{VALUE}}',
                    '{{WRAPPER}}.wpr-custom-field-callback-checkbox .wpr-custom-field' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'custom_field1_bg_color',
            [
                'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wpr-custom-field' => 'background-color: {{VALUE}}',
                ]
            ]
        );

        $this->add_control(
            'custom_field1_border_color',
            [
                'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#E8E8E8',
                'selectors' => [
                    '{{WRAPPER}} .wpr-custom-field' => 'border-color: {{VALUE}}',
                ],
                'separator' => 'after',
            ]
        );

        // $this->add_control(
        //     'custom_field1_transition_duration',
        //     [
        //         'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
        //         'type' => Controls_Manager::NUMBER,
        //         'default' => 0.1,
        //         'min' => 0,
        //         'max' => 5,
        //         'step' => 0.1,
        //         'selectors' => [
        //             '{{WRAPPER}} .wpr-grid-cf-style-1 .inner-block a' => 'transition-duration: {{VALUE}}s',
        //         ],
        //         'separator' => 'after',
        //     ]
        // );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'custom_field1_typography',
                'selector' => '{{WRAPPER}} .wpr-custom-field .wpr-custom-field-value, {{WRAPPER}}.wpr-custom-field-callback-checkbox .wpr-custom-field',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
            ]
        );

        $this->add_control(
            'custom_field1_border_type',
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
                    '{{WRAPPER}} .wpr-custom-field' => 'border-style: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'custom_field1_border_width',
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
                    '{{WRAPPER}} .wpr-custom-field' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'condition' => [
                    'custom_field1_border_type!' => 'none',
                ],
            ]
        );

        $this->add_responsive_control(
            'custom_field1_padding',
            [
                'label' => esc_html__( 'Padding', 'wpr-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-custom-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'custom_field1_margin',
            [
                'label' => esc_html__( 'Margin', 'wpr-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'left' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-custom-field' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'custom_field1_radius',
            [
                'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'default' => [
                    'top' => 2,
                    'right' => 2,
                    'bottom' => 2,
                    'left' => 2,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-custom-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ]
            ]
        );

        $this->end_controls_section();

		// Styles ====================
		// Section: Extra Icon -------
		$this->start_controls_section(
			'section_style_custom_field_icon',
			[
				'label' => esc_html__( 'Extra Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
                'condition' => [
                    'custom_field_extra_icon!' => ''
                ]
			]
		);

		$this->add_control(
			'custom_field_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-custom-field i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-custom-field svg' => 'fill: {{VALUE}}'
				],
				'separator' => 'after'
			]
		);

		$this->add_responsive_control(
			'custom_field_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 16
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-custom-field i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-custom-field svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->add_responsive_control(
			'custom_field_icon_space',
			[
				'label' => esc_html__( 'Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 5
				],
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-custom-field i' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-custom-field svg' => 'margin-right: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_section();

		// Styles ====================
		// Section: Extra Text -------
		$this->start_controls_section(
			'section_style_custom_field_text',
			[
				'label' => esc_html__( 'Extra Text', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
                'condition' => [
                    'custom_field_extra_text!' => ''
                ]
			]
		);

		$this->add_control(
			'custom_field_text_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					// '{{WRAPPER}} .wpr-post-info li:not(.wpr-post-info-custom-field) .wpr-post-info-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-custom-field span' => 'color: {{VALUE}}'
				],
				'separator' => 'after'
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'custom_field_extra_text_typography',
				'label' => esc_html__('Typography', 'wpr-addons'),
				'selector' => '{{WRAPPER}} .wpr-custom-field span',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '12',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'custom_field_text_width',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-custom-field span' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

    }

	// Extra Icon  
	public function render_extra_icon( $settings ) {
		if ( '' !== $settings['custom_field_extra_icon'] ) {
			\Elementor\Icons_Manager::render_icon( $settings['custom_field_extra_icon'], [ 'aria-hidden' => 'true' ] );
		}
	}

	// Extra Text 
	public function render_extra_text( $settings ) {
		if ( '' !== $settings['custom_field_extra_text'] ) {
			echo '<span>'. esc_html( $settings['custom_field_extra_text'] ) .'</span>';
		}
	}

    protected function render() {
        $settings = $this->get_settings_for_display();
		$custom_field_value = get_post_meta( get_the_ID(), $settings['custom_fields_list'], true );

		if ( has_filter('wpr_update_custom_field_value') ) {
			ob_start();
			apply_filters( 'wpr_update_custom_field_value', $custom_field_value, get_the_ID(),  $settings['custom_fields_list'] );
			$custom_field_value = ob_get_clean();
		}

		// Hide if Empty
		if ( 'yes' === $settings['custom_field_hide_empty'] ) {
			if ( 'truefalse' === $settings['custom_fields_callback'] ) {
				$custom_field_value = filter_var( $custom_field_value, FILTER_VALIDATE_BOOLEAN );
			}

			if ( '' === $custom_field_value || !$custom_field_value ) {
				return;
			}
		}
		
		if ( 'checkbox' !== $settings['custom_fields_callback'] ) {
			// Wrapper
			echo '<span class="wpr-custom-field">';

			// Extra Icon & Text 
			if ( '' !== $settings['custom_field_extra_icon'] || '' !== $settings['custom_field_extra_text'] ) {
				echo '<span class="wpr-post-info-text">';
					$this->render_extra_icon( $settings );
					$this->render_extra_text( $settings );
				echo '</span>';
			}
		}

			// Custom Field Value
			if ( 'default' === $settings['custom_fields_callback'] ) {
				if ( !is_array( $custom_field_value ) ) {
					echo '<span class="wpr-custom-field-value">'. $custom_field_value .'</span>';
				} else {
					echo '<span>Please select different callback for this field.</span>';
				}
			} elseif ( 'checkbox' === $settings['custom_fields_callback'] ) {
				echo '<ul>';
					if ( is_array( $custom_field_value ) ) {
						foreach ( $custom_field_value as $value ) {
							echo '<li class="wpr-custom-field">';
								$this->render_extra_icon( $settings );
								echo esc_html( $value );
							echo '</li>';
						}
					} elseif ( '' !== $custom_field_value ) {
						echo '<span>Please select different callback for this field.</span>';
					}
				echo '</ul>';
			} elseif ( 'truefalse' === $settings['custom_fields_callback'] ) {
				if ( !is_array( $custom_field_value ) ) {
					if ( $custom_field_value ) {
						if ( '' !== $settings['custom_field_tf_enabled_text'] ) {
							echo '<span class="wpr-custom-field-value">'. esc_html($settings['custom_field_tf_enabled_text']) .'</span>';
						}
					} else {
						if ( '' !== $settings['custom_field_tf_disabled_text'] ) {
							echo '<span class="wpr-custom-field-value">'. esc_html($settings['custom_field_tf_disabled_text']) .'</span>';
						}
					}
				} else {
					echo '<span>Please select different callback for this field.</span>';
				}
			}

		// Close Wrapper
		if ( 'checkbox' !== $settings['custom_fields_callback'] ) {
			echo '</span>';
		}
    }
}