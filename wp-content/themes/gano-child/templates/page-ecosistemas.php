<?php
/**
 * Template Name: Ecosistemas — Planes Gano
 *
 * Página de catálogo de planes. Muestra las 4 arquitecturas con copy definitivo
 * y botones de checkout Reseller. Los PFIDs se leen desde las constantes definidas
 * en functions.php (GANO_PFID_*). Mientras sean 'PENDING_RCC', el botón muestra
 * un aviso en lugar de redirigir al carrito.
 *
 * @package gano-child
 * @since   1.1.0
 */

get_header();
?>

<main id="gano-main-content" class="gano-ecosistemas-page gano-km-shell">

  <!-- ── HERO DE SECCIÓN ─────────────────────────────────────────── -->
  <section class="gano-ecosistemas-hero gano-dark-section gano-km-shell">
    <div class="gano-container gano-km-container">
      <p class="gano-label-pill gano-km-live-badge">Arquitecturas Gano Digital</p>
      <h1 class="gano-km-title">Elige la infraestructura que merece tu proyecto.</h1>
      <p class="gano-ecosistemas-hero__sub gano-km-lead">
        Cuatro planes construidos sobre el programa Reseller de GoDaddy, operados en español
        y facturados en pesos colombianos. Elige el que corresponde a tu etapa; cambia cuando crezcas.
      </p>
    </div>
  </section>

  <!-- ── GRID DE PLANES ──────────────────────────────────────────── -->
  <section class="gano-planes-grid-section">
    <div class="gano-container">

      <?php
      // SLAs comerciales — revisar contra hoja de producto GoDaddy Managed WordPress
      // antes de producción. Valores alineados con práctica estándar del programa
      // Reseller (Economy / Deluxe / Premium / Ultimate).
      // **NUEVO:** Slugs RCC para integración dinámica con WP_Query
      $planes = [
        [
          'id'           => 'nucleo-prime',
          'nombre'       => 'Núcleo Prime',
          'precio'       => '$ 196.000',
          'periodo'      => '/mes (IVA incluido)',
          'tag'          => '',
          'dark'         => false,
          'gold'         => false,
          'promesa'      => 'El punto de partida correcto. NVMe real, soporte en español y activación sin fricciones para sitios que acaban de despegar.',
          'beneficios'   => [
            '20 GB de almacenamiento NVMe',
            'WordPress preinstalado + Hello Elementor + Gano Security',
            'Soporte por ticket en español · respuesta < 8 h laborales',
            'SSL Let\'s Encrypt incluido',
            'Backups automáticos diarios · retención 30 días',
            'CDN global incluido',
            'Facturación en COP — sin sorpresas de tipo de cambio',
          ],
          'objecion'     => '"Es caro para lo que necesito ahora."',
          'respuesta'    => 'Incluye NVMe real (no HDD compartido), soporte en tu idioma y activación rápida. Más que un hosting genérico al mismo precio.',
          'cta_primario' => 'Elegir Núcleo Prime',
          'cta_secundario' => 'Ver especificaciones',
          'rcc_slug'     => 'wordpress-basico',
          'pfid_const'   => 'GANO_PFID_HOSTING_ECONOMIA',
          'enlace_detalle' => '/ecosistemas/nucleo-prime',
        ],
        [
          'id'           => 'fortaleza-delta',
          'nombre'       => 'Fortaleza Delta',
          'precio'       => '$ 450.000',
          'periodo'      => '/mes (IVA incluido)',
          'tag'          => 'Más popular',
          'dark'         => false,
          'gold'         => false,
          'promesa'      => 'Para marcas que ya generan ingresos. Más recursos, hardening activo y visibilidad operativa sin tener que pedirla.',
          'beneficios'   => [
            '40 GB NVMe · doble de recursos de cómputo que Núcleo Prime',
            'Hardening WordPress incluido (gano-security MU plugin)',
            'Backups diarios + on-demand · retención 30 días',
            'Soporte chat + ticket priorizado · respuesta < 4 h laborales',
            'Monitoreo de disponibilidad (uptime) con alertas',
            'WooCommerce soportado y configurado',
            'Facturación en COP',
          ],
          'objecion'     => '"¿Por qué pagar el doble si mi host actual funciona?"',
          'respuesta'    => 'El salto de precio compra hardening activo, más recursos y visibilidad operativa: no es pagar dos veces el mismo servicio.',
          'cta_primario' => 'Activar Fortaleza Delta',
          'cta_secundario' => 'Comparar planes',
          'rcc_slug'     => 'wordpress-deluxe',
          'pfid_const'   => 'GANO_PFID_HOSTING_DELUXE',
          'enlace_detalle' => '/ecosistemas/fortaleza-delta',
        ],
        [
          'id'           => 'bastion-sota',
          'nombre'       => 'Bastión SOTA',
          'precio'       => '$ 890.000',
          'periodo'      => '/mes (IVA incluido)',
          'tag'          => 'Recomendado para comercio',
          'dark'         => true,
          'gold'         => true,
          'promesa'      => 'Rendimiento crítico Gen4 con seguridad de nivel empresarial. Para operaciones que no toleran degradación ni incidentes visibles.',
          'beneficios'   => [
            '75 GB NVMe · recursos dedicados con aislamiento de procesos',
            'SLA objetivo ≥ 99,9% de disponibilidad',
            'Monitoreo proactivo con alertas antes del incidente',
            'Hardening avanzado + auditoría de seguridad incluida',
            'Agente IA de administración (gano-agent-core)',
            'Soporte chat + ticket prioritario 24/7 · respuesta < 2 h',
            'Staging incluido · backups diarios + on-demand · retención 60 días',
          ],
          'objecion'     => '"¿Qué me da que un VPS gestionado no dé?"',
          'respuesta'    => 'Capa de servicio en español, hardening WordPress específico, agente IA integrado y SLA documentado. El VPS genérico no incluye nada de eso.',
          'cta_primario' => 'Solicitar Bastión SOTA',
          'cta_secundario' => 'Hablar con ventas',
          'rcc_slug'     => 'wordpress-ultimate',
          'pfid_const'   => 'GANO_PFID_HOSTING_PREMIUM',
          'enlace_detalle' => '/ecosistemas/bastion-sota',
        ],
        [
          'id'           => 'ultimate-wp',
          'nombre'       => 'Ultimate WP',
          'precio'       => '$ 1.200.000',
          'periodo'      => '/mes (IVA incluido)',
          'tag'          => 'Agencias y alto tráfico',
          'dark'         => false,
          'gold'         => false,
          'promesa'      => 'Máxima capacidad y blindaje ante picos masivos. Para agencias con portafolios de alto tráfico o plataformas que no pueden fallar.',
          'beneficios'   => [
            '100 GB NVMe · máxima asignación de recursos del catálogo',
            'Multi-site y portafolio de clientes soportado',
            'Canal de soporte dedicado · respuesta < 1 h 24/7',
            'Backups continuos + on-demand · retención 90 días · DR incluido',
            'Escalado automático ante picos de tráfico',
            'Onboarding técnico incluido',
            'Facturación en COP',
          ],
          'objecion'     => '"¿Por qué no un cloud propio?"',
          'respuesta'    => 'Sin overhead de DevOps, sin gestión de infraestructura. Tú operas, nosotros sostenemos el piso técnico con SLA documentado.',
          'cta_primario' => 'Cotizar Ultimate WP',
          'cta_secundario' => 'Hablar con ventas',
          'rcc_slug'     => 'cpanel-ultimate',
          'pfid_const'   => 'GANO_PFID_HOSTING_ULTIMATE',
          'enlace_detalle' => '/ecosistemas/ultimate-wp',
        ],
      ];

      foreach ( $planes as $plan ) :
        // NUEVO: Buscar producto RCC dinámicamente via WP_Query
        $product_query = new WP_Query([
          'post_type'      => 'reseller_product',
          'name'           => $plan['rcc_slug'],
          'posts_per_page' => 1,
        ]);
        $rcc_product_id = $product_query->have_posts() ? $product_query->posts[0]->ID : null;
        wp_reset_postdata();

        // Fallback a PFID constante si no encuentra el producto RCC
        $pfid       = defined( $plan['pfid_const'] ) ? constant( $plan['pfid_const'] ) : 'PENDING_RCC';
        $cart_url   = ( $pfid !== 'PENDING_RCC' && function_exists( 'gano_rstore_cart_url' ) )
                        ? gano_rstore_cart_url( $pfid )
                        : '/contacto';
        $is_pending = ( $pfid === 'PENDING_RCC' ) && ! $rcc_product_id;

        $card_classes = 'gano-plan-card';
        if ( $plan['dark'] ) $card_classes .= ' gano-plan-card--dark';
        if ( $plan['gold'] ) $card_classes .= ' gano-plan-card--gold';
        if ( $plan['tag'] ) $card_classes .= ' gano-plan-card--featured';
        ?>

        <article class="<?php echo esc_attr( $card_classes ); ?> gano-km-card" id="<?php echo esc_attr( $plan['id'] ); ?>">

          <?php if ( $plan['tag'] ) : ?>
            <span class="gano-plan-badge"><?php echo esc_html( $plan['tag'] ); ?></span>
          <?php endif; ?>

          <h2 class="gano-plan-nombre"><?php echo esc_html( $plan['nombre'] ); ?></h2>
          <p class="gano-plan-promesa"><?php echo esc_html( $plan['promesa'] ); ?></p>

          <div class="gano-plan-precio">
            <strong><?php echo esc_html( $plan['precio'] ); ?></strong>
            <span><?php echo esc_html( $plan['periodo'] ); ?></span>
          </div>

          <ul class="gano-plan-beneficios" aria-label="Beneficios incluidos">
            <?php foreach ( $plan['beneficios'] as $b ) : ?>
              <li><?php echo esc_html( $b ); ?></li>
            <?php endforeach; ?>
          </ul>

          <blockquote class="gano-plan-objecion">
            <p><?php echo esc_html( $plan['objecion'] ); ?></p>
            <footer><?php echo esc_html( $plan['respuesta'] ); ?></footer>
          </blockquote>

          <div class="gano-plan-ctas">
            <?php if ( $is_pending ) : ?>
              <a href="/contacto" class="gano-btn gano-km-btn-primary" aria-label="<?php echo esc_attr( $plan['cta_primario'] ); ?>">
                <?php echo esc_html( $plan['cta_primario'] ); ?>
              </a>
            <?php elseif ( $rcc_product_id ) : ?>
              <!-- NUEVO: Si se encontró el producto RCC, usar shortcode rstore_product -->
              <?php echo do_shortcode( "[rstore_product post_id={$rcc_product_id} show_price=1 redirect=1 button_label='" . esc_attr( $plan['cta_primario'] ) . "']" ); ?>
            <?php else : ?>
              <a href="<?php echo esc_url( $cart_url ); ?>" class="gano-btn gano-km-btn-primary" target="_blank" rel="noopener">
                <?php echo esc_html( $plan['cta_primario'] ); ?>
              </a>
            <?php endif; ?>

            <a href="<?php echo esc_url( $plan['enlace_detalle'] ); ?>" class="gano-link-secundario gano-km-btn-secondary">
              <?php echo esc_html( $plan['cta_secundario'] ); ?> →
            </a>
          </div>

        </article>

      <?php endforeach; ?>

    </div><!-- .gano-container -->
  </section>

  <!-- ── NOTA DE PRECIOS ─────────────────────────────────────────── -->
  <section class="gano-ecosistemas-nota">
    <div class="gano-container">
      <p class="gano-km-glass"><small>Precios indicativos en COP (pesos colombianos), IVA incluido. Sujetos a variación. Consulta el carrito para el valor final. Facturación a través del programa Reseller de GoDaddy.</small></p>
    </div>
  </section>

  <!-- ── COMPARACIÓN RÁPIDA ──────────────────────────────────────── -->
  <section class="gano-ecosistemas-tabla">
    <div class="gano-container">
      <h2>Comparación rápida</h2>
      <div class="gano-table-scroll">
        <table>
          <thead>
            <tr>
              <th scope="col">Característica</th>
              <th scope="col">Núcleo Prime</th>
              <th scope="col">Fortaleza Delta</th>
              <th scope="col">Bastión SOTA</th>
              <th scope="col">Ultimate WP</th>
            </tr>
          </thead>
          <tbody>
            <tr><th scope="row">Almacenamiento</th><td>NVMe (base)</td><td>NVMe (medio)</td><td>NVMe (alto)</td><td>NVMe (máximo)</td></tr>
            <tr><th scope="row">Hardening WP</th><td>Básico</td><td>Activo</td><td>Avanzado</td><td>Avanzado</td></tr>
            <tr><th scope="row">Soporte español</th><td>Ticket</td><td>Priorizado</td><td>Dedicado</td><td>Prioritario</td></tr>
            <tr><th scope="row">Agente IA</th><td>—</td><td>—</td><td>✓</td><td>✓</td></tr>
            <tr><th scope="row">SLA uptime</th><td>Estándar</td><td>Estándar</td><td>≥ 99,9%</td><td>≥ 99,9%</td></tr>
            <tr><th scope="row">Facturación</th><td>COP</td><td>COP</td><td>COP</td><td>COP</td></tr>
          </tbody>
        </table>
      </div>
      <p><small>Especificaciones de almacenamiento sujetas al catálogo vigente del Reseller Control Center de GoDaddy. <a href="/contacto">Consulta para cifras exactas.</a></small></p>
    </div>
  </section>

  <!-- ── CTA FINAL ───────────────────────────────────────────────── -->
  <section class="gano-dark-section gano-ecosistemas__cta-final gano-km-shell" id="gano-cta-final-sentinel">
    <div class="gano-container gano-km-container">
      <h2>¿No sabes cuál elegir?</h2>
      <p>Cuéntanos dónde estás y te decimos qué arquitectura corresponde a tu etapa. Sin presión.</p>
      <a href="/contacto" class="gano-btn gano-km-btn-primary">Hablar con el equipo</a>
    </div>
  </section>

  <!-- ── CTA STICKY MOBILE ────────────────────────────────────────── -->
  <div class="gano-sticky-cta-mobile"
       role="complementary"
       aria-label="Acciones rápidas">
    <a href="#nucleo-prime"
       class="gano-sticky-cta__primary"
       aria-label="Elegir plan — ir a los planes">
      Elegir plan →
    </a>
    <a href="/contacto"
       class="gano-sticky-cta__secondary"
       aria-label="Hablar con el equipo de ventas">
      Contactar
    </a>
  </div>

</main>

<script>
(function () {
  // Ocultar la barra sticky cuando el CTA final entra en viewport
  var sentinel = document.getElementById('gano-cta-final-sentinel');
  var bar = document.querySelector('.gano-sticky-cta-mobile');
  if (!sentinel || !bar) return;

  document.body.classList.add('has-mobile-cta');

  var obs = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        bar.classList.add('is-hidden');
      } else {
        bar.classList.remove('is-hidden');
      }
    });
  }, { threshold: 0.1 });

  obs.observe(sentinel);
})();
</script>

<?php get_footer(); ?>
