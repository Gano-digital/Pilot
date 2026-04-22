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
                'name'            => 'Núcleo Prime',
                'price'           => '196000',
                'priceCurrency'   => 'COP',
                'description'     => 'Entrada sólida con NVMe real, SSL y soporte por ticket en español.',
                'availability'    => 'https://schema.org/InStock',
            ),
            array(
                '@type'           => 'Offer',
                'name'            => 'Fortaleza Delta',
                'price'           => '450000',
                'priceCurrency'   => 'COP',
                'description'     => 'Arquitectura para negocios en crecimiento con hardening activo y mejor capacidad.',
                'availability'    => 'https://schema.org/InStock',
            ),
            array(
                '@type'           => 'Offer',
                'name'            => 'Bastión SOTA',
                'price'           => '890000',
                'priceCurrency'   => 'COP',
                'description'     => 'Rendimiento premium con monitoreo proactivo y capa de servicio para operaciones críticas.',
                'availability'    => 'https://schema.org/InStock',
            ),
            array(
                '@type'           => 'Offer',
                'name'            => 'Ultimate WP',
                'price'           => '1200000',
                'priceCurrency'   => 'COP',
                'description'     => 'Máxima capacidad para agencias y proyectos de alto tráfico con operación en COP.',
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

get_header();

?>

<!-- Gano Digital: SEO Landing Page Template -->
<main id="gano-seo-landing" class="gano-seo-landing gano-km-shell" role="main">

    <!-- H1 SEO — Visible para Google y usuarios, estilizado via Elementor o CSS del child theme -->
    <section class="gano-landing-hero elementor-section gano-km-shell">
        <div class="elementor-container gano-km-container">
            <span class="gano-km-live-badge">SEO landing operativa</span>
            <h1 class="gano-landing-h1 gano-km-title"><?php echo esc_html( $h1_override ); ?></h1>

            <?php if ( has_excerpt() ) : ?>
                <p class="gano-landing-intro gano-km-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
            <?php endif; ?>

            <a href="<?php echo esc_url( $cta_url ); ?>" class="gano-btn-primary gano-km-btn-primary" rel="noopener">
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
    <section class="gano-landing-pricing gano-catalog-shell" aria-label="Planes de hosting" data-gano-catalog>
        <div class="elementor-container">
            <h2>Planes de Hosting WordPress en Colombia</h2>
            <p class="gano-pricing-subtitle">Todos los precios en pesos colombianos (COP). Sin cargos por conversión de divisas.</p>
            <div class="gano-catalog-mode-switch" role="group" aria-label="Modo de navegación del catálogo">
                <?php
                $landing_modes = function_exists( 'gano_get_catalog_nav_modes' ) ? gano_get_catalog_nav_modes() : array();
                foreach ( $landing_modes as $mode_key => $mode_meta ) :
                    ?>
                    <button type="button" class="gano-catalog-mode-btn" data-gano-mode="<?php echo esc_attr( $mode_key ); ?>" aria-pressed="false">
                        <?php echo esc_html( $mode_meta['label'] ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <p class="gano-catalog-mode-desc" data-gano-mode-description>
                Navega por vista general, familias o asistente según tu objetivo comercial.
            </p>
            <section class="gano-catalog-guided-panel" data-gano-guided-panel aria-label="Asistente de selección">
                <ul class="gano-catalog-guided-list" data-gano-guided-list></ul>
            </section>

            <div class="gano-plans-grid" id="catalog-container">
                <?php
                $catalog_rows = function_exists( 'gano_get_reseller_catalog_products' ) ? gano_get_reseller_catalog_products() : array();
                if ( ! empty( $catalog_rows ) ) :
                    foreach ( $catalog_rows as $catalog_row ) :
                        if ( ! in_array( $catalog_row['cat'], array( 'hostingwebcpanel', 'webhostingplus', 'wordpressadministrado' ), true ) ) {
                            continue;
                        }
                        $cta = function_exists( 'gano_resolver_catalog_cta' ) ? gano_resolver_catalog_cta( $catalog_row ) : array(
                            'url'    => '#',
                            'label'  => 'Ver detalles',
                            'target' => '',
                            'status' => 'sync-missing',
                        );
                        $card_class = 'gano-plan-card gano-km-card';
                        if ( 'sync-missing' === $cta['status'] ) {
                            $card_class .= ' gano-catalog-sync-missing';
                        }
                        ?>
                        <article class="<?php echo esc_attr( $card_class ); ?>"
                                 data-category="<?php echo esc_attr( $catalog_row['cat'] ); ?>"
                                 data-product-id="<?php echo esc_attr( sanitize_title( $catalog_row['cat'] . '-' . $catalog_row['name'] ) ); ?>"
                                 data-product-name="<?php echo esc_attr( $catalog_row['name'] ); ?>"
                                 data-product-price="<?php echo esc_attr( $catalog_row['price'] ); ?>"
                                 itemscope itemtype="https://schema.org/Product">
                            <h3 itemprop="name"><?php echo esc_html( $catalog_row['name'] ); ?></h3>
                            <div class="gano-plan-price p-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <meta itemprop="priceCurrency" content="COP">
                                <span itemprop="price" content="<?php echo esc_attr( preg_replace( '/[^\d\.]/', '', (string) $catalog_row['price'] ) ); ?>">
                                    <?php echo esc_html( $catalog_row['price'] ); ?>
                                </span>
                                <span class="gano-plan-period"><?php echo esc_html( $catalog_row['price_context'] ?? 'Precio desde' ); ?></span>
                            </div>
                            <p class="gano-plan-desc p-desc" itemprop="description"><?php echo esc_html( $catalog_row['desc'] ); ?></p>
                            <a href="<?php echo esc_url( $cta['url'] ); ?>" class="gano-btn-secondary gano-km-btn-secondary rstore-add-to-cart--<?php echo esc_attr( $cta['status'] ); ?>" itemprop="url" <?php echo $cta['target']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
                                <?php echo esc_html( $cta['label'] ); ?>
                            </a>
                            <button type="button" class="gano-catalog-compare-toggle" data-gano-compare-toggle aria-pressed="false">
                                Comparar
                            </button>
                            <?php if ( 'sync-missing' === $cta['status'] ) : ?>
                                <span class="gano-catalog-sync-note">Precio temporalmente no disponible</span>
                            <?php endif; ?>
                        </article>
                        <?php
                    endforeach;
                else :
                    // Fallback estático si WooCommerce no está activo o no hay productos aún
                    $static_plans = array(
                        array( 'name' => 'Núcleo Prime',    'price' => '196.000',  'desc' => 'Entrada sólida con NVMe real, SSL y soporte por ticket.' ),
                        array( 'name' => 'Fortaleza Delta', 'price' => '450.000',  'desc' => 'Arquitectura para negocios en crecimiento con hardening activo.' ),
                        array( 'name' => 'Bastión SOTA',    'price' => '890.000',  'desc' => 'Rendimiento premium para operaciones críticas y e-commerce.' ),
                        array( 'name' => 'Ultimate WP',     'price' => '1.200.000','desc' => 'Máxima capacidad para agencias y alto tráfico.' ),
                    );
                    foreach ( $static_plans as $plan ) :
                        ?>
                        <article class="gano-plan-card gano-km-card gano-catalog-sync-missing"
                                 data-category="wordpressadministrado"
                                 data-product-id="<?php echo esc_attr( sanitize_title( $plan['name'] ) ); ?>"
                                 data-product-name="<?php echo esc_attr( $plan['name'] ); ?>"
                                 data-product-price="<?php echo esc_attr( $plan['price'] ); ?>"
                                 itemscope itemtype="https://schema.org/Product">
                            <h3 itemprop="name"><?php echo esc_html( $plan['name'] ); ?></h3>
                            <div class="gano-plan-price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <meta itemprop="priceCurrency" content="COP">
                                <span itemprop="price">$&nbsp;<?php echo esc_html( $plan['price'] ); ?></span>
                                <span class="gano-plan-period">/mes COP</span>
                            </div>
                            <p class="gano-plan-desc" itemprop="description"><?php echo esc_html( $plan['desc'] ); ?></p>
                            <a href="<?php echo esc_url( get_site_url() . '/ecosistemas' ); ?>" class="gano-btn-secondary gano-km-btn-secondary">Ver plan</a>
                            <button type="button" class="gano-catalog-compare-toggle" data-gano-compare-toggle aria-pressed="false">
                                Comparar
                            </button>
                        </article>
                        <?php
                    endforeach;
                endif;
                ?>
            </div><!-- .gano-plans-grid -->
            <section class="gano-catalog-comparator" data-gano-compare hidden>
                <h3 class="gano-catalog-comparator-title">Comparador inteligente (hasta 3)</h3>
                <ul class="gano-catalog-compare-list" data-gano-compare-list></ul>
                <div class="gano-catalog-compare-grid" data-gano-compare-grid></div>
            </section>
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
                <li><strong>Checkout validado en Reseller Store</strong> — Flujo comercial conectado al programa de GoDaddy Reseller.</li>
                <li><strong>Migraciones incluidas</strong> — Traemos tu sitio sin tiempo de inactividad.</li>
                <li><strong>Cumplimiento Ley 1581</strong> — Protección de datos colombiana garantizada.</li>
            </ul>
        </div>
    </section>

</main><!-- #gano-seo-landing -->

<?php get_footer();
