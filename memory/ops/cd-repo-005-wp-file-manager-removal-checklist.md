# Tarea cd-repo-005 — Eliminar wp-file-manager (security hardening checklist)

**ID:** `cd-repo-005`  
**Prioridad:** P1 (Security)  
**Requiere humano:** SÍ (Diego accede wp-admin + SFTP)  
**Generado:** 2026-04-19  
**Estado:** Listo para ejecución (no bloqueante, ejecutable en paralelo)

---

## Objetivo

Verificar que **wp-file-manager** está **completamente eliminado** del servidor gano.digital. Este plugin representa **riesgo de seguridad crítico** (acceso directo a filesystem sin restricción).

**Por qué:**
- wp-file-manager abre interfaz visual al filesystem del servidor (`/home/f1rml03th382/public_html/`)
- Sin restricción de acceso: cualquiera en wp-admin puede leer/escribir/ejecutar archivos
- Según auditoría externa (Auditoria de seguridad.pdf): **prohibido en producción**
- Fase 1 (parches): ya fue eliminado; esta tarea **verifica** no haya reaparecido

---

## 🚨 Riesgo de seguridad

**Nivel:** 🔴 **CRÍTICO**

| Riesgo | Impacto | Probabilidad |
|--------|---------|------------|
| Lectura de `wp-config.php` | Exposición de DB credentials | ALTA si plugin activo |
| Ejecución de código PHP | Acceso completo al servidor | ALTA si plugin activo |
| Modificación de themes/plugins | Inyección de backdoors | ALTA si plugin activo |
| Cambio de permisos de archivos | Defacement del sitio | MEDIA si plugin activo |

**Acción:** Eliminación inmediata + validación periódica.

---

## 🔍 Checklist de verificación (3 niveles)

### Nivel 1: WordPress Admin Panel

**1.1 — Accede a wp-admin:**

```
https://gano.digital/wp-admin/
```

**1.2 — Navega a Plugins:**

```
Menú izquierdo → Plugins → Todos los plugins
```

**1.3 — Busca "wp-file-manager" o "File Manager":**

```
Ctrl+F (buscar en página) → escribe "wp-file-manager"
```

**Resultados esperados:**
- ✅ **SIN RESULTADOS** → Plugin no está instalado (estado deseado)
- ❌ **RESULTADO:** Aparece en lista → Procede a Paso 1.4

**1.4 — Si aparece en lista:**

1. Haz clic en el nombre del plugin para expandir detalles
2. Busca botón **"Delete"** (o "Eliminar" en español)
3. Haz clic en **Delete**
4. Confirma eliminación en popup
5. WordPress mostrará: "Plugin deleted successfully"
6. ✅ Verifica que desaparece de la lista

**Tabla de validación Nivel 1:**

| Paso | Acción | ✅ Resultado | ❌ Error |
|------|--------|-----------|---------|
| 1.3 | Búsqueda "wp-file-manager" en plugins | No encontrado (Deseado) | Plugin activo (Eliminar) |
| 1.4a | Hacer clic en Delete | Botón aparece | Botón no disponible |
| 1.4b | Confirmar eliminación | Popup "deleted successfully" | Error PHP/permiso |

---

### Nivel 2: Filesystem (SFTP / SSH)

**2.1 — Verificar carpeta de plugins:**

Accede vía SFTP (recomendado) o SSH (si tienes acceso):

**Ruta esperada:**
```
/home/f1rml03th382/public_html/gano.digital/wp-content/plugins/
```

**2.2 — Listar contenido:**

**Vía SFTP (Filezilla, etc.):**
1. Abre Filezilla (o cliente SFTP de tu elección)
2. Conecta a servidor gano.digital (detalles de hosting)
3. Navega a carpeta plugins (arriba)
4. **Busca carpeta llamada "wp-file-manager" o "file-manager"**

**Vía SSH (si tienes acceso):**
```bash
ls -la /home/f1rml03th382/public_html/gano.digital/wp-content/plugins/ | grep -i "file-manager"
```

**Resultados esperados:**
- ✅ **Vacío (sin coincidencias)** → Plugin no existe (estado deseado)
- ❌ **Aparece carpeta "wp-file-manager"** → Procede a eliminación

**2.3 — Eliminar carpeta si aparece:**

**Vía SFTP:**
1. Click derecho en carpeta `wp-file-manager`
2. Selecciona **Delete** (o "Borrar")
3. Confirma eliminación recursiva (todas las carpetas dentro)
4. Espera a que Filezilla termine

**Vía SSH:**
```bash
# Backup previo (seguridad):
tar -czf /home/f1rml03th382/backups/wp-file-manager-backup-$(date +%Y%m%d).tar.gz \
  /home/f1rml03th382/public_html/gano.digital/wp-content/plugins/wp-file-manager/

# Eliminar:
rm -rf /home/f1rml03th382/public_html/gano.digital/wp-content/plugins/wp-file-manager/

# Validar:
ls -la /home/f1rml03th382/public_html/gano.digital/wp-content/plugins/ | grep -i "file-manager"
# (Debería no mostrar nada)
```

**Tabla de validación Nivel 2:**

| Paso | Acción | ✅ Resultado | ❌ Error |
|------|--------|-----------|---------|
| 2.1-2.2 | Listar `/wp-content/plugins/` | No hay `wp-file-manager/` | Aparece carpeta `wp-file-manager` |
| 2.3 | Eliminar carpeta si existe | Eliminada exitosamente | Permiso denegado (contactar hosting) |
| 2.3b | Validar con `ls` de nuevo | Vacío/sin menciones | Carpeta sigue ahí |

---

### Nivel 3: WordPress Database

**3.1 — Verificar en BD que plugin no está registrado:**

Accede a MySQL (vía phpmyadmin o SSH):

**3.2 — Query para detectar referencias:**

```sql
SELECT option_name, option_value 
FROM wp_options 
WHERE option_value LIKE '%wp-file-manager%' 
   OR option_value LIKE '%file-manager%'
LIMIT 10;
```

**Resultado esperado:**
- ✅ **0 rows** → Sin referencias (estado deseado)
- ❌ **1+ rows** → Hay referencias (ver detalles abajo)

**3.3 — Si hay referencias restantes:**

```sql
SELECT * FROM wp_options 
WHERE option_name IN ('active_plugins', 'deactivated_plugins')
\G
```

Busca en `option_value` (lista serializada PHP):
- Si `wp-file-manager` aparece: plugin aún está registrado
- Acción: limpiar entrada (ver Troubleshooting)

**Tabla de validación Nivel 3:**

| Paso | Acción | ✅ Resultado | ❌ Error |
|------|--------|-----------|---------|
| 3.2 | Query `%wp-file-manager%` | 0 rows (limpio) | 1+ rows (referencias detectadas) |
| 3.3 | Revisar `active_plugins` | Sin mención | Aparece `wp-file-manager` |

---

## 🔄 Flujo de eliminación completa (si aparece)

**Tiempo estimado:** 15–20 minutos

### Opción A: Vía WordPress Admin (recomendado para no técnicos)

**Pasos:**
1. Accede wp-admin
2. Plugins → Todos los plugins
3. Busca "wp-file-manager"
4. Si aparece:
   - Haz clic en "Deactivate" (primero)
   - Espera ~5 segundos
   - Haz clic en "Delete"
   - Confirma en popup
5. Valida que desapareció de lista

**Ventaja:** WordPress maneja deactivation hooks automáticamente  
**Desventaja:** Requiere acceso wp-admin

---

### Opción B: Vía SFTP + BD cleanup (para acceso parcial)

**Pasos:**
1. Accede SFTP a `/wp-content/plugins/`
2. Elimina carpeta `wp-file-manager/` (backup previo)
3. Espera ~2 minutos (WordPress cacheará cambios)
4. En wp-admin, navega a Plugins (fuerza refresh: Ctrl+Shift+Del)
5. Verifica que desapareció
6. **Bonus:** ejecuta SQL cleanup (Paso 3.3) si necesario

**Ventaja:** Funciona incluso si wp-admin está roto  
**Desventaja:** Requiere acceso SFTP/SSH

---

### Opción C: Vía SSH (para técnicos)

```bash
#!/bin/bash
# Script seguro para eliminar wp-file-manager

WORDPRESS_PATH="/home/f1rml03th382/public_html/gano.digital"
PLUGIN_PATH="$WORDPRESS_PATH/wp-content/plugins/wp-file-manager"
BACKUP_DIR="/home/f1rml03th382/backups"

# 1. Backup previo
if [ -d "$PLUGIN_PATH" ]; then
  echo "📦 Haciendo backup..."
  tar -czf "$BACKUP_DIR/wp-file-manager-$(date +%Y%m%d-%H%M%S).tar.gz" "$PLUGIN_PATH"
  echo "✅ Backup completado: $BACKUP_DIR/wp-file-manager-*.tar.gz"
fi

# 2. Eliminar carpeta
echo "🗑️ Eliminando plugin..."
rm -rf "$PLUGIN_PATH"

# 3. Validar
if [ ! -d "$PLUGIN_PATH" ]; then
  echo "✅ Plugin eliminado exitosamente"
else
  echo "❌ Error: plugin aún existe"
  exit 1
fi

# 4. Verificar en BD (opcional)
# Requiere MySQL CLI configurada
# mysql -u usuario -p basedatos -e "SELECT COUNT(*) FROM wp_options WHERE option_value LIKE '%wp-file-manager%';"

echo "📋 Checklist completada"
```

**Usar:**
```bash
bash /ruta/al/script.sh
```

---

## ⚠️ Troubleshooting

### ❌ Escenario A: "Permiso denegado al eliminar vía SFTP"

**Causa:** Permisos de archivo restringidos (seguridad)

**Solución:**
1. Contacta soporte de hosting (GoDaddy Managed WordPress support)
2. Pide que eliminen carpeta `/wp-content/plugins/wp-file-manager/`
3. Verifica después de 30–60 minutos

**Alternativa:** Usa wp-cli si tienes acceso SSH:
```bash
wp plugin delete wp-file-manager
```

---

### ❌ Escenario B: "Plugin no aparece en wp-admin pero carpeta existe en filesystem"

**Causa probable:** Plugin desactivado o corrompido

**Solución:**
1. Vía SFTP/SSH: elimina carpeta manualmente (Opción B/C)
2. En wp-admin: vacía Plugins → Unused Plugins (si aparece)
3. Limpia caché: `wp cache flush` (SSH)
4. Recarga wp-admin

---

### ❌ Escenario C: "Aparece en BD pero no en wp-admin"

**Causa probable:** Entrada en BD huérfana (plugin eliminado pero registro no limpiado)

**Solución — SQL cleanup:**

```sql
-- IMPORTANTE: HACER BACKUP DE BD PRIMERO

-- 1. Desactivar referencia en active_plugins
SELECT option_value FROM wp_options WHERE option_name = 'active_plugins';
-- Verás algo como: a:5:{i:0;s:20:"plugin-name/file.php";...}

-- 2. Si wp-file-manager aparece en esa lista, necesitas limpiarla
-- (Requiere conocimiento PHP/serialización)
-- MEJOR: Usa wp-cli:
wp plugin delete wp-file-manager --allow-root
```

O vía wp-admin:
1. Navega a Plugins
2. Activa "Unused Plugins" view (si existe opción)
3. Elimina cualquier plugin huérfano

---

## ✅ Definition of Done

La tarea se cierra cuando:

- [ ] Búsqueda en wp-admin Plugins → **NO aparece** `wp-file-manager`
- [ ] Búsqueda en filesystem `/wp-content/plugins/` → **NO existe** carpeta `wp-file-manager`
- [ ] Query MySQL:
  ```sql
  SELECT COUNT(*) FROM wp_options WHERE option_value LIKE '%wp-file-manager%';
  ```
  → Devuelve **0 (cero)**
- [ ] Si existe backup: documentado en comentario qué versión se eliminó
- [ ] Validación ejecutada en los 3 niveles (admin, filesystem, BD)
- [ ] Registro en git (si aplica) o checklist manual cerrado

---

## 📋 Validación rápida (sin eliminar nada)

Si solo quieres **verificar** que no está instalado (sin eliminar):

```bash
# SSH: lista plugins activos
wp plugin list --status=active

# SFTP: navega a /wp-content/plugins/ y busca "wp-file-manager"

# MySQL: cuenta referencias
mysql -e "SELECT COUNT(*) FROM wp_options WHERE option_value LIKE '%wp-file-manager%';"
```

**Si todos devuelven 0/vacío:** ✅ Plugin no está presente

---

## 🔐 Prevención de reactivación

Para evitar que wp-file-manager sea reinstalado accidentalmente:

**Opción 1: Bloquearlo en wp-config.php**

```php
// wp-config.php - agregar línea:
define( 'DISALLOW_FILE_MODS', true );  // Bloquea instalación de plugins
```

**Opción 2: Bloquearlo en MU plugin (gano-security.php)**

```php
// wp-content/mu-plugins/gano-security.php

// Bloquear plugin específico
add_filter( 'plugin_install_status_text', function( $status, $plugin ) {
    if ( strpos( $plugin, 'wp-file-manager' ) !== false ) {
        return '<span style="color:red;">PROHIBIDO (seguridad)</span>';
    }
    return $status;
});
```

**Opción 3: Monitoreo periódico**

Agregar a `.github/workflows/` un workflow que valida periódicamente:

```yaml
# .github/workflows/13-security-audit-plugins.yml
name: Security Audit - Prohibited Plugins

on:
  schedule:
    - cron: '0 0 * * 0'  # Cada domingo

jobs:
  audit:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Check for wp-file-manager
        run: |
          if grep -r "wp-file-manager" ./wp-content/plugins/; then
            echo "❌ ERROR: wp-file-manager detectado!"
            exit 1
          fi
          echo "✅ Plugin prohibido no encontrado"
```

---

## 📞 Escalaciones

Si encuentras dificultad eliminando:

1. **Permiso denegado en SFTP:**
   - Contacta soporte GoDaddy
   - Referencia: "ticket-cd-repo-005"

2. **Plugin persiste después de eliminación:**
   - Limpia caché: `wp cache flush`
   - Reconstruye opciones: `wp wp-cli cache flush all`
   - Si problema persiste: abre issue GitHub #[N] con detalles

3. **Necesitas rollback:**
   - Si tenías backup de plugin (de antes de eliminar)
   - Restaura: `tar -xzf backups/wp-file-manager-backup-*.tar.gz`
   - Re-activa en wp-admin

---

## 🔄 Periodicidad

**Cuándo repetir esta tarea:**
- ✅ Ahora (verificación inicial)
- ✅ Después de cada actualización masiva de plugins
- ✅ Mensualmente (monitoreo seguridad)
- ✅ Post-deploy a producción

**Checklist de monitoreo mensual:**
```bash
# Ejecutar mensualmente en inicio de mes:
wp plugin list --status=all | grep -i "file-manager"
# Debe devolver: (vacío)
```

---

**Generado por:** Claude Dispatch (cd-repo-005 security hardening checklist)  
**Última actualización:** 2026-04-19 22:00 UTC  
**Próxima tarea:** cd-content-002 (sin bloqueantes, ejecutable post cd-repo-002)
