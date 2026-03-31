<?php
/**
 * Logger Class for the Gano Agent
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gano_Agent_Logger {

	/**
	 * Initialize the logger.
	 */
	public static function init() {
		// Create log file if it doesn't exist.
		if ( ! file_exists( GANO_AGENT_LOG_FILE ) ) {
			file_put_contents( GANO_AGENT_LOG_FILE, "=== Gano Agent Log Initialized ===\n" );
		}
	}

	/**
	 * Log a message or array/object to the custom log file.
	 *
	 * @param mixed  $message The message or payload to log.
	 * @param string $level   The severity level (INFO, ERROR, WARNING).
	 */
	public static function log( $message, $level = 'INFO' ) {
		$time = current_time( 'mysql' );
		
		if ( is_array( $message ) || is_object( $message ) ) {
			$message_str = print_r( $message, true );
		} else {
			$message_str = (string) $message;
		}

		// Truncate massively oversized payloads (max 2000 chars) to prevent disk stuffing
		if ( mb_strlen( $message_str ) > 2000 ) {
			$message_str = mb_substr( $message_str, 0, 2000 ) . '... [TRUNCATED]';
		}

		// Strip structural tags and encode for safe viewing in UI 
		// Even though it's error_log, our UI parser outputs this in HTML
		$message_str = htmlspecialchars( wp_strip_all_tags( $message_str ) );

		// Remove newlines inside the message to keep one log per line
		$message_str = str_replace( array("\r", "\n"), " ", $message_str );

		$log_entry = sprintf( "[%s] [%s] %s\n", $time, $level, $message_str );
		
		error_log( $log_entry, 3, GANO_AGENT_LOG_FILE );
	}

	/**
	 * Read the last N lines from the log file.
	 * 
	 * @param int $lines Number of lines to return.
	 * @return string Log content.
	 */
	public static function read_logs( $lines = 100 ) {
		if ( ! file_exists( GANO_AGENT_LOG_FILE ) ) {
			return 'No logs found.';
		}

		$file = file( GANO_AGENT_LOG_FILE );
		$file = array_slice( $file, -$lines );
		return implode( "", $file );
	}
	
	/**
	 * Clear the log file.
	 */
	public static function clear_logs() {
		if ( file_exists( GANO_AGENT_LOG_FILE ) ) {
			file_put_contents( GANO_AGENT_LOG_FILE, "=== Gano Agent Log Cleared ===\n" );
		}
	}
}
