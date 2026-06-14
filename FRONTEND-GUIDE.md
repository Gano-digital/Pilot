# Frontend Developer Guide — Gano Digital

**Para:** Sergio Ean (Frontend Developer)
**Última actualización:** 2026-04-10
**Estado:** WordPress 6.9.4 + Elementor 3.35.7 + gano-child theme

---

## 🚀 Quick Start

### Acceso a WordPress
```
URL: https://gano.digital/wp-admin
Usuario: sergio
Contraseña: (Diego te proporciona)
```

### Cambiar contraseña (primera vez)
1. Entra a wp-admin
2. Arriba a la derecha → Tu nombre → Mi perfil
3. Desplázate a "Nueva contraseña" → Cambia
4. Guarda

---

## 📝 Tareas Comunes

### 1. Editar una página existente
```
Dashboard → Páginas → (busca la página) → Editar
```
- Se abre **Elementor Frontend Builder** (editor visual)
- Arrastra widgets, cambia colores, tipografía
- Click **Actualizar** para guardar

### 2. Crear página nueva
```
Dashboard → Páginas → Añadir Nueva
  → Titulo + contenido
  → Elementor (botón "Editar con Elementor")
  → Arrastra bloques
  → Publicar
```

### 3. Cambiar CSS de una página
En Elementor (mientras editas):
```
Página Settings (engranaje) → Advanced → Custom CSS
```
Ejemplo:
```css
.elemento-customizado {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
  border-radius: 8px;
}
```

### 4. Cambiar colores/tipografía globales
```
Dashboard → Elementor → Global Colors
        o → Global Fonts
```
Cualquier cambio se refleja en todo el sitio.

### 5. Subir imágenes optimizadas
En Elementor Image widget:
```
Click imagen → Upload
  → Formatos: WebP si es posible (más rápido)
  → Tamaño: <500KB para web
  → Alt text (importante para SEO)
```

---

## 📁 Estructura de Archivos

Ubicación en servidor: `/home/$SERVER_USER/public_html/gano.digital/`

```
gano.digital/
├── wp-admin/                      ← WordPress core (no toques)
├── wp-includes/                   ← WordPress core (no toques)
├── wp-content/
│   ├── themes/
│   │   ├── gano-child/            ← 🎯 TU TEMA ACTIVO
│   │   │   ├── functions.php      ← Lógica PHP (rate limit, CSP, headers)
│   │   │   ├── style.css          ← Estilos globales
│   │   │   ├── templates/         ← Plantillas de página (page-seo-landing.php, etc.)
│   │   │   ├── inc/               ← Funciones modulares
│   │   │   └── js/                ← JavaScript personalizado
│   │   └── royal-elementor-kit/   ← Tema parent (no edites)
│   │
│   ├── plugins/
│   │   ├── elementor/             ← Builder visual (no toques internals)
│   │   ├── gano-phase1-7/         ← 🚫 INSTALADORES (NUNCA eliminar)
│   │   ├── gano-reseller-enhance/ ← Filtros Reseller Store
│   │   └── ... (otros plugins)
│   │
│   └── mu-plugins/
│       ├── gano-security.php      ← 🔐 CSP, rate limiting, protección
│       ├── gano-seo.php           ← Schema JSON-LD, Open Graph
│       ├── gano-agent-core.php    ← Integración agentes IA
│       └── gano-frontend-guide.php ← 📚 Esta guía (dashboard widget)
│
├── wp-config.php                  ← 🚫 BD + secretos (no toques)
├── .htaccess                      ← 🚫 Reescrituras + bloqueos (no toques)
└── index.php                       ← WordPress entry point (no toques)
```

---

## ✅ Tareas que SÍ puedes hacer

- ✅ Editar/crear/publicar páginas
- ✅ Editar CSS personalizado
- ✅ Cambiar colores/tipografía globales
- ✅ Subir imágenes y media
- ✅ Instalar plugins nuevos (si Diego aprueba)
- ✅ Cambiar opciones Elementor
- ✅ Ver/responder comentarios

---

## ❌ Cosas que NO toques (o avisa a Diego)

| Cosa | Por qué | Qué hacer si necesitas |
|------|---------|------------------------|
| `gano-phase1-7` plugins | Son instaladores de contenido | Avisa a Diego |
| `wp-config.php` | Contraseña BD + secretos | Diego solo |
| `.htaccess` | Reglas críticas de seguridad | Diego solo |
| `gano-security.php` | Core de seguridad | Diego solo |
| `gano-seo.php` | Schema JSON-LD crítico | Consulta a Diego |
| Core WordPress | Versión + actualizaciones | Diego manages |

---

## 🐛 Troubleshooting

### "Ver sitio" no funciona desde wp-admin
**Problema:** Error 403 al hacer click "Ver sitio"
**Solución:** Es normal (firewall bloquea herramientas). Abre manualmente `https://gano.digital`

### Cambios en Elementor no se ven
**Solución:**
1. Click **Actualizar** en Elementor
2. Cierra y reabre navegador (CTRL+SHIFT+R)
3. Borra caché si está activado (Dashboard → WP Rocket o similar)

### Imagen se ve cortada en mobile
**Solución:** En Elementor widget imagen:
1. Image → Responsive → Mobile
2. Ajusta tamaño/escala

### Necesito agregar JavaScript personalizado
**Opción 1 (simple):** Elementor → Custom CSS (también soporta `<script>`)
**Opción 2 (mejor):** Edita `/wp-content/themes/gano-child/js/` y Diego despliega

---

## 🔗 Recursos Útiles

| Recurso | URL |
|---------|-----|
| **Elementor Docs** | https://elementor.com/help/ |
| **Elementor Widgets** | https://elementor.com/widgets/ |
| **WordPress Codex** | https://developer.wordpress.org/ |
| **Repo GitHub** | https://github.com/Gano-digital/Pilot |
| **Contacto Diego** | diego_r_95@hotmail.com |

---

## 📞 ¿Necesitas ayuda?

1. **Error en página:** Screenshot + describe qué pasó → Diego
2. **¿Puedo hacer X?:** Pregunta a Diego en GitHub Issues
3. **Cambios no se ven:** Borra caché + recarga
4. **Elemental roto:** Refresca página, si persiste avisa a Diego

---

**Bienvenido a Gano Digital, Sergio! 🚀**
