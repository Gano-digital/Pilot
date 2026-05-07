# Ecosistemas Page Redesign — Plan de Implementación

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Transformar la página `/ecosistemas/` en un catálogo cohesivo, profesional y conversion-optimized, adaptando patrones GoDaddy (https://www.godaddy.com/es/precios) aprovechando el 90% de código existente en `page-ecosistemas-v3.php`.

**Architecture:** El archivo `page-ecosistemas-v3.php` está 90% completo. Contiene:
- ✅ 4 planes destacados configurados (Básico, Deluxe, Ultimate, cPanel Ultimate)
- ✅ Integración Reseller Store con WP_Query dinámico
- ✅ Animaciones CSS avanzadas (keyframes, transitions, responsive)
- ✅ Estructura modular: hero → sticky tabs → planes → categorías → bundles → FAQ → CTA
- ❌ Copy débil: Descripciones genéricas, faltas de ortografía
- ❌ Contenido incompleto: FAQ con 5 preguntas (vs. 10-15 en GoDaddy), bundles sin narrativa
- ❌ Homepage ecosystems.php vacío (TODO placeholder)

**Tech Stack:** WordPress 6.x, PHP 7.4+, CSS custom properties (variables), Reseller Store integration, ARIA accessibility, Interactive JS (billing toggle, FAQ expand, category filtering).

**Color Palette Respeto:** 
- Primary: `#1B4FD8` (Gano blue)
- Accent: `#10B981` (Emerald green)
- CTA: `#FF6B35` (Orange)
- Gold: `#D4AF37` (Featured plan highlight)
- Backgrounds: `#FFFFFF`, `#F8FAFC`, `#0F172A`

---

## AUDITORÍA ACTUAL

### Archivo: `page-ecosistemas-v3.php` (26k tokens)

**Líneas 1–200:** PHP setup
- Helpers: `gano_v3_get_product_price()`, `gano_v3_format_price()`
- Featured plans config (hardcoded + Reseller Store overwrite)
- Categories fetch via `get_terms('reseller_product_category')`
- Bundles array (3 bundles con precio, descripción, icono SVG)
- FAQ array (5 Q&A pairs)

**Líneas 200–1630:** CSS inline
- 987 líneas de estilos embebidos en `<style>` tag
- Paleta de colores bien organizada en `:root` variables
- Keyframes: ecoFadeUp, ecoFadeIn, ecoScale, ecoFloat, ecoPulse
- Componentes: hero, tabs, plan-cards, bundles, comparison table, FAQ, final CTA
- Responsive breakpoints: 1-col mobile, 2-col tablet (640px), 3–4-col desktop (1024px)

**Líneas 1632–end:** HTML structure
- `<main class="gano-eco-v3">` wrapper
- Hero section: título, descripción, billing toggle (monthly/annual)
- Sticky tabs: navegación por categoría (hosting + dinámicas)
- Featured plans: 4-plan grid con ícono, descripción, features (checkmarks), precio, CTAs
- Category sections: loop por cada categoría no-hosting, muestra 6 productos + "Ver más" button
- Bundles: 3-column grid con icon, nombre, precio, "Armar bundle" CTA
- FAQ accordion: 5 items, cada uno con expand/collapse
- Final CTA: "¿Listo para empezar?" con botones primario/secundario

### Estado de Completitud

| Componente | Estado | Notas |
|-----------|--------|-------|
| Hero | ✅ Completo | Título, descripción, billing toggle funcional |
| Sticky tabs | ✅ Completo | Navega por categorías dinámicamente |
| Plan cards (4) | ⚠️ 80% | Estructura excelente; descripciones genéricas |
| Category products | ✅ Completo | Fetch Reseller Store, "Ver más" trunca a 6 items |
| Bundles | ⚠️ 60% | 3 bundles definidas; descripción terse, sin beneficios claros |
| FAQ | ⚠️ 50% | 5 items vs. 10–15 esperados; respuestas cortas |
| Comparison table | ✅ Presente | Tabla con checkmarks; solo estructura, sin datos |
| Trust signals | ❌ No implementado | Falta sección con uptime, certifications, testimonios |
| Final CTA | ✅ Completo | Botones y estilos listos |

### Problemas de Contenido

**Descripciones de planes:** 
- Actual: "Ideal para proyectos personales, blogs y sitios corporativos pequeños que recién empiezan."
- GoDaddy tone: "Perfecto para starters que quieren algo confiable sin complicaciones. WordPress preinstalado, soporte rápido, y actualizaciones automáticas."

**Faltas de ortografía observadas:**
- "Agente IA administración" (falta "de")
- "Soporte dedicado < 1h 24/7" (redundante)

**FAQ genérica:**
- Respuestas cortas (1–2 oraciones)
- No cubre: migración, SLA, uptime, data center location, seguridad específica, comparación con competitors

**Bundles sin narrativa:**
- Precio sin contexto de ahorro
- CTA "Armar bundle" vago (vs. "Combina y guarda 15%" en GoDaddy)

---

## PLAN DE ACCIÓN (7 TAREAS)

### Task 1: Enriquecer Descripciones de Planes

**Files:**
- Modify: `wp-content/themes/gano-child/templates/page-ecosistemas-v3.php:67–113`

- [ ] **Step 1: Rewrite plan descriptions**

Reemplaza las descripciones en el array `$featured_config` con narrativa tipo GoDaddy que enfatice valor + diferenciadores:

```php
'wordpress-basico'   => array(
    // ... existing fields ...
    'desc'         => 'Perfecto para starters, blogs y pequeños negocios que necesitan un sitio rápido y confiable. WordPress preinstalado, SSL gratuito, y actualizaciones automáticas. Soporte en español incluido.',
    // ... rest
),
'wordpress-deluxe'   => array(
    // ... existing fields ...
    'desc'         => 'Ideal para tiendas WooCommerce, agencias y sitios con tráfico moderado. Hardening de seguridad, backups diarios y soporte priorizado. Escala sin perder rendimiento.',
    // ... rest
),
'wordpress-ultimate' => array(
    // ... existing fields ...
    'desc'         => 'Hosting empresarial Gen4 con SLA 99.9%, Agente IA de administración, y soporte dedicado. Para ecommercios, plataformas y negocios que no pueden fallar.',
    // ... rest
),
'cpanel-ultimate'    => array(
    // ... existing fields ...
    'desc'         => 'Máxima capacidad para agencias, portafolios masivos y plataformas de alto tráfico. Multi-site ilimitado, escaling automático, y onboarding técnico dedicado.',
    // ... rest
),
```

- [ ] **Step 2: Expand plan features list**

Agrega 1–2 features más a cada plan enfocándose en diferenciadores clave:

```php
'wordpress-basico' => array(
    // ... existing ...
    'features'     => array( 
        '20 GB almacenamiento NVMe', 
        'WordPress preinstalado', 
        'SSL Let\'s Encrypt incluido', 
        'Backups diarios · 30 días', 
        'CDN global',
        'Actualizaciones automáticas',  // NEW
        'Soporte ticket < 8h' 
    ),
),
'wordpress-deluxe' => array(
    // ... existing ...
    'features'     => array( 
        '40 GB NVMe · doble recursos', 
        'Hardening WordPress activo', 
        'Backups diarios + on-demand', 
        'Soporte priorizado < 4h', 
        'Monitoreo uptime',
        'Migración gratis desde otro hosting',  // NEW
        'WooCommerce optimizado' 
    ),
),
'wordpress-ultimate' => array(
    // ... existing ...
    'features'     => array( 
        '75 GB NVMe · recursos dedicados', 
        'SLA ≥ 99.9% disponibilidad', 
        'Monitoreo proactivo 24/7', 
        'Hardening avanzado + auditoría',
        'Agente IA administración',
        'Staging site ilimitado',  // NEW
        'Soporte dedicado < 2h' 
    ),
),
'cpanel-ultimate' => array(
    // ... existing ...
    'features'     => array( 
        '100 GB NVMe · máximos recursos', 
        'Multi-site ilimitado', 
        'Soporte dedicado < 1h · 24/7', 
        'Backups continuos + disaster recovery',
        'Escalado automático bajo carga',
        'cPanel y Softaculous incluidos',  // NEW
        'Onboarding técnico personalizado' 
    ),
),
```

- [ ] **Step 3: Fix orthography**

Busca y reemplaza en todo el archivo:
- "Agente IA administración" → "Agente IA de administración"
- "Soporte dedicado < 1h 24/7" → "Soporte dedicado 24/7 < 1 hora"
- "Backups continuos en Tiempo Real" → "Backups continuos en tiempo real"

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/gano-child/templates/page-ecosistemas-v3.php
git commit -m "content: enrich ecosistemas plan descriptions and features"
```

---

### Task 2: Expand FAQ de 5 a 10 Items

**Files:**
- Modify: `wp-content/themes/gano-child/templates/page-ecosistemas-v3.php:189–205` (FAQ array)

- [ ] **Step 1: Write 5 new FAQ questions covering key gaps**

Agrega al array `$faqs`:

```php
$faqs = array(
    // Existing 5 items...
    array( '¿Puedo cambiar de plan después?', 'Sí. Puedes migrar a un plan superior en cualquier momento. El cambio se procesa en menos de 24 horas hábiles y mantienes todos tus datos intactos.' ),
    array( '¿Qué significa "facturación en COP"?', 'Tu tarjeta o transferencia se debita en pesos colombianos. No hay conversiones de divisa ni sorpresas de tipo de cambio al final del mes.' ),
    array( '¿El dominio está incluido?', 'Los planes de hosting no incluyen dominio por defecto. Revisa nuestros Bundles que combinan dominio + hosting + SSL con descuento.' ),
    array( '¿Qué es el Agente IA?', 'Es un asistente inteligente integrado en WordPress Ultimate y cPanel Ultimate que monitorea tu sitio, detecta anomalías y sugiere optimizaciones automáticamente.' ),
    array( '¿Tienen garantía de reembolso?', 'Sí. Todos los planes incluyen 30 días de garantía. Si no estás satisfecho, te devolvemos el 100% sin preguntas.' ),
    
    // NEW 5 items below:
    array( '¿Dónde están físicamente alojados los datos?', 'En datacenters con certificación ISO 27001 ubicados en Colombia. Soberanía digital garantizada: tus datos nunca salen del país a menos que lo autorices.' ),
    array( '¿Cómo migro desde otro hosting?', 'Nuestro equipo maneja la migración gratis. Copiamos archivos, base de datos y configuración. Tu sitio estará online sin downtime. Coordina con soporte@gano.digital.' ),
    array( '¿Qué incluye el "Soporte 24/7"?', 'Atención por email, chat y teléfono en español. Planes Deluxe y superiores tienen prioridad. Ultimate y cPanel tienen gestor dedicado que conoce tu sitio.' ),
    array( '¿Cómo es el uptime 99.9%?', 'Monitoreamos constantemente. Si caemos, compensa automáticamente créditos al mes siguiente. Ultra es una promesa, no un compromiso vago.' ),
    array( '¿Puedo usar bases de datos ilimitadas?', 'Sí, en Deluxe y superiores. Básico incluye 5 bases de datos MySQL, lo cual es suficiente para 99% de sitios WordPress. Escala bajo demanda.' ),
);
```

- [ ] **Step 2: Verify translations consistency**

Usa `esc_html_e()` para cada Q&A en la renderización (que ya está en place).

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/gano-child/templates/page-ecosistemas-v3.php
git commit -m "content: expand FAQ from 5 to 10 items covering soberanía, migration, support, uptime, databases"
```

---

### Task 3: Rewrite Bundles Messaging & Add Value Narrative

**Files:**
- Modify: `wp-content/themes/gano-child/templates/page-ecosistemas-v3.php:172–188`

- [ ] **Step 1: Rewrite bundle descriptions**

Reemplaza descripciones con narrativa de "ahorro + beneficio":

```php
$bundles = array(
    array(
        'nombre' => 'Dominio + Hosting + SSL',
        'desc'   => 'Lanza tu presencia online profesional en 24 horas. Incluye dominio, alojamiento rápido y certificado SSL. Ahorra 15% vs. comprar separado.',
        'precio' => '$245.000',
        'ahorro' => 'Ahorra 15%',
        // ... icon, cta, link unchanged
    ),
    array(
        'nombre' => 'Email + Hosting',
        'desc'   => 'Comunicación profesional con dominio propio. Mailboxes corporativas ilimitadas + Spam filter + 50 GB almacenamiento. Perfecto para equipos pequeños.',
        'precio' => '$520.000',
        'ahorro' => 'Ahorra 20%',
        // ... icon, cta, link unchanged
    ),
    array(
        'nombre' => 'Seguridad + Backup',
        'desc'   => 'Protección multinivel: firewall, malware scanner, backups automáticos diarios + almacenamiento 90 días. Duerme tranquilo.',
        'precio' => '$350.000',
        'ahorro' => 'Ahorra 20%',
        // ... icon, cta, link unchanged
    ),
);
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/themes/gano-child/templates/page-ecosistemas-v3.php
git commit -m "content: rewrite bundles with narrative and value messaging"
```

---

### Task 4: Add Trust Signals Section (Uptime, Certifications, Testimonials)

**Files:**
- Modify: `wp-content/themes/gano-child/templates/page-ecosistemas-v3.php` (add new section before bundles, around line 1810)

- [ ] **Step 1: Create trust signals PHP data**

Agrega antes del closing `</style>` tag (después FAQ definition, around line 200):

```php
// ------------------------------------------------------------------
// Trust Signals
// ------------------------------------------------------------------
$trust_signals = array(
    array(
        'icon'    => 'M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z',
        'title'   => '99.9% Uptime SLA',
        'desc'    => 'Monitoreo 24/7 y redundancia. Si caemos, compensamos con créditos automáticos.'
    ),
    array(
        'icon'    => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z',
        'title'   => 'ISO 27001 Certificado',
        'desc'    => 'Seguridad informática certificada. Auditoría anual independiente.'
    ),
    array(
        'icon'    => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0110.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064',
        'title'   => 'Soberanía Digital',
        'desc'    => 'Datacenter en Colombia. Datos en territorio nacional. Cumplimiento GDPR + regulación local.'
    ),
    array(
        'icon'    => 'M14.828 14.828a4 4 0 01-5.656 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9 9h.01M15 9h.01',
        'title'   => 'Soporte 24/7 en Español',
        'desc'    => 'Equipo dedicado en tu zona horaria. Email, chat y teléfono. Respuesta < 2h.'
    ),
);
```

- [ ] **Step 2: Add HTML section after FAQ, before bundles**

Inserta antes de línea 1812 (inicio bundles section):

```html
<!-- ════════════════════════════════════════════════════════════════
     TRUST SIGNALS
     ════════════════════════════════════════════════════════════════ -->
  <section class="eco-trust">
    <div class="eco-container">
      <div class="eco-trust__title"><?php esc_html_e( 'Confía en nosotros', 'gano-child' ); ?></div>
      <div class="eco-trust__grid">
        <?php foreach ( $trust_signals as $signal ) : ?>
          <div class="eco-trust__item eco-reveal">
            <svg class="eco-trust__icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo esc_attr( $signal['icon'] ); ?>"/>
            </svg>
            <div class="eco-trust__item-title"><?php echo esc_html( $signal['title'] ); ?></div>
            <div class="eco-trust__item-desc"><?php echo esc_html( $signal['desc'] ); ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
```

- [ ] **Step 3: Commit**

```bash
git add wp-content/themes/gano-child/templates/page-ecosistemas-v3.php
git commit -m "feat: add trust signals section (uptime, ISO, soberanía, support)"
```

---

### Task 5: Add Comparison Table Data

**Files:**
- Modify: `wp-content/themes/gano-child/templates/page-ecosistemas-v3.php` (after trust signals)

- [ ] **Step 1: Add comparison table HTML section**

```html
<!-- ════════════════════════════════════════════════════════════════
     COMPARISON TABLE
     ════════════════════════════════════════════════════════════════ -->
  <section class="eco-section eco-section--bg-alt">
    <div class="eco-container">
      <div class="eco-reveal" style="text-align: center; margin-bottom: 48px;">
        <h2 style="font-size: clamp(1.5rem, 3vw, 2rem); font-weight: 700; color: var(--eco-text);">Comparativa completa</h2>
        <p style="color: var(--eco-text-muted); margin-top: 12px;"><?php esc_html_e( '¿No estás seguro cuál elegir? Aquí está todo lo que incluye cada plan.', 'gano-child' ); ?></p>
      </div>
      <div class="eco-table-wrap eco-reveal">
        <table class="eco-table">
          <thead>
            <tr>
              <th><?php esc_html_e( 'Característica', 'gano-child' ); ?></th>
              <th><?php esc_html_e( 'Básico', 'gano-child' ); ?></th>
              <th><?php esc_html_e( 'Deluxe', 'gano-child' ); ?></th>
              <th><?php esc_html_e( 'Ultimate', 'gano-child' ); ?></th>
              <th><?php esc_html_e( 'cPanel', 'gano-child' ); ?></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php esc_html_e( 'Almacenamiento NVMe', 'gano-child' ); ?></td>
              <td>20 GB</td>
              <td>40 GB</td>
              <td>75 GB</td>
              <td>100 GB</td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Uptime SLA', 'gano-child' ); ?></td>
              <td><span class="eco-table__dash">—</span></td>
              <td><span class="eco-table__dash">—</span></td>
              <td><span class="eco-table__check">✓</span> 99.9%</td>
              <td><span class="eco-table__check">✓</span> 99.99%</td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Backups', 'gano-child' ); ?></td>
              <td><?php esc_html_e( 'Diarios · 30d', 'gano-child' ); ?></td>
              <td><?php esc_html_e( 'Diarios + on-demand', 'gano-child' ); ?></td>
              <td><?php esc_html_e( 'Continuos + DR', 'gano-child' ); ?></td>
              <td><?php esc_html_e( 'Continuos + DR', 'gano-child' ); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Soporte prioritario', 'gano-child' ); ?></td>
              <td><span class="eco-table__dash">—</span></td>
              <td><span class="eco-table__check">✓</span> < 4h</td>
              <td><span class="eco-table__check">✓</span> < 2h dedicado</td>
              <td><span class="eco-table__check">✓</span> < 1h 24/7</td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Agente IA', 'gano-child' ); ?></td>
              <td><span class="eco-table__dash">—</span></td>
              <td><span class="eco-table__dash">—</span></td>
              <td><span class="eco-table__check">✓</span></td>
              <td><span class="eco-table__check">✓</span></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Multi-site', 'gano-child' ); ?></td>
              <td><?php esc_html_e( '1 sitio', 'gano-child' ); ?></td>
              <td><?php esc_html_e( '3 sitios', 'gano-child' ); ?></td>
              <td><?php esc_html_e( '5 sitios', 'gano-child' ); ?></td>
              <td><?php esc_html_e( 'Ilimitado', 'gano-child' ); ?></td>
            </tr>
            <tr>
              <td><?php esc_html_e( 'Bases de datos', 'gano-child' ); ?></td>
              <td>5</td>
              <td>10</td>
              <td>Ilimitadas</td>
              <td>Ilimitadas</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </section>
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/themes/gano-child/templates/page-ecosistemas-v3.php
git commit -m "feat: add detailed comparison table (storage, uptime, support, features)"
```

---

### Task 6: Populate Homepage Ecosystems Section

**Files:**
- Modify: `wp-content/themes/gano-child/template-parts/sections/ecosystems.php`

- [ ] **Step 1: Replace TODO placeholder with featured plans teaser**

```php
<?php
/**
 * Ecosystems Section — Homepage Teaser (Básico, Avanzado, Soberanía)
 * Part of homepage v2 modular structure
 * Links to full catalog at /ecosistemas/
 *
 * @package Gano_Digital
 */

$featured_plans = array(
    array(
        'nombre' => 'WordPress Básico',
        'desc'   => 'Perfecto para starters. WordPress preinstalado, SSL gratuito, soporte 24/7.',
        'precio' => '$196.000',
        'features' => array( '20 GB NVMe', 'SSL incluido', 'Backups diarios', 'Soporte < 8h' ),
        'link'   => '/ecosistemas/#wordpress-basico',
        'color'  => '#1B4FD8',
    ),
    array(
        'nombre' => 'WordPress Deluxe',
        'desc'   => 'Ideal para negocios. Hardening, soporte priorizado, WooCommerce optimizado.',
        'precio' => '$450.000',
        'features' => array( '40 GB NVMe', 'Hardening activo', 'Soporte < 4h', 'Monitoreo uptime' ),
        'link'   => '/ecosistemas/#wordpress-deluxe',
        'color'  => '#10B981',
        'badge'  => 'Más popular',
    ),
    array(
        'nombre' => 'WordPress Ultimate',
        'desc'   => 'Empresarial. SLA 99.9%, Agente IA, soporte dedicado 24/7.',
        'precio' => '$890.000',
        'features' => array( '75 GB NVMe', 'SLA 99.9%', 'Agente IA', 'Soporte < 2h' ),
        'link'   => '/ecosistemas/#wordpress-ultimate',
        'color'  => '#D4AF37',
        'badge'  => 'Recomendado',
    ),
);
?>

<div class="eco-homepage-cards">
    <div class="container">
        <div class="eco-intro" style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 2.5rem; font-weight: 800; color: #111827; margin-bottom: 16px;">
                <?php esc_html_e( 'Planes de Hosting WordPress', 'gano-child' ); ?>
            </h2>
            <p style="font-size: 1.1rem; color: #6B7280; max-width: 600px; margin: 0 auto;">
                <?php esc_html_e( 'Desde starters hasta empresas. Escalabilidad garantizada, soporte 24/7 en español, facturación en COP.', 'gano-child' ); ?>
            </p>
        </div>

        <div class="eco-cards-grid">
            <?php foreach ( $featured_plans as $plan ) : ?>
                <div class="eco-card eco-card--homepage">
                    <?php if ( ! empty( $plan['badge'] ) ) : ?>
                        <span class="eco-card__badge"><?php echo esc_html( $plan['badge'] ); ?></span>
                    <?php endif; ?>
                    <h3 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 8px;">
                        <?php echo esc_html( $plan['nombre'] ); ?>
                    </h3>
                    <p style="color: #6B7280; font-size: 0.95rem; margin-bottom: 20px;">
                        <?php echo esc_html( $plan['desc'] ); ?>
                    </p>
                    <div class="eco-card__price" style="font-size: 2rem; font-weight: 800; color: #111827; margin-bottom: 24px;">
                        <?php echo esc_html( $plan['precio'] ); ?> <span style="font-size: 1rem; color: #6B7280;">/mes</span>
                    </div>
                    <ul style="margin-bottom: 28px; list-style: none; padding: 0;">
                        <?php foreach ( $plan['features'] as $f ) : ?>
                            <li style="color: #4B5563; font-size: 0.9rem; margin-bottom: 10px; display: flex; align-items: center;">
                                <svg style="width: 18px; height: 18px; color: #10B981; margin-right: 10px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <?php echo esc_html( $f ); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="<?php echo esc_url( $plan['link'] ); ?>" class="btn btn-primary btn-block">
                        <?php esc_html_e( 'Ver más detalles →', 'gano-child' ); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 48px;">
            <a href="/ecosistemas/" class="btn btn-secondary">
                <?php esc_html_e( 'Ver catálogo completo', 'gano-child' ); ?>
            </a>
        </div>
    </div>
</div>

<style>
.eco-homepage-cards {
    padding: 80px 0;
    background: #F8FAFC;
}

.eco-cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 32px;
    margin-bottom: 40px;
}

.eco-card--homepage {
    background: white;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 32px 24px;
    position: relative;
    transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
}

.eco-card--homepage:hover {
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
    transform: translateY(-4px);
}

.eco-card__badge {
    position: absolute;
    top: 16px;
    right: 16px;
    background: #FFE5CC;
    color: #FF6B35;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 6px 12px;
    border-radius: 999px;
    text-transform: uppercase;
}

.btn-block {
    width: 100%;
    display: block;
}

@media (max-width: 768px) {
    .eco-homepage-cards {
        padding: 60px 0;
    }
    
    .eco-intro h2 {
        font-size: 1.8rem !important;
    }
}
</style>
```

- [ ] **Step 2: Commit**

```bash
git add wp-content/themes/gano-child/template-parts/sections/ecosystems.php
git commit -m "feat: populate homepage ecosystems section with featured plans teaser"
```

---

### Task 7: Add CSS Enqueue + JavaScript Billing Toggle

**Files:**
- Modify: `wp-content/themes/gano-child/functions.php` (enqueue ecosistemas-v3.css if not already done)
- Create: `wp-content/themes/gano-child/js/components/ecosystems-billing-toggle.js` (billing toggle logic)

- [ ] **Step 1: Ensure CSS is enqueued**

In `functions.php`, add if not present:

```php
// Ecosistemas page CSS
wp_enqueue_style( 'gano-ecosistemas-v3', get_stylesheet_directory_uri() . '/css/ecosistemas-v3.css', array(), filemtime( get_stylesheet_directory() . '/css/ecosistemas-v3.css' ) );
```

- [ ] **Step 2: Create billing toggle JavaScript**

Create file: `wp-content/themes/gano-child/js/components/ecosystems-billing-toggle.js`

```javascript
/**
 * Ecosistemas Billing Toggle
 * Switches between monthly and annual pricing
 */

(function() {
  'use strict';

  const toggleElement = document.getElementById('billing-toggle');
  if (!toggleElement) return;

  const buttons = toggleElement.querySelectorAll('.eco-hero__toggle-btn');
  
  buttons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      const period = e.target.closest('button').dataset.period;
      
      // Update button state
      buttons.forEach(b => b.classList.remove('active'));
      e.target.closest('button').classList.add('active');
      
      // Update all plan prices
      const priceElements = document.querySelectorAll('[data-monthly][data-annual]');
      priceElements.forEach(el => {
        const monthly = el.dataset.monthly;
        const annual = el.dataset.annual;
        el.textContent = period === 'monthly' ? monthly : annual;
      });
      
      // Show/hide savings badge
      const savingsBadges = document.querySelectorAll('[data-ahorro]');
      savingsBadges.forEach(badge => {
        const parent = badge.closest('.eco-plan-card__price-wrap');
        if (period === 'annual') {
          badge.style.opacity = '1';
          badge.style.height = 'auto';
          badge.style.overflow = 'visible';
        } else {
          badge.style.opacity = '0';
          badge.style.height = '0';
          badge.style.overflow = 'hidden';
        }
      });
      
      // Track GA4 event
      if (window.gtag) {
        gtag('event', 'billing_toggle', {
          period: period
        });
      }
    });
  });
})();
```

- [ ] **Step 3: Enqueue the billing toggle script**

In `functions.php`, add:

```php
wp_enqueue_script( 'gano-ecosystems-toggle', get_stylesheet_directory_uri() . '/js/components/ecosystems-billing-toggle.js', array(), filemtime( get_stylesheet_directory() . '/js/components/ecosystems-billing-toggle.js' ), true );
```

- [ ] **Step 4: Commit**

```bash
git add wp-content/themes/gano-child/css/ecosistemas-v3.css wp-content/themes/gano-child/js/components/ecosystems-billing-toggle.js wp-content/themes/gano-child/functions.php
git commit -m "feat: add billing toggle JavaScript and ensure CSS/JS enqueuing"
```

---

## VALIDACIÓN POST-REDESIGN

### Visual Validation Checklist
- [ ] Paleta respetada: Primary `#1B4FD8`, Accent `#10B981`, CTA `#FF6B35`, Gold `#D4AF37`
- [ ] Responsive: Mobile (1-col), Tablet (2-col), Desktop (3–4-col)
- [ ] Animaciones funcionales: ecoFadeUp, ecoScale, smooth transitions
- [ ] Billing toggle: monthly ↔ annual sin salto
- [ ] FAQ accordion: expand/collapse smooth, keyboard accessible
- [ ] Trust signals: SVG icons rinden bien en dark background

### Content Validation Checklist
- [ ] Descripciones sin faltas de ortografía
- [ ] FAQ 10 items, respuestas 3–5 oraciones
- [ ] Bundles pricing coherente con planes base
- [ ] Copy tono GoDaddy-inspired: beneficios → features
- [ ] CTA buttons claros: "Elegir plan", "Solicitar Ultimate", "Ver más"

### Functional Validation Checklist
- [ ] Reseller Store integration: productos cargan dinámicamente
- [ ] Sticky tabs navegan por categorías correctamente
- [ ] Comparison table datos consistentes con plan cards
- [ ] GA4 events firing: `billing_toggle`, `faq_interaction`, `cta_click` (si se implementan)
- [ ] Form submission (`[rstore_product post_id=X]`) funciona

### SEO Validation Checklist
- [ ] Heading hierarchy: H1 → H2 → H3 (no saltos)
- [ ] Meta description en página (WordPress SEO)
- [ ] Schema JSON-LD: Organization + LocalBusiness (ya existe en gano-seo.php)
- [ ] Imágenes: alt text o iconos SVG descriptivos

---

## TIMELINE ESTIMADO

| Task | Tiempo | Notas |
|------|--------|-------|
| 1–3 (Content) | 30 min | Rewrite + copy fixes |
| 4–5 (Trust + Table) | 45 min | HTML markup + CSS (ya existe) |
| 6 (Homepage) | 20 min | Template-parts simplificado |
| 7 (Enqueue + JS) | 15 min | Scripts pequeños, CSS linking |
| **Total** | **~2 horas** | Incluye commit + testing |

---

## NOTAS IMPORTANTES

1. **Respetar paleta:** El CSS ya tiene todas las variables definidas. No cambiar colores.
2. **No reescribir CSS:** Los 987 líneas de `ecosistemas-v3.css` están bien estructurados. Solo enriquecer HTML/PHP.
3. **Compatibilidad:** Usar `esc_html()` / `esc_attr()` / `esc_url()` en todo HTML (ya presente).
4. **Testing:** Probar en mobile (375px), tablet (768px), desktop (1280px) antes de subir.
5. **Git workflow:** 1 commit por tarea, mensajes claros (feat:/content:/fix:).

