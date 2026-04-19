# Smoke Test — Flujo Carrito Reseller GoDaddy (Pasos Reproducibles)

_Documento de referencia — issue #231 · Fase 4 Gano Digital · Abril 2026_

---

## Objetivo

Verificar manualmente que el flujo **CTA en gano.digital → carrito marca blanca GoDaddy Reseller** funciona
de extremo a extremo, sin ejecutar un pago real en producción.

> **Nota:** GoDaddy Reseller no expone un modo sandbox explícito para el carrito.
> La técnica segura es **abandonar el carrito antes de ingresar datos de pago**.
> Para OTE (Operational Test Environment) consulta la nota al final.

---

## Prerequisitos

| Requisito | Estado esperado |
|-----------|----------------|
| Plugin `reseller-store` activo en wp-admin | ✅ Verificar en wp-admin → Plugins |
| `pl_id` configurado en wp-admin → Ajustes → Reseller Store | ✅ Debe coincidir con Private Label ID del RCC |
| PFIDs de producto ingresados en wp-admin → Ajustes → Gano Reseller | ✅ Los 8 campos `GANO_PFID_*` con valores reales del RCC |
| URL de staging disponible (opcional pero recomendada) | `staging.gano.digital` si el entorno de staging está activo |

> Si algún prerequisito no está cumplido, los botones mostrarán "Configuración pendiente".
> Completar el checklist en [`memory/commerce/rcc-pfid-checklist.md`](../commerce/rcc-pfid-checklist.md) antes de continuar.

---

## Pasos del Smoke Test

### Paso 1 — Navegar al catálogo de productos

1. Abrir en el navegador: `https://gano.digital/shop-premium/`
   (o `https://staging.gano.digital/shop-premium/` para staging).
2. **Verificar:** la página carga sin errores JavaScript en la consola del navegador.
3. **Verificar:** los productos (Núcleo Prime, Fortaleza Delta, Bastión SOTA, Ultimate WP) son visibles con sus precios en **COP**.
4. **Verificar:** los filtros de categoría (Hosting, Seguridad, Dominios, etc.) funcionan.

> **URL alternativa de entrada:** la sección Hero de `https://gano.digital/` tiene el botón
> **"Explorar Nodos de Red"** que hace scroll a `#catalog`; desde ahí también se puede
> llegar al CTA de compra.

---

### Paso 2 — Hacer clic en el CTA de un producto

1. Localizar el producto **Núcleo Prime** (plan más básico, menor riesgo de prueba).
2. Hacer clic en el botón **"Adquirir Nodo"** (o equivalente según copy actual).
3. **Verificar:** el clic no abre un `alert()` ni muestra "Configuración pendiente".
4. **Verificar:** el navegador redirige a una URL con dominio `cart.secureserver.net`
   (o `crt.secureserver.net`). Ejemplo de URL esperada:
   ```
   https://cart.secureserver.net/order/main/add/[PFID]?plid=[PL_ID]&currencyType=COP&marketId=es-CO
   ```

> Si la URL del carrito tiene `plid=0` o `currencyType=USD`, hay un error de configuración —
> revisar `pl_id` y locale en el plugin `gano-reseller-enhancements`.

---

### Paso 3 — Verificar contenido del carrito marca blanca

1. En la página del carrito GoDaddy (dominio `cart.secureserver.net`):
   - **Verificar:** el producto corresponde al seleccionado (nombre y descripción legibles).
   - **Verificar:** el precio está en **COP** (pesos colombianos).
   - **Verificar:** no hay errores de página ni redirección a una página de error 404/500.
   - **Verificar:** aparece la marca/logo de Gano Digital o la etiqueta de reseller configurada
     en el RCC (si se configuró marca blanca en GoDaddy RCC → Storefront).
2. **Captura de pantalla** del carrito con el producto y precio visible (para evidencia).

---

### Paso 4 — Verificar que el carrito no procede a pago (abandono seguro)

1. **No ingresar** datos de tarjeta ni información de pago real.
2. Cerrar la pestaña del carrito o hacer clic en "Cancelar" / "Volver".
3. Confirmar que no se generó ningún cargo.

> Esto es suficiente para validar que el flujo CTA → carrito funciona.
> Solo continuar al pago en un entorno OTE con credenciales de prueba (ver nota abajo).

---

### Paso 5 — Verificar flujo desde la homepage (opcional)

1. Abrir `https://gano.digital/` (o staging).
2. En la sección Hero, hacer clic en el CTA principal.
3. **Verificar:** scroll suave a `#catalog` o redirección a `/shop-premium/`.
4. Repetir los pasos 2–4 para confirmar consistencia.

---

### Paso 6 — Verificar bundle de 3 años (cuando PFIDs estén completos)

1. En `/shop-premium/`, seleccionar un plan con opción de **3 años** (si está disponible).
2. Hacer clic en "Adquirir Nodo".
3. **Verificar:** la URL del carrito incluye el PFID del SKU de 3 años
   (diferente al SKU de 1 año) y el descuento aplicado refleja el precio correcto en COP.

---

## Resultado esperado por paso

| Paso | Qué verificar | ✅ Éxito | ❌ Fallo |
|------|--------------|---------|---------|
| 1 | Página catálogo carga | Sin errores JS, precios en COP | Errores JS o precios en USD |
| 2 | Clic en CTA redirige | URL `cart.secureserver.net` con PFID y `plid` real | `alert()`, "Configuración pendiente" o `plid=0` |
| 3 | Carrito muestra producto | Nombre correcto + precio COP | Carrito vacío, 404 o precio en USD |
| 4 | Abandono sin cargo | Sin transacción | Cualquier cargo no intencionado |
| 5 | Flujo desde homepage | CTA → catálogo → carrito | Botón roto o sin destino |

---

## Sandbox / OTE de GoDaddy Reseller

GoDaddy ofrece el entorno **OTE (Operational Test Environment)** para pruebas de integración:

- **URL OTE:** `https://cart.test-godaddy.com/` (verificar actualidad en documentación GoDaddy Reseller).
- **Credenciales OTE:** solicitar en GoDaddy Reseller Portal → Developer Tools → OTE Access.
- **Tarjeta de prueba OTE:** `4111 1111 1111 1111` (Visa de prueba GoDaddy), fecha futura, CVC cualquier 3 dígitos.
- **Limitación:** OTE puede no reflejar exactamente el catálogo de producción; los PFIDs pueden diferir.

> **Sin OTE configurado:** el smoke test manual con abandono del carrito (pasos 1–4) es suficiente
> para validar el flujo de UI. El pago real solo se prueba en producción con supervisión directa de Diego.

---

## Troubleshooting

| Síntoma | Causa probable | Solución |
|---------|---------------|---------|
| Botón dice "Configuración pendiente" | PFIDs en `0` o plugin inactivo | Completar [`rcc-pfid-checklist.md`](../commerce/rcc-pfid-checklist.md) |
| Carrito con `plid=0` en URL | `pl_id` no configurado | wp-admin → Ajustes → Reseller Store → Private Label ID |
| Precios en USD en el carrito | Locale incorrecto | Verificar `es-CO` / `COP` en `gano-reseller-enhancements.php` |
| Carrito vacío o error 404 | PFID inválido o producto no activo en RCC | Verificar PFID en RCC → Products & Pricing |
| Error CORS o CSP en consola | Headers bloquean `cart.secureserver.net` | Revisar [`mu-plugins/gano-security.php`](../../wp-content/mu-plugins/gano-security.php) directiva `connect-src` |
| Carrito carga pero sin marca blanca | Storefront no configurado en RCC | RCC → Storefront → activar y personalizar con marca Gano Digital |

---

## Referencias

- Plugin Reseller Store: `wp-content/plugins/reseller-store/`
- Plugin Enhancements Gano: `wp-content/plugins/gano-reseller-enhancements/`
- Template shop: `wp-content/themes/gano-child/templates/shop-premium.php`
- Checklist PFIDs: [`memory/commerce/rcc-pfid-checklist.md`](../commerce/rcc-pfid-checklist.md)
- Smoke test estático previo (análisis de código): [`memory/smoke-tests/checkout-reseller-2026-04.md`](../smoke-tests/checkout-reseller-2026-04.md)
- Reseller Control Center: <https://reseller.godaddy.com/>
- TASKS.md § Fase 4
