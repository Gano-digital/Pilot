<?php
namespace WprAddonsPro\Modules\MailchimpPro\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Wpr_Mailchimp_Pro extends \WprAddons\Modules\Mailchimp\Widgets\Wpr_Mailchimp {

	public function add_control_clear_fields_on_submit() {
		$this->add_control(
			'clear_fields_on_submit',
			[
				'label' => esc_html__( 'Clear Fields On Submit', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
			]
		);
	}

	public function add_control_extra_fields() {
		$this->add_control(
			'extra_fields',
			[
				'label' => esc_html__( 'Show Extra Fields', 'wpr-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'separator' => 'before',
			]
		);
	}
	
	public function add_control_name_label() {
		$this->add_control(
			'name_label',
			[
				'label' => esc_html__( 'Name Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Name',
				'condition' => [
					'extra_fields' => 'yes',
				]
			]
		);
	}
	
	public function add_control_name_placeholder() {
		$this->add_control(
			'name_placeholder',
			[
				'label' => esc_html__( 'Name Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Name',
				'condition' => [
					'extra_fields' => 'yes',
				]
			]
		);
	}
	
	public function add_control_last_name_label() {
		$this->add_control(
			'last_name_label',
			[
				'label' => esc_html__( 'Last Name Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Last Name',
				'condition' => [
					'extra_fields' => 'yes',
				]
			]
		);
	}
	
	public function add_control_last_name_placeholder() {
		$this->add_control(
			'last_name_placeholder',
			[
				'label' => esc_html__( 'L.Name Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Last Name',
				'condition' => [
					'extra_fields' => 'yes',
				]
			]
		);
	}
	
	public function add_control_phone_number_label_and_placeholder() {
		$this->add_control(
			'phone_number_label',
			[
				'label' => esc_html__( 'Phone Label', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'Phone Number',
				'condition' => [
					'extra_fields' => 'yes',
				]
			]
		);

		$this->add_control(
			'phone_number_placeholder',
			[
				'label' => esc_html__( 'Phone Placeholder', 'wpr-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'condition' => [
					'extra_fields' => 'yes',
				]
			]
		);
	}
	
	public function render_pro_element_extra_fields() {
		// Get Settings
		$settings = $this->get_settings();

		if ( 'yes' === $settings['extra_fields'] ) :
			if ( '' !== $settings['name_label'] || '' !== $settings['name_placeholder'] ) : ?>
			<div class="wpr-mailchimp-first-name">
				<?php echo '' !== $settings['name_label'] ? '<label>'. esc_html($settings['name_label']) .'</label>' : ''; ?>
				<input type="text" name="wpr_mailchimp_firstname" placeholder="<?php echo esc_attr($settings['name_placeholder']); ?>">
			</div>
			<?php 
			endif;

			if ( '' !== $settings['last_name_label'] || '' !== $settings['last_name_placeholder'] ) : ?>
			<div class="wpr-mailchimp-last-name">
				<?php echo '' !== $settings['last_name_label'] ? '<label>'. esc_html($settings['last_name_label']) .'</label>' : ''; ?>
				<input type="text" name="wpr_mailchimp_lastname" placeholder="<?php echo esc_attr($settings['last_name_placeholder']); ?>">
			</div>

			<?php 
			endif;

			if ( '' !== $settings['phone_number_label'] || '' !== $settings['phone_number_placeholder'] ) : ?>
			<div class="wpr-mailchimp-phone-number">
				<?php echo '' !== $settings['phone_number_label'] ? '<label>'. esc_html($settings['phone_number_label']) .'</label>' : ''; ?>
				<input type="tel" name="wpr_mailchimp_phone_number" placeholder="<?php echo esc_attr($settings['phone_number_placeholder']); ?>">
			</div>

			<?php 
			endif;
		endif;
	}

}