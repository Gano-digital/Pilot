<?php
namespace WprAddonsPro\Modules\ThemeBuilder\PostCommentsPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Post_Comments_Pro extends \WprAddons\Modules\ThemeBuilder\PostComments\Widgets\Wpr_Post_Comments {
	
	public function add_control_comments_avatar_size() {
		$this->add_responsive_control(
			'comments_avatar_size',
			[
				'label' => esc_html__( 'Avatar Size', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 60,
				'min' => 10,
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-avatar img' => 'width: {{SIZE}}px;',
				],
				'render_type' => 'template',
				'condition' => [
					'comments_avatar' => 'yes'
				],
			]
		);
	}

	public function add_control_avatar_gutter() {
		$this->add_responsive_control(
			'avatar_gutter',
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
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-comment-meta, .wpr-comment-content' => 'margin-left: calc({{comments_avatar_size.VALUE}}px + {{SIZE}}{{UNIT}});',
					'{{WRAPPER}}.wpr-comment-reply-separate .wpr-comment-reply' => 'margin-left: calc({{comments_avatar_size.VALUE}}px + {{SIZE}}{{UNIT}});',
				],
				'separator' => 'after',
			]
		);
	}

	public function add_control_comments_form_layout() {
		$this->add_control(
			'comments_form_layout',
			[
				'label' => esc_html__( 'Select Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'style-5',
				'options' => [
					'style-1' => esc_html__( 'Style 1', 'wpr-addons' ),
					'style-2' => esc_html__( 'Style 2', 'wpr-addons' ),
					'style-3' => esc_html__( 'Style 3', 'wpr-addons' ),
					'style-4' => esc_html__( 'Style 4', 'wpr-addons' ),
					'style-5' => esc_html__( 'Style 5', 'wpr-addons' ),
					'style-6' => esc_html__( 'Style 6', 'wpr-addons' ),
				],
				'separator' => 'before'
			]
		);
	}

	public function add_control_comment_form_placeholders() {
		$this->add_control(
			'comment_form_placeholders',
			[
				'label' => esc_html__( 'Show Placeholders', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);
	}
		
}