<?php
namespace WprAddonsPro\Modules\PromoBoxPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Promo_Box_Pro extends \WprAddons\Modules\PromoBox\Widgets\Wpr_Promo_Box {

	public function add_control_image_style() {
		$this->add_control(
			'image_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cover',
				'options' => [
					'classic' => esc_html__( 'Classic', 'wpr-addons' ),
					'cover' => esc_html__( 'Cover', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-promo-box-style-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_image_position() {
		$this->add_control(
			'image_position',
			[
				'label' => esc_html__( 'Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'center' => esc_html__( 'Center', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-promo-box-image-position-',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'image_style' => 'classic',
				],
			]
		);
	}

	public function add_control_image_min_width() {
		$this->add_responsive_control(
			'image_min_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Min Width', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 270,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-image' => 'min-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_style' => 'classic',
					'image_position' => [ 'left','right' ],
				],
			]
		);
	}

	public function add_control_image_min_height() {
		$this->add_responsive_control(
			'image_min_height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Height', 'wpr-addons' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 1000,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-image' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_style' => 'classic',
				],
			]
		);
	}

	public function add_control_content_bg_color() {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#212121',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-promo-box-content',
				'condition' => [
					'image_style' => 'classic',
				],
			]
		);
	}

	public function add_control_content_hover_bg_color() {
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'content_hover_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#ddb34f',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-promo-box:hover .wpr-promo-box-content',
				'condition' => [
					'image_style' => 'classic',
				],
			]
		);
	}

	public function add_control_icon_animation_section() {
		$this->add_control(
			'icon_animation_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'content_icon_type!' => 'none',
				],
			]
		);
	}

	public function add_control_group_icon_animation_section() {
		$this->add_control(
			'icon_animation_section',
			[
				'label' => esc_html__( 'Icon', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'content_icon_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
				'condition' => [
					'content_icon_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-icon' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'icon_animation!' => 'none',
					'content_icon_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-icon' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;',
				],
				'condition' => [
					'icon_animation!' => 'none',
					'content_icon_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->add_args_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'icon_animation!' => 'none',
					'content_icon_type!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'medium',
				'condition' => [
					'icon_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'icon_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'icon_animation_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
				'condition' => [
					'content_icon_type!' => 'none',
				],
			]
		);
	}

	public function add_control_group_title_animation_section() {
		$this->add_control(
			'title_animation_section',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
			]
		);

		$this->add_control(
			'title_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-title' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'title_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'title_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-title' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'title_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'title_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->add_args_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'title_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'title_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'medium',
				'condition' => [
					'title_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'title_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'title_animation!' => 'none',
				],
			]
		);
	}

	public function add_control_group_description_animation_section() {
		$this->add_control(
			'description_animation_section',
			[
				'label' => esc_html__( 'Description', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
			]
		);

		$this->add_control(
			'description_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-description' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'description_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'description_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.2,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-description' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'description_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'description_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->add_args_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'description_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'description_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'medium',
				'condition' => [
					'description_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'description_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'description_animation!' => 'none',
				],
			]
		);
	}

	public function add_control_group_btn_animation_section() {
		$this->add_control(
			'btn_animation_section',
			[
				'label' => esc_html__( 'Button', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'btn_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations',
				'default' => 'none',
				'condition' => [
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'btn_animation_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.4,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-btn-wrap' => '-webkit-transition-duration: {{VALUE}}s; transition-duration: {{VALUE}}s;'
				],
				'condition' => [
					'btn_animation!' => 'none',
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'btn_animation_delay',
			[
				'label' => esc_html__( 'Animation Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-btn-wrap' => '-webkit-transition-delay: {{VALUE}}s;transition-delay: {{VALUE}}s;'
				],
				'condition' => [
					'btn_animation!' => 'none',
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'btn_animation_timing',
			[
				'label' => esc_html__( 'Animation Timing', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->add_args_animation_timings(),
				'default' => 'ease-default',
				'condition' => [
					'btn_animation!' => 'none',
					'content_link_type' => ['btn','btn-title'],
				],
			]
		);

		$this->add_control(
			'btn_animation_size',
			[
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'default' => 'medium',
				'condition' => [
					'btn_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'btn_animation_tr',
			[
				'label' => esc_html__( 'Animation Transparency', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'return_value' => 'yes',
				'condition' => [
					'btn_animation!' => 'none',
				],
			]
		);
	}

	public function add_control_border_animation() {
		$this->add_control(
			'border_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'layla' => esc_html__( 'Layla', 'wpr-addons' ),
					'oscar' => esc_html__( 'Oscar', 'wpr-addons' ),
					'bubba' => esc_html__( 'Bubba', 'wpr-addons' ),
					'romeo' => esc_html__( 'Romeo', 'wpr-addons' ),
					'chicho' => esc_html__( 'Chicho', 'wpr-addons' ),
					'apollo' => esc_html__( 'Apollo', 'wpr-addons' ),
					'jazz' => esc_html__( 'Jazz', 'wpr-addons' ),
				],
				'default' => 'chicho',
				'condition' => [
					'image[url]!' => '',
				],
			]
		);
	}

	public function add_section_badge() {
		$this->start_controls_section(
			'section_badge',
			[
				'label' => esc_html__( 'Badge', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'badge_style',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'default' => 'corner',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'corner' => esc_html__( 'Corner Badge', 'wpr-addons' ),
					'cyrcle' => esc_html__( 'Cyrcle Badge', 'wpr-addons' ),
					'flag' => esc_html__( 'Flag Badge', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'badge_title',
			[
				'label' => esc_html__( ' Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Hot',
				'condition' => [
					'badge_style!' => 'none',
				],
			]
		);

		$this->add_control(
			'badge_title_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

        $this->add_responsive_control(
			'badge_cyrcle_size',
			[
				'label' => esc_html__( 'Size', 'wpr-addons' ),
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
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-badge-cyrcle .wpr-promo-box-badge-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'badge_style' => 'cyrcle',
					'badge_style!' => 'none',
				],
			]
		);

        $this->add_responsive_control(
			'badge_distance',
			[
				'label' => esc_html__( 'Distance', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-badge-corner .wpr-promo-box-badge-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg);',
					'{{WRAPPER}} .wpr-promo-box-badge-flag' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'badge_style!' => [ 'none', 'cyrcle' ],
				],	
			
			]
		);

		$this->add_control(
            'badge_hr_position',
            [
                'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'right',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'wpr-addons' ),
                        'icon' => 'eicon-h-align-right',
                    ]
                ],
				'separator' => 'before',
                'condition' => [
					'badge_style!' => 'none',
				],
            ]
        );

		$this->end_controls_section(); // End Controls Section
	}

	public function add_section_style_badge() {
		$this->start_controls_section(
			'section_style_badge',
			[
				'label' => esc_html__( 'Badge', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'badge_style!' => 'none',
				],
			]
		);

		$this->add_control(
			'badge_text_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-badge-inner' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'type' => Controls_Manager::COLOR,
				'label' => esc_html__( 'Background Color', 'wpr-addons' ),
				'default' => '#e83d17',
				'selectors' => [
					'{{WRAPPER}} .wpr-promo-box-badge-inner' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .wpr-promo-box-badge-flag:before' => ' border-top-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .wpr-promo-box-badge-inner'
			]
		);

		$this->add_control(
			'badge_box_shadow_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'label' => esc_html__( 'Typography', 'wpr-addons' ),
				'selector' => '{{WRAPPER}} .wpr-promo-box-badge-inner'
			]
		);

		$this->add_responsive_control(
			'badge_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 0,
					'right' => 10,
					'bottom' => 0,
					'left' => 10,
				],
				'size_units' => [ 'px', ],
				'selectors' => [
				'{{WRAPPER}} .wpr-promo-box-badge .wpr-promo-box-badge-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function render_pro_element_badge() {
		$settings = $this->get_settings();

		if ( $settings['badge_style'] !== 'none' && ! empty( $settings['badge_title'] ) ) :

			$this->add_render_attribute( 'wpr-promo-box-badge-attr', 'class', 'wpr-promo-box-badge wpr-promo-box-badge-'. $settings[ 'badge_style'] );
			if ( ! empty( $settings['badge_hr_position'] ) ) :
				$this->add_render_attribute( 'wpr-promo-box-badge-attr', 'class', 'wpr-promo-box-badge-'. $settings['badge_hr_position'] );
			endif; ?>

			<div <?php echo $this->get_render_attribute_string( 'wpr-promo-box-badge-attr' ); ?>>
				<div class="wpr-promo-box-badge-inner"><?php echo $settings['badge_title']; ?></div>
			</div>
		<?php endif;
	}

}