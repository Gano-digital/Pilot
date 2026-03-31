<?php
/**
 * Template Name: SEO Landing Page — Gano Digital
 * Template Post Type: page
 *
 * Template PHP para páginas de aterrizaje SEO orientadas a keywords de búsqueda
 * específicas del mercado colombiano de hosting.
 *
 * Uso:
 *   1. Crear nueva página en wp-admin → Páginas → Añadir nueva
 *   2. En "Atributos de la página" → Plantilla → seleccionar "SEO Landing Page"
 *   3. Configurar la keyword objetivo en el campo personalizado "seo_keyword_target"
 *   4. Editar el contenido de la página con Elementor (el template solo provee el wrapper)
 *
 * Campos personalizados ACF/CMB2 opcionales (usar update_post_meta() si no hay ACF):
 *   - seo_keyword_target  : Keyword principal (ej: "hosting wordpress colombia")
 *   - seo_h1_override     : H1 personalizado (si se quiere diferente del post_title)
 *   - seo_cta_text        : Texto del CTA principal
 *   - seo_cta_url         : URL del CTA
 *
 * Fase 3 — Gano Digital, Marzo 2026.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

// Recuperar metadatos SEO específicos de esta landing page
$post_id        = get_the_ID();
$keyword        = get_post_meta( $post_id, 'seo_keyword_target', true ) ?: '';
$h1_override    = get_post_meta( $post_id, 'seo_h1_override',    true ) ?: get_the_title();
$cta_text       = get_post_meta( $post_id, 'seo_cta_text',       true ) ?: 'Ver planes de hosting';
$cta_url        = get_post_meta( $post_id, 'seo_cta_url',        true ) ?: get_site_url() . '/ecosistemas';

// Schema específico de esta landing page (Service schema para Google)
$cfg = function_exists( 'gano_seo_config' ) ? gano_seo_config() : array(
    'name'     => 'Gano Digital',
    'site_url' => get_site_url(),
    'phone'    => get_option( 'gano_seo_phone', '' ),
    'email'    => get_option( 'gano_seo_email', 'hola@gano.digital' ),
);

$service_schema = array(
    '@context'        => 'https://schema.org',
    '@type'           => 'Service',
    '@id'             => get_permalink() . '#service',
    'name'            => get_the_title(),
    'description'     => wp_strip_all_tags( get_the_excerpt() ?: get_the_title() ),
    'url'             => get_permalink(),
    'inLanguage'      => 'es-CO',
    'provider'        => array(
        '@type' => 'Organization',
        '@id'   => $cfg['site_url'] . '/#organization',
        'name'  => $cfg['name'],
    ),
    'areaServed'      => array(
        array( '@type' => 'Country', 'name' => 'Colombia' ),
        array( '@type' => 'City',    'name' => 'Bogotá' ),
    ),
    'hasOfferCatalog' => array(
        '@type'           => 'OfferCatalog',
        'name'            => 'Planes de Hosting WordPress Colombia',
        'itemListElement' => array(
            array(
                '@type'           => 'Offer',
                'name'            => 'Startup Blueprint',
                'price'           => '196000',
                'priceCurrency'   => 'COP',
                'description'     => 'Hosting WordPress de alto rendimiento con NVMe Gen4, SSL gratuito, Wordfence y soporte 24/7.',
                'availability'    => 'https://schema.org/InStock',
            ),
            array(
                '@type'           => 'Offer',
                'name'            => 'Ecosistema Básico',
                'price'           => '600000',
                'priceCurrency'   => 'COP',
                'description'     => 'Hosting WordPress con e-commerce WooCommerce, pasarela Wompi y seguridad avanzada.',
                'availability'    => 'https://schema.org/InStock',
            ),
            array(
                '@type'           => 'Offer',
                'name'            => 'Ecosistema Avanzado',
                'price'           => '1200000',
                'priceCurrency'   => 'COP',
                'description'     => 'Hosting WordPress empresarial con CDN, backups automáticos, monitoring y soporte dedicado.',
                'availability'    => 'https://schema.org/InStock',
            ),
            array(
                '@type'           => 'Offer',
                'name'            => 'Soberanía Digital',
                'price'           => '2000000',
                'priceCurrency'   => 'COP',
                'description'     => 'Infraestructura dedicada con Zero-Trust, cifrado post-cuántico y propiedad absoluta de datos.',
                'availability'    => 'https://schema.org/InStock',
            ),
        ),
    ),
);

// Emitir schema de servicio en el <head>
add_action( 'wp_head', function() use ( $service_schema ) {
    echo "\n<!-- Gano Digital: Schema JSON-LD Servicio (SEO Landing) -->\n";
    echo '<script type="application/ld+json">'
        . wp_json_encode( $service_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
        . '</script>' . "\n";
}, 8 );

?>

<!-- Gano Digital: SEO Landing Page Template -->
<main id="gano-seo-landing" class="gano-seo-landing" role="main">

    <!-- H1 SEO — Visible para Google y usuarios, estilizado via Elementor o CSS del child theme -->
    <section class="gano-landing-hero elementor-section">
        <div class="elementor-container">
            <h1 class="gano-landing-h1"><?php echo esc_html( $h1_override ); ?></h1>

            <?php if ( has_excerpt() ) : ?>
                <p class="gano-landing-intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
            <?php endif; ?>

            <a href="<?php echo esc_url( $cta_url ); ?>" class="gano-btn-primary" rel="noopener">
                <?php echo esc_html( $cta_text ); ?>
            </a>
        </div>
    </section>

    <!-- Contenido principal — editable con Elementor -->
    <section class="gano-landing-content">
        <div class="elementor-container">
            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </section>

    <!-- Tabla de precios en COP — visible solo en páginas de landing de hosting -->
    <section class="gano-landing-pricing" aria-label="Planes de hosting">
        <div class="elementor-container">
            <h2>Planes de Hosting WordPress en Colombia</h2>
            <p class="gano-pricing-subtitle">Todos los precios en pesos colombianos (COP). Sin cargos por conversión de divisas.</p>

            <div class="gano-plans-grid">
                <?php
                // Obtener los 4 productos WooCommerce (ecosistemas) por SKU o categoría
                $plan_skus = array( 'GD-STARTUP-01', 'GD-BASIC-01', 'GD-ADVANCED-01', 'GD-SOBERANIA-01' );
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 4,
                    'post_status'    => 'publish',
                    'meta_query'     => array(
                        array(
                            'key'     => '_sku',
                            'value'   => $plan_skus,
                            'compare' => 'IN',
                        ),
                    ),
                    'orderby'       => 'meta_value',
                    'meta_key'      => '_price',
                    'order'         => 'ASC',
                );
                $products = new WP_Query( $args );

                if ( $products->have_posts() ) :
                    while ( $products->have_posts() ) :
                        $products->the_post();
                        $wc_product = wc_get_product( get_the_ID() );
                        if ( ! $wc_product ) continue;
                        $price    = $wc_product->get_regular_price();
                        $currency = get_woocommerce_currency_symbol();
                        ?>
                        <article class="gano-plan-card" itemscope itemtype="https://schema.org/Product">
                            <h3 itemprop="name"><?php the_title(); ?></h3>
                            <div class="gano-plan-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <meta itemprop="priceCurrency" content="COP">
                                <span itemprop="price" content="<?php echo esc_attr( $price ); ?>">
                                    <?php echo esc_html( $currency ); ?>&nbsp;<?php echo esc_html( number_format( (float) $price, 0, ',', '.' ) ); ?>
                                </span>
                                <span class="gano-plan-period">/mes</span>
                            </div>
                            <?php if ( has_excerpt() ) : ?>
                                <p class="gano-plan-desc" itemprop="description"><?php the_excerpt(); ?></p>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="gano-btn-secondary" itemprop="url">
                                Ver detalles
                            </a>
                        </article>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback estático si WooCommerce no está activo o no hay productos aún
                    $static_plans = array(
                        array( 'name' => 'Startup Blueprint',   'price' => '196.000', 'desc' => 'Hosting WordPress NVMe Gen4, SSL, Wordfence, soporte 24/7.' ),
                        array( 'name' => 'Ecosistema Básico',   'price' => '600.000', 'desc' => 'WooCommerce + Wompi PSE, seguridad avanzada, migraciones incluidas.' ),
                        array( 'name' => 'Ecosistema Avanzado', 'price' => '1.200.000','desc' => 'CDN, backups automáticos, monitoring, soporte dedicado.' ),
                        array( 'name' => 'Soberanía Digital',   'price' => '2.000.000','desc' => 'Infraestructura dedicada, Zero-Trust, cifrado post-cuántico.' ),
                    );
                    foreach ( $static_plans as $plan ) :
                        ?>
                        <article class="gano-plan-card" itemscope itemtype="https://schema.org/Product">
                            <h3 itemprop="name"><?php echo esc_html( $plan['name'] ); ?></h3>
                            <div class="gano-plan-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <meta itemprop="priceCurrency" content="COP">
                                <span itemprop="price">$&nbsp;<?php echo esc_html( $plan['price'] ); ?></span>
                                <span class="gano-plan-period">/mes COP</span>
                            </div>
                            <p class="gano-plan-desc" itemprop="description"><?php echo esc_html( $plan['desc'] ); ?></p>
                            <a href="<?php echo esc_url( get_site_url() . '/ecosistemas' ); ?>" class="gano-btn-secondary">Ver plan</a>
                        </article>
                        <?php
                    endforeach;
                endif;
                ?>
            </div><!-- .gano-plans-grid -->
        </div>
    </section>

    <!-- Señales de confianza / Trust signals para conversión -->
    <section class="gano-landing-trust" aria-label="Por qué Gano Digital">
        <div class="elementor-container">
            <h2>¿Por qué elegir hosting WordPress en Colombia con Gano Digital?</h2>
            <ul class="gano-trust-list">
                <li><strong>Facturación en COP</strong> — Sin conversiones de divisas ni cargos internacionales.</li>
                <li><strong>Soporte en español 24/7</strong> — Agentes en Colombia, no bots ni formularios.</li>
                <li><strong>Seguridad empresarial</strong> — WAF, Wordfence, MU Plugin hardening, 2FA y backups diarios.</li>
                <li><strong>Paga con PSE, Nequi o tarjeta</strong> — Procesamiento vía Wompi Colombia.</li>
                <li><strong>Migraciones incluidas</strong> — Traemos tu sitio sin tiempo de inactividad.</li>
                <li><strong>Cumplimiento Ley 1581</strong> — Protección de datos colombiana garantizada.</li>
            </ul>
        </div>
    </section>

</main><!-- #gano-seo-landing -->

<?php
// Estilos mínimos inline para el template (el diseño real lo toma Elementor)
add_action( 'wp_footer', function() {
    ?>
    <style id="gano-landing-css">
    .gano-seo-landing { font-family: inherit; }
    .gano-landing-hero { padding: 60px 20px; text-align: center; background: var(--e-global-color-primary, #0a0a0a); color: #fff; }
    .gano-landing-h1   { font-size: clamp(1.8rem, 4vw, 3rem); font-weight: 700; margin: 0 0 1rem; }
    .gano-landing-intro { font-size: 1.1rem; max-width: 640px; margin: 0 auto 2rem; opacity: .85; }
    .gano-btn-primary   { display: inline-block; padding: .9rem 2.2rem; background: #f5a623; color: #000; font-weight: 700; border-radius: 6px; text-decoration: none; transition: opacity .2s; }
    .gano-btn-primary:hover { opacity: .85; }
    .gano-btn-secondary { display: inline-block; padding: .6rem 1.4rem; border: 2px solid currentColor; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: .5rem; }
    .gano-landing-pricing, .gano-landing-trust, .gano-landing-content { padding: 60px 20px; }
    .gano-landing-pricing { background: #f8f8f8; }
    .gano-landing-pricing h2, .gano-landing-trust h2 { text-align: center; margin-bottom: .5rem; }
    .gano-pricing-subtitle { text-align: center; color: #666; margin-bottom: 2.5rem; }
    .gano-plans-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; max-width: 1100px; margin: 0 auto; }
    .gano-plan-card   { background: #fff; border: 1px solid #e0e0e0; border-radius: 12px; padding: 1.5rem; text-align: center; }
    .gano-plan-card h3 { margin: 0 0 .75rem; font-size: 1.1rem; }
    .gano-plan-price  { font-size: 1.5rem; font-weight: 700; color: #0a0a0a; margin-bottom: .5rem; }
    .gano-plan-period { font-size: .85rem; font-weight: 400; color: #666; }
    .gano-plan-desc   { font-size: .9rem; color: #555; margin: .75rem 0; }
    .gano-trust-list  { max-width: 700px; margin: 0 auto; padding-left: 1.2rem; line-height: 2; }
    @media (prefers-color-scheme: dark) {
        .gano-plan-card { background: #1a1a1a; border-color: #333; color: #eee; }
        .gano-landing-pricing { background: #111; }
    }
    </style>
    <?php
} );

get_footer();
