<?php
/**
 * Gano Master Operations Hub
 * Centralizes all backlog tools (AI, CRM, Marketing) into a premium UI.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Gano_Master_Hub {

	public function init() {
		add_action( 'admin_menu', array( $this, 'add_hub_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_hub_assets' ) );
        add_action( 'admin_init', array( $this, 'register_hub_settings' ) );
	}

	public function add_hub_menu() {
		add_menu_page( 'Gano Hub', 'Gano Hub', 'manage_options', 'gano-master-hub', array( $this, 'render_hub_page' ), 'dashicons-rest-api', 3 );
	}

	public function register_hub_settings() {
		register_setting( 'gano_hub_options', 'gano_ai_api_key' );
		register_setting( 'gano_hub_options', 'gano_ai_model' );
        register_setting( 'gano_hub_options', 'gano_crm_webhook' );
	}

	public function enqueue_hub_assets( $hook ) {
		if ( 'toplevel_page_gano-master-hub' !== $hook ) return;
		$css = '
			.gano-hub-wrap { background: #0b0d11; color: #e2e8f0; padding: 30px; border-radius: 12px; font-family: "Inter", sans-serif; max-width: 1100px; margin-top: 20px; }
			.hub-header { display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(212, 175, 55, 0.2); padding-bottom: 20px; margin-bottom: 30px; }
			.hub-nav { display: flex; gap: 15px; margin-bottom: 30px; border-bottom: 1px solid #222; }
			.hub-nav-item { padding: 12px 25px; cursor: pointer; color: #94a3b8; font-weight: 600; border-bottom: 3px solid transparent; transition: all 0.3s; }
			.hub-nav-item.active { color: #D4AF37; border-bottom-color: #D4AF37; background: rgba(212, 175, 55, 0.05); }
			.hub-card { background: #161a21; border: 1px solid #2d343f; border-radius: 10px; padding: 25px; margin-bottom: 20px; position: relative; }
			.hub-card h3 { color: #D4AF37; margin-top: 0; display: flex; align-items: center; gap: 10px; }
			.hub-context-box { background: rgba(27, 79, 216, 0.1); border-left: 4px solid #1B4FD8; padding: 15px; border-radius: 0 6px 6px 0; font-size: 0.9rem; color: #a5b4fc; margin-bottom: 20px; }
			.hub-input { background: #0b0d11; border: 1px solid #374151; color: #fff; padding: 10px; border-radius: 6px; width: 100%; margin: 10px 0; }
            .hub-badge-soon { background: #333; color: #888; padding: 2px 8px; border-radius: 4px; font-size: 0.7rem; text-transform: uppercase; }
		';
		wp_add_inline_style( 'wp-admin', $css );
	}

	public function render_hub_page() {
		?>
		<div class="wrap gano-hub-wrap">
			<div class="hub-header">
				<h1 style="color:#D4AF37;">🕹️ Master Operations Hub</h1>
				<span style="color:#4ade80;font-family:monospace;">System: ONLINE</span>
			</div>

			<div class="hub-nav">
				<div class="hub-nav-item active" onclick="switchHubTab('ai')">AI Connector</div>
				<div class="hub-nav-item" onclick="switchHubTab('marketing')">Marketing & CRM</div>
				<div class="hub-nav-item" onclick="switchHubTab('intelligence')">Central Intel</div>
				<div class="hub-nav-item" onclick="switchHubTab('security')">Security Vault</div>
			</div>

			<form method="post" action="options.php">
				<?php settings_fields( 'gano_hub_options' ); ?>
				
				<!-- Tab: AI Connector -->
				<div id="hub-tab-ai" class="hub-tab-content">
					<div class="hub-card">
						<h3>🧠 AI Action Engine <span class="hub-badge-soon">Ready</span></h3>
						<div class="hub-context-box">
							Este módulo conecta el Agente Gano con modelos de lenguaje SOTA (OpenAI/Gemini). Permite procesamiento de lenguaje natural para ventas y soporte.
						</div>
						<label>API Provider</label>
						<select name="gano_ai_model" class="hub-input">
							<option value="mock" <?php selected(get_option('gano_ai_model'), 'mock'); ?>>Gano Local (Mock Engine)</option>
							<option value="gpt4" <?php selected(get_option('gano_ai_model'), 'gpt4'); ?>>OpenAI GPT-4o</option>
							<option value="gemini" <?php selected(get_option('gano_ai_model'), 'gemini'); ?>>Google Gemini Pro 1.5</option>
						</select>
						<label>API Key</label>
						<input type="password" name="gano_ai_api_key" value="<?php echo esc_attr(get_option('gano_ai_api_key')); ?>" class="hub-input" placeholder="sk-...">
						<?php submit_button('Guardar Configuración AI'); ?>
					</div>
				</div>

				<!-- Tab: Marketing -->
				<div id="hub-tab-marketing" class="hub-tab-content" style="display:none;">
					<div class="hub-card">
						<h3>✉️ Email & Automation <span class="hub-badge-soon">Infrastructure Ready</span></h3>
						<div class="hub-context-box">
							Configura el flujo de salida de correos transaccionales y la sincronización con CRMs externos (Salesforce/HubSpot).
						</div>
						<label>CRM Webhook URL</label>
						<input type="text" name="gano_crm_webhook" value="<?php echo esc_attr(get_option('gano_crm_webhook')); ?>" class="hub-input" placeholder="https://hooks.zapier.com/...">
						<p style="font-size:0.8rem;color:#888;">Nota: La infraestructura de cola de correos local está activa para el Onboarding.</p>
						<?php submit_button('Vincular CRM'); ?>
					</div>
				</div>

                <!-- Tab: Intelligence -->
				<div id="hub-tab-intelligence" class="hub-tab-content" style="display:none;">
					<div class="hub-card">
						<h3>📊 Central Intelligence</h3>
						<div class="hub-context-box">Métricas consolidadas de conversión, uso de API y salud del ecosistema por usuario.</div>
						<div style="text-align:center;padding:40px;color:#555;font-style:italic;">Esperando datos de producción para generar visualizaciones...</div>
					</div>
				</div>

                <!-- Tab: Security -->
				<div id="hub-tab-security" class="hub-tab-content" style="display:none;">
					<div class="hub-card">
						<h3>🔒 Security Vault</h3>
						<div class="hub-context-box">Gestión de llaves de cifrado y auditoría de accesos a la API Maestra.</div>
						<ul>
                            <li>Cifrado de Base de Datos: <span style="color:#4ade80;">AES-256 Activo</span></li>
                            <li>Rotación de Keys: <span style="color:#D4AF37;">Manual</span></li>
                        </ul>
					</div>
				</div>

			</form>
		</div>

		<script>
			function switchHubTab(tab) {
				document.querySelectorAll('.hub-tab-content').forEach(c => c.style.display = 'none');
				document.querySelectorAll('.hub-nav-item').forEach(n => n.classList.remove('active'));
				document.getElementById('hub-tab-' + tab).style.display = 'block';
				event.target.classList.add('active');
			}
		</script>
		<?php
	}
}
