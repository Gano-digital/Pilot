<?php
/**
 * Template Name: Nosotros — Quiénes somos
 * @package gano-child
 */
get_header(); ?>

<main id="gano-main-content" class="gano-trust-page gano-nosotros gano-km-shell">

  <!-- HERO -->
  <section class="gano-dark-section gano-trust-hero">
    <div class="gano-container">
      <p class="gano-label-pill gano-km-live-badge">Quiénes somos</p>
      <h1>Hosting en serio. Sin rodeos.</h1>
      <p class="gano-trust-hero__sub">
        El mercado colombiano merece opciones de hosting que no requieran leer la letra pequeña tres veces para entender qué compraste.
        Gano Digital existe para eso.
      </p>
    </div>
  </section>

  <!-- QUÉ HACEMOS -->
  <section class="gano-trust-section">
    <div class="gano-container gano-el-prose-narrow">
      <h2>Ecosistemas WordPress de alto rendimiento para negocios en Colombia y LATAM.</h2>
      <p>
        No somos un datacenter. Somos el equipo que configura, protege y sostiene tu infraestructura.
        Trabajamos con infraestructura de clase mundial —aprovisionada vía programa reseller de GoDaddy—
        y le sumamos la capa que más duele cuando falta: configuración experta, hardening activo
        y alguien al otro lado del chat que entiende WordPress y negocio en Colombia.
      </p>
      <p>
        No prometemos lo que no podemos cumplir. Sí prometemos transparencia, respuesta
        y un modelo de servicio que escala con tu empresa.
      </p>
    </div>
  </section>

  <!-- DIFERENCIADORES -->
  <section class="gano-trust-section gano-trust-section--alt">
    <div class="gano-container">
      <h2>Lo que hacemos directamente</h2>
      <div class="gano-trust-grid">
        <?php
        $diferenciadores = [
          ['icon' => '🌐', 'titulo' => 'Español y COP',         'texto' => 'Soporte, contratos y facturación en tu idioma y moneda. Sin sorpresas de tipo de cambio.'],
          ['icon' => '⚡', 'titulo' => 'WordPress con foco',    'texto' => 'No hacemos hosting genérico. Cada ecosistema está diseñado para cargas y flujos de WordPress.'],
          ['icon' => '🛡',  'titulo' => 'Hardening incluido',   'texto' => 'Seguridad activa desde el día uno: CSP, Wordfence, rate limiting, backups monitoreados.'],
          ['icon' => '🔍', 'titulo' => 'Monitoreo y respuesta', 'texto' => 'Visibilidad operativa real sobre tu instalación. Alertas antes del incidente, no después.'],
          ['icon' => '📋', 'titulo' => 'Transparencia reseller','texto' => 'Decimos claramente quién provee la infraestructura y qué rol juega Gano Digital. Sin letra pequeña.'],
          ['icon' => '🔑', 'titulo' => 'Soberanía del sitio',   'texto' => 'Tus credenciales, tu acceso, tu contenido. Migración asistida sin retención de datos.'],
        ];
        foreach ($diferenciadores as $d) : ?>
          <article class="gano-el-pillar">
            <span aria-hidden="true" class="gano-nosotros__icon"><?php echo $d['icon']; ?></span>
            <h3><?php echo esc_html($d['titulo']); ?></h3>
            <p><?php echo esc_html($d['texto']); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- TRANSPARENCIA RESELLER -->
  <section class="gano-trust-section">
    <div class="gano-container gano-el-prose-narrow">
      <h2>Sobre nuestra infraestructura</h2>
      <div class="gano-trust-disclaimer">
        <p>
          Gano Digital no opera un datacenter propio. Somos un operador de ecosistemas WordPress
          que aprovisionamos capacidad de servidor a través del <strong>programa de reseller
          autorizado de GoDaddy</strong>. Eso nos permite ofrecer infraestructura de nivel
          empresarial —NVMe, CDN, redundancia, certificados SSL— sin el costo de construir
          y mantener centros de datos.
        </p>
        <p>
          La transparencia es parte del servicio. Si en algún momento tienes preguntas
          sobre las condiciones de la infraestructura subyacente, te las respondemos con claridad.
          Puedes consultar los términos de GoDaddy en
          <a href="https://www.godaddy.com/es/legal" target="_blank" rel="noopener">godaddy.com/es/legal</a>.
        </p>
      </div>
      <p class="gano-nosotros__legal-links">
        <a href="/legal/terminos-y-condiciones">Ver Términos y Condiciones</a>
        &nbsp;·&nbsp;
        <a href="/legal/acuerdo-de-nivel-de-servicio">Ver nuestro SLA</a>
      </p>
    </div>
  </section>

  <!-- EQUIPO -->
  <section class="gano-trust-section gano-trust-section--alt">
    <div class="gano-container gano-el-prose-narrow">
      <h2>El equipo</h2>
      <article class="gano-team-member">
        <div class="gano-team-member__header">
          <h3>Diego Sandoval</h3>
          <p class="gano-team-member__role">Fundador</p>
        </div>
        <p class="gano-team-member__bio">
          Psicólogo organizacional y consultor técnico con experiencia en hosting, seguridad web y soluciones
          digitales para empresas colombianas. Combina visión tecnológica con enfoque humano para construir
          infraestructura WordPress que funciona sin excusas — en pesos, en español, con soporte real.
        </p>
      </article>
    </div>
  </section>

  <!-- CTA -->
  <section class="gano-dark-section gano-nosotros__cta">
    <div class="gano-container">
      <h2>¿Quieres saber qué arquitectura corresponde a tu etapa?</h2>
      <p class="gano-nosotros__cta-copy">Habla con el equipo. Sin formularios de ventas agresivos.</p>
      <a href="/contacto" class="gano-btn">Habla con el equipo</a>
      &nbsp;&nbsp;
      <a href="/ecosistemas" class="gano-nosotros__cta-link">Ver ecosistemas →</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
