<?php
/**
 * Admin UX Class for the Gano Agent
 * Final Version: Logging, Resilience, and Leads Dashboard.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Gano_Agent_Admin {

	public function init() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_post_clear_agent_logs', array( $this, 'handle_clear_logs' ) );
        add_action( 'admin_post_export_gano_leads', array( $this, 'handle_export_leads' ) );
	}

	public function add_admin_menu() {
		add_menu_page( 'Agent Monitor', 'Agent Monitor', 'manage_options', 'gano-agent-monitor', array( $this, 'render_admin_page' ), 'dashicons-visibility', 2 );
	}

	public function enqueue_styles( $hook ) {
		if ( 'toplevel_page_gano-agent-monitor' !== $hook ) return;
		$css = '
			.wrap.gano-agent-wrap { font-family: "Inter", sans-serif; background: #0f1115; color: #e2e8f0; padding: 25px; border-radius: 8px; margin-top: 20px; max-width: 1200px; }
			.gano-brand-header { display: flex; align-items: center; border-bottom: 2px solid #B30000; padding-bottom: 20px; margin-bottom: 25px; justify-content: space-between; }
			.gano-tab-nav { display: flex; gap: 10px; margin-bottom: 20px; }
			.gano-tab-btn { padding: 10px 20px; background: #1a1d24; border-radius: 4px; cursor: pointer; color: #94a3b8; font-weight: 600; }
			.gano-tab-btn.active { background: #D4AF37; color: #000; }
			.gano-log-viewer { background: #0a0c10; color: #00ff41; padding: 20px; border-radius: 6px; font-family: monospace; height: 350px; overflow-y: auto; border-left: 4px solid #B30000; }
			.gano-leads-table { width: 100%; color: #fff; border-collapse: collapse; margin-top: 20px; }
			.gano-leads-table th { text-align: left; border-bottom: 1px solid #333; padding: 10px; color:#D4AF37; }
			.gano-leads-table td { padding: 10px; border-bottom: 1px solid #222; font-size: 0.9rem; }
            .btn-download { background: #4ade80; color: #000; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-weight: 700; font-size: 0.8rem; }
		';
		wp_add_inline_style( 'wp-admin', $css );
	}

	public function render_admin_page() {
		$raw_logs = Gano_Agent_Logger::read_logs( 100 );
		$formatted_logs = htmlspecialchars( $raw_logs );
		$formatted_logs = str_replace( array('[ERROR]', '[INFO]', '[WARNING]'), array('<span style="color:#ff4b4b">[ERROR]</span>', '<span style="color:#61dafb">[INFO]</span>', '<span style="color:#f5a623">[WARNING]</span>'), $formatted_logs );
		$clear_url = wp_nonce_url( admin_url( 'admin-post.php?action=clear_agent_logs' ), 'clear_agent_logs_action', 'gano_nonce' );
        $export_url = wp_nonce_url( admin_url( 'admin-post.php?action=export_gano_leads' ), 'export_gano_leads_action', 'gano_nonce' );

		$db_leads = get_option( 'gano_leads_backup', array() );
		?>
		<div class="wrap gano-agent-wrap">
			<div class="gano-brand-header">
				<h1 style="color:#D4AF37;">🧠 Gano Intelligence Monitor</h1>
				<div>
					<a href="<?php echo esc_url( $export_url ); ?>" class="btn-download">Exportar Leads (CSV)</a>
					<a href="<?php echo esc_url( $clear_url ); ?>" class="gano-btn-danger" style="margin-left:10px;background:#B30000;color:#fff;padding:8px;text-decoration:none;border-radius:4px;">Limpiar Logs</a>
				</div>
			</div>

			<div class="gano-tab-nav">
				<div class="gano-tab-btn active" onclick="showTab('logs')">System Logs</div>
				<div class="gano-tab-btn" onclick="showTab('leads')">Captured Leads (<?php echo count($db_leads); ?>)</div>
			</div>
			
			<div id="tab-logs">
				<div class="gano-log-viewer"><?php echo $formatted_logs ?: 'Sin eventos.'; ?></div>
			</div>

			<div id="tab-leads" style="display:none;">
				<table class="gano-leads-table">
					<thead><tr><th>Fecha</th><th>Nombre</th><th>WhatsApp</th><th>Origen</th></tr></thead>
					<tbody>
						<?php foreach ( array_reverse($db_leads) as $lead ) : ?>
							<tr>
								<td><?php echo esc_html($lead['date']); ?></td>
								<td><?php echo esc_html($lead['name']); ?></td>
								<td><?php echo esc_html($lead['whatsapp']); ?></td>
								<td><?php echo esc_html($lead['context']); ?></td>
							</tr>
						<?php endforeach; if(empty($db_leads)) echo '<tr><td colspan="4">No hay leads capturados aún.</td></tr>'; ?>
					</tbody>
				</table>
			</div>
		</div>

		<script>
			function showTab(id) {
				document.getElementById('tab-logs').style.display = id === 'logs' ? 'block' : 'none';
				document.getElementById('tab-leads').style.display = id === 'leads' ? 'block' : 'none';
				document.querySelectorAll('.gano-tab-btn').forEach(b => b.classList.remove('active'));
				event.target.classList.add('active');
			}
		</script>
		<?php
	}

    public function handle_export_leads() {
        if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['gano_nonce'], 'export_gano_leads_action' ) ) wp_die('No autorizado');
        $file = WP_CONTENT_DIR . '/uploads/gano-leads.csv';
        if ( ! file_exists($file) ) wp_die('No hay datos para exportar.');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="gano-leads-export.csv"');
        readfile($file);
        exit;
    }

	public function handle_clear_logs() {
		if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['gano_nonce'], 'clear_agent_logs_action' ) ) wp_die('No autorizado');
		Gano_Agent_Logger::clear_logs();
		wp_redirect( admin_url( 'admin.php?page=gano-agent-monitor' ) );
		exit;
	}
}
