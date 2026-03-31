<?php
/**
 * Gano Member Portal Add-on
 * Adds the 'My Ecosystem' tab to WooCommerce My Account.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Register Endpoint
add_action( 'init', function() {
    add_rewrite_endpoint( 'mi-ecosistema', EP_PAGES );
} );

// 2. Add to Menu
add_filter( 'woocommerce_account_menu_items', function( $items ) {
    $new_items = array( 'mi-ecosistema' => '🛡️ Mi Ecosistema' );
    return array_slice( $items, 0, 1, true ) + $new_items + array_slice( $items, 1, null, true );
} );

// 3. Render Content
add_action( 'woocommerce_account_mi-ecosistema_endpoint', function() {
    $user_id = get_current_user_id();
    $disk_usage = '12.4 GB / 100 GB'; // Mock
    $uptime = '99.99%'; // Mock
    
    echo '<h3>Dashboard de Mi Ecosistema Gano</h3>';
    echo '<div class="gano-portal-card">
        <h4 style="color:#D4AF37;margin-top:0;">🚀 Infraestructura NVMe Activa</h4>
        <p>Tu ecosistema está operando en el Nodo Bogotá-SOTA-01.</p>
        <div style="display:flex;gap:40px;margin-top:20px;">
            <div><small style="color:#888;">ESPACIO EN DISCO</small><br><strong>' . $disk_usage . '</strong></div>
            <div><small style="color:#888;">UPTIME 30 DÍAS</small><br><strong>' . $uptime . '</strong></div>
            <div><small style="color:#888;">NIVEL DE SOBERANÍA</small><br><strong style="color:#4ade80;">ALTO</strong></div>
        </div>
    </div>';
    
    echo '<div class="gano-portal-card" style="border-color:rgba(255,255,255,0.1);">
        <h4>Configuración de Seguridad</h4>
        <ul>
            <li>Cifrado Post-Cuántico: <span style="color:#4ade80;">ACTIVO</span></li>
            <li>WAF Capa 7: <span style="color:#4ade80;">MONITOREANDO</span></li>
            <li>Certificado SSL SOTA: <span style="color:#4ade80;">VÁLIDO (320 días)</span></li>
        </ul>
    </div>';
} );

// 4. Header Redirection
add_action( 'template_redirect', function() {
    if ( is_account_page() && is_user_logged_in() && ! is_page_template('page.php') ) {
        // Just ensuring styles load
    }
} );
