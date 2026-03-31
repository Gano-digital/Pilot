<?php
namespace WprAddonsPro\Modules\TaxonomyListPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Taxonomy_List_Pro extends \WprAddons\Modules\TaxonomyList\Widgets\Wpr_Taxonomy_List {
	
	public function get_post_taxonomies() {
		$post_taxonomies = [];
		$post_taxonomies['category'] = esc_html__( 'Categories', 'wpr-addons' );
		$post_taxonomies['post_tag'] = esc_html__( 'Tags', 'wpr-addons' );
		$post_taxonomies['product_cat'] = esc_html__( 'Product Categories', 'wpr-addons' );
		$post_taxonomies['product_tag'] = esc_html__( 'Product Tags', 'wpr-addons' );

		$custom_post_taxonomies = Utilities::get_custom_types_of( 'tax', true );
		foreach( $custom_post_taxonomies as $slug => $title ) {
			if ( 'product_tag' === $slug || 'product_cat' === $slug ) {
				continue;
			}

			if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
				$post_taxonomies[$slug] = esc_html( $title );
			} else {
				$post_taxonomies['pro-'. substr($slug, 0, 2)] = esc_html( $title ) .' (Expert)';
			}
		}

		return $post_taxonomies;
	}

	public function add_controls_group_sub_category_filters() {
		$this->add_control(
			'show_sub_categories',
			[
				'label' => esc_html__( 'Show Sub Categories', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes'
			]
		);

		$this->add_control(
			'show_sub_children',
			[
				'label' => esc_html__( 'Show Sub Children', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'show_sub_categories' => 'yes'
				]
			]
		);

		$this->add_control(
			'show_sub_categories_on_click',
			[
				'label' => esc_html__( 'Show Children on Click', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'show_sub_categories' => 'yes',
					'taxonomy_list_layout' => 'vertical'
				]
			]
		);
	}

	public function add_section_style_toggle_icon() {

		// Tab: Style ==============
		// Section: Toggle Icon --------
		$this->start_controls_section(
			'section_style_toggle_icon',
			[
				'label' => esc_html__( 'Toggle Icon', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_sub_categories_on_click' => 'yes'
				]
			]
		);

		$this->add_control(
			'toggle_icon_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-taxonomy-list li i.wpr-tax-dropdown' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_icon_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'render_type' => 'template',
				'description' => esc_html__('Changing Size may distort distances, click on icon to see actual result', 'wpr-addons'),
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-taxonomy-list li i.wpr-tax-dropdown' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'toggle_icon_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 8,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-taxonomy-list li .wpr-tax-dropdown' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

	}

}