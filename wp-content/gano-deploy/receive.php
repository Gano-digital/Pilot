<?php
/**
 * Gano Digital — Deploy Webhook Receiver (Hardened v2)
 *
 * Cambios de seguridad (2026-04-24):
 *   - Secret leído desde $_ENV primero, wp-config.php como fallback
 *   - Eliminado exec() — usa wp_cache_flush() nativo cuando WP está cargado
 *   - Rate limiting por IP (máx 10 requests/minuto)
 *   - Validación de checksum SHA-256 de archivos dentro del ZIP
 *   - Bloqueo de archivos ejecutables no permitidos
 *
 * URL: https://gano.digital/wp-content/gano-deploy/receive.php
 *
 * Protocolo:
 *   POST + Content-Type: application/zip
 *   Header X-Gano-Signature: sha256=<HMAC-SHA256(secret, body_bytes)>
 *
 * Setup:
 *   1. Configurar en servidor (nginx/Apache/php-fpm):
 *        fastcgi_param GANO_DEPLOY_HOOK_SECRET <secret-64-chars>;
 *      O en wp-config.php:
 *        define('GANO_DEPLOY_HOOK_SECRET', '<secret>');
 *   2. GitHub Secrets:
 *        GANO_DEPLOY_HOOK_URL    = https://gano.digital/wp-content/gano-deploy/receive.php
 *        GANO_DEPLOY_HOOK_SECRET = <mismo-secret>
 */
declare(strict_types=1);

// ── 0. Rate limiting por IP ──────────────────────────────────────────────────
$rateLimitFile = sys_get_temp_dir() . '/gano_deploy_ratelimit.json';
$clientIp = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$now = time();
$window = 60; // segundos
$maxRequests = 10;

$rates = [];
if (is_readable($rateLimitFile)) {
    $rates = json_decode(file_get_contents($rateLimitFile) ?: '[]', true) ?: [];
}

// Limpiar entradas antiguas
$rates = array_filter($rates, fn($r) => ($r['t'] ?? 0) > ($now - $window));

$clientRequests = array_filter($rates, fn($r) => ($r['ip'] ?? '') === $clientIp);
if (count($clientRequests) >= $maxRequests) {
    http_response_code(429);
    header('Retry-After: ' . $window);
    exit('Rate limit exceeded. Max ' . $maxRequests . ' requests per ' . $window . 's.');
}

$rates[] = ['ip' => $clientIp, 't' => $now];
file_put_contents($rateLimitFile, json_encode($rates, JSON_PRETTY_PRINT), LOCK_EX);

// ── 1. Cargar secret ─────────────────────────────────────────────────────────
$secret = '';

// Prioridad 1: Variable de entorno (recomendado)
if (!empty($_ENV['GANO_DEPLOY_HOOK_SECRET'])) {
    $secret = $_ENV['GANO_DEPLOY_HOOK_SECRET'];
}

// Prioridad 2: Constante de PHP (si wp-config.php ya fue cargado)
if ($secret === '' && defined('GANO_DEPLOY_HOOK_SECRET')) {
    $secret = constant('GANO_DEPLOY_HOOK_SECRET');
}

// Prioridad 3: Leer desde wp-config.php directamente (legacy fallback)
if ($secret === '') {
    $wp_config = dirname(__DIR__, 2) . '/wp-config.php';
    if (is_readable($wp_config)) {
        $cfg = file_get_contents($wp_config);
        if (preg_match("/define\s*\(\s*'GANO_DEPLOY_HOOK_SECRET'\s*,\s*'([^']{16,})'\s*\)/", $cfg, $m)) {
            $secret = $m[1];
        }
    }
}

if ($secret === '' || strlen($secret) < 16) {
    http_response_code(503);
    exit('Hook not configured. Set GANO_DEPLOY_HOOK_SECRET as env var or in wp-config.php.');
}

// ── 2. Solo POST ─────────────────────────────────────────────────────────────
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Method not allowed.');
}

// ── 3. Leer body ─────────────────────────────────────────────────────────────
$body = file_get_contents('php://input');
if ($body === false || strlen($body) < 22) {
    http_response_code(400);
    exit('Empty or invalid payload.');
}

// ── 4. Validar firma HMAC-SHA256 ─────────────────────────────────────────────
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

// ── 5. Escribir ZIP temporal ─────────────────────────────────────────────────
$tmp = sys_get_temp_dir() . '/gano_deploy_' . bin2hex(random_bytes(8)) . '.zip';
if (file_put_contents($tmp, $body) === false) {
    http_response_code(500);
    exit('Could not write temp file.');
}

// ── 6. Validar y extraer ZIP ─────────────────────────────────────────────────
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
$deploy_root = dirname(__DIR__, 2);
$allowed_prefixes = [
    'wp-content/themes/gano-child/',
    'wp-content/mu-plugins/gano-',
    'wp-content/plugins/gano-',
    '.htaccess',
];

// Extensiones bloqueadas (ejecutables que no son PHP permitidos)
$blocked_extensions = ['.exe', '.sh', '.bat', '.cmd', '.com', '.scr', '.pif'];

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

    // Bloquear extensiones peligrosas
    $ext = strtolower(pathinfo($clean, PATHINFO_EXTENSION));
    if (in_array('.' . $ext, $blocked_extensions, true)) {
        $skipped++;
        $errors[] = "blocked executable: $clean";
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

// ── 8. Flush WP cache (sin exec) ─────────────────────────────────────────────
$wp_load = $deploy_root . '/wp-load.php';
if (is_readable($wp_load) && !defined('ABSPATH')) {
    require_once $wp_load;
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
}

// ── 9. Respuesta ─────────────────────────────────────────────────────────────
$status_code = empty($errors) ? 200 : 207;
http_response_code($status_code);
header('Content-Type: application/json');
echo json_encode([
    'status'   => empty($errors) ? 'ok' : 'partial',
    'deployed' => $deployed,
    'skipped'  => $skipped,
    'errors'   => $errors,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
