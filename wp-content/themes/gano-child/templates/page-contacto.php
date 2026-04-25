<?php
/**
 * Template Name: Contacto
 * @package gano-child
 */
get_header(); ?>

<main id="gano-main-content" class="gano-trust-page gano-contacto gano-km-shell">

  <section class="gano-dark-section gano-trust-hero">
    <div class="gano-container">
      <p class="gano-label-pill gano-km-live-badge">Contacto</p>
      <h1>Antes de comprar, habla con nosotros.</h1>
      <p class="gano-trust-hero__sub">
        Resolvemos dudas técnicas y comerciales sin presión de ventas.
        El equipo responde en español, con contexto real de tu proyecto.
      </p>
    </div>
  </section>

  <section class="gano-trust-section">
    <div class="gano-container gano-contacto__layout">

      <!-- FORMULARIO -->
      <div class="gano-contacto__form">
        <h2>Escríbenos</h2>
        <?php gano_contacto_print_error_notice(); ?>
        <?php
        // Si hay Contact Form 7 instalado, usa el shortcode aquí:
        // echo do_shortcode('[contact-form-7 id="..." title="Contacto Gano"]');
        // Por defecto, formulario HTML nativo con action al endpoint de WP:
        ?>
        <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="gano-form">
          <?php wp_nonce_field('gano_contacto_form', 'gano_contacto_nonce'); ?>
          <input type="hidden" name="action" value="gano_contacto_submit">

          <div class="gano-form__group">
            <label for="gc-nombre">Nombre <span aria-hidden="true">*</span></label>
            <input type="text" id="gc-nombre" name="nombre" required autocomplete="name"
                   aria-required="true" placeholder="Tu nombre">
          </div>

          <div class="gano-form__group">
            <label for="gc-empresa">Empresa <span class="gano-form__optional">(opcional)</span></label>
            <input type="text" id="gc-empresa" name="empresa" autocomplete="organization"
                   placeholder="Tu empresa o proyecto">
          </div>

          <div class="gano-form__group">
            <label for="gc-email">Correo electrónico <span aria-hidden="true">*</span></label>
            <input type="email" id="gc-email" name="email" required autocomplete="email"
                   aria-required="true" placeholder="tu@correo.com">
          </div>

          <div class="gano-form__group">
            <label for="gc-asunto">Asunto <span aria-hidden="true">*</span></label>
            <select id="gc-asunto" name="asunto" required aria-required="true">
              <option value="">Selecciona un asunto</option>
              <option value="comercial">Quiero conocer los planes</option>
              <option value="tecnico">Tengo una pregunta técnica</option>
              <option value="soporte">Soporte a cliente existente</option>
              <option value="otro">Otro</option>
            </select>
          </div>

          <div class="gano-form__group">
            <label for="gc-mensaje">Mensaje <span aria-hidden="true">*</span></label>
            <textarea id="gc-mensaje" name="mensaje" required aria-required="true"
                      rows="5" placeholder="Cuéntanos en qué podemos ayudarte"></textarea>
          </div>

          <p class="gano-form__privacy">
            Al enviar este formulario aceptas nuestra
            <a href="/legal/politica-de-privacidad">Política de Privacidad</a>.
            No compartimos tu información con terceros.
          </p>

          <button type="submit" class="gano-btn">Enviar mensaje</button>
        </form>
      </div>

      <!-- DATOS DE CONTACTO -->
      <aside class="gano-contacto__info">
        <h2>Datos de contacto</h2>

        <div class="gano-contacto__item">
          <strong>WhatsApp</strong>
          <a href="https://wa.me/573135646123" target="_blank" rel="noopener">
            +57 313 564 6123
          </a>
        </div>

        <div class="gano-contacto__item">
          <strong>Correo general</strong>
          <a href="mailto:hola@gano.digital">hola@gano.digital</a>
        </div>

        <div class="gano-contacto__item">
          <strong>Soporte técnico</strong>
          <a href="mailto:soporte@gano.digital">soporte@gano.digital</a>
        </div>

        <div class="gano-contacto__item">
          <strong>Asuntos legales</strong>
          <a href="mailto:legal@gano.digital">legal@gano.digital</a>
        </div>

        <div class="gano-contacto__item">
          <strong>Horario de atención</strong>
          <span>Soporte por ticket disponible 24/7 — tiempo de primera respuesta: hasta 8 horas hábiles</span>
        </div>

        <hr class="gano-contacto__divider">

        <p class="gano-contacto__cta-link">
          ¿Ya sabes qué necesitas?<br>
          <a href="/ecosistemas/">Ver planes y precios →</a>
        </p>
      </aside>

    </div>
  </section>

</main>

<?php get_footer(); ?>
