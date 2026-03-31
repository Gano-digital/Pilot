<?php
namespace WprAddonsPro\Modules\PostsTimelinePro\Widgets;

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Posts_Timeline_Pro extends \WprAddons\Modules\PostsTimeline\Widgets\Wpr_Posts_Timeline {

	public function add_control_slides_to_show() {
		$this->add_control(
			'slides_to_show',
			[
				'label' => __( 'Slides To Show', 'wpr-addons' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => '3',
				'separator' => 'before',
				'render_type' => 'template',
				'condition'   => [
					'timeline_layout'   => [
					   'horizontal',
					   'horizontal-bottom'
					],
				]
			]
		);
	}

	public function add_control_swiper_loop() {
		$this->add_control(
			'swiper_loop',
			[
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'condition' => [
					'timeline_layout'   => [
						'horizontal-bottom',
						'horizontal'
					],
				],
				'render_type' => 'template',
			]
		);
	}

	public function add_control_group_autoplay() {
		$this->add_control(
			'swiper_autoplay',
			[
				'label' => esc_html__( 'Autoplay', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'condition' => [
					'timeline_layout'   => [
						'horizontal-bottom',
						'horizontal'
					],
				],
				'render_type' => 'template',
			]
		);
				
		$this->add_control(
			'swiper_delay',
			[
				'label' => esc_html__( 'Autoplay Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 5000,
				'frontend_available' => true,
				'default' => 500,
				'condition' => [
					'swiper_autoplay' => 'yes',
					'timeline_layout' => ['horizontal', 'horizontal-bottom']
				]
			]
		);

		$this->add_control(
			'swiper_pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default' => 'yes',
				'label_block' => false,
				'condition' => [
					'timeline_layout'   => [
						'horizontal-bottom',
						'horizontal'
					],
					'swiper_autoplay' => 'yes'
				],
				'render_type' => 'template',
			]
		);		
	}
	
	public function wpr_aos_animation_array(){
		return [
			"none" =>"None",
			  "fade" =>"Fade",
			  "fade-up" =>"Fade Up",
			  "fade-down" =>"Fade Down",
			  "fade-left" =>"Fade Left",
			  "fade-right" =>"Fade Right",
			  "fade-up-right" =>"Fade Up Right",
			  "fade-up-left" =>"Fade Up Left",
			  "fade-down-right" =>"Fade Down Right",
			  "fade-down-left" =>"Fade Down Left",
			  "flip-up" =>"Flip Up",
			  "flip-down" =>"Flip Down",
			  "flip-right" =>"Flip right",
			  "flip-left" =>"Flip Left",
			  "slide-up" =>"Slide Up",
			  "slide-left" =>"Slide Left",
			  "slide-right" =>"Slide Right",
			  "slide-down" =>"Slide Down",
			  "zoom-in" =>"Zoom In",
			  "zoom-out" =>"Zoom Out",
			  "zoom-in-up" =>"Zoom In Up",
			  "zoom-in-down" =>"Zoom In Down",
			  "zoom-in-left" =>"Zoom In Left",
			  "zoom-in-right" =>"Zoom In Right",
			  "zoom-out-up" =>"Zoom Out Up",
			  "zoom-out-down" =>"Zoom Out Down",
			  "zoom-out-left" =>"Zoom Out Left",
			  "zoom-out-right" =>"Zoom Out Right"
	   ];
	}

	public function add_control_show_pagination() {
		$this->add_control(
			'show_pagination',
			[
				'label' => esc_html__( 'Show Pagination', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'label_block' => false,
				'render_type' => 'template',
				'condition' => [
					'timeline_layout!' => ['horizontal', 'horizontal-bottom'],
					'timeline_content' => 'dynamic'
				],
				'separator' => 'before'
			]
		);
	}

	public function render_pagination($settings, $paged) {
		if ( 'yes' === $settings['show_pagination'] ) {
			echo '<div>';
			echo '<div class="wpr-grid-pagination wpr-pagination-load-more">';

			echo '<div class="wpr-pagination-loading">';
				switch ( $settings['pagination_animation'] ) {
					case 'loader-1':
						echo '<div class="wpr-double-bounce">';
							echo '<div class="wpr-child wpr-double-bounce1"></div>';
							echo '<div class="wpr-child wpr-double-bounce2"></div>';
						echo '</div>';
						break;
					case 'loader-2':
						echo '<div class="wpr-wave">';
							echo '<div class="wpr-rect wpr-rect1"></div>';
							echo '<div class="wpr-rect wpr-rect2"></div>';
							echo '<div class="wpr-rect wpr-rect3"></div>';
							echo '<div class="wpr-rect wpr-rect4"></div>';
							echo '<div class="wpr-rect wpr-rect5"></div>';
						echo '</div>';
						break;
					case 'loader-3':
						echo '<div class="wpr-spinner wpr-spinner-pulse"></div>';
						break;
					case 'loader-4':
						echo '<div class="wpr-chasing-dots">';
							echo '<div class="wpr-child wpr-dot1"></div>';
							echo '<div class="wpr-child wpr-dot2"></div>';
						echo '</div>';
						break;
					case 'loader-5':
						echo '<div class="wpr-three-bounce">';
							echo '<div class="wpr-child wpr-bounce1"></div>';
							echo '<div class="wpr-child wpr-bounce2"></div>';
							echo '<div class="wpr-child wpr-bounce3"></div>';
						echo '</div>';
						break;
					case 'loader-6':
						echo '<div class="wpr-fading-circle">';
							echo '<div class="wpr-circle wpr-circle1"></div>';
							echo '<div class="wpr-circle wpr-circle2"></div>';
							echo '<div class="wpr-circle wpr-circle3"></div>';
							echo '<div class="wpr-circle wpr-circle4"></div>';
							echo '<div class="wpr-circle wpr-circle5"></div>';
							echo '<div class="wpr-circle wpr-circle6"></div>';
							echo '<div class="wpr-circle wpr-circle7"></div>';
							echo '<div class="wpr-circle wpr-circle8"></div>';
							echo '<div class="wpr-circle wpr-circle9"></div>';
							echo '<div class="wpr-circle wpr-circle10"></div>';
							echo '<div class="wpr-circle wpr-circle11"></div>';
							echo '<div class="wpr-circle wpr-circle12"></div>';
						echo '</div>';
						break;
					
					default:
						break;
				}
			echo '</div>';

			echo '<p class="wpr-pagination-finish">'. $settings['pagination_finish_text'] .'</p>';
				echo '<a href="'. get_pagenum_link( $paged + 1, true ) .'" class="wpr-load-more-btn button">';
				echo $settings['pagination_load_more_text'];
				echo '</a>';
			echo '</div>';
			echo '</div>';
		}
	}

	public function add_control_posts_per_page() {
        $this->add_control(
			'posts_per_page',
			[
				'label' => esc_html__( 'Posts Per Page', 'wpr-addons'),
				'type' => Controls_Manager::NUMBER,
				'render_type' => 'template',
				'default' => 3,
                'min' => 0,
				'label_block' => false,
			]
		);
	}

	public function add_option_query_source() {
		$post_types = [];
		$post_types['post'] = esc_html__( 'Posts', 'wpr-addons' );
		$post_types['page'] = esc_html__( 'Pages', 'wpr-addons' );

		$custom_post_types = Utilities::get_custom_types_of( 'post', true );
		foreach( $custom_post_types as $slug => $title ) {
			if ( 'product' === $slug || 'e-landing-page' === $slug ) {
				continue;
			}

			if ( wpr_fs()->is_plan( 'expert' ) && defined('WPR_ADDONS_PRO_VERSION') ) {
				$post_types[$slug] = esc_html( $title );
			} else {
				$post_types['pro-'. substr($slug, 0, 2)] = esc_html( $title ) .' (Expert)';
			}
		}

		$post_types['current'] = esc_html__( 'Current Query', 'wpr-addons' );
		$post_types['related'] = esc_html__( 'Related Query', 'wpr-addons' );
		
		return $post_types;
	}

}