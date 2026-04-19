<?php
/**
 * Template Name: Gano Premium Shop SOTA
 *
 * Este template reemplaza los viejos bundles legacy
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
    .doodle--top-right { top: 10%; right: -5%; }
    .doodle--bottom-left { bottom: 10%; left: -5%; }

    /* HERO SECTION */
    .sota-hero { height: 90vh; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; padding-top: 60px; }
    .hero-bg-text { position: absolute; font-family: var(--gano-font-mono); font-size: 15vw; font-weight: var(--gano-fw-extrabold); color: rgba(255,255,255,0.02); white-space: nowrap; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0; }
    .hero-bg-text--engineering { color: rgba(99, 102, 241, 0.03); }

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
    .nav-item { background: none; border: none; border-bottom: 2px solid transparent; cursor: pointer; font-family: var(--gano-font-mono); font-size: var(--gano-fs-xs); font-weight: var(--gano-fw-bold); text-transform: uppercase; color: var(--gano-gray-500); transition: var(--gano-transition); padding: 10px 20px; }
    .nav-item.active, .nav-item:hover { color: var(--gano-purple); border-color: var(--gano-purple); }
    .nav-item:focus-visible { outline: 3px solid var(--gano-gold, #D4AF37); outline-offset: 3px; }

    .catalog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; transition: var(--motion-slow) var(--ease-out); }

    .product-card {
        background: var(--gano-card-sota); border: 1px solid var(--gano-border-sota); padding: 40px 30px;
        display: flex; flex-direction: column; transition: var(--motion-slow) var(--ease-spring);
        position: relative; overflow: hidden; border-radius: var(--gano-radius-md);
    }
    .product-card__badge {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(192,193,255,0.15);
        border: 1px solid rgba(192,193,255,0.45);
        color: var(--gano-white);
        font-size: 10px;
        font-weight: var(--gano-fw-bold);
        text-transform: uppercase;
        letter-spacing: var(--gano-ls-wide);
        padding: 4px 8px;
        border-radius: var(--gano-radius-pill);
        z-index: 3;
    }
    .product-card__tip {
        font-size: 11px;
        line-height: 1.45;
        color: var(--gano-gray-500);
        margin-bottom: 12px;
    }
    .product-card:hover { transform: translateY(-10px); border-color: var(--gano-purple); box-shadow: 0 20px 50px rgba(0,0,0,0.4); }
    .product-card::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 0%; background: linear-gradient(0deg, var(--gano-purple-soft), transparent); transition: var(--motion-slow); z-index: 0; }
    .product-card:hover::after { height: 100%; }

    .product-card > * { position: relative; z-index: 2; }

    .svg-container { position: relative; width: 60px; height: 60px; margin-bottom: 30px; }
    .svg-container__svg { width: 100%; height: 100%; }
    .svg-container__icon { font-size: 30px; color: var(--gano-purple); }
    .path-anim { stroke: var(--gano-purple); stroke-width: 1; fill: none; stroke-dasharray: 200; stroke-dashoffset: 200; transition: stroke-dashoffset 2s ease; }
    .product-card:hover .path-anim { stroke-dashoffset: 0; }

    .p-name { font-size: var(--gano-fs-2xl); margin-bottom: 15px; color: var(--gano-white); }
    .p-desc { font-size: var(--gano-fs-xs); opacity: 0.5; margin-bottom: 25px; min-height: 48px; }
    .p-price { font-family: var(--gano-font-mono); font-size: var(--gano-fs-2xl); color: var(--gano-white); font-weight: var(--gano-fw-extrabold); margin-top: auto; margin-bottom: 20px; }
    .p-price span { font-size: 10px; color: var(--gano-purple); text-transform: uppercase; display: block; margin-bottom: 5px; }

    .rstore-add-to-cart { width: 100%; text-align: center; padding: 12px; font-weight: var(--gano-fw-bold); background: var(--gano-indigo-soft); color: var(--gano-purple); border: 1px solid var(--gano-border-sota); cursor: pointer; transition: var(--gano-transition); font-size: var(--gano-fs-xs); text-transform: uppercase; letter-spacing: var(--gano-ls-wide); }
    .rstore-add-to-cart:hover { background: var(--gano-purple); color: var(--gano-white); }
    .rstore-add-to-cart:focus-visible { outline: 3px solid var(--gano-gold, #D4AF37); outline-offset: 3px; box-shadow: 0 0 0 5px rgba(212, 175, 55, 0.28); }
    .rstore-add-to-cart--pending { background: rgba(255,255,255,0.08); color: var(--gano-white); border-color: rgba(255,255,255,0.2); }
    .rstore-add-to-cart--coming-soon { opacity: 0.55; cursor: not-allowed; pointer-events: none; }
    .rstore-status-note { display: block; margin-top: 10px; font-size: 10px; color: var(--gano-gray-500); text-transform: uppercase; letter-spacing: var(--gano-ls-wide); }

    /* PILLARS SECTION */
    .pillars-section-bg { background: var(--gano-purple-soft); }
    .pillars-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; text-align: center; }
    .pillar-chip { background: rgba(255,255,255,0.02); border: 1px solid var(--gano-border-sota); padding: 20px; font-family: var(--gano-font-mono); font-size: var(--gano-fs-xs); text-transform: uppercase; }

    /* END: SOTA WRAPPER */
</style>
<!-- END: SHOP PREMIUM STYLES -->

<div class="sota-wrapper gano-km-shell gano-on-dark gano-catalog-shell" data-gano-catalog>
    <div class="mockup-status" id="scroll-progress"></div>
    <div class="badge-fixed" aria-hidden="true">SOTA v3.1 — RESELLER API SYNC</div>

    <!-- BACKGROUND DOODLES -->
    <div class="doodle constellation doodle--top-right"></div>
    <div class="doodle constellation doodle--bottom-left"></div>

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
        <div class="hero-bg-text hero-bg-text--engineering">ENGINEERING</div>
        <div class="monolith-wrap"><div class="monolith" id="main-monolith"></div></div>
        
        <div class="hero-content">
            <span class="accent reveal gano-km-live-badge">Omnipresencia de Datos</span>
            <h1 class="reveal gano-km-title">gano.digital<span class="gano-km-title-accent">/sota</span></h1>
            <p class="reveal gano-km-lead">El fin de la infraestructura pasiva. Ecosistemas (Núcleo Prime, Fortaleza Delta, Bastión SOTA) resilientes y soberanos para activos de alta autoridad.</p>
            <div class="reveal">
                <button type="button" class="btn-sota gano-km-btn-primary" onclick="document.getElementById('catalog').scrollIntoView({behavior: 'smooth'})">Explorar Nodos de Red</button>
            </div>
        </div>
    </section>

    <!-- PHP Rendered Catalog -->
    <?php
    $products = function_exists( 'gano_get_reseller_catalog_products' )
        ? gano_get_reseller_catalog_products()
        : array();
    $catalog_categories = function_exists( 'gano_get_reseller_catalog_categories' )
        ? gano_get_reseller_catalog_categories()
        : array();

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

            <div class="gano-catalog-mode-switch" role="group" aria-label="Modo de navegación del catálogo">
                <?php
                $catalog_modes = function_exists( 'gano_get_catalog_nav_modes' ) ? gano_get_catalog_nav_modes() : array();
                foreach ( $catalog_modes as $mode_key => $mode_meta ) :
                    ?>
                    <button type="button" class="gano-catalog-mode-btn" data-gano-mode="<?php echo esc_attr( $mode_key ); ?>" aria-pressed="false">
                        <?php echo esc_html( $mode_meta['label'] ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
            <p class="gano-catalog-mode-desc" data-gano-mode-description>
                Selecciona una vista de navegación para explorar el catálogo completo.
            </p>

            <section class="gano-catalog-guided-panel" data-gano-guided-panel aria-label="Asistente de selección">
                <ul class="gano-catalog-guided-list" data-gano-guided-list></ul>
            </section>

            <div class="catalog-nav" role="group" aria-label="Filtrar productos por categoría">
                <button type="button" class="nav-item active" data-filter="all">Todos</button>
                <?php foreach ( $catalog_categories as $cat_key => $cat_label ) : ?>
                    <button type="button" class="nav-item" data-filter="<?php echo esc_attr( $cat_key ); ?>">
                        <?php echo esc_html( $cat_label ); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="catalog-grid" id="catalog-container">
                <?php foreach($products as $p): ?>
                <?php
                $cta = function_exists( 'gano_resolver_catalog_cta' ) ? gano_resolver_catalog_cta( $p ) : array(
                    'url'    => '#',
                    'label'  => 'Próximamente',
                    'target' => '',
                    'status' => 'pending',
                );
                $status_classes = 'product-card reveal-item gano-km-card';
                if ( 'sync-missing' === ( $cta['status'] ?? '' ) ) {
                    $status_classes .= ' gano-catalog-sync-missing';
                }
                ?>
                <div class="<?php echo esc_attr( $status_classes ); ?>"
                     data-category="<?php echo esc_attr($p['cat']); ?>"
                     data-product-id="<?php echo esc_attr( sanitize_title( $p['cat'] . '-' . $p['name'] ) ); ?>"
                     data-product-name="<?php echo esc_attr( $p['name'] ); ?>"
                     data-product-price="<?php echo esc_attr( $p['price'] ); ?>">
                    <?php if ( ! empty( $p['badge'] ) ) : ?>
                        <span class="product-card__badge"><?php echo esc_html( $p['badge'] ); ?></span>
                    <?php endif; ?>
                    <?php if ( ! empty( $p['tip'] ) ) : ?>
                        <p class="product-card__tip"><?php echo esc_html( $p['tip'] ); ?></p>
                    <?php endif; ?>
                    <div class="svg-container">
                        <svg class="svg-container__svg" viewBox="0 0 100 100" aria-hidden="true" focusable="false">
                            <rect class="path-anim" x="10" y="10" width="80" height="80" rx="4" />
                            <foreignObject x="25" y="25" width="50" height="50">
                                <i class="fa-solid svg-container__icon <?php echo esc_attr($p['icon']); ?>" aria-hidden="true"></i>
                            </foreignObject>
                        </svg>
                    </div>
                    <h3 class="p-name"><?php echo esc_html($p['name']); ?></h3>
                    <p class="p-desc"><?php echo esc_html($p['desc']); ?></p>
                    <div class="p-price">
                        <span><?php echo esc_html( $p['price_context'] ?? 'Precio referencial' ); ?></span>
                        <?php echo esc_html( $p['price'] ); ?>
                    </div>

                    <a href="<?php echo esc_url( $cta['url'] ); ?>"
                       class="rstore-add-to-cart rstore-add-to-cart--<?php echo esc_attr( $cta['status'] ); ?> gano-km-btn-secondary"
                       <?php echo $cta['target']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                       aria-label="<?php echo esc_attr( $cta['label'] . ': ' . $p['name'] ); ?>"
                       <?php if ( 'coming-soon' === $cta['status'] ) : ?>tabindex="-1" aria-disabled="true"<?php endif; ?>
                    ><?php echo esc_html( $cta['label'] ); ?></a>
                    <button type="button" class="gano-catalog-compare-toggle" data-gano-compare-toggle aria-pressed="false">
                        Comparar
                    </button>
                    <small class="rstore-status-note">
                        <?php
                        if ( 'active' === $cta['status'] ) {
                            echo 'Estado comercial: activo';
                        } elseif ( 'sync-missing' === $cta['status'] ) {
                            echo 'Estado comercial: sincronizando catálogo';
                        } elseif ( 'pending' === $cta['status'] ) {
                            echo 'Estado comercial: pendiente de RCC';
                        } else {
                            echo 'Estado comercial: próximamente';
                        }
                        ?>
                    </small>
                    <?php if ( 'sync-missing' === $cta['status'] ) : ?>
                        <span class="gano-catalog-sync-note">Precio temporalmente no disponible</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

            <section class="gano-catalog-comparator" data-gano-compare hidden>
                <h3 class="gano-catalog-comparator-title">Comparador inteligente (hasta 3)</h3>
                <ul class="gano-catalog-compare-list" data-gano-compare-list></ul>
                <div class="gano-catalog-compare-grid" data-gano-compare-grid></div>
            </section>
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

    });
</script>

<?php get_footer(); ?>
