<?php
/**
 * Template Name: Gano Digital — Homepage
 * Description: Front page principal con hero parallax, ecosistemas dinámicos y propuesta de valor
 * SOTA aesthetic — no Elementor
 */

get_header();
?>

<main class="gano-home" id="gano-home-main" data-gano-homepage>
    <section class="hero-gano gano-hero-overlay">
        <div class="hero-gano__ghost" aria-hidden="true">
            <span class="hero-gano__ghost-line"><?php esc_html_e( 'SOTA', 'gano-child' ); ?></span>
            <span class="hero-gano__ghost-line hero-gano__ghost-line--accent"><?php esc_html_e( 'COLOMBIA', 'gano-child' ); ?></span>
        </div>
        <div class="gano-shell hero-content">
            <p class="overline">Soberanía Digital · Operación Colombia</p>
            <h1>Infraestructura NVMe blindada y un agente IA que trabaja antes de que algo falle.</h1>
            <p>Hosting WordPress de alto rendimiento con seguridad de nivel empresarial, soporte en español y operación en pesos colombianos. Menos ruido, más continuidad.</p>
            <div class="hero-cta-row">
                <?php
                $url_shop = function_exists( 'gano_resolve_page_url' )
                    ? gano_resolve_page_url( 'shop-premium', 'tienda', 'catalogo' )
                    : home_url( '/shop-premium/' );
                ?>
                <a href="#ecosistemas" class="btn-holo">
                    <span class="label">Ver arquitecturas y planes</span>
                    <span class="arrow">↓</span>
                </a>
                <a href="#registro-sota" class="btn-gano btn-gano--secondary">Activar acceso ahora</a>
            </div>
            <div class="hero-gano__proof-glass">
                <p class="hero-gano__proof-label"><?php esc_html_e( 'Marco operativo', 'gano-child' ); ?></p>
                <ul class="hero-proof-bar" aria-label="<?php esc_attr_e( 'Marco operativo: SLA, endurecimiento y operación local', 'gano-child' ); ?>">
                    <li><strong><?php esc_html_e( 'SLA', 'gano-child' ); ?></strong><span><?php esc_html_e( 'Acuerdos de nivel de servicio explícitos', 'gano-child' ); ?></span></li>
                    <li><strong><?php esc_html_e( 'Hardening', 'gano-child' ); ?></strong><span><?php esc_html_e( 'WordPress endurecido y monitoreado', 'gano-child' ); ?></span></li>
                    <li><strong><?php esc_html_e( 'COP', 'gano-child' ); ?></strong><span><?php esc_html_e( 'Facturación y operación en Colombia', 'gano-child' ); ?></span></li>
                </ul>
            </div>
            <?php
            $url_comenzar = function_exists( 'gano_resolve_page_url' )
                ? gano_resolve_page_url( 'comenzar-aqui', 'comenzar', 'registro-y-compra' )
                : home_url( '/comenzar-aqui/' );
            ?>
            <p class="gano-home-comenzar-link">
                <a href="<?php echo esc_url( $url_comenzar ); ?>"><?php esc_html_e( '¿Primera compra? Guía del checkout y registro →', 'gano-child' ); ?></a>
            </p>
        </div>
    </section>

    <!-- SECCIÓN DE REGISTRO UNIFICADA -->
    <section id="registro-sota" class="section-gano gano-home-section">
        <?php
        gano_cta_registro(array(
            'heading'     => 'Únete al Hub SOTA Access',
            'description' => 'Registra tu interés para acceder al catálogo premium y recibe una consultoría inicial sin costo. Agilizamos tu proceso de despliegue desde el primer minuto.',
            'button_text' => 'Iniciar registro seguro',
            'class'       => 'gano-cta-registro--prominent'
        ));
        ?>
    </section>

    <!-- CATÁLOGO ORIGINAL SOTA 2026 -->
    <section class="section-gano gano-home-section gano-home-section--surface" id="ecosistemas" data-gano-catalog aria-labelledby="ecosistemas-heading">
        <div class="gano-shell">
            <div class="section-title-gano">
                <h2 id="ecosistemas-heading">Ecosistemas WordPress SOTA</h2>
                <p>Infraestructura seria, soporte en español, facturación en pesos colombianos. Elige tu nivel de operación.</p>
            </div>
            
            <div class="ecosistemas-grid" id="catalog-container">
                <?php
                $ecosistemas = array(
                    array(
                        'nombre'    => 'Núcleo Prime',
                        'slug'      => 'wordpress-basico',
                        'precio'    => '$196.000',
                        'categoria' => 'hostingwebcpanel',
                        'features'  => array(
                            'Almacenamiento NVMe SSD',
                            'Soporte en español por ticket',
                            'SSL gratuito & Backups'
                        )
                    ),
                    array(
                        'nombre'    => 'Fortaleza Delta',
                        'slug'      => 'wordpress-deluxe',
                        'precio'    => '$450.000',
                        'categoria' => 'wordpressadministrado',
                        'features'  => array(
                            'Hardening activo incluido',
                            'Monitoreo proactivo 24/7',
                            'Respaldos cada 6 horas'
                        )
                    ),
                    array(
                        'nombre'    => 'Bastión SOTA',
                        'slug'      => 'wordpress-ultimate',
                        'precio'    => '$890.000',
                        'categoria' => 'seguridadweb',
                        'features'  => array(
                            'Recursos dedicados aislados',
                            'SLA ≥ 99.9% garantizado',
                            'Soporte prioritario VIP'
                        )
                    ),
                    array(
                        'nombre'    => 'Ultimate WP',
                        'slug'      => 'cpanel-ultimate',
                        'precio'    => '$1.200.000',
                        'categoria' => 'servidoresvps',
                        'features'  => array(
                            'Máxima capacidad de cómputo',
                            'Blindaje ante picos masivos',
                            'Arquitecto dedicado 3h/mes'
                        )
                    ),
                );

                foreach ( $ecosistemas as $eco ) :
                    $product_query = new WP_Query(
                        array(
                            'post_type'      => 'reseller_product',
                            'name'           => $eco['slug'],
                            'posts_per_page' => 1,
                        )
                    );
                    $post_id = $product_query->have_posts() ? $product_query->posts[0]->ID : null;
                    wp_reset_postdata();
                    ?>
                    <article
                        class="ecosystem-card"
                        data-category="<?php echo esc_attr( $eco['categoria'] ); ?>"
                        data-product-name="<?php echo esc_attr( $eco['nombre'] ); ?>"
                    >
                        <h3><?php echo esc_html( $eco['nombre'] ); ?></h3>
                        <div class="price"><?php echo esc_html( $eco['precio'] ); ?> <span>COP/mes</span></div>
                        <ul>
                            <?php foreach ( $eco['features'] as $feature ) : ?>
                                <li><?php echo esc_html( $feature ); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php
                        if ( $post_id ) {
                            echo do_shortcode( "[rstore_product post_id={$post_id} show_price=0 redirect=1 button_label='Seleccionar Plan']" );
                        } else {
                            echo '<a href="' . esc_url( home_url( '/contacto/' ) ) . '" class="btn-gano">Consultar disponibilidad</a>';
                        }
                        ?>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- BÚSQUEDA DE DOMINIOS -->
    <section class="section-gano gano-home-section" aria-labelledby="dominios-heading">
        <div class="gano-shell">
            <div class="section-title-gano">
                <h2 id="dominios-heading">Encuentra tu dominio ideal</h2>
                <p>Búsqueda instantánea de TLDs disponibles para lanzar tu operación.</p>
            </div>
            <div class="gano-domain-search-wrap">
                <?php echo do_shortcode( '[rstore_domain_search]' ); ?>
            </div>
        </div>
    </section>

    <!-- PILARES Y MÉTRICAS -->
    <section class="section-gano gano-home-section gano-home-section--surface" aria-labelledby="valor-heading">
        <div class="gano-shell">
            <div class="section-title-gano">
                <h2 id="valor-heading">Cuatro pilares de la infraestructura SOTA</h2>
                <p>Lo que diferencia a Gano Digital en el mercado colombiano.</p>
            </div>
            <div class="value-grid">
                <article class="value-item">
                    <h3>Velocidad real (NVMe)</h3>
                    <p>Almacenamiento de nueva generación y stack optimizado para WordPress: menos espera, más conversión. Tu sitio carga cuando el cliente ya decidió quedarse.</p>
                </article>
                <article class="value-item">
                    <h3>WordPress blindada</h3>
                    <p>Hardening continuo, controles de acceso y visibilidad sobre lo que ocurre en tu instalación. Menos superficie de ataque, más tranquilidad operativa.</p>
                </article>
                <article class="value-item">
                    <h3>Zero-Trust operativo</h3>
                    <p>Confianza cero por defecto: identidad, sesiones y permisos bajo control. La seguridad no es un cartel: es política aplicada en capas.</p>
                </article>
                <article class="value-item">
                    <h3>Edge y latencia</h3>
                    <p>Contenido más cerca del usuario sin magia barata. Arquitectura pensada para entregar experiencias rápidas donde vive tu audiencia.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- CIERRE Y CONFIANZA -->
    <section class="section-gano gano-home-section" id="cta-final" aria-labelledby="cierre-heading">
        <div class="gano-shell">
            <div class="gano-panel gano-panel--compact">
                <h2 id="cierre-heading">¿Listo para una infraestructura que no te pida disculpas?</h2>
                <?php echo do_shortcode( '[gano_cta_icons]' ); ?>
                <p class="gano-panel-cta">
                    <a href="<?php echo esc_url( $url_shop ); ?>" class="btn-gano">Ir al catálogo premium</a>
                </p>
            </div>
        </div>
    </section>

    <section class="section-gano gano-home-section gano-home-section--surface" aria-label="Confianza y respaldo">
        <div class="gano-shell">
            <?php echo do_shortcode( '[gano_socio_tecnologico]' ); ?>
            <?php echo do_shortcode( '[gano_metrics]' ); ?>
        </div>
    </section>
</main>

<?php
get_footer();
?>
