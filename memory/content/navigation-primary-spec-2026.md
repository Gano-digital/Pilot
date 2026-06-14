# Especificación menú `primary` — Gano Digital · 2026

**Versión:** 1.1 · Abril 2026  
**Ubicación registrada:** `primary` (`gano_child_register_nav_menus()` en `functions.php` líneas 321–330)  
**Referencia de IA:** `memory/content/site-ia-wave3-proposed.md` §6  
**Regla de conversión:** ningún camino a checkout supera **3 clics** desde Home.

---

## 1. Tabla de ítems — orden, etiquetas y destinos

> **Prioridad:** P0 = siempre visible en escritorio y móvil · P1 = visible si el espacio lo permite (escritorio) · P2 = sólo footer o menú secundario.

| # | Prioridad | Etiqueta menú (ES-CO) | URL / Slug de página WP | Tipo | Notas |
|---|-----------|----------------------|-------------------------|------|-------|
| 1 | **P0** | Ecosistemas | `/ecosistemas` | Ítem con dropdown | Hub principal de conversión. Muestra los 3 planes + "Comparar todos". Template PHP: `shop-premium.php` |
| 1a | P1 | Núcleo Prime | `/ecosistemas/nucleo-prime` | Sub-ítem (dropdown) | Plan de entrada — $196.000 COP/mes. CTA a carrito Reseller (GANO_PFID_HOSTING_ECONOMIA) |
| 1b | P1 | Fortaleza Delta | `/ecosistemas/fortaleza-delta` | Sub-ítem (dropdown) | Plan medio — $450.000 COP/mes. CTA a carrito Reseller (GANO_PFID_HOSTING_DELUXE) |
| 1c | P1 | Bastión SOTA | `/ecosistemas/bastion-sota` | Sub-ítem (dropdown) | Plan premium — $890.000 COP/mes. CTA a carrito Reseller (GANO_PFID_HOSTING_PREMIUM) |
| 1d | P1 | Comparar todos los planes | `/ecosistemas` | Sub-ítem (dropdown) | Ancla a tabla comparativa en la misma página. Añadir separador visual (línea `<hr>`) antes de este ítem |
| 2 | **P0** | Pilares | `/pilares` | Ítem con dropdown | Hub SEO — índice de 5 categorías temáticas |
| 2a | P1 | Infraestructura | `/pilares#infraestructura` | Sub-ítem (dropdown) | 6 artículos: NVMe, Hosting Compartido, Edge, Backups, Escalamiento, HA |
| 2b | P1 | Seguridad | `/pilares#seguridad` | Sub-ítem (dropdown) | 3 artículos: Zero-Trust, DDoS, Cifrado Post-Cuántico |
| 2c | P1 | Rendimiento | `/pilares#rendimiento` | Sub-ítem (dropdown) | 6 artículos: Headless WP, Green Hosting, CI/CD, Skeleton Screens, Micro-animaciones, HTTP/3 |
| 2d | P1 | Inteligencia Artificial | `/pilares#inteligencia-artificial` | Sub-ítem (dropdown) | 3 artículos: Gestión Predictiva, Self-Healing, Agente IA |
| 2e | P1 | Estrategia | `/pilares#estrategia` | Sub-ítem (dropdown) | 2 artículos: Soberanía Digital, Analytics Server-Side |
| 3 | **P0** | Nosotros | `/nosotros` | Ítem directo | Hub de confianza. ⚠️ Mantener en borrador hasta tener copy real (sin placeholders visibles) |
| 4 | **P0** | Contacto | `/contacto` | Ítem directo | Hub pre-venta. Formulario + WhatsApp + chat IA (`gano-chat.js`) |
| 5 | **P0** | Elegir plan → | `/ecosistemas` | CTA primario (botón) | Estilo: botón con `background: var(--gano-orange)`. Posición: extremo derecho del header. En mobile: parte inferior del menú hamburguesa |

---

## 2. Ítems P0 (siempre visibles)

Los siguientes ítems son **P0** y deben aparecer en cualquier viewport, incluyendo la vista móvil (menú hamburguesa):

| # | Etiqueta | URL |
|---|----------|-----|
| 1 | Ecosistemas | `/ecosistemas` |
| 2 | Pilares | `/pilares` |
| 3 | Nosotros | `/nosotros` |
| 4 | Contacto | `/contacto` |
| 5 | Elegir plan → | `/ecosistemas` (botón CTA) |

> En **mobile**, los dropdowns de Ecosistemas y Pilares se convierten en acordeones desplegables (comportamiento nativo de Elementor Nav Menu widget).

---

## 3. Ítems secundarios / P2 (footer o menú secundario)

Los siguientes ítems **no van** en el menú `primary` del header, sino en el footer o en un menú `footer-legal`:

| Etiqueta | URL | Dónde aparece |
|----------|-----|---------------|
| Términos y condiciones | `/legal/terminos-y-condiciones` | Footer — columna legal |
| Política de privacidad | `/legal/politica-de-privacidad` | Footer — columna legal |
| Acuerdo de nivel de servicio | `/legal/acuerdo-de-nivel-de-servicio` | Footer — columna legal |
| Hosting WordPress Colombia | `/hosting-wordpress-colombia` | Footer — SEO; también enlace desde pilares |

---

## 4. Estructura visual del header

```
[Logo Gano Digital]   Ecosistemas ▾   Pilares ▾   Nosotros   Contacto   [Elegir plan →]
```

**Dropdown — Ecosistemas:**
```
┌─────────────────────────┐
│  Núcleo Prime           │  → /ecosistemas/nucleo-prime
│  Fortaleza Delta        │  → /ecosistemas/fortaleza-delta
│  Bastión SOTA           │  → /ecosistemas/bastion-sota
│ ─────────────────────── │
│  Comparar todos ›       │  → /ecosistemas
└─────────────────────────┘
```

**Dropdown — Pilares:**
```
┌──────────────────────────────┐
│  Infraestructura             │  → /pilares#infraestructura
│  Seguridad                   │  → /pilares#seguridad
│  Rendimiento                 │  → /pilares#rendimiento
│  Inteligencia Artificial     │  → /pilares#inteligencia-artificial
│  Estrategia                  │  → /pilares#estrategia
└──────────────────────────────┘
```

---

## 5. Checklist de aplicación en wp-admin / Elementor Theme Builder

> Este checklist es para **Diego** (humano). Los agentes de código no modifican Elementor/BD directamente.

### 5.1 Crear el menú en wp-admin

- [ ] Ir a **wp-admin → Apariencia → Menús**
- [ ] Crear nuevo menú con nombre: `Menú Principal 2026`
- [ ] Asignar a la ubicación **`Menú principal (header / Elementor)`** (campo `primary`)
- [ ] Guardar

### 5.2 Agregar ítems P0 (en orden)

- [ ] Añadir **Página** → `Ecosistemas` (URL: `/ecosistemas`) — marcar "Permitir desplegable"
  - [ ] Sub-ítem: Página `Núcleo Prime` → `/ecosistemas/nucleo-prime`
  - [ ] Sub-ítem: Página `Fortaleza Delta` → `/ecosistemas/fortaleza-delta`
  - [ ] Sub-ítem: Página `Bastión SOTA` → `/ecosistemas/bastion-sota`
  - [ ] Sub-ítem: Enlace personalizado `Comparar todos los planes` → `/ecosistemas` (añadir separador antes con CSS class `menu-separator`)
- [ ] Añadir **Página** → `Pilares` (URL: `/pilares`) — marcar "Permitir desplegable"
  - [ ] Sub-ítem: Enlace `Infraestructura` → `/pilares#infraestructura`
  - [ ] Sub-ítem: Enlace `Seguridad` → `/pilares#seguridad`
  - [ ] Sub-ítem: Enlace `Rendimiento` → `/pilares#rendimiento`
  - [ ] Sub-ítem: Enlace `Inteligencia Artificial` → `/pilares#inteligencia-artificial`
  - [ ] Sub-ítem: Enlace `Estrategia` → `/pilares#estrategia`
- [ ] Añadir **Página** → `Nosotros` (URL: `/nosotros`) ⚠️ Solo si la página está publicada con copy real
- [ ] Añadir **Página** → `Contacto` (URL: `/contacto`)
- [ ] Añadir **Enlace personalizado** con etiqueta `Elegir plan →` → `/ecosistemas`
  - [ ] Asignar CSS class: `gano-menu-cta` (aplica estilo botón naranja)
- [ ] Guardar menú

### 5.3 Configurar en Elementor Theme Builder (header template)

- [ ] Ir a **wp-admin → Elementor → Theme Builder → Header**
- [ ] Abrir el template de header activo
- [ ] Seleccionar el widget **Nav Menu** (o **Elementor Pro: Nav Menu**)
- [ ] En "Menú" seleccionar: `Menú Principal 2026`
- [ ] Verificar que el dropdown de Ecosistemas muestra los 3 sub-ítems
- [ ] Verificar que el dropdown de Pilares muestra las 5 categorías
- [ ] En la configuración del ítem CTA (`Elegir plan →`):
  - [ ] Confirmar que tiene clase CSS `gano-menu-cta`
  - [ ] Confirmar color de fondo `var(--gano-orange)` en los estilos del widget
- [ ] Configurar **breakpoint móvil**: activar menú hamburguesa para viewport < 1024px
- [ ] Publicar / actualizar el header template

### 5.4 Verificación visual

- [ ] Abrir `https://gano.digital` en escritorio — confirmar los 5 ítems P0 visibles
- [ ] Confirmar que el dropdown de Ecosistemas abre con 3 planes + separador + "Comparar todos"
- [ ] Confirmar que el dropdown de Pilares abre con 5 categorías
- [ ] Confirmar que `Elegir plan →` tiene estilo de botón naranja (CTA)
- [ ] Abrir en móvil (viewport < 768px) — confirmar menú hamburguesa funcional
- [ ] Confirmar que los acordeones de Ecosistemas y Pilares se despliegan en móvil
- [ ] Si `/nosotros` aún es borrador: el ítem debe estar **oculto** o reemplazado por un enlace a `/contacto`

### 5.5 Ítems de footer (menú secundario)

- [ ] Crear menú `Menú Legal Footer` con los 3 ítems legales + `Hosting WordPress Colombia`
- [ ] Asignar a la ubicación de footer en el template de footer de Elementor

---

## 6. Notas técnicas para agentes de código

- La ubicación `primary` ya está registrada en `functions.php` (líneas 321–330, función `gano_child_register_nav_menus()`). El fallback automático lo gestiona `gano_child_assign_nav_menu_locations()` (líneas 349–351), que asigna el primer menú disponible a `primary`, `header` y `footer` si alguna de ellas está vacía.
- Los estilos de `.gano-menu-cta` y `.menu-separator` **ya están implementados** en `gano-child/style.css` (líneas 1706–1737). No es necesario agregar CSS adicional. La implementación incluye `:visited`, `:hover`, `:focus`, `:focus-visible` (outline con `--gano-gold`) y el separador visual del dropdown:
  ```css
  /* Referencia — ya en style.css (no duplicar) */
  .gano-menu-cta > a,
  .gano-menu-cta > a:visited {
    background: var(--gano-orange, #E85D04);
    color: #fff !important;
    padding: 0.5rem 1.25rem;
    border-radius: 4px;
    font-weight: 700;
    text-decoration: none !important;
    transition: opacity 150ms ease, background 150ms ease;
  }
  .gano-menu-cta > a:hover,
  .gano-menu-cta > a:focus { opacity: 0.88; }
  .gano-menu-cta > a:focus-visible { outline: 3px solid var(--gano-gold, #D4AF37); outline-offset: 3px; }

  /* Separador antes de "Comparar todos" en dropdown Ecosistemas */
  .menu-separator > a { border-top: 1px solid #e2e8f0; margin-top: 0.25rem; padding-top: 0.75rem; }
  ```
- Los ítems P1 (sub-ítems de dropdown) **no** tienen que estar en `primary` como ítems de primer nivel — se crean como hijos (sub-ítems) del ítem padre en el editor de menús de WordPress.
- Los anclas `#infraestructura`, `#seguridad`, etc. en `/pilares` deben existir en el HTML de esa página (IDs de sección en Elementor).

---

_Fin del documento · Sincronizar con `TASKS.md` y `memory/content/site-ia-wave3-proposed.md` §6._
