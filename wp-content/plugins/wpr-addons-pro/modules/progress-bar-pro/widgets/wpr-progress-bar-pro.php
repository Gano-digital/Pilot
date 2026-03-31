<?php
namespace WprAddonsPro\Modules\ProgressBarPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Progress_Bar_Pro extends \WprAddons\Modules\ProgressBar\Widgets\Wpr_Progress_Bar {

	public function add_control_layout() {
		$this->add_control(
			'layout',
			[
				'label' => esc_html__( 'Layout', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'hr-line',
				'options' => [
					'hr-line' => esc_html__( 'Horizontal Line', 'wpr-addons' ),
					'vr-line' => esc_html__( 'Vertical Line', 'wpr-addons' ),
					'circle' => esc_html__( 'Circle', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-prbar-layout-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_line_width() {
		$this->add_responsive_control(
			'line_width',
			[
				'label' => esc_html__( 'Line Width', 'wpr-addons' ),
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
					'size' => 15,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'condition' => [
					'layout' => 'circle',
				],
			]
		);
	}

	public function add_control_prline_width() {
		$this->add_responsive_control(
			'prline_width',
			[
				'label' => esc_html__( 'Progress Width', 'wpr-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'tablet_default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'mobile_default' => [
					'unit' => 'px',
					'size' => 15,
				],
				'condition' => [
					'layout' => 'circle',
				],
			]
		);
	}

	public function add_control_stripe_switcher() {
		$this->add_control(
			'stripe_switcher',
			[
				'label' => esc_html__( 'Show Stripe', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'prefix_class' => 'wpr-prbar-stripe-',
				'render_type' => 'template',
				'condition' => [
					'layout!' => 'circle',
				],
			]
		);
	}

	public function add_control_stripe_anim() {
		$this->add_control(
			'stripe_anim',
			[
				'label' => esc_html__( 'Stripe Direction', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'none' => esc_html__( 'None', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
				],
				'condition' => [
					'layout!' => 'circle',
					'stripe_switcher' => 'yes',
				],
				'prefix_class' => 'wpr-prbar-stripe-anim-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_anim_loop() {
		$this->add_control(
			'anim_loop',
			[
				'label' => esc_html__( 'Loop', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'render_type' => 'template',
				'separator' => 'before',
			]
		);
	}

	public function add_control_anim_loop_delay() {
		$this->add_control(
			'anim_loop_delay',
			[
				'label' => esc_html__( 'Loop Delay', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5,
				'min' => 2,
				'max' => 50,
				'step' => 1,
				'render_type' => 'template',
				'condition' => [
					'anim_loop' => 'yes'
				]
			]
		);
	}

	public function render_progress_bar_vr_line() {
		// Get Settings
		$settings = $this->get_settings();

		if ( 'yes' === $settings['counter_switcher']  && 'outside' === $settings['counter_position']  ) {
			$this->render_progress_bar_counter();
		}

		?>

		<div class="wpr-prbar-vr-line">
			<?php
				if ( 'yes' === $settings['counter_switcher']  && 'inside' === $settings['counter_position']  ) {
					$this->render_progress_bar_counter();
				}
			?>			
			<div class="wpr-prbar-vr-line-inner wpr-anim-timing-<?php echo esc_attr( $settings['anim_timing'] ); ?>"></div>
		</div>

		<?php
		
		if ( '' !== $settings['title'] ){
			echo '<div class="wpr-prbar-title">'. esc_html( $settings['title'] ) .'</div>';
		}
		
		if ( '' !== $settings['subtitle'] ){
			echo '<div class="wpr-prbar-subtitle">'. esc_html( $settings['subtitle'] ) .'</div>';
		}
		
	}
	
}