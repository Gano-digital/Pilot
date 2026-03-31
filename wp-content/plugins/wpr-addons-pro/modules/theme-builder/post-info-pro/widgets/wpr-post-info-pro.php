<?php
namespace WprAddonsPro\Modules\ThemeBuilder\PostInfoPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Post_Info_Pro extends \WprAddons\Modules\ThemeBuilder\PostInfo\Widgets\Wpr_Post_Info {

	public function add_options_post_info_select() {
		$options = [
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'time' => esc_html__( 'Time', 'wpr-addons' ),
			'comments' => esc_html__( 'Comments', 'wpr-addons' ),
			'author' => esc_html__( 'Author', 'wpr-addons' ),
			'taxonomy' => esc_html__( 'Taxonomy', 'wpr-addons' ),
		];

		if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
			$options['custom-field'] = esc_html__( 'Custom Field', 'wpr-addons' );
		} else {
			$options['pro-cf'] = esc_html__( 'Custom Field (Expert)', 'wpr-addons' );
		}

		return $options;
	}

	public function add_section_style_custom_field() {
		$this->start_controls_section(
			'section_style_custom_field',
			[
				'label' => esc_html__( 'Custom Field', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'post_info_select' => 'custom-field'
				]
			]
		);

		$this->start_controls_tabs( 'tabs_grid_custom_field_style' );

		$this->start_controls_tab(
			'tab_grid_custom_field_normal',
			[
				'label' => __( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'custom_field_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-custom-field a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-custom-field > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'custom_field_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-custom-field a' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-custom-field > span' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'custom_field_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-custom-field a' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-custom-field > span' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'custom_field_typography',
				'selector' => '{{WRAPPER}} .wpr-post-info-custom-field'
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_custom_field_hover',
			[
				'label' => __( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'custom_field_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-custom-field a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-custom-field > span:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'custom_field_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-custom-field a:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-custom-field > span:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'custom_field_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-post-info-custom-field a:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-post-info-custom-field > span:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'custom_field_padding',
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
					'{{WRAPPER}} .wpr-post-info-custom-field a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-custom-field > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_field_border_type',
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
					'{{WRAPPER}} .wpr-post-info-custom-field a' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-post-info-custom-field > span' => 'border-style: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'custom_field_border_width',
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
					'{{WRAPPER}} .wpr-post-info-custom-field a' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-custom-field > span' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'custom_field_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'custom_field_radius',
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
					'{{WRAPPER}} .wpr-post-info-custom-field a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-post-info-custom-field > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Post Custom Field
	public function render_post_info_custom_field( $settings ) {
		$custom_field_value = get_post_meta( get_the_ID(), $settings['post_info_cf'], true );
		$custom_field_html = $settings['post_info_cf_wrapper_html']; // GOGA - maybe needs condition

		// Get First Value if Array
		if ( is_array($custom_field_value) ) {
			$custom_field_value = $custom_field_value[0];
		}

		// Erase if Array or Object
		if ( ! is_string( $custom_field_value ) && ! is_numeric( $custom_field_value ) ) {
			$custom_field_value = '';
		}

		// Return if Empty
		if ( '' === $custom_field_value ) {
			return;
		}
		
		// Button Link
		if ( 'yes' === $settings['post_info_cf_btn_link'] ) {
			$target = 'yes' === $settings['post_info_cf_new_tab'] ? '_blank' : '_self';
			echo '<a href="'. $custom_field_value .'" target="'. $target .'">';

				// Extra Icon & Text 
				$this->render_extra_icon_text( $settings );

				// Button Text
				echo esc_html( $settings['post_info_cf_btn_text'] );
			echo '</a>';

		// Custom Field
		} else {
			echo '<span>';

				// Extra Icon & Text 
				$this->render_extra_icon_text( $settings );

				// Custom Field Value
				if ( 'yes' === $settings['post_info_cf_wrapper'] ) {
					echo str_replace( '*cf_value*', $custom_field_value, $custom_field_html );
				} else {
					echo $custom_field_value;
				}
			echo '</span>';
		}
	}

	public function get_post_taxonomies() {
		$post_taxonomies = Utilities::get_custom_types_of( 'tax', false );
		unset($post_taxonomies['product_cat']);
		unset($post_taxonomies['product_tag']);

		return $post_taxonomies;
	}
}