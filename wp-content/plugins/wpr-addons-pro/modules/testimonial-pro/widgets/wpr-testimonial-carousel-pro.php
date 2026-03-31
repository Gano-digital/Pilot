<?php
namespace WprAddonsPro\Modules\TestimonialPro\Widgets;

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Testimonial_Carousel_Pro extends \WprAddons\Modules\Testimonial\Widgets\Wpr_Testimonial_Carousel {

	public function add_repeater_args_social_media() {
		return [
			'label' => esc_html__( 'Social Media', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
		];
	}

	public function add_repeater_args_social_media_is_external() {
		return [
			'label' => esc_html__( 'Open in new window', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_media_nofollow() {
		return [
			'label' => esc_html__( 'Add nofollow', 'wpr-addons' ),
			'type' => Controls_Manager::SWITCHER,
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_section_1() {
		return [
			'label' => esc_html__( 'Social 1', 'wpr-addons' ),
			'type' => Controls_Manager::HEADING,
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_icon_1() {
		return [
			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
			'type' => Controls_Manager::ICONS,
			'skin' => 'inline',
			'label_block' => false,
			'default' => [
				'value' => 'fab fa-facebook-f',
				'library' => 'fa-brands',
			],
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_url_1() {
		return [
			'label' => esc_html__( 'Social URL', 'wpr-addons' ),
			'type' => Controls_Manager::URL,
			'dynamic' => [
				'active' => true,
			],
			'show_external' => false,
			'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_section_2() {
		return [
			'label' => esc_html__( 'Social 2', 'wpr-addons' ),
			'type' => Controls_Manager::HEADING,
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_icon_2() {
		return [
			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
			'type' => Controls_Manager::ICONS,
			'skin' => 'inline',
			'label_block' => false,
			'default' => [
				'value' => 'fab fa-pinterest',
				'library' => 'fa-brands',
			],
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_url_2() {
		return [
			'label' => esc_html__( 'Social URL', 'wpr-addons' ),
			'type' => Controls_Manager::URL,
			'dynamic' => [
				'active' => true,
			],
			'show_external' => false,
			'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_section_3() {
		return [
			'label' => esc_html__( 'Social 3', 'wpr-addons' ),
			'type' => Controls_Manager::HEADING,
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_icon_3() {
		return [
			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
			'type' => Controls_Manager::ICONS,
			'skin' => 'inline',
			'label_block' => false,
			'default' => [
				'value' => 'fab fa-twitter',
				'library' => 'fa-brands',
			],
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_url_3() {
		return [
			'label' => esc_html__( 'Social URL', 'wpr-addons' ),
			'type' => Controls_Manager::URL,
			'dynamic' => [
				'active' => true,
			],
			'show_external' => false,
			'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_section_4() {
		return [
			'label' => esc_html__( 'Social 4', 'wpr-addons' ),
			'type' => Controls_Manager::HEADING,
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_icon_4() {
		return [
			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
			'type' => Controls_Manager::ICONS,
			'skin' => 'inline',
			'label_block' => false,
			'default' => [
				'value' => 'fab fa-dribbble',
				'library' => 'fa-brands',
			],
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_url_4() {
		return [
			'label' => esc_html__( 'Social URL', 'wpr-addons' ),
			'type' => Controls_Manager::URL,
			'dynamic' => [
				'active' => true,
			],
			'show_external' => false,
			'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_section_5() {
		return [
			'label' => esc_html__( 'Social 5', 'wpr-addons' ),
			'type' => Controls_Manager::HEADING,
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_icon_5() {
		return [
			'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
			'type' => Controls_Manager::ICONS,
			'skin' => 'inline',
			'label_block' => false,
			'default' => [
				'value' => 'fab fa-linkedin',
				'library' => 'fa-brands',
			],
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_repeater_args_social_url_5() {
		return [
			'label' => esc_html__( 'Social URL', 'wpr-addons' ),
			'type' => Controls_Manager::URL,
			'dynamic' => [
				'active' => true,
			],
			'show_external' => false,
			'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
			'condition' => [
				'social_media' => 'yes',
			],
		];
	}

	public function add_control_testimonial_amount() {
		$this->add_responsive_control(
			'testimonial_amount',
			[
				'label' => esc_html__( 'Columns', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 3,
				'widescreen_default' => 3,
				'laptop_default' => 3,
				'tablet_extra_default' => 3,
				'tablet_default' => 2,
				'mobile_extra_default' => 2,
				'mobile_default' => 1,
				'options' => [
					1 => esc_html__( 'One', 'wpr-addons' ),
					2 => esc_html__( 'Two', 'wpr-addons' ),
					3 => esc_html__( 'Three', 'wpr-addons' ),
					4 => esc_html__( 'Four', 'wpr-addons' ),
					5 => esc_html__( 'Five', 'wpr-addons' ),
					6 => esc_html__( 'Six', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-testimonial-slider-columns-%s',
				'render_type' => 'template',
				'frontend_available' => true,
				'separator' => 'before',
			]
		);
	}

	public function add_control_testimonial_icon() {
		$this->add_control(
			'testimonial_icon',
			[
				'label' => esc_html__( 'Select Quote Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => Utilities::get_svg_icons_array( 'blockquote', [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'fas fa-quote-left' => esc_html__( 'Quote Left', 'wpr-addons' ),
					'fas fa-quote-right' => esc_html__( 'Quote Right', 'wpr-addons' ),
					'svg-icons' => esc_html__( 'SVG Icons -----', 'wpr-addons' ),
				] ),
				'separator' => 'before',
			]
		);
	}

	public function add_control_testimonial_rating_score() {
		$this->add_control(
			'testimonial_rating_score',
			[
				'label' => esc_html__( 'Show Score', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'condition' => [
					'testimonial_rating' => 'yes',
				],
			]
		);
	}

	public function render_pro_element_testimonial_score($rating_amount) {
		$settings = $this->get_settings();

 		if ( 'yes' === $settings['testimonial_rating_score'] ) {
			if ( $rating_amount == 1 || $rating_amount == 2 || $rating_amount == 3 || $rating_amount == 4 || $rating_amount == 5 )  {
				$rating_amount = $rating_amount .'.0';
			}

			echo '<span>'. $rating_amount .'</span>';
		}
	}

	public function render_pro_element_social_media( $item, $item_count ) {
		$settings = $this->get_settings();

		if ( $item['social_media'] === 'yes' ) :
			$this->add_render_attribute( 'social_attribute' . $item_count, 'class', 'wpr-testimonial-social' );
					
			if ( $item['social_media_is_external'] ) {
				$this->add_render_attribute( 'social_attribute' . $item_count, 'target', '_blank' );
			}

			if ( $item['social_media_nofollow'] ) {
				$this->add_render_attribute( 'social_attribute' . $item_count, 'nofollow', '' );
			}
		?>

		<div class="wpr-testimonial-social-media elementor-clearfix">
			
			<?php if ( '' !== $item['social_icon_1']['value'] ) : ?>
				<a href="<?php echo esc_url( $item['social_url_1']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' . $item_count ); ?>>
					<i class="<?php echo esc_html( $item['social_icon_1']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>
		
			<?php if ( '' !== $item['social_icon_2']['value'] ) : ?>
				<a href="<?php echo esc_url( $item['social_url_2']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' . $item_count ); ?>>
					<i class="<?php echo esc_html( $item['social_icon_2']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>

			<?php if ( '' !== $item['social_icon_3']['value'] ) : ?>
				<a href="<?php echo esc_url( $item['social_url_3']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' . $item_count ); ?>>
					<i class="<?php echo esc_html( $item['social_icon_3']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>

			<?php if ( '' !== $item['social_icon_4']['value'] ) : ?>
				<a href="<?php echo esc_url( $item['social_url_4']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' . $item_count ); ?>>
					<i class="<?php echo esc_html( $item['social_icon_4']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>

			<?php if ( '' !== $item['social_icon_5']['value'] ) : ?>
				<a href="<?php echo esc_url( $item['social_url_5']['url'] ); ?>" <?php echo $this->get_render_attribute_string( 'social_attribute' . $item_count ); ?>>
					<i class="<?php echo esc_html( $item['social_icon_5']['value'] ); ?>"></i>
				</a>
			<?php endif; ?>

		</div>

		<?php
		endif;
	}

	public function add_control_stack_testimonial_autoplay() {
		$this->add_control(
			'testimonial_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'testimonial_autoplay_duration',
			[
				'label' => esc_html__( 'Autoplay Speed', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 0,
				'max' => 15,
				'step' => 0.5,
				'frontend_available' => true,
				'condition' => [
					'testimonial_autoplay' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'testimonial_pause_on_hover',
			[
				'label' => esc_html__( 'Pause Slide on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'testimonial_autoplay' => 'yes',
				],
			]
		);
	}

	public function add_control_stack_nav_position() {
		$this->add_control(
			'nav_position',
			[
				'label' => esc_html__( 'Positioning', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'custom',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-testimonial-nav-position-',
			]
		);

		$this->add_control(
			'nav_position_default',
			[
				'label' => esc_html__( 'Align', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'label_block' => false,
				'default' => 'top-left',
				'options' => [
					'top-left' => esc_html__( 'Top Left', 'wpr-addons' ),
					'top-center' => esc_html__( 'Top Center', 'wpr-addons' ),
					'top-right' => esc_html__( 'Top Right', 'wpr-addons' ),
					'bottom-left' => esc_html__( 'Bottom Left', 'wpr-addons' ),
					'bottom-center' => esc_html__( 'Bottom Center', 'wpr-addons' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-testimonial-nav-align-',
				'condition' => [
					'nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'nav_outer_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Outer Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}}[class*="wpr-testimonial-nav-align-top"] .wpr-testimonial-arrow-container' => 'top: {{SIZE}}px;',
					'{{WRAPPER}}[class*="wpr-testimonial-nav-align-bottom"] .wpr-testimonial-arrow-container' => 'bottom: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-testimonial-nav-align-top-left .wpr-testimonial-arrow-container' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-testimonial-nav-align-bottom-left .wpr-testimonial-arrow-container' => 'left: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-testimonial-nav-align-top-right .wpr-testimonial-arrow-container' => 'right: {{SIZE}}px;',
					'{{WRAPPER}}.wpr-testimonial-nav-align-bottom-right .wpr-testimonial-arrow-container' => 'right: {{SIZE}}px;',
				],
				'condition' => [
					'nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'nav_inner_distance',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Inner Distance', 'wpr-addons' ),
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow-container .wpr-testimonial-prev-arrow' => 'margin-right: {{SIZE}}px;',
				],
				'condition' => [
					'nav_position' => 'default',
				],
			]
		);

		$this->add_responsive_control(
			'nav_position_top',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Vertical Position', 'wpr-addons' ),
				'size_units' => [ '%','px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 1200,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 52,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-arrow' => 'top: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'before',
				'condition' => [
					'nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'nav_position_left',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Left Position', 'wpr-addons' ),
				'size_units' => [ '%','px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 120,
					],
					'px' => [
						'min' => 0,
						'max' => 1200,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-prev-arrow' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'nav_position' => 'custom',
				],
			]
		);

		$this->add_responsive_control(
			'nav_position_right',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Right Position', 'wpr-addons' ),
				'size_units' => [ '%','px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 120,
					],
					'px' => [
						'min' => 0,
						'max' => 1200,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 2,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-next-arrow' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'nav_position' => 'custom',
				],
			]
		);
	}

	public function add_control_dots_hr() {
		$this->add_responsive_control(
			'dots_hr',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__( 'Horizontal Position', 'wpr-addons' ),
				'size_units' => [ '%','px' ],
				'range' => [
					'%' => [
						'min' => -20,
						'max' => 120,
					],
					'px' => [
						'min' => -200,
						'max' => 1200,
					],
				],											
				'default' => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-testimonial-dots' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
	}

}