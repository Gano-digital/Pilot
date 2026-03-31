<?php
/**
 * Security Class for the Gano Agent
 * Defends against prompt injection and autonomous attacks.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gano_Agent_Security {

	/**
	 * Defines restricted keywords indicating prompt injection attempts.
	 */
	private static $injection_signatures = array(
		'ignore previous instructions',
		'ignore all previous instructions',
		'forget everything',
		'system prompt',
		'<<<',
		'>>>',
		'system override',
		'you are now',
		'you are no longer',
		'bypass',
		'disregard'
	);

	/**
	 * Validates the size of an incoming payload.
	 *
	 * @param string $payload The payload to check.
	 * @param int    $max_length Maximum allowed length.
	 * @return bool True if valid, False if it exceeds max length.
	 */
	public static function validate_payload_size( $payload, $max_length = 2000 ) {
		if ( mb_strlen( $payload ) > $max_length ) {
			return false;
		}
		return true;
	}

	/**
	 * Checks if the input contains malicious intent or prompt injection signatures.
	 *
	 * @param string $input User input to analyze.
	 * @return bool True if malicious intent is detected, false otherwise.
	 */
	public static function detect_malicious_intent( $input ) {
		$normalized_input = strtolower( trim( $input ) );

		foreach ( self::$injection_signatures as $signature ) {
			if ( strpos( $normalized_input, $signature ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Sanitizes the input to be safely passed to an LLM, treating it purely as data.
	 * Strips out control characters and structural tags.
	 *
	 * @param string $input Raw input.
	 * @return string Sanitized and safely fenced input string.
	 */
	public static function sanitize_prompt_input( $input ) {
		// Strip HTML/PHP tags
		$clean = strip_tags( $input );
		
		// Remove typical injection wrappers
		$clean = str_replace( array( '<', '>', '{', '}', '[', ']' ), '', $clean );
		
		return trim( $clean );
	}
}
