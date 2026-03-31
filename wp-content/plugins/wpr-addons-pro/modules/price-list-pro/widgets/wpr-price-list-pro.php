<?php
namespace WprAddonsPro\Modules\PriceListPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Price_List_Pro extends \WprAddons\Modules\PriceList\Widgets\Wpr_Price_List {

	public function add_repeater_args_prlist_image() {
		return [
			'label' => esc_html__( 'Image', 'wpr-addons' ),
			'type' => Controls_Manager::MEDIA,
			'dynamic' => [
				'active' => true,
			],
			'default' => [
				'url' => Utils::get_placeholder_image_src(),
			],
		];
	}
	
	public function add_repeater_args_prlist_link() {
		return [
			'label' => esc_html__( 'Link', 'wpr-addons' ),
			'type' => Controls_Manager::URL,
			'dynamic' => [
				'active' => true,
			],
			'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
			'separator' => 'before'
		];
	}
	
	public function add_control_prlist_position() {
		$this->add_control(
			'prlist_position_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'prlist_position',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Image Left', 'wpr-addons' ),
					'center' => esc_html__( 'Content Center', 'wpr-addons' ),
					'right' => esc_html__( 'Image Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-price-list-position-',
				'render_type' => 'template',
			]
		);
	}
	
	public function add_control_prlist_vr_position() {
		$this->add_responsive_control(
			'prlist_vr_position',
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
					'{{WRAPPER}} .wpr-price-list-item' => '-webkit-align-items: {{VALUE}};align-items: {{VALUE}};',
				],
				'condition' => [
					'prlist_position!' => 'center',
				],
			]
		);
	}
	
	public function add_section_style_image() {
		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'medium',
			]
		);

		$this->add_responsive_control(
			'image_size1',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 80,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-image img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_gutter',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Spacing', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-price-list-position-left .wpr-price-list-image img' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-price-list-position-right .wpr-price-list-image img' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wpr-price-list-position-center .wpr-price-list-image img' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
						'width' => [
							'default' => [
								'top' => '1',
								'right' => '1',
								'bottom' => '1',
								'left' => '1',
								'isLinked' => true,
							],
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-price-list-image img',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 1,
					'right' => 1,
					'bottom' => 1,
					'left' => 1,
				],
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-price-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-price-list-image img',
			]
		);

		$this->end_controls_section(); // End Controls Section	
	}

	public function render_pro_element_image( $item, $item_count ) {
		$settings = $this->get_settings();

		$item['prlist_image']['id'] = isset($item['prlist_image']['id']) ? $item['prlist_image']['id'] : false;

		$image_src = Group_Control_Image_Size::get_attachment_image_src( $item['prlist_image']['id'], 'image_size', $settings );

		if ( isset($item['prlist_link']['url']) && '' !== $item['prlist_link']['url'] ) {
		
			$this->add_render_attribute( 'wpr-price-list-link' . $item_count, 'class', 'wpr-price-list-link' );

			$this->add_render_attribute( 'wpr-price-list-link' . $item_count, 'href', esc_url( $item['prlist_link']['url'] ) );

			if ( $item['prlist_link']['is_external'] ) {
				$this->add_render_attribute( 'wpr-price-list-link' . $item_count, 'target', '_blank' );
			}

			if ( $item['prlist_link']['nofollow'] ) {
				$this->add_render_attribute( 'wpr-price-list-link' . $item_count, 'nofollow', '' );
			}

			echo '<a '. $this->get_render_attribute_string( 'wpr-price-list-link' . $item_count ) .'></a>';
		}

		if ( $image_src ) {
			echo '<div class="wpr-price-list-image">';
				echo '<img src="'. esc_url( $image_src ) .'" >';
			echo '</div>';
		}
	}

}