<?php
/**
 * Template Name: Catálogo SOTA v2
 * Template Post Type: page
 *
 * Gano Digital — Catálogo de Servicios
 * v2.0.0 — solo productos MEDIO/ALTO, diseño GoDaddy-inspired
 */

defined('ABSPATH') || exit;
get_header();
$theme_uri = get_stylesheet_directory_uri();
?>

<!----------- ASSETS ----------->
<link rel="stylesheet" href="<?php echo $theme_uri; ?>/catalog-sota-v2.css?v=2.0.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">

<main id="catalog-main" class="catalog-wrapper">

  <!-- ── HERO ── -->
  <section class="catalog-hero">
    <h1>Infraestructura digital<br><em>a tu medida</em></h1>
    <p>Elige el plan que más se ajuste a tu proyecto. Todos incluyen soporte por ingeniero SOTA, precios en pesos colombianos y activación en minutos.</p>
  </section>

  <!-- ── CONTROLES ── -->
  <div class="catalog-controls">

    <!-- Toggle mensual / anual -->
    <div class="price-toggle-wrap">
      <label id="lbl-monthly" for="price-toggle">Mensual</label>
      <label class="toggle-switch">
        <input type="checkbox" id="price-toggle" checked>
        <span class="toggle-slider"></span>
      </label>
      <label id="lbl-annual" class="active" for="price-toggle">Anual</label>
      <span class="save-badge">Ahorra hasta 17%</span>
    </div>

    <!-- Tabs categoría -->
    <div id="cat-tabs" role="tablist" aria-label="Categoría de servicios"></div>

    <!-- Chips objetivo -->
    <div id="obj-chips" role="group" aria-label="Filtrar por objetivo"></div>

    <!-- Contador -->
    <div class="results-meta">
      <span id="results-count">Cargando…</span>
    </div>

  </div>

  <!-- ── GRID ── -->
  <div id="product-grid" role="list" aria-live="polite">
    <!-- renderGrid() lo llena -->
  </div>

  <!-- ── TIMELINE ── -->
  <section class="timeline-section">
    <h2>¿Cómo funciona la activación?</h2>
    <div id="timeline-track"></div>
  </section>

  <!-- ── TOOLTIP GLOSARIO ── -->
  <div id="gtooltip" role="tooltip">
    <div id="gtt-title"></div>
    <div id="gtt-body"></div>
    <div id="gtt-metric"></div>
  </div>

</main>

<!-- ── SCRIPTS ── -->
<script src="<?php echo $theme_uri; ?>/reseller-data-v2.js?v=2.0.0"></script>
<script src="<?php echo $theme_uri; ?>/catalog-sota-v2.js?v=2.0.0"></script>

<?php get_footer(); ?>
