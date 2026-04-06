<?php
/**
 * Template Name: Nosotros — Quiénes somos
 * @package gano-child
 */
get_header(); ?>

<main id="gano-main-content" class="gano-trust-page gano-nosotros">

  <!-- HERO -->
  <section class="gano-dark-section gano-trust-hero">
    <div class="gano-container">
      <p class="gano-label-pill">Quiénes somos</p>
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
            <span aria-hidden="true" style="font-size:1.75rem;"><?php echo $d['icon']; ?></span>
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
      <p style="margin-top:1.5rem;">
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
      <!-- [NIT_PENDIENTE]: Diego — añadir nombre, rol y bio real cuando esté disponible.
           Omitir esta sección si no hay datos verificables; mejor no tener que tener stock. -->
      <p style="color:#64748b; font-style:italic;">
        Sección en preparación. Próximamente: el equipo detrás de Gano Digital.
      </p>
    </div>
  </section>

  <!-- CTA -->
  <section class="gano-dark-section" style="text-align:center; padding:4rem 1.5rem;">
    <div class="gano-container">
      <h2>¿Quieres saber qué arquitectura corresponde a tu etapa?</h2>
      <p style="color:rgba(255,255,255,.75); margin-bottom:1.5rem;">Habla con el equipo. Sin formularios de ventas agresivos.</p>
      <a href="/contacto" class="gano-btn">Habla con el equipo</a>
      &nbsp;&nbsp;
      <a href="/ecosistemas" style="color:#fff; text-decoration:underline;">Ver ecosistemas →</a>
    </div>
  </section>

</main>

<style>
.gano-trust-hero { padding: 5rem 1.5rem 4rem; text-align: center; }
.gano-trust-hero h1 { font-size: var(--gano-fs-4xl, 2.25rem); font-weight: 700; line-height: 1.1; max-width: 720px; margin: .75rem auto 1rem; }
.gano-trust-hero__sub { color: rgba(255,255,255,.75); max-width: 600px; margin: 0 auto; line-height: 1.65; }
.gano-trust-section { padding: 4rem 1.5rem; }
.gano-trust-section--alt { background: #f8fafc; }
.gano-trust-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 1.25rem; margin-top: 2rem; }
.gano-trust-disclaimer { background: #f1f5f9; border-left: 4px solid var(--gano-blue, #2952CC); padding: 1.25rem 1.5rem; border-radius: 0 8px 8px 0; line-height: 1.65; }
.gano-trust-disclaimer p { margin: 0 0 .75rem; }
.gano-trust-disclaimer p:last-child { margin: 0; }
@media (max-width: 600px) { .gano-trust-grid { grid-template-columns: 1fr; } }
</style>

<?php get_footer(); ?>
