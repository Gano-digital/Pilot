<?php
/**
 * Plugin Name: Gano Agent Integration
 * Description: Custom functionalities for AI Agent interaction with WordPress (API, UI, and Logging).
 * Version: 1.1.0
 * Author: Gano Digital Dev
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define Constants
define( 'GANO_AGENT_VERSION', '1.1.0' );
define( 'GANO_AGENT_DIR', plugin_dir_path( __FILE__ ) . 'gano-agent/' );
define( 'GANO_AGENT_LOG_FILE', WP_CONTENT_DIR . '/agent-debug.log' );

// Include Core Classes
require_once GANO_AGENT_DIR . 'class-gano-agent-logger.php';
require_once GANO_AGENT_DIR . 'class-gano-agent-security.php';
require_once GANO_AGENT_DIR . 'class-gano-agent-api.php';
require_once GANO_AGENT_DIR . 'class-gano-agent-admin.php';
require_once GANO_AGENT_DIR . 'class-gano-leads-handler.php';
require_once GANO_AGENT_DIR . 'gano-member-portal.php';
require_once GANO_AGENT_DIR . 'class-gano-master-hub.php';

// Initialize the plugin
function gano_agent_init() {
	Gano_Agent_Logger::init();
	Gano_Leads_Handler::init();

	$api = new Gano_Agent_API();
	$api->init();

	if ( is_admin() ) {
		$admin = new Gano_Agent_Admin();
		$admin->init();
        $hub = new Gano_Master_Hub();
        $hub->init();
	}
}
add_action( 'plugins_loaded', 'gano_agent_init' );
