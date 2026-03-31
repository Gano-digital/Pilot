<?php
namespace WprAddonsPro\Modules\MegaMenuPro\Widgets;

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;
use Elementor\Core\Responsive\Responsive;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Mega_Menu_Pro extends \WprAddons\Modules\MegaMenu\Widgets\Wpr_Mega_Menu {

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
					'move-up' => esc_html__( 'Move Up', 'wpr-addons' ),
					'move-down' => esc_html__( 'Move Down', 'wpr-addons' ),
					'move-left' => esc_html__( 'Move Left (VR Menu)', 'wpr-addons' ),
					'move-right' => esc_html__( 'Move Right (VR Menu)', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-sub-menu-fx-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_mob_menu_show_on() {
		$breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();

		$this->add_control(
			'mob_menu_show_on',
			[
				'label' => esc_html__( 'Show On', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tablet',
				'options' => [
					// 'none' => esc_html__( 'Don\'t Show', 'wpr-addons' ),
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

	public function add_controls_group_offcanvas() {
		$this->add_control(
			'mob_menu_display_as',
			[
				'label' => esc_html__( 'Display As', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dropdown',
				'options' => [
					'dropdown' => esc_html__( 'Dropdown', 'wpr-addons' ),
					'offcanvas' => esc_html__( 'Off-Canvas', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-mobile-menu-display-',
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'mob_menu_offcanvas_align',
			[
				'label' => esc_html__( 'Off-Canvas Slide', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' )
				],
				'prefix_class' => 'wpr-mobile-menu-offcanvas-slide-',
				'condition' => [
					'mob_menu_display_as' => 'offcanvas',
				],
			]
		);

		$this->add_responsive_control(
			'mob_menu_offcanvas_width',
			[
				'label' => esc_html__( 'Off-Canvas Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'tablet_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.wpr-mobile-menu-display-offcanvas .wpr-mobile-mega-menu-wrap' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'mob_menu_display_as' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'mob_menu_offcanvas_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => Utilities::wpr_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'mob_menu_display_as' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'mob_menu_offcanvas_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.5,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}}.wpr-mobile-menu-display-offcanvas .wpr-mobile-mega-menu-wrap' => 'transition-duration: {{VALUE}}s;',
					'{{WRAPPER}}.wpr-mobile-menu-display-offcanvas .wpr-mobile-mega-menu > li > a,
					 {{WRAPPER}}.wpr-mobile-menu-display-offcanvas .wpr-mobile-mega-menu .wpr-mobile-sub-menu > li > a,
					 {{WRAPPER}}.wpr-mobile-menu-display-offcanvas .wpr-mobile-sub-mega-menu,
					 {{WRAPPER}}.wpr-mobile-menu-display-offcanvas .wpr-mobile-mega-menu > li > .wpr-mobile-sub-menu' => 'transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'mob_menu_display_as' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'mob_menu_offcanvas_logo',
			[
				'label' => esc_html__( 'Off-Canvas Logo', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'mob_menu_display_as' => 'offcanvas',
				],
			]
		);

		$this->add_control(
			'mob_menu_toggle_offcanvas_backface',
			[
				'label' => esc_html__( 'Toggle Backface Menu', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'mob_menu_display_as' => 'offcanvas',
				],
				'render_type' => 'template'
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
					'mob_menu_show_on!' => 'none',
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