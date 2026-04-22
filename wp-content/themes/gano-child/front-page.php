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
                <a href="#ecosistemas" class="btn-holo">
                    <span class="label">Ver arquitecturas y planes</span>
                    <span class="arrow">→</span>
                </a>
                <?php
                $url_shop = function_exists( 'gano_resolve_page_url' )
                    ? gano_resolve_page_url( 'shop-premium', 'tienda', 'catalogo' )
                    : home_url( '/shop-premium/' );
                ?>
                <a href="<?php echo esc_url( $url_shop ); ?>" class="btn-gano btn-gano--secondary">Explorar catálogo inteligente</a>
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

    <?php
    gano_cta_registro(array(
        'heading'     => '¿No sabes por dónde empezar?',
        'description' => '¿no sabes por dónde empezar? registra tu cuenta y recibe soporte inmediato. Nosotros te agendamos. Acompañamos tu empresa en cada decisión: siempre donde verdaderamente importa.',
        'button_text' => 'Registra tu cuenta',
        'class'       => 'gano-cta-registro--hero-section'
    ));
    ?>

    <section class="section-gano gano-home-section gano-home-section--surface" aria-labelledby="dominios-heading">
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

    <?php
    gano_cta_registro(array(
        'heading'     => '¿Aún tienes dudas?',
        'description' => 'Nuestro equipo te acompaña en cada decisión. Registra tu cuenta y accede a soporte inmediato.',
        'button_text' => 'Crear cuenta',
    ));
    ?>

    <section class="section-gano gano-home-section gano-home-section--surface" aria-labelledby="valor-heading">
        <div class="gano-shell">
            <div class="section-title-gano">
                <h2 id="valor-heading">Cuatro pilares de la infraestructura SOTA</h2>
                <p>Lo que diferencia a Gano Digital en el mercado colombiano.</p>
            </div>
            <div class="value-section">
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
        </div>
    </section>

    <section class="section-gano gano-home-section" aria-labelledby="compromisos-heading">
        <div class="gano-shell">
            <div class="section-title-gano">
                <h2 id="compromisos-heading">Compromisos operativos</h2>
                <p>Métricas de servicio enfocadas en continuidad, respuesta y acompañamiento.</p>
            </div>
            <div class="metrics-grid">
                <article class="metric-box">
                    <div class="number">99.9%</div>
                    <div class="label">Disponibilidad esperada</div>
                </article>
                <article class="metric-box">
                    <div class="number">24/7</div>
                    <div class="label">Soporte y seguimiento</div>
                </article>
                <article class="metric-box">
                    <div class="number">&lt;2h</div>
                    <div class="label">Respuesta inicial prioritaria</div>
                </article>
                <article class="metric-box">
                    <div class="number">COP</div>
                    <div class="label">Operación comercial local</div>
                </article>
            </div>
        </div>
    </section>

    <section class="section-gano gano-home-section gano-home-section--surface" aria-labelledby="lead-heading">
        <div class="gano-shell">
            <div class="gano-panel gano-panel--compact">
                <h2 id="lead-heading">Primero: recibe la guía SOTA de crecimiento digital</h2>
                <p>Buenas prácticas de infraestructura, seguridad y conversión para equipos que necesitan resultados sostenibles.</p>
                <form id="gano-lead-magnet" class="gano-lead-form" novalidate>
                    <label for="gano-lead-email" class="screen-reader-text">Correo corporativo</label>
                    <input
                        id="gano-lead-email"
                        type="email"
                        name="email"
                        placeholder="tu@empresa.com"
                        autocomplete="email"
                        required
                    />
                    <input type="hidden" name="nonce" value="<?php echo esc_attr( gano_lead_capture_nonce() ); ?>" />
                    <input type="hidden" name="plan" value="homepage-sota" />
                    <button type="submit" class="btn-gano">Recibir guía gratis</button>
                    <p class="gano-form-status" data-gano-form-status aria-live="polite"></p>
                </form>
                <small>Sin spam. Puedes cancelar en cualquier momento.</small>
            </div>
        </div>
    </section>

    <section class="section-gano gano-home-section" id="cta-final" aria-labelledby="cierre-heading">
        <div class="gano-shell">
            <div class="gano-panel gano-panel--compact">
                <h2 id="cierre-heading">¿Listo para una infraestructura que no te pida disculpas?</h2>
                <?php echo do_shortcode( '[gano_cta_icons]' ); ?>
                <p class="gano-panel-cta">
                    <a href="#ecosistemas" class="btn-gano">Elegir mi arquitectura</a>
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
