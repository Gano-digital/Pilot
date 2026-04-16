# SOTA convergence code pack (abril 2026)

Este paquete baja a codigo la convergencia visual entre:

- identidad actual de `gano-child`,
- referencias de `Gano_Digital_Showcase.html`,
- direccion visual de `obsidian-prism`.

No es una copia verbatim del prototipo; es una adaptacion operable para WordPress + Elementor.

## 1) Clases nuevas disponibles

Archivo fuente: `wp-content/themes/gano-child/css/gano-sota-convergence.css`

- `gano-km-shell`
- `gano-km-container`
- `gano-km-live-badge`
- `gano-km-hero`
- `gano-km-title`
- `gano-km-title-accent`
- `gano-km-lead`
- `gano-km-cta-row`
- `gano-km-btn-primary`
- `gano-km-btn-secondary`
- `gano-km-glass`
- `gano-km-prism`
- `gano-km-metrics` / `gano-km-metric` / `gano-km-metric__value` / `gano-km-metric__label`
- `gano-km-value-grid` / `gano-km-card`

## 2) Snippet base (hero convergente)

```html
<section class="gano-km-shell gano-on-dark">
  <div class="gano-km-container">
    <div class="gano-km-hero">
      <div>
        <span class="gano-km-live-badge">Infraestructura activa</span>
        <h1 class="gano-km-title">
          Soberania
          <span class="gano-km-title-accent">Digital</span>
          verificable.
        </h1>
        <p class="gano-km-lead">
          Rendimiento NVMe, seguridad Zero-Trust y operacion en COP para empresas
          colombianas que no negocian continuidad.
        </p>
        <div class="gano-km-cta-row">
          <a class="gano-km-btn-primary" href="/shop-premium/">Ver arquitecturas</a>
          <a class="gano-km-btn-secondary" href="/contacto/">Hablar con el equipo</a>
        </div>
      </div>
      <div class="gano-km-prism gano-km-glass">
        <p class="gano-small">Espacio reservado para visual insignia (prism/constellation).</p>
      </div>
    </div>
  </div>
</section>
```

## 3) Snippet de franja de metricas

```html
<div class="gano-km-shell">
  <div class="gano-km-container">
    <div class="gano-km-metrics">
      <article class="gano-km-metric">
        <div class="gano-km-metric__value">99.99%</div>
        <div class="gano-km-metric__label">Uptime</div>
      </article>
      <article class="gano-km-metric">
        <div class="gano-km-metric__value">&lt;4ms</div>
        <div class="gano-km-metric__label">Latencia P99</div>
      </article>
      <article class="gano-km-metric">
        <div class="gano-km-metric__value">24/7</div>
        <div class="gano-km-metric__label">Soporte es-CO</div>
      </article>
      <article class="gano-km-metric">
        <div class="gano-km-metric__value">COP</div>
        <div class="gano-km-metric__label">Facturacion local</div>
      </article>
    </div>
  </div>
</div>
```

## 4) Snippet de grid de valor

```html
<section class="gano-km-shell">
  <div class="gano-km-container">
    <div class="gano-km-value-grid">
      <article class="gano-km-card">
        <h3>NVMe enterprise</h3>
        <p>Throughput alto y latencia estable en WordPress y comercio.</p>
      </article>
      <article class="gano-km-card">
        <h3>Zero-Trust real</h3>
        <p>WAF, segmentacion y politicas de acceso por defecto.</p>
      </article>
      <article class="gano-km-card">
        <h3>Soberania en COP</h3>
        <p>Operacion local, lenguaje local, costos previsibles.</p>
      </article>
    </div>
  </div>
</section>
```

## 5) Integracion recomendada (sin romper lo existente)

1. Home (Elementor)
   - Aplicar `gano-km-shell` en el bloque hero premium.
   - Aplicar `gano-km-metrics` a la franja de confianza.
2. Ecosistemas / Shop
   - Usar `gano-km-card` para bloques de propuesta de valor no transaccionales.
3. SOTA Hub
   - Usar `gano-km-live-badge` y `gano-km-title-accent` en encabezados editoriales.

## 6) Guardrails de calidad

- No usar estilos inline nuevos para estructura.
- No introducir Tailwind ni librerias adicionales para esta capa.
- Toda animacion debe respetar `prefers-reduced-motion`.
- Claims en metricas solo si son auditables o contractualizados.
