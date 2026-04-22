<?php
/**
 * Template Name: Gano Digital — Homepage
 * Description: Front page principal con hero parallax, ecosistemas dinámicos y propuesta de valor
 * SOTA aesthetic — no Elementor
 */

get_header();

$url_ecosistemas = function_exists( 'gano_resolve_page_url' )
	? gano_resolve_page_url( 'ecosistemas' )
	: home_url( '/ecosistemas/' );
$url_shop = function_exists( 'gano_resolve_page_url' )
	? gano_resolve_page_url( 'shop-premium', 'tienda', 'catalogo' )
	: home_url( '/shop-premium/' );
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
                <a href="<?php echo esc_url( $url_ecosistemas ); ?>" class="btn-holo">
                    <span class="label"><?php esc_html_e( 'Ver catálogo en Ecosistemas', 'gano-child' ); ?></span>
                    <span class="arrow">→</span>
                </a>
                <a href="<?php echo esc_url( $url_shop ); ?>" class="btn-gano btn-gano--secondary"><?php esc_html_e( 'Vista catálogo SOTA', 'gano-child' ); ?></a>
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

    <?php if ( function_exists( 'gano_render_content_atlas' ) ) : ?>
    <section class="section-gano gano-home-section gano-home-section--atlas-below-hero" aria-label="<?php esc_attr_e( 'Atlas de lectura', 'gano-child' ); ?>">
        <div class="gano-shell">
            <?php echo gano_render_content_atlas(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </section>
    <?php endif; ?>

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

    <?php
    // Teaser rStore de 4 tarjetas: borrador en inc/draft-home-rstore-catalog-home.php (filtro gano_show_draft_home_rstore_catalog).
    ?>
    <section class="section-gano gano-home-section gano-home-section--commerce-teaser" id="ecosistemas" aria-labelledby="ecosistemas-heading">
        <div class="gano-shell gano-home-commerce-teaser">
            <div class="section-title-gano">
                <h2 id="ecosistemas-heading"><?php esc_html_e( 'Catálogo comercial', 'gano-child' ); ?></h2>
                <p>
                    <?php
                    echo esc_html(
                        __( 'Compará planes con precios en COP y referencia USD*, filtros por familia y checkout seguro en la página Ecosistemas.', 'gano-child' )
                    );
                    ?>
                </p>
            </div>
            <div class="gano-home-commerce-teaser__actions">
                <a class="btn-holo" href="<?php echo esc_url( $url_ecosistemas ); ?>">
                    <span class="label"><?php esc_html_e( 'Abrir catálogo en Ecosistemas', 'gano-child' ); ?></span>
                    <span class="arrow" aria-hidden="true">→</span>
                </a>
                <a class="btn-gano btn-gano--secondary" href="<?php echo esc_url( $url_shop ); ?>"><?php esc_html_e( 'Vista SOTA (shop-premium)', 'gano-child' ); ?></a>
            </div>
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
                    <a href="<?php echo esc_url( $url_ecosistemas ); ?>" class="btn-gano"><?php esc_html_e( 'Elegir mi arquitectura', 'gano-child' ); ?></a>
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
