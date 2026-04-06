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

<main id="gano-main-content" class="gano-ecosistemas-page">

  <!-- ── HERO DE SECCIÓN ─────────────────────────────────────────── -->
  <section class="gano-ecosistemas-hero gano-dark-section">
    <div class="gano-container">
      <p class="gano-label-pill">Arquitecturas Gano Digital</p>
      <h1>Elige la infraestructura que merece tu proyecto.</h1>
      <p class="gano-ecosistemas-hero__sub">
        Cuatro planes construidos sobre el programa Reseller de GoDaddy, operados en español
        y facturados en pesos colombianos. Elige el que corresponde a tu etapa; cambia cuando crezcas.
      </p>
    </div>
  </section>

  <!-- ── GRID DE PLANES ──────────────────────────────────────────── -->
  <section class="gano-planes-grid-section">
    <div class="gano-container">

      <?php
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
            'Almacenamiento NVMe (capacidad según plan RCC)',
            'WordPress preinstalado + Hello Elementor + Gano Security',
            'Soporte por ticket en español (SLA por confirmar)',
            'SSL Let\'s Encrypt incluido',
            'Backups automáticos según política del proveedor',
            'CDN incluido en el plan base',
            'Facturación en COP — sin sorpresas de tipo de cambio',
          ],
          'objecion'     => '"Es caro para lo que necesito ahora."',
          'respuesta'    => 'Incluye NVMe real (no HDD compartido), soporte en tu idioma y activación rápida. Más que un hosting genérico al mismo precio.',
          'cta_primario' => 'Elegir Núcleo Prime',
          'cta_secundario' => 'Ver especificaciones',
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
            'Mayor capacidad de cómputo y almacenamiento que Núcleo Prime',
            'Hardening WordPress incluido (gano-security MU plugin)',
            'Respaldos automáticos con mayor frecuencia (confirmar con RCC)',
            'Canal de soporte priorizado [tiempo de respuesta a definir]',
            'Monitoreo de disponibilidad (uptime)',
            'WooCommerce soportado y configurado',
            'Facturación en COP',
          ],
          'objecion'     => '"¿Por qué pagar el doble si mi host actual funciona?"',
          'respuesta'    => 'El salto de precio compra hardening activo, más recursos y visibilidad operativa: no es pagar dos veces el mismo servicio.',
          'cta_primario' => 'Activar Fortaleza Delta',
          'cta_secundario' => 'Comparar planes',
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
            'Recursos dedicados o aislados (confirmar especificaciones RCC)',
            'SLA objetivo ≥ 99,9% de disponibilidad',
            'Monitoreo proactivo con alertas antes del incidente',
            'Hardening avanzado + auditoría de seguridad incluida',
            'Agente IA de administración (gano-agent-core)',
            'Canal de soporte dedicado / WhatsApp Business [confirmar operación]',
            'Política de incidentes publicada y firmada',
          ],
          'objecion'     => '"¿Qué me da que un VPS gestionado no dé?"',
          'respuesta'    => 'Capa de servicio en español, hardening WordPress específico, agente IA integrado y SLA documentado. El VPS genérico no incluye nada de eso.',
          'cta_primario' => 'Solicitar Bastión SOTA',
          'cta_secundario' => 'Hablar con ventas',
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
            'Máxima asignación de recursos del catálogo RCC',
            'Multi-site y portafolio de clientes soportado',
            'Soporte prioritario con tiempo de respuesta documentado',
            'Backups continuos (frecuencia real a confirmar con RCC)',
            'Escalado ante picos de tráfico',
            'Onboarding técnico incluido',
            'Facturación en COP',
          ],
          'objecion'     => '"¿Por qué no un cloud propio?"',
          'respuesta'    => 'Sin overhead de DevOps, sin gestión de infraestructura. Tú operas, nosotros sostenemos el piso técnico con SLA documentado.',
          'cta_primario' => 'Cotizar Ultimate WP',
          'cta_secundario' => 'Hablar con ventas',
          'pfid_const'   => 'GANO_PFID_HOSTING_ULTIMATE',
          'enlace_detalle' => '/ecosistemas/ultimate-wp',
        ],
      ];

      foreach ( $planes as $plan ) :
        $pfid       = defined( $plan['pfid_const'] ) ? constant( $plan['pfid_const'] ) : 'PENDING_RCC';
        $cart_url   = ( $pfid !== 'PENDING_RCC' && function_exists( 'gano_rstore_cart_url' ) )
                        ? gano_rstore_cart_url( $pfid )
                        : '#ecosistemas-proximamente';
        $is_pending = ( $pfid === 'PENDING_RCC' );

        $card_classes = 'gano-plan-card';
        if ( $plan['dark'] ) $card_classes .= ' gano-plan-card--dark';
        if ( $plan['gold'] ) $card_classes .= ' gano-plan-card--gold';
        if ( $plan['tag'] ) $card_classes .= ' gano-plan-card--featured';
        ?>

        <article class="<?php echo esc_attr( $card_classes ); ?>" id="<?php echo esc_attr( $plan['id'] ); ?>">

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
              <a href="/contacto" class="gano-btn" aria-label="<?php echo esc_attr( $plan['cta_primario'] ); ?> — contactar para precio">
                <?php echo esc_html( $plan['cta_primario'] ); ?>
              </a>
              <small class="gano-plan-pending-note">Carrito en configuración · Contacta para activar</small>
            <?php else : ?>
              <a href="<?php echo esc_url( $cart_url ); ?>" class="gano-btn" target="_blank" rel="noopener">
                <?php echo esc_html( $plan['cta_primario'] ); ?>
              </a>
            <?php endif; ?>

            <a href="<?php echo esc_url( $plan['enlace_detalle'] ); ?>" class="gano-link-secundario">
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
      <p><small>Precios indicativos en COP (pesos colombianos), IVA incluido. Sujetos a variación. Consulta el carrito para el valor final. Facturación a través del programa Reseller de GoDaddy.</small></p>
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
  <section class="gano-dark-section" style="text-align:center; padding: 5rem 1.5rem;">
    <div class="gano-container">
      <h2>¿No sabes cuál elegir?</h2>
      <p>Cuéntanos dónde estás y te decimos qué arquitectura corresponde a tu etapa. Sin presión.</p>
      <a href="/contacto" class="gano-btn">Hablar con el equipo</a>
    </div>
  </section>

</main>

<?php get_footer(); ?>
