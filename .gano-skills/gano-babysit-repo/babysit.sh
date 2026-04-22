#!/bin/bash

# Gano Digital Repo Babysitter v1.0
# Validaciones: git status, PHP lint, archivos servidor, HTTP 200, error logs, divergencias
# Retorna JSON con estado completo

set -e

TIMESTAMP=$(date -u +"%Y-%m-%dT%H:%M:%SZ")
SSH_KEY="~/.ssh/id_rsa_deploy"
SSH_HOST="f1rml03th382@72.167.102.145"
SERVER_PATH="/home/f1rml03th382/public_html/gano.digital"
LOCAL_PATH="$(pwd)"
SITE_URL="https://gano.digital"

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "[$(date)] 🔍 Iniciando babysitting de repo..." >&2

# 1. GIT STATUS
echo "[$(date)] 📊 Verificando git status..." >&2
git_status=$(git status --porcelain 2>&1 || echo "ERROR")
git_branch=$(git branch --show-current 2>&1 || echo "UNKNOWN")
git_commits_ahead=$(git rev-list --left-only --count origin/main...HEAD 2>&1 || echo "0")

if [ "$git_status" = "ERROR" ]; then
  git_check_status="❌ error"
  git_pending="unknown"
else
  if [ -z "$git_status" ]; then
    git_check_status="✅ limpio"
    git_pending="0"
  else
    git_check_status="⚠️ cambios pendientes"
    git_pending=$(echo "$git_status" | wc -l)
  fi
fi

# 2. PHP LINT
echo "[$(date)] 🔍 Validando PHP lint..." >&2
php_functions=$(php -l "wp-content/themes/gano-child/functions.php" 2>&1 | grep -q "No syntax errors" && echo "✅ OK" || echo "❌ ERROR")
php_frontpage=$(php -l "wp-content/themes/gano-child/front-page.php" 2>&1 | grep -q "No syntax errors" && echo "✅ OK" || echo "❌ ERROR")

php_check_status="✅ OK"
if [[ "$php_functions" == *"ERROR"* ]] || [[ "$php_frontpage" == *"ERROR"* ]]; then
  php_check_status="❌ ERRORES DETECTADOS"
fi

# 3. SERVIDOR - Archivos actualizados
echo "[$(date)] 📁 Verificando archivos en servidor..." >&2
server_check=$(ssh -i "$SSH_KEY" -o ConnectTimeout=5 "$SSH_HOST" "ls -la $SERVER_PATH/wp-content/themes/gano-child/functions.php 2>/dev/null | awk '{print \$6,\$7,\$8}'" 2>&1 || echo "UNREACHABLE")

if [ "$server_check" = "UNREACHABLE" ]; then
  server_status="❌ SSH no responde"
  files_synced="unknown"
else
  # Comparar timestamp local vs servidor
  local_mtime=$(stat -c %y "wp-content/themes/gano-child/functions.php" 2>/dev/null | cut -d' ' -f1-2 || echo "unknown")
  server_mtime=$(ssh -i "$SSH_KEY" -o ConnectTimeout=5 "$SSH_HOST" "stat -c %y $SERVER_PATH/wp-content/themes/gano-child/functions.php 2>/dev/null | cut -d' ' -f1-2" 2>&1 || echo "unknown")

  if [ "$local_mtime" = "$server_mtime" ]; then
    server_status="✅ Sincronizado"
    files_synced="true"
  else
    server_status="⚠️ Desincronizado"
    files_synced="false"
  fi
fi

# 4. HTTP 200 OK
echo "[$(date)] 🌐 Verificando sitio live..." >&2
http_code=$(curl -s -o /dev/null -w "%{http_code}" "$SITE_URL/" 2>&1 || echo "000")
homepage_status="$([ "$http_code" = "200" ] && echo "✅ 200 OK" || echo "❌ HTTP $http_code")"

# 5. ERROR LOG del servidor
echo "[$(date)] 📋 Buscando errores en servidor..." >&2
error_log_check=$(ssh -i "$SSH_KEY" -o ConnectTimeout=5 "$SSH_HOST" "tail -50 $SERVER_PATH/wp-content/debug.log 2>/dev/null | grep -i 'fatal\|error\|warning' | wc -l" 2>&1 || echo "0")
if [ "$error_log_check" = "0" ]; then
  error_log_status="✅ Sin errores"
  errors_found="0"
else
  error_log_status="⚠️ $error_log_check alertas"
  errors_found="$error_log_check"
fi

# 6. DIVERGENCIAS local vs servidor
echo "[$(date)] 🔄 Detectando divergencias..." >&2
divergences=()

# Divergencia: cambios locales sin push
if [ "$git_check_status" != "✅ limpio" ]; then
  divergences+=("cambios_locales_sin_push")
fi

# Divergencia: servidor desincronizado
if [ "$files_synced" = "false" ]; then
  divergences+=("archivos_desincronizados")
fi

# Divergencia: HTTP no 200
if [ "$http_code" != "200" ]; then
  divergences+=("sitio_no_responde_200")
fi

# Divergencia: PHP errors
if [ "$php_check_status" != "✅ OK" ]; then
  divergences+=("errores_php_detectados")
fi

# Divergencia: SSH no responde
if [ "$server_check" = "UNREACHABLE" ]; then
  divergences+=("servidor_inaccesible")
fi

# Divergencia: Error logs con alertas
if [ "$errors_found" != "0" ]; then
  divergences+=("error_log_con_alertas")
fi

divergence_status="$([ ${#divergences[@]} -eq 0 ] && echo "✅ LIMPIO" || echo "⚠️ ${#divergences[@]} DIVERGENCIAS")"

# RETORNAR JSON
cat <<EOF
{
  "timestamp": "$TIMESTAMP",
  "status": "OK",
  "summary": {
    "git": "$git_check_status",
    "php": "$php_check_status",
    "server": "$server_status",
    "homepage": "$homepage_status",
    "error_log": "$error_log_status",
    "divergences": "$divergence_status"
  },
  "details": {
    "git": {
      "branch": "$git_branch",
      "pending_changes": "$git_pending",
      "commits_ahead_main": "$git_commits_ahead"
    },
    "php": {
      "functions_php": "$php_functions",
      "front_page_php": "$php_frontpage"
    },
    "server": {
      "ssh_status": "$([ "$server_check" = "UNREACHABLE" ] && echo "unreachable" || echo "connected")",
      "files_synced": "$files_synced",
      "local_mtime": "$local_mtime",
      "server_mtime": "$server_mtime"
    },
    "homepage": {
      "url": "$SITE_URL",
      "http_code": "$http_code"
    },
    "error_log": {
      "recent_errors": "$errors_found"
    }
  },
  "divergences": [
    $(IFS=,; for d in "${divergences[@]}"; do echo "\"$d\""; done | paste -sd, -)
  ]
}
EOF

# ALERT si hay divergencias
if [ ${#divergences[@]} -gt 0 ]; then
  echo "" >&2
  echo -e "${YELLOW}⚠️  DIVERGENCIAS DETECTADAS:${NC}" >&2
  for div in "${divergences[@]}"; do
    echo "  • $div" >&2
  done
  exit 1
else
  echo -e "${GREEN}✅ Todo sincronizado${NC}" >&2
  exit 0
fi
