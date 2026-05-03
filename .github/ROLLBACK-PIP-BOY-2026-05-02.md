# Rollback Pip-Boy — 2026-05-02

**Rama:** `rollback/pip-boy-removal-2026-05-02`  
**Fecha:** 2026-05-02  
**Motivo:** Revertir cambios visuales Pip-Boy (Phases 1-3) manteniendo integridad de datos

---

## ✅ Completado

### Frontend
- ✅ **Homepage:** `front-page.php` reemplazado con versión simplificada (3 tarjetas)
- ✅ **Plugins Desactivados:**
  - gano-phase1-installer
  - gano-phase2-business
  - gano-phase3-content
  - gano-phase6-catalog
  - gano-phase7-activator
  - reseller-store
- ✅ **CSS Comentadas en functions.php:**
  - gano_enqueue_premium_styles (línea 36)
  - gano_enqueue_status_hud (línea 110) — elimina HUD inferior
  - gano_enqueue_hero_holograma (línea 204)
  - gano_enqueue_scroll_animations (línea 238)
  - gano_enqueue_particles (línea 270)
- ✅ **CSS Custom:** Nuevo archivo `css/custom-rollback.css` para override de estilos
- ✅ **Resultado:** Sitio con fondo blanco, contenido visible, sin elementos Pip-Boy visuales

### Visibilidad
- ✅ Página principal muestra: "Soluciones WordPress para Colombia"
- ✅ 3 tarjetas: Hosting Profesional, Dominios Locales, Seguridad SOTA
- ✅ CTA verde: "Comenzar Ahora"
- ✅ Botón contacto funcional

---

## 🔒 Conservado (No Eliminado)

### Backend
- ✅ **Base de datos:** Intacta
- ✅ **60 Páginas creadas 28-29 abril:** Mantenidas (contenido valioso)
  - Features: Whitepaper, Coming Soon, Roadmap, Press, Jobs, Partners, Academy, etc.
  - Servicios: Security, Speed, SEO, E-Commerce, Mobile, Testimonios, Case Studies, etc.
  - Legales: Privacy, Cookies, Terms, API Documentation
  - Todas las páginas están **publicadas** en la BD
- ✅ **Posts/Content:** 116 posts totales intactos

### Código
- ✅ **Plugins gano-*** desactivados pero **no eliminados** (recuperables)
- ✅ **functions.php comentado, no borrado** — backup en `.bak` en servidor
- ✅ **CSS archivos intactos** — solo no encolados

---

## 📦 Archivado (Vacío o No Utilizado)

### Elementos sin contenido o requerimiento actual
- **Icono amarillo Pip-Boy:** Pendiente de ubicación precisa (cosmético, no afecta)
- **Algunos shortcodes Pip-Boy:** Comentados en functions.php, recuperables

---

## 🔄 Cambios en Servidor

### SSH: 72.167.102.145 (f1rml03th382)

| Ruta | Cambio | Estado |
|------|--------|--------|
| `wp-content/themes/gano-child/front-page.php` | Reemplazado con versión simplificada | ✅ Activo |
| `wp-content/themes/gano-child/functions.php` | 5 funciones enqueue comentadas | ✅ Activo |
| `wp-content/themes/gano-child/css/custom-rollback.css` | Nuevo archivo CSS override | ✅ Activo |
| `wp-content/themes/gano-child/functions.php.backup` | Backup del original | 🔒 Seguro |

### WP-CLI: Plugins Desactivados
```bash
wp plugin list
```
- gano-phase1-installer: INACTIVE
- gano-phase2-business: INACTIVE
- gano-phase3-content: INACTIVE
- gano-phase6-catalog: INACTIVE
- gano-phase7-activator: INACTIVE
- reseller-store: INACTIVE

**Activos:** gano-reseller-enhancements, seo-by-rank-math, wordfence (inactivo)

---

## ✏️ Próximos Pasos

1. **Merge a main:** PR desde esta rama con revisión
2. **Documentación:** Este archivo + notas en MEMORY.md
3. **Monitoreo:** Verificar rendimiento del sitio sin Pip-Boy CSS
4. **Recuperación:** Si se necesita restaurar algo, todos los cambios están documentados

---

## 📸 Verificación

**Comando para verificar estado en servidor:**
```bash
ssh f1rml03th382@72.167.102.145 \
  "cd /home/f1rml03th382/public_html/gano.digital && \
   echo '=== PLUGINS ===' && \
   wp plugin list --allow-root | grep inactive && \
   echo '=== FRONTEND ===' && \
   head -5 wp-content/themes/gano-child/front-page.php"
```

**Esperado:**
- ✅ 6 plugins inactivos
- ✅ front-page.php comienza con "<?php /**"
- ✅ Sitio carga sin errores en https://gano.digital

---

**Creado por:** Claude Code  
**Rama:** rollback/pip-boy-removal-2026-05-02  
**Estado:** Listo para merge a main
