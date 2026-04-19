# Issue #264 — RCC: cargar PFIDs/PLID y validar checkout

**Estado**: 🔴 Bloqueado por acción humana (Diego)  
**Fecha de análisis**: 2026-04-19  
**Responsable siguiente paso**: Diego (acceso wp-admin + RCC)

---

## Resumen

El flujo de checkout Reseller GoDaddy requiere dos datos que solo Diego puede obtener del
Reseller Control Center (RCC) y configurar en wp-admin:

1. **8 PFIDs** (Private Family ID) — uno por producto del catálogo Gano Digital.
2. **pl_id** (Private Label ID) — identificador de la cuenta Reseller en GoDaddy.

La infraestructura de código está **100 % completa** y lista en el repositorio.
Los CTAs en `/ecosistemas` funcionan pero redirigen a `/contacto` en lugar del carrito
mientras los PFIDs estén en `PENDING_RCC`.

---

## Estado de infraestructura (código ya en producción)

| Componente | Archivo | Estado |
|---|---|---|
| Panel PFID (`Ajustes → Gano Reseller`) | `wp-content/plugins/gano-reseller-enhancements/includes/class-pfid-admin.php` | ✅ Listo |
| Smoke Test (`Herramientas → Smoke Test Reseller`) | `wp-content/plugins/gano-reseller-enhancements/includes/class-smoke-test.php` | ✅ Listo (checks 1–7) |
| Modo sandbox carrito GoDaddy OTE | `wp-content/plugins/gano-reseller-enhancements/includes/class-sandbox.php` | ✅ Listo |
| Constantes PFID en `functions.php` | `wp-content/themes/gano-child/functions.php` (L. 965–993) | ✅ Listo (leen `wp_options`) |
| Bundle handler (SKUs 3-año) | `wp-content/plugins/gano-reseller-enhancements/includes/class-bundle-handler.php` | ⚠️ IDs de ejemplo; completar tras PFIDs reales |
| Locale API (`es-CO` / `COP`) | `gano-reseller-enhancements.php` filtro `rstore_api_query_args` | ✅ Corregido |
| Fallback CTAs (sin PFID → `/contacto`) | `wp-content/themes/gano-child/templates/shop-premium.php` | ✅ Comportamiento seguro |

---

## Qué hace el smoke test ahora (tras commit de esta oleada)

El check **5** del smoke test (`Herramientas → Smoke Test Reseller`) verifica los 8 PFIDs
y muestra en una tabla cuáles están configurados y cuáles siguen en `PENDING_RCC`.

Ruta para acceder: `wp-admin/tools.php?page=gano-reseller-smoke-test`

---

## Checklist de acción humana (Diego)

### Paso 1 — Obtener pl_id del RCC
1. Ir a https://reseller.godaddy.com/
2. `Account → Account Details → Private Label ID`
3. Copiar el número (ej. `599667`)
4. En wp-admin: `Ajustes → Reseller Store → Private Label ID` → pegar y guardar.

> ⚠️ Verificar que el valor existente `599667` en el código corresponde a la cuenta real.
> Si es incorrecto, todos los carritos fallarán con carrito vacío.

### Paso 2 — Obtener los 8 PFIDs del RCC

Navegar en el RCC: **Products → Product Catalog** y copiar el PFID de cada uno:

| # | Producto GoDaddy en RCC | Constante PHP | wp_option |
|---|---|---|---|
| 1 | WordPress Hosting Economy | `GANO_PFID_HOSTING_ECONOMIA` | `gano_pfid_hosting_economia` |
| 2 | WordPress Hosting Deluxe | `GANO_PFID_HOSTING_DELUXE` | `gano_pfid_hosting_deluxe` |
| 3 | WordPress Hosting Premium | `GANO_PFID_HOSTING_PREMIUM` | `gano_pfid_hosting_premium` |
| 4 | WordPress Hosting Ultimate | `GANO_PFID_HOSTING_ULTIMATE` | `gano_pfid_hosting_ultimate` |
| 5 | SSL DV Deluxe | `GANO_PFID_SSL_DELUXE` | `gano_pfid_ssl_deluxe` |
| 6 | Website Security Premium | `GANO_PFID_SECURITY_ULTIMATE` | `gano_pfid_security_ultimate` |
| 7 | Microsoft 365 Business Premium | `GANO_PFID_M365_PREMIUM` | `gano_pfid_m365_premium` |
| 8 | Online Storage 1 TB | `GANO_PFID_ONLINE_STORAGE` | `gano_pfid_online_storage` |

> Formato aceptado: 3–10 dígitos numéricos. Ejemplo válido: `123456`.

### Paso 3 — Ingresar PFIDs en wp-admin
1. `wp-admin → Ajustes → Gano Reseller`
2. Para cada fila, pegar el PFID numérico del RCC.
3. Hacer clic en **Guardar PFIDs**.
4. Verificar que aparece el banner verde **"✓ Checkout listo"** (8/8 configurados).

### Paso 4 — Smoke test manual
1. `wp-admin → Herramientas → Smoke Test Reseller`
2. Activar **Modo sandbox** y guardar.
3. Verificar que los checks 1–5 están en ✅ (pl_id, plugin activo, PFIDs configurados, URL construida).
4. Hacer clic en la URL de checkout generada → debe abrir `cart.test-godaddy.com` con productos.
5. Verificar que el precio está en **COP** y el producto es correcto.
6. **NO ingresar datos de pago** — abandonar el carrito antes de pagar.
7. Si todo OK: desactivar modo sandbox.

### Paso 5 — Smoke test en producción (tras sandbox exitoso)
1. `wp-admin → Herramientas → Smoke Test Reseller`
2. Confirmar que **Modo sandbox** está desactivado.
3. Ir a `https://gano.digital/ecosistemas/`
4. Hacer clic en **"Elegir Núcleo Prime"** (u otro CTA).
5. Verificar que abre `cart.secureserver.net/?plid=...` con el producto y precio COP.
6. Abandonar el carrito antes de pagar.

### Paso 6 — Documentar evidencia
Actualizar este archivo con:
- ✅ Fecha de configuración de PFIDs
- ✅ Resultado del smoke test sandbox
- ✅ Resultado del smoke test producción
- Screenshots en `memory/ops/screenshots/` (si aplica)

---

## Troubleshooting rápido

| Síntoma | Causa | Solución |
|---|---|---|
| CTA redirige a `/contacto` | PFID sigue en `PENDING_RCC` | Paso 3 arriba |
| Carrito con `plid=0` | `pl_id` no configurado | Paso 1 arriba |
| Carrito muestra USD | Locale incorrecto | Verificar filtro `rstore_api_query_args` en plugin |
| Carrito vacío o 404 | PFID inválido / producto no activo en RCC | Verificar PFID en RCC → Products |
| Panel "Gano Reseller" no aparece en Ajustes | Plugin `gano-reseller-enhancements` inactivo | Activar en wp-admin → Plugins |

---

## Referencias

- Panel PFID: `wp-content/plugins/gano-reseller-enhancements/includes/class-pfid-admin.php`
- Smoke test: `wp-content/plugins/gano-reseller-enhancements/includes/class-smoke-test.php`
- Checklist completo PFIDs: `memory/commerce/rcc-pfid-checklist.md`
- Runbook activación plugins: `memory/ops/runbook-activacion-wp-admin-2026-04-16.md`
- Reseller Control Center: https://reseller.godaddy.com/
- TASKS.md § Fase 4
