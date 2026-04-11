<?php
/**
 * Template Name: Contacto — Gracias
 * Página de confirmación tras enviar el formulario (redirect desde contact-form-handler).
 *
 * @package gano-child
 */
get_header(); ?>

<main id="gano-main-content" class="gano-trust-page gano-contacto-gracias">

  <section class="gano-dark-section gano-trust-hero">
    <div class="gano-container">
      <p class="gano-label-pill">Contacto</p>
      <h1>¡Mensaje recibido!</h1>
      <p class="gano-trust-hero__sub">
        Revisaremos tu solicitud y te responderemos en <strong>24–48 horas</strong> hábiles.
        Si el asunto es urgente, indícalo en un nuevo mensaje o escribe a
        <a href="mailto:hola@gano.digital">hola@gano.digital</a>.
      </p>
      <p style="margin-top:1.5rem;">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gano-btn">Volver al inicio</a>
        &ensp;
        <a href="<?php echo esc_url( home_url( '/contacto' ) ); ?>" class="gano-btn" style="background:transparent;border:1px solid var(--gano-gold,#D4AF37);color:var(--gano-gold,#D4AF37);">
          Enviar otro mensaje
        </a>
      </p>
    </div>
  </section>

</main>

<?php get_footer(); ?>
