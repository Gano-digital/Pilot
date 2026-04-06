<?php
/**
 * 404 — Página no encontrada
 * Microcopy: memory/content/microcopy-wave3-kit.md §4e
 *
 * @package gano-child
 */
get_header();
?>

<main id="gano-main-content" class="gano-trust-page gano-404">

  <section class="gano-dark-section" style="min-height: 60vh; display: flex; align-items: center; text-align: center;">
    <div class="gano-container" style="width: 100%;">

      <p class="gano-label-pill" aria-hidden="true">404</p>
      <h1 style="font-size: var(--gano-fs-4xl, 2.25rem); font-weight: 700; line-height: 1.1; max-width: 640px; margin: .75rem auto 1rem;">
        Esta página no existe (todavía)
      </h1>
      <p style="color: rgba(255,255,255,.75); max-width: 520px; margin: 0 auto 2rem; line-height: 1.65;">
        El enlace puede estar desactualizado o la página fue movida.
        No se ha perdido nada importante: desde aquí puedes ir a donde necesitas.
      </p>

      <div style="display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="gano-btn">
          Ir al inicio
        </a>
        <a href="<?php echo esc_url( home_url( '/ecosistemas' ) ); ?>"
           style="color: #fff; text-decoration: underline; align-self: center; font-weight: 500;">
          Ver planes →
        </a>
      </div>

      <p style="margin-top: 2rem; font-size: .875rem; color: rgba(255,255,255,.5);">
        ¿Crees que esto es un error?
        <a href="<?php echo esc_url( home_url( '/contacto' ) ); ?>"
           style="color: rgba(255,255,255,.75); text-decoration: underline;">
          Escríbenos
        </a>.
      </p>

    </div>
  </section>

</main>

<?php get_footer(); ?>
