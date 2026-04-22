# Tarea cd-content-002 — Wave contenidos: Páginas ecosistemas (4 planes de hosting)

**ID:** `cd-content-002`  
**Prioridad:** P1 (Comercial)  
**Requiere humano:** SÍ (Diego revisa + ajusta en Elementor)  
**Generado:** 2026-04-19  
**Estado:** Listo para ejecución (requiere cd-repo-002 completada)

---

## ⏳ Requisito previo

Esta tarea **REQUIERE** que cd-repo-002 esté completa:
- [ ] ✅ PFIDs extraídos de GoDaddy RCC
- [ ] ✅ Constantes PHP en `wp-content/themes/gano-child/functions.php` (líneas 662–696)
- [ ] ✅ `python scripts/validate_agent_queue.py` → OK

**Si cd-repo-002 no está completa:** detén aquí y ejecuta primero.

---

## Objetivo

Crear **4 páginas de ecosistemas** (planes de hosting Gano Digital):

1. **Página `/ecosistemas`** — Hub de planes (landing de decisión)
2. **Página `/ecosistema/essential`** — Plan Essential Hosting
3. **Página `/ecosistema/fortaleza-delta`** — Plan Fortaleza Delta (Premium)
4. **Página `/ecosistema/bastion-sota`** — Plan Bastión SOTA (Elite)

Cada página:
- ✅ Contiene estructura canónica (Hero + Specs + Pricing + CTA)
- ✅ Precios **dinámicos** (vinculados a PFIDs desde RCC)
- ✅ CTAs de "Elegir este plan" → redirigen a carrito GoDaddy
- ✅ Está optimizada WCAG AA + Core Web Vitals
- ✅ Incluye comparativas con planes anteriores

**Impacto:**
- Conversión de vitrina a checkout
- Precios siempre sincronizados con catálogo Reseller
- Cumplimiento legal (disclaimers Reseller incluidos)

---

## 📋 Las 4 páginas — Especificación de contenido

### Página 1: `/ecosistemas` (Landing Hub)

**Tipo:** Página de decisión (elige cuál plan)  
**Estructura:**

| Bloque | Contenido | Clase CSS |
|--------|-----------|-----------|
| **Hero** | H1: "Elige tu arquitectura de infraestructura"; Subheadline: "Desde startup hasta operación empresarial. Todo en NVMe, todo con seguridad de nivel 3." | `gano-el-hero-ecosistemas` |
| **Intro** | Párrafo: "En Gano Digital no hay hosting de 'tiendita'. Cada plan está diseñado para una etapa real del crecimiento." + 1 CTA micro: "¿No sabes cuál es para ti?" (desplaza a comparativa abajo) | `gano-el-intro-ecosistemas` |
| **Comparativa visual (tabla)** | Tabla: Essential vs Fortaleza vs Bastión (3-4 filas: Almacenamiento, Ancho banda, Recursos DB, Soporte, Precio) | `gano-el-comparison-table` |
| **Grid de 3 tarjetas** | Tarjeta 1: Essential; Tarjeta 2: Fortaleza; Tarjeta 3: Bastión. Cada una con: nombre, precio (dinámico via PHP), 4 bullets features, botón "Ver detalles plan" | `gano-el-plan-card-[1/2/3]` |
| **Sección FAQ** | "¿Preguntas frecuentes?" → Acordeón con 5 FAQ (migraciones, garantías, etc.) | `gano-el-faq-ecosistemas` |
| **CTA Final** | "¿Necesitas soporte en la decisión?" + link contacto | `gano-el-cta-contact-ecosistemas` |

---

### Página 2: `/ecosistema/essential`

**Tipo:** Plan detail (Essential Hosting)  
**Precio esperado:** desde $[PFID_DYNAMIC] COP/mes  
**Público:** Startups, blogs, proyectos nuevos  
**Estructura:**

| Bloque | Contenido | Clase CSS |
|--------|-----------|-----------|
| **Hero** | H1: "Essential Hosting — Para empezar bien"; Subheadline: "NVMe desde día 1, sin comprometer el presupuesto inicial." | `gano-el-hero-plan-essential` |
| **Specs table** | Tabla 2 columnas: Característica vs Límite (Almacenamiento: 50GB NVMe; Dominios: 1; Emails: 50, etc.) | `gano-el-specs-table-essential` |
| **Inclusions list** | "Incluye:" + bullets: NVMe, SSL gratis, CDN, Soporte chat, Backups diarios, Uptime 99.9% | `gano-el-inclusions-essential` |
| **Pricing block** | Precio grande: `<?php echo GANO_PFID_ESSENTIAL_HOSTING; ?>` COP/mes; Facturación anual con descuento (12.5%); "Prueba 30 días gratis" | `gano-el-pricing-essential` |
| **CTA primario** | Botón naranja: "Elegir Essential" → Redirige a carrito (via `gano-reseller-enhancements` plugin) | `gano-btn-primary-essential` |
| **Comparativa ligera** | "¿Necesitas más?" → Tabla chica: Essential vs Fortaleza (2 columnas) | `gano-el-comparison-upsell-essential` |
| **Disclaimer Reseller** | Pequeño texto: "Alojado en infraestructura GoDaddy Managed WordPress. [Link a términos]" | `gano-el-reseller-disclaimer` |

---

### Página 3: `/ecosistema/fortaleza-delta`

**Tipo:** Plan detail (Fortaleza Delta / Premium)  
**Precio esperado:** desde $[PFID_DYNAMIC] COP/mes  
**Público:** Empresas pequeñas, ecommerce, agencias  
**Estructura:** (idéntica a Essential, con contenido específico)

| Bloque | Contenido | Clase CSS |
|--------|-----------|-----------|
| **Hero** | H1: "Fortaleza Delta — Para crecer sin límites"; Subheadline: "NVMe potenciado, seguridad de nivel empresa, soporte prioritario." | `gano-el-hero-plan-fortaleza` |
| **Specs table** | Almacenamiento: 250GB NVMe; Dominios: 10; Emails: 500; Tráfico: ilimitado | `gano-el-specs-table-fortaleza` |
| **Inclusions list** | Incluso Essential + SSD caché adicional; Repositorio privado CI/CD; Soporte prioritario (24/7); Rate limiting avanzado | `gano-el-inclusions-fortaleza` |
| **Pricing block** | Precio: `<?php echo GANO_PFID_PREMIUM_HOSTING; ?>` COP/mes; Facturación flexible (mensual/anual); Contrato 1 año con beneficios | `gano-el-pricing-fortaleza` |
| **CTA primario** | "Elegir Fortaleza Delta" | `gano-btn-primary-fortaleza` |
| **Comparativa upsell** | Fortaleza vs Bastión (mostrar por qué escalar) | `gano-el-comparison-upsell-fortaleza` |
| **Disclaimer Reseller** | [Igual a Essential] | `gano-el-reseller-disclaimer` |

---

### Página 4: `/ecosistema/bastion-sota`

**Tipo:** Plan detail (Bastión SOTA / Elite)  
**Precio esperado:** desde $[PFID_DYNAMIC] COP/mes  
**Público:** Empresas medianas, ecommerce alto volumen, SaaS  
**Estructura:** (idéntica a Premium, con contenido élite)

| Bloque | Contenido | Clase CSS |
|--------|-----------|-----------|
| **Hero** | H1: "Bastión SOTA — Infraestructura indestructible"; Subheadline: "NVMe máximo, seguridad zero-trust, arquitectura redundante, 99.99% SLA." | `gano-el-hero-plan-bastion` |
| **Specs table** | Almacenamiento: 500GB NVMe + 250GB SSD caché; Dominios: ilimitados; Emails: ilimitadas; Tráfico: ilimitado | `gano-el-specs-table-bastion` |
| **Inclusions list** | Todo Fortaleza + Alta disponibilidad (HA); Zero-trust security; Monitoreo predictivo IA; Soporte gestor dedicado (opcional add-on) | `gano-el-inclusions-bastion` |
| **Pricing block** | Precio: `<?php echo GANO_PFID_ELITE_HOSTING; ?>` COP/mes; Contrato 1 año recomendado; Descuento multi-año (hasta 25%) | `gano-el-pricing-bastion` |
| **CTA primario** | "Elegir Bastión SOTA" | `gano-btn-primary-bastion` |
| **Add-ons section** | Opcional: SSL Wildcard, Backup premium, Security scan avanzado; Links a cada add-on (si PFIDs disponibles) | `gano-el-addons-bastion` |
| **Disclaimer Reseller** | [Igual a anterior] | `gano-el-reseller-disclaimer` |

---

## 🔐 Integración con precios dinámicos (PHP)

Cada página usa **constantes GANO_PFID_*** definidas en `wp-content/themes/gano-child/functions.php`.

### Ejemplo en Elementor (texto dinámico):

En Elementor, para mostrar precio dinámico:

1. Agrega widget **Text**
2. Click en ícono **dinámico** (⚡ Dynamic content)
3. Si el plugin `gano-reseller-enhancements` existe, debería mostrar opción "PFID Essential Hosting"
4. Selecciona y confirma

**Alternativa (si Elementor no soporta dinámico):**

En PHP, encima del contenido HTML:

```php
<?php
$price_essential = GANO_PFID_ESSENTIAL_HOSTING;
$price_fortaleza = GANO_PFID_PREMIUM_HOSTING;
$price_bastion = GANO_PFID_ELITE_HOSTING;
?>
```

Luego en Elementor HTML widget:

```html
<p>desde <strong><?php echo $price_essential; ?></strong> COP/mes</p>
```

---

## 🎨 Tokens CSS — Verificación

Además de tokens estándar (fs, lh), estas páginas usan:

| Token | Valor | Usado en |
|-------|-------|----------|
| `--gano-orange` | (color primario) | Botones "Elegir plan" |
| `--gano-gold` | #D4AF37 | Énfasis en precios (tarjetas premium) |
| `--gano-color-surface-dark` | #05080B | Fondos secciones |
| `--gano-color-bg-light` | (claro) | Fondos tarjetas |

---

## 🔄 Flujo de implementación (para Diego)

**Tiempo estimado:** 90–120 minutos (4 páginas)  
**Requisitos:**
- cd-repo-002 completada (PFIDs en functions.php)
- Acceso wp-admin
- Editor Elementor activo
- Copy de planes verificado (ver Apéndice A abajo)

---

### Paso 1: Crear página `/ecosistemas` (Hub)

**1.1 — Accede wp-admin → Pages:**

```
wp-admin → Pages → Add New
```

**1.2 — Configura página:**

- Título: "Ecosistemas"
- Slug: `ecosistemas`
- Template: Elementor (si hay opción)
- Status: **Draft** (para revision)

**1.3 — Abre en editor Elementor:**

1. Click en "Edit with Elementor"
2. Espera a que cargue

**1.4 — Construye estructura (6 bloques):**

Sigue tabla "Página 1: `/ecosistemas`" arriba. Agrega:

1. **Sección Hero:**
   - Columna izquierda: H1 + Subheadline
   - Columna derecha: imagen OR shape (opcional)
   - Clase CSS: `gano-el-hero-ecosistemas`

2. **Sección Intro:**
   - Párrafo + CTA micro

3. **Sección Comparativa (tabla):**
   - Usa widget Table de Elementor
   - 3 columnas (Essential, Fortaleza, Bastión)
   - Filas: specs comparadas

4. **Grid de 3 tarjetas:**
   - Cada tarjeta con: nombre, precio (dinámico), bullets, botón
   - Botones redirigen a páginas detalle (links a `/ecosistema/essential`, etc.)

5. **Sección FAQ:**
   - Widget Accordion de Elementor (si está disponible)
   - 5 preguntas frecuentes

6. **CTA Final:**
   - Botón + link contacto

**1.5 — Guarda (Draft):**

Click en **Save (No Publish)**

---

### Paso 2: Crear página `/ecosistema/essential`

**2.1 — Nueva página:**

```
wp-admin → Pages → Add New
```

**2.2 — Configura:**

- Título: "Essential Hosting"
- Slug: `ecosistema/essential`
- Status: Draft

**2.3 — Elementor:**

Construye 7 bloques siguiendo tabla "Página 2" arriba:

1. Hero
2. Specs table
3. Inclusions list
4. **Pricing block (DINÁMICO):**
   ```php
   Precio: <strong><?php echo GANO_PFID_ESSENTIAL_HOSTING; ?></strong> COP/mes
   ```
5. CTA primario → botón "Elegir Essential"
6. Comparativa upsell
7. Disclaimer Reseller

**2.4 — CTA redirige a carrito:**

En botón "Elegir Essential":
- URL: `<?php echo add_query_arg( 'pfid', GANO_PFID_ESSENTIAL_HOSTING, 'https://www.godaddy.com/reseller/cart' ); ?>`
- O confiar en `gano-reseller-enhancements` si maneja redirección automática

**2.5 — Guarda (Draft)**

---

### Paso 3: Crear página `/ecosistema/fortaleza-delta`

**Igual a Paso 2, pero:**

- Slug: `ecosistema/fortaleza-delta`
- Precio: `GANO_PFID_PREMIUM_HOSTING`
- Botón redirige con PFID premium

---

### Paso 4: Crear página `/ecosistema/bastion-sota`

**Igual a Paso 2, pero:**

- Slug: `ecosistema/bastion-sota`
- Precio: `GANO_PFID_ELITE_HOSTING`
- Botón redirige con PFID élite
- Incluye sección opcional "Add-ons"

---

### Paso 5: Testing de precios dinámicos

**5.1 — Preview de cada página:**

1. Abre `/ecosistema/essential` en navegador (preview draft)
2. Verifica que **precio muestra número** (ej: 250000 COP/mes)
3. **NO debe mostrar:**
   - `[PENDIENTE DIEGO]`
   - Variable nombre sin valor
   - 0 o vacío

**5.2 — Test CTA redireccion:**

1. En `/ecosistema/essential`, haz clic en botón "Elegir Essential"
2. Debería redirigir a carrito GoDaddy con parámetro `?pfid=XXXX` en URL
3. Verifica que **GoDaddy carrito carga producto correcto**

**5.3 — Verificación de links internos:**

- En `/ecosistemas`, verifica que botones en tarjetas apuntan a URLs correctas:
  - Botón tarjeta 1 → `/ecosistema/essential` ✅
  - Botón tarjeta 2 → `/ecosistema/fortaleza-delta` ✅
  - Botón tarjeta 3 → `/ecosistema/bastion-sota` ✅

---

### Paso 6: Publicación

**6.1 — Una vez testeadas todas las 4 páginas:**

Para cada página:
1. Click en **Publish**
2. Verifica status cambia a **Published**
3. Copia URL pública en tabla abajo

**Tabla de validación post-publish:**

| Página | URL | Status | Precio visible | CTA funciona |
|--------|-----|--------|---|---|
| Hub | `https://gano.digital/ecosistemas` | Published | N/A | Links internos [✓] |
| Essential | `https://gano.digital/ecosistema/essential` | Published | [DYNÁM] | Redirige a GDC [✓] |
| Fortaleza | `https://gano.digital/ecosistema/fortaleza-delta` | Published | [DYNÁM] | Redirige a GDC [✓] |
| Bastión | `https://gano.digital/ecosistema/bastion-sota` | Published | [DYNÁM] | Redirige a GDC [✓] |

---

## 📝 Copy por página — Placeholders y valores reales

**[PENDIENTE DIEGO]:** Reemplaza con valores reales:

### Página Hub (`/ecosistemas`)

```
H1: "Elige tu arquitectura de infraestructura"
Subheadline: "Desde startup hasta operación empresarial. Todo en NVMe, todo con seguridad."
Intro: "En Gano Digital no hay hosting de 'tiendita'. Cada plan está diseñado para una etapa real del crecimiento."
FAQ Item 1: "¿Puedo cambiar de plan después?" 
FAQ Answer: "Sí, sin penalización. Escala cuando lo necesites."
... (5 FAQ total)
```

### Página Essential

```
H1: "Essential Hosting — Para empezar bien"
Subheadline: "NVMe desde día 1, sin comprometer el presupuesto inicial."
Specs:
  - Almacenamiento NVMe: 50 GB
  - Dominios: 1
  - Emails: 50
  - Tráfico: 500 GB/mes
  - Ancho banda: [PENDIENTE DIEGO: validar con RCC]
```

### Página Fortaleza

```
H1: "Fortaleza Delta — Para crecer sin límites"
Subheadline: "NVMe potenciado, seguridad de nivel empresa, soporte prioritario."
Specs:
  - Almacenamiento NVMe: 250 GB
  - Dominios: 10
  - Emails: 500
  - Tráfico: Ilimitado
```

### Página Bastión

```
H1: "Bastión SOTA — Infraestructura indestructible"
Subheadline: "NVMe máximo, seguridad zero-trust, arquitectura redundante, 99.99% SLA."
Specs:
  - Almacenamiento NVMe: 500 GB + 250 GB SSD
  - Dominios: Ilimitados
  - Emails: Ilimitadas
  - Tráfico: Ilimitado
```

---

## ✅ Definition of Done

La tarea se cierra cuando:

- [ ] 4 páginas creadas (Hub + 3 detail pages)
- [ ] URLs correctas (ecosistemas, ecosistema/essential, etc.)
- [ ] Estructura Elementor completa (6-7 bloques por página)
- [ ] Precios dinámicos muestran PFID valores (no placeholders)
- [ ] CTAs redirigen a carrito GoDaddy con parámetro `?pfid=XXXX`
- [ ] Disclaimers Reseller presentes en cada detail page
- [ ] Clases CSS aplicadas (gano-el-hero-ecosistemas, etc.)
- [ ] Lighthouse ≥85 en mobile (prueba 1 página)
- [ ] Enlaces internos funcionan (navegación entre páginas)
- [ ] 4 páginas publicadas (status = Published)
- [ ] Sitemap actualizado
- [ ] Git commit: "cd-content-002: Crear 4 páginas ecosistemas con precios dinámicos"

---

## 🚨 Troubleshooting

### ❌ Escenario A: "Precios no aparecen dinámicamente"

**Causa:** PHP no se interpreta o constantes GANO_PFID_* no existen

**Solución:**
1. Verifica que `GANO_PFID_ESSENTIAL_HOSTING` está definida en `functions.php`
   ```bash
   grep -n "GANO_PFID_ESSENTIAL" /ruta/functions.php
   ```
2. En Elementor, usa widget **Code** en lugar de **Text** si HTML con PHP no funciona
3. Alternativa: texto estático "[desde 250.000 COP/mes]" post-validación de precios RCC

---

### ❌ Escenario B: "Redirección a carrito no funciona"

**Causa:** Plugin `gano-reseller-enhancements` inactivo O URL incorrecta

**Solución:**
1. Verifica que plugin está **Active** en wp-admin
   ```
   wp-admin → Plugins → gano-reseller-enhancements
   ```
2. Valida URL en botón:
   ```
   https://www.godaddy.com/reseller/cart?pfid=XXXX
   ```
   Reemplaza XXXX con valor real de PFID (ej: 4729)
3. Test manual en navegador (pega URL directa)

---

### ❌ Escenario C: "Lighthouse <85 en mobile"

**Causa probable:** Imágenes heavy en hero

**Solución:**
1. Optimiza imagen hero a <100 KB
2. Activa lazy loading (Elementor → Image → Advanced → Lazy Load = ON)
3. Recarga y re-test Lighthouse

---

## 📞 Escalaciones

Si encuentras:
- **Constantes GANO_PFID_* vacías:** Contacta a Claude (cd-repo-002 puede no estar hecha correctamente)
- **Carrito no responde:** Contacta soporte GoDaddy Reseller
- **Elementor no guarda cambios:** Limpia caché WordPress (`wp cache flush`)

---

## 🔄 Post-implementación

**Próximos pasos:**
1. **Analytics:** Monitorear traffic a estas 4 páginas (Google Analytics)
2. **Conversion tracking:** Configurar evento "Click Plan CTA" en GA4
3. **Validación de checkout:** Test completo de compra (staging primero)
4. **Optimización A/B:** Si tasas de conversión bajas, probar variantes de copy/CTA

---

**Generado por:** Claude Dispatch (cd-content-002 implementation guide)  
**Última actualización:** 2026-04-19 22:30 UTC  
**Próximas tareas:** ag-phase4-001 (Antigravity setup), cd-repo-003 (guías operativas)

---

## Apéndice A: Plantilla de precio por plan

| Plan | PFID Const | Valor esperado (COP/mes) | Facturación | Descuento anual |
|------|-----------|----------|-------------|--|
| Essential | GANO_PFID_ESSENTIAL_HOSTING | [PENDIENTE] | Flexible | 10% |
| Fortaleza | GANO_PFID_PREMIUM_HOSTING | [PENDIENTE] | Flexible (recomendado 1 año) | 15% |
| Bastión | GANO_PFID_ELITE_HOSTING | [PENDIENTE] | 1 año recomendado | 20-25% |

---

## Apéndice B: Disclaimers Reseller (legal compliance)

**Ubicación:** Final de cada página detail  
**Texto estándar:**

```html
<p class="gano-el-reseller-disclaimer">
  Este servicio es suministrado por Gano Digital, reseller autorizado de 
  GoDaddy Managed WordPress. Los productos están alojados en infraestructura 
  GoDaddy bajo términos separados. 
  <a href="/terminos-reseller">Ver términos del acuerdo Reseller</a>.
</p>
```

Asegúrate que:
- [ ] Disclaimer presente en cada detail page
- [ ] Link a `/terminos-reseller` funciona
- [ ] Texto legible (contraste ≥4.5:1)
