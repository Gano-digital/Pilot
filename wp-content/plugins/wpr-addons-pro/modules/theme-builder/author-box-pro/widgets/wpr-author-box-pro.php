<?php
namespace WprAddonsPro\Modules\ThemeBuilder\AuthorBoxPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Border;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Author_Box_Pro extends \WprAddons\Modules\ThemeBuilder\AuthorBox\Widgets\Wpr_Author_Box {

	public function add_controls_group_author_name_links_to() {
		$this->add_control(
			'author_name_links_to',
			[
				'label' => esc_html__( 'Links To', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'wpr-addons' ),
					'posts' => esc_html__( 'Author Posts', 'wpr-addons' ),
					'website' => esc_html__( 'Website', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'author_name' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_name_link_tab',
			[
				'label' => esc_html__( 'Open in New Tab', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'author_name' => 'yes',
					'author_name_links_to!' => 'none',
				]
			]
		);
	}

	public function add_controls_group_author_title_links_to() {
		$this->add_control(
			'author_title_links_to',
			[
				'label' => esc_html__( 'Links To', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'Nothing', 'wpr-addons' ),
					'posts' => esc_html__( 'Author Posts', 'wpr-addons' ),
					'website' => esc_html__( 'Website', 'wpr-addons' ),
				],
				'default' => 'none',
				'condition' => [
					'author_title' => 'yes',
				]
			]
		);

		$this->add_control(
			'author_title_link_tab',
			[
				'label' => esc_html__( 'Open in New Tab', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'author_title' => 'yes',
					'author_title_links_to!' => 'none',
				]
			]
		);
	}

	public function add_control_author_bio() {
		$this->add_control(
			'author_bio',
			[
				'label' => esc_html__( 'Show Biography', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'separator' => 'before'
			]
		);
	}

	public function add_section_style_bio() {
		$this->start_controls_section(
			'section_style_bio',
			[
				'label' => esc_html__( 'Biography', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'bio_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#555555',
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-bio' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bio_typography',
				'selector' => '{{WRAPPER}} .wpr-author-box-bio',
				'fields_options' => [
					'typography'      => [
						'default' => 'custom',
					],
					'font_size'      => [
						'default'    => [
							'size' => '15',
							'unit' => 'px',
						],
					]
				]
			]
		);

		$this->add_responsive_control(
			'bio_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 15,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-author-box-bio' => 'margin-bottom: {{SIZE}}px',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

}