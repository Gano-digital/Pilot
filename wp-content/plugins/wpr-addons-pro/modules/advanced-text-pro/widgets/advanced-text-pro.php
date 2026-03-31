<?php
namespace WprAddonsPro\Modules\AdvancedTextPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) exit;

class Advanced_Text_Pro extends \WprAddons\Modules\AdvancedText\Widgets\Advanced_Text {

	public function add_control_text_style() {
		$this->add_control(
			'text_style',
			[
				'label' => esc_html__( 'Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'animated',
				'options' => [
					'animated' => esc_html__( 'Animated', 'wpr-addons' ),
					'highlighted' => esc_html__( 'Highlighted', 'wpr-addons' ),
					'clipped' => esc_html__( 'Clipped', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-advanced-text-style-',
				'render_type' => 'template',
			]
		);
	}

	public function add_control_clipped_text() {
		$this->add_control(
			'clipped_text',
			[
				'label' => esc_html__( 'Clipped Text', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => 'Best Websites',
				'placeholder' => esc_html__( 'Enter your text', 'wpr-addons' ),
				'condition' => [
					'text_style' => 'clipped',
				],
			]
		);
	}

	public function add_section_style_clipped_text() {
		$this->start_controls_section(
			'section_style_clipped_text',
			[
				'label' => esc_html__( 'Clipped Text', 'wpr-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'text_style' => 'clipped',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'clipped_text_bg_color',
				'label' => esc_html__( 'Background', 'wpr-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'fields_options' => [
					'color' => [
						'default' => '#605BE5',
					],
				],
				'selector' => '{{WRAPPER}} .wpr-clipped-text-content'
			]
		);

		$this->add_control(
			'clipped_text_typography_divider',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'clipped_text_typography',
				'selector' => '{{WRAPPER}} .wpr-clipped-text',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'shadow_section',
			[
				'label' => esc_html__( 'Shadow', 'wpr-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'clipped_text_shadow_type',
			[
				'label' => esc_html__( 'Type', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'long',
				'options' => [
					'default' => esc_html__( 'Default', 'wpr-addons' ),
					'long' => esc_html__( 'Long', 'wpr-addons' ),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'clipped_text_shadow',
				'selector' => '{{WRAPPER}} .wpr-clipped-text',
				'condition' => [
					'clipped_text_shadow_type' => 'default',
				],
			]
		);

		$this->add_control(
			'clipped_text_long_shadow_color',
			[
				'label' => esc_html__( 'Color', 'wpr-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e8e8e8',
				'selectors' => [
					'{{WRAPPER}} .wpr-clipped-text' => 'color: {{VALUE}}',
				],
				'condition' => [
					'clipped_text_shadow_type' => 'long',
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'clipped_text_long_shadow_size',
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
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .wpr-clipped-text' => 'stroke-width: {{SIZE}}{{UNIT}}',
				],
				'render_type' => 'template',
				'condition' => [
					'clipped_text_shadow_type' => 'long',
				],
			]
		);

		$this->add_control(
			'clipped_text_long_shadow_direction',
			[
				'label' => esc_html__( 'Direction', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bottom-left',
				'options' => [
					'top-left' => esc_html__( 'Top Left', 'wpr-addons' ),
					'top-right' => esc_html__( 'Top Right', 'wpr-addons' ),
					'bottom-left' => esc_html__( 'Bottom Left', 'wpr-addons' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'wpr-addons' ),
					'top' => esc_html__( 'Top', 'wpr-addons' ),
					'bottom' => esc_html__( 'Bottom', 'wpr-addons' ),
					'left' => esc_html__( 'Left', 'wpr-addons' ),
					'right' => esc_html__( 'Right', 'wpr-addons' ),
				],
				'render_type' => 'template',
				'condition' => [
					'clipped_text_shadow_type' => 'long',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section
	}

	public function wpr_clipped_text() {
		// Get Settings
		$settings = $this->get_settings();

		$this->add_render_attribute( 'wpr-clipped-text', 'class', 'wpr-clipped-text' );

		if ( 'long' === $settings['clipped_text_shadow_type'] ) {
			$clipped_options = [
				'longShadowColor' => $settings['clipped_text_long_shadow_color'],
				'longShadowSize' => $settings['clipped_text_long_shadow_size']['size'],
				'longShadowSizeTablet' => $settings['clipped_text_long_shadow_size_tablet']['size'],
				'longShadowSizeMobile' => $settings['clipped_text_long_shadow_size_mobile']['size'],
				'longShadowDirection' => $settings['clipped_text_long_shadow_direction'],
			];

			$this->add_render_attribute( 'wpr-clipped-text', 'data-clipped-options', wp_json_encode( $clipped_options ) );
		}

		?>

		<?php if ( '' !== $settings['clipped_text'] ) : ?>
			
		<span <?php echo $this->get_render_attribute_string( 'wpr-clipped-text' ); ?>>
			<span class="wpr-clipped-text-content"><?php echo esc_html( $settings['clipped_text'] ); ?></span>
			<?php if ( 'long' === $settings['clipped_text_shadow_type'] ) : ?>
			<span class="wpr-clipped-text-long-shadow"><?php echo esc_html( $settings['clipped_text'] ); ?></span>
			<?php endif ?>
		</span>

		<?php endif; ?>

		<?php
	}

}