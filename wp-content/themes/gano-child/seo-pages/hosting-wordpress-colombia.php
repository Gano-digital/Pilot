<?php
/**
 * SEO Page Content: "Hosting WordPress Colombia"
 * Keyword objetivo: hosting wordpress colombia (Vol. estimado: 1.600 búsq/mes en Colombia)
 *
 * Instrucciones de despliegue:
 * ──────────────────────────────────────────────────────────────────────────────
 * 1. En wp-admin → Páginas → Añadir nueva
 * 2. Título: "Hosting WordPress Colombia — Gano Digital"
 *    Slug: hosting-wordpress-colombia  (URL: gano.digital/hosting-wordpress-colombia)
 * 3. Plantilla: "SEO Landing Page — Gano Digital"
 * 4. Agregar los campos personalizados (usar plugin "Advanced Custom Fields" o "Custom Fields"):
 *    - seo_keyword_target = "hosting wordpress colombia"
 *    - seo_h1_override    = "Hosting WordPress en Colombia con Seguridad Empresarial"
 *    - seo_cta_text       = "Ver planes desde $196.000 COP/mes"
 *    - seo_cta_url        = https://gano.digital/ecosistemas
 * 5. Copiar el bloque HTML de abajo en el editor de WordPress (modo HTML/Código)
 * 6. En Rank Math → Editar página:
 *    - Focus Keyword: hosting wordpress colombia
 *    - SEO Title: Hosting WordPress Colombia | Seguridad + Soporte 24/7 | Gano Digital
 *    - Meta Description: Hosting WordPress en Colombia desde $196.000 COP/mes. NVMe Gen4, SSL gratuito, Wordfence, soporte 24/7 en español y pago por PSE, Nequi o tarjeta. Migración incluida.
 * ──────────────────────────────────────────────────────────────────────────────
 *
 * Fase 3 — SEO y Performance — Gano Digital, Marzo 2026
 */

/**
 * CONTENIDO HTML PARA COPIAR EN WORDPRESS:
 * (Copiar todo el bloque HTML de abajo en el editor de bloques → Bloque HTML personalizado)
 */

$landing_content_html = <<<'HTML'
<!-- wp:html -->
<div class="gano-landing-body">

    <!-- SECCIÓN 1: Intro SEO — qué es y por qué Colombia -->
    <section class="gano-landing-section">
        <h2>El hosting WordPress más rápido y seguro en Colombia</h2>
        <p>
            Elegir el <strong>hosting WordPress en Colombia</strong> correcto es la decisión técnica más importante
            para tu negocio digital. Un mal servidor cuesta ventas, posicionamiento y credibilidad.
        </p>
        <p>
            En <strong>Gano Digital</strong> ofrecemos infraestructura WordPress de alto rendimiento desde Bogotá,
            con soporte en español, facturación en COP y sin intermediarios internacionales.
            Cada plan incluye configuración optimizada para el mercado colombiano: pasarela Wompi (PSE, Nequi, Daviplata),
            cumplimiento de la Ley 1581 y certificado SSL gratuito.
        </p>
    </section>

    <!-- SECCIÓN 2: Diferenciadores técnicos -->
    <section class="gano-landing-section gano-bg-light">
        <h2>¿Qué hace diferente nuestro hosting WordPress?</h2>

        <div class="gano-features-grid">
            <div class="gano-feature-item">
                <h3>⚡ NVMe Gen4 — 4× más rápido que SSD</h3>
                <p>
                    Almacenamiento NVMe de cuarta generación para tiempos de carga por debajo de 1.5 segundos.
                    Core Web Vitals (LCP, FID, CLS) optimizados desde el servidor.
                </p>
            </div>

            <div class="gano-feature-item">
                <h3>🛡️ Seguridad empresarial incluida</h3>
                <p>
                    WAF activo, Wordfence configurado, MU Plugin de hardening WordPress,
                    2FA en wp-admin y verificación HMAC en webhooks de pago. Sin configuración adicional.
                </p>
            </div>

            <div class="gano-feature-item">
                <h3>💳 Pago 100% colombiano</h3>
                <p>
                    PSE, Nequi, Daviplata y tarjetas débito/crédito a través de <strong>Wompi Colombia</strong>.
                    Facturación electrónica DIAN disponible. Sin conversiones USD → COP.
                </p>
            </div>

            <div class="gano-feature-item">
                <h3>🤝 Soporte humano 24/7 en español</h3>
                <p>
                    Equipo en Colombia. Sin bots, sin formularios que tardan 3 días.
                    WhatsApp, email y tickets con SLA documentado.
                </p>
            </div>

            <div class="gano-feature-item">
                <h3>🚀 Migración gratuita incluida</h3>
                <p>
                    Traemos tu sitio WordPress desde cualquier proveedor sin tiempo de inactividad.
                    Incluye verificación de integridad post-migración.
                </p>
            </div>

            <div class="gano-feature-item">
                <h3>📊 Backups automáticos diarios</h3>
                <p>
                    Copias de seguridad diarias con retención de 30 días.
                    Restauración en un clic desde wp-admin.
                </p>
            </div>
        </div>
    </section>

    <!-- SECCIÓN 3: Para quién es -->
    <section class="gano-landing-section">
        <h2>¿Para quién es el hosting WordPress de Gano Digital?</h2>
        <ul class="gano-audience-list">
            <li>
                <strong>PyMEs colombianas</strong> que necesitan un sitio profesional, rápido y sin dolores de cabeza técnicos.
            </li>
            <li>
                <strong>Agencias y freelancers</strong> que gestionan múltiples sitios WordPress para clientes y necesitan un proveedor confiable con soporte real.
            </li>
            <li>
                <strong>E-commerce con WooCommerce</strong> que aceptan pagos por PSE o Nequi y necesitan una pasarela integrada y segura.
            </li>
            <li>
                <strong>Empresas que han tenido hackeos o lentitud</strong> y necesitan migrar a un entorno con seguridad de nivel empresarial.
            </li>
            <li>
                <strong>Startups SaaS</strong> que necesitan infraestructura escalable desde el primer día.
            </li>
        </ul>
    </section>

    <!-- SECCIÓN 4: Comparativa con proveedores internacionales -->
    <section class="gano-landing-section gano-bg-light">
        <h2>Gano Digital vs. otros hostings WordPress en Colombia</h2>
        <div class="gano-table-wrapper">
            <table class="gano-compare-table">
                <thead>
                    <tr>
                        <th>Característica</th>
                        <th>Gano Digital</th>
                        <th>Proveedores internacionales</th>
                        <th>Hostings locales básicos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Facturación en COP</td>
                        <td class="gano-td-yes">✅ Sí</td>
                        <td class="gano-td-no">❌ USD</td>
                        <td class="gano-td-yes">✅ Sí</td>
                    </tr>
                    <tr>
                        <td>Pago con PSE / Nequi</td>
                        <td class="gano-td-yes">✅ Wompi integrado</td>
                        <td class="gano-td-no">❌ No disponible</td>
                        <td class="gano-td-partial">⚠️ Parcial</td>
                    </tr>
                    <tr>
                        <td>Soporte en español 24/7</td>
                        <td class="gano-td-yes">✅ Agentes Colombia</td>
                        <td class="gano-td-partial">⚠️ Traducido / bots</td>
                        <td class="gano-td-partial">⚠️ Solo horario laboral</td>
                    </tr>
                    <tr>
                        <td>Seguridad empresarial</td>
                        <td class="gano-td-yes">✅ WAF + Wordfence + 2FA</td>
                        <td class="gano-td-partial">⚠️ Básica / addon de pago</td>
                        <td class="gano-td-no">❌ No incluida</td>
                    </tr>
                    <tr>
                        <td>Almacenamiento NVMe Gen4</td>
                        <td class="gano-td-yes">✅ Todos los planes</td>
                        <td class="gano-td-partial">⚠️ Solo planes premium</td>
                        <td class="gano-td-no">❌ SSD estándar</td>
                    </tr>
                    <tr>
                        <td>Cumplimiento Ley 1581 (DIAN)</td>
                        <td class="gano-td-yes">✅ Colombia-first</td>
                        <td class="gano-td-no">❌ GDPR / norma extranjera</td>
                        <td class="gano-td-partial">⚠️ Parcial</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- SECCIÓN 5: CTA final -->
    <section class="gano-landing-section gano-cta-section">
        <h2>Empieza hoy con hosting WordPress en Colombia</h2>
        <p>
            Planes desde <strong>$196.000 COP/mes</strong>. Sin contrato anual obligatorio.
            Migración gratuita incluida. Pagos por PSE, Nequi o tarjeta.
        </p>
        <div class="gano-cta-buttons">
            <a href="/ecosistemas/" class="gano-btn-primary" rel="noopener">
                Ver todos los planes →
            </a>
            <a href="https://wa.me/57TUNUMERO?text=Hola%2C%20quiero%20hosting%20WordPress%20en%20Colombia"
               class="gano-btn-whatsapp" target="_blank" rel="noopener nofollow">
                💬 Chatear por WhatsApp
            </a>
        </div>
        <p class="gano-cta-note">
            ¿Tienes preguntas? Escríbenos a <a href="mailto:hola@gano.digital">hola@gano.digital</a>
        </p>
    </section>

</div>
<!-- /wp:html -->
HTML;

// ─── Exportar el contenido como archivo de importación para WordPress ──────────
// Este archivo no se carga como plugin ni template; es una referencia de contenido.
// El contenido HTML de $landing_content_html se importa manualmente a WordPress.

// Para uso programático al crear la página via WP-CLI o plugin de setup:
function gano_create_seo_landing_page_hwc(): int|false {
    $page_title = 'Hosting WordPress Colombia — Gano Digital';
    $slug       = 'hosting-wordpress-colombia';

    // Verificar si ya existe
    $existing = get_page_by_path( $slug );
    if ( $existing ) {
        return $existing->ID;
    }

    $page_id = wp_insert_post( array(
        'post_title'    => $page_title,
        'post_name'     => $slug,
        'post_content'  => $GLOBALS['gano_landing_hwc_content'] ?? '',
        'post_status'   => 'draft', // Publicar manualmente después de revisar
        'post_type'     => 'page',
        'post_author'   => 1,
        'page_template' => 'templates/page-seo-landing.php',
        'meta_input'    => array(
            'seo_keyword_target' => 'hosting wordpress colombia',
            'seo_h1_override'    => 'Hosting WordPress en Colombia con Seguridad Empresarial',
            'seo_cta_text'       => 'Ver planes desde $196.000 COP/mes',
            'seo_cta_url'        => get_site_url() . '/ecosistemas',
            // Rank Math SEO fields
            'rank_math_focus_keyword'       => 'hosting wordpress colombia',
            'rank_math_title'               => 'Hosting WordPress Colombia | Seguridad + Soporte 24/7 | Gano Digital',
            'rank_math_description'         => 'Hosting WordPress en Colombia desde $196.000 COP/mes. NVMe Gen4, SSL gratuito, Wordfence, soporte 24/7 en español y pago por PSE, Nequi o tarjeta. Migración incluida.',
        ),
    ) );

    return is_wp_error( $page_id ) ? false : $page_id;
}

// Registrar la función para llamarse desde WP-CLI o desde un plugin de setup:
// wp eval 'require get_stylesheet_directory() . "/seo-pages/hosting-wordpress-colombia.php"; gano_create_seo_landing_page_hwc();'

// Keywords adicionales para crear páginas similares siguiendo el mismo patrón:
$seo_pages_roadmap = array(
    'vps-colombia'           => array(
        'keyword' => 'vps colombia',
        'title'   => 'VPS Colombia — Servidores Virtuales en Bogotá | Gano Digital',
        'h1'      => 'Servidores VPS en Colombia para proyectos de alto rendimiento',
        'meta'    => 'VPS Colombia desde Bogotá. Servidores virtuales con NVMe, IPv4 dedicada, panel de control y soporte 24/7 en español. Pago por PSE o Nequi.',
    ),
    'hosting-woocommerce-colombia' => array(
        'keyword' => 'hosting woocommerce colombia',
        'title'   => 'Hosting WooCommerce Colombia | PSE + Nequi integrado | Gano Digital',
        'h1'      => 'Hosting WooCommerce en Colombia con Wompi y PSE integrado',
        'meta'    => 'Hosting WooCommerce en Colombia optimizado para e-commerce. Wompi PSE, Nequi y Daviplata integrados. SSL, seguridad y soporte 24/7 desde $600.000 COP/mes.',
    ),
    'hosting-barato-colombia' => array(
        'keyword' => 'hosting barato colombia',
        'title'   => 'Hosting Barato Colombia | Desde $196.000 COP | Gano Digital',
        'h1'      => 'Hosting económico en Colombia con calidad empresarial',
        'meta'    => 'Hosting barato en Colombia desde $196.000 COP/mes sin sacrificar velocidad ni seguridad. WordPress optimizado, SSL gratuito y soporte en español.',
    ),
    'hosting-bogota'         => array(
        'keyword' => 'hosting bogota',
        'title'   => 'Hosting en Bogotá Colombia | Servidor local + Soporte 24/7 | Gano Digital',
        'h1'      => 'Hosting WordPress en Bogotá — infraestructura local, soporte real',
        'meta'    => 'Hosting en Bogotá con soporte en español 24/7, facturación en COP y pago por PSE. El proveedor de hosting WordPress más confiable en Colombia.',
    ),
);

// Los datos anteriores sirven para crear las 4 páginas SEO adicionales
// siguiendo el mismo proceso que "hosting-wordpress-colombia".
// Prioridad recomendada: vps-colombia, hosting-woocommerce-colombia, hosting-barato-colombia, hosting-bogota
