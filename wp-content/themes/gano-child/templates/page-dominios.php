<?php
/**
 * Template Name: Dominios — Registra tu Presencia
 * Description: Página de búsqueda y registro de dominios TLD con integración GoDaddy Reseller
 * SOTA aesthetic — buscador + grid de TLDs populares
 */

get_header();

// Pre-fill del parámetro ?domain=X: el rstore plugin lo procesa en JS, pero capturarlo
// en PHP permite pre-cargar el valor en el atributo data-domain para búsqueda inmediata.
$searched_domain = isset( $_GET['domain'] ) ? sanitize_text_field( wp_unslash( $_GET['domain'] ) ) : '';
?>

<main class="dominios-page">
    <!-- HERO -->
    <section class="dominios-hero">
        <div class="hero-content">
            <h1><?php esc_html_e( 'Tu Identidad Digital Comienza Aquí', 'gano-child' ); ?></h1>
            <p><?php esc_html_e( 'Busca, registra y gestiona dominios con la máxima libertad. Precios en COP, soporte en español y control total.', 'gano-child' ); ?></p>
        </div>
    </section>

    <!-- BUSCADOR DE DOMINIOS -->
    <section id="dominios-search" class="dominios-search-section">
        <h2 class="dominios-search-title"><?php esc_html_e( 'Busca tu Dominio Ideal', 'gano-child' ); ?></h2>
        <?php if ( $searched_domain ) : ?>
            <p class="dominios-search-hint"><?php printf( esc_html__( 'Resultados para: %s', 'gano-child' ), '<strong>' . esc_html( $searched_domain ) . '</strong>' ); ?></p>
        <?php endif; ?>
        <div class="search-container">
            <?php
            // Pasa el dominio buscado como atributo extra para que el widget JS lo use
            $shortcode_atts = 'page_size="5"';
            if ( $searched_domain ) {
                $shortcode_atts .= ' domain="' . esc_attr( $searched_domain ) . '"';
            }
            echo do_shortcode( '[rstore_domain_search ' . $shortcode_atts . ']' );
            ?>
        </div>
    </section>

    <!-- TLDS POPULARES -->
    <section class="tlds-section">
        <h2 class="tlds-title">Extensiones Populares</h2>
        <div class="tlds-grid">
            <?php
            $tlds = [
                [
                    'extension' => '.CO',
                    'tld'       => 'co',
                    'nombre'    => 'Colombia',
                    'desc'      => 'Presencia nacional de máxima autoridad',
                ],
                [
                    'extension' => '.COM',
                    'tld'       => 'com',
                    'nombre'    => 'Global',
                    'desc'      => 'El estándar internacional de credibilidad',
                ],
                [
                    'extension' => '.NET',
                    'tld'       => 'net',
                    'nombre'    => 'Infraestructura',
                    'desc'      => 'La red técnica de confianza',
                ],
                [
                    'extension' => '.TECH',
                    'tld'       => 'tech',
                    'nombre'    => 'Tecnología',
                    'desc'      => 'Para startups e innovadores',
                ],
                [
                    'extension' => '.BIZ',
                    'tld'       => 'biz',
                    'nombre'    => 'Negocio',
                    'desc'      => 'Enfoque operativo y profesional',
                ],
                [
                    'extension' => '.AI',
                    'tld'       => 'ai',
                    'nombre'    => 'Inteligencia Artificial',
                    'desc'      => 'La frontera de la innovación',
                ],
            ];

            foreach ($tlds as $tld) {
                ?>
                <div class="tld-card" data-tld="<?php echo esc_attr($tld['tld']); ?>">
                    <h3><?php echo esc_html($tld['extension']); ?></h3>
                    <p class="tld-price"><?php esc_html_e( 'Consultar precio vigente en el buscador', 'gano-child' ); ?></p>
                    <p class="tld-description"><?php echo esc_html($tld['desc']); ?></p>
                    <a href="#dominios-search"
                       class="tld-button"
                       data-tld="<?php echo esc_attr($tld['tld']); ?>"
                       aria-label="Registrar dominio <?php echo esc_attr($tld['extension']); ?>">
                        Registrar
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </section>

    <!-- TLD pre-fill: clic en card TLD → rellena buscador + dispara búsqueda + scroll -->
    <script>
    (function () {
        document.querySelectorAll('.tld-button[data-tld]').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                var tld   = btn.getAttribute('data-tld');
                var input = document.querySelector('.rstore-domain-search input[type="text"]');
                if ( ! input || ! tld ) { return; }

                input.value = 'midominio.' + tld;
                input.focus();

                // Intentar enviar el formulario del widget rstore para disparar la búsqueda
                var form = input.closest('form');
                if ( form ) {
                    form.dispatchEvent( new Event('submit', { bubbles: true, cancelable: true }) );
                }

                // Scroll suave al buscador
                var section = document.getElementById('dominios-search');
                if ( section ) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    })();
    </script>

    <!-- CTA FINAL -->
    <section class="dominios-cta-final">
        <div style="max-width: 700px; margin: 0 auto;">
            <h2>¿Necesitas Ayuda para Elegir?</h2>
            <p>Nuestro equipo te asesorará en la mejor estrategia de dominios para tu marca.</p>
            <a href="/contacto/" class="btn-primary">Contactar Equipo</a>
        </div>
    </section>

</main>

<?php get_footer(); ?>
