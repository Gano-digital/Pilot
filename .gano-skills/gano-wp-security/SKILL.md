---
name: gano-wp-security
description: >
  Skill de seguridad WordPress específico para Gano Digital. Aplica y verifica los parches
  de seguridad identificados en la auditoría integral de gano.digital. Úsalo cuando necesites:
  corregir vulnerabilidades del stack WordPress/WooCommerce/Wompi, revisar o generar código PHP
  seguro, aplicar hardening a wp-config.php, implementar nonce CSRF en endpoints REST,
  verificar firmas HMAC en webhooks de pago, auditar permisos de archivos, o cuando el usuario
  mencione "security fix", "parche", "vulnerabilidad", "WP_DEBUG", "Wompi webhook", "nonce",
  "hardening", "wp-config", "seguridad WordPress" en el contexto de Gano Digital.
---

# Gano WP Security — Skill de Seguridad WordPress

Este skill guía la implementación de los parches de seguridad críticos identificados en la
Auditoría Integral de Gano Digital (Marzo 2026). Trabaja sobre el **workspace del repo**
(`Gano.digital-copia` / clon de `Gano-digital/Pilot`), no rutas absolutas de otra máquina.

## Contexto del proyecto

- **Stack**: WordPress + Elementor + Royal Elementor Addons + WooCommerce + Wompi Colombia
- **MU Plugin de seguridad**: `wp-content/mu-plugins/gano-security.php` — YA implementado, no modificar sin auditar
- **Pasarela de pago**: `wp-content/plugins/gano-wompi-integration/` — tiene vulnerabilidades activas
- **Tema hijo**: `wp-content/themes/gano-child/` — chat IA sin nonce CSRF

---

## Vulnerabilidades por prioridad

### V-01 CRÍTICA — WP_DEBUG en producción

**Archivo**: `wp-config.php`

Verificar y corregir:
```php
// MAL (estado actual):
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

// BIEN (producción):
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
```

Adicionalmente agregar en wp-config.php:
```php
define('DISALLOW_FILE_EDIT', true);    // Deshabilitar editor de temas/plugins
define('DISALLOW_FILE_MODS', true);    // Deshabilitar instalación desde admin
```

Verificar que debug.log no sea accesible públicamente — agregar a .htaccess:
```apache
<Files "debug.log">
    Order allow,deny
    Deny from all
</Files>
```

---

### V-02 CRÍTICA — Clave Wompi hardcodeada

**Archivo**: `wp-content/plugins/gano-wompi-integration/gano-wompi-integration.php`

El patrón a buscar y eliminar: cualquier valor default de clave sandbox en el código fuente.
Las claves deben leerse ÚNICAMENTE desde `get_option()` — nunca tener valores hardcodeados.

Patrón correcto para almacenamiento seguro de claves Wompi:
```php
// Leer desde wp_options (ya configurado en el plugin de settings)
$public_key  = get_option('gano_wompi_public_key', '');
$private_key = get_option('gano_wompi_private_key', '');
$integrity   = get_option('gano_wompi_integrity_secret', '');

// Nunca hacer esto:
// $public_key = 'pub_test_Q5yS9v9...' (valor hardcodeado)
```

---

### V-03 CRÍTICA — Sin verificación de firma HMAC en webhooks Wompi

**Archivo**: `wp-content/plugins/gano-wompi-integration/class-gano-wompi-gateway.php`

Wompi firma cada webhook con SHA256. El campo `X-Event-Checksum` contiene la firma.
La verificación debe ser lo PRIMERO que se ejecuta al recibir un webhook.

Implementación correcta:
```php
/**
 * Verificar integridad del webhook Wompi
 * Documentación: https://docs.wompi.co/en/docs/colombia/eventos/
 */
function gano_wompi_verify_webhook_signature( $payload, $checksum_header ) {
    $integrity_secret = get_option('gano_wompi_integrity_secret', '');

    if ( empty($integrity_secret) || empty($checksum_header) ) {
        return false;
    }

    // La firma es SHA256 de: properties_concatenados + timestamp + integrity_secret
    // NOTA: El array 'properties' varía por evento — extraer dinámicamente
    $data = json_decode( $payload, true );

    if ( ! isset($data['data']['transaction']) || ! isset($data['timestamp']) ) {
        return false;
    }

    // Concatenar valores del array properties en el orden que vienen
    $properties_str = '';
    if ( isset($data['properties']) && is_array($data['properties']) ) {
        foreach ( $data['properties'] as $prop ) {
            $properties_str .= $data['data']['transaction'][$prop] ?? '';
        }
    }

    $string_to_hash = $properties_str . $data['timestamp'] . $integrity_secret;
    $calculated     = hash( 'sha256', $string_to_hash );

    // hash_equals es timing-safe (previene timing attacks)
    return hash_equals( $calculated, $checksum_header );
}

// En el handler del webhook, verificar ANTES de cualquier acción:
function gano_wompi_handle_webhook() {
    $payload          = file_get_contents('php://input');
    $checksum_header  = $_SERVER['HTTP_X_EVENT_CHECKSUM'] ?? '';

    if ( ! gano_wompi_verify_webhook_signature($payload, $checksum_header) ) {
        wp_send_json_error('Firma inválida', 403);
        exit;
    }

    // Solo aquí procesar el webhook...
}
```

---

### V-04 ALTA — wp-file-manager (RCE potencial)

**⚠️ NOTA IMPORTANTE sobre plugins de fase:**
Los plugins `gano-phase1-installer`, `gano-phase2-business`, `gano-phase3-content`,
`gano-phase6-catalog`, `gano-phase7-activator` **NO deben eliminarse** hasta confirmar
que han sido ejecutados/activados correctamente en WordPress y han completado su
instalación. Consultar con Diego antes de eliminarlos.

**wp-file-manager SÍ debe eliminarse inmediatamente** — no es un plugin de fase, es un
plugin de gestión de archivos con historial de CVEs críticos (CVE-2020-25213).

```bash
# Verificar si está activo
ls wp-content/plugins/wp-file-manager/

# Eliminar (hacer backup primero):
# 1. Desactivar desde wp-admin > Plugins
# 2. Eliminar carpeta wp-content/plugins/wp-file-manager/
# 3. Alternativa segura: acceso SFTP directo al servidor
```

---

### V-05 ALTA — Chat IA sin nonce CSRF

**Archivo**: `wp-content/themes/gano-child/js/gano-chat.js` y `functions.php`

En `functions.php`, al encolar el script del chat, agregar localización con nonce:
```php
// En gano_child_enqueue_styles() o función separada:
wp_localize_script( 'gano-chat-js', 'ganoChatConfig', array(
    'nonce'   => wp_create_nonce('gano_chat_nonce'),
    'restUrl' => rest_url('gano-agent/v1/log'),
    'siteUrl' => get_site_url(),
));
```

En `gano-chat.js`, usar el nonce en todas las peticiones fetch:
```javascript
// Reemplazar fetch directo por:
fetch(ganoChatConfig.restUrl, {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': ganoChatConfig.nonce
    },
    body: JSON.stringify({ name: userName, whatsapp: whatsappNum, message: msg })
});
```

En el endpoint REST (PHP), verificar el nonce:
```php
register_rest_route('gano-agent/v1', '/log', array(
    'methods'             => 'POST',
    'callback'            => 'gano_agent_log_callback',
    'permission_callback' => function( $request ) {
        return wp_verify_nonce( $request->get_header('X-WP-Nonce'), 'gano_chat_nonce' );
    },
));
```

---

## Checklist de verificación post-parche

Antes de considerar completada la fase de seguridad, verificar:

- [ ] `define('WP_DEBUG', false)` confirmado en wp-config.php
- [ ] `define('DISALLOW_FILE_EDIT', true)` agregado
- [ ] debug.log bloqueado por .htaccess
- [ ] Claves Wompi sin valores hardcodeados en código fuente
- [ ] Verificación HMAC implementada y testeada con evento Wompi sandbox
- [ ] wp-file-manager eliminado del servidor
- [ ] Nonce CSRF en endpoint REST del chat
- [ ] 2FA activo en todas las cuentas admin (usar Wordfence 2FA — ya instalado)
- [ ] Permisos de archivos auditados: wp-config.php → 400, directorios → 755, archivos → 644
- [ ] Transacción de pago completa testeada en sandbox Wompi

## Herramientas de verificación

```bash
# Verificar permisos de wp-config.php
ls -la wp-config.php

# Buscar claves hardcodeadas en todo el proyecto
grep -r "pub_test_\|prv_test_\|pub_prod_\|prv_prod_" wp-content/plugins/

# Verificar WP_DEBUG
grep -n "WP_DEBUG" wp-config.php

# Buscar debug.log accesible
ls -la wp-content/debug.log 2>/dev/null
```

## CI y secretos en el repo (Abril 2026)

- **TruffleHog** en GitHub Actions escanea **solo rutas Gano** (MU-plugins, tema hijo, plugins `gano-*`); no sustituye auditoría manual de vendor de terceros.
- **Scripts SSH locales:** usar variables de entorno (`GANO_SSH_*` u otras acordadas); **no** commitear contraseñas, claves privadas ni tokens en el repo.
- **`.gitignore`:** mantener fuera `.env`, backups de BD y scripts personales destructivos.
- Si el flujo comercial prioriza **GoDaddy Reseller** para pagos, los ítems de Wompi en checklist siguen siendo relevantes solo donde el plugin o webhooks locales sigan activos.
