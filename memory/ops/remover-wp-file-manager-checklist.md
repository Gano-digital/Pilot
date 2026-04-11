# Checklist: Eliminación de wp-file-manager

Este documento proporciona los pasos necesarios para eliminar de forma segura el plugin **wp-file-manager** del servidor de producción/staging. 

> [!CAUTION]
> **Riesgo Crítico de Seguridad**: Este plugin tiene un historial de vulnerabilidades de ejecución remota de código (RCE) con puntaje CVSS 10.0 (ej. CVE-2020-25213). Su mera presencia en el disco del servidor representa un riesgo, incluso si está desactivado.

---

## Opción A: Eliminación Automatizada (GitHub Actions)
Es el método recomendado si ya has configurado los *secrets* del repositorio.

1. Ve a la pestaña **Actions** en GitHub.
2. Selecciona el workflow **12 · Ops · Eliminar wp-file-manager (SSH)**.
3. Haz clic en **Run workflow**.
4. Asegúrate de que `force_remove` esté en `true`.
5. El script se conectará por SSH y borrará la carpeta de forma recursiva.

---

## Opción B: Eliminación Manual (wp-admin + SFTP)
Si prefieres hacerlo manualmente o no tienes configurada la automatización:

1. **Desactivar el plugin**:
   - Ve a **wp-admin** → **Plugins**.
   - Haz clic en **Desactivar** sobre el plugin "File Manager" (wp-file-manager).
   - Haz clic en **Borrar** si WordPress te lo permite.

2. **Eliminar rastro en disco (Crítico)**:
   - Conéctate a tu servidor vía **SFTP** o el Gestor de Archivos de tu Hosting (cPanel).
   - Navega a la ruta: `/wp-content/plugins/`.
   - Busca y elimina completamente la carpeta `wp-file-manager/`.

---

## Verificación de Éxito
Una vez eliminado el plugin y su carpeta:

1. **Alerta de Gano Security**: Inicia sesión en **wp-admin**. La alerta roja crítica inyectada por el MU plugin `gano-security.php` **debe haber desaparecido**.
2. **WP-CLI (Opcional)**: Si tienes acceso a terminal, ejecuta:
   ```bash
   wp plugin list --status=inactive
   ```
   Verifica que `wp-file-manager` no aparezca en la lista.

---

## Referencias
*   **Vulnerabilidad**: [CVE-2020-25213](https://nvd.nist.gov/vuln/detail/CVE-2020-25213).
*   **Detección en el Repo**: Ver lógica en `wp-content/mu-plugins/gano-security.php`.
*   **Guía de Workflows**: [`.github/workflows/README.md`](../../.github/workflows/README.md).
