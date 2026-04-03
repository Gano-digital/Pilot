# Runbook DNS + HTTPS: gano.digital en GoDaddy (Managed WP / dominio)

> **Alcance:** Documentación operativa. No contiene contraseñas, tokens ni IPs de panel.
> Los valores entre `[CORCHETES]` son placeholders que Diego completa desde GoDaddy / hosting.
> Última revisión: 2026-04-03

---

## 1. Síntomas comunes

| Síntoma | Causa probable |
|---------|----------------|
| `ERR_NAME_NOT_RESOLVED` | Registro A/CNAME ausente o mal configurado en el registrador del dominio |
| `NET::ERR_CERT_AUTHORITY_INVALID` o candado rojo | Certificado SSL expirado, no emitido o nombre de dominio no coincide |
| El sitio carga en `http://` pero no en `https://` | Certificado no aprovisionado en el hosting o redirección forzada a HTTP |
| `www.gano.digital` no resuelve pero el apex sí (o viceversa) | Falta registro CNAME/A para el subdominio `www`, o cobertura SSL sin wildcard |
| Contenido desactualizado / CDN devuelve versión antigua | TTL alto de caché de CDN o DNS; propagación incompleta |
| Sitio carga con error 500/503 tras cambio de DNS | El hosting aún no reconoce el dominio o WordPress tiene `siteurl`/`home` con la URL anterior |

---

## 2. Diferencia clave: DNS del dominio vs SSL en el hosting

```
┌──────────────────────────────────────────────────────────┐
│  REGISTRADOR DEL DOMINIO (GoDaddy — gestión de dominio)  │
│                                                          │
│  Registros DNS:                                          │
│    A       gano.digital     →  [IP_HOSTING]              │
│    CNAME   www              →  gano.digital              │
│                                                          │
│  Controlan: a qué servidor llegan las peticiones HTTP/S  │
└──────────────────────┬───────────────────────────────────┘
                       │ (tráfico llega al servidor)
                       ▼
┌──────────────────────────────────────────────────────────┐
│  HOSTING — Managed WordPress (GoDaddy)                   │
│                                                          │
│  Panel Managed WP → SSL/HTTPS:                          │
│    • Certificado: Let's Encrypt o GoDaddy SSL            │
│    • Debe cubrir: gano.digital  y  www.gano.digital      │
│    • Redirección HTTP → HTTPS activada                   │
│                                                          │
│  WordPress:                                              │
│    • wp-config.php / Ajustes Generales:                  │
│      siteurl = https://gano.digital                      │
│      home    = https://gano.digital                      │
└──────────────────────────────────────────────────────────┘
```

**Regla de oro:** el DNS solo enruta el tráfico; el certificado SSL existe en el servidor de hosting, no en el registrador. Cambiar DNS sin tener el certificado listo en el hosting dejará el sitio sin HTTPS. Cambiar el certificado sin los DNS correctos da error de resolución.

---

## 3. Qué verificar en orden

### Paso 1 — Verificar registros DNS en el registrador

1. Ir a **GoDaddy → Mis Productos → Dominios → `gano.digital` → Gestionar DNS**.
2. Confirmar que existen los registros:

   | Tipo | Nombre | Valor | TTL sugerido |
   |------|--------|-------|--------------|
   | `A` | `@` (apex) | `[IP_HOSTING]` | 600 s |
   | `CNAME` | `www` | `gano.digital` | 3600 s |

   > ⚠️ Si el hosting GoDaddy Managed WP usa un nombre de host en lugar de IP, puede requerirse CNAME en el apex (verificar documentación del plan). Algunos planes usan `[HOSTNAME].myftpupload.com` como alias.

3. Verificar que no existan registros duplicados o conflictivos (p. ej., dos `A` para el apex con IPs distintas).

### Paso 2 — Verificar panel de hosting Managed WordPress

1. Ir a **GoDaddy → Mis Productos → WordPress → `gano.digital` → Gestionar**.
2. En la sección **SSL/HTTPS**:
   - Estado del certificado: **Activo** (no "Pendiente" ni "Expirado").
   - Dominios cubiertos: incluye `gano.digital` y `www.gano.digital`.
3. En la sección **CDN**: confirmar estado **Activo** si está habilitado.
4. Verificar que el dominio primario del hosting sea `gano.digital` (no la URL temporal `*.myftpupload.com`).

### Paso 3 — Verificar WordPress

1. **wp-admin → Ajustes → Generales**:
   - "Dirección de WordPress (URL)": `https://gano.digital`
   - "Dirección del sitio (URL)": `https://gano.digital`
2. Verificar que no haya redirección conflictiva en `.htaccess` (buscar `RewriteRule` con HTTP sin redirect a HTTPS).
3. Si hay mixed content (console del navegador muestra recursos `http://`): usar plugin **Better Search Replace** para migrar URLs en BD de `http://gano.digital` a `https://gano.digital`.

### Paso 4 — Verificar propagación desde la máquina local

Ver tabla de herramientas en la [sección 5](#5-herramientas-de-verificación-local).

---

## 4. TTL y propagación

| Concepto | Detalle |
|----------|---------|
| **TTL del registro DNS** | Tiempo (segundos) que los resolvers cachean la respuesta. Valor típico: 600–3600 s. Bajar a 300 s antes de un cambio planificado; restaurar a 3600 s tras confirmar que funciona. |
| **Tiempo de propagación global** | Con TTL 600 s: ~10–30 min. Con TTL 3600 s: hasta 1–2 h. En algunos resolvers de ISP puede tardar hasta 48 h. |
| **Verificación temprana** | Usar DNS de Google (`8.8.8.8`) o Cloudflare (`1.1.1.1`) para probar antes de que el ISP local actualice su caché. |
| **CDN GoDaddy** | Si el CDN está activo, puede requerir vaciado de caché desde el panel Managed WP tras cambios de dominio o SSL. |

---

## 5. Herramientas de verificación local

| Herramienta | Comando o URL | Qué esperar |
|-------------|---------------|-------------|
| **dig** (Linux/Mac) | `dig +short gano.digital A` | La IP del hosting (`[IP_HOSTING]`) |
| **dig www** | `dig +short www.gano.digital CNAME` | `gano.digital.` (con punto al final) o la IP si resuelve directo |
| **dig usando Google DNS** | `dig +short @8.8.8.8 gano.digital A` | La IP del hosting (propagación externa) |
| **nslookup** (Windows/Linux) | `nslookup gano.digital 8.8.8.8` | "Address: [IP_HOSTING]" |
| **curl TLS** | `curl -Iv https://gano.digital 2>&1 \| head -40` | `subject: CN=gano.digital`, `200 OK` o redirección 301/302 |
| **curl con follow** | `curl -L -o /dev/null -w "%{http_code} %{url_effective}\n" https://gano.digital` | `200 https://gano.digital/` |
| **check_dns_https_gano.py** (este repo) | `python scripts/check_dns_https_gano.py` | `[DNS] gano.digital: A -> [IP_HOSTING]` y `[HTTPS] gano.digital: OK HTTP 200` |
| **SSL Labs** | [https://www.ssllabs.com/ssltest/analyze.html?d=gano.digital](https://www.ssllabs.com/ssltest/analyze.html?d=gano.digital) | Grado A o A+; sin errores de cadena de certificados |
| **DNS Checker** | [https://dnschecker.org/#A/gano.digital](https://dnschecker.org/#A/gano.digital) | Todos los nodos muestran la misma IP |
| **WhatsMyDNS** | [https://www.whatsmydns.net/#A/gano.digital](https://www.whatsmydns.net/#A/gano.digital) | Mayoría de nodos con IP correcta; algunos pueden tardar |
| **GoDaddy Docs — Gestionar DNS** | [https://es.godaddy.com/help/administrar-registros-dns-680](https://es.godaddy.com/help/administrar-registros-dns-680) | Guía oficial; sin credenciales |
| **GoDaddy Docs — SSL Managed WP** | [https://es.godaddy.com/help/agregar-un-certificado-ssl-a-mi-sitio-de-wordpress-administrado-4801](https://es.godaddy.com/help/agregar-un-certificado-ssl-a-mi-sitio-de-wordpress-administrado-4801) | Guía oficial para activar/renovar SSL en Managed WP |

---

## 6. Rollback

Si un cambio de DNS rompe el sitio:

1. **Restaurar registros anteriores** en GoDaddy → Gestionar DNS.
   - Si no los tienes anotados, ir a **Historial de cambios DNS** (GoDaddy guarda los últimos cambios).
2. Esperar propagación (ver sección 4).
3. Si se cambió el dominio primario en el hosting y el sitio quedó en la URL temporal (`*.myftpupload.com`):
   - Verificar que WordPress en `wp-admin → Ajustes → Generales` sigue apuntando al dominio correcto.
   - Si se corrompió: editar `wp-config.php` temporalmente con `define('WP_HOME','https://gano.digital')` y `define('WP_SITEURL','https://gano.digital')` hasta restaurar la BD.
4. Si el certificado SSL quedó expirado o en estado "Pendiente" tras el rollback: solicitar re-emisión desde el panel Managed WP → SSL.

---

## 7. Escalación a soporte GoDaddy

Escalar si no se resuelve en ~2 h tras verificar todos los pasos anteriores:

| Canal | Cuándo usar |
|-------|-------------|
| **Chat en vivo** — [https://es.godaddy.com/help](https://es.godaddy.com/help) | Primera opción; tiempo de espera bajo; útil para validar registros DNS en el panel |
| **Teléfono soporte GoDaddy Colombia** | Fallos críticos donde el chat no avanza; buscar número en [https://es.godaddy.com/contact](https://es.godaddy.com/contact) |
| **Ticket / Soporte Premium** | Si el hosting tiene soporte premium activo; permite seguimiento escrito |

**Información a tener lista antes de contactar soporte:**

- Nombre del dominio: `gano.digital`
- ID de cliente GoDaddy (en el perfil de la cuenta)
- Producto afectado: "Managed WordPress" + nombre del plan
- Resultado de `python scripts/check_dns_https_gano.py` (sin IPs privadas si no es necesario)
- Captura de pantalla del error (sin tokens ni contraseñas visibles)
- Hora y zona horaria del último cambio realizado

---

## 8. Referencias cruzadas

- `scripts/check_dns_https_gano.py` — Script de verificación automatizada DNS + HTTPS (stdlib Python, sin credenciales)
- `memory/ops/wordfence-2fa-checklist.md` — Checklist de seguridad relacionado
- `memory/ops/gano-seo-rankmath-gsc-checklist.md` — Sección D: "Si HTTPS aún falla"
- `TASKS.md` — Estado de fases y tareas activas
