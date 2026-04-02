# Smoke Test — Checkout Reseller (Sandbox / Staging)
_Ejecutado por: GitHub Copilot Agent (issue #31) · Fecha: 2026-04-02_

---

## Alcance

Verificación estática del flujo CTA → carrito marca blanca de GoDaddy Reseller,
basada en análisis de código del repositorio. No se ejecutó contra wp-admin ni
contra el servidor de producción (acceso no disponible al agente).

---

## Resumen de resultados

| # | Componente | Estado | Descripción |
|---|-----------|--------|-------------|
| 1 | CTA hero → catálogo | ✅ OK | Botón "Explorar Nodos de Red" hace scroll suave a `#catalog` |
| 2 | Filtros de catálogo | ✅ OK | Lógica JS de filtrado por categoría funciona correctamente |
| 3 | Locale API Reseller | ✅ Corregido | Era `es-MX`/`USD`; corregido a `es-CO`/`COP` en este PR |
| 4 | Botones "Adquirir Nodo" | ✅ Corregido | Reemplazados `alert()` por lógica condicional con `[rstore_cart_button]` |
| 5 | IDs de producto Reseller | ❌ Bloqueante | Todos los `rstore_id` están en `0` (pendientes de configurar) |
| 6 | Plugin Reseller Store | ⚠️ Sin confirmar | Plugin v2.2.16 presente en repo; estado de activación en servidor desconocido |
| 7 | `pl_id` (reseller account) | ⚠️ Sin confirmar | `rstore_get_option('pl_id', '599667')` — verificar que `599667` corresponde a la cuenta real de Diego |
| 8 | Carrito marca blanca GoDaddy | ❌ No probado | Sin IDs reales no fue posible abrir `cart.secureserver.net` |

---

## Fallos / Bloqueantes

### 🔴 BLOQUEANTE 1 — IDs de producto sin configurar

**Archivo**: `wp-content/themes/gano-child/templates/shop-premium.php`

Todos los productos tienen `'rstore_id' => 0`. El shortcode `[rstore_cart_button]`
requiere un `product_id` numérico válido que corresponda a un producto configurado
en el Reseller Control Center (RCC) de GoDaddy.

**Acción requerida (Diego)**:
1. Ingresar al [Reseller Control Center](https://reseller.godaddy.com/).
2. Ir a **Products & Pricing** → seleccionar cada producto.
3. Copiar el **Product ID** (visible en la URL o en la ficha del producto).
4. Reemplazar cada `0` en `shop-premium.php` con el ID numérico real.

Ejemplo de mapeo esperado:

| Producto Gano      | Producto GoDaddy aproximado           | rstore_id a completar |
|--------------------|---------------------------------------|-----------------------|
| Núcleo Prime       | WordPress Basic / Starter             | `[TODO]`              |
| Fortaleza Delta    | WordPress Deluxe / Business           | `[TODO]`              |
| Bastión SOTA       | WordPress Ultimate / Pro              | `[TODO]`              |
| Ultimate WP        | Managed WP Advanced / VPS             | `[TODO]`              |
| SSL Deluxe         | SSL OV / DV Premium                   | `[TODO]`              |
| Security Ultimate  | Website Security + Backup             | `[TODO]`              |
| Dominio .CO        | .co domain registration               | `[TODO]`              |
| Dominio .COM       | .com domain registration              | `[TODO]`              |
| M365 Premium       | Microsoft 365 Business Standard       | `[TODO]`              |
| Online Storage 1TB | Online Storage (1 TB)                 | `[TODO]`              |

---

### 🔴 BLOQUEANTE 2 — `pl_id` de cuenta Reseller sin verificar

**Archivo**: `wp-content/plugins/gano-reseller-enhancements/includes/class-bundle-handler.php` línea 58

El valor por defecto es `'599667'`. Este ID identifica tu cuenta de reseller en
GoDaddy y aparece en todas las URLs de carrito (`cart.secureserver.net/?plid=...`).
Si es incorrecto, el carrito cargará vacío o mostrará error.

**Acción requerida (Diego)**:
1. RCC → **Account** → tomar el **Private Label ID (plid)**.
2. Configurarlo en WordPress: **wp-admin → Settings → Reseller Store → Private Label ID**.

---

### 🟡 ADVERTENCIA 1 — Plugin Reseller Store: estado de activación en servidor

El plugin `reseller-store` v2.2.16 está en el repositorio, pero no se puede
confirmar si está **activado** en el servidor de producción sin acceso a wp-admin.

**Acción requerida (Diego)**:
- wp-admin → Plugins → verificar que "Reseller Store" (by GoDaddy) está **activo**.
- Si no está activo, la función `shortcode_exists('rstore_cart_button')` devolverá
  `false` y los botones seguirán mostrando "Configuración pendiente".

---

### 🟡 ADVERTENCIA 2 — Bundles 3 años: IDs de ejemplo

**Archivo**: `wp-content/plugins/gano-reseller-enhancements/includes/class-bundle-handler.php`

Los IDs de producto en los bundles `GANO-STARTER-3YR`, `GANO-PRO-3YR`,
`GANO-ENTERPRISE-3YR` son valores de ejemplo (`301`, `320`, `600`, etc.) y **no
corresponden** a IDs reales. Los bundles no deben activarse en producción hasta
completar el mapeo.

---

## Correcciones aplicadas en este PR

### ✅ Fix 1 — Locale de API corregido a `es-CO` / `COP`

```php
// Antes (incorrecto para Colombia)
$args['marketId']     = 'es-MX';
$args['currencyType'] = 'USD';

// Después (correcto)
$args['marketId']     = 'es-CO';
$args['currencyType'] = 'COP';
```

**Archivo**: `wp-content/plugins/gano-reseller-enhancements/gano-reseller-enhancements.php`

Sin este fix, los precios se mostraban en dólares con locale mexicano, lo que
causaría que el carrito de GoDaddy no mostrara precios COP.

---

### ✅ Fix 2 — Botones de carrito reemplazados

Los botones `onclick="alert(...)"` se reemplazaron por lógica PHP condicional:

- Si el plugin Reseller Store está activo **y** el `rstore_id` es mayor a 0:
  → se renderiza `[rstore_cart_button product_id="..."]` (integración real).
- Si faltan IDs o el plugin está inactivo:
  → se muestra botón deshabilitado con tooltip claro: _"ID de producto Reseller
  no configurado. Ver TASKS.md Fase 4."_

---

## Próximos pasos para completar el flujo

1. **Diego** completa la tabla de IDs en `shop-premium.php` (bloqueante 1).
2. **Diego** verifica `pl_id` en wp-admin (bloqueante 2).
3. **Diego** confirma que el plugin Reseller Store está activo (advertencia 1).
4. Re-ejecutar smoke test manual: abrir `/shop-premium` en staging → clic en
   "Adquirir Nodo" → verificar redirect a `cart.secureserver.net` con productos
   correctos y precios en COP.
5. Completar prueba de checkout sandbox (sin datos de pago reales): GoDaddy
   Reseller no expone un modo sandbox explícito; la verificación se hace
   abandonando el carrito antes de pagar.

---

## Referencias

- Plugin Reseller Store: `wp-content/plugins/reseller-store/`
- Enhancements Gano: `wp-content/plugins/gano-reseller-enhancements/`
- Template shop: `wp-content/themes/gano-child/templates/shop-premium.php`
- TASKS.md sección Fase 4
- Reseller Control Center: https://reseller.godaddy.com/
