<?php
namespace WprAddons\Classes\Modules\Forms;

use Elementor\Utils;
use WprAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR Recaptcha Handler - reCAPTCHA v2 and v3 verification
 *
 * @since 3.4.6
 */

class WPR_Recaptcha_Handler {
	public function __construct() {
		add_action( 'wp_ajax_wpr_verify_recaptcha', [ $this, 'wpr_verify_recaptcha' ] );
		add_action( 'wp_ajax_nopriv_wpr_verify_recaptcha', [ $this, 'wpr_verify_recaptcha' ] );
	}

	public function wpr_verify_recaptcha() {
		$recaptcha_response = isset( $_POST['g-recaptcha-response'] ) ? sanitize_text_field( wp_unslash( $_POST['g-recaptcha-response'] ) ) : '';
		$recaptcha_version  = isset( $_POST['recaptcha_version'] ) ? sanitize_text_field( wp_unslash( $_POST['recaptcha_version'] ) ) : 'v3';

		if ( empty( $recaptcha_response ) ) {
			wp_send_json_error( [ 'message' => 'Recaptcha Error' ] );
			return;
		}

		if ( 'v2' === $recaptcha_version ) {
			$is_valid = $this->check_recaptcha_v2( $recaptcha_response );
			if ( $is_valid ) {
				wp_send_json_success( [ 'message' => 'Recaptcha Success' ] );
			} else {
				wp_send_json_error( [ 'message' => 'Recaptcha Error' ] );
			}
			return;
		}

		// v3
		$result = $this->check_recaptcha_v3( $recaptcha_response );
		$score  = isset( $result['score'] ) ? $result['score'] : 0;
		$score_threshold = (float) get_option( 'wpr_recaptcha_v3_score', 0.5 );

		if ( $result['success'] && $score >= $score_threshold ) {
			wp_send_json_success( [
				'message' => 'Recaptcha Success',
				'score'   => $score,
			] );
		} else {
			wp_send_json_error( [
				'message' => 'Recaptcha Error',
				'score'   => $score,
			] );
		}
	}

	/**
	 * Verify reCAPTCHA v2 (checkbox) response. No score returned.
	 *
	 * @param string $recaptcha_response Token from g-recaptcha-response.
	 * @return bool
	 */
	public function check_recaptcha_v2( $recaptcha_response ) {
		$secret_key = get_option( 'wpr_recaptcha_v2_secret_key' );
		if ( empty( $secret_key ) ) {
			return false;
		}

		$remote_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			[
				'body' => [
					'secret'   => $secret_key,
					'response' => $recaptcha_response,
					'remoteip' => $remote_ip,
				],
			]
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$decoded = json_decode( wp_remote_retrieve_body( $response ), true );
		return ! empty( $decoded['success'] );
	}

	/**
	 * Verify reCAPTCHA v3 response. Returns success and score.
	 *
	 * @param string $recaptcha_response Token from g-recaptcha-response.
	 * @return array{ success: bool, score: float|null }
	 */
	public function check_recaptcha_v3( $recaptcha_response ) {
		$secret_key = get_option( 'wpr_recaptcha_v3_secret_key' );
		if ( empty( $secret_key ) ) {
			return [ 'success' => false, 'score' => null ];
		}

		$remote_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			[
				'body' => [
					'secret'   => $secret_key,
					'response' => $recaptcha_response,
					'remoteip' => $remote_ip,
				],
			]
		);

		if ( is_wp_error( $response ) ) {
			return [ 'success' => false, 'score' => null ];
		}

		$decoded = json_decode( wp_remote_retrieve_body( $response ), true );
		$success = ! empty( $decoded['success'] );
		$score   = isset( $decoded['score'] ) ? (float) $decoded['score'] : null;

		return [ 'success' => $success, 'score' => $score ];
	}
}

new WPR_Recaptcha_Handler();
