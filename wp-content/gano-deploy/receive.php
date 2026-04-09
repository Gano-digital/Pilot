<?php
/**
 * Gano Digital — Deploy Webhook Receiver
 *
 * Permite a GitHub Actions desplegar archivos via HTTPS POST en lugar de SSH.
 * Solución al bloqueo de IPs de runners de GitHub por el firewall de GoDaddy.
 *
 * URL: https://gano.digital/wp-content/gano-deploy/receive.php
 *
 * Protocolo:
 *   POST + Content-Type: application/zip
 *   Header X-Gano-Signature: sha256=<HMAC-SHA256(secret, body_bytes)>
 *
 * Setup (una sola vez):
 *   1. Agregar en wp-config.php del servidor:
 *        define('GANO_DEPLOY_HOOK_SECRET', '<tu-secret-de-64-chars>');
 *   2. Agregar en GitHub Settings → Secrets:
 *        GANO_DEPLOY_HOOK_URL   = https://gano.digital/wp-content/gano-deploy/receive.php
 *        GANO_DEPLOY_HOOK_SECRET = <mismo-secret-de-wp-config>
 */
declare(strict_types=1);

// ── 1. Cargar secret desde wp-config ─────────────────────────────────────────
$wp_config = dirname(__DIR__, 2) . '/wp-config.php';
$secret = '';
if (is_readable($wp_config)) {
    $cfg = file_get_contents($wp_config);
    if (preg_match("/define\s*\(\s*'GANO_DEPLOY_HOOK_SECRET'\s*,\s*'([^']{16,})'\s*\)/", $cfg, $m)) {
        $secret = $m[1];
    }
}

if ($secret === '') {
    http_response_code(503);
    exit('Hook not configured. Add GANO_DEPLOY_HOOK_SECRET to wp-config.php on the server.');
}

// ── 2. Solo POST ──────────────────────────────────────────────────────────────
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

// ── 3. Leer body ──────────────────────────────────────────────────────────────
$body = file_get_contents('php://input');
if ($body === false || strlen($body) < 22) {
    http_response_code(400);
    exit('Empty or invalid payload.');
}

// ── 4. Validar firma HMAC-SHA256 ──────────────────────────────────────────────
$sig_header = $_SERVER['HTTP_X_GANO_SIGNATURE'] ?? '';
if (!str_starts_with($sig_header, 'sha256=')) {
    http_response_code(400);
    exit('Missing X-Gano-Signature header.');
}
$expected = 'sha256=' . hash_hmac('sha256', $body, $secret);
if (!hash_equals($expected, $sig_header)) {
    http_response_code(403);
    exit('Invalid signature.');
}

// ── 5. Escribir ZIP temporal ──────────────────────────────────────────────────
$tmp = sys_get_temp_dir() . '/gano_deploy_' . bin2hex(random_bytes(8)) . '.zip';
if (file_put_contents($tmp, $body) === false) {
    http_response_code(500);
    exit('Could not write temp file.');
}

// ── 6. Validar y extraer ZIP ──────────────────────────────────────────────────
if (!class_exists('ZipArchive')) {
    unlink($tmp);
    http_response_code(500);
    exit('ZipArchive extension not available.');
}

$zip = new ZipArchive();
$open_result = $zip->open($tmp);
if ($open_result !== true) {
    unlink($tmp);
    http_response_code(400);
    exit('Invalid ZIP file. ZipArchive error: ' . $open_result);
}

// ── 7. Allowlist de paths permitidos ─────────────────────────────────────────
// Solo rutas relativas a la raíz de WordPress.
// Raíz WP: calculada en runtime (no incluir rutas absolutas reales en el repo).
$deploy_root = dirname(__DIR__, 2);
$allowed_prefixes = [
    'wp-content/themes/gano-child/',
    'wp-content/mu-plugins/gano-',
    'wp-content/plugins/gano-',
    '.htaccess',
];

$deployed = 0;
$skipped  = 0;
$errors   = [];

for ($i = 0; $i < $zip->numFiles; $i++) {
    $name = $zip->getNameIndex($i);
    if ($name === false) {
        continue;
    }

    // Sanitizar: prevenir path traversal
    $clean = ltrim(str_replace(['../', '..\\', "\0", '\\'], ['', '', '', '/'], $name), '/');
    if (strlen($clean) === 0 || $clean !== ltrim($name, '/')) {
        $skipped++;
        continue;
    }

    // Verificar allowlist
    $allowed = false;
    foreach ($allowed_prefixes as $prefix) {
        if (str_starts_with($clean, $prefix) || $clean === rtrim($prefix, '/')) {
            $allowed = true;
            break;
        }
    }
    if (!$allowed) {
        $skipped++;
        continue;
    }

    $dest = $deploy_root . '/' . $clean;

    // Entrada de directorio
    if (str_ends_with($name, '/')) {
        if (!is_dir($dest) && !mkdir($dest, 0755, true)) {
            $errors[] = "mkdir failed: $clean";
        }
        continue;
    }

    // Entrada de archivo
    $dest_dir = dirname($dest);
    if (!is_dir($dest_dir) && !mkdir($dest_dir, 0755, true)) {
        $errors[] = "mkdir dir failed: $clean";
        continue;
    }

    $content = $zip->getFromIndex($i);
    if ($content === false) {
        $errors[] = "read failed from zip: $clean";
        continue;
    }

    if (file_put_contents($dest, $content) === false) {
        $errors[] = "write failed: $clean";
    } else {
        $deployed++;
    }
}

$zip->close();
unlink($tmp);

// ── 8. Flush WP cache (best-effort) ──────────────────────────────────────────
if (function_exists('exec')) {
    @exec('cd ' . escapeshellarg($deploy_root) . ' && wp cache flush --allow-root 2>/dev/null');
}

// ── 9. Respuesta ──────────────────────────────────────────────────────────────
$status_code = empty($errors) ? 200 : 207;
http_response_code($status_code);
header('Content-Type: application/json');
echo json_encode([
    'status'   => empty($errors) ? 'ok' : 'partial',
    'deployed' => $deployed,
    'skipped'  => $skipped,
    'errors'   => $errors,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
