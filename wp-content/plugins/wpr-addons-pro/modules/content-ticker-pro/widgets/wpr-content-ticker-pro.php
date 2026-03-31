<?php
namespace WprAddonsPro\Modules\ContentTickerPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Content_Ticker_Pro extends \WprAddons\Modules\ContentTicker\Widgets\Wpr_Content_Ticker {

	public $post_types;

	public function add_control_post_type() {
		$this->add_control(
			'post_type',
			[
				'label' => esc_html__( 'Select Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'dynamic' => esc_html__( 'Dynamic', 'wpr-addons' ),
					'custom' => esc_html__( 'Custom', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_query_source() {
		// Get Available Post Types
		$this->post_types = [];
		$this->post_types['post'] = esc_html__( 'Posts', 'wpr-addons' );
		$this->post_types['page'] = esc_html__( 'Pages', 'wpr-addons' );

		$custom_post_types = Utilities::get_custom_types_of( 'post', true );
		foreach( $custom_post_types as $slug => $title ) {
			if ( 'product' === $slug || 'e-landing-page' === $slug ) {
				continue;
			}

			if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
				$this->post_types[$slug] = esc_html( $title );
			} else {
				$this->post_types['pro-'. substr($slug, 0, 2)] = esc_html( $title ) .' (Expert)';
			}
		}

		$this->post_types['product'] = 'Products';
		$this->post_types['featured'] = 'Featured';
		$this->post_types['sale'] = 'On Sale';

		$this->add_control(
			'query_source',
			[
				'label' => esc_html__( 'Source', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => $this->post_types,
			]
		);
	}

	public function add_control_type_select() {
		$this->add_control(
			'type_select',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Select Type', 'wpr-addons' ),
				'default' => 'slider',
				'options' => [
					'slider' => esc_html__( 'Slider', 'wpr-addons' ),
					'marquee' => esc_html__( 'Marquee', 'wpr-addons' ),
				],
				'render_type' => 'template',
				'separator' => 'before',
			]
		);
	}

	public function add_control_slider_effect() {
		$this->add_control(
			'slider_effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Effect', 'wpr-addons' ),
				'default' => 'hr-slide',
				'options' => [
					'typing' => esc_html__( 'Typing', 'wpr-addons' ),
					'fade' => esc_html__( 'Fade', 'wpr-addons' ),
					'hr-slide' => esc_html__( 'Horizontal Slide', 'wpr-addons' ),
					'vr-slide' => esc_html__( 'Vertical Slide', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-ticker-effect-',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'type_select' => 'slider',
				],
			]
		);
	}

	public function add_control_slider_effect_cursor() {
		$this->add_control(
			'slider_effect_cursor',
			[
				'label' => esc_html__( 'Typing Cursor', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '_',
				'selectors' => [
					'{{WRAPPER}}.wpr-ticker-effect-typing .wpr-ticker-title:after' => 'content: "{{VALUE}}";',
				],
				'condition' => [
					'type_select' => 'slider',
					'slider_effect' => 'typing',
				],
			]
		);
	}

	public function add_control_heading_icon_type() {
		$this->add_control(
			'heading_icon_type',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Select Type', 'wpr-addons' ),
				'default' => 'circle',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'fontawesome' => esc_html__( 'FontAwesome', 'wpr-addons' ),
					'circle' => esc_html__( 'Circle', 'wpr-addons' ),
				],
			]
		);
	}

	public function add_control_marquee_direction() {
		$this->add_control(
			'marquee_direction',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Direction', 'wpr-addons' ),
				'default' => 'left',
				'options' => [
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-ticker-marquee-direction-',
				'render_type' => 'template',
				'separator' => 'before',
				'condition' => [
					'type_select' => 'marquee',
				],
			]
		);
	}

	public function add_control_marquee_pause_on_hover() {
		$this->add_control(
			'marquee_pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'true',
				'return_value' => 'true',
				'condition' => [
					'type_select' => 'marquee',
				],
			]
		);
	}

	public function add_control_marquee_effect_duration() {
		$this->add_control(
			'marquee_effect_duration',
			[
				'label' => esc_html__( 'Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 50,
				'min' => 0,
				'step' => 0.5,	
				'condition' => [
					'type_select' => 'marquee',
				],
			]
		);
	}

	public function wpr_content_ticker_marquee() {

		// Get Settings
		$settings = $this->get_settings();

		$marquee_options = [
			'direction' => $settings['marquee_direction'],
            'duplicated' => true,
            'startVisible' => true,
            'gap' => 0,
			'duration' => absint( $settings['marquee_effect_duration'] * 1000 ),
			'pauseOnHover' => $settings['marquee_pause_on_hover'],
		];

		$this->add_render_attribute( 'ticker-marquee-attribute', [
			'class' => 'wpr-ticker-marquee wpr-marquee-hidden',
			'data-options' => wp_json_encode( $marquee_options ),
		] );

		if ( 'none' !== $settings['content_gradient_position'] ) {
			$this->add_render_attribute( 'ticker-marquee-attribute','class', 'wpr-ticker-gradient' );
		}

		?>

		<div <?php echo $this->get_render_attribute_string( 'ticker-marquee-attribute' ); ?>>	
			<?php
				if ( 'dynamic' === $settings['post_type'] ) {
					$this->wpr_content_ticker_dynamic();
				} else {
					$this->wpr_content_ticker_custom();
				}
			?>
		</div>

		<?php

	}

	public function add_section_ticker_items() {
		$this->start_controls_section(
			'section_ticker_items',
			[
				'label' => esc_html__( 'Ticker Items', 'wpr-addons' ),
				'condition' => [
					'post_type' => 'custom',
				],
			]
		);
		
		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_pricing_item' );

		$repeater->add_control(
			'ticker_title',
			[
				'label' => esc_html__( 'Title', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Custom Title 1',
			]
		);

		$repeater->add_control(
			'ticker_image',
			[
				'label' => esc_html__( 'Image', 'wpr-addons' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'ticker_link',
			[
				'label' => esc_html__( 'Link', 'wpr-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://www.your-link.com', 'wpr-addons' ),
				'separator' => 'before',
				
			]
		);

		$this->add_control(
			'ticker_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ ticker_title }}}',
				 'default'  => [
					[
						'ticker_title' => esc_html__( 'Custom Title 1', 'wpr-addons' ),
					],
					[
						'ticker_title' => esc_html__( 'Custom Title 2', 'wpr-addons' ),
					],
					[
						'ticker_title' => esc_html__( 'Custom Title 3', 'wpr-addons' ),
					],
					[
						'ticker_title' => esc_html__( 'Custom Title 4', 'wpr-addons' ),
					],
					[
						'ticker_title' => esc_html__( 'Custom Title 5', 'wpr-addons' ),
					],

                ]
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function wpr_content_ticker_custom() {

		$settings = $this->get_settings();
		$item_count = 0;
		
		?>

		<?php foreach ( $settings['ticker_items'] as $key=>$item ) : ?>
			
			<?php

				$image_src = Group_Control_Image_Size::get_attachment_image_src( $item['ticker_image']['id'], 'image_size', $settings );

				if ( !$image_src ) {
					$image_src = $item['ticker_image']['url'];
				}

				$this->add_render_attribute( 'link_attribute'. $key, 'href', esc_url( $item['ticker_link']['url'] ) );

				if ( $item['ticker_link']['is_external'] ) {
					$this->add_render_attribute( 'link_attribute'. $key, 'target', '_blank' );
				}

				if ( $item['ticker_link']['nofollow'] ) {
					$this->add_render_attribute( 'link_attribute'. $key, 'nofollow', '' );
				}
				
			?>

			<div class="wpr-ticker-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">

				<?php if ( 'box' === $settings['link_type'] ): ?>
				<a class="wpr-ticker-link" <?php echo $this->get_render_attribute_string( 'link_attribute'. $key ); ?>></a>	
				<?php endif; ?>

				<?php if ( 'yes' === $settings['image_switcher'] && $image_src ) : ?>
					<div class="wpr-ticker-image">
						
						<?php
						if ( 'image' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
							echo '<a '.$this->get_render_attribute_string( 'link_attribute'. $key ).'>';
						}

						echo '<img src="'. esc_url( $image_src ) .'" >';
					
						if ( 'image' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
							echo '</a>';
						}
						?>

					</div>
				<?php endif; ?>

				<?php if ( '' !== $item['ticker_title'] ) : ?>
					<h3 class="wpr-ticker-title">
						<div class="wpr-ticker-title-inner">
						<?php
						if ( 'title' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
							echo '<a '.$this->get_render_attribute_string( 'link_attribute'. $key ).'>';
						}

						echo esc_html( $item['ticker_title'] );
					
						if ( 'title' === $settings['link_type'] || 'image-title' === $settings['link_type']  ) {
							echo '</a>';
						}
						?>
						</div>
					</h3>
				<?php endif; ?>

			</div>

			<?php 
			$item_count++;
		endforeach;
		
	}

}