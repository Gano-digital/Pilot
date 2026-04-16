# GitHub Actions: Configuración de Secretos y Runners

**Documento de coordinación para desarrolladores, operadores y CI/CD**

_Última actualización: 2026-04-16_

---

## 1. Secretos Requeridos (GitHub Repository Secrets)

Estos secretos deben configurarse en **GitHub Repo → Settings → Secrets and variables → Actions** para que los workflows críticos funcionen.

### 1.1 Secretos Deploy (Workflow 04 · Deploy)

| Secret Name | Descripción | Ejemplo | Dónde obtener |
|-------------|-----------|---------|---------------|
| `GANO_DEPLOY_HOOK_URL` | URL HTTPS del endpoint webhook que recibe deployments | `https://gano.digital/wp-content/gano-deploy/receive.php` | GoDaddy Managed WordPress → Settings → Webhooks (o crear en wp-content) |
| `GANO_DEPLOY_HOOK_SECRET` | Token/secret para validar la solicitud del webhook | Cadena aleatoria 32+ caracteres | Generar con: `openssl rand -hex 16` |

### 1.2 Secretos SSH (Workflows 05, 12, 06, 07)

| Secret Name | Descripción | Ejemplo | Dónde obtener |
|-------------|-----------|---------|---------------|
| `SSH` | Clave privada SSH (id_rsa en PEM format) | `-----BEGIN OPENSSH PRIVATE KEY-----...` | GoDaddy cPanel → SSH Keys → Descargar Private Key |
| `SERVER_HOST` | IP o FQDN del servidor | `123.45.67.89` o `managed-wp.godaddy.com` | GoDaddy Account → Hosting → IP/Domain |
| `SERVER_USER` | Usuario SSH del servidor | `f1rml03th382` o `root` (no recomendado) | GoDaddy cPanel → SSH Keys → Username |
| `DEPLOY_PATH` | Path al WordPress public_html en servidor | `/home/f1rml03th382/public_html/gano.digital` | GoDaddy cPanel → File Manager (raíz pública) |

### 1.3 Secretos GoDaddy Reseller API (Workflow 30 · Reseller Catalog Sync)

| Secret Name | Descripción | Ejemplo | Dónde obtener |
|-------------|-----------|---------|---------------|
| `GODADDY_API_KEY` | API Key para Reseller | `abc123...xyz` | GoDaddy Reseller Portal → Developer Tools → API Keys → Create/View |
| `GODADDY_API_SECRET` | API Secret para Reseller | `secret123...xyz` | Mismo lugar (generar con key) |

### 1.4 Secretos GitHub Integrations (Workflow 13 · Add to Project)

| Secret Name | Descripción | Ejemplo | Dónde obtener |
|-------------|-----------|---------|---------------|
| `ADD_TO_PROJECT_PAT` | Personal Access Token (classic, `public_repo`, `read:org`) | `ghp_abc...xyz` | GitHub → Settings → Developer settings → Tokens (classic) → Generate new token |

---

## 2. Cómo Configurar Secretos

### Paso a paso (GitHub Web UI):

1. Ve a: **https://github.com/Gano-digital/Pilot/settings/secrets/actions**
2. Haz clic en **"New repository secret"**
3. Ingresa el nombre exacto (ej. `GANO_DEPLOY_HOOK_URL`)
4. Pega el valor (sin comillas)
5. Haz clic en **"Add secret"**
6. **Repite para todos los 9 secretos**

### ✅ Verificación (NO expone valores):

Después de configurar, ejecuta:

\`\`\`bash
gh secret list
\`\`\`

Debe mostrar:
\`\`\`
GANO_DEPLOY_HOOK_URL       Updated 2026-04-16
GANO_DEPLOY_HOOK_SECRET    Updated 2026-04-16
SSH                        Updated 2026-04-16
SERVER_HOST                Updated 2026-04-16
SERVER_USER                Updated 2026-04-16
DEPLOY_PATH                Updated 2026-04-16
GODADDY_API_KEY            Updated 2026-04-16
GODADDY_API_SECRET         Updated 2026-04-16
ADD_TO_PROJECT_PAT         Updated 2026-04-16
\`\`\`

---

## 3. Runners: Estado y Migración

### 3.1 Self-Hosted Runners (ELIMINADOS)

**Estado actual**: Runners \`gano-production\` fueron eliminados el 2026-04-11.

**Workflows afectados**:
- Workflow 06 · DB Backup
- Workflow 07 · Content Sync
- Workflow 08 · Health Check
- Workflow 31 · Phase 4 Health

### 3.2 Opción Recomendada: Migración a ubuntu-latest

Edita \`.github/workflows/{06-db-backup,07-sync-content,08-health-check-plugins,31-plugin-health-check-phase4}.yml\`:

**Cambiar de**:
\`\`\`yaml
runs-on: [self-hosted, gano-production]
\`\`\`

**A**:
\`\`\`yaml
runs-on: ubuntu-latest
\`\`\`

---

## 4. Pipeline Crítico: Orden de Ejecución

\`\`\`
1. [Configurar Secretos GitHub] (MANUAL - Diego)
   ↓
2. [Validar YAML] (CI automático)
   ↓
3. [Workflow 04 · Deploy] → staging first (manual trigger)
   ↓
4. [Workflow 05 · Verify Patches] (manual trigger)
   ↓
5. [Workflow 12 · Remove wp-file-manager] (manual trigger)
   ↓
6. [Servidor limpio, Phase 4 ready]
\`\`\`

---

## 5. Cómo Ejecutar Workflows Manualmente

**GitHub CLI**:
\`\`\`bash
gh workflow run deploy.yml --field target=staging
gh workflow run verify-patches.yml
gh workflow run verify-remove-wp-file-manager.yml --field force_remove=true
\`\`\`

**GitHub Web UI**: Actions → [Workflow] → Run workflow

---

**Documento**: Gano Digital Dev Coordination  
**Fecha**: 2026-04-16  
**Estado**: Implementación en progreso  
