# DevOps Engineer Guide — Gano Digital

**Para:** Jeisson Sachica (DevOps Engineer)
**Última actualización:** 2026-04-10
**Infraestructura:** GoDaddy Managed WordPress Deluxe + GitHub Actions + Webhook Deploy

---

## 🚀 Quick Start

### Acceso a WordPress (wp-admin)
```
URL: https://gano.digital/wp-admin
Usuario: jeisson
Contraseña: (Diego te proporciona)
```

### Acceso SSH al Servidor
```bash
ssh -i ~/.ssh/id_rsa_deploy f1rml03th382@72.167.102.145
# O vía GitHub Secrets: SSH (privada), SERVER_HOST, SERVER_USER
```

### Directorio Web
```
/home/f1rml03th382/public_html/gano.digital
```

---

## 📋 Responsabilidades Core

### 1. **Infraestructura & Backups**
- ✅ Verificar backups automáticos en cPanel (mínimo semanal)
- ✅ SSL/HTTPS Force HTTPS ON en `gano.bio` y `gano.digital`
- ✅ AutoSSL renovación (cPanel → SSL/TLS Status)
- ✅ Espacios en disco (20GB NVMe límite GoDaddy)
- ✅ RAM usage (~512 MB, revisar si hay picos)

### 2. **Deploy & CI/CD**
- ✅ Mantener workflows funcionando (04-Deploy, 05-Verify, 12-Remove-plugins)
- ✅ Webhook `receive.php` operacional (recibe ZIP, valida HMAC, despliega)
- ✅ GitHub Actions logs sin errores
- ✅ Sincronización repo → servidor (después de cada merge a main)

### 3. **Bases de Datos**
- ✅ MySQL: gano_staging (BD de producción, naming confusion)
- ✅ Backups exportados regularmente (phpMyAdmin → Export)
- ✅ Optimización: `wp db optimize --allow-root`
- ✅ Migraciones: search-replace si cambian dominios

### 4. **Seguridad**
- ✅ Wordfence activo (Dashboard → Wordfence → Status)
- ✅ CSP headers implementados (gano-security.php)
- ✅ Rate limiting REST API (429 en functions.php)
- ✅ SSH key rotación cada 90 días
- ✅ Secrets GitHub seguros (nunca en repo público)

### 5. **Monitoreo & Logs**
- ✅ error.log revisar semanalmente
- ✅ HTTP 200 en gano.digital (uptime monitoring)
- ✅ Wordfence alerts (firewall, malware scans)
- ✅ PHP errors (wp-content/debug.log si WP_DEBUG ON)

---

## 🔧 Herramientas & Accesos

### SSH + WP-CLI
```bash
ssh -i ~/.ssh/id_rsa_deploy f1rml03th382@72.167.102.145
cd ~/public_html/gano.digital

# WordPress
wp user list --allow-root
wp plugin list --allow-root
wp db optimize --allow-root

# MySQL
mysql -u gano_dev -pGanoDev2024\!Secure gano_staging -e "SHOW TABLES;"
```

### cPanel
- URL: https://gano.digital:2083 (o panel.godaddy.com)
- Usuario: f1rml03th382
- Tareas: SSL, Backups, Dominios, File Manager, phpMyAdmin

### GitHub Actions
- Repo: https://github.com/Gano-digital/Pilot
- Workflows: `.github/workflows/*.yml`
- Secrets: configurados (SSH, DEPLOY_PATH, GANO_DEPLOY_HOOK_*)
- Dashboard: Actions → ver logs en tiempo real

### Webhook Deploy
- Endpoint: `https://gano.digital/wp-content/gano-deploy/receive.php`
- Método: POST + ZIP + HMAC-SHA256
- Almacenable en GitHub Secrets:
  - `GANO_DEPLOY_HOOK_URL`
  - `GANO_DEPLOY_HOOK_SECRET`

---

## 📝 Tareas Comunes

### Revisar Logs (Weekly)
```bash
ssh -i ~/.ssh/id_rsa_deploy f1rml03th382@72.167.102.145
tail -50 ~/public_html/gano.digital/error.log
# Busca: PHP errors, DB connection issues, permission denied
```

### Backup Manual (si falla automático)
```bash
# Vía cPanel: Backup → Full Backup Backup (Download)
# O vía SSH:
mysqldump -u gano_dev -pGanoDev2024\!Secure gano_staging > backup-gano-$(date +%Y-%m-%d).sql
```

### Optimizar BD
```bash
wp db optimize --allow-root
# O vía WP-CLI dashboard
wp db check --allow-root --repair
```

### Test Webhook (manual)
```bash
# Generar HMAC signature
PAYLOAD=$(cat deploy.zip)
SIGNATURE="sha256=$(echo -n "$PAYLOAD" | openssl dgst -sha256 -hmac "SECRET_AQUI" -binary | xxd -p)"

# POST a webhook
curl -X POST \
  -H "X-Gano-Signature: $SIGNATURE" \
  -H "Content-Type: application/zip" \
  --data-binary @deploy.zip \
  https://gano.digital/wp-content/gano-deploy/receive.php
```

### SSH Key Rotation (cada 90 días)
```bash
# En local
ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa_deploy_new -N ""

# Copiar nueva public key al servidor
cat ~/.ssh/id_rsa_deploy_new.pub | ssh -i ~/.ssh/id_rsa_deploy f1rml03th382@72.167.102.145 "cat >> ~/.ssh/authorized_keys"

# Actualizar GitHub Secrets con nueva private key
gh secret set SSH --body "$(cat ~/.ssh/id_rsa_deploy_new)"

# Verificar y luego eliminar old key
rm ~/.ssh/id_rsa_deploy_*
```

---

## 🚨 Troubleshooting

### HTTP 503 / 500 Errors
```bash
# Revisar error.log
tail -100 ~/public_html/gano.digital/error.log | grep -i "error\|fatal"

# Posibles causas:
# - RAM limit (512 MB)
# - Plugin broken
# - DB connection
# - PHP version issue
```

### Webhook no recibe POST
```bash
# 1. Verificar receive.php existe
[ -f ~/public_html/gano.digital/wp-content/gano-deploy/receive.php ] && echo "OK" || echo "NOT FOUND"

# 2. Revisar permisos
ls -la ~/public_html/gano.digital/wp-content/gano-deploy/

# 3. Test manual (ver arriba)
# 4. Revisar logs PHP
grep "gano-deploy" ~/public_html/gano.digital/error.log
```

### BD Connection Failed
```bash
# Verificar credenciales en wp-config.php
grep "DB_" ~/public_html/gano.digital/wp-config.php

# Test manual
mysql -u gano_dev -pGanoDev2024\!Secure gano_staging -e "SELECT 1;"

# Si falla: ticket GoDaddy (posible reset contraseña)
```

### Force HTTPS no funciona
```bash
# Revisar cPanel:
# Settings → Force HTTPS Redirect (debe estar ON en ambos dominios)

# Revisar .htaccess
head -20 ~/public_html/gano.digital/.htaccess

# Si necesita editar: crear en wp-admin o vía SSH (con backup)
```

---

## 📊 Checklist Semanal

- [ ] Revisar error.log (últimas 100 líneas)
- [ ] Verificar Wordfence alerts en wp-admin
- [ ] Comprobar backup automático ejecutado
- [ ] Revisar GitHub Actions últimos deploys
- [ ] Test webhook POST manual
- [ ] Uptime check (https://gano.digital responde HTTP 200)
- [ ] RAM/CPU usage (cPanel → Statistics)
- [ ] SSL certificados válidos (cPanel → SSL/TLS Status)

---

## 📌 Checklist Mensual

- [ ] Optimizar base de datos: `wp db optimize --allow-root`
- [ ] Revisar Wordfence config-synced.php (debe ser reciente)
- [ ] Actualizar documentación si hubo cambios
- [ ] Revisar GitHub Secrets (no expired)
- [ ] Backup manual (si auto falló algún día)
- [ ] Review WordPress/plugins updates pending
- [ ] Disk space usage (no llegar a 100%)

---

## 🔐 Secretos Críticos (GitHub)

| Secret | Valor | Uso |
|--------|-------|-----|
| `SSH` | Private RSA key | Deploy workflow SSH steps |
| `SERVER_HOST` | 72.167.102.145 | SSH conexión |
| `SERVER_USER` | f1rml03th382 | SSH usuario |
| `DEPLOY_PATH` | /home/.../gano.digital | Rsync destination |
| `GANO_DEPLOY_HOOK_URL` | https://gano.digital/wp-content/gano-deploy/receive.php | Webhook endpoint |
| `GANO_DEPLOY_HOOK_SECRET` | (64 chars) | HMAC firma |

**NUNCA** compartir en público. Rotar `SSH` cada 90 días.

---

## 🔗 Recursos DevOps

| Recurso | URL |
|---------|-----|
| **GitHub Repo** | https://github.com/Gano-digital/Pilot |
| **WP-CLI Docs** | https://developer.wordpress.org/cli/ |
| **WordPress Hosting** | GoDaddy Managed WordPress Deluxe |
| **cPanel Docs** | https://cpanel.net/tutorials/ |
| **Wordfence Docs** | https://www.wordfence.com/docs/ |
| **SSH Troubleshooting** | memory/ops/github-actions-ssh-secret-troubleshooting.md |
| **Hosting Report** | memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md |
| **Contact** | Diego: diego_r_95@hotmail.com |

---

## 📞 Escalación

1. **Error en deploy:** Revisar GitHub Actions logs → SSH troubleshoot → contact Diego
2. **BD issue:** Check conexión MySQL → backup → contact GoDaddy si necesario
3. **SSL problem:** Force HTTPS en cPanel → AutoSSL refresh → contact GoDaddy
4. **Security alert:** Wordfence → analyze threat → isolate/remediate → notify Diego
5. **Ambiguo:** Ask Diego primero, no assumir

---

**Bienvenido al equipo, Jeisson! ⚙️**

Cualquier duda: Diego → diego_r_95@hotmail.com o GitHub Issues
