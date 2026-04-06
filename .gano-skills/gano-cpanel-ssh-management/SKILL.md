# Skill — cPanel + SSH Server Management para Gano Digital

**Última actualización:** 2026-04-03
**Estado:** 🟡 PARTIALLY OPERATIONAL (SSH keys need sync)
**Especialización:** Configuración servidor GoDaddy, dominios, SSL, deployments, backups

---

## 🎯 Propósito

Automatizar y documentar la administración completa del servidor GoDaddy (72.167.102.145) donde corre gano.digital:

- ✅ Configuración de dominios (Addon Domain, parqueo, redirecciones)
- ✅ Gestión SSL/TLS y certificados
- ✅ Backups automáticos y restauración
- ✅ Monitoreo de recursos (CPU, memoria, almacenamiento)
- ✅ Deployments seguros desde GitHub → servidor
- ✅ WordPress hardening (permisos, .htaccess, caché)
- ✅ Limpieza de temporales y optimización

---

## 🔐 Credenciales (Securizadas en GitHub Secrets)

| Secret Name | Valor | Tipo |
|------------|-------|------|
| `SSH_PRIVATE_KEY` | ~/.ssh/id_rsa (hardened permisos) | SSH Key |
| `SERVER_HOST` | 72.167.102.145 | IP |
| `SERVER_USER` | f1rml03th382 | Usuario cPanel/SSH |
| `DEPLOY_PATH` | /public_html/gano.digital | Ruta WordPress |
| `CPANEL_USER` | [Diego proporciona] | cPanel account |
| `CPANEL_TOKEN` | [Diego proporciona] | cPanel API token |

**Status:** 🟡 SSH keys not yet synced to server. **Action:** Diego to validate SSH access manually.

---

## 🛠️ Workflows Principales

### WF-1: Corrección Addon Domain (gano.digital → /public_html/gano.digital)

**Estado actual:** `gano.digital` apunta a `/public_html` (raíz gano.bio) ❌
**Estado deseado:** `gano.digital` apunta a `/public_html/gano.digital` ✅

**Opción A: vía SSH (Recomendado)**
```bash
ssh f1rml03th382@72.167.102.145 << 'EOF'
  # Verificar estado actual
  grep "gano.digital" /etc/userdomains

  # Editar archivo
  sudo nano /etc/userdomains
  # BUSCAR: gano.digital: f1rml03th382
  # CAMBIAR A: gano.digital: f1rml03th382:/public_html/gano.digital

  # Reconstruir configuración
  /scripts/builddomainconf

  # Reiniciar Apache
  systemctl restart httpd

  # Validar
  curl -I https://gano.digital
EOF
```

**Opción B: vía cPanel API (If documented)**
```bash
# POST a: https://72.167.102.145:2083/json-api/cpanel
# Endpoint: ModifyAddonDomain
# Params: domain=gano.digital, dir=/public_html/gano.digital
```

---

### WF-2: Deployment Safety Check (Pre-deploy)

Antes de cada push a `main`:

```bash
#!/bin/bash
# Script: pre-deploy-validation.sh

SERVER_HOST="72.167.102.145"
SERVER_USER="f1rml03th382"
DEPLOY_PATH="/public_html/gano.digital"

echo "[1] Verificando conectividad SSH..."
ssh -i ~/.ssh/id_rsa "$SERVER_USER@$SERVER_HOST" "echo ✅ SSH OK"

echo "[2] Descargando wp-config.php del servidor..."
scp -i ~/.ssh/id_rsa "$SERVER_USER@$SERVER_HOST:$DEPLOY_PATH/wp-config.php" /tmp/wp-config.server.php

echo "[3] Validando que wp-config.php esté fuera de git..."
if git ls-files | grep -q "wp-config.php"; then
  echo "❌ ERROR: wp-config.php en git! Remover AHORA"
  exit 1
fi

echo "[4] Generando MD5 de archivos locales vs servidor..."
find . -type f -name "*.php" -o -name "*.js" -o -name "*.css" | xargs md5sum > /tmp/local.md5
ssh "$SERVER_USER@$SERVER_HOST" "find $DEPLOY_PATH -type f -name '*.php' -o -name '*.js' -o -name '*.css' | xargs md5sum" > /tmp/server.md5

echo "[5] Alertando cambios no sincronizados..."
diff /tmp/local.md5 /tmp/server.md5 | head -20

echo "✅ Pre-deploy validation OK. Proceed con: git push origin main"
```

**Uso:** Ejecutar antes de cada push:
```bash
bash .gano-skills/gano-cpanel-ssh-management/pre-deploy-validation.sh
```

---

### WF-3: Backup Automático (Semanal)

```bash
#!/bin/bash
# Script: backup-weekly.sh
# Cron: 0 2 * * 0 (Domingos 02:00 UTC)

BACKUP_DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/f1rml03th382/backups"
DEPLOY_PATH="/public_html/gano.digital"

echo "⏳ Iniciando backup $BACKUP_DATE..."

# Backup BD
mysqldump -u gano_user -p$GANO_DB_PASS gano_staging > "$BACKUP_DIR/db_$BACKUP_DATE.sql"
gzip "$BACKUP_DIR/db_$BACKUP_DATE.sql"

# Backup archivos WordPress (excluyendo cache)
tar -czf "$BACKUP_DIR/wp_$BACKUP_DATE.tar.gz" \
  --exclude="$DEPLOY_PATH/wp-content/cache" \
  --exclude="$DEPLOY_PATH/.git" \
  "$DEPLOY_PATH"

# Retener últimos 4 backups, borrar más viejos
ls -1t "$BACKUP_DIR"/db_*.gz | tail -n +5 | xargs rm -f
ls -1t "$BACKUP_DIR"/wp_*.tar.gz | tail -n +5 | xargs rm -f

echo "✅ Backup completo: $BACKUP_DIR/db_$BACKUP_DATE.sql.gz"
echo "✅ Backup completo: $BACKUP_DIR/wp_$BACKUP_DATE.tar.gz"
```

---

### WF-4: SSL Certificate Renewal (Auto via cPanel)

cPanel con AutoSSL renovará automáticamente. Validar en:
```
cPanel → SSL/TLS Status
Dominios que monitorear:
- gano.digital (principal)
- gano.bio (secundario)
```

**If manual renewal needed:**
```bash
ssh f1rml03th382@72.167.102.145 << 'EOF'
  /usr/local/cpanel/bin/ssl_install --domain=gano.digital \
    --cert-file=/etc/ssl/certs/gano.digital.crt \
    --key-file=/etc/ssl/private/gano.digital.key \
    --ca-file=/etc/ssl/certs/gano.digital.ca
EOF
```

---

### WF-5: Performance Monitoring

**Script:** `.gano-skills/gano-cpanel-ssh-management/monitor-health.sh`

```bash
#!/bin/bash
# Ejecutar: daily (cron 0 6 * * *)

SERVER="f1rml03th382@72.167.102.145"

echo "=== CPU Usage ==="
ssh "$SERVER" "top -bn1 | grep 'Cpu(s)' | sed 's/.*, *\([0-9.]*\)%* id.*/\1/' | awk '{print 100 - $1}'"

echo "=== Memory Usage ==="
ssh "$SERVER" "free -h | grep Mem"

echo "=== Disk Usage ==="
ssh "$SERVER" "df -h /public_html"

echo "=== MySQL Status ==="
ssh "$SERVER" "mysqladmin -u gano_user -p$GANO_DB_PASS status"

echo "=== Apache Status ==="
ssh "$SERVER" "systemctl status httpd | grep 'Active:'"

echo "=== WordPress Health Check ==="
curl -s https://gano.digital/wp-json/v1/site-health | jq '.tests[] | select(.status == "critical")'

# Si algún valor > threshold, alertar
```

---

## 📊 Parámetros Críticos a Monitorear

| Métrica | Umbral Alerta | Acción |
|---------|--------------|--------|
| CPU | >80% por 5 min | Revisar procesos PHP, MySQL |
| Memoria | >85% | Aumentar plan o limpiar cache |
| Disco | >80% | Limpiar logs, backups viejos |
| MySQL Connections | >10 | Optimizar queries, aumentar max_connections |
| HTTP Errors 5xx | >10/hora | Revisar wp-content/debug.log |

---

## 🔒 Seguridad SSH

### Hardening SSH Key (Windows PowerShell Admin)
```powershell
# Ejecutado post-token-rotation:
icacls "$env:USERPROFILE\.ssh\id_rsa" /inheritance:r /grant:r "$env:USERNAME`:F"
icacls "$env:USERPROFILE\.ssh\id_ed25519" /inheritance:r /grant:r "$env:USERNAME`:F"
```

### SSH Config Optimizado (~/.ssh/config)
```
Host gano-godaddy
    HostName 72.167.102.145
    User f1rml03th382
    Port 22
    IdentityFile ~/.ssh/id_ed25519
    ServerAliveInterval 60
    ServerAliveCountMax 3
    Compression yes
    CompressionLevel 6
```

---

## 🚀 Dispatch Tasks (Para queue)

**File:** `.github/agent-queue/tasks-cpanel-ssh.json`

```json
{
  "tasks": [
    {
      "task_id": "cpanel-addon-domain-fix",
      "description": "Corregir Addon Domain gano.digital → /public_html/gano.digital",
      "priority": "CRITICAL",
      "blockers": ["gano.digital deploy", "DNS validation"],
      "assignee": "diego",
      "commands": [
        "Validar SSH access: ssh f1rml03th382@72.167.102.145",
        "Ejecutar: grep gano.digital /etc/userdomains",
        "Editar /etc/userdomains (ruta correcta)",
        "/scripts/builddomainconf",
        "systemctl restart httpd",
        "curl -I https://gano.digital (validar)"
      ]
    },
    {
      "task_id": "pre-deploy-validation-setup",
      "description": "Configurar pre-deploy script en CI/CD",
      "status": "ready",
      "commands": [
        "Copiar pre-deploy-validation.sh a .gano-skills/",
        "Hacer ejecutable: chmod +x",
        "Agregar a .github/workflows/04-deploy.yml"
      ]
    },
    {
      "task_id": "backup-cron-setup",
      "description": "Configurar cronjob para backups semanales",
      "status": "ready",
      "commands": [
        "SSH al servidor",
        "mkdir -p /home/f1rml03th382/backups",
        "crontab -e → añadir línea backup",
        "Validar: crontab -l"
      ]
    }
  ]
}
```

---

## 📋 Checklist Completitud

- [ ] SSH keys sincronizadas y validadas
- [ ] Addon Domain gano.digital apuntando a /public_html/gano.digital
- [ ] SSL certificate instalado y renovación automática
- [ ] Pre-deploy validation script integrado
- [ ] Backup semanal configurado
- [ ] Health check monitoring en lugar
- [ ] GitHub Secrets configurados (SSH_HOST, USER, etc)
- [ ] Workflow 04-Deploy ejecutando sin errores

---

## 🔗 Referencias

- cPanel API docs: https://api.docs.cpanel.net/
- SSH Security: https://man.openbsd.org/ssh_config
- GoDaddy Hosting Docs: https://www.godaddy.com/help

---

**Generated:** 2026-04-03 by Claude
**Next:** Diego valida SSH access, ejecuta corrección Addon Domain
