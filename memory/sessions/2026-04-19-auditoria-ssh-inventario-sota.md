# Auditoria SSH + inventario SOTA (produccion)

Fecha: 2026-04-19
Entorno inspeccionado: `gano-godaddy` (`/home/f1rml03th382/public_html/gano.digital`)

## Objetivo

Validar en servidor real:
- que esta realmente activo en produccion,
- que esta pendiente/inactivo,
- que drift existe entre repo y servidor,
- y ejecutar ajustes de bajo riesgo para alinear operacion comercial.

## Hallazgos clave

1. Stack y runtime
   - Linux + PHP `8.3.30` + WordPress `6.9.4`.
   - Tema activo: `gano-child` `1.0.1`.
   - Plugins activos principales: `gano-phase6-catalog`, `gano-phase7-activator`, `gano-reseller-enhancements`, `reseller-store`.
   - `woocommerce`, `wordfence`, `elementor`, `rank-math` instalados pero inactivos.

2. Home y navegacion
   - Front real usa pagina estatica (`page_on_front = 1745`, slug `inicio`) y sirve `https://gano.digital/`.
   - Existia pagina legacy `/home/` publicada (ID `1657`) y menu principal apuntando a ella.
   - Se detecto duplicado publicado de dominios (`dominios-2`, ID `1808`).

3. Catalogo Reseller
   - El post type `reseller_product` esta poblado y operativo.
   - `rstore_pl_id` existe y responde (`599667`).
   - Las opciones `gano_pfid_*` no estaban registradas en `wp_options` (fallback `PENDING_RCC` en tema).
   - El catalogo de `/ecosistemas/` ya estaba publicando links de carrito Reseller y flujo comercial activo.

4. Seguridad y bots
   - `.htaccess` mantiene una regla anti-scraper estricta que bloquea UAs tecnicos (ej. `curl`, `urllib`, etc.).
   - Resultado observado: `/` puede retornar `403` para ciertos user agents tecnicos, pero `200` para navegador.
   - Logs muestran actividad frecuente de `CSP_VIOLATION` relacionada a recursos de `gui.secureserver.net`.

5. Drift repo <-> servidor
   - MD5 de archivos criticos difiere entre local y produccion (`front-page.php`, `functions.php`, `homepage.css`, `gano-homepage.js`, `shop-premium.php`, `gano-seo.php`, `gano-security.php`, `class-pfid-admin.php`).
   - Convergencia aplicada (2026-04-19): fuente canonica = repo `main` local; despliegue por `scp` a produccion tras backup en servidor (`/home/f1rml03th382/backups/repo-converge-20260419-125954/`). Post-deploy, MD5 de los 8 archivos coincide byte-a-byte con el working tree local.

## Cambios ejecutados via SSH (esta sesion)

1. Backups previos
   - Export DB: `backups/sota-audit-2026-04-19/db.pre-wave.sql`
   - Copia de `.htaccess` pre-ola en `backups/sota-audit-2026-04-19/`.

2. Bot content para crawlers/LLM con CTA comercial
   - Creado `llms.txt` en raiz web.
   - Creado `bot-seo-context.md` en raiz web.
   - Ambos accesibles por HTTP (`200`).

3. Canonical home cleanup (bajo riesgo)
   - Pagina legacy `home` (ID `1657`) pasada a `draft`.
   - Se elimino metadato de template invalido (`_wp_page_template`) en esa pagina para evitar warnings de update.
   - Menu principal: se elimino item viejo de Home y se creo item custom `Inicio -> https://gano.digital/` (ID `1812`).

4. Slug/duplicado cleanup
   - `dominios-2` (ID `1808`) enviado a papelera.

5. PFID options (gano) inicializadas desde IDs de catalogo Reseller
   - `gano_pfid_hosting_economia = wordpress-basic`
   - `gano_pfid_hosting_deluxe = wordpress-deluxe`
   - `gano_pfid_hosting_premium = wordpress-ultimate`
   - `gano_pfid_hosting_ultimate = cpanel-ultimate`
   - `gano_pfid_ssl_deluxe = ssl-standard`
   - `gano_pfid_security_ultimate = website-security-premium`
   - `gano_pfid_m365_premium = microsoft-365-business-premium`
   - `gano_pfid_online_storage = PENDING_RCC` (sin mapping claro en catalogo activo actual).

6. Hardening bots policy (balanceado) + limpieza de plugins inactivos
   - `.htaccess` actualizado para permitir discovery bots/LLM y bloquear solo escaneres abusivos:
     - bloqueados: `sqlmap|nikto|acunetix|masscan|dirbuster|wpscan|zgrab|nmap|httrack`.
   - Pruebas de respuesta HTTP:
     - `curl -A "GPTBot/1.0" https://gano.digital` => `200`.
     - `curl -A "sqlmap/1.7" https://gano.digital` => `403`.
     - `curl` default => `200`.
   - Eliminados 13 plugins inactivos no criticos (Elementor stack, WooCommerce, plugins legacy de fases/importacion/wompi).
   - Inventario final de plugins deja solo runtime reseller activo + `rank-math` y `wordfence` inactivos para activacion posterior si se decide.

## Pendientes recomendados (siguiente ola)

1. QA comercial post-cambios
   - Smoke en `/ecosistemas/`, `/shop-premium/`, `/dominios/`, `/contacto/`.
   - Verificar que no haya regresion por el cambio de item Home y la baja de `/home/`.

2. Convergencia repo-servidor — **hecha 2026-04-19**
   - Fuente canonica = `main`; despliegue SCP de 8 archivos criticos + MD5 verificado; ver [`2026-04-19-trazabilidad-ops-wave-handoff.md`](2026-04-19-trazabilidad-ops-wave-handoff.md).

## Indice maestro (post-ola)

[`2026-04-19-trazabilidad-ops-wave-handoff.md`](2026-04-19-trazabilidad-ops-wave-handoff.md) — estado hecho/pendiente, alineacion de herramientas, enlaces a GitHub [#263](https://github.com/Gano-digital/Pilot/issues/263).
