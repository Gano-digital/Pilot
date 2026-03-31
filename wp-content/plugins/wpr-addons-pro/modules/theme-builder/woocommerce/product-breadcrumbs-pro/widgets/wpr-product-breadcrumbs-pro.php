<?php
namespace WprAddonsPro\Modules\ThemeBuilder\Woocommerce\ProductBreadcrumbsPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Product_Breadcrumbs_Pro extends Widget_Base {
	
	public function get_name() {
		return 'wpr-product-breadcrumbs-pro';
	}

	public function get_title() {
		return esc_html__( 'Product Breadcrumbs', 'wpr-addons' );
	}

	public function get_icon() {
		return 'wpr-icon eicon-product-breadcrumbs';
	}

	public function get_categories() {
		return Utilities::show_theme_buider_widget_on('product_single') ? [ 'wpr-woocommerce-builder-widgets' ] : [];
	}

	public function get_keywords() {
		return [ 'qq', 'product-breadcrumbs', 'product', 'woocommerce', 'breadcrumbs' ];//tmp
	}

	public function has_widget_inner_wrapper(): bool {
		return ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_breadcrumb_general',
			[
				'label' => esc_html__( 'General', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'breadcrumb_homepage',
			[
				'label' => esc_html__( 'Show Home Page', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'breadcrumb_separator',
			[
				'label' => esc_html__( 'Separator', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '/',
			]
		);

		$this->add_responsive_control(
            'breadcrumb_align',
            [
                'label' => esc_html__( 'Alignment', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'label_block' => false,
                'options' => [
					'left'    => [
						'title' => __( 'Left', 'wpr-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'wpr-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'wpr-addons' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .wpr-product-breadcrumbs' => 'text-align: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section

		// Styles ====================
		// Section: Style ------------
		$this->start_controls_section(
			'section_style_breadcrumb',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'breadcrumb_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#787878',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-breadcrumbs' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wpr-product-breadcrumbs a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'breadcrumb_color_hr',
			[
				'label'  => esc_html__( 'Hover Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-product-breadcrumbs a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'breadcrumb_typography',
				'selector' => '{{WRAPPER}} .wpr-product-breadcrumbs',
				'fields_options' => [
					'typography' => [
						'default' => 'custom',
					],
					'font_size' => [
						'default' => [
							'size' => '13',
							'unit' => 'px'
						]
					]
				]
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		// Get Settings
		$settings = $this->get_settings();

		$args = [
			'delimiter' => ' '. $settings['breadcrumb_separator'] .' ',
			'wrap_before' => '',
			'wrap_after' => '',
			'before' => '',
			'after' => '',
		];

		if ( '' === $settings['breadcrumb_homepage'] ) {
			$args['home'] = false;
		}

		// Output
		echo '<div class="wpr-product-breadcrumbs">';
			woocommerce_breadcrumb( $args );
		echo '</div>';

	}
	
}