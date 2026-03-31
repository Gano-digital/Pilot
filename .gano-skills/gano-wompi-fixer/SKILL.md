---
name: gano-wompi-fixer
description: >
  Skill especializado en la integración de pago Wompi Colombia para Gano Digital.
  Úsalo cuando necesites: corregir el gateway de Wompi en WooCommerce, implementar
  verificación de firma de webhooks, migrar de sandbox a producción, configurar
  estados de orden para PSE/tarjetas/Nequi, depurar transacciones fallidas, agregar
  logging de pagos, o cuando el usuario mencione "Wompi", "pago", "PSE", "checkout",
  "webhook de pago", "orden pendiente", "transacción", "gateway", "pasarela" en el
  contexto de gano.digital.
---

# Gano Wompi Fixer — Skill de Integración de Pago

Skill para corregir y optimizar la integración Wompi Colombia en el stack WooCommerce
de Gano Digital. Proyecto en: `/sessions/trusting-vibrant-newton/mnt/Gano.digital-copia/`

## Estado actual de la integración

**Archivos del plugin Wompi:**
```
wp-content/plugins/gano-wompi-integration/
├── gano-wompi-integration.php        ← Registro del plugin y settings page
├── class-gano-wompi-gateway.php      ← Gateway WooCommerce (TIENE VULNERABILIDADES)
└── gano-wompi-troubleshooter.php     ← Herramienta de debug (útil para tests)
```

**Problemas confirmados:**
1. Sin verificación HMAC de webhooks (crítico — ver skill gano-wp-security)
2. Clave sandbox hardcodeada como valor default en código
3. Sin manejo de idempotencia en eventos duplicados
4. Sin logging de transacciones para auditoría

**Plugin oficial alternativo disponible:**
- WordPress.org: `payment-integration-wompi` (v4.0.1, estable, WP 6.0+)
- GitHub: `saulmoralespa/payment-integration-wompi` (más mantenido activamente)
- Decisión: Evaluar si conviene migrar al plugin oficial o corregir el actual

---

## Documentación Wompi clave

**URLs de referencia:**
- Eventos/Webhooks: https://docs.wompi.co/en/docs/colombia/eventos/
- Ambientes y llaves: https://docs.wompi.co/en/docs/colombia/ambientes-y-llaves/
- Datos de prueba sandbox: https://docs.wompi.co/en/docs/colombia/datos-de-prueba-en-sandbox/
- Plugin WooCommerce oficial: https://docs.wompi.co/en/docs/colombia/woocommerce-wordpress-plugin/

**Prefijos de claves por ambiente:**
```
Sandbox:    pub_test_  | prv_test_  | test_events_  | test_integrity_
Producción: pub_prod_  | prv_prod_  | prod_events_  | prod_integrity_
```

---

## Implementación: Verificación de firma de webhook

La firma llega en el header `X-Event-Checksum`. El proceso es:
1. Tomar los valores del array `properties` del payload en el orden que vienen
2. Concatenar con el `timestamp` del evento
3. Concatenar con el `integrity_secret` (del dashboard Wompi > Developers)
4. Hacer SHA256 del string resultante
5. Comparar con `X-Event-Checksum` usando `hash_equals()`

```php
function gano_wompi_verify_signature( string $raw_body, string $received_checksum ): bool {
    $integrity_secret = get_option( 'gano_wompi_integrity_secret', '' );
    if ( empty($integrity_secret) || empty($received_checksum) ) {
        return false;
    }

    $payload = json_decode( $raw_body, true );
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        return false;
    }

    $concat = '';
    // Properties array varía por tipo de evento — siempre dinámico
    if ( isset($payload['properties']) && is_array($payload['properties']) ) {
        foreach ( $payload['properties'] as $key ) {
            $concat .= $payload['data']['transaction'][$key] ?? '';
        }
    }
    $concat .= $payload['timestamp'] ?? '';
    $concat .= $integrity_secret;

    return hash_equals( hash('sha256', $concat), $received_checksum );
}
```

---

## Mapeo de estados: Wompi → WooCommerce

```
Wompi PENDING   → WC 'pending'    (esperando pago — PSE puede tardar horas)
Wompi PROCESSING → WC 'processing' (procesando — tarjeta en validación)
Wompi APPROVED  → WC 'processing' → luego 'completed' si entrega automática
Wompi DECLINED  → WC 'failed'
Wompi VOIDED    → WC 'cancelled'
Wompi ERROR     → WC 'failed'
```

**Caso especial PSE**: Las transacciones PSE pueden quedar en PENDING por varias horas.
Implementar polling o confiar en el webhook cuando llegue. NO marcar como fallida
prematuramente.

---

## Checklist de migración sandbox → producción

### Antes de activar producción:
- [ ] Ejecutar al menos 3 transacciones de prueba en sandbox (CARD, PSE, NEQUI)
- [ ] Verificar que llegan webhooks al endpoint configurado en dashboard Wompi
- [ ] Confirmar que la firma HMAC se verifica correctamente
- [ ] Confirmar cambio de estado de orden en WooCommerce al recibir APPROVED
- [ ] Verificar emails de confirmación se envían al cliente
- [ ] Configurar URL de webhook de producción en dashboard Wompi
- [ ] Reemplazar todas las claves sandbox por las de producción en wp-options
- [ ] Confirmar que NINGUNA clave sandbox queda en el código fuente

### Configuración en dashboard Wompi (producción):
1. Ir a Wompi Dashboard > Developers
2. Copiar `Public Key`, `Private Key`, `Events Key`, `Integrity Secret`
3. Configurar URL del webhook: `https://gano.digital/wp-json/gano-wompi/v1/webhook`
4. Guardar en WordPress: WooCommerce > Ajustes > Pagos > Wompi

---

## Logging de transacciones para auditoría

```php
function gano_wompi_log_transaction( array $transaction_data, string $event_type ): void {
    $log_entry = array(
        'timestamp'      => current_time('mysql'),
        'event_type'     => $event_type,
        'transaction_id' => $transaction_data['id'] ?? 'unknown',
        'status'         => $transaction_data['status'] ?? 'unknown',
        'amount'         => $transaction_data['amount_in_cents'] ?? 0,
        'currency'       => $transaction_data['currency'] ?? 'COP',
        'order_id'       => $transaction_data['reference'] ?? 'unknown',
    );

    // Guardar en tabla personalizada o usar WC Logger
    $logger  = wc_get_logger();
    $context = array('source' => 'gano-wompi');
    $logger->info( json_encode($log_entry), $context );
}
```

Los logs de WooCommerce quedan en: `wp-content/uploads/wc-logs/gano-wompi-*.log`

---

## WHMCS + Wompi (Fase 4)

Cuando se integre WHMCS, el módulo oficial está disponible en:
- WHMCS Marketplace: `wompi-widget-web-checkout` (actualizado Oct 2025)
- Compatibilidad: WHMCS v8.13+
- Métodos: Tarjetas, PSE, Nequi, Daviplata
- Modos: Widget embebido o Web Checkout externo

El módulo incluye validación PCI DSS y firmas SHA256.
