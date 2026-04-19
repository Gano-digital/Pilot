<?php
/**
 * Template Name: Gano Digital — Homepage
 * Description: Front page principal con hero parallax, ecosistemas dinámicos y propuesta de valor
 * SOTA aesthetic — no Elementor
 */

get_header();
?>

<style>
:root {
    --gano-blue: #1B4FD8;
    --gano-green: #00C26B;
    --gano-orange: #FF6B35;
    --gano-gold: #D4AF37;
    --gano-dark: #0F1923;
    --gano-darker: #05080b;
    --gano-glass: rgba(255, 255, 255, 0.03);
    --gano-glass-border: rgba(255, 255, 255, 0.08);
    --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

.gano-home * { margin: 0; padding: 0; box-sizing: border-box; }
.gano-home { background: var(--gano-darker); color: #e2e8f0; font-family: 'Inter', sans-serif; overflow-x: hidden; }

.hero-gano { height: 100vh; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; background: radial-gradient(circle at 50% 50%, #1e2530 0%, var(--gano-darker) 100%); padding-top: 60px; }
.hero-gano::before { content: ''; position: absolute; width: 200%; height: 200%; background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231B4FD8' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); animation: moveGrid 20s linear infinite; opacity: 0.3; z-index: 0; pointer-events: none; }
@keyframes moveGrid { from { transform: translateY(0); } to { transform: translateY(-60px); } }
.hero-content { position: relative; z-index: 2; max-width: 900px; text-align: center; animation: fadeInUp 1s ease-out 0.3s both; }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
.hero-gano h1 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: clamp(2.5rem, 8vw, 5rem); font-weight: 800; line-height: 1.1; margin-bottom: 20px; letter-spacing: -1px; }
.hero-gano h1 span { background: linear-gradient(135deg, var(--gano-blue), var(--gano-green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.hero-gano p { font-size: 1.2rem; color: #94a3b8; margin-bottom: 40px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.6; animation: fadeInUp 1s ease-out 0.6s both; }
.btn-gano { background: linear-gradient(135deg, var(--gano-blue), var(--gano-green)); color: #fff; padding: 18px 40px; border-radius: 50px; text-decoration: none; font-weight: 700; display: inline-block; box-shadow: 0 10px 30px rgba(27, 79, 216, 0.3); transition: var(--transition); border: none; cursor: pointer; font-size: 1rem; animation: fadeInUp 1s ease-out 0.9s both; }
.btn-gano:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0, 194, 107, 0.4); }
.section-gano { padding: 100px 5%; max-width: 1400px; margin: 0 auto; }
.section-title-gano { text-align: center; margin-bottom: 60px; }
.section-title-gano h2 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.5rem; font-weight: 800; color: #fff; margin-bottom: 10px; }
.section-title-gano p { color: #94a3b8; font-size: 1.1rem; }
.ecosistemas-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 80px; }
.ecosystem-card { background: var(--gano-glass); border: 1px solid var(--gano-glass-border); border-radius: 20px; padding: 40px; transition: var(--transition); text-align: center; position: relative; overflow: hidden; display: flex; flex-direction: column; }
.ecosystem-card::before { content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent); transition: left 0.6s; z-index: 1; }
.ecosystem-card:hover::before { left: 100%; }
.ecosystem-card:hover { transform: translateY(-10px); border-color: var(--gano-gold); background: rgba(255, 255, 255, 0.05); }
.ecosystem-card h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; margin-bottom: 15px; font-weight: 800; color: #fff; position: relative; z-index: 2; }
.ecosystem-card .price { font-size: 2rem; font-weight: 800; color: var(--gano-gold); margin-bottom: 20px; font-family: 'Plus Jakarta Sans', sans-serif; position: relative; z-index: 2; }
.ecosystem-card .price small { font-size: 0.6rem; color: #94a3b8; display: block; font-weight: 400; margin-top: 5px; }
.ecosystem-card ul { list-style: none; margin-bottom: 30px; color: #94a3b8; font-size: 0.9rem; position: relative; z-index: 2; }
.ecosystem-card ul li { margin-bottom: 10px; padding-left: 20px; position: relative; }
.ecosystem-card ul li::before { content: '→'; position: absolute; left: 0; color: var(--gano-green); font-weight: bold; }
.ecosystem-card .btn-gano { width: 100%; animation: none; margin-top: auto; position: relative; z-index: 2; }
.value-section { background: var(--gano-glass); border: 1px solid var(--gano-glass-border); border-radius: 20px; padding: 60px; margin-bottom: 80px; }
.value-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; }
.value-item h4 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.2rem; color: var(--gano-gold); margin-bottom: 10px; font-weight: 800; }
.value-item p { color: #cbd5e1; line-height: 1.6; font-size: 0.95rem; }
.metrics-section { text-align: center; }
.metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 30px; }
.metric-box { background: var(--gano-glass); border: 1px solid var(--gano-glass-border); border-radius: 15px; padding: 40px 20px; transition: var(--transition); }
.metric-box:hover { border-color: var(--gano-green); background: rgba(0, 194, 107, 0.05); }
.metric-box .number { font-size: 2.5rem; font-weight: 900; color: var(--gano-green); margin-bottom: 10px; font-family: 'Plus Jakarta Sans', sans-serif; }
.metric-box .label { color: #94a3b8; font-size: 0.9rem; }

@media (max-width: 768px) {
    .hero-gano { height: 90vh; padding-top: 40px; }
    .hero-gano h1 { font-size: 2.5rem; }
    .hero-gano p { font-size: 1rem; }
    .section-gano { padding: 60px 5%; }
    .section-title-gano h2 { font-size: 2rem; }
    .value-section { padding: 40px; }
    .ecosistemas-grid { grid-template-columns: 1fr; gap: 20px; }
    .ecosystem-card { padding: 30px; }
}
</style>

<div class="gano-home">
    <section class="hero-gano">
        <div class="hero-content">
            <h1>Tu <span>Soberanía Digital</span> Comienza Aquí</h1>
            <p>Infraestructura NVMe de alta gama, ciberseguridad SOTA y acompañamiento consultivo para escalar tu visión.</p>
            <a href="#ecosistemas" class="btn-gano">Explorar Ecosistemas</a>
        </div>
    </section>

    <section class="section-gano" style="text-align: center;">
        <div class="section-title-gano">
            <h2>Encuentra tu Dominio</h2>
            <p>Búsqueda instantánea de TLDs disponibles</p>
        </div>
        <?php echo do_shortcode('[rstore_domain_search]'); ?>
    </section>

    <section class="section-gano" id="ecosistemas">
        <div class="section-title-gano">
            <h2>Ecosistemas SOTA 2026</h2>
            <p>Elige el nivel de blindaje y potencia que tu proyecto merece</p>
        </div>
        <div class="ecosistemas-grid">
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
                <div class="ecosystem-card">
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
                </div>
                <?php
            }
            ?>
        </div>
    </section>

    <section class="section-gano">
        <div class="section-title-gano">
            <h2>¿Por qué Gano Digital?</h2>
            <p>Soberanía tecnológica para PYMEs colombianas</p>
        </div>
        <div class="value-section">
            <div class="value-grid">
                <div class="value-item">
                    <h4>🚀 Velocidad SOTA</h4>
                    <p>NVMe Gen4 con latencia cero. 7.500 MB/s vs. 20% de hosting convencional.</p>
                </div>
                <div class="value-item">
                    <h4>🔒 Blindaje Military-Grade</h4>
                    <p>WAF Capa 7, cifrado post-cuántico, DDoS immunity. Tu infraestructura protegida.</p>
                </div>
                <div class="value-item">
                    <h4>🤖 IA Consultiva</h4>
                    <p>Agente SOTA 24/7 para optimización, escalado y estrategia técnica.</p>
                </div>
                <div class="value-item">
                    <h4>🇨🇴 Nodos Bogotá</h4>
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

    <section class="section-gano" style="text-align: center;">
        <div style="background: var(--gano-glass); border: 1px solid var(--gano-glass-border); border-radius: 20px; padding: 60px; max-width: 700px; margin: 0 auto;">
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2rem; margin-bottom: 20px;">¿Listo para Escalar?</h2>
            <p style="color: #94a3b8; margin-bottom: 30px;">Contacta a nuestro equipo de especialistas para una consultoría personalizada.</p>
            <a href="/contacto/" class="btn-gano">Agendar Llamada</a>
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
