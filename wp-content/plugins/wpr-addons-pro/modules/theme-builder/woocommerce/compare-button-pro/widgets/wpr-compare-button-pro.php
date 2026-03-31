<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\CompareButtonPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Compare_Button_Pro extends Widget_Base {
	
	public function get_name() {
		return 'wpr-compare-button-pro';
	}

	public function get_title() {
		return esc_html__( 'Compare Button', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-exchange';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? ['wpr-woocommerce-builder-widgets'] : [];
	}

	public function get_keywords() {
		return [ 'royal', 'compare button' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: Settings ------------
		$this->start_controls_section(
			'section_compare_button_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'compare_notice_video_tutorial',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Build Wishlist & Compare features <strong>completely with Elementor and Royal Elementor Addons !</strong> <ul><li><a href="https://www.youtube.com/watch?v=wis1rQTn1tg" target="_blank" style="color: #93003c;"><strong>Watch Video Tutorial <span class="dashicons dashicons-video-alt3"></strong></a></li></ul>', 'wpr-addons' ),
				'separator' => 'after',
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
			]
		);

		$this->add_control(
			'show_text',
			[
				'label' => esc_html__( 'Show Text', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->add_control(
			'add_to_compare_text',
			[
				'label' => esc_html__( 'Add Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Add to Compare')
			]
		);

		$this->add_control(
			'remove_from_compare_text',
			[
				'label' => esc_html__( 'Remove Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Remove from Compare')
			]
		);

		$this->add_control(
			'show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);

		$this->end_controls_section();

		// Tab: Style ==============
		// Section: Button Styles ------------
		$this->start_controls_section(
			'section_compare_button_styles',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_btn_styles' );

		$this->start_controls_tab(
			'tab_btn_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_text_color',
			[
				'label'  => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add span' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'btn_icon_color',
			[
				'label'  => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-add svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-compare-add, {{WRAPPER}} .wpr-compare-remove',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_typography',
				'selector' => '{{WRAPPER}} .wpr-compare-add span, {{WRAPPER}} .wpr-compare-add i, {{WRAPPER}} .wpr-compare-remove span, {{WRAPPER}} .wpr-compare-remove i',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '16',
							'unit' => 'px',
						],
					],
				]
			]
		);

		$this->add_control(
			'btn_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-add span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-add i' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-remove' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-remove span' => 'transition-duration: {{VALUE}}s',
					'{{WRAPPER}} .wpr-compare-remove i' => 'transition-duration: {{VALUE}}s'
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_btn_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'btn_hover_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add:hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-add:hover svg' => 'fill: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-add:hover span' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'btn_hover_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'btn_hover_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605be5',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'btn_box_shadow_hr',
				'selector' => '{{WRAPPER}} .wpr-compare-add:hover, WRAPPER}} .wpr-compare-remove:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_remove_btn',
			[
				'label' => esc_html__( 'Remove', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'remove_btn_text_color',
			[
				'label'  => esc_html__( 'Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-remove span' => 'color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'remove_btn_icon_color',
			[
				'label'  => esc_html__( 'Icon Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FFF',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-remove i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-compare-remove svg' => 'fill: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'remove_btn_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-remove' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->add_control(
			'remove_btn_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#FF4F40',
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-remove' => 'background-color: {{VALUE}}'
				]
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-compare-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_type',
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
					'{{WRAPPER}} .wpr-compare-add' => 'border-style: {{VALUE}};',
					'{{WRAPPER}} .wpr-compare-remove' => 'border-style: {{VALUE}};'
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 2,
					'right' => 2,
					'bottom' => 2,
					'left' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-compare-add' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-compare-remove' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'button_border_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
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
					'{{WRAPPER}} .wpr-compare-add' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wpr-compare-remove' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();

    }
	
	// Add two new functions for handling cookies
	public function get_compare_from_cookie() {
        if (isset($_COOKIE['wpr_compare'])) {
            return json_decode(stripslashes($_COOKIE['wpr_compare']), true);
        } else if ( isset($_COOKIE['wpr_compare_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['wpr_compare_'. get_current_blog_id() .'']), true);
        }
        return array();
	}

    protected function render() {

		$settings = $this->get_settings_for_display();

        global $product;
		$product = wc_get_product();
		$user_id = get_current_user_id();
		$button_add_title = '';
		$button_remove_title = '';

        if ( empty( $product ) ) {
            return;
        }

        setup_postdata( $product->get_id() );

		if ($user_id > 0) {
			$compare = get_user_meta( $user_id, 'wpr_compare', true );
		
			if ( ! $compare ) {
				$compare = array();
			}
	
		} else {
			$compare = $this->get_compare_from_cookie();
		}

        $remove_button_hidden = !in_array( $product->get_id(), $compare ) ? 'wpr-button-hidden' : '';
        $add_button_hidden = in_array( $product->get_id(), $compare ) ? 'wpr-button-hidden' : '';

		$add_to_compare_content = '';
		$remove_from_compare_content = '';

		if ( 'yes' === $settings['show_icon'] ) {
			$add_to_compare_content .= '<i class="fas fa-exchange-alt"></i>';
			$remove_from_compare_content .= '<i class="fas fa-exchange-alt"></i>';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$add_to_compare_content .= ' <span>'. esc_html__($settings['add_to_compare_text']) .'</span>';
		} else {
			$button_add_title = 'title="'. $settings['add_to_compare_text'] .'"';
			$button_remove_title = 'title="'. $settings['remove_from_compare_text'] .'"';
		}

		if ( 'yes' === $settings['show_text'] ) {
			$remove_from_compare_content .= ' <span>'. esc_html__($settings['remove_from_compare_text']) .'</span>';
		}

        echo '<button class="wpr-compare-add '. $add_button_hidden .'" data-product-id="' . $product->get_id() . '" '. $button_add_title .'>'. $add_to_compare_content .'</button>';
        echo '<button class="wpr-compare-remove '. $remove_button_hidden .'" data-product-id="' . $product->get_id() . '" '. $button_remove_title .'>'. $remove_from_compare_content .'</button>';


        // function create_compare_button() {
        // }

        // add_action( 'woocommerce_after_add_to_cart_button', 'create_compare_button' );
    }
}