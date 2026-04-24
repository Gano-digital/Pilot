<?php
/**
 * Componentes Premium — Merged UI/UX Pro Max + Frontend Design Skills
 *
 * Shortcodes mejorados que combinan:
 * - Trust & Authority pattern (UI/UX Pro Max)
 * - Dark Tech Authority aesthetic (Frontend Design)
 * - Social Proof elements (testimonios, métricas, badges)
 *
 * Uso:
 *   [gano_planes_premium]
 *   [gano_trust_section_premium]
 *   [gano_hero_premium]
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// Shortcode: [gano_planes_premium]
// Pattern: Trust & Authority + Social Proof (UI/UX Pro Max)
// Aesthetic: Dark Tech Authority (Frontend Design)
// =============================================================================

add_shortcode( 'gano_planes_premium', 'gano_render_planes_premium' );

/**
 * Renderiza los 4 ecosistemas/planes con diseño premium Trust & Authority.
 *
 * @return string HTML del grid de planes.
 */
function gano_render_planes_premium(): string {
    $planes = array(
        array(
            'nombre'     => 'Núcleo Prime',
            'promesa'    => 'Infraestructura de entrada con velocidad real',
            'precio'     => '196.000',
            'featured'   => false,
            'cta_text'   => 'Elegir Núcleo Prime',
            'cta_url'    => '/ecosistemas#nucleo-prime',
            'features'   => array(
                'NVMe SSD de última generación',
                'Almacenamiento seguro y backups diarios',
                'Soporte en español 24/7',
                'SSL incluido',
                'WordPress preinstalado',
            ),
        ),
        array(
            'nombre'     => 'Fortaleza Delta',
            'promesa'    => 'Rendimiento administrado con hardening activo',
            'precio'     => '450.000',
            'featured'   => true,
            'badge'      => 'MÁS ELEGIDO',
            'cta_text'   => 'Activar Fortaleza Delta',
            'cta_url'    => '/ecosistemas#fortaleza-delta',
            'features'   => array(
                'Todo de Núcleo Prime +',
                'Mayor capacidad de cómputo',
                'Hardening incluido (CSP, WAF)',
                'Monitoreo proactivo',
                'Backups cada 6 horas',
            ),
        ),
        array(
            'nombre'     => 'Bastión SOTA',
            'promesa'    => 'Rendimiento crítico para operaciones exigentes',
            'precio'     => '890.000',
            'featured'   => false,
            'cta_text'   => 'Solicitar Bastión SOTA',
            'cta_url'    => '/ecosistemas#bastion-sota',
            'features'   => array(
                'Todo de Fortaleza Delta +',
                'Recursos dedicados/aislados',
                'SLA 99,9% de disponibilidad',
                'Soporte prioritario',
                'Análisis de seguridad cada 72h',
            ),
        ),
        array(
            'nombre'     => 'Ultimate WP',
            'promesa'    => 'Máxima capacidad para operaciones de alto volumen',
            'precio'     => '1.200.000',
            'featured'   => false,
            'cta_text'   => 'Cotizar Ultimate WP',
            'cta_url'    => '/contacto',
            'features'   => array(
                'Todo de Bastión SOTA +',
                'Máxima capacidad de escala',
                'Blindaje contra picos masivos',
                'Arquitecto dedicado 3 horas/mes',
                'Auditoría de seguridad trimestral',
            ),
        ),
    );

    ob_start();
    ?>
    <section class="gano-ecosistemas-premium">
        <div class="gano-ecosistemas-header">
            <h2 class="gano-ecosistemas-title">Ecosistemas WordPress que escalan con tu negocio</h2>
            <p class="gano-ecosistemas-subtitle">
                Cuatro arquitecturas diseñadas para cada etapa de crecimiento.
                Infraestructura seria, soporte en español, facturación en COP.
            </p>
        </div>

        <div class="gano-planes-grid">
            <?php foreach ( $planes as $plan ) : ?>
                <div class="gano-plan-card <?php echo $plan['featured'] ? 'featured' : ''; ?>">
                    <?php if ( isset( $plan['badge'] ) ) : ?>
                        <span class="gano-plan-badge"><?php echo esc_html( $plan['badge'] ); ?></span>
                    <?php endif; ?>

                    <h3 class="gano-plan-name"><?php echo esc_html( $plan['nombre'] ); ?></h3>
                    <p class="gano-plan-promise"><?php echo esc_html( $plan['promesa'] ); ?></p>

                    <div class="gano-plan-divider"></div>

                    <div class="gano-plan-pricing">
                        <div class="gano-plan-price">$<?php echo number_format( (int) $plan['precio'], 0, '', '.' ); ?></div>
                        <div class="gano-plan-price-period">COP / mes</div>
                    </div>

                    <div class="gano-plan-divider"></div>

                    <ul class="gano-plan-features">
                        <?php foreach ( $plan['features'] as $feature ) : ?>
                            <li><?php echo esc_html( $feature ); ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <a href="<?php echo esc_url( $plan['cta_url'] ); ?>" class="gano-plan-cta">
                        <?php echo esc_html( $plan['cta_text'] ); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

// =============================================================================
// Shortcode: [gano_trust_section_premium]
// Pattern: Trust & Authority + Social Proof
// Elementos: Badges, Metrics, Testimonios implícitos
// =============================================================================

add_shortcode( 'gano_trust_section_premium', 'gano_render_trust_premium' );

/**
 * Renderiza sección de confianza con Trust & Authority pattern + Social Proof.
 *
 * @return string HTML de la sección de confianza.
 */
function gano_render_trust_premium(): string {
    $trust_items = array(
        array(
            'icon'        => '🛡️',
            'label'       => 'Seguridad',
            'metric'      => '24/7',
            'description' => 'Monitoreo proactivo y respuesta inmediata a amenazas',
        ),
        array(
            'icon'        => '⚡',
            'label'       => 'Velocidad',
            'metric'      => 'NVMe',
            'description' => 'Almacenamiento de última generación con Core Web Vitals optimizados',
        ),
        array(
            'icon'        => '🌍',
            'label'       => 'Global',
            'metric'      => '99,9%',
            'description' => 'Disponibilidad de nivel empresarial en infraestructura GoDaddy',
        ),
        array(
            'icon'        => '🇨🇴',
            'label'       => 'Local',
            'metric'      => 'COP',
            'description' => 'Facturación en pesos colombianos, soporte en español',
        ),
    );

    $badges = array(
        array( 'icon' => '🔐', 'text' => 'GoDaddy Reseller' ),
        array( 'icon' => '🛡️', 'text' => 'Wordfence Certified' ),
        array( 'icon' => '✓', 'text' => 'WCAG 2.2 AA' ),
        array( 'icon' => '🚀', 'text' => 'Core Web Vitals' ),
    );

    ob_start();
    ?>
    <section class="gano-trust-section">
        <div class="gano-trust-header">
            <h2 class="gano-trust-title">La infraestructura que mereces</h2>
            <p class="gano-trust-subtitle">
                Hosting WordPress serio, sin rodeos. Transparencia total en modelo,
                soporte que entiende tu negocio, seguridad de nivel empresarial.
            </p>
        </div>

        <div class="gano-trust-grid">
            <?php foreach ( $trust_items as $item ) : ?>
                <div class="gano-trust-card">
                    <div class="gano-trust-icon"><?php echo $item['icon']; ?></div>
                    <div class="gano-trust-label"><?php echo esc_html( $item['label'] ); ?></div>
                    <div class="gano-trust-metric"><?php echo esc_html( $item['metric'] ); ?></div>
                    <p class="gano-trust-description"><?php echo esc_html( $item['description'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="gano-trust-badges">
            <?php foreach ( $badges as $badge ) : ?>
                <div class="gano-badge">
                    <span class="gano-badge-icon"><?php echo $badge['icon']; ?></span>
                    <span><?php echo esc_html( $badge['text'] ); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

/**
 * Versión mejorada del hero con animaciones y gradientes premium.
 * Combina copy compelling + visual impactante.
 */
add_shortcode( 'gano_hero_premium', 'gano_render_hero_premium' );

function gano_render_hero_premium(): string {
    ob_start();
    ?>
    <section class="gano-hero-premium">
        <div class="gano-hero-content">
            <h1 class="gano-hero-headline">
                Infraestructura <span class="gano-hero-accent">blindada</span> y <span class="gano-hero-accent">previsible</span>
            </h1>
            <p class="gano-hero-subheadline">
                Hosting WordPress de alto rendimiento con seguridad de nivel empresarial,
                soporte en español y operación en pesos colombianos. Menos ruido, más continuidad.
            </p>
            <div class="gano-hero-cta-group">
                <a href="<?php echo esc_url( home_url( '/ecosistemas' ) ); ?>" class="gano-btn-hero-primary">
                    Ver arquitecturas y planes
                </a>
                <a href="<?php echo esc_url( home_url( '/contacto' ) ); ?>" class="gano-btn-hero-secondary">
                    Hablar con el equipo
                </a>
            </div>
        </div>

        <div class="gano-hero-visual">
            <div class="gano-hero-graphic">
                <!-- Placeholder para imagen/gráfico del servidor/infraestructura -->
                <svg viewBox="0 0 200 200" style="width: 200px; height: 200px; opacity: 0.8;">
                    <circle cx="100" cy="100" r="80" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="2"/>
                    <circle cx="100" cy="100" r="60" fill="none" stroke="rgba(27,79,216,0.2)" stroke-width="2"/>
                    <circle cx="100" cy="100" r="40" fill="none" stroke="rgba(76, 215, 246,0.3)" stroke-width="2"/>
                    <circle cx="100" cy="100" r="20" fill="rgba(27,79,216,0.5)"/>
                </svg>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
