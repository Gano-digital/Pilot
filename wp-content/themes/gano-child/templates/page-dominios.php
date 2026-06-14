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
    <section class="dominios-search-section">
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
                    'nombre' => 'Colombia',
                    'precio' => '$90.000',
                    'desc' => 'Presencia nacional de máxima autoridad',
                ],
                [
                    'extension' => '.COM',
                    'nombre' => 'Global',
                    'precio' => '$75.000',
                    'desc' => 'El estándar internacional de credibilidad',
                ],
                [
                    'extension' => '.NET',
                    'nombre' => 'Infraestructura',
                    'precio' => '$85.000',
                    'desc' => 'La red técnica de confianza',
                ],
                [
                    'extension' => '.TECH',
                    'nombre' => 'Tecnología',
                    'precio' => '$160.000',
                    'desc' => 'Para startups e innovadores',
                ],
                [
                    'extension' => '.BIZ',
                    'nombre' => 'Negocio',
                    'precio' => '$110.000',
                    'desc' => 'Enfoque operativo y profesional',
                ],
                [
                    'extension' => '.AI',
                    'nombre' => 'Inteligencia Artificial',
                    'precio' => '$350.000',
                    'desc' => 'La frontera de la innovación',
                ],
            ];

            foreach ($tlds as $tld) {
                ?>
                <div class="tld-card">
                    <h3><?php echo esc_html($tld['extension']); ?></h3>
                    <div class="tld-price"><?php echo esc_html($tld['precio']); ?><small>/año</small></div>
                    <p class="tld-description"><?php echo esc_html($tld['desc']); ?></p>
                    <a href="#" class="tld-button">Registrar</a>
                </div>
                <?php
            }
            ?>
        </div>
    </section>

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
