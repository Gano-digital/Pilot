---
name: FASE 1 — Instrucciones Operativas Deploy
description: Pasos concretos A1–A4, checklist, validación post-deploy
type: project
---

# FASE 1 — SINCRONIZACIÓN CÓDIGO ↔ SERVIDOR

**Objetivo:** Llevar Fases 1–3 (seguridad + hardening + SEO) del código a gano.digital (producción).

**Owner:** Diego (accionista) + Claude (asistencia técnica)  
**Timeline estimado:** 1–3 horas (mayormente automático)

---

## TAREAS A1–A4 (ORDEN SECUENCIAL)

### **TAREA A1: Configurar Webhook Secrets en GitHub**

**Ubicación:** GitHub → `Gano-digital/Pilot` → Settings → **Secrets and variables** → **Actions**

**Qué agregar (2 nuevos secrets):**

| Nombre Secret | Valor | Notas |
|---|---|---|
| `GANO_DEPLOY_HOOK_URL` | `https://gano.digital/wp-content/gano-deploy/receive.php` | URL exacta, sin trailing slash |
| `GANO_DEPLOY_HOOK_SECRET` | `<tu-secret-aleatorio-64-chars>` | Mismo que en wp-config.php (ver TAREA A1b) |

**TAREA A1b: Generar y agregar secret en wp-config.php del servidor**

**En tu PC (PowerShell o Git Bash):**
```powershell
# Generar 64 caracteres hexadecimales
openssl rand -hex 32
# Ejemplo output: a7f3c9e2d1b4f8a6c5e9d2b1f4a8e7c3d6b9f2a5e8c1d4b7a0f3e6c9d2b5a8
```

**En servidor (via SSH o cPanel File Manager):**
Abrir `/public_html/wp-config.php` y agregar (antes de la línea "That's all, stop editing!"):

```php
// ── Gano Deploy Webhook ──────────────────────────────────────
define('GANO_DEPLOY_HOOK_SECRET', 'a7f3c9e2d1b4f8a6c5e9d2b1f4a8e7c3d6b9f2a5e8c1d4b7a0f3e6c9d2b5a8');
```

**✅ Checklist A1:**
- [ ] Secret `GANO_DEPLOY_HOOK_SECRET` generado (64 caracteres)
- [ ] Agregado a GitHub Secrets (exactamente el mismo valor)
- [ ] Agregado a `wp-config.php` del servidor (exactamente el mismo valor)
- [ ] Ambos valores son idénticos (importante para HMAC validation)

---

### **TAREA A2: Ejecutar Deploy — Workflow 04**

**Opción A (Manual via GitHub UI — recomendado):**

1. Ir a: GitHub → `Gano-digital/Pilot` → **Actions**
2. Buscar workflow: **"04 · Deploy · Producción (webhook HTTPS)"**
3. Click en workflow → **"Run workflow"** dropdown
4. Elegir **Branch:** `main`
5. Click **"Run workflow"** (verde)
6. Monitorear logs en tiempo real:
   - Step: *Security Scan* (verificar sin credenciales)
   - Step: *Prepare deployment ZIP* (empaqueta archivos)
   - Step: *Sign and send webhook* (envía a endpoint)
   - Step: *Verify Deployment* (comprueba que sitio responde)

**Opción B (Automático via Push):**
- Hacer un commit trivial a `main` (ej: actualizar fecha en TASKS.md)
- Workflow 04 se dispara automáticamente

**Logs esperados (success):**
```
✅ Sin credenciales expuestas (paths Gano)
✅ Sitio respondiendo: HTTP 200
✅ Webhook accepted
```

**Si falla, revisar:**
- HTTP 503: `GANO_DEPLOY_HOOK_SECRET` no en `wp-config.php`
- HTTP 403: Firma HMAC no coincide (secrets no idénticos)
- HTTP 000: Servidor no responde (gano.digital down o timeout)

**✅ Checklist A2:**
- [ ] Workflow 04 ejecutado (manual o push)
- [ ] Logs verdes (seguridad, ZIP, webhook, verify sitio)
- [ ] gano.digital responde sin errores PHP 500

---

### **TAREA A3: Eliminar `wp-file-manager` del servidor**

**Riesgo crítico:** CVE-2020-25213 (CVSS 10.0 — remote code execution sin autenticación)

**Opción A (via cPanel File Manager):**
1. Abrir cPanel → **File Manager**
2. Navegar a: `public_html` → `wp-content` → `plugins`
3. Click derecho en carpeta `wp-file-manager` → **Delete**
4. Confirmar eliminación
5. Vaciar basura si aplica

**Opción B (via SSH):**
```bash
ssh usuario@gano.digital
rm -rf ~/public_html/wp-content/plugins/wp-file-manager/
```

**Opción C (via Workflow 12 — SSH automático):**
- Requiere que SSH esté configurado en GitHub Secrets (alternativa a webhook)
- GitHub → Actions → "12 · Ops · Eliminar wp-file-manager" → Run workflow
- (Solo si prefieres automatizar; cPanel File Manager es más seguro)

**Post-eliminación:**
- Verificar en wp-admin → Plugins → verificar que `wp-file-manager` NO aparece
- Limpiar cualquier reference en `wp-config.php` (no debería haber)

**✅ Checklist A3:**
- [ ] Carpeta `wp-file-manager/` eliminada de servidor
- [ ] wp-admin muestra 0 plugins inactivos con ese nombre
- [ ] No hay archivos `wp-file-manager*` sueltos en `wp-content/`

---

### **TAREA A4: Verificar Checksums — Parches Aplicados**

**Workflow 05 — Verificar parches:**

1. GitHub → Actions → **"05 · Ops · Verificar parches en servidor"**
2. Run workflow → Branch: `main`
3. Monitorear output:
   - Verifica que `gano-security.php` está en `/mu-plugins/`
   - Verifica checksums de MU plugins (si disponibles)
   - Verifica que `wp-config.php` tiene `WP_DEBUG = false`

**Validación manual (SSH):**
```bash
ssh usuario@gano.digital
# Verificar gano-security.php existe
ls -la ~/public_html/wp-content/mu-plugins/gano-security.php

# Verificar gano-seo.php existe
ls -la ~/public_html/wp-content/mu-plugins/gano-seo.php

# Verificar WP_DEBUG en config
grep "WP_DEBUG" ~/public_html/wp-config.php | head -3
# Debería mostrar: define( 'WP_DEBUG', false ); ← false, no true
```

**Post-verificación:**
- [ ] Ambos MU plugins están presentes
- [ ] `WP_DEBUG` está `false`
- [ ] `DISALLOW_FILE_EDIT` está `true`

**✅ Checklist A4:**
- [ ] Workflow 05 ejecutado (o verificación manual)
- [ ] Parches confirmados en servidor

---

## VERIFICACIÓN POST-FASE 1

**Acceder a gano.digital y validar:**

| Validación | Esperado | Cómo revisar |
|---|---|---|
| **Sitio carga (no 500)** | HTTP 200 | Abrir https://gano.digital en navegador |
| **No hay WP warnings** | Log limpio | wp-admin → Tools → Site Health → verifica "Good" |
| **CSP headers presente** | X-Content-Type-Options: nosniff | DevTools → Network → Headers de respuesta |
| **`wp-file-manager` ausente** | Plugin no aparece | wp-admin → Plugins → buscar "file-manager" |
| **MU plugins activos** | 4 MU plugins listados | wp-admin → Plugins → filter "mu-plugins" |

**Comando de validación rápida (SSH):**
```bash
# Verificar que sitio responde
curl -I https://gano.digital

# Verificar headers seguridad
curl -I https://gano.digital | grep -E "X-Content-Type|X-Frame-Options|Strict-Transport"

# Verificar que wp-file-manager no existe
ls ~/public_html/wp-content/plugins/wp-file-manager 2>&1 | grep -i "no such"
```

---

## SI ALGO FALLA

### Error: `HTTP 503` en Webhook
**Causa:** `GANO_DEPLOY_HOOK_SECRET` no en `wp-config.php`  
**Solución:** Agregar línea a `wp-config.php` y reintentar workflow 04

### Error: `HTTP 403` en Webhook
**Causa:** Secrets `GANO_DEPLOY_HOOK_SECRET` no coinciden (GitHub ≠ servidor)  
**Solución:** Copiar secret exacto sin espacios, coincidir ambos lados, reintentar

### Error: `HTTP 000` (timeout)
**Causa:** Servidor no responde o DNS resuelve incorrectamente  
**Solución:** Verificar que gano.digital está UP (ping, curl manual)

### Detectas plugin `wp-file-manager` aún presente
**Causa:** Eliminación incompleta o cache  
**Solución:** 
- Eliminar manualmente via cPanel
- Limpiar cache WordPress (si hay plugin de cache)
- Purgar CDN si existe

---

## TIMELINE ESTIMADA

| Tarea | Duración | Bloqueador |
|----|----|---|
| **A1:** Secrets GitHub + wp-config | 10 min | No |
| **A2:** Deploy workflow 04 | 3–5 min | Depende de A1 |
| **A3:** Eliminar wp-file-manager | 5 min | No (parallelizable con A2) |
| **A4:** Verify patches (workflow 05) | 2 min | Depende de A2 |
| **Validación visual** | 10 min | Depende de A2 |

**TOTAL:** ~30 min (si todo verde)

---

## PRÓXIMOS PASOS (POST FASE 1)

Una vez A1–A4 completados:
1. ✅ Código en producción (theme, plugins, MU-plugins, .htaccess)
2. ✅ Seguridad aplicada (gano-security.php, gano-seo.php activos)
3. ✅ CVE crítica eliminada (wp-file-manager fuera)

**Proceder a FASE 2:** Elementor + copy (tareas B1–C7, Diego en wp-admin)

---

**Documento:** 2026-04-11 | Owner: Diego | Asistencia: Claude
