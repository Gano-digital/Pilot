# Arquitectura de información (IA) — Gano Digital · Wave 3

**Versión:** 1.0 · Abril 2026  
**Referencia:** Brief oleada 3 §4 — `memory/research/gano-wave3-brand-ux-master-brief.md`  
**Regla cardinal:** ningún camino de conversión supera **3 clics** desde Home.

---

## 1. Árbol de rutas completo

```
/ (Home)
├── /ecosistemas                       ← Hub principal de conversión
│   ├── /ecosistemas/nucleo-prime      ← Plan de entrada
│   ├── /ecosistemas/fortaleza-delta   ← Plan medio
│   └── /ecosistemas/bastion-sota      ← Plan premium
│
├── /pilares                           ← Hub SEO (landing de índice)
│   ├── infraestructura/
│   │   ├── /arquitectura-nvme-la-muerte-del-ssd-tradicional
│   │   ├── /la-muerte-del-hosting-compartido-el-riesgo-invisible
│   │   ├── /edge-computing-contenido-a-cero-distancia-de-tu-cliente
│   │   ├── /backups-continuos-en-tiempo-real-tu-maquina-del-tiempo
│   │   ├── /escalamiento-elastico-sobrevive-a-tu-propio-exito-viral
│   │   └── /alta-disponibilidad-ha-la-infraestructura-indestructible
│   │
│   ├── seguridad/
│   │   ├── /zero-trust-security-el-fin-de-las-contrasenas
│   │   ├── /mitigacion-ddos-inteligente-firewall-de-nueva-generacion
│   │   └── /cifrado-post-cuantico-la-boveda-del-futuro
│   │
│   ├── rendimiento/
│   │   ├── /headless-wordpress-la-velocidad-absoluta
│   │   ├── /green-hosting-infraestructura-sostenible-para-tu-negocio
│   │   ├── /cicd-automatizado-nunca-mas-rompas-tu-tienda-en-vivo
│   │   ├── /skeleton-screens-la-psicologia-de-la-velocidad-percibida
│   │   ├── /micro-animaciones-e-interacciones-hapticas-diseno-que-se-siente
│   │   └── /http3-y-quic-el-protocolo-que-rompe-la-congestion
│   │
│   ├── inteligencia-artificial/
│   │   ├── /gestion-predictiva-con-ai-cero-caidas-cero-sorpresas
│   │   ├── /self-healing-el-ecosistema-que-se-cura-solo
│   │   └── /el-agente-ia-de-administracion-tu-infraestructura-habla-espanol
│   │
│   └── estrategia/
│       ├── /soberania-digital-en-latam-tus-datos-tu-control
│       └── /analytics-server-side-privacidad-velocidad-y-datos-reales
│
├── /hosting-wordpress-colombia        ← Landing SEO dedicada
│
├── /nosotros                          ← Hub de confianza
│
├── /contacto                          ← Hub de soporte / pre-venta
│
└── /legal                             ← Hub legal
    ├── /legal/terminos-y-condiciones
    ├── /legal/politica-de-privacidad
    └── /legal/acuerdo-de-nivel-de-servicio
```

---

## 2. Mapa de profundidad de clics (desde Home)

| Destino | Clic 1 | Clic 2 | Clic 3 | Profundidad |
|---------|--------|--------|--------|-------------|
| Ecosistemas (índice) | `/ecosistemas` | — | — | 1 |
| Plan específico (ej. Bastión SOTA) | `/ecosistemas` | `/ecosistemas/bastion-sota` | — | 2 |
| **Checkout / carrito** (GoDaddy Reseller) | `/ecosistemas` | Plan | Iniciar compra | **3** ✅ |
| Pilar SEO individual | `/pilares` | Categoría | Pilar | **3** ✅ |
| Nosotros | `/nosotros` | — | — | 1 |
| Contacto | `/contacto` | — | — | 1 |
| Legal (documento) | `/legal` | Documento | — | 2 |
| Landing SEO Colombia | `/hosting-wordpress-colombia` | — | — | 1 |

> Ningún camino supera 3 clics. ✅

---

## 3. Tecnología de cada página: Elementor vs PHP

### 3.1 Páginas Elementor (editor visual — cambios requieren wp-admin)

| Ruta | Notas |
|------|-------|
| `/` (Home) | Plantilla dedicada Elementor; clases CSS en `memory/content/elementor-home-classes.md` |
| `/ecosistemas` (índice hub) | Elementor — sección de presentación de los tres planes |
| `/ecosistemas/nucleo-prime` | Elementor — tarjeta de plan con CTA Reseller |
| `/ecosistemas/fortaleza-delta` | Elementor — tarjeta de plan con CTA Reseller |
| `/ecosistemas/bastion-sota` | Elementor — tarjeta de plan con CTA Reseller |
| `/nosotros` | Elementor — manifiesto + equipo (pendiente contenido real) |
| `/contacto` | Elementor — formulario WP/CF7 + datos de contacto `[teléfono]` `[email]` |
| `/legal/terminos-y-condiciones` | Elementor o editor clásico |
| `/legal/politica-de-privacidad` | Elementor o editor clásico |
| `/legal/acuerdo-de-nivel-de-servicio` | Elementor o editor clásico |
| `/pilares` (índice de categorías) | Elementor — grid de 4 categorías con card visual |

> **Limitación:** los agentes de código no modifican Elementor/BD directamente. Los cambios de contenido en estas páginas requieren wp-admin → Elementor editor.

### 3.2 Páginas con plantilla PHP (tema hijo `gano-child`)

| Ruta | Plantilla | Archivo |
|------|-----------|---------|
| `/ecosistemas` (catálogo completo + animaciones) | `shop-premium.php` | `wp-content/themes/gano-child/templates/shop-premium.php` |
| `/hosting-wordpress-colombia` | `page-seo-landing.php` + seo-page PHP | `wp-content/themes/gano-child/templates/page-seo-landing.php` + `seo-pages/hosting-wordpress-colombia.php` |
| 20 páginas SOTA (`/arquitectura-nvme-…`, etc.) | `sota-single-template.php` | `wp-content/themes/gano-child/templates/sota-single-template.php` |

> Las 20 páginas SOTA son creadas por el plugin `gano-content-importer` como borradores (`draft`); su contenido HTML está incrustado en el plugin y se renderiza vía `sota-single-template.php`.

---

## 4. Hubs principales

### Hub 1 — Home `/`

**Rol:** narrativa completa del ecosistema; introduce los 4 pilares de valor y lleva al catálogo.

**Bloques funcionales:**
1. Hero — headline + subheadline + 2 CTAs (primario: `Ver ecosistemas y planes`, secundario: `Hablar con el equipo`)
2. Pilares de valor (4 cards) — velocidad, seguridad, Zero-Trust, Edge
3. Ecosistemas — preview de los 3 planes con botón `Ver detalles`
4. Bloque de confianza — copy técnico + bullets
5. CTA final — `Elegir mi arquitectura`

**Salidas (≤ 1 clic):** `/ecosistemas`, `/contacto`, cualquier plan individual.

---

### Hub 2 — Ecosistemas / Shop `/ecosistemas`

**Rol:** catálogo de conversión — 3 planes + carrito Reseller GoDaddy.

**Estructura:**
- Tabla comparativa de planes (Núcleo Prime · Fortaleza Delta · Bastión SOTA)
- Cada plan con: nombre, precio en COP, specs clave, CTA `Elegir este plan`
- Footer del hub: enlace a `/contacto` para duda comercial y a pilares SEO relacionados

**Salidas:** plan individual, `/contacto`, pilares relacionados por plan.

---

### Hub 3 — Nosotros `/nosotros`

**Rol:** confianza y credibilidad; mostrar solo lo que sea real.

**Bloques funcionales:**
1. Manifiesto de marca (tono técnico-cercano, sin hipérboles)
2. Fundador / equipo (solo si hay contenido real; usar `[nombre]`, `[foto]` como placeholders)
3. Propuesta de valor resumida
4. CTA: `Ver nuestros ecosistemas`

> ⚠️ No publicar esta página con placeholders visibles; mantener en borrador hasta tener copy real.

---

### Hub 4 — Legal `/legal`

**Rol:** cumplimiento SIC Colombia + confianza pre-compra.

**Páginas:**
- Términos y condiciones de servicio
- Política de privacidad y tratamiento de datos (RGPD + SIC Colombia)
- Acuerdo de nivel de servicio (SLA) — usar formulaciones prudentes ("objetivo de disponibilidad del 99,9%")

---

### Hub 5 — Contacto `/contacto`

**Rol:** reducir fricción pre-venta; canal directo con el equipo.

**Elementos:**
- Formulario de contacto con campos: nombre, empresa, correo, mensaje
- WhatsApp / chat IA `gano-chat.js` (ya integrado)
- Teléfono y correo `[datos reales pendientes]`
- CTA secundario: `Ver planes y precios`

---

### Hub 6 — Pilares SEO `/pilares`

**Rol:** posicionamiento orgánico + educación técnica + enlace interno a planes.

**Estructura del índice:**
- Grid de 5 categorías (Infraestructura · Seguridad · Rendimiento · IA · Estrategia)
- Cada categoría: card con título, descripción 1 línea, enlace `Ver artículos`
- Breadcrumb: Home → Pilares → Categoría → Artículo

---

## 5. Matriz de enlaces internos sugeridos (pilares ↔ planes)

### 5.1 Por categoría de pilar

| Categoría | Pilares | Planes recomendados | Texto de enlace sugerido |
|-----------|---------|---------------------|--------------------------|
| **Infraestructura** | NVMe, Hosting Compartido, Edge, Backups, Escalamiento, Alta Disponibilidad | Bastión SOTA (todos), Fortaleza Delta (HA, backups) | "Disponible en Bastión SOTA" · "Ver plan Fortaleza Delta" |
| **Seguridad** | Zero-Trust, DDoS, Cifrado Post-Cuántico | Fortaleza Delta + Bastión SOTA | "Incluido en Fortaleza Delta y Bastión SOTA" |
| **Rendimiento** | Headless, Green Hosting, CI/CD, Skeleton Screens, Micro-animaciones, HTTP/3 | Bastión SOTA (full), Fortaleza Delta (CI/CD, HTTP/3) | "Activar en tu ecosistema" → `/ecosistemas/bastion-sota` |
| **IA** | Gestión Predictiva, Self-Healing, Agente IA | Bastión SOTA | "El agente IA viene con Bastión SOTA" |
| **Estrategia** | Soberanía Digital, Analytics Server-Side | Todos los planes (mención genérica) | "Elige tu nivel de soberanía" → `/ecosistemas` |

### 5.2 Por plan (qué pilares cada plan debe mencionar)

| Plan | Pilares a enlazar | Argumento comercial |
|------|------------------|---------------------|
| **Núcleo Prime** | NVMe, Backups, HTTP/3 | "Tu primera infraestructura seria" |
| **Fortaleza Delta** | NVMe, Zero-Trust, DDoS, CI/CD, HA, Backups | "Seguridad y rendimiento para negocios en crecimiento" |
| **Bastión SOTA** | Todos los 20 pilares | "El ecosistema completo — sin compromisos" |

### 5.3 Posición del enlace en cada pilar

Cada página SOTA (`sota-single-template.php`) debe incluir en su sección final:

```
┌────────────────────────────────────────────────────────┐
│  ¿Quieres [beneficio del pilar] en tu ecosistema?      │
│  → [Nombre del plan recomendado]  [Ver plan]           │
│  → Todos los ecosistemas          [Comparar planes]    │
└────────────────────────────────────────────────────────┘
```

Los slugs exactos para los CTA:
- Bastión SOTA: `/ecosistemas/bastion-sota`
- Fortaleza Delta: `/ecosistemas/fortaleza-delta`
- Núcleo Prime: `/ecosistemas/nucleo-prime`
- Índice: `/ecosistemas`

---

## 6. Menú de navegación principal (estructura sugerida)

```
[Logo Gano Digital]   Ecosistemas ▾   Pilares ▾   Nosotros   Contacto   [Elegir plan →]
```

**Dropdown Ecosistemas:**
- Núcleo Prime
- Fortaleza Delta
- Bastión SOTA
- ─────────────
- Comparar todos los planes

**Dropdown Pilares:**
- Infraestructura
- Seguridad
- Rendimiento
- Inteligencia Artificial
- Estrategia

> Implementar en wp-admin → Apariencia → Menús, ubicación `primary` (registrada en `gano-child/functions.php`).

---

## 7. Rutas a crear / publicar (checklist de estado)

| Ruta | Estado actual | Acción requerida |
|------|--------------|-----------------|
| `/` | Publicada (Elementor, pendiente copy final) | Aplicar copy de `homepage-copy-2026-04.md` |
| `/ecosistemas` | Plantilla PHP lista (`shop-premium.php`) | Publicar como página con template asignado |
| `/ecosistemas/nucleo-prime` | Pendiente | Crear página Elementor |
| `/ecosistemas/fortaleza-delta` | Pendiente | Crear página Elementor |
| `/ecosistemas/bastion-sota` | Pendiente | Crear página Elementor |
| `/pilares` | Pendiente | Crear página índice Elementor |
| 20 páginas SOTA | Creadas como `draft` por plugin importer | Revisar + publicar individualmente |
| `/hosting-wordpress-colombia` | PHP template lista | Publicar con template asignado |
| `/nosotros` | Pendiente contenido real | Crear + publicar cuando haya copy |
| `/contacto` | Pendiente | Crear página Elementor con formulario |
| `/legal/*` | Pendiente | Crear 3 sub-páginas bajo `/legal` |

---

## 8. Notas adicionales

- **Breadcrumbs:** habilitados vía `gano-seo.php` (Schema BreadcrumbList automático). Confirmar que Rank Math los muestre en las 20 páginas SOTA y en las sub-páginas de ecosistemas.
- **Canonical URLs:** Rank Math debe definir la URL canónica de cada pilar para evitar duplicidad si WordPress genera `/pilares/categoria/slug` vs `/slug` raíz.
- **Sitemap XML:** Rank Math generará `sitemap_index.xml` automáticamente; excluir páginas en borrador y páginas de instalación de los plugins `gano-phase*`.
- **robots.txt:** ya configurado en el repo (`robots.txt`). Confirmar que `Disallow: /wp-admin/` no bloquee crawlers en rutas públicas.

---

_Fin del documento · Mantener sincronizado con `TASKS.md` y `memory/research/gano-wave3-brand-ux-master-brief.md`._
