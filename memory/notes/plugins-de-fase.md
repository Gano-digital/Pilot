# Nota Crítica: Plugins de Fase — NO eliminar prematuramente

**Fecha de nota**: Marzo 18, 2026
**Última revisión**: 2026-04-17
**Confirmado por**: Diego (instrucción explícita)

> **Runbook operativo**: ver `memory/ops/runbook-activacion-wp-admin-2026-04-16.md`
> **Orden oficial**: `phase1 → phase2 → phase3 → content-importer → phase6 → phase7 → reseller-enhancements` (permanente).
> **Script WP-CLI**: `scripts/activate-gano-phases.sh` (úsese sólo cuando haya SSH + secretos configurados).

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

Última revisión: **2026-04-17**. El runbook wp-admin actualiza esta tabla tras cada corrida.

| Plugin | Rol | ¿Activado en WP? | ¿Contenido confirmado? | ¿Listo para desactivar? | ¿Listo para eliminar? |
|--------|-----|-----------------|------------------------|------------------------|----------------------|
| phase1-installer | one-shot | ❓ Por verificar | ❓ MU gano-security.php + .htaccess | ❌ NO (hasta confirmar) | ❌ NO |
| phase2-business | one-shot | ❓ Por verificar | ❓ WooCommerce COP/Bogotá | ❌ NO | ❌ NO |
| phase3-content | one-shot | ❓ Por verificar | ❓ Páginas base (Home/Contacto/etc.) | ❌ NO | ❌ NO |
| content-importer | one-shot | ❓ Por verificar | ❓ 20 páginas SOTA en Borrador | ❌ NO | ❌ NO |
| phase6-catalog | persistente | ❓ Por verificar | ❓ Los 4 ecosistemas WooCommerce | 🔒 Mantener activo | ❌ NO |
| phase7-activator | persistente | ❓ Por verificar | ❓ Menú + templates | 🔒 Mantener activo | ❌ NO |
| reseller-enhancements | persistente | ✅ Activo (2026-04-17) | ✅ Filtros carrito + panel PFID | 🔒 Mantener activo | ❌ NO |
| **wp-file-manager** | ⚠️ CVE | ❓ Verificar | N/A | ✅ DESACTIVAR YA | ✅ **ELIMINAR YA** |

**Información que falta** (tras esta revisión):
- 8 PFIDs de RCC — introducir vía `Ajustes → Gano Reseller` (panel creado 2026-04-17).
- `GANO_API_TOKEN` + `GANO_AGENT_API_KEY` — opcionales, en `wp-config.php` del servidor.

**Fixes aplicados 2026-04-17** (rama main):
- `functions.php` — PFIDs ahora leen de `get_option(...)` con fallback `PENDING_RCC`.
- `functions.php` — eliminada referencia literal `TU_TOKEN_AQUÍ`.
- `gano-p6-security-audit.php` — default `dev-local-key-123` eliminado; 503 si no configurada + admin notice.
- `page-ecosistemas.php` — eliminadas 9 cadenas placeholder ("por confirmar", "a definir", etc.).

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
