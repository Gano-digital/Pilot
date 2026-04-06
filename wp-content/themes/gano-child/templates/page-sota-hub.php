<?php
/**
 * Template Name: Hub SOTA — Innovación Gano
 *
 * Página de índice de las 20 páginas SOTA. Lista los pilares por categoría
 * (infraestructura, seguridad, inteligencia-artificial, estrategia, rendimiento)
 * con enlace directo a cada una. Solo se muestran páginas publicadas con meta
 * _gano_sota_category definido.
 *
 * @package gano-child
 * @since   1.1.0
 */

get_header();

$categorias = [
    'infraestructura'       => [ 'label' => 'Infraestructura', 'icon' => '⚡', 'color' => '--gano-blue' ],
    'seguridad'             => [ 'label' => 'Seguridad',       'icon' => '🛡',  'color' => '--gano-orange' ],
    'inteligencia-artificial' => [ 'label' => 'Inteligencia Artificial', 'icon' => '🤖', 'color' => '--gano-gold' ],
    'estrategia'            => [ 'label' => 'Estrategia',      'icon' => '🎯', 'color' => '--gano-blue' ],
    'rendimiento'           => [ 'label' => 'Rendimiento',     'icon' => '🚀', 'color' => '--gano-orange' ],
];

$sota_pages = get_posts([
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => '_gano_sota_category',
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
]);

// Agrupar por categoría
$por_categoria = [];
foreach ( $sota_pages as $pg ) {
    $cat = get_post_meta( $pg->ID, '_gano_sota_category', true );
    if ( $cat ) {
        $por_categoria[ $cat ][] = $pg;
    }
}
?>

<main id="gano-main-content" class="gano-sota-hub">

  <!-- ── HERO ────────────────────────────────────────────────────── -->
  <section class="gano-dark-section gano-sota-hub__hero">
    <div class="gano-container" style="text-align:center; padding: 5rem 1.5rem 4rem;">
      <p class="gano-label-pill">Hub de Innovación SOTA</p>
      <h1>Tecnología de vanguardia, explicada sin hipérboles.</h1>
      <p style="color:rgba(255,255,255,.75); max-width:640px; margin:1rem auto 0; line-height:1.65;">
        Veinte artículos técnicos que documentan el estado del arte en infraestructura WordPress,
        seguridad, IA operacional y rendimiento. Escritos para quien decide, no para quien vende.
      </p>
    </div>
  </section>

  <?php if ( empty( $sota_pages ) ) : ?>
    <!-- ── Sin páginas publicadas aún ─────────────────────────────── -->
    <section style="padding:4rem 1.5rem; text-align:center;">
      <div class="gano-container">
        <h2>Contenido en preparación</h2>
        <p>Las páginas SOTA están siendo revisadas. Vuelve pronto.</p>
        <a href="/contacto" class="gano-btn">Hablar con el equipo</a>
      </div>
    </section>

  <?php else : ?>

    <!-- ── CATEGORÍAS ──────────────────────────────────────────────── -->
    <?php foreach ( $categorias as $slug => $meta ) : ?>
      <?php if ( empty( $por_categoria[ $slug ] ) ) continue; ?>
      <section class="gano-sota-hub__categoria" id="categoria-<?php echo esc_attr( $slug ); ?>">
        <div class="gano-container">
          <h2 class="gano-sota-hub__cat-title">
            <span aria-hidden="true"><?php echo $meta['icon']; ?></span>
            <?php echo esc_html( $meta['label'] ); ?>
          </h2>
          <div class="gano-sota-hub__grid">
            <?php foreach ( $por_categoria[ $slug ] as $pg ) : ?>
              <article class="gano-sota-hub__card">
                <?php if ( has_post_thumbnail( $pg->ID ) ) : ?>
                  <div class="gano-sota-hub__thumb">
                    <?php echo get_the_post_thumbnail( $pg->ID, 'thumbnail', [ 'alt' => '', 'aria-hidden' => 'true' ] ); ?>
                  </div>
                <?php endif; ?>
                <div class="gano-sota-hub__card-body">
                  <h3><a href="<?php echo esc_url( get_permalink( $pg->ID ) ); ?>"><?php echo esc_html( $pg->post_title ); ?></a></h3>
                  <?php
                  $excerpt = wp_trim_words( wp_strip_all_tags( $pg->post_content ), 20, '…' );
                  if ( $excerpt ) echo '<p>' . esc_html( $excerpt ) . '</p>';
                  ?>
                  <a href="<?php echo esc_url( get_permalink( $pg->ID ) ); ?>" class="gano-link-secundario">Leer artículo →</a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>
    <?php endforeach; ?>

  <?php endif; ?>

  <!-- ── CTA FINAL ───────────────────────────────────────────────── -->
  <section class="gano-dark-section" style="text-align:center; padding:4rem 1.5rem;">
    <div class="gano-container">
      <h2>¿Tu infraestructura está a la altura?</h2>
      <p style="color:rgba(255,255,255,.75); margin-bottom:1.5rem;">Habla con el equipo y descubre qué arquitectura corresponde a tu etapa actual.</p>
      <a href="/contacto" class="gano-btn">Hablar con el equipo</a>
    </div>
  </section>

</main>

<style>
.gano-sota-hub__hero { padding: 0; }
.gano-sota-hub__categoria { padding: 3.5rem 1.5rem; }
.gano-sota-hub__categoria:nth-child(even) { background: #f8fafc; }
.gano-sota-hub__cat-title { font-size: var(--gano-fs-3xl, 1.875rem); margin-bottom: 1.5rem; display: flex; align-items: center; gap: .5rem; }
.gano-sota-hub__grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; }
.gano-sota-hub__card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: box-shadow .2s ease; }
.gano-sota-hub__card:hover { box-shadow: 0 8px 24px rgba(15,17,21,.08); }
.gano-sota-hub__thumb img { width: 100%; height: 140px; object-fit: cover; display: block; }
.gano-sota-hub__card-body { padding: 1.25rem; flex: 1; display: flex; flex-direction: column; gap: .5rem; }
.gano-sota-hub__card-body h3 { font-size: var(--gano-fs-base, 1rem); margin: 0; line-height: 1.4; }
.gano-sota-hub__card-body h3 a { color: inherit; text-decoration: none; }
.gano-sota-hub__card-body h3 a:hover { color: var(--gano-orange, #FF6B1A); }
.gano-sota-hub__card-body p { font-size: var(--gano-fs-sm, .875rem); color: #64748b; margin: 0; flex: 1; }
@media (max-width: 600px) { .gano-sota-hub__grid { grid-template-columns: 1fr; } }
</style>

<?php get_footer(); ?>
