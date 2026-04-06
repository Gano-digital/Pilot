<?php
/**
 * Template Name: Contacto
 * @package gano-child
 */
get_header(); ?>

<main id="gano-main-content" class="gano-trust-page gano-contacto">

  <section class="gano-dark-section gano-trust-hero">
    <div class="gano-container">
      <p class="gano-label-pill">Contacto</p>
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
          <span><!-- [NIT_PENDIENTE]: confirmar horario real antes de publicar -->
            Por confirmar — soporte por ticket disponible 24/7
          </span>
        </div>

        <hr style="margin:1.5rem 0; border-color:#e2e8f0;">

        <p style="font-size:.875rem; color:#64748b;">
          ¿Ya sabes qué necesitas?<br>
          <a href="/ecosistemas">Ver planes y precios →</a>
        </p>
      </aside>

    </div>
  </section>

</main>

<style>
.gano-contacto__layout { display: grid; grid-template-columns: 1fr 360px; gap: 3rem; align-items: start; }
.gano-contacto__info { background: #f8fafc; border-radius: 12px; padding: 2rem; }
.gano-contacto__item { margin-bottom: 1.25rem; }
.gano-contacto__item strong { display: block; font-size: .75rem; text-transform: uppercase; letter-spacing: .06em; color: #64748b; margin-bottom: .25rem; }
.gano-contacto__item a { color: var(--gano-blue, #2952CC); text-decoration: none; font-weight: 600; }
.gano-contacto__item a:hover { text-decoration: underline; }
.gano-form { display: flex; flex-direction: column; gap: 1.25rem; }
.gano-form__group { display: flex; flex-direction: column; gap: .375rem; }
.gano-form__group label { font-size: .875rem; font-weight: 600; }
.gano-form__optional { font-weight: 400; color: #94a3b8; }
.gano-form__group input,
.gano-form__group select,
.gano-form__group textarea { padding: .625rem .875rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1rem; font-family: inherit; width: 100%; box-sizing: border-box; }
.gano-form__group input:focus,
.gano-form__group select:focus,
.gano-form__group textarea:focus { outline: 3px solid var(--gano-blue, #2952CC); outline-offset: 2px; border-color: var(--gano-blue, #2952CC); }
.gano-form__privacy { font-size: .8125rem; color: #64748b; margin: 0; }
@media (max-width: 768px) { .gano-contacto__layout { grid-template-columns: 1fr; } }
</style>

<?php get_footer(); ?>
