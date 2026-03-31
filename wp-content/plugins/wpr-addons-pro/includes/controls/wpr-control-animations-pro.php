<?php
namespace WprAddonsPro\Includes\Controls;

class WPR_Control_Animations_Pro {
	
	/**
	** WPR Animations
	*/
	public static function wpr_animations() {
		return [
			'Fade' => [
				'fade-in' => 'Fade In',
				'fade-out' => 'Fade Out',
			],
			'Slide' => [
				'slide-top' => 'Slide Top',
				'slide-right' => 'Slide Right',
				'slide-x-right' => 'Slide X Right',
				'slide-bottom' => 'Slide Bottom',
				'slide-left' => 'Slide Left',
				'slide-x-left' => 'Slide X Left',
			],
			'Skew' => [
				'skew-top' => 'Skew Top',
				'skew-right' => 'Skew Right',
				'skew-bottom' => 'Skew Bottom',
				'skew-left' => 'Skew Left',
			],
			'Scale' => [
				'scale-up' => 'Scale Up',
				'scale-down' => 'Scale Down',
			],
			'Roll' => [
				'roll-left' => 'Roll Left',
				'roll-right' => 'Roll Right',
			],
		];
	}
	
	/**
	** WPR Button Animations
	*/
	public static function wpr_button_animations() {
		return [
			'Animations' => [
				'wpr-button-none' => esc_html__( 'None', 'wpr-addons' ),
				'wpr-button-winona' => esc_html__( 'Winona + Text', 'wpr-addons' ),
				'wpr-button-rayen-left' => esc_html__( 'Ray Left + Text', 'wpr-addons' ),
				'wpr-button-rayen-right' => esc_html__( 'Ray Right + Text', 'wpr-addons' ),
				'wpr-button-wayra-left' => esc_html__( 'Wayra Left', 'wpr-addons' ),
				'wpr-button-wayra-right' => esc_html__( 'Wayra Right', 'wpr-addons' ),
				'wpr-button-isi-left' => esc_html__( 'Isi Left', 'wpr-addons' ),
				'wpr-button-isi-right' => esc_html__( 'Isi Right', 'wpr-addons' ),
				'wpr-button-aylen' => esc_html__( 'Aylen', 'wpr-addons' ),
				'wpr-button-antiman' => esc_html__( 'Antiman', 'wpr-addons' ),
			],
			'2D Animations' => [
				'elementor-animation-grow' => esc_html__( 'Grow', 'wpr-addons' ),
				'elementor-animation-shrink' => esc_html__( 'Shrink', 'wpr-addons' ),
				'elementor-animation-pulse' => esc_html__( 'Pulse', 'wpr-addons' ),
				'elementor-animation-pulse-grow' => esc_html__( 'Pulse Grow', 'wpr-addons' ),
				'elementor-animation-pulse-shrink' => esc_html__( 'Pulse Shrink', 'wpr-addons' ),
				'elementor-animation-push' => esc_html__( 'Push', 'wpr-addons' ),
				'elementor-animation-pop' => esc_html__( 'Pop', 'wpr-addons' ),
				'elementor-animation-bounce-in' => esc_html__( 'Bounce In', 'wpr-addons' ),
				'elementor-animation-bounce-out' => esc_html__( 'Bounce Out', 'wpr-addons' ),
				'elementor-animation-rotate' => esc_html__( 'Rotate', 'wpr-addons' ),
				'elementor-animation-grow-rotate' => esc_html__( 'Grow Rotate', 'wpr-addons' ),
				'elementor-animation-float' => esc_html__( 'Float', 'wpr-addons' ),
				'elementor-animation-sink' => esc_html__( 'Sink', 'wpr-addons' ),
				'elementor-animation-bob' => esc_html__( 'Bob', 'wpr-addons' ),
				'elementor-animation-hang' => esc_html__( 'Hang', 'wpr-addons' ),
				'elementor-animation-skew' => esc_html__( 'Skew', 'wpr-addons' ),
				'elementor-animation-skew-forward' => esc_html__( 'Skew Forward', 'wpr-addons' ),
				'elementor-animation-skew-backward' => esc_html__( 'Skew Backward', 'wpr-addons' ),
				'elementor-animation-wobble-horizontal' => esc_html__( 'Wobble Horizontal', 'wpr-addons' ),
				'elementor-animation-wobble-vertical' => esc_html__( 'Wobble Vertical', 'wpr-addons' ),
				'elementor-animation-wobble-to-bottom-right' => esc_html__( 'Wobble To Bottom Right', 'wpr-addons' ),
				'elementor-animation-wobble-to-top-right' => esc_html__( 'Wobble To Top Right', 'wpr-addons' ),
				'elementor-animation-wobble-top' => esc_html__( 'Wobble Top', 'wpr-addons' ),
				'elementor-animation-wobble-bottom' => esc_html__( 'Wobble Bottom', 'wpr-addons' ),
				'elementor-animation-wobble-skew' => esc_html__( 'Wobble Skew', 'wpr-addons' ),
				'elementor-animation-buzz' => esc_html__( 'Buzz', 'wpr-addons' ),
				'elementor-animation-buzz-out' => esc_html__( 'Buzz Out', 'wpr-addons' ),
				'elementor-animation-forward' => esc_html__( 'Forward', 'wpr-addons' ),
				'elementor-animation-backward' => esc_html__( 'Backward', 'wpr-addons' ),
			],
			'Background Animations' => [
				'wpr-button-back-pulse' => esc_html__( 'Back Pulse', 'wpr-addons' ),
				'wpr-button-sweep-to-right' => esc_html__( 'Sweep To Right', 'wpr-addons' ),
				'wpr-button-sweep-to-left' => esc_html__( 'Sweep To Left', 'wpr-addons' ),
				'wpr-button-sweep-to-bottom' => esc_html__( 'Sweep To Bottom', 'wpr-addons' ),
				'wpr-button-sweep-to-top' => esc_html__( 'Sweep To top', 'wpr-addons' ),
				'wpr-button-bounce-to-right' => esc_html__( 'Bounce To Right', 'wpr-addons' ),
				'wpr-button-bounce-to-left' => esc_html__( 'Bounce To Left', 'wpr-addons' ),
				'wpr-button-bounce-to-bottom' => esc_html__( 'Bounce To Bottom', 'wpr-addons' ),
				'wpr-button-bounce-to-top' => esc_html__( 'Bounce To Top', 'wpr-addons' ),
				'wpr-button-radial-out' => esc_html__( 'Radial Out', 'wpr-addons' ),
				'wpr-button-radial-in' => esc_html__( 'Radial In', 'wpr-addons' ),
				'wpr-button-rectangle-in' => esc_html__( 'Rectangle In', 'wpr-addons' ),
				'wpr-button-rectangle-out' => esc_html__( 'Rectangle Out', 'wpr-addons' ),
				'wpr-button-shutter-in-horizontal' => esc_html__( 'Shutter In Horizontal', 'wpr-addons' ),
				'wpr-button-shutter-out-horizontal' => esc_html__( 'Shutter Out Horizontal', 'wpr-addons' ),
				'wpr-button-shutter-in-vertical' => esc_html__( 'Shutter In Vertical', 'wpr-addons' ),
				'wpr-button-shutter-out-vertical' => esc_html__( 'Shutter Out Vertical', 'wpr-addons' ),
				'wpr-button-underline-from-left' => esc_html__( 'Underline From Left', 'wpr-addons' ),
				'wpr-button-underline-from-center' => esc_html__( 'Underline From Center', 'wpr-addons' ),
				'wpr-button-underline-from-right' => esc_html__( 'Underline From Right', 'wpr-addons' ),
				'wpr-button-underline-reveal' => esc_html__( 'Underline Reveal', 'wpr-addons' ),
				'wpr-button-overline-reveal' => esc_html__( 'Overline Reveal', 'wpr-addons' ),
				'wpr-button-overline-from-left' => esc_html__( 'Overline From Left', 'wpr-addons' ),
				'wpr-button-overline-from-center' => esc_html__( 'Overline From Center', 'wpr-addons' ),
				'wpr-button-overline-from-right' => esc_html__( 'Overline From Right', 'wpr-addons' ),
			]
		];
	}
	
	/**
	** WPR Animation Timings
	*/
	public static function wpr_animation_timings() {
		return [
			'ease-default' => 'Default',
			'linear' => 'Linear',
			'ease-in' => 'Ease In',
			'ease-out' => 'Ease Out',
			'ease-in-out' => 'Ease In Out',
			'ease-in-quad' => 'Ease In Quad',
			'ease-in-cubic' => 'Ease In Cubic',
			'ease-in-quart' => 'Ease In Quart',
			'ease-in-quint' => 'Ease In Quint',
			'ease-in-sine' => 'Ease In Sine',
			'ease-in-expo' => 'Ease In Expo',
			'ease-in-circ' => 'Ease In Circ',
			'ease-in-back' => 'Ease In Back',
			'ease-out-quad' => 'Ease Out Quad',
			'ease-out-cubic' => 'Ease Out Cubic',
			'ease-out-quart' => 'Ease Out Quart',
			'ease-out-quint' => 'Ease Out Quint',
			'ease-out-sine' => 'Ease Out Sine',
			'ease-out-expo' => 'Ease Out Expo',
			'ease-out-circ' => 'Ease Out Circ',
			'ease-out-back' => 'Ease Out Back',
			'ease-in-out-quad' => 'Ease In Out Quad',
			'ease-in-out-cubic' => 'Ease In Out Cubic',
			'ease-in-out-quart' => 'Ease In Out Quart',
			'ease-in-out-quint' => 'Ease In Out Quint',
			'ease-in-out-sine' => 'Ease In Out Sine',
			'ease-in-out-expo' => 'Ease In Out Expo',
			'ease-in-out-circ' => 'Ease In Out Circ',
			'ease-in-out-back' => 'Ease In Out Back',
		];
	}

}