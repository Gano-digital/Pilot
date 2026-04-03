# Matriz Productos/Servicios — Vitrina, Slugs, CTAs y GANO_PFID
**Versión:** 1.0 · Abril 2026  
**Fuente de verdad:** `wp-content/themes/gano-child/templates/shop-premium.php` + `functions.php` (bloque `GANO RESELLER STORE`)  
**Referencia IA:** `memory/content/site-ia-wave3-proposed.md`

---

## 1. Tabla principal — Productos activos en vitrina

| # | Nombre en vitrina | Categoría vitrina | Slug WP sugerido | Fila `shop-premium.php` (`$products`) | Constante `GANO_PFID_*` | Fuente RCC | Nota RCC |
|---|-------------------|-------------------|------------------|---------------------------------------|-------------------------|------------|----------|
| 1 | **Núcleo Prime** | Hosting | `/ecosistemas/nucleo-prime` | `cat='hosting'`, `name='Núcleo Prime'`, `price='196.000'` | `GANO_PFID_HOSTING_ECONOMIA` | RCC → Productos → Web Hosting → WordPress Hosting Economy | *Rellenar en RCC* |
| 2 | **Fortaleza Delta** | Hosting | `/ecosistemas/fortaleza-delta` | `cat='hosting'`, `name='Fortaleza Delta'`, `price='450.000'` | `GANO_PFID_HOSTING_DELUXE` | RCC → Productos → Web Hosting → WordPress Hosting Deluxe | *Rellenar en RCC* |
| 3 | **Bastión SOTA** | Hosting | `/ecosistemas/bastion-sota` | `cat='hosting'`, `name='Bastión SOTA'`, `price='890.000'` | `GANO_PFID_HOSTING_PREMIUM` | RCC → Productos → Web Hosting → WordPress Hosting Premium | *Rellenar en RCC* |
| 4 | **Ultimate WP** | Hosting | `/ecosistemas/ultimate-wp` | `cat='hosting'`, `name='Ultimate WP'`, `price='1.200.000'` | `GANO_PFID_HOSTING_ULTIMATE` | RCC → Productos → Web Hosting → WordPress Hosting Ultimate | *Rellenar en RCC* |
| 5 | **SSL Deluxe** | Seguridad | `/seguridad/ssl-deluxe` | `cat='security'`, `name='SSL Deluxe'`, `price='680.000'` | `GANO_PFID_SSL_DELUXE` | RCC → Productos → SSL & Seguridad → SSL DV Deluxe | *Rellenar en RCC* |
| 6 | **Security Ultimate** | Seguridad | `/seguridad/security-ultimate` | `cat='security'`, `name='Security Ultimate'`, `price='1.800.000'` | `GANO_PFID_SECURITY_ULTIMATE` | RCC → Productos → SSL & Seguridad → Website Security Premium | *Rellenar en RCC* |
| 7 | **Dominio .CO** | Identidad | `/dominios/` | `cat='identity'`, `name='Dominio .CO'`, `price='90.000'` | `'domain_search'` (especial) | RCC / shortcode `[rstore-domain-search]` | CTA apunta a `/dominios/` — no usa pfid de carrito directo |
| 8 | **Dominio .COM** | Identidad | `/dominios/` | `cat='identity'`, `name='Dominio .COM'`, `price='75.000'` | `'domain_search'` (especial) | RCC / shortcode `[rstore-domain-search]` | CTA apunta a `/dominios/` — no usa pfid de carrito directo |
| 9 | **M365 Premium** | Colaboración (email) | `/colaboracion/m365-premium` | `cat='email'`, `name='M365 Premium'`, `price='98.000'` | `GANO_PFID_M365_PREMIUM` | RCC → Productos → Email & Office → Microsoft 365 Business Premium | *Rellenar en RCC* |
| 10 | **Online Storage 1TB** | Colaboración (email) | `/colaboracion/online-storage-1tb` | `cat='email'`, `name='Online Storage 1TB'`, `price='120.000'` | `GANO_PFID_ONLINE_STORAGE` | RCC → Productos → Email & Office → Online Storage 1 TB | *Rellenar en RCC* |

> **Importante:** Los valores `PENDING_RCC` de las constantes deben reemplazarse con los PFIDs reales obtenidos desde el **Reseller Control Center (RCC)**. Ver instrucciones en `functions.php` (bloque `GANO RESELLER STORE`, líneas ~630-664). El flujo de checkout permanece desactivado hasta que se configuren los pfids reales.

---

## 2. CTA por tipo de producto

| Tipo de pfid | CTA label en vitrina | Destino href | Comportamiento |
|---|---|---|---|
| `PENDING_RCC` | "Próximamente" | `#` (deshabilitado) | clase `rstore-add-to-cart--pending`; cursor `not-allowed` |
| `domain_search` | "Buscar Dominio" | `home_url('/dominios/')` | Redirige a página de búsqueda de dominios |
| pfid real (string) | "Adquirir Nodo" | `gano_rstore_cart_url($pfid)` → `cart.secureserver.net` | `target="_blank"` — carrito GoDaddy Reseller |

---

## 3. Slugs WP sugeridos — estado de creación

Según `site-ia-wave3-proposed.md` (§7), las páginas de planes individuales están **pendientes de creación** en wp-admin. El árbol de slugs recomendado es:

| Slug | Página | Estado (conocido) | Tecnología | Acción |
|------|--------|-------------------|------------|--------|
| `/ecosistemas` | Catálogo principal (shop-premium.php) | Plantilla PHP lista | `shop-premium.php` (PHP) | Publicar con template `Gano Premium Shop SOTA` asignado |
| `/ecosistemas/nucleo-prime` | Detalle plan — Núcleo Prime | Pendiente | Elementor | Crear página con slug, asignar template Elementor, incluir CTA Reseller |
| `/ecosistemas/fortaleza-delta` | Detalle plan — Fortaleza Delta | Pendiente | Elementor | Crear página con slug, asignar template Elementor, incluir CTA Reseller |
| `/ecosistemas/bastion-sota` | Detalle plan — Bastión SOTA | Pendiente | Elementor | Crear página con slug, asignar template Elementor, incluir CTA Reseller |
| `/ecosistemas/ultimate-wp` | Detalle plan — Ultimate WP | Pendiente | Elementor | Considerar si este plan necesita página individual (ver §4 huecos) |
| `/seguridad/ssl-deluxe` | Detalle SSL Deluxe | Pendiente | Elementor o PHP | No hay template dedicado aún |
| `/seguridad/security-ultimate` | Detalle Security Ultimate | Pendiente | Elementor o PHP | No hay template dedicado aún |
| `/dominios/` | Búsqueda de dominios | Pendiente | Elementor + shortcode | Usar `[rstore-domain-search]` |
| `/colaboracion/m365-premium` | Detalle M365 Premium | Pendiente | Elementor o PHP | No hay template dedicado aún |
| `/colaboracion/online-storage-1tb` | Detalle Online Storage | Pendiente | Elementor o PHP | No hay template dedicado aún |

> Los slugs `seguridad/` y `colaboracion/` son sugerencias. Podrían consolidarse bajo `/ecosistemas/` si el sitio adopta una IA plana (todos los productos bajo el mismo hub).

---

## 4. Huecos — Páginas faltantes o solo como idea

### 4.1 Páginas que no existen en el repo (ni como template PHP ni como borrador Elementor conocido)

| Hueco | Descripción | Impacto comercial | Acción sugerida |
|-------|-------------|-------------------|-----------------|
| `/ecosistemas/nucleo-prime` | Página de detalle del plan de entrada | Alto — es el primer plan que verá un cliente nuevo | Crear en wp-admin con copy de `ecosystems-copy-matrix-wave3.md` |
| `/ecosistemas/fortaleza-delta` | Página de detalle del plan medio | Alto — plan de mayor conversión esperada | Crear en wp-admin |
| `/ecosistemas/bastion-sota` | Página de detalle del plan premium | Alto — ticket más alto | Crear en wp-admin |
| `/dominios/` | Búsqueda y registro de dominios | Medio — soporte al onboarding | Crear con shortcode `[rstore-domain-search]` |
| `/seguridad/ssl-deluxe` | Página de producto SSL | Medio — upsell a planes | Crear o incluir como sección en `/ecosistemas` |
| `/seguridad/security-ultimate` | Página de producto seguridad avanzada | Medio — upsell a planes | Crear o incluir como sección en `/ecosistemas` |
| `/colaboracion/m365-premium` | Página Microsoft 365 | Bajo-Medio — cross-sell | Crear o incluir como sección en `/ecosistemas` |
| `/colaboracion/online-storage-1tb` | Página almacenamiento online | Bajo — cross-sell | Crear o incluir como sección en `/ecosistemas` |

### 4.2 Productos presentes en vitrina sin página de detalle dedicada

Los productos de categoría `security`, `identity` y `email` aparecen como tarjetas en `/ecosistemas` (la vitrina `shop-premium.php`) **pero no tienen páginas de detalle individuales**. Dependen enteramente de la tarjeta de la vitrina para comunicar su propuesta de valor.

**Recomendación:** para el lanzamiento inicial, mantenerlos solo como tarjetas en la vitrina es aceptable. Priorizar las páginas de los 3 planes de hosting (`nucleo-prime`, `fortaleza-delta`, `bastion-sota`) primero.

### 4.3 Plan "Ultimate WP" — coherencia narrativa

El plan `Ultimate WP` (fila 4 de la vitrina) **no tiene nombre de ecosistema** en la nomenclatura oficial (Núcleo Prime / Fortaleza Delta / Bastión SOTA). Aparece en `shop-premium.php` con el nombre "Ultimate WP" (marca GoDaddy). 

**Opciones:**
- Asignarle un nombre de ecosistema propio (ej. `Comando Sigma`) alineado con la narrativa SOTA.
- O mantenerlo como "Ultimate WP" si es el nombre de producto oficial del Reseller que Diego no desea renombrar.

> ⚠️ Decisión pendiente de Diego antes de crear su página de detalle.

### 4.4 Constantes con valor `PENDING_RCC` — bloqueo de checkout

Actualmente **todas** las constantes `GANO_PFID_*` tienen el valor `PENDING_RCC`. Esto significa que todos los botones de compra muestran "Próximamente" y apuntan a `#`. El flujo de checkout **no está operativo**. 

Para desbloquearlo, Diego debe:
1. Acceder al **RCC** (Reseller Control Center de GoDaddy).
2. Navegar a cada categoría de producto indicada en la columna "Fuente RCC" de la tabla §1.
3. Copiar el `pfid` de cada producto.
4. Reemplazar los `PENDING_RCC` en `functions.php` por los valores reales.

### 4.5 PLID (Private Label ID) — configuración pendiente

La función `gano_rstore_cart_url()` en `functions.php` requiere que el **PLID** esté configurado en `wp-admin → Ajustes → Reseller Store → Private Label ID`. Sin PLID válido, la función retorna `#` aunque los PFIDs sean correctos.

---

## 5. Coherencia entre vitrina y menú de navegación

Según `site-ia-wave3-proposed.md` (§6), el menú principal propuesto es:
```
[Logo]   Ecosistemas ▾   Pilares ▾   Nosotros   Contacto   [Elegir plan →]
```

El dropdown "Ecosistemas" debe enlazar exactamente a los slugs de la tabla §3:
- Núcleo Prime → `/ecosistemas/nucleo-prime`
- Fortaleza Delta → `/ecosistemas/fortaleza-delta`  
- Bastión SOTA → `/ecosistemas/bastion-sota`
- Comparar todos → `/ecosistemas`

**Hueco:** no existe actualmente un menú asignado a la ubicación `primary` en wp-admin. Requiere acción manual en wp-admin → Apariencia → Menús.

---

## 6. Resumen de estado por categoría

| Categoría | Productos en vitrina | Templates PHP dedicados | Páginas WP publicadas | Checkout operativo |
|-----------|---------------------|------------------------|----------------------|-------------------|
| Hosting (4 planes) | ✅ 4 tarjetas | ✅ `shop-premium.php` (catálogo) | ❌ Páginas de detalle pendientes | ❌ `PENDING_RCC` |
| Seguridad (2 productos) | ✅ 2 tarjetas | ❌ Sin template dedicado | ❌ Pendiente | ❌ `PENDING_RCC` |
| Identidad / Dominios (2) | ✅ 2 tarjetas | ❌ Sin template dedicado | ❌ `/dominios/` pendiente | N/A (domain_search) |
| Colaboración / Email (2) | ✅ 2 tarjetas | ❌ Sin template dedicado | ❌ Pendiente | ❌ `PENDING_RCC` |

---

_Fin del documento · Mantener sincronizado con `functions.php` (bloque GANO RESELLER STORE) y `memory/content/site-ia-wave3-proposed.md`._
