<?php
/**
 * Template Name: Gano Premium Shop SOTA
 *
 * Este template reemplaza los viejos bundles (Wompi)
 * por el catálogo unificado SOTA conectado a GoDaddy Reseller.
 *
 * Las constantes GANO_PFID_* y la función gano_rstore_cart_url() están
 * definidas en functions.php. Ver sección "GANO RESELLER STORE" en ese archivo
 * para instrucciones de cómo obtener los pfids reales desde el RCC.
 */

get_header();
?>

<!-- START: SHOP PREMIUM STYLES -->
<!-- GSAP enqueued via functions.php — no inline script tags needed -->
<style>
    /* START: SOTA WRAPPER — Dark template scoped to .sota-wrapper.
       Usa tokens globales de style.css. No redefinir :root aquí.
       Nuevos tokens SOTA (--gano-purple, --gano-indigo, etc.) viven en style.css.
    */

    .sota-wrapper {
        background-color: var(--gano-dark-deep);
        color: var(--gano-text-slate);
        font-family: var(--gano-font-body);
        overflow-x: hidden;
        line-height: var(--gano-lh-relaxed);
    }

    .sota-wrapper h1,
    .sota-wrapper h2,
    .sota-wrapper h3,
    .sota-wrapper h4 {
        color: var(--gano-white);
        font-family: var(--gano-font-heading);
        letter-spacing: var(--gano-ls-tight);
    }

    .sota-wrapper .accent {
        color: var(--gano-purple);
        font-weight: var(--gano-fw-extrabold);
        text-transform: uppercase;
        font-size: var(--gano-fs-xs);
        letter-spacing: var(--gano-ls-wider);
        display: block;
        margin-bottom: var(--gano-fs-sm);
    }

    .mockup-status { position: fixed; top: 0; left: 0; width: 0%; height: 2px; background: var(--gano-purple); z-index: 10000; box-shadow: 0 0 15px var(--gano-purple); }
    .badge-fixed { position: fixed; bottom: 20px; right: 20px; background: var(--gano-purple-soft); border: 1px solid var(--gano-purple); color: var(--gano-white); padding: 10px 20px; font-family: var(--gano-font-mono); font-size: 10px; z-index: 1000; backdrop-filter: blur(10px); }

    /* DECORATIVE DOODLES */
    .doodle { position: absolute; pointer-events: none; opacity: 0.15; z-index: 0; }
    .constellation { width: 400px; height: 400px; border-radius: 50%; background: radial-gradient(circle, var(--gano-indigo) 0%, transparent 70%); filter: blur(80px); }

    /* HERO SECTION */
    .sota-hero { height: 90vh; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; padding-top: 60px; }
    .hero-bg-text { position: absolute; font-family: var(--gano-font-mono); font-size: 15vw; font-weight: var(--gano-fw-extrabold); color: rgba(255,255,255,0.02); white-space: nowrap; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0; }

    .monolith-wrap { perspective: 1500px; position: absolute; width: 400px; height: 600px; opacity: 0.8; z-index: 1; }
    .monolith { width: 100%; height: 100%; background: linear-gradient(135deg, var(--gano-indigo-soft), var(--gano-purple-soft)); border: 1px solid var(--gano-border-sota); backdrop-filter: blur(40px); box-shadow: 0 50px 100px rgba(0,0,0,0.5); }

    .hero-content { text-align: center; max-width: 900px; z-index: 10; position: relative; }
    .sota-hero h1 { font-size: clamp(3rem, 7vw, 7rem); font-weight: var(--gano-fw-extrabold); line-height: 0.85; margin-bottom: 30px; text-transform: lowercase; }
    .sota-hero h1 span { color: var(--gano-purple); }
    .sota-hero p { font-size: var(--gano-fs-lg); font-weight: var(--gano-fw-normal); margin-bottom: 50px; color: var(--gano-white); opacity: 0.6; }

    .btn-sota {
        background: linear-gradient(90deg, var(--gano-blue), var(--gano-indigo));
        color: var(--gano-white); text-decoration: none; padding: 22px 60px;
        border-radius: var(--gano-radius-sm); font-weight: var(--gano-fw-extrabold);
        text-transform: uppercase; letter-spacing: var(--gano-ls-wide); font-size: var(--gano-fs-xs);
        display: inline-block; transition: var(--gano-transition); border: none; cursor: pointer;
        box-shadow: 0 10px 30px rgba(27, 79, 216, 0.3);
    }
    .btn-sota:hover { transform: translateY(-5px); box-shadow: 0 15px 40px var(--gano-purple-glow); }

    /* CATALOG SECTION */
    .sota-section { padding: 100px 5%; position: relative; z-index: 5; }
    .sota-container { max-width: 1400px; margin: 0 auto; }

    .section-header { margin-bottom: 80px; text-align: center; }
    .section-header h2 { font-size: clamp(2.5rem, 5vw, 4rem); margin-bottom: 20px; }

    .catalog-nav { display: flex; justify-content: center; gap: 30px; margin-bottom: 80px; flex-wrap: wrap; }
    .nav-item { cursor: pointer; font-family: var(--gano-font-mono); font-size: var(--gano-fs-xs); font-weight: var(--gano-fw-bold); text-transform: uppercase; color: var(--gano-gray-500); transition: var(--gano-transition); padding: 10px 20px; border-bottom: 2px solid transparent; }
    .nav-item.active, .nav-item:hover { color: var(--gano-purple); border-color: var(--gano-purple); }

    .catalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; transition: var(--motion-slow) var(--ease-out); }

    .product-card {
        background: var(--gano-card-sota); border: 1px solid var(--gano-border-sota); padding: 40px 30px;
        display: flex; flex-direction: column; transition: var(--motion-slow) var(--ease-spring);
        position: relative; overflow: hidden; border-radius: var(--gano-radius-md);
    }
    .product-card:hover { transform: translateY(-10px); border-color: var(--gano-purple); box-shadow: 0 20px 50px rgba(0,0,0,0.4); }
    .product-card::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 0%; background: linear-gradient(0deg, var(--gano-purple-soft), transparent); transition: var(--motion-slow); z-index: 0; }
    .product-card:hover::after { height: 100%; }

    .product-card > * { position: relative; z-index: 2; }

    .svg-container { position: relative; width: 60px; height: 60px; margin-bottom: 30px; }
    .path-anim { stroke: var(--gano-purple); stroke-width: 1; fill: none; stroke-dasharray: 200; stroke-dashoffset: 200; transition: stroke-dashoffset 2s ease; }
    .product-card:hover .path-anim { stroke-dashoffset: 0; }

    .p-name { font-size: var(--gano-fs-2xl); margin-bottom: 15px; color: var(--gano-white); }
    .p-desc { font-size: var(--gano-fs-xs); opacity: 0.5; margin-bottom: 25px; min-height: 48px; }
    .p-price { font-family: var(--gano-font-mono); font-size: var(--gano-fs-2xl); color: var(--gano-white); font-weight: var(--gano-fw-extrabold); margin-top: auto; margin-bottom: 20px; }
    .p-price span { font-size: 10px; color: var(--gano-purple); text-transform: uppercase; display: block; margin-bottom: 5px; }

    .rstore-add-to-cart { width: 100%; text-align: center; padding: 12px; font-weight: var(--gano-fw-bold); background: var(--gano-indigo-soft); color: var(--gano-purple); border: 1px solid var(--gano-border-sota); cursor: pointer; transition: var(--gano-transition); font-size: var(--gano-fs-xs); text-transform: uppercase; letter-spacing: var(--gano-ls-wide); }
    .rstore-add-to-cart:hover { background: var(--gano-purple); color: var(--gano-white); }
    .rstore-add-to-cart--pending { opacity: 0.4; cursor: not-allowed; }

    /* PILLARS SECTION */
    .pillars-section-bg { background: var(--gano-purple-soft); }
    .pillars-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; text-align: center; }
    .pillar-chip { background: rgba(255,255,255,0.02); border: 1px solid var(--gano-border-sota); padding: 20px; font-family: var(--gano-font-mono); font-size: var(--gano-fs-xs); text-transform: uppercase; }

    /* END: SOTA WRAPPER */
</style>
<!-- END: SHOP PREMIUM STYLES -->

<div class="sota-wrapper">
    <div class="mockup-status" id="scroll-progress"></div>
    <div class="badge-fixed">SOTA v3.1 — RESELLER API SYNC</div>

    <!-- BACKGROUND DOODLES -->
    <div class="doodle constellation" style="top: 10%; right: -5%;"></div>
    <div class="doodle constellation" style="bottom: 10%; left: -5%;"></div>

    <!-- SC CONSTELLATION OVERLAYS ─────────────────────────────── -->
    <!-- Activados sólo con btnMenu o tecla M. No intrusivos.      -->

    <!-- Overlay TL (top-left) — leyenda de estado -->
    <aside id="sc-overlay-tl"
           class="sc-overlay sc-overlay--tl"
           aria-label="Leyenda de estado SOTA"
           aria-hidden="true">
        <span class="sc-overlay__title">Estado · Gano SOTA</span>
        <dl class="sc-legend">
            <div class="sc-legend__item">
                <dt class="sc-legend__label">NVMe Gen4</dt>
                <dd class="sc-legend__value">ONLINE</dd>
            </div>
            <div class="sc-legend__item">
                <dt class="sc-legend__label">Zero-Trust</dt>
                <dd class="sc-legend__value">ACTIVO</dd>
            </div>
            <div class="sc-legend__item">
                <dt class="sc-legend__label">CDN Edge</dt>
                <dd class="sc-legend__value">OK</dd>
            </div>
            <div class="sc-legend__item">
                <dt class="sc-legend__label">Uptime</dt>
                <dd class="sc-legend__value">99.9%</dd>
            </div>
        </dl>
    </aside>

    <!-- Overlay TR (top-right) — portales de acceso rápido -->
    <aside id="sc-overlay-tr"
           class="sc-overlay sc-overlay--tr"
           aria-label="Portales de acceso rápido"
           aria-hidden="true">
        <span class="sc-overlay__title">Portales</span>
        <nav class="sc-portal" aria-label="Accesos directos SOTA">
            <a class="sc-portal__link" href="#catalog">→ Catálogo</a>
            <a class="sc-portal__link" href="#pillars-preview">→ Arquitectura</a>
            <a class="sc-portal__link" href="<?php echo esc_url( home_url( '/contacto' ) ); ?>">→ Contacto</a>
        </nav>
    </aside>

    <!-- Modal SC (visible sólo en mobile ≤ 480 px) -->
    <div id="sc-modal"
         class="sc-modal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="sc-modal-title"
         aria-hidden="true">
        <div class="sc-modal__inner">
            <header class="sc-modal__header">
                <span id="sc-modal-title" class="sc-modal__title">Panel SOTA</span>
                <button id="sc-modal-close"
                        class="sc-modal__close"
                        aria-label="Cerrar panel SOTA">✕</button>
            </header>

            <!-- Leyenda -->
            <p class="sc-modal__section-title">Estado</p>
            <dl class="sc-legend sc-legend--modal">
                <div class="sc-legend__item">
                    <dt class="sc-legend__label">NVMe Gen4</dt>
                    <dd class="sc-legend__value">ONLINE</dd>
                </div>
                <div class="sc-legend__item">
                    <dt class="sc-legend__label">CDN Edge</dt>
                    <dd class="sc-legend__value">OK</dd>
                </div>
                <div class="sc-legend__item">
                    <dt class="sc-legend__label">Uptime</dt>
                    <dd class="sc-legend__value">99.9%</dd>
                </div>
            </dl>

            <!-- Portales -->
            <p class="sc-modal__section-title">Portales</p>
            <nav class="sc-portal" aria-label="Accesos directos móvil">
                <a class="sc-portal__link" href="#catalog">→ Catálogo</a>
                <a class="sc-portal__link" href="#pillars-preview">→ Arquitectura</a>
                <a class="sc-portal__link" href="<?php echo esc_url( home_url( '/contacto' ) ); ?>">→ Contacto</a>
            </nav>
        </div>
    </div>

    <!-- Botón de activación del panel SC (btnMenu) -->
    <button id="sc-btn-menu"
            class="sc-btn-menu"
            aria-label="Abrir panel de estado SOTA"
            aria-expanded="false"
            aria-controls="sc-overlay-tl sc-overlay-tr sc-modal"
            title="Panel SC (M)">⊞</button>
    <!-- ─────────────────────────────────────────────────────────── -->

    <!-- HERO -->
    <section class="sota-hero">
        <div class="hero-bg-text" style="color: rgba(99, 102, 241, 0.03);">ENGINEERING</div>
        <div class="monolith-wrap"><div class="monolith" id="main-monolith"></div></div>
        
        <div class="hero-content">
            <span class="accent reveal">Omnipresencia de Datos</span>
            <h1 class="reveal">gano.digital<span>/</span>sota</h1>
            <p class="reveal">El fin de la infraestructura pasiva. Ecosistemas (Núcleo Prime, Fortaleza Delta, Bastión SOTA) resilientes y soberanos para activos de alta autoridad.</p>
            <div class="reveal">
                <button class="btn-sota" onclick="document.getElementById('catalog').scrollIntoView({behavior: 'smooth'})">Explorar Nodos de Red</button>
            </div>
        </div>
    </section>

    <!-- PHP Rendered Catalog -->
    <?php
    // Catálogo mapeado a constantes GANO_PFID_* (functions.php). Dominios → domain_search.
    $products = array(
        array( 'cat' => 'hosting',  'name' => 'Núcleo Prime',       'desc' => 'Soberanía inicial para marcas emergentes.',        'price' => '196.000', 'icon' => 'fa-rocket',          'pfid' => GANO_PFID_HOSTING_ECONOMIA ),
        array( 'cat' => 'hosting',  'name' => 'Fortaleza Delta',     'desc' => 'Ecosistema administrado por ingenieros.',          'price' => '450.000', 'icon' => 'fa-microchip',        'pfid' => GANO_PFID_HOSTING_DELUXE ),
        array( 'cat' => 'hosting',  'name' => 'Bastión SOTA',        'desc' => 'Velocidad crítica Gen4 para operaciones masivas.', 'price' => '890.000', 'icon' => 'fa-bolt',             'pfid' => GANO_PFID_HOSTING_PREMIUM ),
        array( 'cat' => 'hosting',  'name' => 'Ultimate WP',         'desc' => 'Inmunidad ante picos masivos de tráfico.',         'price' => '1.200.000', 'icon' => 'fa-crown',            'pfid' => GANO_PFID_HOSTING_ULTIMATE ),

        array( 'cat' => 'security', 'name' => 'SSL Deluxe',          'desc' => 'Validación corporativa de alta confianza.',        'price' => '680.000', 'icon' => 'fa-shield-halved',    'pfid' => GANO_PFID_SSL_DELUXE ),
        array( 'cat' => 'security', 'name' => 'Security Ultimate',   'desc' => 'Blindaje Zero-Trust contra DDoS.',                 'price' => '1.800.000', 'icon' => 'fa-fire-extinguisher', 'pfid' => GANO_PFID_SECURITY_ULTIMATE ),

        array( 'cat' => 'identity', 'name' => 'Dominio .CO',         'desc' => 'Presencia nacional de alta autoridad.',           'price' => '90.000',  'icon' => 'fa-globe',            'pfid' => 'domain_search' ),
        array( 'cat' => 'identity', 'name' => 'Dominio .COM',        'desc' => 'Estándar global de soberanía.',                   'price' => '75.000',  'icon' => 'fa-earth-americas',   'pfid' => 'domain_search' ),

        array( 'cat' => 'email',    'name' => 'M365 Premium',        'desc' => 'Máxima seguridad en colaboración corporativa.',   'price' => '98.000',  'icon' => 'fa-id-badge',         'pfid' => GANO_PFID_M365_PREMIUM ),
        array( 'cat' => 'email',    'name' => 'Online Storage 1TB',  'desc' => 'Archivo Maestro Soberano.',                       'price' => '120.000', 'icon' => 'fa-hard-drive',       'pfid' => GANO_PFID_ONLINE_STORAGE ),
    );

    $sotaPillars = [
        "NVMe Gen4", "Zero-Trust", "Predictive AI", "Digital Sovereignty", "Headless WP",
        "DDoS Immunity", "Containers", "Edge Computing", "Sustainable", "Quantum-Safe",
        "CI/CD Pipeline", "Real-Time Backup"
    ];
    ?>

    <!-- CATALOG -->
    <section class="sota-section" id="catalog">
        <div class="sota-container">
            <div class="section-header">
                <span class="accent">Operatividad Total GoDaddy API</span>
                <h2>Sincronización de Ecosistemas</h2>
            </div>

            <div class="catalog-nav">
                <div class="nav-item active" data-filter="all">Todos</div>
                <div class="nav-item" data-filter="hosting">Cómputo (Núcleo)</div>
                <div class="nav-item" data-filter="security">Blindaje (Zero-Trust)</div>
                <div class="nav-item" data-filter="identity">Identidad (.CO)</div>
                <div class="nav-item" data-filter="email">Colaboración</div>
            </div>

            <div class="catalog-grid" id="catalog-container">
                <?php foreach($products as $p): ?>
                <div class="product-card reveal-item" data-category="<?php echo esc_attr($p['cat']); ?>">
                    <div class="svg-container">
                        <svg viewBox="0 0 100 100" style="width:100%; height:100%;">
                            <rect class="path-anim" x="10" y="10" width="80" height="80" rx="4" />
                            <foreignObject x="25" y="25" width="50" height="50">
                                <i class="fa-solid <?php echo esc_attr($p['icon']); ?>" style="font-size:30px; color:var(--gano-purple);"></i>
                            </foreignObject>
                        </svg>
                    </div>
                    <h3 class="p-name"><?php echo esc_html($p['name']); ?></h3>
                    <p class="p-desc"><?php echo esc_html($p['desc']); ?></p>
                    <div class="p-price"><span>Inversión Mensual</span>$ <?php echo esc_html($p['price']); ?></div>

                    <?php
                    if ( 'domain_search' === $p['pfid'] ) {
                        $cta_url    = esc_url( home_url( '/dominios/' ) );
                        $cta_label  = 'Buscar Dominio';
                        $cta_target = '';
                    } elseif ( 'PENDING_RCC' === $p['pfid'] ) {
                        $cta_url    = esc_url( '#' );
                        $cta_label  = 'Próximamente';
                        $cta_target = '';
                    } else {
                        $cta_url    = gano_rstore_cart_url( $p['pfid'] );
                        $cta_label  = 'Adquirir Nodo';
                        $cta_target = 'target="_blank" rel="noopener noreferrer"';
                    }
                    ?>
                    <a href="<?php echo $cta_url; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>"
                       class="rstore-add-to-cart"
                       <?php echo $cta_target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    ><?php echo esc_html( $cta_label ); ?></a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- PILLARS -->
    <section class="sota-section pillars-section-bg">
        <div class="sota-container text-center">
            <div class="section-header">
                <span class="accent">Innovación Proactiva</span>
                <h2>Pivotes de Arquitectura</h2>
            </div>

            <div id="pillars-preview" class="pillars-grid">
                <?php foreach($sotaPillars as $pillar): ?>
                    <div class="reveal-pillar pillar-chip">
                        <?php echo esc_html($pillar); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Init GSAP
        if (typeof gsap !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            // SCROLL PROGRESS
            gsap.to("#scroll-progress", {
                width: "100%",
                scrollTrigger: { scrub: 0.1, start: "top top", end: "bottom bottom" }
            });

            // HERO MONOLITH ROTATION
            gsap.to("#main-monolith", {
                rotateY: 360,
                duration: 20,
                repeat: -1,
                ease: "none"
            });

            // REVEAL ANIMATIONS
            const trigger = { trigger: ".sota-hero", start: "top 80%", toggleActions: "play none none none" };
            gsap.from(".reveal", { opacity: 0, y: 50, duration: 1, stagger: 0.2, scrollTrigger: trigger });
            
            gsap.from(".reveal-pillar", { 
                opacity: 0, scale: 0.9, duration: 0.8, stagger: 0.1, 
                scrollTrigger: { trigger: "#pillars-preview", start: "top 80%" }
            });
        }

        // Filtering Logic
        const navItems = document.querySelectorAll('.nav-item');
        const productCards = document.querySelectorAll('.product-card');

        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                // Update active state
                navItems.forEach(nav => nav.classList.remove('active'));
                e.target.classList.add('active');

                const filter = e.target.getAttribute('data-filter');

                // Animate out
                if (typeof gsap !== 'undefined') {
                    gsap.to(productCards, {
                        opacity: 0, 
                        y: 20, 
                        duration: 0.3, 
                        onComplete: () => {
                            // Filter displays
                            productCards.forEach(card => {
                                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                                    card.style.display = 'flex';
                                } else {
                                    card.style.display = 'none';
                                }
                            });
                            // Animate in
                            gsap.to(document.querySelectorAll('.product-card[style*="display: flex"]'), {
                                opacity: 1, y: 0, duration: 0.4, stagger: 0.05
                            });
                        }
                    });
                }
            });
        });
    });
</script>

<?php get_footer(); ?>
