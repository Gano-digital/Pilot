# URL Canónica: apex vs www — Gano Digital (2026)

**Objetivo:** elegir **una única** URL canónica para `gano.digital` y mantenerla de forma consistente. Mezclar `https://gano.digital` con `https://www.gano.digital` genera duplicados SEO, cookies rotas e inconsistencias en analítica.

---

## 1. Comparativa: apex vs www en Managed WordPress (GoDaddy)

| Criterio | Apex (`gano.digital`) | www (`www.gano.digital`) |
|---|---|---|
| **DNS** | Registro `A` → IP hosting. En algunos DNS, `ALIAS`/`ANAME` para CDN. | Registro `CNAME` posible; flexible para CDNs y balanceadores. |
| **Redirección de raíz** | El dominio es la URL final; no hay subdirectorio que gestionar. | Requiere redirigir `gano.digital` → `www.gano.digital` (301). |
| **Cookies de subdominio** | Las cookies de `gano.digital` **se propagan a todos los subdominios** (`my.`, `support.`, etc.) salvo prefijo `__Host-`. | Las cookies de `www` solo aplican a `www` por defecto, a menos que el dominio de cookie sea `.gano.digital`. |
| **SEO (Google)** | Google trata apex y www como variantes del mismo sitio; ambas son válidas. La que elijas es la preferida en Search Console. | Ídem; lo crítico es que **todas** las URLs apunten a la variante elegida (301 + canónica en HTML). |
| **HSTS preload** | Se puede enviar HSTS desde el apex. Si se agrega al **preload list**, cubre todo el dominio. | HSTS en `www` solo cubre `www`; el apex requiere su propia política. |
| **GoDaddy Managed WP** | La URL se configura en wp-admin → Ajustes → Generales. Hosting trata ambas variantes, pero la redirección la controla el panel de dominio/WordPress. | Ídem. Managed WP permite configurar la URL de WordPress como `https://www.gano.digital`. |
| **Simplicidad** | ✅ URL más corta; menor fricción en materiales de marketing. | ➕ Más común en hosting tradicional; mejor control de subdominios de cookie. |
| **Compatibilidad CDN (GoDaddy CDN)** | ✅ Funciona con ALIAS/ANAME o IP directa. | ✅ CNAME apuntando al CDN; técnicamente más limpio para CDNs externos. |

---

## 2. Recomendación para Gano Digital

### ✅ Recomendación principal: **`https://gano.digital` (apex)**

**Razones:**
1. URL más corta y limpia para materiales de marca ("hosting para negocios").
2. GoDaddy Managed WP ya tiene la URL del sitio configurada como `https://gano.digital` según el estado actual del proyecto.
3. La CDN incluida en el plan y los workflows de CI/CD apuntan al apex.
4. Subdomains planificados (`my.gano.digital`, `support.gano.digital`) son compatibles con apex; se gestionan como registros DNS independientes.

**Configuración mínima:**
1. **wp-admin → Ajustes → Generales:** WordPress Address y Site Address deben ser `https://gano.digital` (sin www).
2. **Panel de dominio (GoDaddy):** añadir redirección `301` de `www.gano.digital` → `https://gano.digital`.
3. **Rank Math / Gano SEO:** URL canónica confirmada como apex; sitemap con URLs `https://gano.digital/...`.
4. **Google Search Console:** añadir `https://gano.digital` como propiedad preferida.

### Alternativa: **`https://www.gano.digital`**

Solo recomendada si:
- Se necesita aislamiento de cookies entre el sitio principal y subdominios (p. ej. portal de cliente con sesión propia en `my.gano.digital`).
- El proveedor CDN requiere CNAME (no permite apex sin ALIAS/ANAME).

Si se elige www: redirigir `gano.digital` → `https://www.gano.digital` en el panel de dominio y actualizar las URLs en WordPress, Rank Math y GSC.

---

## 3. Patrón de redirecciones 301

Independientemente de la variante elegida, deben existir redirecciones permanentes para **todas** las combinaciones posibles hacia la URL canónica:

```
http://gano.digital         → https://gano.digital    (301)
http://www.gano.digital     → https://gano.digital    (301)
https://www.gano.digital    → https://gano.digital    (301)
```

En GoDaddy Managed WP, la redirección HTTP → HTTPS la gestiona el panel de hosting (SSL activado). La redirección www → apex se configura en **Administrador de dominios → Reenvíos** o en el `.htaccess` del sitio:

```apache
# Redirigir www a apex (solo si WordPress no lo hace automáticamente)
RewriteEngine On
RewriteCond %{HTTP_HOST} ^www\.gano\.digital [NC]
RewriteRule ^ https://gano.digital%{REQUEST_URI} [L,R=301]
```

> ⚠️ **No modificar `.htaccess` en producción sin respaldo previo.** Ver también la sección correspondiente en el checklist HTTPS.

---

## 4. HSTS: cuándo y cómo activar

**HSTS (HTTP Strict Transport Security)** instruye al navegador a conectarse **siempre** por HTTPS, eliminando el primer salto HTTP tras la visita inicial.

### Prerrequisitos (NO activar antes de cumplirlos todos)

- [x] El certificado SSL está instalado y **no expirará sin renovación automática** (Let's Encrypt en Managed WP suele renovar automáticamente).
- [x] HTTPS funciona de forma estable, sin errores de certificado ni mixed content.
- [x] La redirección www → apex (o apex → www) funciona correctamente.
- [x] **Todos** los subdominios activos (`my.`, `support.`, etc.) tienen HTTPS — si se usa `includeSubDomains`.
- [x] Se ha probado durante al menos **2 semanas** en producción sin incidentes HTTPS.

### Cabecera recomendada (inicio conservador)

```
Strict-Transport-Security: max-age=300
```

`max-age=300` (5 minutos) permite revertir si se detectan problemas antes de que los navegadores queden "atrapados". Escalar gradualmente:

| Fase | `max-age` | Cuándo |
|---|---|---|
| Prueba inicial | `300` (5 min) | Primeros días tras activar HSTS |
| Estabilización | `86400` (1 día) | Sin incidentes después de 1 semana |
| Producción estable | `2592000` (30 días) | Sin incidentes después de 1 mes |
| HSTS Preload | `31536000` + `includeSubDomains` + `preload` | Solo cuando TODO el ecosistema (`gano.digital` y **todos** sus subdominios) opere 100% HTTPS permanentemente |

### Dónde configurar en GoDaddy Managed WP

La cabecera `Strict-Transport-Security` se añade en `wp-content/mu-plugins/gano-security.php`, función de cabeceras de seguridad (ya existe en el MU plugin). Ejemplo:

```php
// En gano-security.php, función gano_security_headers():
header( 'Strict-Transport-Security: max-age=300' );
// Cambiar max-age según la fase de estabilización descrita arriba.
```

> **ADVERTENCIA:** No añadir `preload` hasta que **todos** los subdominios (`my.gano.digital`, `support.gano.digital`, etc.) tengan HTTPS activo y estable. La inclusión en el preload list de Chrome/Firefox es irreversible a corto plazo (mínimo 1 año para remoción).

### Verificar HSTS

Desde terminal local (o el script `check_dns_https_gano.py`):

```bash
curl -sI https://gano.digital | grep -i strict
```

Respuesta esperada:
```
strict-transport-security: max-age=300
```

---

## 5. Referencias internas

- **Runbook DNS + HTTPS:** [`memory/ops/dns-https-godaddy-runbook-2026.md`](./dns-https-godaddy-runbook-2026.md) — síntomas, verificación en orden, rollback, escalación a soporte GoDaddy.
- **Checklist HTTPS en WordPress:** [`memory/ops/https-wordpress-managed-checklist-2026.md`](./https-wordpress-managed-checklist-2026.md) — mixed content, URL de WordPress, búsqueda/reemplazo en BD.
- **Script de verificación:** [`scripts/check_dns_https_gano.py`](../../scripts/check_dns_https_gano.py) — comprobación DNS + HTTPS desde la línea de comandos.
- **Registros DNS esperados:** [`memory/ops/dns-expected-records-template-2026.md`](./dns-expected-records-template-2026.md) — tabla con placeholders para rellenar offline.
- **MU Plugin seguridad:** [`wp-content/mu-plugins/gano-security.php`](../../wp-content/mu-plugins/gano-security.php) — cabeceras de seguridad, incluida HSTS.

---

*Última revisión: 2026-04 | Autor: copilot (issue dns-canonical-www-hsts)*
