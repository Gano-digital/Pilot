# Checklist: eliminar wp-file-manager (manual o complemento al workflow 12)

**Objetivo:** quitar el plugin de riesgo del servidor para que desaparezca la alerta del MU plugin `gano-security.php`.

## Opción A — GitHub Actions (recomendada si ya tienes deploy)

1. Configurar secrets: `SSH`, `SERVER_HOST`, `SERVER_USER`, `DEPLOY_PATH` (iguales que **04 · Deploy**).
2. **Actions → 12 · Ops · Eliminar wp-file-manager (SSH)** → Run workflow → `force_remove: true` cuando corresponda.
3. Ver documentación: [`.github/workflows/README.md`](../../.github/workflows/README.md) y archivo `verify-remove-wp-file-manager.yml`.

## Opción B — Manual en panel + SFTP

1. **wp-admin → Plugins:** desactivar **WP File Manager** (o nombre exacto del plugin).
2. **SFTP/SSH:** eliminar la carpeta `wp-content/plugins/wp-file-manager/` (o el slug real del plugin).
3. Vaciar caché del servidor si hay plugin de caché.
4. Recargar **wp-admin:** la alerta de seguridad de Gano no debe mostrar ya la advertencia por ese plugin.

## Verificación post

- `wp plugin list` (WP-CLI en servidor) — el plugin no debe listarse como activo ni inactivo con esas rutas.
- No reinstalar el plugin en producción.

## Referencias

- `TASKS.md` — ítem crítico wp-file-manager.
- `memory/notes/plugins-de-fase.md` — no confundir con plugins `gano-phase*`.
