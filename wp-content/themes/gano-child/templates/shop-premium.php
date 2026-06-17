<?php
/**
 * Template Name: Cat├ílogo SOTA v2
 * Template Post Type: page
 *
 * Gano Digital ÔÇö Cat├ílogo de Servicios
 * v2.0.0 ÔÇö solo productos MEDIO/ALTO, dise├▒o GoDaddy-inspired
 */

defined('ABSPATH') || exit;
get_header();
$theme_uri = get_stylesheet_directory_uri();
?>

<!----------- ASSETS ----------->
<link rel="stylesheet" href="<?php echo $theme_uri; ?>/catalog-sota-v2.css?v=2.2.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous">

<main id="catalog-main" class="catalog-wrapper">

  <!-- ÔöÇÔöÇ HERO ÔöÇÔöÇ -->
  <section class="catalog-hero">
    <h1>Infraestructura digital<br><em>a tu medida</em></h1>
    <p>Elige el plan que m├ís se ajuste a tu proyecto. Todos incluyen soporte por ingeniero SOTA, precios en pesos colombianos y activaci├│n en minutos.</p>
  </section>

  <!-- ÔöÇÔöÇ CONTROLES ÔöÇÔöÇ -->
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

    <!-- Tabs categor├¡a -->
    <div id="cat-tabs" role="tablist" aria-label="Categor├¡a de servicios"></div>

    <!-- Chips objetivo -->
    <div id="obj-chips" role="group" aria-label="Filtrar por objetivo"></div>

    <!-- Contador -->
    <div class="results-meta">
      <span id="results-count">CargandoÔÇª</span>
    </div>

  </div>

  <!-- ÔöÇÔöÇ GRID ÔöÇÔöÇ -->
  <div id="product-grid" role="list" aria-live="polite">
    <!-- renderGrid() lo llena -->
  </div>

  <!-- ÔöÇÔöÇ TIMELINE ÔöÇÔöÇ -->
  <section class="timeline-section">
    <h2>┬┐C├│mo funciona la activaci├│n?</h2>
    <div id="timeline-track"></div>
  </section>

  <!-- ÔöÇÔöÇ TOOLTIP GLOSARIO ÔöÇÔöÇ -->
  <div id="gtooltip" role="tooltip">
    <div id="gtt-title"></div>
    <div id="gtt-body"></div>
    <div id="gtt-metric"></div>
  </div>

</main>

<!-- ÔöÇÔöÇ SCRIPTS ÔöÇÔöÇ -->
<script src="<?php echo $theme_uri; ?>/reseller-data-v2.js?v=2.0.0"></script>
<script src="<?php echo $theme_uri; ?>/catalog-sota-v2.js?v=2.2.0"></script>

<?php get_footer(); ?>
