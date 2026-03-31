<?php
namespace WprAddonsPro\Modules\ContentTogglePro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Content_Toggle_Pro extends \WprAddons\Modules\ContentToggle\Widgets\Wpr_Content_Toggle {

	public function add_control_switcher_style() {
		$this->add_control(
			'switcher_style',
			[
				'label' => esc_html__( 'Switcher Style', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'dual',
				'options' => [
					'dual' => esc_html__( 'Dual', 'wpr-addons' ),
					'multi' => esc_html__( 'Multi', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-switcher-style-',
				'render_type' => 'template',
			]
		);
	}

	public function add_repeater_switcher_items() {
		$repeater = new Repeater();

		$repeater->add_control(
			'switcher_label',
			[
				'label' => esc_html__( 'Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Label 1',
			]
		);

		$repeater->add_control(
			'switcher_show_icon',
			[
				'label' => esc_html__( 'Show Icon', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);

		$repeater->add_control(
			'switcher_icon',
			[
				'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'default' => [
					'value' => 'fas fa-angle-right',
					'library' => 'fa-solid',
				],
				'condition' => [
					'switcher_show_icon' => 'yes',
				],
			]
		);

		$repeater->add_control(
            'switcher_content_type',
            [
                'label' => esc_html__( 'Content Type', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'editor',
                'options' => [
                    'template' => esc_html__( 'Elementor Template', 'wpr-addons' ),
                    'editor' => esc_html__( 'Editor', 'wpr-addons' ),
                ],
				'separator' => 'before',
            ]
        );

		$repeater->add_control(
			'switcher_content',
			[
				'label' => esc_html__( 'Content', 'wpr-addons' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Tab Content', 'wpr-addons' ),
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima incidunt voluptates nemo, dolor optio quia architecto quis delectus perspiciatis. Nobis atque id hic neque possimus voluptatum voluptatibus tenetur, perspiciatis consequuntur.',
				'condition' => [
					'switcher_content_type' => 'editor',
				],
			]
		);

		$repeater->add_control(
			'switcher_select_template',
			[
				'label'	=> esc_html__( 'Select Template', 'wpr-addons' ),
				'type' => 'wpr-ajax-select2',
				'options' => 'ajaxselect2/get_elementor_templates',
				'label_block' => true,
				'condition' => [
					'switcher_content_type' => 'template',
				],
			]
		);

		$this->add_control(
			'switcher_items',
			[
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ switcher_label }}}',
				'condition' => [
					'switcher_style' => 'multi',
				],
			]
		);
	}

	public function add_control_switcher_label_style() {
		$this->add_control(
			'switcher_label_style',
			[
				'label' => esc_html__( 'Label Position', 'wpr-addons' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'outer',
				'options' => [
					'inner' => esc_html__( 'Inside', 'wpr-addons' ),
					'outer' => esc_html__( 'Outside', 'wpr-addons' ),
				],
				'prefix_class' => 'wpr-switcher-label-style-',
				'render_type' => 'template',
				'condition' => [
					'switcher_style' => 'dual',
				],
			]
		);		
	}

	public function add_section_settings() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'wpr-addons' ),
			]
		);

		$this->add_control(
			'active_switcher',
			[
				'label' => esc_html__( 'Active Switcher Index', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'label_block' => false,
				'default' => 1,
				'min' => 1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'content_animation',
			[
				'label' => esc_html__( 'Content Animation', 'wpr-addons' ),
				'type' => 'wpr-animations-alt',
				'default' => 'none',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'content_anim_size',
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
					'content_animation!' => 'none',
				],
			]
		);

		$this->add_control(
			'content_anim_duration',
			[
				'label' => esc_html__( 'Animation Duration', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
				'min' => 0,
				'max' => 5,
				'step' => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wpr-switcher-content-inner' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
					'{{WRAPPER}} .wpr-tabs-content-wrap' => '-webkit-transition-duration: {{VALUE}}s;transition-duration: {{VALUE}}s;',
				],
				'condition' => [
					'content_animation!' => 'none',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section	
	}

	public function wpr_multi_switcher() {

		$settings = $this->get_settings();

		$switcher = $this->get_settings_for_display( 'switcher_items' );
		
		$active_switcher = $settings['active_switcher'];

		if ( ! defined('WPR_ADDONS_PRO_VERSION') ) {
			$active_switcher = 1;
		}

		if ( $active_switcher > sizeof( $switcher ) ) {
			$active_switcher = sizeof( $switcher );
		}

		$id_int = substr( $this->get_id_int(), 0, 3 );

		?>


		<div class="wpr-switcher-container" data-active-switcher="<?php echo esc_attr( $active_switcher ); ?>">
			
			<div class="wpr-switcher-outer">
				<div class="wpr-switcher-wrap">
					<?php foreach ( $switcher as $index => $item ) :

					$switcher_count = $index + 1;
					$switcher_setting_key = $this->get_repeater_setting_key( 'wpr_switcher', 'switcher_items', $index );
				
					$this->add_render_attribute( $switcher_setting_key, [
						'id' => 'wpr-switcher-' . $id_int . $switcher_count,
						'class' => [ 'wpr-switcher', 'elementor-repeater-item-'. $item['_id'] ],
						'data-switcher' => $switcher_count,
					] );

					?>

					<div <?php echo $this->get_render_attribute_string( $switcher_setting_key ); ?>>
						<div class="wpr-switcher-inner">
							<?php if ( '' !== $item['switcher_label'] ) : ?>
							<div class="wpr-switcher-label"><?php echo $item['switcher_label']; ?></div>
							<?php endif; ?>

							<?php if ( 'yes' === $item['switcher_show_icon'] && '' !== $item['switcher_icon']['value'] ) : ?>
							<div class="wpr-switcher-icon">
								<i class="<?php echo esc_attr( $item['switcher_icon']['value'] ); ?>"></i>
							</div>
							<?php endif; ?>
						</div>

					</div>

					<?php endforeach; ?>

					<div class="wpr-switcher-bg"></div>
				</div>
			</div>

		</div>

		<div class="wpr-switcher-content-wrap">
			<?php foreach ( $switcher as $index => $item ) :

			$switcher_count = $index + 1;

			$switcher_content_setting_key = $this->get_repeater_setting_key( 'wpr_switcher_content', 'switcher_items', $index );
			$this->add_render_attribute( $switcher_content_setting_key, [
				'id' => 'wpr-switcher-content-' . $id_int . $switcher_count,
				'class' => [ 'wpr-switcher-content', 'elementor-repeater-item-'. $item['_id'] ],
				'data-switcher' => $switcher_count,
			] );

			?>

			<div <?php echo $this->get_render_attribute_string( $switcher_content_setting_key ); ?>>
				<?php 
				echo '<div class="wpr-switcher-content-inner wpr-anim-size-'. $settings['content_anim_size'] .' wpr-overlay-'. $settings['content_animation'] .'">';

					if ( 'template' === $item['switcher_content_type'] ) {

						echo $this->wpr_switcher_template( $item['switcher_select_template'] );

					} else if( 'editor' === $item['switcher_content_type'] ) {

						echo $item['switcher_content'];
					}

				echo '</div>';

				?>
			</div>

			<?php endforeach; ?>
		</div>

		<?php
	}

}