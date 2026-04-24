<?php
/**
 * Template Name: Gano Premium Shop SOTA
 *
 * Este template renderiza el catálogo SOTA de forma agnóstica utilizando
 * el motor React-like (catalog-sota.js) que consume reseller-data.js.
 */

// Enqueue catalog assets specific to this template BEFORE get_header()
wp_enqueue_style( 'gano-fonts-sota', 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Manrope:wght@400;500;600&display=swap', array(), null );
wp_enqueue_style( 'gano-catalog-sota-css', get_stylesheet_directory_uri() . '/css/catalog-sota.css', array(), '1.0.0' );

// Load font-awesome if not loaded
wp_enqueue_style( 'font-awesome-6', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', array(), '6.5.0' );

// Enqueue data and logic
wp_enqueue_script( 'gano-reseller-data', get_stylesheet_directory_uri() . '/js/reseller-data.js', array(), '1.0.0', true );
wp_enqueue_script( 'gano-catalog-sota-js', get_stylesheet_directory_uri() . '/js/catalog-sota.js', array('gano-reseller-data'), '1.0.0', true );

// Pass PHP config variables to JS if needed
wp_localize_script( 'gano-reseller-data', 'ganoCatalogWP', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'whatsappNum' => '573000000000'
));

get_header();
?>

<!-- START: SHOP PREMIUM SOTA WRAPPER -->
<div class="sota-wrapper gano-catalog-shell">
    
    <!-- HERO SECTION -->
    <section class="hero">
      <div class="container">
        <div class="hero-eyebrow"><i class="fa-solid fa-shield-halved"></i> Infraestructura SOTA · Nodo Bogotá</div>
        <h1>Tu soberanía digital<br><span class="accent">sin concesiones</span></h1>
        <p class="hero-sub">Hosting WordPress NVMe, VPS dedicado, dominios .co y seguridad de capa 7. Todo en COP, soporte en español, datos en Colombia.</p>
        <div class="hero-stats">
          <div class="hero-stat"><span class="stat-val">99.99%</span><span class="stat-lbl">SLA Elite</span></div>
          <div class="stat-div"></div>
          <div class="hero-stat"><span class="stat-val">&lt;20ms</span><span class="stat-lbl">Latencia Bogotá</span></div>
          <div class="stat-div"></div>
          <div class="hero-stat"><span class="stat-val">10 min</span><span class="stat-lbl">Activación</span></div>
          <div class="stat-div"></div>
          <div class="hero-stat"><span class="stat-val">24/7</span><span class="stat-lbl">Agente IA</span></div>
        </div>
      </div>
    </section>

    <!-- REACT-LIKE CATALOG MOUNT POINT -->
    <div id="gano-catalog-root">
        <!-- FILTERS -->
        <section class="filter-section" id="catalogo">
          <div class="container">
            <div class="filter-inner">
              <div class="filter-row-top">
                <div class="filter-tabs" id="cat-tabs"></div>
                <div class="price-toggle-wrap">
                  <span class="ptl" id="lbl-monthly">Mensual</span>
                  <label class="toggle-pill">
                    <input type="checkbox" id="price-toggle">
                    <div class="ttrack"><div class="tthumb"></div></div>
                  </label>
                  <span class="ptl on" id="lbl-annual">Anual</span>
                  <span class="save-pill">Ahorra hasta 20%</span>
                </div>
              </div>
              <div class="obj-row">
                <span class="obj-label"><i class="fa-solid fa-sliders"></i> Objetivo:</span>
                <div class="obj-chips" id="obj-chips"></div>
              </div>
              <div class="results-bar">
                <span class="rcount">Mostrando <strong id="results-count">0</strong> productos</span>
              </div>
            </div>
          </div>
        </section>

        <!-- CATALOG GRID -->
        <section class="catalog-grid-wrap">
          <div class="container">
            <div class="product-grid" id="product-grid">
              <div class="no-results">Cargando catálogo…</div>
            </div>
          </div>
        </section>

        <!-- TIMELINE -->
        <section class="timeline-section">
          <div class="container">
            <div class="section-eyebrow"><i class="fa-solid fa-clock"></i> Onboarding Express</div>
            <h2 class="section-title">De cero a online<br>en <span class="accent">10 minutos</span></h2>
            <p class="section-sub">El proceso más rápido del mercado colombiano. Sin burocracia, sin esperas.</p>
            <div class="timeline-track" id="timeline-track"></div>
          </div>
        </section>
        
        <!-- GLOSSARY TOOLTIP -->
        <div class="gtooltip" id="gtooltip">
          <div class="gtt-title" id="gtt-title"></div>
          <div class="gtt-body" id="gtt-body"></div>
          <span class="gtt-metric" id="gtt-metric"></span>
        </div>
    </div>

    <!-- FOOTER CTA -->
    <div class="container" style="margin-top: 80px; margin-bottom: 80px;">
        <?php 
        if ( function_exists( 'gano_cta_registro' ) ) {
            gano_cta_registro( array(
                'heading'     => '¿Tienes dudas sobre qué plan elegir?',
                'description' => 'Un ingeniero SOTA te asesora sin costo. Registra tu interés y recibe acompañamiento inmediato para elegir la infraestructura que tu proyecto merece.',
                'button_text' => 'Comenzar ahora',
                'class'       => 'gano-cta-registro--hero-section'
            ) );
        }
        ?>
    </div>


</div>
<!-- END: SHOP PREMIUM SOTA WRAPPER -->

<?php get_footer(); ?>
