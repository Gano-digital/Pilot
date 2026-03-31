# GoDaddy Reseller — Referencia de Contenido Marketing
**Extraído del XML export | Marzo 19, 2026**

---

## 📋 Estructura de Página Propuesta Comercial

### Secciones Principales
1. **Hero/Intro** — Propuesta de valor general
2. **Nuestros Servicios** — Grid de 8 categorías de productos
3. **Planes de Hosting** — Pricing y especificaciones
4. **Servidores VPS** — Opciones de alto rendimiento
5. **Certificados SSL** — Seguridad
6. **Protección/Seguridad del Sitio** — Malware y amenazas
7. **Por Qué Nosotros** — Ventajas competitivas
8. **CTA Final** — Llamada a acción

---

## 🎨 Paleta de Color y Diseño

**Colores Primarios:**
- Gradiente principal: `#6366f1 → #8b5cf6 → #a855f7` (Indigo a Púrpura)
- Hero BG: Gradiente oscuro `#0f0c29 → #302b63 → #24243e`
- Texto primario: `#1a1a2e` (casi negro)
- Texto secundario: `#64748b` (gris azulado)
- Fondo claro: `#f8fafc`

**Tipografía:**
- Familia: Segoe UI, system-ui, -apple-system, sans-serif
- Headings: Font-weight 800, tamaños fluidos (`clamp()`)
- Body: Font-weight 400-700

**Efectos:**
- Bordes redondeados: 16px–50px (pills para botones)
- Sombras: `0 4px 20px rgba(0,0,0,0.06)` hasta `0 12px 40px rgba(99,102,241,0.15)`
- Hover: Translate Y(-4px a -6px), shadow elevation
- Transiciones: 0.3s ease

---

## 📦 CATÁLOGOS DE PRODUCTOS

### 1. **Hosting Web (cPanel)**

| Plan | Specs | Precio |
|------|-------|--------|
| cPanel Deluxe | — | $9.99/mes |
| cPanel Ultimate | — | $12.99/mes |

### 2. **Web Hosting Plus (Comercial)**

| Plan | Detalles | Precio |
|------|----------|--------|
| Inicio WHP | Entrada de Web Hosting Plus | $21.99/mes |
| Mejora WHP | Escalable para empresas | $38.99/mes |
| Crecimiento WHP | Medio-alto tráfico | $54.99/mes |
| Expansión WHP | Alto rendimiento | $76.99/mes |

### 3. **VPS Estándar (Autoadministrado)**

Equipamiento 1-16 vCPU, 1-32 GB RAM
- VPS 1vCPU/1GB: $4.99/mes
- VPS 2vCPU/4GB: $25.99/mes
- VPS 4vCPU/8GB: $50.99/mes
- VPS 8vCPU/16GB: $87.99/mes
- VPS 16vCPU/32GB: $146.99/mes

### 4. **VPS High Performance**

Para aplicaciones empresariales
- VPS HP 2vCPU/8GB: $38.99/mes
- VPS HP 4vCPU/16GB: $63.99/mes
- VPS HP 8vCPU/32GB: $123.99/mes
- VPS HP 16vCPU/64GB: $187.99/mes
- VPS HP 32vCPU/128GB: $255.99/mes

### 5. **Certificados SSL**

**DV (Domain Validated) — Blogs, pequeñas empresas**
- 1 sitio: $33.99/año
- 5 sitios: $61.99/año
- Comodín: $214.99/año

**EV (Extended Validation) — Ecommerce, banca**
- 1 sitio: $110.99/año
- 5 sitios: $262.99/año

**Servicios SSL Administrados**
- SSL DV Administrado: $144.99/año
- SSL SAN Administrado: $231.99/año
- Instalación (1 sitio): $27.99
- Instalación (5 sitios): $109.99
- Instalación (10 sitios): $177.99
- Instalación (25 sitios): $341.99

### 6. **Seguridad del Sitio Web**

- Escaneo de malware
- Eliminación de amenazas
- Protección activa
- **Seguridad Esencial**: $3.99/mes

---

## 🎯 MENSAJERÍA Y PROPUESTA DE VALOR

**Headline Principal:**
> "✦ Plan Reseller GoDaddy Certificado"
> "Soluciones Digitales para Tu Negocio"

**Subtítulo:**
> "Hosting, dominios, seguridad, email profesional, marketing y más — todo respaldado por la infraestructura líder de GoDaddy. Sin complicaciones, con soporte real."

**Estadísticas/Credibilidad (Hero Section):**
- 56+ Productos disponibles
- 99.9% Uptime garantizado
- 24/7 Soporte técnico
- GoDaddy Partner certificado

**Descripción de Servicios:**
> "Desde hosting compartido hasta servidores VPS, correo profesional, certificados SSL y herramientas de marketing digital."

---

## 🛍️ CATEGORÍAS DE SERVICIOS (8 items)

1. 🖥️ **Hosting Web** — cPanel, WordPress y Hosting Plus
2. ⚡ **Servidores VPS** — Alta potencia, recursos dedicados
3. 🌐 **Dominios** — Registro de .com, .net y más
4. 🔒 **Certificados SSL** — DV, EV y servicios administrados
5. 🛡️ **Seguridad Web** — Protección y backups del sitio
6. 📧 **Email Profesional** — Microsoft 365 y correo corporativo
7. 📈 **Marketing Digital** — Email marketing y SEO
8. 🎨 **Creador Web** — Construye tu sitio sin código

---

## 🎨 COMPONENTES UI A REUTILIZAR

### Botones
- **Primary**: Gradiente indigo-púrpura, padding 14px 32px, border-radius 50px
- **Secondary**: Fondo transparente con borde, backdrop blur
- **White CTA**: Fondo blanco sobre gradiente, padding 16px 40px
- **Outline**: Borde blanco, fondo transparente en hover

### Cards
- **Categoría/Servicio**: max-width 220-280px, shadow on hover, translateY(-6px)
- **Producto/Precio**: 260px min-width, top accent bar (4px gradient), product-type badge
- **Why/Benefit**: Dark background with white text, icon + title + description

### Grids
- Categorías: `grid-template-columns: repeat(auto-fit, minmax(220px, 1fr))`
- Productos: `repeat(auto-fill, minmax(260px, 1fr))`
- Responsive: 2 cols en tablet, 1 col en mobile

### Hero Section
- Full-width gradient background
- Centered text (h1, p, buttons)
- Stats grid below (4 stats: 56+, 99.9%, 24/7, Partner)
- Icons/emojis for visual interest

---

## 📊 NOTAS PARA GANO.DIGITAL

### Aplicable a tu proyecto:
1. **Estructura de pricing** — 6 niveles de VPS con clara diferenciación de specs/precio ✅
2. **Grid layout patterns** — Reutilizable para tus 4 ecosistemas de hosting
3. **Hero + stats + servicios** — Template limpio y moderno para landing pages
4. **Color palette** — Indigo-púrpura funciona bien para B2B tech
5. **Typography approach** — `clamp()` para responsive, buena escalabilidad
6. **Icon usage** — Emojis funcionan bien en headings de producto

### NO está en el XML (necesitarías del servidor):
- Funcionalidad de compra/carrito
- Integración con APIs de GoDaddy
- Gestión de dominios
- Provisioning automático de VPS
- Facturación/billing

---

## 📝 METADATA

| Campo | Valor |
|-------|-------|
| **URL Original** | https://l9w.f71.myftpupload.com/propuesta-comercial/ |
| **Autor** | 665375pwpadmin |
| **Fecha Export** | 2026-03-19 |
| **Slug** | propuesta-comercial |
| **Categoría** | Reseller |
| **Estado** | Publicado |

---

**Recomendación:** Los datos de precios y specs son útiles para estructura, pero tus precios en COP y productos específicos deben definirse en tu WHMCS. Este template es un buen punto de partida para el sitio web de ventas (gano.digital).
