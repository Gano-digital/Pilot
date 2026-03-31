<?php
namespace WprAddonsPro\Modules\TeamMemberPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Team_Member_Pro extends \WprAddons\Modules\TeamMember\Widgets\Wpr_Team_Member {

	public function add_section_layout() {
		$this->start_controls_section(
			'wpr__section_layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'member_name_location',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Name Location', 'wpr-addons' ),
				'default' => 'below',
				'options' => [
					'over' => esc_html__( 'Over Image', 'wpr-addons' ),
					'below' => esc_html__( 'Below Image', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'member_job_location',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Job Location', 'wpr-addons' ),
				'default' => 'below',
				'options' => [
					'over' => esc_html__( 'Over Image', 'wpr-addons' ),
					'below' => esc_html__( 'Below Image', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'member_divider_location',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Divider Location', 'wpr-addons' ),
				'default' => 'below',
				'options' => [
					'over' => esc_html__( 'Over Image', 'wpr-addons' ),
					'below' => esc_html__( 'Below Image', 'wpr-addons' ),
				],
				'condition' => [
					'member_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'member_description_location',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Description Location', 'wpr-addons' ),
				'default' => 'below',
				'options' => [
					'over' => esc_html__( 'Over Image', 'wpr-addons' ),
					'below' => esc_html__( 'Below Image', 'wpr-addons' ),
				],
			]
		);

		$this->add_control(
			'member_social_media_location',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Socials Location', 'wpr-addons' ),
				'default' => 'below',
				'options' => [
					'over' => esc_html__( 'Over Image', 'wpr-addons' ),
					'below' => esc_html__( 'Below Image', 'wpr-addons' ),
				],
				'condition' => [
					'social_media' => 'yes',
				],
			]
		);

		$this->add_control(
			'member_btn_location',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Button Location', 'wpr-addons' ),
				'default' => 'below',
				'options' => [
					'over' => esc_html__( 'Over Image', 'wpr-addons' ),
					'below' => esc_html__( 'Below Image', 'wpr-addons' ),
				],
				'condition' => [
					'member_btn' => 'yes',
				],
			]
		);

		$this->add_control(
			'content_vertical_align',
			[
				'label' => esc_html__( 'Vertical Align', 'wpr-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'default' => 'middle',
				'options' => [
					'top' => [
						'title' => esc_html__( 'Top', 'wpr-addons' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'wpr-addons' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'wpr-addons' ),
						'icon' => 'eicon-v-align-bottom',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-cv-inner' => 'vertical-align: {{VALUE}}',
				]
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function add_section_image_overlay() {
		$this->start_controls_section(
			'wpr__section_image_overlay',
			[
				'label' => esc_html__( 'Overlay', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'overlay_animation',
			[
				'label' => esc_html__( 'Select Animation', 'wpr-addons' ),
				'type' => 'wpr-animations-alt',
				'default' => 'fade-in',
			]
		);
		
		$this->add_control(
			'overlay_anim_size',
			[
				'type' => Controls_Manager::SELECT,
				'label' => esc_html__( 'Animation Size', 'wpr-addons' ),
				'default' => 'large',
				'options' => [
					'small' => esc_html__( 'Small', 'wpr-addons' ),
					'medium' => esc_html__( 'Medium', 'wpr-addons' ),
					'large' => esc_html__( 'Large', 'wpr-addons' ),
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0.3,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-team-member-animation .wpr-cv-outer' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'overlay_animation!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function add_section_style_overlay() {
		$this->start_controls_section(
			'section_style_overlay',
			[
				'label' => esc_html__( 'Overlay', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		// Image Overlay
		$this->add_control(
			'image_overlay_section',
			[
				'label' => esc_html__( 'Image Overlay', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image_overlay_bg_color',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wpr-member-overlay'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'overlay_border',
				'label' => esc_html__( 'Border', 'wpr-addons' ),
				'default' => 'solid',
				'fields_options' => [
					'color' => [
						'default' => '#E8E8E8',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
				],
				'selector' => '{{WRAPPER}} .wpr-member-overlay',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_overlay_padding',
			[
				'label' => esc_html__( 'Padding', 'wpr-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'default' => [
					'top' => 10,
					'right' => 10,
					'bottom' => 10,
					'left' => 10,
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .wpr-member-overlay-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section(); // End Controls Section		
	}

	protected function team_member_overlay() {
		// Get Settings 
		$settings = $this->get_settings();
		
		if ( ( '' !== $settings['member_name'] && 'over' === $settings['member_name_location'] ) || 
			( '' !== $settings['member_job'] && 'over' === $settings['member_job_location'] ) || 
			( '' !== $settings['member_description'] && 'over' === $settings['member_description_location'] ) || 
			( 'yes' === $settings['social_media'] && 'over' === $settings['member_social_media_location'] ) || 
			( 'yes' === $settings['member_btn'] && 'over' === $settings['member_btn_location'] ) ) : 

		$this->add_render_attribute( 'overlay_container', 'class', 'wpr-member-overlay-wrap wpr-cv-container' );	
		$this->add_render_attribute( 'overlay_outer', 'class', 'wpr-cv-outer' );	

		if ( 'none' !== $settings['overlay_animation'] ) {
			$this->add_render_attribute( 'overlay_container', 'class', 'wpr-team-member-animation wpr-animation-wrap' );
			$this->add_render_attribute( 'overlay_outer', 'class', 'wpr-anim-transparency wpr-anim-size-'. $settings['overlay_anim_size'] .' wpr-overlay-'. $settings['overlay_animation'] );
		}

		?>

		<div <?php echo $this->get_render_attribute_string( 'overlay_container' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'overlay_outer' ); ?>>
				<div class="wpr-cv-inner">
					
					<div class="wpr-member-overlay"></div>
					<div class="wpr-member-overlay-content">
						<?php
							if ( '' !== $settings['member_name'] && 'over' === $settings['member_name_location'] ) {
								echo '<'. esc_attr( $settings['member_name_tag'] ) .' class="wpr-member-name">';
									echo esc_html( $settings['member_name'] );
								echo '</'. esc_attr( $settings['member_name_tag'] ) .'>';
							}
						?>

						<?php if ( 'yes' === $settings['member_divider'] && 'over' === $settings['member_divider_location'] && 'before_job' === $settings['member_divider_position'] ) : ?>
							<div class="wpr-member-divider"></div>
						<?php endif; ?>

						<?php if ( '' !== $settings['member_job'] && 'over' === $settings['member_job_location'] ) : ?>
							<div class="wpr-member-job"><?php echo esc_html( $settings['member_job'] ); ?></div>
						<?php endif; ?>

						<?php if ( 'yes' === $settings['member_divider'] && 'over' === $settings['member_divider_location'] && 'after_job' === $settings['member_divider_position'] ) : ?>
							<div class="wpr-member-divider"></div>
						<?php endif; ?>

						<?php if ( '' !== $settings['member_description'] && 'over' === $settings['member_description_location'] ) : ?>
							<div class="wpr-member-description"><?php echo esc_html( $settings['member_description'] ); ?></div>
						<?php endif; ?>
						
						<?php 
							if ( 'yes' === $settings['social_media'] && 'over' === $settings['member_social_media_location'] ) {
								$this->team_member_social_media();
							}
								
							if ( 'yes' === $settings['member_btn'] && 'over' === $settings['member_btn_location'] ) {
								$this->team_member_button();
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<?php endif;

	}

}