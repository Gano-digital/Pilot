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
          'catalog_cat'  => 'wordpressadministrado',
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
          'catalog_cat'  => 'wordpressadministrado',
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
          'catalog_cat'  => 'webhostingplus',
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
          'catalog_cat'  => 'hostingwebcpanel',
          'pfid_const'   => 'GANO_PFID_HOSTING_ULTIMATE',
          'enlace_detalle' => '/ecosistemas/ultimate-wp',
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
        Cambia la forma de explorar los ecosistemas según tu nivel de madurez.
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
              <?php echo do_shortcode( "[rstore_product post_id={$rcc_product_id} show_price=1 redirect=1 button_label='" . esc_attr( $plan['cta_primario'] ) . "']" ); ?>
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

  <!-- ── RESPUESTAS A OBJECIONES ────────────────────────────────── -->
  <section class="gano-ecosistemas-objeciones" style="padding: 60px 5%; background: rgba(15,17,21,0.5); border-top: 1px solid rgba(255,255,255,0.08);">
    <div class="gano-container">
      <h2 style="text-align: center; margin-bottom: 40px;">Lo que dirías vs. lo que respondemos</h2>
      <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">

        <!-- Objeción 1: Precio -->
        <article style="background: rgba(255,255,255,0.03); padding: 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
          <h3 style="color: #FF6B35; margin-bottom: 15px;">💰 "Es caro para lo que necesito"</h3>
          <div style="font-size: 0.95rem; line-height: 1.6;">
            <p><strong>Núcleo Prime:</strong> NVMe real (no HDD) + soporte en español = más que un hosting compartido genérico al mismo precio.</p>
            <p><strong>Fortaleza Delta:</strong> Hardening activo + más recursos + visibilidad = no pagas dos veces el mismo servicio.</p>
            <p><strong>Bastión SOTA:</strong> Una hora de caída supera la diferencia de precio mensual. Es inversión en continuidad.</p>
          </div>
        </article>

        <!-- Objeción 2: Confianza -->
        <article style="background: rgba(255,255,255,0.03); padding: 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
          <h3 style="color: #1B4FD8; margin-bottom: 15px;">🤝 "¿Quién es Gano Digital?"</h3>
          <div style="font-size: 0.95rem; line-height: 1.6;">
            <p><strong>La infraestructura:</strong> GoDaddy Managed WordPress — años de trayectoria, datacenter con estándares globales.</p>
            <p><strong>Lo que sumamos:</strong> Configuración experta, hardening que el reseller no hace por omisión, soporte en tu idioma y contexto local.</p>
            <p><strong>Transparencia:</strong> SLA publicado, política de incidentes clara, sin letra pequeña.</p>
          </div>
        </article>

        <!-- Objeción 3: Soporte -->
        <article style="background: rgba(255,255,255,0.03); padding: 25px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.08);">
          <h3 style="color: #00C26B; margin-bottom: 15px;">📞 "¿Recibiré soporte real?"</h3>
          <div style="font-size: 0.95rem; line-height: 1.6;">
            <p><strong>Núcleo Prime:</strong> Ticket en español con contexto de tu instalación — no un bot genérico.</p>
            <p><strong>Fortaleza Delta:</strong> Canal priorizado para clientes Fortaleza (respuesta dentro de horario hábil).</p>
            <p><strong>Bastión SOTA:</strong> Contacto dedicado, monitoreo proactivo, política de escalamiento documentada.</p>
          </div>
        </article>

      </div>
    </div>
  </section>

  <!-- ── IFRAME RESELLER STORE (FASE 4) ──────────────────────── -->
  <section style="padding: 60px 5%; background: linear-gradient(135deg, #f8f9fa 0%, #f0f2f5 100%);">
    <div class="gano-container">
      <h2 style="text-align: center; margin-bottom: 10px;">Elige tu plan y activa hoy</h2>
      <p style="text-align: center; color: #64748b; margin-bottom: 40px; font-size: 1.1rem;">
        Selecciona el ecosistema que se adapte a tu proyecto. El checkout está integrado y listo.
      </p>

      <!-- Tabs para seleccionar ecosistema -->
      <div class="gano-reseller-tabs" style="display: flex; gap: 10px; margin-bottom: 30px; justify-content: center; flex-wrap: wrap;">
        <button class="gano-reseller-tab-btn active" data-tab="hosting_economia" style="padding: 10px 20px; border: 1px solid #e2e8f0; background: white; border-radius: 6px; cursor: pointer; font-weight: 500;">
          Núcleo Prime
        </button>
        <button class="gano-reseller-tab-btn" data-tab="hosting_deluxe" style="padding: 10px 20px; border: 1px solid #e2e8f0; background: white; border-radius: 6px; cursor: pointer; font-weight: 500;">
          Fortaleza Delta
        </button>
        <button class="gano-reseller-tab-btn" data-tab="hosting_premium" style="padding: 10px 20px; border: 1px solid #e2e8f0; background: white; border-radius: 6px; cursor: pointer; font-weight: 500;">
          Bastión SOTA
        </button>
        <button class="gano-reseller-tab-btn" data-tab="hosting_ultimate" style="padding: 10px 20px; border: 1px solid #e2e8f0; background: white; border-radius: 6px; cursor: pointer; font-weight: 500;">
          Ultimate WP
        </button>
      </div>

      <!-- Iframes para cada ecosistema -->
      <div class="gano-reseller-tabs-content">
        <div class="gano-reseller-tab-pane active" data-tab="hosting_economia">
          <?php echo do_shortcode( '[gano_reseller_iframe ecosistema="hosting_economia" heading="Núcleo Prime — Elige tu plan"]' ); ?>
        </div>
        <div class="gano-reseller-tab-pane" data-tab="hosting_deluxe">
          <?php echo do_shortcode( '[gano_reseller_iframe ecosistema="hosting_deluxe" heading="Fortaleza Delta — Elige tu plan"]' ); ?>
        </div>
        <div class="gano-reseller-tab-pane" data-tab="hosting_premium">
          <?php echo do_shortcode( '[gano_reseller_iframe ecosistema="hosting_premium" heading="Bastión SOTA — Elige tu plan"]' ); ?>
        </div>
        <div class="gano-reseller-tab-pane" data-tab="hosting_ultimate">
          <?php echo do_shortcode( '[gano_reseller_iframe ecosistema="hosting_ultimate" heading="Ultimate WP — Elige tu plan"]' ); ?>
        </div>
      </div>

      <script>
        // Tab switching logic para iframes Reseller
        document.querySelectorAll('.gano-reseller-tab-btn').forEach(button => {
          button.addEventListener('click', function() {
            const tab = this.getAttribute('data-tab');

            // Remove active class from all buttons and panes
            document.querySelectorAll('.gano-reseller-tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.gano-reseller-tab-pane').forEach(pane => pane.classList.remove('active'));

            // Add active class to clicked button and corresponding pane
            this.classList.add('active');
            this.style.background = '#FF6B35';
            this.style.color = 'white';
            this.style.borderColor = '#FF6B35';

            document.querySelector(`[data-tab="${tab}"].gano-reseller-tab-pane`).classList.add('active');
            document.querySelector(`[data-tab="${tab}"].gano-reseller-tab-pane`).style.display = 'block';

            // Reset styles for other buttons
            document.querySelectorAll('.gano-reseller-tab-btn').forEach(btn => {
              if (btn !== this) {
                btn.style.background = 'white';
                btn.style.color = 'inherit';
                btn.style.borderColor = '#e2e8f0';
              }
            });
          });
        });

        // Hide all panes except the first one
        document.querySelectorAll('.gano-reseller-tab-pane').forEach((pane, idx) => {
          pane.style.display = idx === 0 ? 'block' : 'none';
        });
      </script>

      <style>
        .gano-reseller-tab-btn.active {
          background-color: #FF6B35 !important;
          color: white !important;
          border-color: #FF6B35 !important;
        }

        .gano-reseller-tab-pane {
          display: none;
        }

        .gano-reseller-tab-pane.active {
          display: block;
        }
      </style>
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
