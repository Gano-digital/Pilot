<?php
namespace WprAddonsPro\Modules\NavMenuPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Nav_Menu_Pro extends \WprAddons\Modules\NavMenu\Widgets\Wpr_Nav_Menu {

	public function add_control_menu_layout() {
		$this->add_control(
			'menu_layout',
			[
				'label' => esc_html__( 'Menu Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => [
					'horizontal' => esc_html__( 'Horizontal', 'wpr-addons' ),
					'vertical' => esc_html__( 'Vertical', 'wpr-addons' ),
				],
				'frontend_available' => true,
			]
		);
	}

	public function add_control_menu_items_pointer() {
		$this->add_control(
			'menu_items_pointer',
			[
				'label' => esc_html__( 'Hover Effect', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'underline' => esc_html__( 'Underline', 'wpr-addons' ),
					'overline' => esc_html__( 'Overline', 'wpr-addons' ),
					'double-line' => esc_html__( 'Double Line', 'wpr-addons' ),
					'border' => esc_html__( 'Border', 'wpr-addons' ),
					'background' => esc_html__( 'Background', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-pointer-',
			]
		);
	}

	public function add_control_pointer_animation_border() {
		$this->add_control(
			'pointer_animation_border',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'grow' => 'Grow',
					'shrink' => 'Shrink',
				],
				'prefix_class' => 'wpr-pointer-border-fx wpr-pointer-fx-',
				'condition' => [
					'menu_items_pointer' => 'border',
				],
			]
		);
	}

	public function add_control_pointer_animation_background() {
		$this->add_control(
			'pointer_animation_background',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'grow' => 'Grow',
					'shrink' => 'Shrink',
					'sweep' => 'Sweep',
					'skew' => 'Skew',
				],
				'prefix_class' => 'wpr-pointer-background-fx wpr-pointer-fx-',
				'condition' => [
					'menu_items_pointer' => 'background',
				],
			]
		);
	}

	public function add_control_pointer_animation_line() {
		$this->add_control(
			'pointer_animation_line',
			[
				'label' => esc_html__( 'Hover Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'none' => 'None',
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop' => 'Drop',
				],
				'prefix_class' => 'wpr-pointer-line-fx wpr-pointer-fx-',
				'condition' => [
					'menu_items_pointer' => [ 'underline', 'overline', 'double-line' ],
				],
			]
		);
	}

	public function add_control_menu_items_submenu_entrance() {
		$this->add_control(
			'menu_items_submenu_entrance',
			[
				'label' => esc_html__( 'Sub Menu Entrance', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'slide' => esc_html__( 'Slide', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-sub-menu-fx-',
			]
		);
	}

	public function add_control_mob_menu_display() {
		$breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

		$this->add_control(
			'mob_menu_display',
			[
				'label' => esc_html__( 'Show On', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'mobile',
				'options' => [
					'none' => esc_html__( 'Don\'t Show', 'wpr-addons' ),
					'always' => esc_html__( 'All Devices', 'wpr-addons' ),
					/* translators: %d: Breakpoint number. */
					'mobile' => sprintf( esc_html__( 'Mobile (≤ %dpx)', 'wpr-addons' ), $breakpoints['mobile']->get_default_value() ),
					/* translators: %d: Breakpoint number. */
					'tablet' => sprintf( esc_html__( 'Tablet (≤ %dpx)', 'wpr-addons' ), $breakpoints['tablet']->get_default_value() ),
				],
				'prefix_class' => 'wpr-nav-menu-bp-',
			]
		);
	}

	public function add_control_toggle_btn_style() {
		$this->add_control(
			'toggle_btn_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hamburger',
				'options' => [
					'hamburger' => esc_html__( 'Hamburger', 'wpr-addons' ),
					'text' => esc_html__( 'Text', 'wpr-addons' ),
				],
				'condition' => [
					'mob_menu_display!' => 'none',
				],
			]
		);
	}

	public function add_control_sub_menu_width() {
		$this->add_responsive_control(
			'sub_menu_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 180,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-sub-menu' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
	}

}