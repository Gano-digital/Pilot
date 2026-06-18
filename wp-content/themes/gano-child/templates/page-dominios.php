<?php
/**
 * Template Name: Dominios — Registra tu Presencia
 * Description: Página de búsqueda y registro de dominios TLD con integración GoDaddy Reseller
 * SOTA aesthetic — buscador + grid de TLDs populares
 */

get_header();
?>

<main class="dominios-page">
    <!-- HERO -->
    <section class="dominios-hero">
        <div class="hero-content">
            <h1>Tu Identidad Digital Comienza Aquí</h1>
            <p>Busca, registra y gestiona dominios con la máxima libertad. Precios en COP, soporte en español y control total.</p>
        </div>
    </section>

    <!-- BUSCADOR DE DOMINIOS -->
    <section id="dominios-search" class="dominios-search-section">
        <h2 class="dominios-search-title">Busca tu Dominio Ideal</h2>
        <div class="search-container">
            <?php echo do_shortcode('[rstore_domain_search page_size="5"]'); ?>
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
                    <p class="tld-price"><?php esc_html_e( 'Ver precio actual →', 'gano-child' ); ?></p>
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

    <!-- TLD pre-fill: cuando el usuario hace clic en un botón TLD, rellena el input del buscador -->
    <script>
    (function () {
        document.querySelectorAll('.tld-button[data-tld]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var tld = btn.getAttribute('data-tld');
                // El widget rstore renderiza un input[type=text] dentro de .rstore-domain-search
                var input = document.querySelector('.rstore-domain-search input[type="text"]');
                if (input && tld) {
                    input.value = 'midominio.' + tld;
                    input.focus();
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
