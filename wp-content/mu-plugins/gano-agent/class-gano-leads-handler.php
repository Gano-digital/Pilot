<?php
/**
 * Leads Handler for Gano Agent
 * Intercepts lead capture logs and stores them in a secure CSV and DB fallback.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Gano_Leads_Handler {

	private static $leads_file;

	public static function init() {
		self::$leads_file = WP_CONTENT_DIR . '/uploads/gano-leads.csv';
		
		// Ensure file exists with headers
		if ( ! file_exists( self::$leads_file ) ) {
			$header = "Date,Name,WhatsApp,Source,Context\n";
			@file_put_contents( self::$leads_file, $header );
			self::protect_leads_file();
		}
	}

	public static function process_entry( $message ) {
		if ( strpos( $message, 'LEAD CAPTURED:' ) === 0 ) {
			preg_match( '/LEAD CAPTURED: (.*) \| WA: (.*)/', $message, $matches );
			if ( count( $matches ) === 3 ) {
				self::save_lead( trim( $matches[1] ), trim( $matches[2] ) );
			}
		}
	}

	private static function save_lead( $name, $whatsapp ) {
		$date = current_time( 'mysql' );
		$source = is_user_logged_in() ? 'Logged User' : 'Visitor';
		$line = sprintf( "\"%s\",\"%s\",\"%s\",\"%s\",\"AI Chat\"\n", $date, $name, $whatsapp, $source );
		
		// Primary: CSV (Resilient write)
		@file_put_contents( self::$leads_file, $line, FILE_APPEND );

		// Redundant: WordPress Options (DB Fallback)
		$db_leads = get_option( 'gano_leads_backup', array() );
		$db_leads[] = array(
			'date'     => $date,
			'name'     => $name,
			'whatsapp' => $whatsapp,
			'source'   => $source,
			'context'  => 'AI Chat'
		);
		// Keep last 100 leads to prevent DB bloat
		if ( count( $db_leads ) > 100 ) array_shift( $db_leads );
		update_option( 'gano_leads_backup', $db_leads );
		
		Gano_Agent_Logger::log( "Lead Secured (CSV & DB Redundancy): $name", 'INFO' );
	}

	private static function protect_leads_file() {
		$htaccess_dir = dirname( self::$leads_file );
		$htaccess_file = $htaccess_dir . '/.htaccess';
		if ( ! file_exists( $htaccess_file ) ) {
			$rules = "<Files \"gano-leads.csv\">\nOrder Allow,Deny\nDeny from all\n</Files>";
			@file_put_contents( $htaccess_file, $rules );
		}
	}
}
