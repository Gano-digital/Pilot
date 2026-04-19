<?php
/**
 * Template Name: Gano Digital — Homepage
 * Description: Front page principal con hero parallax, ecosistemas dinámicos y propuesta de valor
 * SOTA aesthetic — no Elementor
 */

get_header();
?>

<div class="gano-home">
    <section class="hero-gano gano-hero-overlay" id="gano-main-content">
        <div class="hero-content">
            <p class="overline">Soberanía Digital · Nodo Bogotá</p>
            <h1>Infraestructura NVMe blindada y un agente IA que trabaja antes de que algo falle.</h1>
            <p>Hosting WordPress de alto rendimiento con seguridad de nivel empresarial, soporte en español y operación en pesos colombianos. Menos ruido, más continuidad.</p>
            <div class="hero-cta-row">
                <a href="#ecosistemas" class="btn-holo"><span class="label">Ver arquitecturas y planes</span><span class="arrow">→</span></a>
                <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>" class="btn-gano btn-gano--secondary">Hablar con el equipo</a>
            </div>
            <p class="hero-microcopy">NVMe · Monitoreo proactivo · Facturación en COP</p>
        </div>
    </section>

    <section class="section-gano" style="text-align: center;">
        <div class="section-title-gano">
            <h2>Encuentra tu Dominio</h2>
            <p>Búsqueda instantánea de TLDs disponibles</p>
        </div>
        <?php echo do_shortcode('[rstore_domain_search]'); ?>
    </section>

    <section class="section-gano gano-catalog-shell" id="ecosistemas" data-gano-catalog>
        <div class="section-title-gano">
            <h2>Ecosistemas SOTA 2026</h2>
            <p>Elige el nivel de blindaje y potencia que tu proyecto merece</p>
        </div>
        <div class="gano-catalog-mode-switch" role="group" aria-label="Modo de navegación del catálogo">
            <?php
            $home_modes = function_exists( 'gano_get_catalog_nav_modes' ) ? gano_get_catalog_nav_modes() : array();
            foreach ( $home_modes as $mode_key => $mode_meta ) :
                ?>
                <button type="button" class="gano-catalog-mode-btn" data-gano-mode="<?php echo esc_attr( $mode_key ); ?>" aria-pressed="false">
                    <?php echo esc_html( $mode_meta['label'] ); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <p class="gano-catalog-mode-desc" data-gano-mode-description>
            Navega por vista general, familias o asistente guiado según tu contexto.
        </p>
        <section class="gano-catalog-guided-panel" data-gano-guided-panel aria-label="Asistente de selección">
            <ul class="gano-catalog-guided-list" data-gano-guided-list></ul>
        </section>
        <div class="ecosistemas-grid" id="catalog-container">
            <?php
            $ecosistemas = [
                [
                    'nombre' => 'Núcleo Prime',
                    'slug' => 'wordpress-basico',
                    'precio' => '$196.000',
                    'features' => ['NVMe Gen4', 'WAF Capa 7', 'Soporte Standard']
                ],
                [
                    'nombre' => 'Fortaleza Delta',
                    'slug' => 'wordpress-deluxe',
                    'precio' => '$450.000',
                    'features' => ['50GB NVMe', 'Agente IA', 'Soporte 24/7']
                ],
                [
                    'nombre' => 'Bastión SOTA',
                    'slug' => 'wordpress-ultimate',
                    'precio' => '$890.000',
                    'features' => ['150GB NVMe', 'Dedicado', 'Seguridad Militar']
                ],
                [
                    'nombre' => 'Soberanía Total',
                    'slug' => 'cpanel-ultimate',
                    'precio' => '$2.5M+',
                    'features' => ['Recursos Ilimitados', 'Infraestructura Custom', 'Consultoría']
                ]
            ];

            foreach ($ecosistemas as $eco) {
                $product_query = new WP_Query([
                    'post_type' => 'reseller_product',
                    'name' => $eco['slug'],
                    'posts_per_page' => 1,
                ]);

                $post_id = $product_query->have_posts() ? $product_query->posts[0]->ID : null;
                wp_reset_postdata();
                ?>
                <div class="ecosystem-card"
                     data-category="wordpressadministrado"
                     data-product-id="<?php echo esc_attr( sanitize_title( $eco['slug'] ) ); ?>"
                     data-product-name="<?php echo esc_attr( $eco['nombre'] ); ?>"
                     data-product-price="<?php echo esc_attr( $eco['precio'] ); ?>">
                    <h3><?php echo esc_html($eco['nombre']); ?></h3>
                    <div class="price"><?php echo esc_html($eco['precio']); ?></div>
                    <ul>
                        <?php foreach ($eco['features'] as $feature) { ?>
                            <li><?php echo esc_html($feature); ?></li>
                        <?php } ?>
                    </ul>
                    <?php
                    if ($post_id) {
                        echo do_shortcode("[rstore_product post_id={$post_id} show_price=1 redirect=1 button_label='Elegir Plan']");
                    } else {
                        echo '<a href="/contacto/" class="btn-gano">Consultar</a>';
                    }
                    ?>
                    <button type="button" class="gano-catalog-compare-toggle" data-gano-compare-toggle aria-pressed="false">
                        Comparar
                    </button>
                </div>
                <?php
            }
            ?>
        </div>
        <section class="gano-catalog-comparator" data-gano-compare hidden>
            <h3 class="gano-catalog-comparator-title">Comparador inteligente (hasta 3)</h3>
            <ul class="gano-catalog-compare-list" data-gano-compare-list></ul>
            <div class="gano-catalog-compare-grid" data-gano-compare-grid></div>
        </section>
    </section>

    <section class="section-gano">
        <div class="section-title-gano">
            <h2>¿Por qué Gano Digital?</h2>
            <p>Soberanía tecnológica para PYMEs colombianas</p>
        </div>
        <div class="value-section">
            <div class="value-grid">
                <div class="value-item">
                    <h4>Velocidad SOTA</h4>
                    <p>NVMe Gen4 con latencia cero. 7.500 MB/s vs. 20% de hosting convencional.</p>
                </div>
                <div class="value-item">
                    <h4>Blindaje Military-Grade</h4>
                    <p>WAF Capa 7, cifrado post-cuántico, DDoS immunity. Tu infraestructura protegida.</p>
                </div>
                <div class="value-item">
                    <h4>IA Consultiva</h4>
                    <p>Agente SOTA 24/7 para optimización, escalado y estrategia técnica.</p>
                </div>
                <div class="value-item">
                    <h4>Nodos Bogota</h4>
                    <p>Infraestructura soberana. Datos en Colombia. Cumplimiento normativo asegurado.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section-gano metrics-section">
        <div class="section-title-gano">
            <h2>Nuestros Compromisos</h2>
            <p>Benchmarks SOTA garantizados</p>
        </div>
        <div class="metrics-grid">
            <div class="metric-box">
                <div class="number">99.99%</div>
                <div class="label">Uptime SLA</div>
            </div>
            <div class="metric-box">
                <div class="number">&lt;50ms</div>
                <div class="label">Latencia Bogotá</div>
            </div>
            <div class="metric-box">
                <div class="number">7.500MB/s</div>
                <div class="label">Velocidad I/O</div>
            </div>
            <div class="metric-box">
                <div class="number">24/7</div>
                <div class="label">Soporte Premium</div>
            </div>
        </div>
    </section>

    <section class="section-gano" style="text-align: center;">
        <div style="background: var(--gano-glass); border: 1px solid var(--gano-glass-border); border-radius: 20px; padding: 60px; max-width: 700px; margin: 0 auto;">
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2rem; margin-bottom: 20px;">Primero — Descarga la Guía SOTA</h2>
            <p style="color: #94a3b8; margin-bottom: 30px;">Recibe estrategias de infraestructura SOTA directo a tu correo (gratis).</p>

            <form id="gano-lead-magnet" style="display: flex; flex-direction: column; gap: 16px;">
                <input
                    type="email"
                    name="email"
                    placeholder="tu@empresa.com"
                    required
                    style="padding: 12px 16px; background: rgba(255,255,255,0.05); border: 1px solid var(--gano-glass-border); border-radius: 6px; color: #e2e8f0; font-size: 1rem;"
                />
                <input type="hidden" name="nonce" value="<?php echo esc_attr(gano_lead_capture_nonce()); ?>" />
                <input type="hidden" name="plan" value="general" />
                <button
                    type="submit"
                    style="padding: 12px 24px; background: var(--gc-primary); color: white; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                    onmouseover="this.style.background='var(--gc-secondary)';"
                    onmouseout="this.style.background='var(--gc-primary)';"
                >
                    Recibir Guía Gratis
                </button>
            </form>

            <p style="color: #94a3b8; font-size: 0.85rem; margin-top: 16px;">Sin spam. Desuscríbete en cualquier momento.</p>
        </div>
    </section>

    <section class="section-gano" style="text-align: center;" id="cta-final">
        <div style="background: var(--gano-glass); border: 1px solid var(--gano-glass-border); border-radius: 20px; padding: 60px; max-width: 700px; margin: 0 auto;">
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2rem; margin-bottom: 20px;">¿Listo para una infraestructura que no te pida disculpas?</h2>
            <?php echo do_shortcode( '[gano_cta_icons]' ); ?>
            <p style="margin-top: 2rem;">
                <a href="#ecosistemas" class="btn-gano">Elegir mi arquitectura</a>
            </p>
        </div>
    </section>

    <?php echo do_shortcode('[gano_socio_tecnologico]'); ?>
    <?php echo do_shortcode('[gano_metrics]'); ?>
</div>

<script>
document.getElementById('gano-lead-magnet')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const email = form.email.value;
    const nonce = form.nonce.value;
    const plan = form.plan.value;

    try {
        const response = await fetch('/wp-json/gano/v1/lead-capture', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, nonce, plan })
        });
        const data = await response.json();

        if (response.ok) {
            form.innerHTML = '<p style="color: var(--gc-secondary); font-weight: 600;">✓ Revisa tu correo</p>';
            // GA4 event
            gtag?.('event', 'gano_lead_capture', { email_domain: email.split('@')[1] });
        } else {
            alert('Error: ' + (data.error || 'No se pudo capturar el lead'));
        }
    } catch (err) {
        console.error('Lead capture error:', err);
        alert('Error de red. Intenta de nuevo.');
    }
});
</script>

<?php get_footer(); ?>
