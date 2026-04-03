# Checklist: HTTPS en WordPress administrado (Gano Digital)

**Versión:** 2026-04 | **Aplica a:** Managed WordPress GoDaddy (gano.digital)

> Este checklist cubre los pasos necesarios tras confirmar que el DNS apunta
> correctamente para que el sitio funcione de forma íntegra en HTTPS —sin mixed
> content, sin redirecciones rotas y con las URLs de WordPress correctamente
> configuradas.

---

## ⚠️ **NO HACER EN PRODUCCIÓN SIN BACKUP**

> **Antes de cualquier paso que modifique la base de datos o los archivos del
> servidor, realiza SIEMPRE un backup completo (BD + archivos) y verifica que
> el backup se puede restaurar. En GoDaddy Managed WordPress: Panel → Backups →
> Crear backup manual.**
>
> - **Nunca ejecutar Search & Replace en BD en producción sin un snapshot previo.**
> - **Nunca modificar wp-config.php en producción sin una copia de seguridad del archivo.**
> - **Nunca desactivar un plugin SSL sin tener acceso alternativo confirmado (no romper HTTPS).**
> - **Antes de cambiar las URLs en Ajustes → Generales, confirmar que el certificado SSL está activo.**

---

## 1. Verificar certificado SSL activo

1. **GoDaddy Panel → Managed WordPress → SSL:** confirmar que el certificado
   está instalado y vigente para `gano.digital` y `www.gano.digital`.
2. Visitar `https://gano.digital` desde el navegador — el candado debe aparecer
   sin advertencias.
3. Herramienta externa (opcional): <https://www.ssllabs.com/ssltest/> — rating
   mínimo esperado: **A**.

---

## 2. URLs de WordPress (Ajustes → Generales)

1. **wp-admin → Ajustes → Generales:**
   - **Dirección de WordPress (URL):** `https://gano.digital`
   - **Dirección del sitio (URL):** `https://gano.digital`
2. Guardar cambios. WordPress cerrará la sesión; volver a ingresar por
   `https://gano.digital/wp-admin`.
3. Verificar que ambas URLs muestran `https://` y **no** `http://`.

> **Nota:** si el panel no guarda o se produce un bucle de redirección,
> verificar primero que el certificado SSL está correctamente instalado
> (paso 1) antes de continuar.

---

## 3. Patrones en `wp-config.php` (sin credenciales)

Los siguientes fragmentos son patrones de referencia para forzar HTTPS a nivel
de configuración. **No pegarlos con credenciales reales ni hostnames
de servidor.**

```php
// Forzar SSL en el área de administración
define( 'FORCE_SSL_ADMIN', true );

// Si el sitio está detrás de un proxy inverso / CDN (ej. GoDaddy CDN)
// que termina SSL antes de llegar a PHP, descomentar según corresponda:
// $_SERVER['HTTPS'] = 'on';

// URLs de WordPress (también configurables desde wp-admin → Ajustes → Generales)
// define( 'WP_HOME',    'https://gano.digital' );
// define( 'WP_SITEURL', 'https://gano.digital' );
```

> **Regla de seguridad Gano:** `wp-config.php` **nunca** se sube al repositorio.
> Aplicar estos cambios directamente en el servidor vía SSH/SFTP o usando el
> editor de archivos del panel de GoDaddy.

---

## 4. Plugin SSL — Really Simple SSL (y alternativas)

### 4.1 Really Simple SSL

1. **wp-admin → Plugins → Añadir nuevo:** buscar **Really Simple SSL**.
2. Instalar y activar. El plugin detecta automáticamente el certificado activo.
3. Hacer clic en **"Ir a HTTPS"** cuando lo solicite.
4. Verificar que las URLs de WordPress (paso 2) quedaron en `https://`.
5. Revisar el panel del plugin: **Ajustes → SSL** — todos los ítems en verde.

### 4.2 Wordfence (complementario)

- Wordfence ya está activo en gano.digital (`gano-security.php`).
- En **Wordfence → All Options → General Wordfence Options** activar
  **"Redirect HTTP to HTTPS"** si no está activo por otro mecanismo.

### 4.3 Sin plugin adicional

Si el hosting ya gestiona la redirección HTTP→HTTPS (GoDaddy Managed WP lo
hace a nivel servidor), **no es necesario** instalar Really Simple SSL. Verificar
en el paso 5 que las redirecciones funcionan sin plugins extra.

---

## 5. `.htaccess` / Reglas de hosting

> En **GoDaddy Managed WordPress**, el archivo `.htaccess` está en
> `public_html/.htaccess`. Editar vía SSH/SFTP o panel de archivos.

### Regla canónica HTTPS (añadir si no existe)

```apache
# Redirigir HTTP → HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### Regla www → no-www (o viceversa, según el canonical elegido)

```apache
# Forzar no-www (si el canonical es gano.digital sin www)
RewriteCond %{HTTP_HOST} ^www\.gano\.digital [NC]
RewriteRule ^(.*)$ https://gano.digital/$1 [L,R=301]
```

> **Advertencia:** modificar `.htaccess` incorrectamente puede dejar el sitio
> inaccesible. Hacer backup del archivo antes de editar. Si el sitio queda en
> bucle, restaurar el `.htaccess` original desde el backup.

---

## 6. Mixed Content — detectar y corregir

### 6.1 Detección rápida

- Abrir `https://gano.digital` en Chrome/Edge.
- **Herramientas para desarrolladores (F12) → Consola:** buscar errores
  `Mixed Content` o warnings `net::ERR_CERT_AUTHORITY_INVALID`.
- Extensión recomendada: **"HTTPS Everywhere"** o **"Why No Padlock?"**
  (<https://www.whynopadlock.com/>).

### 6.2 Causas comunes

| Recurso | Causa habitual |
|---------|---------------|
| Imágenes | URL `http://` hardcoded en el contenido de entradas/páginas |
| Scripts / CSS externos | Librerías CDN sin `https://` |
| iframes | Widgets embedidos con URL `http://` |
| URLs en BD de Elementor | Datos serializados con `http://gano.digital` |

### 6.3 Really Simple SSL — auto-fix mixed content

- **Ajustes → SSL → Mixed content fixer:** activar la opción
  **"Enable mixed content fixer"**. El plugin reescribe las URLs en el HTML
  al vuelo (`ob_start` / buffer de salida).
- Esta solución es en memoria, no modifica la base de datos. Es suficiente
  para la mayoría de los casos; si persiste el mixed content, continuar con
  el paso 7 (Search & Replace en BD).

---

## 7. Search & Replace en Base de Datos

> **⚠️ REQUIERE BACKUP PREVIO. Ver sección inicial de advertencias.**
>
> Este paso reemplaza permanentemente URLs `http://` por `https://` en todas
> las tablas de WordPress, incluyendo datos serializados de Elementor y
> WooCommerce.

### 7.1 Plugin recomendado: Better Search Replace

1. **wp-admin → Plugins → Añadir nuevo:** buscar **Better Search Replace**.
2. Instalar y activar (puede desactivarse tras el proceso).
3. **Herramientas → Better Search Replace:**
   - **Buscar:** `http://gano.digital`
   - **Reemplazar:** `https://gano.digital`
   - **Tablas:** seleccionar todas (incluir `wp_postmeta`, `wp_options`,
     `wp_posts`).
   - **Ejecutar como prueba (Dry run):** sí, primero revisar el recuento.
   - **Ejecutar (Run):** confirmar solo tras revisar el dry run.
4. Repetir para `http://www.gano.digital` → `https://www.gano.digital` si
   aplica.
5. Desactivar y eliminar el plugin tras completar el reemplazo.

### 7.2 WP-CLI (alternativa por SSH)

```bash
# Dry run — solo muestra qué cambiaría, NO modifica datos
wp search-replace 'http://gano.digital' 'https://gano.digital' --dry-run

# Reemplazar — ejecutar solo tras confirmar backup
wp search-replace 'http://gano.digital' 'https://gano.digital'
```

> **Nota:** WP-CLI maneja correctamente los datos serializados de PHP.
> No usar `UPDATE` SQL directo para URLs de WordPress: rompe la serialización.

---

## 8. Caché — limpiar después de los cambios

1. **GoDaddy Panel → Managed WordPress → Caché → Vaciar caché.**
2. Si hay plugin de caché activo (ej. WP Rocket, W3 Total Cache):
   **wp-admin → plugin de caché → Vaciar todo**.
3. Recargar el sitio con `Ctrl+Shift+R` (hard reload) y verificar HTTPS.

---

## 9. Verificación final

- [ ] `https://gano.digital` carga sin advertencias de certificado.
- [ ] `http://gano.digital` redirige a `https://gano.digital` (301).
- [ ] Consola del navegador sin errores de mixed content.
- [ ] wp-admin accesible por `https://gano.digital/wp-admin`.
- [ ] WooCommerce checkout en HTTPS (si aplica).
- [ ] Google Search Console → Inspeccionar URL → sin errores HTTPS.
- [ ] Rank Math SEO → sitemap en `https://`.

---

## Referencias

- `TASKS.md` — ítem DNS / HTTPS / Fase infra.
- `memory/ops/wordfence-2fa-checklist.md` — seguridad complementaria.
- `wp-content/mu-plugins/gano-security.php` — MU plugin seguridad Gano
  (CSP + headers; revisar que `Content-Security-Policy` no bloquee recursos HTTPS).
- Plugin de diagnóstico externo: <https://www.ssllabs.com/ssltest/>
- Really Simple SSL docs: <https://really-simple-ssl.com/knowledge-base/>
