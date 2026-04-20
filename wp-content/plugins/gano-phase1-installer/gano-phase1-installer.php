<?php
/**
 * Plugin Name: Gano Digital — Phase 1 Security Installer
 * Plugin URI:  https://gano.digital
 * Description: Instalador único. Al activar: (1) crea MU plugin de seguridad, (2) endurece .htaccess. Desactivar y eliminar después de instalar.
 * Version:     1.0.0
 * Author:      DevSecOps / Gano Digital
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ─── ACTIVACIÓN ──────────────────────────────────────────────────────────────
register_activation_hook( __FILE__, 'gano_installer_activate' );

function gano_installer_activate() {
    $results = [];

    // 1. INSTALAR MU PLUGIN ──────────────────────────────────────────────────
    $mu_dir    = WP_CONTENT_DIR . '/mu-plugins';
    $mu_target = $mu_dir . '/gano-security.php';
    $mu_source = plugin_dir_path( __FILE__ ) . 'gano-security.php.txt';

    if ( ! is_dir( $mu_dir ) ) {
        wp_mkdir_p( $mu_dir );
    }

    if ( file_exists( $mu_source ) ) {
        $copied = @copy( $mu_source, $mu_target );
        if ( $copied ) {
            $results[] = '✅ MU Plugin instalado: ' . $mu_target;
        } else {
            // Intentar con file_put_contents
            $content = file_get_contents( $mu_source );
            if ( $content && file_put_contents( $mu_target, $content ) ) {
                $results[] = '✅ MU Plugin instalado (método alternativo): ' . $mu_target;
            } else {
                $results[] = '❌ Error instalando MU Plugin. Verificar permisos en ' . $mu_dir;
            }
        }
    } else {
        $results[] = '❌ Archivo fuente no encontrado: ' . $mu_source;
    }

    // 2. ENDURECER .htaccess ──────────────────────────────────────────────────
    $htaccess_path   = ABSPATH . '.htaccess';
    $htaccess_source = plugin_dir_path( __FILE__ ) . 'htaccess-security.txt';

    if ( file_exists( $htaccess_source ) ) {
        $security_rules = file_get_contents( $htaccess_source );

        // Leer .htaccess actual
        $current_htaccess = file_exists( $htaccess_path ) ? file_get_contents( $htaccess_path ) : '';

        // Backup del .htaccess actual
        $backup_path = $htaccess_path . '.bak-' . date( 'Ymd-His' );
        file_put_contents( $backup_path, $current_htaccess );

        // Extraer solo las reglas de WordPress (BEGIN/END WordPress block)
        $wp_rules = '';
        if ( preg_match( '/# BEGIN WordPress.*?# END WordPress/s', $current_htaccess, $matches ) ) {
            $wp_rules = $matches[0];
        } else {
            // Si no hay bloque WP, crear uno básico
            $wp_rules = "# BEGIN WordPress\n<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteBase /\nRewriteRule ^index\\.php$ - [L]\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule . /index.php [L]\n</IfModule>\n# END WordPress";
        }

        // Extraer reglas de seguridad del archivo fuente (sin el bloque de rewrite de WP)
        // Remover el bloque de RewriteEngine/rewrite del security file para evitar duplicados
        $security_no_rewrite = preg_replace(
            '/# 3\. BLOQUEO.*?(?=# 4\.)/s',
            '',
            $security_rules
        );

        // Omitir TODOS los bloques <IfModule mod_headers.c> del .htaccess.
        // Los HTTP security headers (CSP, HSTS, X-Frame-Options, etc.) son
        // gestionados exclusivamente por el MU plugin gano-security.php, que
        // ya fue instalado en el paso anterior. Aplicar ambos causaría headers
        // duplicados o conflictivos (ej: X-Frame-Options DENY vs SAMEORIGIN).
        // Ver issue #232 — .htaccess vs headers CSP (gano-security.php).
        $security_no_headers = preg_replace(
            '/<IfModule mod_headers\.c>.*?<\/IfModule>/s',
            '',
            $security_no_rewrite
        );

        // Construir nuevo .htaccess: seguridad primero + WP block al final
        $new_htaccess  = "# ============================================================\n";
        $new_htaccess .= "# GANO DIGITAL — Security Hardening | " . date( 'Y-m-d' ) . "\n";
        $new_htaccess .= "# NOTA: HTTP Security Headers omitidos — gestionados por\n";
        $new_htaccess .= "#        el MU plugin gano-security.php (issue #232).\n";
        $new_htaccess .= "# ============================================================\n\n";
        $new_htaccess .= $security_no_headers . "\n\n";
        $new_htaccess .= $wp_rules . "\n";

        $written = file_put_contents( $htaccess_path, $new_htaccess );

        if ( $written !== false ) {
            $results[] = '✅ .htaccess endurecido (' . strlen( $new_htaccess ) . ' bytes). Backup en: ' . basename( $backup_path );
        } else {
            $results[] = '❌ Error escribiendo .htaccess. Verificar permisos en ' . ABSPATH;
        }
    } else {
        $results[] = '❌ Archivo fuente htaccess no encontrado.';
    }

    // 3. GUARDAR RESULTADOS para mostrar en admin ─────────────────────────────
    update_option( 'gano_installer_results', $results );
    update_option( 'gano_installer_ran', current_time( 'mysql' ) );
}

// ─── MOSTRAR RESULTADOS EN ADMIN ─────────────────────────────────────────────
add_action( 'admin_notices', 'gano_installer_notice' );
function gano_installer_notice() {
    $results = get_option( 'gano_installer_results', [] );
    if ( empty( $results ) ) return;

    $all_ok = ! in_array( false, array_map( fn($r) => str_starts_with( $r, '✅' ), $results ), true );
    $class  = $all_ok ? 'notice-success' : 'notice-error';

    echo '<div class="notice ' . $class . ' is-dismissible">';
    echo '<p><strong>🔒 Gano Digital — Phase 1 Security Installer</strong></p><ul>';
    foreach ( $results as $r ) {
        echo '<li>' . esc_html( $r ) . '</li>';
    }
    echo '</ul>';
    if ( $all_ok ) {
        echo '<p><strong>✅ Instalación completada. Puedes desactivar y eliminar este plugin ahora.</strong></p>';
    }
    echo '</div>';
}

// ─── DESACTIVACIÓN — limpiar opciones ────────────────────────────────────────
register_deactivation_hook( __FILE__, function() {
    delete_option( 'gano_installer_results' );
    delete_option( 'gano_installer_ran' );
} );
