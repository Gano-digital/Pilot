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

<main id="gano-main-content" class="gano-ecosistemas-page gano-km-shell gano-catalog-shell" data-gano-catalog>

  <!-- ── HERO DE SECCIÓN ─────────────────────────────────────────── -->
  <section class="gano-ecosistemas-hero gano-dark-section gano-km-shell">
    <div class="gano-container gano-km-container">
      <p class="gano-label-pill gano-km-live-badge">Planes de Hosting Gano Digital</p>
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
          'id'           => 'wordpress-basic',
          'nombre'       => 'WordPress Básico',
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
          'cta_primario' => 'Elegir WordPress Básico',
          'cta_secundario' => 'Ver especificaciones',
          'rcc_slug'     => 'wordpress-basico',
          'catalog_cat'  => 'wordpressadministrado',
          'pfid_const'   => 'GANO_PFID_HOSTING_ECONOMIA',
          'enlace_detalle' => '/producto/wordpress-basic/',
        ],
        [
          'id'           => 'wordpress-deluxe',
          'nombre'       => 'WordPress Deluxe',
          'precio'       => '$ 450.000',
          'periodo'      => '/mes (IVA incluido)',
          'tag'          => 'Más popular',
          'dark'         => false,
          'gold'         => false,
          'promesa'      => 'Para marcas que ya generan ingresos. Más recursos, hardening activo y visibilidad operativa sin tener que pedirla.',
          'beneficios'   => [
            '40 GB NVMe · doble de recursos de cómputo que WordPress Básico',
            'Hardening WordPress incluido (gano-security MU plugin)',
            'Backups diarios + on-demand · retención 30 días',
            'Soporte chat + ticket priorizado · respuesta < 4 h laborales',
            'Monitoreo de disponibilidad (uptime) con alertas',
            'WooCommerce soportado y configurado',
            'Facturación en COP',
          ],
          'objecion'     => '"¿Por qué pagar el doble si mi host actual funciona?"',
          'respuesta'    => 'El salto de precio compra hardening activo, más recursos y visibilidad operativa: no es pagar dos veces el mismo servicio.',
          'cta_primario' => 'Activar WordPress Deluxe',
          'cta_secundario' => 'Comparar planes',
          'rcc_slug'     => 'wordpress-deluxe',
          'catalog_cat'  => 'wordpressadministrado',
          'pfid_const'   => 'GANO_PFID_HOSTING_DELUXE',
          'enlace_detalle' => '/producto/wordpress-deluxe/',
        ],
        [
          'id'           => 'wordpress-ultimate',
          'nombre'       => 'WordPress Ultimate',
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
          'cta_primario' => 'Solicitar WordPress Ultimate',
          'cta_secundario' => 'Hablar con ventas',
          'rcc_slug'     => 'wordpress-ultimate',
          'catalog_cat'  => 'webhostingplus',
          'pfid_const'   => 'GANO_PFID_HOSTING_PREMIUM',
          'enlace_detalle' => '/producto/wordpress-ultimate/',
        ],
        [
          'id'           => 'cpanel-ultimate',
          'nombre'       => 'cPanel Ultimate',
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
          'cta_primario' => 'Cotizar cPanel Ultimate',
          'cta_secundario' => 'Hablar con ventas',
          'rcc_slug'     => 'cpanel-ultimate',
          'catalog_cat'  => 'hostingwebcpanel',
          'pfid_const'   => 'GANO_PFID_HOSTING_ULTIMATE',
          'enlace_detalle' => '/producto/cpanel-ultimate/',
        ],
      ];

      $catalog_modes = function_exists( 'gano_get_catalog_nav_modes' ) ? gano_get_catalog_nav_modes() : array();
      ?>

      <div class="gano-catalog-mode-switch" role="group" aria-label="Modo de navegación de ecosistemas">
        <?php foreach ( $catalog_modes as $mode_key => $mode_meta ) : ?>
          <button type="button" class="gano-catalog-mode-btn" data-gano-mode="<?php echo esc_attr( $mode_key ); ?>" aria-pressed="false">
            <?php echo esc_html( $mode_meta['label'] ); ?>
          </button>
        <?php endforeach; ?>
      </div>
      <p class="gano-catalog-mode-desc" data-gano-mode-description>
        Cambia la forma de explorar los planes según tu nivel de madurez.
      </p>
      <section class="gano-catalog-guided-panel" data-gano-guided-panel aria-label="Asistente de selección">
        <ul class="gano-catalog-guided-list" data-gano-guided-list></ul>
      </section>

      <div id="catalog-container" class="gano-catalog-grid gano-cards-grid">
      <?php
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
                        : '#ecosistemas-proximamente';
        $is_pending = ( $pfid === 'PENDING_RCC' ) && ! $rcc_product_id;

        $card_classes = 'gano-plan-card';
        if ( $plan['dark'] ) $card_classes .= ' gano-plan-card--dark';
        if ( $plan['gold'] ) $card_classes .= ' gano-plan-card--gold';
        if ( $plan['tag'] ) $card_classes .= ' gano-plan-card--featured';
        ?>

        <article class="<?php echo esc_attr( $card_classes ); ?> gano-km-card"
                 id="<?php echo esc_attr( $plan['id'] ); ?>"
                 data-category="<?php echo esc_attr( $plan['catalog_cat'] ?? 'wordpressadministrado' ); ?>"
                 data-product-id="<?php echo esc_attr( sanitize_title( $plan['id'] ) ); ?>"
                 data-product-name="<?php echo esc_attr( $plan['nombre'] ); ?>"
                 data-product-price="<?php echo esc_attr( $plan['precio'] ); ?>">

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
              <a href="/contacto" class="gano-btn gano-km-btn-primary" aria-label="<?php echo esc_attr( $plan['cta_primario'] ); ?> — contactar para precio">
                <?php echo esc_html( $plan['cta_primario'] ); ?>
              </a>
              <small class="gano-plan-pending-note">Carrito en configuración · Contacta para activar</small>
            <?php elseif ( $rcc_product_id ) : ?>
              <!-- NUEVO: Si se encontró el producto RCC, usar shortcode rstore_product -->
              <?php echo do_shortcode( "[rstore_product post_id=" . intval( $rcc_product_id ) . " show_price=1 redirect=1 button_label='" . esc_attr( $plan['cta_primario'] ) . "']" ); ?>
            <?php else : ?>
              <a href="<?php echo esc_url( $cart_url ); ?>" class="gano-btn gano-km-btn-primary" target="_blank" rel="noopener">
                <?php echo esc_html( $plan['cta_primario'] ); ?>
              </a>
            <?php endif; ?>

            <a href="<?php echo esc_url( $plan['enlace_detalle'] ); ?>" class="gano-link-secundario gano-km-btn-secondary">
              <?php echo esc_html( $plan['cta_secundario'] ); ?> →
            </a>
            <button type="button" class="gano-catalog-compare-toggle" data-gano-compare-toggle aria-pressed="false">
              Comparar
            </button>
          </div>

        </article>

      <?php endforeach; ?>
      </div>

      <section class="gano-catalog-comparator" data-gano-compare hidden>
        <h3 class="gano-catalog-comparator-title">Comparador inteligente (hasta 3)</h3>
        <ul class="gano-catalog-compare-list" data-gano-compare-list></ul>
        <div class="gano-catalog-compare-grid" data-gano-compare-grid></div>
      </section>
    </div><!-- .gano-container -->
  </section>

  <!-- ── NOTA DE PRECIOS ─────────────────────────────────────────── -->
  <section class="gano-ecosistemas-nota">
    <div class="gano-container">
      <p class="gano-km-glass"><small>Precios indicativos en COP (pesos colombianos), IVA incluido. Sujetos a variación. Consulta el carrito para el valor final. Facturación a través del programa Reseller de GoDaddy.</small></p>
    </div>
  </section>

  <!-- ── TRANSPARENCIA RESELLER ────────────────────────────────── -->
  <section class="gano-ecosistemas-nota">
    <div class="gano-container">
      <p class="gano-km-glass"><strong>Operamos sobre infraestructura GoDaddy Reseller.</strong> Gano Digital aprovisiona capacidad de servidor a trav&eacute;s del programa de reseller autorizado de GoDaddy. La infraestructura subyacente cumple est&aacute;ndares globales de datacenter; nosotros sumamos configuraci&oacute;n experta, hardening y soporte en espa&ntilde;ol.</p>
    </div>
  </section>

  <!-- ── SEÑALES DE CONFIANZA ───────────────────────────────────── -->
  <section class="gano-ecosistemas-trust-signals">
    <div class="gano-container">
      <div class="gano-ecosistemas-trust-signals-grid">

        <!-- Signal 1: GoDaddy Reseller -->
        <div class="gano-trust-signal">
          <div class="gano-trust-signal-icon"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
          <div class="gano-trust-signal-content">
            <h4>Infraestructura GoDaddy</h4>
            <p>Programa reseller autorizado · Estándares globales de datacenter</p>
          </div>
        </div>

        <!-- Signal 2: Uptime Guarantee -->
        <div class="gano-trust-signal">
          <div class="gano-trust-signal-icon"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
          <div class="gano-trust-signal-content">
            <h4>99.9% Disponibilidad</h4>
            <p>SLA comprometido · Monitoreo proactivo incluido</p>
          </div>
        </div>

        <!-- Signal 3: COP Billing -->
        <div class="gano-trust-signal">
          <div class="gano-trust-signal-icon"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
          <div class="gano-trust-signal-content">
            <h4>Facturación en COP</h4>
            <p>Pesos colombianos · Sin sorpresas de tipo de cambio</p>
          </div>
        </div>

        <!-- Signal 4: Support Response -->
        <div class="gano-trust-signal">
          <div class="gano-trust-signal-icon"><svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg></div>
          <div class="gano-trust-signal-content">
            <h4>Soporte Priorizado</h4>
            <p>Primera respuesta en 8 horas · En tu idioma</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ── COMPARACIÓN RÁPIDA ──────────────────────────────────────── -->
  <section class="gano-ecosistemas-tabla">
    <div class="gano-container">
      <h2>Comparación rápida</h2>
      <div class="gano-table-scroll">
        <table class="gano-ecosistemas-comparison">
          <thead>
            <tr>
              <th scope="col">Característica</th>
              <th scope="col">WordPress Básico</th>
              <th scope="col">WordPress Deluxe</th>
              <th scope="col">WordPress Ultimate</th>
              <th scope="col">cPanel Ultimate</th>
            </tr>
          </thead>
          <tbody>
            <tr><th scope="row">Almacenamiento NVMe</th><td>20 GB</td><td>40 GB</td><td>75 GB</td><td>100 GB</td></tr>
            <tr><th scope="row">Recursos de cómputo</th><td>Base</td><td>Doble</td><td>Dedicados</td><td>Máximos</td></tr>
            <tr><th scope="row">Hardening WordPress</th><td>Básico</td><td>Activo</td><td>Avanzado</td><td>Avanzado</td></tr>
            <tr><th scope="row">Soporte en español</th><td>Ticket &lt; 8h</td><td>Priorizado &lt; 4h</td><td>Dedicado 24/7 &lt; 2h</td><td>Prioritario &lt; 1h</td></tr>
            <tr><th scope="row">Agente IA de administración</th><td>—</td><td>—</td><td>✓ Incluido</td><td>✓ Incluido</td></tr>
            <tr><th scope="row">SLA de disponibilidad</th><td>Estándar</td><td>Estándar</td><td>≥ 99,9%</td><td>≥ 99,9%</td></tr>
            <tr><th scope="row">Backups automáticos</th><td>Diarios · 30 días</td><td>Diarios + on-demand · 30 días</td><td>Diarios + on-demand · 60 días</td><td>Continuos + DR · 90 días</td></tr>
            <tr><th scope="row">Monitoreo y alertas</th><td>—</td><td>✓ Uptime</td><td>✓ Proactivo</td><td>✓ Escalado automático</td></tr>
            <tr><th scope="row">CDN incluido</th><td>✓ Global</td><td>✓ Global</td><td>✓ Global</td><td>✓ Global</td></tr>
            <tr><th scope="row">Facturación</th><td>COP</td><td>COP</td><td>COP</td><td>COP</td></tr>
          </tbody>
        </table>
      </div>
      <p><small>Especificaciones de almacenamiento sujetas al catálogo vigente del Reseller Control Center de GoDaddy. <a href="/contacto">Consulta para cifras exactas.</a></small></p>
    </div>
  </section>

  <!-- ── RESPUESTAS A OBJECIONES ────────────────────────────────── -->
  <section class="gano-ecosistemas-objeciones">
    <div class="gano-container">
      <h2>Lo que dirías vs. lo que respondemos</h2>
      <div class="gano-objeciones-grid">

        <!-- Objeción 1: Precio -->
        <article class="gano-objecion-card gano-objecion-card-precio">
          <h3><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin-right:6px"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg> "Es caro para lo que necesito"</h3>
          <div class="gano-objecion-card-content">
            <p><strong>WordPress Básico:</strong> NVMe real (no HDD) + soporte en español = más que un hosting compartido genérico al mismo precio.</p>
            <p><strong>WordPress Deluxe:</strong> Hardening activo + más recursos + visibilidad = no pagas dos veces el mismo servicio.</p>
            <p><strong>WordPress Ultimate:</strong> Una hora de caída supera la diferencia de precio mensual. Es inversión en continuidad.</p>
          </div>
        </article>

        <!-- Objeción 2: Confianza -->
        <article class="gano-objecion-card gano-objecion-card-confianza">
          <h3><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin-right:6px"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg> "¿Quién es Gano Digital?"</h3>
          <div class="gano-objecion-card-content">
            <p><strong>La infraestructura:</strong> GoDaddy Managed WordPress — años de trayectoria, datacenter con estándares globales.</p>
            <p><strong>Lo que sumamos:</strong> Configuración experta, hardening que el reseller no hace por omisión, soporte en tu idioma y contexto local.</p>
            <p><strong>Transparencia:</strong> SLA publicado, política de incidentes clara, sin letra pequeña.</p>
          </div>
        </article>

        <!-- Objeción 3: Soporte -->
        <article class="gano-objecion-card gano-objecion-card-soporte">
          <h3><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:middle;margin-right:6px"><path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3zM3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/></svg> "¿Recibiré soporte real?"</h3>
          <div class="gano-objecion-card-content">
            <p><strong>WordPress Básico:</strong> Ticket en español con contexto de tu instalación — no un bot genérico.</p>
            <p><strong>WordPress Deluxe:</strong> Canal priorizado para clientes Deluxe (respuesta dentro de horario hábil).</p>
            <p><strong>WordPress Ultimate:</strong> Contacto dedicado, monitoreo proactivo, política de escalamiento documentada.</p>
          </div>
        </article>

      </div>
    </div>
  </section>

  <!-- ── IFRAME RESELLER STORE (FASE 4) ──────────────────────── -->
  <section class="gano-reseller-checkout-section">
    <div class="gano-container">
      <h2>Elige tu plan y activa hoy</h2>
      <p>Selecciona el plan que se adapte a tu proyecto. El checkout está integrado y listo.</p>

      <!-- Tabs para seleccionar ecosistema -->
      <div class="gano-reseller-tabs" role="tablist" aria-label="Selecciona ecosistema">
        <button class="gano-reseller-tab-btn active"
                data-tab="hosting_economia"
                role="tab"
                aria-selected="true"
                aria-label="Seleccionar plan WordPress Básico">
          WordPress Básico
        </button>
        <button class="gano-reseller-tab-btn"
                data-tab="hosting_deluxe"
                role="tab"
                aria-selected="false"
                aria-label="Seleccionar plan WordPress Deluxe">
          WordPress Deluxe
        </button>
        <button class="gano-reseller-tab-btn"
                data-tab="hosting_premium"
                role="tab"
                aria-selected="false"
                aria-label="Seleccionar plan WordPress Ultimate">
          WordPress Ultimate
        </button>
        <button class="gano-reseller-tab-btn"
                data-tab="hosting_ultimate"
                role="tab"
                aria-selected="false"
                aria-label="Seleccionar plan cPanel Ultimate">
          cPanel Ultimate
        </button>
      </div>

      <!-- Iframes para cada ecosistema -->
      <div class="gano-reseller-tabs-content">
        <div class="gano-reseller-tab-pane active" data-tab="hosting_economia" role="tabpanel">
          <?php echo do_shortcode( '[gano_reseller_iframe ecosistema="hosting_economia" heading="WordPress Básico — Elige tu plan"]' ); ?>
        </div>
        <div class="gano-reseller-tab-pane" data-tab="hosting_deluxe" role="tabpanel">
          <?php echo do_shortcode( '[gano_reseller_iframe ecosistema="hosting_deluxe" heading="WordPress Deluxe — Elige tu plan"]' ); ?>
        </div>
        <div class="gano-reseller-tab-pane" data-tab="hosting_premium" role="tabpanel">
          <?php echo do_shortcode( '[gano_reseller_iframe ecosistema="hosting_premium" heading="WordPress Ultimate — Elige tu plan"]' ); ?>
        </div>
        <div class="gano-reseller-tab-pane" data-tab="hosting_ultimate" role="tabpanel">
          <?php echo do_shortcode( '[gano_reseller_iframe ecosistema="hosting_ultimate" heading="cPanel Ultimate — Elige tu plan"]' ); ?>
        </div>
      </div>
    </div>
  </section>

  <!-- ── CTA FINAL ───────────────────────────────────────────────── -->
  <div class="gano-container">
    <?php
    gano_cta_registro(array(
      'heading'     => '¿No sabes por dónde empezar?',
      'description' => '¿no sabes por dónde empezar? registra tu cuenta y recibe soporte inmediato. Nosotros te agendamos. Acompañamos tu empresa en cada decisión: siempre donde verdaderamente importa.',
      'button_text' => 'Registra tu cuenta',
    ));
    ?>
  </div>

</main>

<?php get_footer(); ?>
