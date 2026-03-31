<?php
namespace WprAddonsPro\Modules\InstagramFeedPro\Widgets;

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Instagram_Feed_Pro extends \WprAddons\Modules\InstagramFeed\Widgets\Wpr_Instagram_Feed {
	
	public function add_controls_group_limit() {
		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Number of Posts', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 20
			]
		);
				
		$this->add_control(
			'limit_mobile',
			[
				'label' => esc_html__( 'Number of Posts (Mobile)', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 2
			]
		);
	}

	public function add_responsive_control_insta_feed_slides_to_show() {
		$this->add_responsive_control(
			'insta_feed_slides_to_show',
			[
				'label' => esc_html__( 'Slides To Show', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'separator' => 'before',
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 2,
				'tablet_default' => 2,
				'mobile_extra_default' => 1,
				'mobile_default' => 1,
				'frontend_available' => true,
				'condition' => [
					'insta_layout_select' => 'layout-carousel',
				]
			]
		);
	}

	public function add_control_insta_feed_elements_defaults() {
		return [
			[
				'element_select' => 'icon',
				'element_location' => 'below',
				'element_display' => 'inline',
				'element_align_hr' => 'right'
			],
			[
				'element_select' => 'date',
				'element_display' => 'inline',
				'element_location' => 'below',
				'element_align_hr' => 'left'
			],
			[
				'element_select' => 'caption',
				'element_location' => 'below',
			],
			[
				'element_select' => 'sharing',
				'element_location' => 'above'
			]
		];
	}

	public function add_option_element_select() {
		return [
			'username' => esc_html__( 'Username', 'wpr-addons' ),
			'caption' => esc_html__( 'Caption', 'wpr-addons' ),
			'date' => esc_html__( 'Date', 'wpr-addons' ),
			'icon' => esc_html__( 'Icon', 'wpr-addons' ),
			'lightbox' => esc_html__( 'Lightbox', 'wpr-addons' ),
			'sharing' => esc_html__( 'Sharing', 'wpr-addons' )
		];
	}
	
	public function add_control_lightbox_popup_thumbnails() {
		$this->add_control(
			'lightbox_popup_thumbnails',
			[
				'label' => esc_html__( 'Show Thumbnails', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);
	}
	
	public function add_control_lightbox_popup_thumbnails_default() {
		$this->add_control(
			'lightbox_popup_thumbnails_default',
			[
				'label' => esc_html__( 'Show Thumbs by Default', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'lightbox_popup_thumbnails' => 'true'
				]
			]
		);
	}
	
	public function add_control_lightbox_popup_sharing() {
		$this->add_control(
			'lightbox_popup_sharing',
			[
				'label' => esc_html__( 'Show Sharing Button', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
			]
		);
	}
	
	public function add_option_social_networks() {
		return [
			'none' => esc_html__( 'None', 'wpr-addons' ),
			'facebook-f' => esc_html__( 'Facebook', 'wpr-addons' ),
			'twitter' => esc_html__( 'Twitter', 'wpr-addons' ),
			'linkedin-in' => esc_html__( 'LinkedIn', 'wpr-addons' ),
			'pinterest-p' => esc_html__( 'Pinterest', 'wpr-addons' ),
			'reddit' => esc_html__( 'Reddit', 'wpr-addons' ),
			'tumblr' => esc_html__( 'Tumblr', 'wpr-addons' ),
			'digg' => esc_html__( 'Digg', 'wpr-addons' ),
			'xing' => esc_html__( 'Xing', 'wpr-addons' ),
			'stumbleupon' => esc_html__( 'StumpleUpon', 'wpr-addons' ),
			'vk' => esc_html__( 'vKontakte', 'wpr-addons' ),
			'odnoklassniki' => esc_html__( 'OdnoKlassniki', 'wpr-addons' ),
			'get-pocket' => esc_html__( 'Pocket', 'wpr-addons' ),
			'skype' => esc_html__( 'Skype', 'wpr-addons' ),
			'whatsapp' => esc_html__( 'WhatsApp', 'wpr-addons' ),
			'telegram' => esc_html__( 'Telegram', 'wpr-addons' ),
		];
	}

	public function add_repeater_args_element_trim_text_by() {
		return [
			'word_count' => esc_html__( 'Word Count', 'wpr-addons' ),
			'letter_count' => esc_html__( 'Letter Count', 'wpr-addons' )
		];
	}
	
	public function add_repeater_args_element_sharing_icon_1() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'facebook-f',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_2() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'twitter',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_3() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'linkedin-in',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_4() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'reddit',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_5() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_icon_6() {
		return [
			'label' => esc_html__( 'Select Network', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => $this->add_option_social_networks(),
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
		];
	}
	
	public function add_repeater_args_element_sharing_trigger() {
		return [
			'label' => esc_html__( 'Trigger Button', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'condition' => [
				'element_select' => [ 'sharing' ],
			]
		];
	}
	
	public function add_repeater_args_element_sharing_trigger_icon() {
		return [
			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'fas fa-share',
			'options' => Utilities::get_svg_icons_array( 'sharing', [
				'fas fa-share' => esc_html__( 'Share', 'wpr-addons' ),
				'fas fa-share-square' => esc_html__( 'Share Square', 'wpr-addons' ),
				'far fa-share-square' => esc_html__( 'Share Sqaure Alt', 'wpr-addons' ),
				'fas fa-share-alt' => esc_html__( 'Share Alt', 'wpr-addons' ),
				'fas fa-share-alt-square' => esc_html__( 'Share Alt Square', 'wpr-addons' ),
				'fas fa-retweet' => esc_html__( 'Retweet', 'wpr-addons' ),
				'fas fa-paper-plane' => esc_html__( 'Paper Plane', 'wpr-addons' ),
				'far fa-paper-plane' => esc_html__( 'Paper Plane Alt', 'wpr-addons' ),
				'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
			] ),
			'condition' => [
				'element_select' => 'sharing',
				'element_sharing_trigger' => 'yes'
			]
		];
	}
	
	public function add_repeater_args_element_sharing_trigger_action() {
		return [
			'label' => esc_html__( 'Trigger Action', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'click',
			'options' => [
				'click' => esc_html__( 'Click', 'wpr-addons' ),
				'hover' => esc_html__( 'Hover', 'wpr-addons' ),
			],
			'condition' => [
				'element_select' => 'sharing',
				'element_sharing_trigger' => 'yes'
			]
		];
	}
	
	public function add_repeater_args_element_sharing_trigger_direction() {
		return [
			'label' => esc_html__( 'Trigger Direction', 'wpr-addons' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'right',
			'options' => [
				'top' => esc_html__( 'Top', 'wpr-addons' ),
				'right' => esc_html__( 'Right', 'wpr-addons' ),
				'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
				'left' => esc_html__( 'Left', 'wpr-addons' ),
			],
			'condition' => [
				'element_select' => 'sharing',
				'element_sharing_trigger' => 'yes'
			],
			'separator' => 'after'
		];
	}
	
	public function add_repeater_args_element_sharing_tooltip() {
		return [
			'label' => esc_html__( 'Label Tooltip', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'condition' => [
				'element_select' => [ 'sharing' ],
			],
			'separator' => 'after'
		];
	}

	public function add_section_style_sharing() {
		$this->start_controls_section(
			'section_style_sharing',
			[
				'label' => esc_html__( 'Sharing', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->start_controls_tabs( 'tabs_insta_feed_sharing_style' );

		$this->start_controls_tab(
			'tab_insta_feed_sharing_normal',
			[
				'label' => esc_html__( 'Normal', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'sharing_color',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_bg_color',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'sharing_border_color',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'border-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'sharing_tooltip_color',
			[
				'label'  => esc_html__( 'Tooltip Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-sharing-tooltip' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_tooltip_bg_color',
			[
				'label'  => esc_html__( 'Tooltip Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-sharing-tooltip' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .wpr-sharing-tooltip:before' => 'border-top-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_extra_text_color',
			[
				'label'  => esc_html__( 'Extra Text Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#9C9C9C',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block span[class*="wpr-insta-feed-extra-text"]' => 'color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_insta_feed_sharing_hover',
			[
				'label' => esc_html__( 'Hover', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'sharing_color_hr',
			[
				'label'  => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#605BE5',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'sharing_bg_color_hr',
			[
				'label'  => esc_html__( 'Background Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a:hover' => 'background-color: {{VALUE}}',
				]
			]
		);

		$this->add_control(
			'sharing_border_color_hr',
			[
				'label'  => esc_html__( 'Border Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#E8E8E8',
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a:hover' => 'border-color: {{VALUE}}',
				],
				'separator' => 'after',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sharing_transition_duration',
			[
				'label' => esc_html__( 'Transition Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'transition-duration: {{VALUE}}s;',
				],
				'separator' => 'after',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'sharing_typography',
				'selector' => '{{WRAPPER}} .wpr-insta-feed-item-sharing'
			]
		);

		$this->add_control(
			'sharing_border_type',
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
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'border-style: {{VALUE}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sharing_border_width',
			[
				'label' => esc_html__( 'Border Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template',
				'condition' => [
					'sharing_border_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'sharing_text_spacing',
			[
				'label' => esc_html__( 'Extra Text Spacing', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .wpr-insta-feed-extra-text-left' => 'padding-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .wpr-insta-feed-extra-text-right' => 'padding-left: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_control(
			'sharing_gutter',
			[
				'label' => esc_html__( 'Gutter', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'sharing_width',
			[
				'label' => esc_html__( 'Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'render_type' => 'template'
			]
		);

		$this->add_control(
			'sharing_height',
			[
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'render_type' => 'template'
			]
		);

		$this->add_responsive_control(
			'sharing_margin',
			[
				'label' => esc_html__( 'Margin', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default' => [
					'top' => 0,
					'right' => 0,
					'bottom' => 0,
					'left' => 0,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'sharing_radius',
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
					'{{WRAPPER}} .wpr-insta-feed-item-sharing .inner-block a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();
	}

	public function render_post_sharing_icons( $settings, $class, $result ) {
		$args = [
			'icons' => 'yes',
			'tooltip' => $settings['element_sharing_tooltip'],
			'url' => esc_url( $result->permalink ),
			'title' => esc_html( '' ),
			'text' => esc_html( isset($result->caption) ? $result->caption : '' ),
			'image' => esc_url( $result->media_url ),
		];

		$hidden_class = '';

		echo '<div class="'. esc_attr($class) .'">';
			echo '<div class="inner-block">';
				// Text: Before
				if ( 'before' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-insta-feed-extra-text-left">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}

				echo '<span class="wpr-post-sharing">';

					if ( 'yes' === $settings['element_sharing_trigger'] ) {
						$hidden_class = ' wpr-sharing-hidden';
						$attributes  = ' data-action="'. esc_attr( $settings['element_sharing_trigger_action'] ) .'"';
						$attributes .= ' data-direction="'. esc_attr( $settings['element_sharing_trigger_direction'] ) .'"';

						echo '<a class="wpr-sharing-trigger wpr-sharing-icon"'. $attributes .'>';
							if ( 'yes' === $settings['element_sharing_tooltip'] ) {
								echo '<span class="wpr-sharing-tooltip wpr-tooltip">'. esc_html__( 'Share', 'wpr-addons' ) .'</span>';
							}

							echo Utilities::get_wpr_icon( $settings['element_sharing_trigger_icon'], '' );
						echo '</a>';
					}


					echo '<span class="wpr-post-sharing-inner'. $hidden_class .'">';

					for ( $i = 1; $i < 7; $i++ ) {
						$args['network'] = $settings['element_sharing_icon_'. $i];

						echo Utilities::get_post_sharing_icon( $args );
					}

					echo '</span>';

				echo '</span>';

				// Text: After
				if ( 'after' === $settings['element_extra_text_pos'] ) {
					echo '<span class="wpr-insta-feed-extra-text-right">'. esc_html( $settings['element_extra_text'] ) .'</span>';
				}
			echo '</div>';
		echo '</div>';
	}
}