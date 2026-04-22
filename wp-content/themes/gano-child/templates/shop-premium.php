<?php
/**
 * Template Name: Gano Premium Shop SOTA
 *
 * Este template renderiza el catálogo SOTA de forma agnóstica utilizando
 * el motor React-like (catalog-sota.js) que consume reseller-data.js.
 */

// Enqueue catalog assets specific to this template BEFORE get_header()
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
    <section class="catalog-hero" style="text-align: center; padding: 80px 20px; background: var(--gano-dark-deep);">
        <h1 style="font-size: 3rem; color: #fff; margin-bottom: 20px; font-family: var(--gano-font-heading);">
            Soberanía digital <span style="color: var(--gano-purple);">sin concesiones</span>
        </h1>
        <p style="color: var(--gano-text-slate); max-width: 600px; margin: 0 auto 40px auto; font-size: 1.1rem; line-height: 1.6;">
            Infraestructura de alto rendimiento con IP dedicada en Bogotá. Selecciona el ecosistema que mejor se adapte a tu operación.
        </p>
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

    <!-- PILLARS PREVIEW -->
    <section style="padding: 80px 20px; text-align: center; border-top: 1px solid var(--gano-border-sota);">
        <h2 style="color: #fff; margin-bottom: 40px;">Pivotes de Arquitectura SOTA</h2>
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <div style="padding: 20px 40px; border: 1px solid var(--gano-border-sota); background: rgba(255,255,255,0.02); color: #fff;">NVMe Gen4</div>
            <div style="padding: 20px 40px; border: 1px solid var(--gano-border-sota); background: rgba(255,255,255,0.02); color: #fff;">Zero-Trust</div>
            <div style="padding: 20px 40px; border: 1px solid var(--gano-border-sota); background: rgba(255,255,255,0.02); color: #fff;">CDN Edge</div>
            <div style="padding: 20px 40px; border: 1px solid var(--gano-border-sota); background: rgba(255,255,255,0.02); color: #fff;">Uptime 99.9%</div>
        </div>
    </section>

</div>
<!-- END: SHOP PREMIUM SOTA WRAPPER -->

<?php get_footer(); ?>
