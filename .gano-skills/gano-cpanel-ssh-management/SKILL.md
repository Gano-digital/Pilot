# Skill — cPanel + SSH Server Management para Gano Digital

**Última actualización:** 2026-04-10
**Estado:** 🟡 PARTIALLY OPERATIONAL (depende de acceso cPanel/SSH)
**Especialización:** Configuración servidor GoDaddy, dominios, SSL, deployments, backups

`<IP_SERVIDOR>` y `<USUARIO_CPANEL>` son placeholders: los valores reales viven en el panel GoDaddy, GitHub Secrets o variables locales — **no** commitear IP/usuario reales en ramas públicas.

---

## 🎯 Propósito

Automatizar y documentar la administración completa del servidor GoDaddy (<IP_SERVIDOR>) donde corre gano.digital:

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
| `SERVER_HOST` | <IP_SERVIDOR> | IP |
| `SERVER_USER` | <USUARIO_CPANEL> | Usuario cPanel/SSH |
| `DEPLOY_PATH` | /public_html/gano.digital | Ruta WordPress |
| `CPANEL_USER` | [Diego proporciona] | cPanel account |
| `CPANEL_TOKEN` | [Diego proporciona] | cPanel API token |

**Status:** 🟡 SSH keys not yet synced to server. **Action:** Diego to validate SSH access manually.

---

## 🛠️ Workflows Principales

### WF-1: Dominios y Document Root (SOTA, sin root)

**Regla de oro:** en hosting cPanel (GoDaddy), **no asumas acceso root** ni edites `/etc/userdomains`. El método SOTA y soportado es **cPanel → Domains**.

**Cambiar root folder de un addon domain/subdominio (GoDaddy):**

- GoDaddy → Web Hosting (cPanel) → **Administrar** → **cPanel Admin**
- **Domains**
- En el dominio objetivo: **Manage**
- En **New Document Root**: define el nuevo directorio
- **Update**

Notas SOTA:
- Cambiar el document root **no mueve archivos**: debes mover/copiar manualmente con File Manager/FTP si estás migrando contenido.
- No aplica a dominios **Alias/Parked** (comparten docroot del principal).

Referencias:
- GoDaddy: `change-my-addon-domain-or-subdomain-root-folder...` (`https://www.godaddy.com/help/change-my-addon-domain-or-subdomain-root-folder-for-web-hosting-cpanel-16170`)
- cPanel: `How can I change the document root...` (`https://support.cpanel.net/hc/en-us/articles/360057802373-How-can-I-change-the-document-root-for-an-Addon-Domain-or-Subdomain`)

---

### WF-2: Deployment Safety Check (Pre-deploy)

Antes de cada push a `main`:

```bash
#!/bin/bash
# Script: pre-deploy-validation.sh

SERVER_HOST="<IP_SERVIDOR>"
SERVER_USER="<USUARIO_CPANEL>"
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
BACKUP_DIR="/home/<USUARIO_CPANEL>/backups"
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
ssh <USUARIO_CPANEL>@<IP_SERVIDOR> << 'EOF'
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

SERVER="<USUARIO_CPANEL>@<IP_SERVIDOR>"

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
    HostName <IP_SERVIDOR>
    User <USUARIO_CPANEL>
    Port 22
    IdentityFile ~/.ssh/id_ed25519
    ServerAliveInterval 60
    ServerAliveCountMax 3
    Compression yes
    CompressionLevel 6
```

---

## 🧰 SSH en cPanel (GoDaddy) — Dev/Repair “servidores pequeños” (SOTA)

### Qué tipo de servidor es (modelo mental correcto)

En GoDaddy **Web Hosting (cPanel)**, el SSH típicamente es **shared hosting** con shell restringida (sin `root`/`sudo`). Implicaciones:

- No hay systemd ni reinicios de servicios (`systemctl`, `service`) garantizados.
- No puedes instalar paquetes del SO; trabajas con lo que el proveedor habilita.
- El usuario SSH suele ser el **mismo** que FTP/cPanel (según GoDaddy).

Referencias:
- Habilitar SSH: `https://www.godaddy.com/help/enable-ssh-for-my-web-hosting-cpanel-account-16102`
- Conectar por SSH: `https://www.godaddy.com/help/connect-to-my-web-hosting-cpanel-account-with-ssh-secure-shell-31865`

### Checklist “primeros 5 comandos” para diagnóstico (no destructivo)

Ejecutar en SSH (solo lectura / inventario):

```bash
pwd
whoami
uname -a
php -v || true
mysql --version || true
```

Luego inventario de rutas típicas (ajustar al docroot real):

```bash
ls -la ~
ls -la public_html || true
ls -la public_html/gano.digital || true
```

### Reparación práctica vía SSH (sin tocar panel)

Usos seguros en hosting pequeño:

- Verificar permisos y ownership en `wp-content/` (uploads, cache).
- Empaquetar backups puntuales (`tar.gz`) de carpeta WP.
- Exportar base de datos si hay `mysqldump` habilitado (si no, usar phpMyAdmin).
- Lint / grep local (para confirmar archivos desplegados).

Evitar (probablemente no disponible o riesgoso):

- `sudo`, edición de config del sistema, reinicios de Apache/PHP-FPM.
- Cambiar document root por archivos de sistema (siempre usar **cPanel → Domains**).

### Automatización de deploy en cPanel (opción nativa SOTA)

Si el cPanel del hosting trae **Git Version Control**, el patrón SOTA es:

1. Crear repo en `cPanel → Files → Git Version Control` (clonar desde remoto).
2. Añadir `.cpanel.yml` al repo (en raíz del repo cPanel-managed).
3. Elegir:
   - **Push deployment**: `git push` al repo cPanel → hook `post-receive` despliega.
   - **Pull deployment**: botón `Update from Remote` + `Deploy HEAD Commit`.

Referencia cPanel (2025-06): `https://docs.cpanel.net/knowledge-base/web-services/guide-to-git-deployment/`

**Notas de seguridad para Gano Digital:**
- No desplegar `.git/` ni comodines; desplegar solo rutas explícitas (`wp-content/themes/gano-child`, `wp-content/mu-plugins`, `wp-content/plugins/gano-*`, `.htaccess` si aplica).
- Mantener `wp-config.php` fuera del repo.

### Automatización “sin Git cPanel”: webhook HTTPS (ya adoptado en repo)

Cuando el SSH desde runners externos falla por IP/firewall, el enfoque robusto es:
- GitHub Actions (ubuntu-latest) genera ZIP de rutas Gano.
- Envía a webhook `receive.php` con firma HMAC.
- El servidor extrae/actualiza sin exponer SSH.

Este es el patrón recomendado cuando:
- El hosting bloquea IPs de GitHub Actions.
- No quieres mantener self-hosted runners en el servidor.

---

## 🟦 “Managed Hosting for Node.JS” (screenshot) — cómo usarlo sin romper WordPress

El modal del screenshot sugiere un producto tipo **PaaS administrado** para Node.js (beta) con:
- Deploy “drag & drop”
- SSL automático y CDN
- Servidores gestionados

**Lectura correcta:** esto no es “cPanel + Apache” tradicional; suele ser un runtime administrado donde tú subes un artefacto Node y el proveedor opera TLS/CDN/escala.

Cómo puede encajar en Gano:

- **Ops Hub / dashboards internos** (p. ej. panel estático o SSR ligero) en un subdominio tipo `ops.gano.digital` sin tocar WordPress.
- **Servicios auxiliares** (webhooks, automatizadores) *si* el proveedor permite variables de entorno y endpoints HTTPS.

Guardrails:
- No mover el WordPress principal a este hosting sin plan (cambia el runtime/stack).
- Mantener secretos en variables de entorno del proveedor (no en repo).
- Usar subdominios separados para aislar (DNS + SSL).

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
        "Validar SSH access: ssh <USUARIO_CPANEL>@<IP_SERVIDOR>",
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
        "mkdir -p /home/<USUARIO_CPANEL>/backups",
        "crontab -e → añadir línea backup",
        "Validar: crontab -l"
      ]
    }
  ]
}
```

---

## Instalaciones híbridas: WordPress (negocio) + Installatron / otra app (evidencias 2026-04)

**Cuándo usar:** el panel cPanel muestra una app (p. ej. **Drupal**) en “Aplicaciones” / **Installatron**, pero el producto en git es **WordPress** en `gano.digital` bajo `public_html/gano.digital`.

**Problemas frecuentes evidenciables:**

| Síntoma | Causa probable | Acción |
|---------|----------------|--------|
| `failed_application_config_doesnotexist` / `noconfigfile` en Installatron | Metadata o archivo de instalación roto | Ticket proveedor: reparación Installatron / `--repair --recache` (solo admin servidor) |
| Logs: “Unable to read source install configuration file” | Misma línea + path movido | No usar Site Publisher sobre carpetas existentes; backup y resincronizar o desinstalar app **no usada** |
| Updates a `…/123/update.php` → **HTTP 400** | URL base mal enlazada (dominio principal vs addon) | Corregir URL en Installatron o alinear DNS/docroot; ver informe ops |
| Force HTTPS **apagado** en dominio principal | SSL o política no forzada | SSL/TLS Status → AutoSSL; Force HTTPS por dominio en cPanel |
| RAM **512 MB** | Plan compartido | Optimizar WP (caché, plugins); valorar upgrade si hay 500 |

**Informe detallado (capturas + plan de reparación):** [`memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md`](../../memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md)

**Regla:** la **fuente de verdad del negocio** es el WordPress en **`gano.digital`** alineado al repo **Pilot**; Installatron gestiona **otra** instalación — no mezclar runbooks sin confirmar rutas en disco.

---

## 🧩 Installatron — instalar sin sobrescribir y “adoptar” instalaciones (SOTA)

### Caso A: “Necesito instalar WordPress pero el dominio ya está en uso” (NO borrar archivos)

Estrategias seguras (preferencia):

- **Subdominio nuevo** (recomendado): crear `wp.gano.digital` / `staging.gano.digital` con docroot **aislado** (sin “Compartir raíz del documento”), luego instalar WP con **Ruta vacía**.
- **Subdirectorio**: instalar en `gano.digital/wp2` (Ruta = `wp2`) para no tocar la raíz.
- **Instalación manual** en carpeta nueva: cuando Installatron bloquea por detección de archivos existentes, aun usando un path.

Guía GoDaddy para Installatron (campo Directorio):
- Dejar **Directorio en blanco** = instalar en la raíz del dominio.
- Escribir `blog` (o `wp2`) = instalar en `/blog` (o `/wp2`).

Referencia GoDaddy: `Instala WordPress ... usando cPanel` (`https://www.godaddy.com/es/help/instala-wordpress-en-mi-dominio-con-web-hosting-cpanel-usando-cpanel-16038`)

### Caso B: “Ya hay un WordPress instalado, pero Installatron no lo gestiona” (Import/Adopt)

En Installatron:
- Ir a **Applications Browser**
- Seleccionar **WordPress**
- En el botón **Install this application**, abrir el menú (chevron) y elegir **Import existing install**
- Elegir **From this account**
- Seleccionar dominio y directorio donde vive WP (vacío si es raíz; `blog` si es subcarpeta)

Referencia (pasos detallados): `https://www.fused.com/docs/cpanel/installatron/importing-an-installation/`

### Caso C: staging (clonar + sincronizar)

Si el hosting trae **WP Toolkit**, el flujo SOTA es:
- **Clone** a subdominio staging
- Probar cambios
- **Copy Data** de staging a producción con control fino (archivos/tablas) y restore point automático

Referencia cPanel WP Toolkit (conceptual): `https://www.cpanel.net/blog/products/how-to-deploy-a-wordpress-staging-site-with-cpanel/`

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
- [ ] Installatron / segunda app: sin errores E-01–E-04 o desinstalada si no aplica (ver informe ops)
- [ ] Force HTTPS coherente en dominios activos (gano.digital mínimo)
- [ ] Si “dominio en uso”: WP instalado en **subdominio/subdirectorio** o WP existente **importado** en Installatron (sin reinstalar en raíz)

---

## 🔗 Referencias

- Informe servidor (evidencias + SOTA + plan): [`memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md`](../../memory/ops/investigacion-servidor-cpanel-evidencias-reparacion-2026-04.md)
- Installatron troubleshooting: https://installatron.com/docs/troubleshooting
- cPanel API docs: https://api.docs.cpanel.net/
- SSH Security: https://man.openbsd.org/ssh_config
- GoDaddy Hosting Docs: https://www.godaddy.com/help

---

**Generated:** 2026-04-03 by Claude
**Next:** Diego valida SSH access, ejecuta corrección Addon Domain
