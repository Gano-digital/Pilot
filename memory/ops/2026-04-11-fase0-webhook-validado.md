---
name: FASE 0 — Webhook Deploy Validado
description: Endpoint receive.php funcional, secrets listos, bloqueador SSH resuelto
type: project
---

# FASE 0 — WEBHOOK DEPLOY HTTPSVALIDADO ✅

**Estado:** RESUELTO — Endpoint webhook implementado, seguro, listo para deploy.

**Cambio importante:** El plan inicial mencionaba "bloqueador SSH" pero **workflow 04 ya fue migrado a webhook HTTPS** en PR #173. **No hay bloqueador.**

---

## VALIDACIÓN: ENDPOINT WEBHOOK

**Archivo:** `wp-content/gano-deploy/receive.php` (6.5 KB)

**Protocolo:**
- Método: `POST`
- Content-Type: `application/zip`
- Header: `X-Gano-Signature: sha256=<HMAC-SHA256>`

**Validaciones en endpoint:**
1. ✅ Requiere secret desde `wp-config.php` (`GANO_DEPLOY_HOOK_SECRET`)
2. ✅ Valida firma HMAC-SHA256 (timing-safe con `hash_equals`)
3. ✅ Sanitiza rutas ZIP (previene path traversal)
4. ✅ Allowlist de paths permitidos:
   - `wp-content/themes/gano-child/`
   - `wp-content/mu-plugins/gano-*`
   - `wp-content/plugins/gano-*`
   - `.htaccess`
5. ✅ Logs de deploy (deployed/skipped/errors count)

**Seguridad:** 🟢 EXCELENTE — Firma HMAC, sanitización de paths, allowlist, timing-safe validation.

---

## QUÉ HACER ANTES DE FASE 1

### En el servidor (gano.digital):

```php
// Agregar en wp-config.php (encima de "That's all, stop editing!")

define('GANO_DEPLOY_HOOK_SECRET', 'tu-secret-aleatorio-de-64-caracteres-sin-espacios');
```

**Generar secret (en tu PC, PowerShell):**
```powershell
-join ([char[]]@(48..57) + @(65..90) + @(97..122) | Get-Random -Count 64)
```

O usar OpenSSL:
```bash
openssl rand -hex 32  # Genera 64 caracteres hexadecimales
```

### En GitHub (`Gano-digital/Pilot` → Settings → Secrets and variables → Actions):

```
GANO_DEPLOY_HOOK_URL      = https://gano.digital/wp-content/gano-deploy/receive.php
GANO_DEPLOY_HOOK_SECRET   = <mismo-secret-que-en-wp-config>
```

---

## VERIFICACIÓN POST-SETUP

1. **Servidor:** Agregar `GANO_DEPLOY_HOOK_SECRET` a `wp-config.php`
2. **GitHub:** Agregar secrets (URL + SECRET)
3. **Test manual:**
   ```bash
   curl -X POST \
     -H "X-Gano-Signature: sha256=$(echo -n 'test' | openssl dgst -sha256 -hmac 'tu-secret' -hex | cut -d ' ' -f2)" \
     -H "Content-Type: application/zip" \
     --data-binary @deploy.zip \
     https://gano.digital/wp-content/gano-deploy/receive.php
   ```
   Debe retornar HTTP 200 (si ZIP es válido).

4. **GitHub workflow:** Ejecutar manualmente o hacer push a `main` → triggea workflow 04 automáticamente

---

## TIMELINE

- **Setup servidor:** 5 min (agregar línea a wp-config)
- **Setup GitHub:** 2 min (agregar 2 secrets)
- **Test webhook:** 5 min (curl manual)
- **Deploy FASE 1:** Automático vía workflow 04 (push o manual trigger)

**TOTAL FASE 0:** ~15 minutos

---

## PRÓXIMOS PASOS

Una vez setup completado:
1. Workflow 04 se ejecuta automáticamente en push a `main`
2. O ejecutar manualmente: GitHub → Actions → "04 · Deploy · Producción" → Run workflow
3. Monitorea logs del job (debería mostrar ✅ Webhook accepted)
4. Verifica gano.digital (Home debería cargar sin errores PHP 500)

**BLOQUEADOR RESUELTO.** Proceder a FASE 1.
