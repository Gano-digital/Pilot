#!/usr/bin/env bash
#
# activate-gano-phases.sh
#
# Activa los plugins de fase Gano Digital en el orden correcto vía WP-CLI.
# Pensado para ejecutarse en el servidor con WP-CLI disponible (vía SSH o
# GitHub Actions). Diego NO lo corre hasta que haya secretos SSH
# configurados y se valide primero con el runbook wp-admin.
#
# Uso:
#   WP_CLI=wp DEPLOY_PATH=/home/user/public_html/gano.digital ./activate-gano-phases.sh
#   DRY_RUN=1 ./activate-gano-phases.sh   # solo imprime comandos
#
# Salida: códigos no-cero si cualquier activación falla (abort al primer error).

set -euo pipefail

WP="${WP_CLI:-wp}"
DEPLOY_PATH="${DEPLOY_PATH:-/var/www/html}"
DRY_RUN="${DRY_RUN:-0}"

# Orden oficial según memory/notes/plugins-de-fase.md
ORDER=(
  gano-phase1-installer
  gano-phase2-business
  gano-phase3-content
  gano-content-importer
  gano-phase6-catalog
  gano-phase7-activator
  gano-reseller-enhancements
)

# One-shots que deben desactivarse al final (dejan marker en wp_options).
ONE_SHOTS=(
  gano-phase1-installer
  gano-phase2-business
  gano-phase3-content
  gano-content-importer
)

run() {
  if [ "$DRY_RUN" = "1" ]; then
    echo "[dry-run] $*"
  else
    "$@"
  fi
}

echo "=== Activación Gano Phases ==="
echo "WP-CLI: $WP"
echo "Path:   $DEPLOY_PATH"
echo "Dry:    $DRY_RUN"
echo ""

# 1. Eliminar wp-file-manager (seguridad)
if "$WP" plugin is-installed wp-file-manager --path="$DEPLOY_PATH" 2>/dev/null; then
  echo "→ Eliminando wp-file-manager (CVE crítico)"
  run "$WP" plugin deactivate wp-file-manager --path="$DEPLOY_PATH" || true
  run "$WP" plugin delete wp-file-manager --path="$DEPLOY_PATH"
else
  echo "→ wp-file-manager no instalado · ok"
fi
echo ""

# 2. Activar plugins en orden
for plugin in "${ORDER[@]}"; do
  if ! "$WP" plugin is-installed "$plugin" --path="$DEPLOY_PATH" 2>/dev/null; then
    echo "✗ Plugin no encontrado: $plugin · abort"
    exit 1
  fi

  if "$WP" plugin is-active "$plugin" --path="$DEPLOY_PATH" 2>/dev/null; then
    echo "= Ya activo: $plugin"
    continue
  fi

  echo "→ Activando: $plugin"
  if ! run "$WP" plugin activate "$plugin" --path="$DEPLOY_PATH"; then
    echo "✗ Fallo al activar $plugin · abort"
    exit 1
  fi
  sleep 1
done
echo ""

# 3. Desactivar one-shots (contenido ya importado)
echo "=== Desactivando one-shots post-activación ==="
for plugin in "${ONE_SHOTS[@]}"; do
  if "$WP" plugin is-active "$plugin" --path="$DEPLOY_PATH" 2>/dev/null; then
    echo "→ Desactivando: $plugin"
    run "$WP" plugin deactivate "$plugin" --path="$DEPLOY_PATH" || true
  fi
done
echo ""

# 4. Reporte final
echo "=== Estado final ==="
"$WP" plugin list --status=active --path="$DEPLOY_PATH" --fields=name,status,version || true
echo ""
echo "✓ Plugins activados en orden"
