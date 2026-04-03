# Checklist RCC → PFID (GoDaddy Reseller) — Gano Digital

**Para:** Diego (operador). **No** pegar pfids ni credenciales en issues públicos de GitHub.

## 1. Dónde obtener el pfid

1. Entra al **Reseller Control Center (RCC)** de GoDaddy con tu cuenta reseller.
2. Navega a **Productos** / catálogo según la documentación actual de GoDaddy Reseller.
3. Por cada producto que vendes en la vitrina, localiza el identificador que usa el ecosistema Reseller para el carrito (**Product Family ID** / pfid — el nombre exacto puede variar en la UI; alinea con lo que espera el plugin Reseller Store en WordPress).
4. Anota el pfid en una hoja **local** o gestor de secretos interno (no en el chat de IA pública).

## 2. Private Label ID (PLID)

- En **wp-admin → Ajustes → Reseller Store** (o equivalente del plugin): confirma **Private Label ID** (`pl_id`).
- Sin `pl_id` válido, `gano_rstore_cart_url()` devuelve `#` aunque los pfids estén bien.

## 3. Mapeo constante → producto (código en repo)

Definiciones en `wp-content/themes/gano-child/functions.php` (buscar `GANO_PFID_`). Sustituir cada `'PENDING_RCC'` por el string del pfid **solo** cuando lo tengas del RCC.

| Constante | Nombre en vitrina (shop-premium) | Comentario en código |
|-----------|-----------------------------------|----------------------|
| `GANO_PFID_HOSTING_ECONOMIA` | Núcleo Prime | WordPress Hosting Economy |
| `GANO_PFID_HOSTING_DELUXE` | Fortaleza Delta | WordPress Hosting Deluxe |
| `GANO_PFID_HOSTING_PREMIUM` | Bastión SOTA | WordPress Hosting Premium |
| `GANO_PFID_HOSTING_ULTIMATE` | Ultimate WP | WordPress Hosting Ultimate |
| `GANO_PFID_SSL_DELUXE` | SSL Deluxe | SSL DV Deluxe |
| `GANO_PFID_SECURITY_ULTIMATE` | Security Ultimate | Website Security Premium |
| `GANO_PFID_M365_PREMIUM` | M365 Premium | Microsoft 365 Business Premium |
| `GANO_PFID_ONLINE_STORAGE` | Online Storage 1TB | Online Storage 1 TB |

Plantilla del catálogo y precios visibles: `wp-content/themes/gano-child/templates/shop-premium.php`.

## 4. Cómo aplicar el cambio

1. Editar `functions.php` en el child theme (o desplegar desde repo tras commit).
2. **Opción más segura:** definir los `define()` vía `wp-config.php` o MU-plugin **solo en el servidor** si no quieres pfids en git — entonces no reemplazar en el archivo versionado y documentar el patrón en runbook interno.
3. Tras cambiar: probar un CTA “Comprar” en **staging** primero; la URL debe parecerse a `cart.secureserver.net` con `plid` y `items` JSON (ver comentarios en `functions.php`).

## 5. Validación rápida

- Clic en comprar desde una fila del shop: no debe quedarse en `#` si pfid y pl_id están configurados.
- Revisar consola del navegador por errores de red al abrir el carrito.

## Referencias

- `TASKS.md` — Fase 4 Reseller.
- `memory/sessions/2026-04-03-consolidacion-prs-copilot.md` — modelo pfid en main post-PR #52.
