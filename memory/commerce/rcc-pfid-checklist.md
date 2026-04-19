# RCC → PFID / PLID Checklist — Gano Digital

**Guía operativa para configurar el checkout GoDaddy Reseller en gano.digital**

_Última actualización: 2026-04-19 (convergencia con código real — issue #264)_  
_Responsable: Diego (datos RCC) · Agentes (documentación)_

> **Cambio v2:** tabla de mapeo alineada con los **8 campos reales** del panel
> `wp-admin → Ajustes → Gano Reseller` (plugin `gano-reseller-enhancements`) y las
> constantes actuales en `functions.php`. Se retiraron referencias a constantes obsoletas
> (`GANO_PFID_ESSENTIAL_HOSTING`, etc.) y al flujo "Antigravity + 9 PFIDs".

---

## 1. ¿Qué son PFID y PLID?

| Término | Significado | Dónde se obtiene |
|---------|-------------|-----------------|
| **PFID** (Private Family ID) | Identificador numérico del producto en tu catálogo Reseller | GoDaddy RCC → Products → Product Catalog |
| **PLID** (Private Label ID) | Identificador de tu cuenta Reseller; aparece en la URL del carrito como `?plid=…` | GoDaddy RCC → Account → Private Label ID |

Ambos son necesarios para que el botón "Adquirir Nodo" lleve al carrito correcto.

---

## 2. Dónde viven estos valores en el código

### PFID — 8 constantes en `functions.php` (~líneas 965–993)

Los valores se leen de `wp_options` (escritos por el panel wp-admin). Si una opción no está
configurada, el fallback es `'PENDING_RCC'` y el CTA redirige a `/contacto` en lugar del carrito.

```php
define('GANO_PFID_HOSTING_ECONOMIA',  get_option('gano_pfid_hosting_economia',  'PENDING_RCC')); // Núcleo Prime
define('GANO_PFID_HOSTING_DELUXE',    get_option('gano_pfid_hosting_deluxe',    'PENDING_RCC')); // Fortaleza Delta
define('GANO_PFID_HOSTING_PREMIUM',   get_option('gano_pfid_hosting_premium',   'PENDING_RCC')); // Bastión SOTA
define('GANO_PFID_HOSTING_ULTIMATE',  get_option('gano_pfid_hosting_ultimate',  'PENDING_RCC')); // Ultimate WP
define('GANO_PFID_SSL_DELUXE',        get_option('gano_pfid_ssl_deluxe',        'PENDING_RCC'));
define('GANO_PFID_SECURITY_ULTIMATE', get_option('gano_pfid_security_ultimate', 'PENDING_RCC'));
define('GANO_PFID_M365_PREMIUM',      get_option('gano_pfid_m365_premium',      'PENDING_RCC'));
define('GANO_PFID_ONLINE_STORAGE',    get_option('gano_pfid_online_storage',    'PENDING_RCC'));
```

### PLID — plugin Reseller Store

El PLID se leen con `rstore_get_option('pl_id')` desde el plugin `reseller-store`.
Configuración: `wp-admin → Ajustes → Reseller Store → Private Label ID`.

---

## 3. Tabla de mapeo: 8 campos reales

> ⚠️ No commitear valores PFID/PLID reales en el repositorio. Configurar solo en wp-admin.

| # | Etiqueta en wp-admin | Ecosistema Gano | Constante PHP | Campo `wp_options` | Estado |
|---|---|---|---|---|---|
| 1 | WordPress Hosting Economy (Núcleo Prime) | Núcleo Prime | `GANO_PFID_HOSTING_ECONOMIA` | `gano_pfid_hosting_economia` | ⏳ Pendiente Diego |
| 2 | WordPress Hosting Deluxe (Fortaleza Delta) | Fortaleza Delta | `GANO_PFID_HOSTING_DELUXE` | `gano_pfid_hosting_deluxe` | ⏳ Pendiente Diego |
| 3 | WordPress Hosting Premium (Bastión SOTA) | Bastión SOTA | `GANO_PFID_HOSTING_PREMIUM` | `gano_pfid_hosting_premium` | ⏳ Pendiente Diego |
| 4 | WordPress Hosting Ultimate (Ultimate WP) | Ultimate WP | `GANO_PFID_HOSTING_ULTIMATE` | `gano_pfid_hosting_ultimate` | ⏳ Pendiente Diego |
| 5 | SSL DV Deluxe | Add-on SSL | `GANO_PFID_SSL_DELUXE` | `gano_pfid_ssl_deluxe` | ⏳ Pendiente Diego |
| 6 | Website Security Premium | Add-on seguridad | `GANO_PFID_SECURITY_ULTIMATE` | `gano_pfid_security_ultimate` | ⏳ Pendiente Diego |
| 7 | Microsoft 365 Business Premium | Add-on email | `GANO_PFID_M365_PREMIUM` | `gano_pfid_m365_premium` | ⏳ Pendiente Diego |
| 8 | Online Storage 1 TB | Add-on almacenamiento | `GANO_PFID_ONLINE_STORAGE` | `gano_pfid_online_storage` | ⏳ Pendiente Diego |

---

## 4. Cómo obtener PFIDs desde el RCC

1. Ir a <https://reseller.godaddy.com/> → iniciar sesión.
2. Navegar a **Products → Product Catalog**.
3. Buscar el producto (ej. "WordPress Hosting Economy").
4. Copiar el **PFID** de los detalles del producto (número puro, 3–10 dígitos).
5. Repetir para los 8 productos de la tabla.
6. Anotar el **PLID** en **Account → Private Label ID** (aparece también en la URL del storefront).

**Formato válido:** `123456` (solo dígitos, sin prefijos ni guiones).

---

## 5. Cómo ingresar los valores en wp-admin (PASO 10E del runbook)

1. Ir a `wp-admin → Ajustes → Gano Reseller`.
2. Pegar cada PFID en el campo correspondiente.
3. Clic en **Guardar PFIDs**.
4. Verificar: aparece banner **"✓ Checkout listo"** cuando los 8 estén configurados.
5. Ir a `wp-admin → Ajustes → Reseller Store` → ingresar el **PLID** en el campo "Private Label ID".

> Runbook completo (con plugins de fase y validación final):
> `memory/ops/runbook-activacion-wp-admin-2026-04-16.md`

---

## 6. Validación (smoke test)

Pasos reproducibles en `memory/research/reseller-smoke-test.md`.

Verificaciones clave:
- URL del carrito tiene `cart.secureserver.net` + PFID + `plid=<tu_PLID>` + `currencyType=COP`.
- Precio aparece en **COP** (no USD).
- No hay mensaje "Configuración pendiente".
- Abandono del carrito sin cargo (no ingresar datos de pago en producción).

---

## 7. Seguridad

⚠️ **No hacer:**
- Pegar PFIDs o PLID en issues, PR comments o archivos del repo.
- Commitear valores reales en `functions.php` o cualquier otro archivo del repo.

✅ **Hacer:**
- Configurar solo desde `wp-admin → Ajustes → Gano Reseller` (los valores quedan en `wp_options`).
- Si algo falla, verificar con `wp-admin → Ajustes → Gano Reseller` que los valores no sean `PENDING_RCC`.

---

## 8. Troubleshooting

| Síntoma | Causa probable | Solución |
|---------|----------------|---------|
| CTA dice "Configuración pendiente" o abre `#` | PFID sigue en `PENDING_RCC` | Ajustes → Gano Reseller → ingresar valor |
| URL del carrito tiene `plid=0` | PLID no configurado | Ajustes → Reseller Store → Private Label ID |
| Precios en USD en el carrito | Locale incorrecto | Verificar `es-CO` / `COP` en `gano-reseller-enhancements.php` |
| Panel "Gano Reseller" no aparece | Plugin desactivado | Plugins → activar `gano-reseller-enhancements` |
| PFID rechazado al guardar | Formato inválido | Solo dígitos, 3–10 caracteres; sin prefijos |

---

## 9. Referencias

| Documento | Propósito |
|-----------|-----------|
| `TASKS.md` § Fase 4 | Estado operativo y pasos PASO 10E |
| `memory/ops/runbook-activacion-wp-admin-2026-04-16.md` | Runbook completo de activación |
| `memory/research/reseller-smoke-test.md` | Pasos del smoke test checkout |
| `wp-content/plugins/gano-reseller-enhancements/includes/class-pfid-admin.php` | Código del panel PFID |
| `wp-content/themes/gano-child/functions.php` (~L. 965–993) | Constantes PFID + función `gano_rstore_cart_url()` |
| `.github/DEV-COORDINATION.md` | Secretos GoDaddy API (si aplica) |

---

## 10. Checklist de cierre (issue #264)

```
BLOQUEADO por acción humana (Diego):
  [ ] 0. Pre-check: backup en GoDaddy → Managed WordPress → Back Up Now
  [ ] 1. Reseller Store activo en wp-admin → Plugins
  [ ] 2. gano-reseller-enhancements activo en wp-admin → Plugins
  [ ] 3. Obtener PLID desde RCC → Account → Private Label ID
  [ ] 4. Ingresar PLID en wp-admin → Ajustes → Reseller Store
  [ ] 5. Obtener los 8 PFIDs desde RCC → Products → Product Catalog
  [ ] 6. Ingresar los 8 PFIDs en wp-admin → Ajustes → Gano Reseller → Guardar
  [ ] 7. Banner "✓ Checkout listo" visible (8/8 configurados)
  [ ] 8. Smoke test: clic en CTA → URL cart.secureserver.net con plid y pfid reales
  [ ] 9. Verificar precio en COP en el carrito GoDaddy
  [ ] 10. Anotar fecha y resultado en issue #264
```

---

**Creado:** 2026-04-16 · **Actualizado:** 2026-04-19 (v2 — alineado con código real)  
**Referencia issue:** [#264](https://github.com/Gano-digital/Pilot/issues/264)
