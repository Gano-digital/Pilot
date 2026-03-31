<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\ProductMediaPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Media_Pro extends \WprAddons\Modules\ThemeBuilder\Woocommerce\ProductMedia\Widgets\Wpr_Product_Media {

	public function add_control_gallery_slider_thumbs() {
		$this->add_control(
			'gallery_slider_thumbs_type',
			[
				'label' => esc_html__( 'Display Thumbs As', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'stacked' => esc_html__( 'Stacked', 'wpr-addons' ),
					'slider' => esc_html__( 'Slider', 'wpr-addons' )
				],
				'default' => 'stacked',
				'render_type' => 'template',
				'prefix_class' => 'wpr-product-media-thumbs-',
				'selectors' => [
					'{{WRAPPER}}.wpr-product-media-thumbs-none .wpr-product-media-wrap .flex-control-nav' => 'display: none;',
					'{{WRAPPER}}.wpr-product-media-thumbs-stacked .wpr-product-media-wrap .flex-control-nav' => 'display: grid;',
					'{{WRAPPER}}.wpr-product-media-thumbs-slider .wpr-product-media-wrap .flex-control-nav' => 'display: flex;',
                ],
                'condition' => [
                    'gallery_slider_thumbs' => 'yes'
                ]
			]
		);
	}

    public function add_controls_group_gallery_slider_thumbs() {

        // $this->add_control(
        //     'gallery_slider_thumbs_layout',
        //     [
        //         'label' => esc_html__( 'Thumbs Layout', 'wpr-addons' ),
        //         'type' => Controls_Manager::SELECT,
        //         'options' => [
        //             'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
        //             'vertical' => esc_html__( 'Vertical', 'wpr-addons' )
        //         ],
        //         'default' => 'horizontal',
        //         'render_type' => 'template',
        //         'prefix_class' => 'wpr-product-media-thumbs-',
        //         'condition' => [
        //             'gallery_slider_thumbs_type' => 'slider'
        //         ]
        //     ]
        // );

		$this->add_control(
			'gallery_slider_thumbs_vertical_height',
			[
				'label' => esc_html__( 'Max Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'vh', '%'],
				'default' => [
					'size' => 70,
					'unit' => 'vh'
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-media-wrap .woocommerce-product-gallery' => 'max-height: {{SIZE}}{{UNIT}}'
                ],
                'condition' => [
                    'gallery_slider_thumbs_layout' => 'vertical'
                ]
			]
		);

        $this->add_responsive_control(
            'thumbnail_slider_nav',
            [
                'label' => esc_html__( 'Show Navigation Arrows', 'wpr-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'render_type' => 'template',
                'default' => 'yes',
                'tablet_default' => 'yes',
                'mobile_default' => 'yes',
                'selectors_dictionary' => [
                    '' => 'none',
                    'yes' => 'flex'
                ],
                'selectors' => [
                    '{{WRAPPER}} .wpr-thumbnail-slider-arrow' => 'display:{{VALUE}} !important;'
                ],
                'condition' => [
                    'gallery_slider_thumbs_type' => 'slider',
                ]
            ]
        );

        $this->add_control(
            'thumbnail_slider_nav_hover',
            [
                'label' => esc_html__( 'Show on Hover', 'wpr-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => 'fade',
                'prefix_class' => 'wpr-thumbnail-slider-nav-',
                'condition' => [
                    'gallery_slider_thumbs_type' => 'slider',
                    'thumbnail_slider_nav' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'thumbnail_slider_nav_icon',
            [
                'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
                'type' => 'wpr-arrow-icons',
                'default' => 'fas fa-angle',
                'condition' => [
                    'gallery_slider_thumbs_type' => 'slider',
                    'thumbnail_slider_nav' => 'yes'
                ],
            ]
        );
    }

	public function add_control_gallery_slider_thumbs_to_slide() {
		$this->add_control(
			'gallery_slider_thumbs_to_slide',
			[
				'label' => esc_html__( 'Thumbs To Slide', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'default' => 2,
				'render_type' => 'template',
				'condition' => [
					'gallery_slider_thumbs_type' => ['slider'],
				],

			]
		);
	}

}