<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\ProductTabsPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Tabs_Pro extends \WprAddons\Modules\ThemeBuilder\Woocommerce\ProductTabs\Widgets\Wpr_Product_Tabs {
	public function add_control_tabs_position() {
		$this->add_control(
			'tabs_position',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Label Position', 'wpr-addons' ),
				'default' => 'above',
				'options' => [
					'above' => esc_html__( 'Horizontal', 'wpr-addons' ),
					'left' => esc_html__( 'Vertical Left', 'wpr-addons' ),
					'right' => esc_html__( 'Vertical Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-tabs-position-',
			]
		);
	}

	public function add_controls_group_tabs_label_adjustments() {
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
	

		$this->add_control(
			'tabs_vr_position',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'middle',
                'options' => [
                    'top' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					],
                ],
                'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end'
				],
				'selectors' => [
					'{{WRAPPER}} .wc-tabs-wrapper .wc-tabs' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'tabs_position!' => 'above',
				],
			]
		);

		$this->add_control( //TODO: change approach
			'text_align',
			[
				'label' => esc_html__( 'Label Alignment', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'center',
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				// 'selectors_dictionary' => [
				// 	'left' => 'flex-start',
				// 	'center' => 'center',
				// 	'right' => 'flex-end'
				// ],
				'selectors' => [
					// '{{WRAPPER}} .wc-tabs li' => 'display: flex; align-items: {{VALUE}}; justify-content: {{VALUE}};',
					'{{WRAPPER}} .wc-tabs li' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wc-tabs li a' => 'text-align: {{VALUE}};'
				],
			]
		);

		$this->add_responsive_control(
			'tabs_width',
			[
				'label' => esc_html__( 'Label Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 600,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 90,
				],
				'selectors' => [
					'{{WRAPPER}} .wc-tabs li' => 'min-width: {{SIZE}}px;',
					'{{WRAPPER}} .wc-tabs li a' => 'min-width: {{SIZE}}px; display: block;'
				],
				'condition' => [
					'tabs_hr_position!' => 'justify',
				],
			]
		);
	}	
}