# Nota Crítica: Plugins de Fase — NO eliminar prematuramente

**Fecha de nota**: Marzo 18, 2026
**Confirmado por**: Diego (instrucción explícita)

## Cuáles son

Los siguientes plugins en `wp-content/plugins/` son **instaladores del sitio web**:

```
gano-phase1-installer/
  └── gano-phase1-installer.php   ← Instalación base del sitio
gano-phase2-business/
  └── gano-phase2-business.php    ← Configuración de negocio
gano-phase3-content/
  └── gano-phase3-content.php     ← Contenido inicial del sitio
gano-phase6-catalog/
  └── gano-phase6-catalog.php     ← Catálogo de productos WooCommerce (4 ecosistemas)
gano-phase7-activator/
  └── gano-phase7-activator.php   ← Activador de funcionalidades
```

También hay archivos de configuración de seguridad listos para instalar:
```
gano-phase1-installer/
  ├── gano-security.php.txt       ← Futuro MU plugin (renombrar a .php en /mu-plugins/)
  └── htaccest-security.txt       ← Reglas .htaccess de seguridad
```

## Por qué NO eliminarlos aún

Estos plugins funcionan como **instaladores one-time**. Al activarlos en WordPress
ejecutan hooks (`register_activation_hook`) que crean productos, configuran ajustes,
importan contenido, etc. Si se eliminan SIN activar primero, el sitio perderá esa
configuración y habría que recrearla manualmente.

**Flujo correcto:**
1. Ir a WordPress Admin → Plugins
2. Activar cada plugin de fase (en orden: 1, 2, 3, 6, 7)
3. Confirmar que el contenido/configuración de esa fase aparece en el sitio
4. Documentar qué instaló cada fase
5. SOLO ENTONCES desactivar y eliminar

## Qué SÍ se puede eliminar sin problema

`wp-file-manager` — este NO es un plugin de fase. Es un gestor de archivos con
historial de CVEs críticos (CVE-2020-25213, CVSS 10.0). Eliminar inmediatamente.

## Estado de verificación de cada fase

| Plugin | ¿Activado en WP? | ¿Contenido confirmado? | ¿Listo para eliminar? |
|--------|-----------------|------------------------|----------------------|
| phase1-installer | ❓ Por verificar | ❓ | ❌ NO |
| phase2-business | ❓ Por verificar | ❓ | ❌ NO |
| phase3-content | ❓ Por verificar | ❓ | ❌ NO |
| phase6-catalog | ❓ Por verificar | ❓ Los 4 ecosistemas existen | ❌ NO |
| phase7-activator | ❓ Por verificar | ❓ | ❌ NO |
| **wp-file-manager** | ✅ Activo (problema) | N/A | ✅ **SÍ ELIMINAR YA** |

## Referencia de código (phase6-catalog como ejemplo)

El hook de activación del phase6 importa los 4 ecosistemas de hosting:
```php
register_activation_hook( __FILE__, 'gano_p6_import_catalog' );
function gano_p6_import_catalog() {
    // Crea los 4 productos: Startup Blueprint, Ecosistema Básico,
    // Ecosistema Avanzado, Soberanía Digital
    // Si ya existen por SKU, los salta (idempotente)
}
```
