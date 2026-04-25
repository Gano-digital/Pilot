<?php
/**
 * Template Name: Hosting — Arquitecturas WordPress
 * @package gano-child
 */
get_header(); ?>

<main id="gano-main-content" class="gano-trust-page gano-hosting gano-km-shell">

  <!-- HERO -->
  <section class="gano-dark-section gano-trust-hero">
    <div class="gano-container">
      <p class="gano-label-pill gano-km-live-badge">Hosting WordPress</p>
      <h1>Velocidad real. Seguridad activa. Soporte que entiende.</h1>
      <p class="gano-trust-hero__sub">
        Ecosistemas de hosting diseñados exclusivamente para WordPress, con infraestructura NVMe,
        hardening desde el día uno y atención en español. Desde el primer blog hasta el e-commerce de alto tráfico.
      </p>
    </div>
  </section>

  <!-- CARACTERÍSTICAS TÉCNICAS -->
  <section class="gano-trust-section">
    <div class="gano-container">
      <h2>Lo que incluye cada ecosistema</h2>
      <div class="gano-trust-grid">
        <article class="gano-el-pillar">
          <span aria-hidden="true" class="gano-nosotros__icon">💾</span>
          <h3>Almacenamiento NVMe Gen4</h3>
          <p>Hasta 7.500 MB/s de lectura. Tu base de datos WordPress responde 3-5× más rápido que en SSD tradicional.</p>
        </article>
        <article class="gano-el-pillar">
          <span aria-hidden="true" class="gano-nosotros__icon">🔒</span>
          <h3>SSL Let's Encrypt incluido</h3>
          <p>Certificados gratuitos activados automáticamente. Wildcard Pro disponible para subdominios ilimitados.</p>
        </article>
        <article class="gano-el-pillar">
          <span aria-hidden="true" class="gano-nosotros__icon">🛡</span>
          <h3>Hardening WordPress activo</h3>
          <p>CSP, Wordfence, rate limiting, monitoreo de integridad de archivos y backups automáticos desde el minuto cero.</p>
        </article>
        <article class="gano-el-pillar">
          <span aria-hidden="true" class="gano-nosotros__icon">🌎</span>
          <h3>CDN global + Nodo Bogotá</h3>
          <p>Contenido servido desde el POP más cercano a tu visitante. Latencia &lt;20ms para usuarios en Colombia.</p>
        </article>
        <article class="gano-el-pillar">
          <span aria-hidden="true" class="gano-nosotros__icon">💬</span>
          <h3>Soporte en español</h3>
          <p>Respuesta en tu idioma, con contexto de tu negocio. No scripts genéricos ni tickets a la India.</p>
        </article>
        <article class="gano-el-pillar">
          <span aria-hidden="true" class="gano-nosotros__icon">📈</span>
          <h3>Escalado sin fricciones</h3>
          <p>Cambia de plan cuando crezcas. Sin migraciones traumáticas ni downtime. Crecemos contigo.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- COMPARATIVA RÁPIDA -->
  <section class="gano-trust-section gano-trust-section--alt">
    <div class="gano-container">
      <h2>Comparativa de ecosistemas</h2>
      <table class="gano-hosting-table">
        <thead>
          <tr>
            <th>Característica</th>
            <th>Núcleo Prime</th>
            <th>Fortaleza Delta</th>
            <th>Bastión SOTA</th>
            <th>Ultimate WP</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Almacenamiento</td>
            <td>20 GB NVMe</td>
            <td>40 GB NVMe</td>
            <td>75 GB NVMe</td>
            <td>100 GB NVMe</td>
          </tr>
          <tr>
            <td>Tráfico estimado</td>
            <td>Hasta 25K/mes</td>
            <td>Hasta 150K/mes</td>
            <td>Hasta 500K/mes</td>
            <td>1M+ /mes</td>
          </tr>
          <tr>
            <td>SSL</td>
            <td>Let's Encrypt</td>
            <td>Let's Encrypt</td>
            <td>Let's Encrypt + Wildcard opt.</td>
            <td>Wildcard Pro incluido</td>
          </tr>
          <tr>
            <td>Backups</td>
            <td>Diarios · 30 d</td>
            <td>Diarios + on-demand · 30 d</td>
            <td>Diarios + on-demand · 60 d</td>
            <td>Continuos + on-demand · 90 d</td>
          </tr>
          <tr>
            <td>Soporte</td>
            <td>Ticket · &lt;8h</td>
            <td>Chat + ticket · &lt;4h</td>
            <td>Chat + ticket 24/7 · &lt;2h</td>
            <td>Dedicado 24/7 · &lt;1h</td>
          </tr>
          <tr>
            <td>Staging</td>
            <td>—</td>
            <td>Incluido</td>
            <td>Incluido</td>
            <td>Incluido + DR</td>
          </tr>
          <tr>
            <td class="gano-hosting-table__price">Precio/mes</td>
            <td class="gano-hosting-table__price">$196.000</td>
            <td class="gano-hosting-table__price">$450.000</td>
            <td class="gano-hosting-table__price">$890.000</td>
            <td class="gano-hosting-table__price">$1.200.000</td>
          </tr>
        </tbody>
      </table>
      <p class="gano-hosting-note">
        * Precios en COP con IVA incluido. Facturación mensual o anual (2 meses gratis al pagar anualmente).
        Infraestructura aprovisionada vía programa reseller autorizado de GoDaddy.
      </p>
    </div>
  </section>

  <!-- CTA -->
  <section class="gano-dark-section gano-nosotros__cta">
    <div class="gano-container">
      <h2>¿No sabes cuál elegir?</h2>
      <p class="gano-nosotros__cta-copy">Haz nuestro diagnóstico gratuito. 3 minutos, sin compromiso.</p>
      <a href="/diagnostico/" class="gano-btn">Diagnosticar mi necesidad →</a>
      &nbsp;&nbsp;
      <a href="/ecosistemas/" class="gano-nosotros__cta-link">Ver comparativa completa →</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
