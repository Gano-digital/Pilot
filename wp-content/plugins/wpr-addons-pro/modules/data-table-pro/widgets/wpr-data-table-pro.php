<?php

namespace WprAddonsPro\Modules\DataTablePro\Widgets;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;

// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

class Wpr_Data_Table_Pro extends \WprAddons\Modules\DataTable\Widgets\Wpr_Data_Table {

	public function add_control_export_buttons_distance() {
        $this->add_responsive_control(
			'export_buttons_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'   => [
                    'size'  => 7,
                    'unit'  => 'px'
                ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-xls' => 'margin-right: {{SIZE}}{{UNIT}}; position: relative;',
					'{{WRAPPER}} .wpr-table-export-button-cont' => 'margin-bottom: {{SIZE}}{{UNIT}}; position: relative;',
					'{{WRAPPER}} .wpr-table-live-search-cont' => 'margin-bottom: {{SIZE}}{{UNIT}}; position: relative;',
				]
			]
		);
	}

	public function add_control_table_search_input_padding() {
        $this->add_responsive_control(
            'table_search_input_padding',
            [
                'label'      => esc_html__( 'Search & Export Padding', 'wpr-addons' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'default'    => [
                    'top'    => 5,
                    'bottom' => 5,
                    'left'   => 5,
                    'right'  => 5,
                    'unit'   => 'px'
                ],
				'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .wpr-table-live-search-cont input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpr-table-export-button-cont button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .wpr-search-input-icon' => 'right: {{RIGHT}}{{UNIT}} !important',
				]
            ]
        );
	}

	public function add_control_choose_table_type() {
		$this->add_control(
			'choose_table_type',
			[
				'label' => esc_html__( 'Data Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'render_type' => 'template',
				'options' => [
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
					'csv' => esc_html__( 'CSV', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-data-table-type-'
			]
		);

		$this->add_control(
			'choose_csv_type',
			[
				'label' => esc_html__( 'File Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'file',
				'options' => [
					'file' => esc_html__( 'Upload CSV', 'wpr-addons' ),
					'url' => esc_html__( 'Remote CSV URL', 'wpr-addons' ),
				],
				'condition' => [
					'choose_table_type' => 'csv'
				]
			]
		);

		$this->add_control(
			'display_header',
			[
				'label' => esc_html__('Show Table Header', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'condition' => [
					'choose_table_type' => 'csv',
				]
			]
		);

        $this->add_control(
            'table_upload_csv',
            [
                'label'     => esc_html__('Upload CSV File', 'wpr-addons'),
                'type'      => Controls_Manager::MEDIA,
                'media_type'=> array(),
				'dynamic' => [
					'active' => true,
					'categories' => [
						'media',
					],
				],
                'condition' => [
                    'choose_table_type'   => 'csv',
					'choose_csv_type' => 'file',
                ]
       		]
		);

        $this->add_control(
            'table_insert_url',
            [
                'label'         => esc_html__( 'Enter a CSV File URL', 'wpr-addons' ),
                'type'          => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
                'show_external' => false,
                'label_block'   => true,
                // 'default'       => [
                //     'url' => Handler::get_url()  . 'assets/table.csv',
                // ],
                'condition' => [
					'choose_table_type' => 'csv',
                    'choose_csv_type'   => 'url',
                ]
            ]
        );
	}

	public function add_control_enable_table_export() {
		$this->add_control(
			'enable_table_export',
			[
				'label' => esc_html__('Show Export Buttons', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);
	}

	public function add_control_export_excel_text() {
        $this->add_control(
            'export_excel_text',
            [
                'label'                 => esc_html__( 'Export Excel Text', 'wpr-addons' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
                'default'               => 'Export XLS',
                'condition'             => [
                    'enable_table_export'   => 'yes',
                ],
            ]
        );
	}

	public function add_control_export_csv_text() {
        $this->add_control(
            'export_csv_text',
            [
                'label'                 => esc_html__( 'Export CSV Text', 'wpr-addons' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
                'default'               => 'Export CSV',
                'condition'             => [
                    'enable_table_export'   => 'yes',
                ],
            ]
        );
	}

	public function add_section_export_buttons_styles() {
		$this->start_controls_section(
			'export_buttons_styles_section',
			[
				'label' => esc_html__( 'Export Buttons', 'wpr-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_table_export' => 'yes'
                ]
			]
		);
        
        $this->start_controls_tabs(
			'export_button_style_tabs'
		);

		$this->start_controls_tab(
			'export_buttons_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'export_buttons_color',
			[
				'label'  => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'export_buttons_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'export_buttons_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'export_typograpphy_divider',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'export_typography',
				'selector' => '{{WRAPPER}} .wpr-table-export-button-cont .wpr-button',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'       => [
						'label'      => esc_html__('Font Size (px)', 'wpr-addons'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					],
					'font_weight'     => [
						'default' => '400',
					]
				],
			]
		);

		$this->add_control(
			'export_hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-button' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size;'
				],
				'separator' => 'before'
			]
		);

        $this->add_responsive_control(
			'export_container_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'   => [
                    'size'  => 325,
                    'unit'  => 'px'
                ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont' => 'width: {{SIZE}}{{UNIT}}; position: relative;',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'export_buttons_border_type',
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
				'separator' => 'before',
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'border-style: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'export_buttons_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'export_buttons_border_type!' => 'none',
				],
			]
		);
		
		$this->add_responsive_control(
			'export_buttons_border_radius',
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
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'export_buttons_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'export_buttons_color_hover',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button:hover' => 'color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'export_buttons_bg_color_hover',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'export_buttons_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-export-button-cont .wpr-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->add_section_search_styles();
	}

	public function add_control_enable_table_search() {
		$this->add_control(
			'enable_table_search',
			[
				'label' => esc_html__('Show Search', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

        $this->add_control(
            'search_placeholder',
            [
                'label'                 => esc_html__( 'Search Placeholder', 'wpr-addons' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
                'default'               => 'Type Here To Search...',
                'condition'             => [
                    'enable_table_search'   => 'yes',
                ],
            ]
        );
	}

	public function add_section_search_styles() {
		$this->start_controls_section(
			'search_style_section',
			[
				'label' => esc_html__('Search', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_table_search' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
            'table_search_input_tabs'
        );

            $this->start_controls_tab(
                'table_search_input_normal_tab',
                [
                    'label'     => esc_html__( 'Normal', 'wpr-addons' ),
                ]
            );

            $this->add_control(
                'table_search_input_color',
                [
                    'label'     => esc_html__( 'Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
					'default' => '#7A7A7A',
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'table_search_input_background_color',
                [
                    'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input' => 'background-color: {{VALUE}};',
                        // '{{WRAPPER}} .wpr-table-live-search-cont' => 'background-color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'table_search_input_border_color',
                [
                    'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
					'default' => '#E8E8E8',
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input' => 'border-color: {{VALUE}};',
                        // '{{WRAPPER}} .wpr-table-live-search-cont' => 'background-color: {{VALUE}};',
                    ],
                ]
            );


            $this->end_controls_tab();

            $this->start_controls_tab(
                'table_search_input_hover_tab',
                [
                    'label'     => esc_html__( 'Hover', 'wpr-addons' ),
                ]
            );

            $this->add_control(
                'table_search_input_hover_color',
                [
                    'label'     => esc_html__( 'Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
					'default' => '#7A7A7A',
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input:hover' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'table_search_input_hover_background_color',
                [
                    'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input:hover' => 'background-color: {{VALUE}};',
                    ],
                ]
            );


            $this->end_controls_tab();

            $this->start_controls_tab(
                'table_search_input_focus_tab',
                [
                    'label'     => esc_html__( 'Focus', 'wpr-addons' ),
                ]
            );

            $this->add_control(
                'table_search_input_focus_color',
                [
                    'label'     => esc_html__( 'Color', 'wpr-addons' ),
					'default' => '#7A7A7A',
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input:focus' => 'color: {{VALUE}};',
                    ],
                ]
            );

            $this->add_control(
                'table_search_input_focus_background_color',
                [
                    'label'     => esc_html__( 'Background Color', 'wpr-addons' ),
                    'type'      => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wpr-table-live-search-cont input:focus' => 'background-color: {{VALUE}};',
                    ],
                ]
            );
    
            $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
			  'name' => 'table_search_input_border_shadow',
			  'selector' => '{{WRAPPER}} .wpr-table-live-search-cont input',
			  'separator' => 'before'
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'table_search_input_text_typography',
                'label' =>esc_html__( 'Typography', 'wpr-addons' ),
                'selector' => '{{WRAPPER}} .wpr-table-live-search-cont input',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'       => [
						'label'      => esc_html__('Font Size (px)', 'wpr-addons'),
						'size_units' => ['px'],
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					]
				],
            ]
        );

        $this->add_control(
            'table_search_input_placeholder_heading',
            [
                'label'     => esc_html__( 'Input Placeholder', 'wpr-addons' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'table_search_input_placeholder_color',
            [
                'label'     => esc_html__( 'Color', 'wpr-addons' ),
                'type'      => Controls_Manager::COLOR,
				'default' => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} .wpr-table-live-search-cont input::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'table_search_input_placeholder_typo',
                'label' =>esc_html__( 'Typography', 'wpr-addons' ),
                'selector' => '{{WRAPPER}} .wpr-table-live-search-cont input::placeholder',
            ]
        );

        $this->add_control(
            'table_search_icon_heading',
            [
                'label'     => esc_html__( 'Icon', 'wpr-addons' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'table_search_icon_color',
            [
                'label'     => esc_html__( 'Icon Color', 'wpr-addons' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#7A7A7A',
                'selectors' => [
                    '{{WRAPPER}} i.wpr-search-input-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'table_search_icon_font_size',
            [
                'label'      => esc_html__( 'Icon Size', 'wpr-addons' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', 'em', 'rem',
				],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
					],
				],
                'selectors'  => [
                    '{{WRAPPER}} i.wpr-search-input-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
        );

        $this->add_control(
            'table_search_input_heading',
            [
                'label'     => esc_html__( 'Input', 'wpr-addons' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
			'table_search_input_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'default'   => [
                    'size'  => 325,
                    'unit'  => 'px'
                ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-live-search-cont' => 'width: {{SIZE}}{{UNIT}}; position: relative;',
				],
			]
		);

		$this->add_control(
			'table_search_input_border',
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
				'separator' => 'before',
				'default' => 'solid',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-live-search-cont input' => 'border-style: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control(
			'table_search_input_border_width',
			[
				'label'      => esc_html__( 'Border Width', 'wpr-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpr-table-live-search-cont input' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
				'condition' => [
					'table_search_input_border!' => 'none'
				]
			]
        );

        $this->add_responsive_control(
			'table_search_input_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .wpr-table-live-search-cont input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				],
			]
        );

		$this->end_controls_section();
	}

	public function render_search_export() {
		$settings = $this->get_settings_for_display(); 

		if ( 'yes' === $settings['enable_table_search'] || 'yes' === $settings['enable_table_export'] ) {

			echo '<div class="wpr-export-search-cont">';
			echo '<div class="wpr-export-search-inner-cont">';
	
			if ( 'yes' === $settings['enable_table_export'] ) {
				echo '<div class="wpr-table-export-button-cont">';
					if ( '' !== $settings['export_excel_text'] ) {
						echo '<button class="wpr-button wpr-xls">'. $settings['export_excel_text'] .'</button>';
					}
					if ( '' !== $settings['export_csv_text'] ) {
						echo '<button class="wpr-button wpr-csv">'. $settings['export_csv_text'] .'</button>';
					}
				echo '</div>';
			}

			if ( 'yes' === $settings['enable_table_search'] ) {
				echo '<div class="wpr-table-live-search-cont">';
					echo '<input type="search" class="wpr-table-live-search" placeholder="'. esc_attr($settings['search_placeholder']) .'">';
					echo '<i class="fas fa-search wpr-search-input-icon"></i>';
				echo '</div>';
			}

			echo '</div>';
			echo '</div>';

		}
	}

	public function add_control_enable_table_sorting() {
		$this->add_control(
			'enable_table_sorting',
			[
				'label' => esc_html__('Show Sorting', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);
	}

	public function add_control_active_td_bg_color() {
		$this->add_control(
			'active_td_bg_color',
			[
				'label'  => esc_html__( 'Active Column Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-active-td-bg-color' => 'background: {{VALUE}} !important;',
				]
			]
		);
	}

	public function add_control_enable_custom_pagination() {
		$this->add_control(
			'enable_custom_pagination', 
			[
				'label' => esc_html__('Show Pagination', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before'
			]
		);

		$this->add_control(
			'table_items_per_page',
			[
				'label' => esc_html__( 'Items Per Page', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'render_type' => 'template',
				'frontend_available' => true,
				'default' => 10,
				'condition' => [
					'enable_custom_pagination' => 'yes'
				]
			]
		);
		

		$this->add_control(
			'pagination_nav_icons',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => 'wpr-arrow-icons',
				'default' => 'fas fa-angle',
				'condition' => [
					'enable_custom_pagination' => 'yes',
				]
			]
		);

		$this->add_control(
			'enable_entry_info',
			[
				'label' => esc_html__('Entry Info', 'wpr-addons'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'render_type' => 'template',
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'wpr-entry-info-',
				'condition' => [
					'enable_custom_pagination' => 'yes'
				]
			]
		);
	}

	public function add_section_pagination_styles() {
		$this->start_controls_section(
			'pagination_style_section',
			[
				'label' => esc_html__('Pagination', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_custom_pagination' => 'yes'
				]
			]
		);

		$this->start_controls_tabs(
			'pagination_normal_style_tabs'
		);

		$this->start_controls_tab(
			'pagination_style_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);
				
		$this->add_control(
			'pagination_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-table-custom-pagination-list svg' => 'fill: {{VALUE}}'
				],
			]
		);
				
		$this->add_control(
			'pagination_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list' => 'background-color: {{VALUE}}'
				],
			]
		);
				
		$this->add_control(
			'pagination_bg_color_active',
			[
				'label' => esc_html__( 'Background Color (Active)', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list.wpr-active-pagination-item' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'pagination_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'separator' => 'after',
				'selectors' => [
					// '{{WRAPPER}} .wpr-table-custom-pagination-inner-cont' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-table-custom-pagination-prev' => 'border-left-color: {{VALUE}}; border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-table-custom-pagination-next' => 'border-right-color: {{VALUE}}; border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-table-custom-pagination-list-item' => 'border-top-color: {{VALUE}}; border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'table_pagination_typography',
				'selector' => '{{WRAPPER}} .wpr-table-custom-pagination-list',
				'render_type' => 'template',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_weight'     => [
						'default' => '400',
					]
				],
			]
		);

		$this->add_control(
			'pagination_hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-inner-cont li' => '-webkit-transition-duration:  {{VALUE}}s; transition-duration:  {{VALUE}}s; transition-property: background-color color font-size;'
				]
			]
		);

		$this->add_responsive_control(
			'pagination_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 15
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list svg' => 'width: {{SIZE}}{{UNIT}}'
				]
			]
		);

		$this->add_responsive_control(
			'pagination_horizontal_gutter',
			[
				'label' => esc_html__( 'Horizontal Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'pagination_vertical_gutter',
			[
				'label' => esc_html__( 'Vertical Gutter', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200
					]
				],
				'default' => [
					'size' => 20,
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-pagination-cont' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'separator' => 'before',
				'default' => [
					'top' => 7,
					'right' => 13,
					'bottom' => 7,
					'left' => 13,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pagination_border_type',
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
				'separator' => 'before',
				'default' => 'solid',
				'selectors' => [
					// '{{WRAPPER}} .wpr-table-custom-pagination-inner-cont' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-table-custom-pagination-prev' => 'border-left-style: {{VALUE}}; border-top-style: {{VALUE}}; border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-table-custom-pagination-next' => 'border-right-style: {{VALUE}}; border-top-style: {{VALUE}}; border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-table-custom-pagination-list-item' => 'border-top-style: {{VALUE}}; border-bottom-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', '%'
				],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-prev' => 'border-left-width: {{SIZE}}{{UNIT}}; border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-table-custom-pagination-next' => 'border-right-width: {{SIZE}}{{UNIT}};  border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-table-custom-pagination-list-item' => 'border-top-width: {{SIZE}}{{UNIT}}; border-bottom-width: {{SIZE}}{{UNIT}};'
				],
				'condition' => [
					'pagination_border_type!' => 'none'
				]
			]
		);

		$this->add_responsive_control(
			'pagination_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [
                    'px', '%'
				],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-prev' => 'border-top-left-radius: {{SIZE}}{{UNIT}}; border-bottom-left-radius: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-table-custom-pagination-next' => 'border-top-right-radius: {{SIZE}}{{UNIT}}; border-bottom-right-radius: {{SIZE}}{{UNIT}};'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'pagination_style_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'pagination_color_hover',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#A4A4A4',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list:hover' => 'color: {{VALUE}}'
				],
			]
		);
				
		$this->add_control(
			'pagination_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-list:hover' => 'background-color: {{VALUE}}'
				],
			]
		);

		$this->add_control(
			'pagination_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFFFFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination-inner-cont:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'pagination_border_type!' => 'none',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		
		$this->add_control(
            'pagination_alignment',
            [
                'label'        => esc_html__('Alignment', 'wpr-addons'), 
                'type'         => Controls_Manager::CHOOSE,
                'label_block'  => false,
                'default'      => 'center',
				'separator' => 'before',
                'options'      => [
                    'flex-start'   => [
                        'title' => esc_html__('Left', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'  => [
                        'title' => esc_html__('Right', 'wpr-addons'),
                        'icon'  => 'eicon-h-align-right',
                    ],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-table-custom-pagination' => 'display: flex; justify-content: {{VALUE}}; align-items: center;',
				],
				'condition' => [
					'enable_entry_info!' => 'yes'
				]
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'entry_info_styles',
			[
				'label' => esc_html__('Entry Info', 'wpr-addons'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_custom_pagination' => 'yes',
					'enable_entry_info' => 'yes'
				]
			]
		);

		$this->add_control(
			'entry_info_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#B3B3B3',
				'selectors' => [
					'{{WRAPPER}} .wpr-entry-info' => 'color: {{VALUE}}; cursor: pointer;',
				],
			]
		);

		$this->add_control(
			'entry_info_color_hover',
			[
				'label'  => esc_html__( 'Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#B3B3B3',
				'selectors' => [
					'{{WRAPPER}} .wpr-entry-info:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'entry_info_typography',
				'selector' => '{{WRAPPER}} .wpr-entry-info',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'       => [
						'default'    => [
							'size' => '13',
							'unit' => 'px',
						],
					],
					'font_weight'     => [
						'default' => '400',
					]
				],
			]
		);

		$this->end_controls_section();
	}

	public function add_repeater_args_content_tooltip() {
		return [
			'label' => esc_html__( 'Show Tooltip', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'separator' => 'before',
			'condition' => [
				'table_content_row_type' => 'col',
			],
		];
	}

	public function add_repeater_args_content_tooltip_text() {
		return [
			'label' => esc_html__( 'Description', 'wpr-addons' ),
			'type' => Controls_Manager::TEXTAREA,
			'dynamic' => [
				'active' => true,
			],
			'default' => 'Tooltip Text',
			'description' => esc_html__( 'This field accepts HTML.', 'wpr-addons' ),
			'condition' => [
				'table_content_row_type' => 'col',
				'content_tooltip' => 'yes',
			],
		];
	}

	public function add_repeater_args_content_tooltip_show_icon() {
		return [
			'label' => esc_html__( 'Show Tooltip Icon', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'condition' => [
				'table_content_row_type' => 'col',
				'content_tooltip' => 'yes',
			],
		];
	}

	public function add_control_stack_content_tooltip_section() {
		
		$this->start_controls_section(
			'tooltip_styles',
			[
				'label' => esc_html__('Tooltip', 'wpr-addons'),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'content_tooltip_icon_heading',
			[
				'label' => esc_html__( 'Tooltip Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'content_tooltip_icon_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#7A7A7A',
				'selectors' => [
					'{{WRAPPER}} .wpr-data-table .fa-question-circle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_tooltip_section',
			[
				'label' => esc_html__( 'Tooltip', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_tooltip_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 5,
						'max' => 100,
					],
					'px' => [
						'min' => 10,
						'max' => 500,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-data-table-content-tooltip' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'content_tooltip_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#3f3f3f',
				'selectors' => [
					'{{WRAPPER}} .wpr-data-table-content-tooltip' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-data-table-content-tooltip:before' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'content_tooltip_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#FFFFFF',				
				'selectors' => [
					'{{WRAPPER}} .wpr-data-table-content-tooltip' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_tooltip_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-data-table-content-tooltip',
			]
		);

		$this->end_controls_section();
	}

	public function render_content_tooltip($item) {
		if ( $item['content_tooltip'] === 'yes' && ! empty( $item['content_tooltip_text'] ) ) : ?>
			<div class="wpr-data-table-content-tooltip"><?php echo wp_kses_post($item['content_tooltip_text']); ?></div>						
		<?php endif;
	}

	public function render_tooltip_icon($item) {
		if ( 'yes' === $item['content_tooltip'] && 'yes' === $item['content_tooltip_show_icon'] ) {
			echo '&nbsp;&nbsp;<i class="far fa-question-circle"></i>';
		}
	}

	public function render_custom_pagination($settings, $countRows) {
	?>

		<div class="wpr-table-pagination-outer-cont">
		<div class="wpr-table-pagination-cont">
		<?php if ( 'yes' === $settings['enable_entry_info'] ) : ?>
			<div class="wpr-entry-info"></div>
		<?php endif; ?>
		<ul class="wpr-table-custom-pagination">
			<div class="wpr-table-custom-pagination-inner-cont">
				<?php if ( 'none' !== $settings['pagination_nav_icons'] ) : ?>
				<li class='wpr-table-custom-pagination-prev wpr-table-prev-next wpr-table-custom-pagination-list'>
					<?php 
						echo Utilities::get_wpr_icon( $settings['pagination_nav_icons'], 'left');
						?>
				</li>
				<?php endif; ?>
	
				<?php $total_rows = 0;
						$item_index = 0;

				if ( 'custom' === $settings['choose_table_type'] ) {
					foreach ( $settings['table_content_rows'] as $item ) {
						if ( 'row' === $item['table_content_row_type'] ) {
							$total_rows++;
						}
					}
				}
		
				// $exact_number_of_pages = $total_rows/$settings['table_items_per_page'];
				$total_pages = 'custom' === $settings['choose_table_type'] ? ceil($total_rows/$settings['table_items_per_page']) : ceil($countRows/$settings['table_items_per_page']);
					
				for (  $i = 1; $i <= $total_pages; $i++ ) {	?>
	
						<li class="wpr-table-custom-pagination-list wpr-table-custom-pagination-list-item <?php echo $i === 1 ? 'wpr-active-pagination-item' : ''; ?>">
							<span><?php echo $i; ?></span>
						</li>
	
					<?php } ?>
					
				<?php if ( 'none' !== $settings['pagination_nav_icons'] ) : ?>
				<li class='wpr-table-custom-pagination-next wpr-table-prev-next wpr-table-custom-pagination-list wpr-table-prev-arrow wpr-table-arrow'>
					<?php echo Utilities::get_wpr_icon( $settings['pagination_nav_icons'], 'right'); ?>
				</li>
				<?php endif; ?>
			</div>
		</ul>
		</div>
		</div>

	<?php
	}
	
}