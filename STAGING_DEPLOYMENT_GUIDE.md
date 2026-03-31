# 🚀 GUÍA DE DEPLOYMENT A STAGING — gano-content-importer v2.0

**Fecha:** 19 de Marzo 2026
**Plugin:** Gano Digital Content Hub Importer v2.0
**Tiempo Estimado:** 10 minutos
**Status:** ✅ Listo para activación

---

## 📋 Pre-Requisitos

✅ Acceso SSH/SFTP al servidor staging
✅ WordPress 5.9+ con PHP 7.4+
✅ Backup reciente del sitio staging
✅ El staging site está en: `https://staging.gano.digital` (o similar)

---

## 🔧 Instalación Paso a Paso

### OPCIÓN A: VÍA SFTP (Recomendado)

#### 1️⃣ Conectar por SFTP
```bash
# Usar cliente SFTP (Cyberduck, FileZilla, WinSCP)
Host: [tu-hosting].com
User: [tu-usuario]
Port: 22 (SFTP)
```

#### 2️⃣ Navegar al Directorio de Plugins
```
/home/[usuario]/public_html/staging/wp-content/plugins/
```

#### 3️⃣ Copiar el Plugin
**Origen local:**
```
/wp-content/plugins/gano-content-importer/
```

**Destino remoto:**
```
/home/[usuario]/public_html/staging/wp-content/plugins/gano-content-importer/
```

**Archivos a copiar:**
- ✅ `gano-content-importer.php` (55KB) — Plugin principal
- ✅ `js/scroll-reveal.js` (4.2KB) — Animaciones scroll
- ✅ `README.md` (8.8KB) — Documentación
- ✅ No necesitas copiar `gano-content-importer-v2.0.php` (es backup)

#### 4️⃣ Copiar el CSS al Theme
**Origen local:**
```
/wp-content/themes/gano-child/gano-sota-animations.css
```

**Destino remoto:**
```
/home/[usuario]/public_html/staging/wp-content/themes/gano-child/gano-sota-animations.css
```

**Archivo:**
- ✅ `gano-sota-animations.css` (14KB) — Estilos de animaciones

---

### OPCIÓN B: VÍA SSH (Si tienes acceso terminal)

```bash
# 1. Conectar al servidor
ssh usuario@hosting.com

# 2. Navegar al directorio de staging
cd /home/usuario/public_html/staging/wp-content/plugins/

# 3. Descargar el plugin (si está en tu servidor o repo)
# O copiar desde backup existente:
cp -r gano-content-importer/ gano-content-importer-backup-old/

# 4. Reemplazar con versión v2.0
# (Aquí irían los comandos de tu despliegue específico)

# 5. Asegurar permisos
chmod 755 gano-content-importer/
chmod 644 gano-content-importer/*.php
chmod 644 gano-content-importer/js/*.js
```

---

## ✅ Activación en WordPress (Panel Admin)

### 1️⃣ Acceder a WordPress Admin
```
https://staging.gano.digital/wp-admin
```

### 2️⃣ Ir a Plugins
```
Dashboard → Plugins → Plugins Instalados
```

### 3️⃣ Buscar "Gano Digital Content Hub Importer"
Deberías ver:
```
Gano Digital — Content Hub Importer v2.0
Importa automáticamente las 20 páginas del Hub...
Version: 2.0.0
Author: Gano Digital Dev
```

### 4️⃣ Hacer Click en "Activar"
```
[Activar] ← Click aquí
```

⏳ **Esperando...** El plugin ejecutará el activation hook que:
- Crea 20 nuevas páginas (DRAFT status)
- Asigna categorías (infraestructura, seguridad, etc)
- Carga featured images
- Genera metadata y CTAs

**Tiempo:** 3-5 segundos

### 5️⃣ Confirmar Creación de Páginas
Después de activar:
```
Dashboard → Páginas → Todas las Páginas
```

Deberías ver 20 nuevas páginas con estado **DRAFT**:
```
✓ Arquitectura NVMe: La Muerte del SSD Tradicional — DRAFT
✓ Zero-Trust Security: El Fin de las Contraseñas — DRAFT
✓ Gestión Predictiva con AI: Cero Caídas, Cero Sorpresas — DRAFT
... (17 más)
```

---

## 🎨 Testing Visual en Staging

### 1️⃣ Publicar Una Página de Prueba
```
Ir a Todas las Páginas
→ Editar primera página (Arquitectura NVMe)
→ Cambiar estado de DRAFT a PUBLICADA
→ Click "Actualizar"
```

### 2️⃣ Ver la Página
```
https://staging.gano.digital/?page_id=XXX
```

### 3️⃣ Verificar Visualmente
- ✅ ¿Aparecen las animaciones al scroll?
  - Título baja con gradiente
  - Bullets aparecen con stagger (0.2s de delay)

- ✅ ¿Se ve responsive en móvil?
  - Chrome DevTools → F12 → Toggle device toolbar
  - Prueba: 480px, 768px, desktop

- ✅ ¿Están los estilos aplicados?
  - Colores: #6366f1 (indigo), #8b5cf6 (purple)
  - Botones verdes: #84cc16 (lime)

- ✅ ¿Funcionan las CTAs?
  - Click en "Descubre Más" debe ir a plan relacionado

### 4️⃣ Verificar Performance
```
Chrome DevTools → Lighthouse
```

Deberías ver:
- ⚡ Performance: 85+/100
- ♿ Accessibility: 90+/100
- 📱 Best Practices: 90+/100
- 🔒 SEO: 95+/100

---

## 🧪 Testing Responsivo

### Desktop (1920px)
```
✓ Dos columnas de contenido
✓ Títulos grandes (h1: 2.5rem)
✓ Stats visibles en cuadro derecho
```

### Tablet (768px)
```
✓ Stack single-column
✓ Padding reducido (30px)
✓ Títulos adaptan a 1.8rem
✓ Stats en fila compacta
```

### Mobile (480px)
```
✓ Stack single-column
✓ Padding mínimo (20px)
✓ Títulos pequeños (1.4rem)
✓ Botones toca-friendly (48px mín height)
```

---

## 🎬 Verificar Animaciones

En la consola del navegador:
```javascript
// Debe mostrar "Scroll reveal initialized"
// Si ves esto, las animaciones están activas ✅
```

**Manual:**
1. Abre DevTools (F12)
2. Ve a Console
3. Recarga la página
4. Deberías ver: `Scroll reveal initialized ✅`

---

## 📊 Verificar Schema.org (SEO)

En la consola:
```javascript
// Busca <script type="application/ld+json">
// Debe contener schema para:
// - ArticleSchema
// - OrganizationSchema
// - FAQPageSchema
```

O usa: https://schema.org/validator/

---

## 🔄 Próximos Pasos Post-Staging

### ✅ Si todo se ve bien:
1. Publicar todas las 20 páginas (cambiar de DRAFT a LIVE)
2. Verificar URLs amigables (sin ?page_id=)
3. Ejecutar SEO audit completo
4. Hacer copia de backup
5. Hacer deploy a PRODUCCIÓN

### ❌ Si hay problemas:
1. Revisar error_log: `/wp-content/debug.log`
2. Desactivar plugin
3. Restaurar backup
4. Contactar soporte con screenshot del error

---

## 📝 Checklist de Validación

```
⬜ Plugin instalado en /wp-content/plugins/gano-content-importer/
⬜ CSS instalado en /wp-content/themes/gano-child/gano-sota-animations.css
⬜ Plugin activado en WordPress admin
⬜ 20 páginas creadas con estado DRAFT
⬜ Primera página publicada y visible
⬜ Animaciones funcionan al scroll
⬜ Responsive en 480px, 768px, desktop
⬜ Schema.org válido
⬜ Performance score >85/100
⬜ Accessibility score >90/100
⬜ CTAs dinámicas funcionan
⬜ Stats visibles en páginas
⬜ Colores correctos (indigo #6366f1, purple #8b5cf6)
```

---

## 📞 Soporte

Si algo falla:
1. Revisa `/wp-content/debug.log`
2. Verifica que PHP 7.4+ esté instalado
3. Verifica que WordPress 5.9+ esté instalado
4. Desactiva otros plugins para descartar conflicto
5. Contacta soporte con:
   - Mensaje de error exacto
   - Screenshot del problema
   - Versión de WordPress y PHP

---

## 🎉 ¡Éxito!

Una vez que todo funcione en staging, tendrás:
- ✅ 20 páginas SOTA con contenido estratégico
- ✅ Animaciones fluidas CSS
- ✅ Mejor UX (score 8/10)
- ✅ Mejor engagement
- ✅ Accesibilidad WCAG AA
- ✅ SEO optimizado con schema

**Tiempo total:** ~35 minutos (instalación + testing)

¡Listo para go-live! 🚀
