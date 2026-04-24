<section class="section-gano gano-home-section" id="ecosistemas" data-gano-catalog aria-labelledby="ecosistemas-heading">
    <div class="gano-shell">
        <div class="section-title-gano">
            <h2 id="ecosistemas-heading">Ecosistemas SOTA 2026</h2>
            <p>Elige el nivel de soporte, arquitectura y crecimiento que tu negocio necesita.</p>
        </div>
        <div class="gano-catalog-mode-switch" role="group" aria-label="Modo de navegación del catálogo">
            <?php
            $home_modes = function_exists( 'gano_get_catalog_nav_modes' ) ? gano_get_catalog_nav_modes() : array();
            $home_primary_modes = array( 'grid', 'family' );
            foreach ( $home_primary_modes as $mode_key ) :
                if ( ! isset( $home_modes[ $mode_key ] ) ) {
                    continue;
                }
                ?>
                <button type="button" class="gano-catalog-mode-btn" data-gano-mode="<?php echo esc_attr( $mode_key ); ?>" aria-pressed="false">
                    <?php echo esc_html( $home_modes[ $mode_key ]['label'] ); ?>
                </button>
            <?php endforeach; ?>
            <button type="button" class="gano-catalog-advanced-toggle" data-gano-advanced-toggle aria-expanded="false">
                Modos avanzados
            </button>
        </div>
        <div class="gano-catalog-advanced-modes" data-gano-advanced-modes hidden>
            <?php
            if ( isset( $home_modes['guided'] ) ) :
                ?>
                <button type="button" class="gano-catalog-mode-btn" data-gano-mode="guided" aria-pressed="false">
                    <?php echo esc_html( $home_modes['guided']['label'] ); ?>
                </button>
            <?php endif; ?>
        </div>
        <p class="gano-catalog-mode-desc" data-gano-mode-description>
            Navega por vista general, familias o asistente guiado según tu contexto.
        </p>
        <section class="gano-catalog-guided-panel" data-gano-guided-panel aria-label="Asistente de selección">
            <ul class="gano-catalog-guided-list" data-gano-guided-list></ul>
        </section>
        <div class="ecosistemas-grid" id="catalog-container">
            <?php
            $ecosistemas = array(
                array(
                    'nombre'    => 'Núcleo Prime',
                    'slug'      => 'wordpress-basico',
                    'precio'    => '$196.000',
                    'categoria' => 'hostingwebcpanel',
                    'features'  => array(
                        'Almacenamiento NVMe',
                        'Soporte en español por ticket',
                        'Activación rápida'
                    )
                ),
                array(
                    'nombre'    => 'Fortaleza Delta',
                    'slug'      => 'wordpress-deluxe',
                    'precio'    => '$450.000',
                    'categoria' => 'wordpressadministrado',
                    'features'  => array(
                        'Hardening activo incluido',
                        'Mayor capacidad de recursos',
                        'Respaldos automatizados'
                    )
                ),
                array(
                    'nombre'    => 'Bastión SOTA',
                    'slug'      => 'wordpress-ultimate',
                    'precio'    => '$890.000',
                    'categoria' => 'seguridadweb',
                    'features'  => array(
                        'Recursos dedicados',
                        'SLA ≥ 99.9%',
                        'Monitoreo proactivo'
                    )
                ),
                array(
                    'nombre'    => 'Ultimate WP',
                    'slug'      => 'cpanel-ultimate',
                    'precio'    => '$1.200.000',
                    'categoria' => 'servidoresvps',
                    'features'  => array(
                        'Máxima capacidad de recursos',
                        'Blindaje ante picos masivos',
                        'Soporte prioritario'
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
                    data-product-id="<?php echo esc_attr( sanitize_title( $eco['slug'] ) ); ?>"
                    data-product-name="<?php echo esc_attr( $eco['nombre'] ); ?>"
                    data-product-price="<?php echo esc_attr( $eco['precio'] ); ?>"
                >
                    <h3><?php echo esc_html( $eco['nombre'] ); ?></h3>
                    <div class="price"><?php echo esc_html( $eco['precio'] ); ?></div>
                    <ul>
                        <?php foreach ( $eco['features'] as $feature ) : ?>
                            <li><?php echo esc_html( $feature ); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php
                    if ( $post_id ) {
                        echo do_shortcode( "[rstore_product post_id={$post_id} show_price=1 redirect=1 button_label='Elegir Plan']" );
                    } else {
                        echo '<a href="' . esc_url( home_url( '/contacto/' ) ) . '" class="btn-gano">Consultar</a>';
                    }
                    ?>
                    <button type="button" class="gano-catalog-compare-toggle" data-gano-compare-toggle aria-pressed="false">
                        Comparar
                    </button>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="gano-catalog-mobile-actions">
            <button type="button" class="btn-gano btn-gano--secondary" data-gano-mobile-more aria-expanded="false">
                Ver más planes
            </button>
        </div>
        <section class="gano-catalog-comparator" data-gano-compare hidden>
            <h3 class="gano-catalog-comparator-title">Comparador inteligente (hasta 3)</h3>
            <ul class="gano-catalog-compare-list" data-gano-compare-list></ul>
            <div class="gano-catalog-compare-grid" data-gano-compare-grid></div>
        </section>
    </div>
</section>
