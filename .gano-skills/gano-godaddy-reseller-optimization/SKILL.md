---
name: gano-godaddy-reseller-optimization
description: >
  Skill de optimización avanzada GoDaddy Reseller Control Center (RCC). Implementar
  bundle selector, SKU mapping, pricing overrides, marca blanca completa y validación
  de checkout. Usar cuando necesites: configurar RCC, mapear bundles 3-year, crear
  product feeds, validar Good as Gold, depurar catálogo, o realizar smoke testing
  de flujo comercial completo.
---

# Gano GoDaddy Reseller Optimization — RCC Avanzado y SKU Mapping

Skill para optimización completa del Reseller Control Center (RCC) de GoDaddy,
incluyendo bundle mapping, pricing overrides y validación de flujo de checkout.

---

## Contexto SOTA 2026

### Producción gano.digital (2026-04-19)

- Checkout activo vía **Reseller Store** + **`cart.secureserver.net`** con `plid` de Private Label.
- **`gano_pfid_*`** en `wp_options` pobladas desde catálogo importado (slugs `rstore_id`); falta validación explícita contra **PFID** en RCC para todos los SKUs y cerrar almacenamiento online (`PENDING_RCC`).
- **WooCommerce no está instalado** en producción; no documentar flujos de carrito local como primarios.

### GoDaddy Reseller Integration (Validado)
- **Plugin oficial:** `wp-reseller-store` (WordPress.org)
- **API Reseller:** Soporta integraciones directas vía webhooks
- **Modelo actual:** Webhook HTTPS (ya implementado en deploy.yml)
- **Validación SOTA:** Webhook HTTPS es correcto para 2026 (más seguro que SSH)

### Pivot Comercial: Marca Blanca
```
Reseller Store no redirige a godaddy.com
Redirige a: [tu dominio]/checkout/
Facturación: bajo tu nombre (gano.digital)
Marca: 100% tuya
```

### Good as Gold Account (Requerimiento P0)
**Estado:** DEBE estar activo antes de procesar pagos transaccionales.

**Verificación:**
```
1. Entrar a GoDaddy → My Products → GoDaddy Reseller
2. Settings → Account Status → "Good as Gold" (YES/NO)
3. Si NO: activar desde Developer Portal
   https://developer.godaddy.com/account/
```

---

## Arquitectura RCC — Conceptos Clave

### 1. SKU (Stock Keeping Unit)
```
GoDaddy asigna SKU único a cada producto:
- Ej: WP-ECONOMY-1Y (WordPress hosting, 1 año)
- Ej: WP-DELUXE-3Y (WordPress hosting deluxe, 3 años)
- Ej: SSL-STD-PLAN (SSL estándar)

Tu tarea: Mapear SKU GoDaddy → planes Gano Digital
```

### 2. Bundle
```
Agrupación de productos con descuento:
- GANO-STARTER-3YR → [WP-ECONOMY-3Y + SSL-STD]
- GANO-PRO-3YR → [WP-DELUXE-3Y + BACKUPS-PREMIUM]
- GANO-ENTERPRISE-3YR → [WP-DELUXE-3Y + SSL-EV + 24/7-SUPPORT]

RCC permite crear bundles custom
```

### 3. Pricing Override
```
GoDaddy lista precio en USD → mostrar en COP
Gano sobreescribe precio CON MARGEN:
- GoDaddy: WP-DELUXE-3Y = $100 USD
- Tipo cambio: ~4000 COP/USD
- Precio Gano: $100 * 4000 = $400,000 COP

Overhead Gano (10%): $400,000 * 1.10 = $440,000 COP (publicado)
```

---

## Paso 1: Auditoría RCC Inicial

### Login RCC
```
URL: https://reseller.godaddy.com/
Usuario: [Reseller account]
Contraseña: [Tu credencial]

Secciones principales:
├─ Dashboard (estado general)
├─ Products (catálogo disponible)
├─ Bundles (agrupaciones custom)
├─ Orders (historial transacciones)
├─ Customers (clientes)
├─ Billing
├─ Integrations (webhooks)
└─ Settings
```

### Inventario de Productos GoDaddy
```
Navegar a: RCC → Products → Available Products

Anotar todos los SKU disponibles:
Categoría | SKU | Nombre | Precio USD | Ciclos disponibles
-----------|-----|--------|------------|-------------------
WordPress | WP-ECON-1Y | Economy | $59.88 | 1Y, 3Y, 5Y
WordPress | WP-DELUXE-1Y | Deluxe | $119.88 | 1Y, 3Y, 5Y
SSL | SSL-STD | Standard | $79.88 | Anual
... (continuar)

Criterio: Solo productos de hosting WordPress
(Ignorar dominios, emails, otros servicios si no aplican)
```

---

## Paso 2: Mapeo SKU → Planes Gano Digital

### Define los Planes Gano
```
Gano Digital planea ofrecer (hipotético):

GANO STARTER
└─ WordPress hosting entrada
└─ SKU base: WP-ECON-3Y (3 años)
└─ Incluir: SSL STD + BACKUPS (1X/mes)
└─ Precio Gano: $440,000 COP/3Y (~$36/mes)

GANO PRO
└─ WordPress hosting estándar
└─ SKU base: WP-DELUXE-3Y (3 años)
└─ Incluir: SSL PRO + BACKUPS (diarios) + SOPORTE PRIORITARIO
└─ Precio Gano: $750,000 COP/3Y (~$63/mes)

GANO ENTERPRISE
└─ WordPress hosting deluxe
└─ SKU base: WP-DELUXE-3Y (3 años)
└─ Incluir: SSL EV + BACKUPS (realtime) + 24/7 SUPPORT + AGENTE IA
└─ Precio Gano: $1,200,000 COP/3Y (~$100/mes)
```

### Crear Mapeo en Spreadsheet
```
Create: gano_sku_mapping.csv

Gano Plan | GoDaddy SKU | Qty | Precio GoDaddy USD | Precio Gano COP | Margen
-----------|-------------|-----|-------------------|-----------------|-------
STARTER | WP-ECON-3Y | 1 | 59.88 | 440,000 | 10%
STARTER | SSL-STD | 1 | incl | (incl) | -
PRO | WP-DELUXE-3Y | 1 | 119.88 | 750,000 | 10%
PRO | BACKUP-PREMIUM | 1 | incl | (incl) | -
ENTERPRISE | WP-DELUXE-3Y | 1 | 119.88 | 1,200,000 | 20%
ENTERPRISE | SSL-EV | 1 | incl | (incl) | -
ENTERPRISE | SUPPORT-24/7 | 1 | incl | (incl) | -

Validación: Total GoDaddy USD → COP con margen = Gano publicado ✓
```

---

## Paso 3: Crear Bundles en RCC

### Navegar a Bundles
```
RCC → Products → Bundles → Create Bundle

Información a ingresar:
- Bundle Name: [Ej: "GANO STARTER 3-YEAR"]
- Bundle Description: [Hosting WordPress entrada con SSL]
- Bundle SKU: [Ej: "GANO-STARTER-3Y"]
- Products in Bundle:
  □ WP-ECON-3Y (qty: 1)
  □ SSL-STD (qty: 1) [si aplica add-on]
- Pricing:
  - Base price (USD): [auto-calculated]
  - Discount: [x% o $x] (opcional)
  - Final price: [revisado]
```

### Repetir para cada plan
```
1. GANO STARTER 3-YEAR
2. GANO PRO 3-YEAR
3. GANO ENTERPRISE 3-YEAR
```

### Validar Bundles Creados
```
RCC → Products → Bundles
Verificar:
✓ Nombre correcto
✓ SKU disponible
✓ Productos incluidos
✓ Precio USD correcto
✓ Descripción clara
```

---

## Paso 4: Pricing Override (COP + Margen Gano)

### Implementación: gano-reseller-enhancements.php
```php
<?php
/**
 * Plugin: gano-reseller-enhancements
 * Purpose: Override pricing USD → COP, aplicar margen Gano
 */

// Hook en gano-reseller-store para manipular precios
add_filter('gano_reseller_product_price', function($price, $product_sku) {
    // Mapeo: SKU → Precio Gano en COP
    $gano_pricing = array(
        'GANO-STARTER-3Y' => 440000,   // COP
        'GANO-PRO-3Y'     => 750000,   // COP
        'GANO-ENTERPRISE-3Y' => 1200000, // COP
    );
    
    if (isset($gano_pricing[$product_sku])) {
        return $gano_pricing[$product_sku];
    }
    
    // Default: Convertir USD → COP (si no existe override)
    $usd_to_cop = 4000; // Tipo cambio aproximado
    return $price * $usd_to_cop;
}, 10, 2);

// ACF campos (opcional, para precio manual en wp-admin)
add_field_group(array(
    'key'  => 'group_gano_pricing',
    'title' => 'Pricing Override',
    'location' => array(
        array(
            array(
                'param'    => 'post_type',
                'operator' => '==',
                'value'    => 'gano-reseller-product'
            )
        )
    ),
    'fields' => array(
        array(
            'key'   => 'field_price_cop',
            'label' => 'Precio Gano (COP)',
            'name'  => 'price_cop',
            'type'  => 'number',
        ),
        array(
            'key'   => 'field_godaddy_sku',
            'label' => 'GoDaddy SKU',
            'name'  => 'godaddy_sku',
            'type'  => 'text',
        )
    )
));
?>
```

### Validar Override en Frontend
```
URL: https://gano.digital/shop-premium/

Buscar:
✓ Precio mostrado en COP
✓ Icono $ colombiano
✓ Conversión correcta (USD → COP)
✓ Margen aplicado (GoDaddy USD < Gano COP)
```

---

## Paso 5: Webhook HTTPS Validation

### Verificar Configuración Webhook
```
RCC → Integrations → Webhooks

Información esperada:
✓ URL webhook: https://gano.digital/wp-json/gano-reseller/v1/webhook
✓ Método: POST
✓ Firma HMAC: SHA256 (activado)
✓ Estado: ACTIVO

Nota: deploy.yml workflow 04 ya implementa el handler
```

### Test Webhook (Desde RCC)
```
RCC → Integrations → Webhooks → [Tu webhook] → Test

GoDaddy envía evento de prueba:
{
  "eventType": "ORDER_CREATED",
  "timestamp": "2026-04-12T14:30:00Z",
  "data": {...}
}

Servidor Gano recibe y procesa (debe responder HTTP 200)
```

### Logs de Webhook (En servidor)
```bash
# Ver ultimos eventos recibidos:
tail -f /home/$SERVER_USER/public_html/gano.digital/wp-content/debug.log | grep -i webhook

# Esperado (si funciona):
[2026-04-12 14:30:45] Webhook received: ORDER_CREATED (signature valid)
[2026-04-12 14:30:46] Order processing: GANO-STARTER-3Y x1
```

---

## Paso 6: Smoke Test Checkout Completo

**Duración:** 15 minutos  
**Ambiente:** Production (gano.digital)  
**Usuarios:** Diego + QA

### Escenario 1: Compra Bundle Básico
```
1. Ir a https://gano.digital/shop-premium/
2. Seleccionar: "GANO STARTER 3-YEAR"
3. Click: "Comprar ahora"
4. Revisar carrito:
   ✓ Bundle mostrado correctamente
   ✓ Precio en COP
   ✓ Cantidad: 1
5. Click: "Proceder al pago"
6. Redirige a: GoDaddy checkout (marca blanca gano.digital)
7. Verificar:
   ✓ Logo/branding Gano visible
   ✓ Moneda: COP
   ✓ Total correcto
   ✓ NO redirecciona a godaddy.com
8. Finalizar (usar tarjeta test de GoDaddy)
9. Confirmar: Factura generada bajo Gano Digital (no GoDaddy)
```

### Escenario 2: Compra Bundle Pro
```
Repetir escenario 1 con:
- Plan: GANO PRO 3-YEAR
- Verificar precio alto (COP más caro)
- Validar cantidad de servicios incluidos
```

### Escenario 3: Compra Bundle Enterprise
```
Repetir escenario 1 con:
- Plan: GANO ENTERPRISE 3-YEAR
- Máximo margen/precio
- Validar soporte 24/7 + agente IA
```

### Checklist Post-Checkout
- [ ] Carrito muestra producto correcto
- [ ] Redirección a GoDaddy sin salir de gano.digital
- [ ] Factura en COP
- [ ] Facturación a nombre "Gano Digital S.A.S." (no GoDaddy)
- [ ] Cliente recibe email de confirmación
- [ ] Acceso a portal de cliente funciona
- [ ] Agente IA asignado automáticamente

---

## Optimizaciones Avanzadas (Post-MVP)

### A/B Testing Precios
```
Cuando Gano tenga tráfico inicial:
- Versión A: Precio actual COP
- Versión B: Precio +5% con "3-year lock" messaging
- Duración: 2 semanas
- Métrica: conversion rate

Herramienta: Google Optimize + Google Analytics 4
```

### Upsell/Cross-sell
```
Post-compra en checkout:
"¿Deseas agregar...?"
- SSL EV adicional
- Backups realtime
- Soporte 24/7
- Agente IA avanzado

Implementación: gano-reseller-enhancements.php
Hook: gano_reseller_post_purchase
```

### Descuentos por Volumen
```
Ejemplo (futuro):
- Compra 1 plan: 10% margen
- Compra 2-5 planes: 12% margen (cliente puede revender)
- Compra 5+ planes: 15% margen (reseller partner)

Requiere: Lógica en pricing override + RCC config
```

---

## Checklist Completitud RCC

- [ ] Auditoría SKU GoDaddy completada
- [ ] Mapeo SKU → Planes Gano documentado
- [ ] Good as Gold account verificado como ACTIVO
- [ ] Bundles creados en RCC (3 planes)
- [ ] Pricing override implementado en gano-reseller-enhancements.php
- [ ] Webhook HTTPS validado y testeado
- [ ] Frontend mostrado precios COP correctamente
- [ ] Smoke test 1 (Starter) completado exitosamente
- [ ] Smoke test 2 (Pro) completado exitosamente
- [ ] Smoke test 3 (Enterprise) completado exitosamente
- [ ] Facturación bajo nombre Gano (validada)
- [ ] Equipo capacitado en RCC básico

---

## Referencias SOTA 2026

- [GoDaddy WordPress Reseller Store Plugin](https://github.com/godaddy/wp-reseller-store)
- [GoDaddy Reseller Control Center](https://reseller.godaddy.com/)
- [GoDaddy Developer Portal](https://developer.godaddy.com/)
- [SOTA Investigation 2026-04](../../memory/research/sota-investigation-2026-04.md) — § 2 GoDaddy Reseller Integration
