<?php
/**
 * Plugin Name: Gano Digital — Phase 3 Content Builder
 * Plugin URI:  https://gano.digital
 * Description: Crea páginas con contenido real, menú de navegación y configuración de Rank Math SEO.
 *              Activar una sola vez y eliminar después.
 * Version:     1.0.0
 * Author:      DevSecOps / Gano Digital
 */

if ( ! defined( 'ABSPATH' ) ) exit;

register_activation_hook( __FILE__, 'gano_p3_activate' );

function gano_p3_activate() {
    $log     = [];
    $created = 0;

    // ─── PÁGINAS PRINCIPALES ─────────────────────────────────────────────────
    $pages = gano_p3_pages();

    foreach ( $pages as $slug => $data ) {
        $existing = get_page_by_path( $slug );

        if ( $existing ) {
            // Actualizar contenido si la página ya existe
            wp_update_post( [
                'ID'           => $existing->ID,
                'post_title'   => $data['title'],
                'post_content' => $data['content'],
                'post_status'  => 'publish',
                'post_excerpt' => $data['excerpt'] ?? '',
            ] );

            // SEO meta
            if ( ! empty( $data['seo'] ) ) {
                update_post_meta( $existing->ID, 'rank_math_title',            $data['seo']['title']       ?? '' );
                update_post_meta( $existing->ID, 'rank_math_description',      $data['seo']['description'] ?? '' );
                update_post_meta( $existing->ID, 'rank_math_focus_keyword',    $data['seo']['keywords']    ?? '' );
            }
            $log[] = "♻️ Página actualizada: {$data['title']}";
        } else {
            $id = wp_insert_post( [
                'post_type'    => 'page',
                'post_title'   => $data['title'],
                'post_name'    => $slug,
                'post_content' => $data['content'],
                'post_status'  => 'publish',
                'post_excerpt' => $data['excerpt'] ?? '',
                'post_author'  => 1,
            ] );

            if ( $id && ! is_wp_error( $id ) ) {
                // SOTA templates vs Elementor Default
                $is_sota = !empty($data['category']) && $data['category'] === 'sota';
                $template = $is_sota ? 'templates/sota-single-template.php' : 'elementor_canvas';
                update_post_meta( $id, '_wp_page_template', $template );

                // SEO
                if ( ! empty( $data['seo'] ) ) {
                    update_post_meta( $id, 'rank_math_title',            $data['seo']['title']       ?? '' );
                    update_post_meta( $id, 'rank_math_description',      $data['seo']['description'] ?? '' );
                    update_post_meta( $id, 'rank_math_focus_keyword',    $data['seo']['keywords']    ?? '' );
                }
                $created++;
            } else {
                $log[] = "❌ Error creando página: {$data['title']}";
            }
        }
    }

    $log[] = "✅ {$created} páginas nuevas creadas.";

    // ─── MENÚ DE NAVEGACIÓN PRINCIPAL ────────────────────────────────────────
    $menu_name = 'Menú Principal';
    $menu_id   = wp_create_nav_menu( $menu_name );

    if ( ! is_wp_error( $menu_id ) ) {
        $menu_pages = [
            'inicio'    => 'Inicio',
            'nosotros'  => 'Nosotros',
            'dominios'  => 'Dominios',
            'hosting'   => 'Hosting',
            'servicios' => 'Servicios',
            'blog'      => 'Blog',
            'contacto'  => 'Contacto',
        ];

        $order = 1;
        foreach ( $menu_pages as $slug => $label ) {
            $page = get_page_by_path( $slug );
            if ( $page ) {
                wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-title'     => $label,
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => $order++,
                ] );
            }
        }

        // Parent Menu: Innovación SOTA
        $sota_parent_id = wp_update_nav_menu_item( $menu_id, 0, [
            'menu-item-title'    => 'Innovación SOTA',
            'menu-item-url'      => '#',
            'menu-item-status'   => 'publish',
            'menu-item-position' => $order++,
        ] );

        $sota_items = [
            'seguridad-zero-trust'      => 'Zero-Trust',
            'almacenamiento-nvme'       => 'NVMe Gen4',
            'soberania-digital'         => 'Soberanía',
            'arquitectura-cloud'        => 'Cloud SOTA',
            'catalogo-sota'             => 'Catálogo',
        ];

        foreach ( $sota_items as $slug => $label ) {
            $page = get_page_by_path( $slug );
            if ( $page ) {
                wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-parent-id' => $sota_parent_id,
                    'menu-item-title'     => $label,
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page->ID,
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => $order++,
                ] );
            }
        }

        // Asignar menú a la ubicación del tema
        $locations = get_theme_mod( 'nav_menu_locations', [] );
        $locations['main'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );

        $log[] = '✅ Menú de navegación principal creado.';
    } else {
        $log[] = 'ℹ️ El menú ya existe o hubo un error al crearlo.';
    }

    // ─── PÁGINAS LEGALES ──────────────────────────────────────────────────────
    $legal_pages = [
        'terminos-y-condiciones' => [
            'title'   => 'Términos y Condiciones',
            'content' => gano_p3_terms_content(),
        ],
        'politica-de-privacidad' => [
            'title'   => 'Política de Privacidad',
            'content' => gano_p3_privacy_content(),
        ],
        'politica-de-cookies' => [
            'title'   => 'Política de Cookies',
            'content' => '<p>Esta política de cookies será completada antes del lanzamiento del sitio.</p>',
        ],
    ];

    foreach ( $legal_pages as $slug => $data ) {
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post( [
                'post_type'    => 'page',
                'post_title'   => $data['title'],
                'post_name'    => $slug,
                'post_content' => $data['content'],
                'post_status'  => 'publish',
                'post_author'  => 1,
            ] );
        }
    }
    $log[] = '✅ Páginas legales creadas.';

    // ─── POSTS DE BLOG INICIALES ──────────────────────────────────────────────
    $posts_created = gano_p3_create_blog_posts();
    $log[] = "✅ {$posts_created} artículos de blog de ejemplo creados.";

    // ─── OPCIONES DE RANK MATH ────────────────────────────────────────────────
    update_option( 'rank_math_schema_type',        'Organization' );
    update_option( 'rank_math_local_business_type','ProfessionalService' );
    update_option( 'rank_math_company_name',       'Gano Digital SAS' );
    update_option( 'rank_math_company_url',        home_url() );
    $log[] = '✅ Rank Math SEO configurado (Organization Schema).';

    update_option( 'gano_p3_log',    $log );
    update_option( 'gano_p3_ran_at', current_time( 'mysql' ) );
}

// ─── CONTENIDO DE LAS PÁGINAS ─────────────────────────────────────────────────
function gano_p3_pages() {
    return [

        'inicio' => [
            'title'   => 'Inicio',
            'excerpt' => 'Servicios digitales para hacer crecer tu negocio en Colombia.',
            'content' => '<!-- Página diseñada con Elementor. El contenido se edita visualmente. -->',
            'seo'     => [
                'title'       => 'Gano Digital | Hosting, Dominios y Servicios Web en Colombia',
                'description' => 'Lleva tu negocio al mundo digital con hosting, dominios, SSL, email profesional y SEO. Soluciones digitales para empresas colombianas.',
                'keywords'    => 'hosting colombia, registro dominio colombia, web hosting bogota',
            ],
        ],

        'nosotros' => [
            'title'   => 'Nosotros',
            'excerpt' => 'Somos Gano Digital, tu aliado en transformación digital.',
            'content' => gano_p3_about_content(),
            'seo'     => [
                'title'       => 'Quiénes Somos | Gano Digital — Servicios Digitales en Colombia',
                'description' => 'Conoce el equipo detrás de Gano Digital. Ayudamos a empresas colombianas a crecer con soluciones digitales profesionales.',
                'keywords'    => 'gano digital, agencia digital colombia, servicios web bogota',
            ],
        ],

        'dominios' => [
            'title'   => 'Registro de Dominios',
            'excerpt' => 'Registra el nombre de dominio perfecto para tu negocio.',
            'content' => gano_p3_domains_content(),
            'seo'     => [
                'title'       => 'Registro de Dominios Colombia | Gano Digital',
                'description' => 'Registra tu dominio .com, .co, .net desde $35.000 COP/año. Gestión fácil, renovación automática y protección de privacidad.',
                'keywords'    => 'registro dominio colombia, comprar dominio .co, dominio .com precio',
            ],
        ],

        'hosting' => [
            'title'   => 'Planes de Hosting',
            'excerpt' => 'Alojamiento web rápido, seguro y con soporte 24/7.',
            'content' => gano_p3_hosting_content(),
            'seo'     => [
                'title'       => 'Hosting Web Colombia | Planes desde $15.000/mes | Gano Digital',
                'description' => 'Hosting cPanel, WordPress y VPS para tu sitio web. Servidores en Colombia, uptime 99.9%, soporte en español. Desde $15.000 COP/mes.',
                'keywords'    => 'hosting colombia, hospedaje web colombia, hosting cpanel bogota',
            ],
        ],

        'servicios' => [
            'title'   => 'Servicios',
            'excerpt' => 'Todos los servicios digitales que tu empresa necesita.',
            'content' => gano_p3_services_content(),
            'seo'     => [
                'title'       => 'Servicios Digitales | SSL, SEO, Email, Seguridad | Gano Digital',
                'description' => 'Certificados SSL, SEO, email marketing, seguridad web y backups. Protege y potencia tu presencia en internet con Gano Digital.',
                'keywords'    => 'servicios digitales colombia, ssl colombia, seo colombia',
            ],
        ],

        'contacto' => [
            'title'   => 'Contacto',
            'excerpt' => 'Estamos en Bogotá para ayudarte.',
            'content' => gano_p3_contact_content(),
            'seo'     => [
                'title'       => 'Contáctanos | Gano Digital — Bogotá, Colombia',
                'description' => 'Escríbenos a hola@gano.digital o visítanos en Bogotá. Soporte técnico en español, respuesta en menos de 24 horas.',
                'keywords'    => 'contacto gano digital, soporte hosting colombia, ayuda dominio colombia',
            ],
        ],

        'soporte' => [
            'title'   => 'Soporte',
            'excerpt' => 'Base de conocimiento y soporte técnico en español.',
            'content' => gano_p3_support_content(),
            'seo'     => [
                'title'       => 'Soporte Técnico | Centro de Ayuda | Gano Digital',
                'description' => 'Encuentra respuestas rápidas a preguntas frecuentes sobre hosting, dominios, SSL y más. Soporte en español 24/7.',
                'keywords'    => 'soporte hosting, ayuda cpanel, configurar ssl dominio',
            ],
        ],

        'blog' => [
            'title'   => 'Blog',
            'excerpt' => 'Artículos y guías sobre presencia digital para tu empresa.',
            'content' => '<!-- Listado de artículos generado automáticamente por WordPress. -->',
            'seo'     => [
                'title'       => 'Blog | Recursos Digitales para tu Negocio | Gano Digital',
                'description' => 'Aprende sobre hosting, dominios, seguridad web y estrategias digitales. Guías y tutoriales para empresas colombianas.',
                'keywords'    => 'blog hosting colombia, guias wordpress, tips seo colombia',
            ],
        ],

        /* ─── NÚCLEO SOTA (Arquitectura e Innovación) ────────────────────────── */

        'seguridad-zero-trust' => [
            'title'    => 'Seguridad Zero-Trust',
            'excerpt'  => 'Infrastructura blindada con el modelo de confianza cero.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Seguridad Zero-Trust | SOTA Gano Digital', 'description' => 'Seguridad perimetral y autenticación continua.', 'keywords' => 'zero trust colombia' ],
        ],

        'almacenamiento-nvme' => [
            'title'    => 'Power NVMe Gen4',
            'excerpt'  => 'Velocidad de lectura/escritura sin precedentes.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'NVMe Gen4 | Rendimiento SOTA', 'description' => 'I/O ultra rápido para aplicaciones críticas.', 'keywords' => 'nvme hosting colombia' ],
        ],

        'soberania-digital' => [
            'title'    => 'Soberanía Digital',
            'excerpt'  => 'Control total sobre tus datos e infraestructura.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Soberanía Digital | Independencia Tecnológica', 'description' => 'Tus datos, tus reglas.', 'keywords' => 'soberania digital colombia' ],
        ],

        'inteligencia-sintetica' => [
            'title'    => 'Inteligencia Sintética',
            'excerpt'  => 'Redes neuronales aplicadas al análisis de infraestructura.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'IA Infraestructura | Gano Digital', 'description' => 'Optimización predictiva mediante IA.', 'keywords' => 'ia hosting colombia' ],
        ],

        'red-global-anycast' => [
            'title'    => 'Red Global Anycast',
            'excerpt'  => 'Latencia mínima distribuida en nodos mundiales.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Anycast Network | Distribución SOTA', 'description' => 'Conexión instantánea desde cualquier lugar.', 'keywords' => 'anycast cdn colombia' ],
        ],

        'computacion-serverless' => [
            'title'    => 'Computación Serverless',
            'excerpt'  => 'Escalabilidad infinita sin gestionar servidores.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Serverless Hosting | Escalabilidad SOTA', 'description' => 'Despliega funciones sin preocuparte por el hardware.', 'keywords' => 'serverless colombia' ],
        ],

        'ecosistemas-hibridos' => [
            'title'    => 'Ecosistemas Híbridos',
            'excerpt'  => 'Lo mejor del cloud privado y público en una malla única.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Hybrid Cloud SOTA | Gano Digital', 'description' => 'Flexibilidad y seguridad en entornos mixtos.', 'keywords' => 'nube hibrida colombia' ],
        ],

        'edge-computing-pro' => [
            'title'    => 'Edge Computing Pro',
            'excerpt'  => 'Procesamiento en el borde para tiempos de respuesta ultra-bajos.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Edge Computing | SOTA Infraestructura', 'description' => 'Velocidad local en escala global.', 'keywords' => 'edge computing colombia' ],
        ],

        'ciber-resiliencia-fractal' => [
            'title'    => 'Ciber-Resiliencia Fractal',
            'excerpt'  => 'Recuperación ante desastres con redundancia multi-capa.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Resiliencia Digital | Gano Digital', 'description' => 'Continuidad de negocio contra cualquier amenaza.', 'keywords' => 'disaster recovery colombia' ],
        ],

        'catalogo-sota' => [
            'title'    => 'Catálogo de Innovación',
            'excerpt'  => 'Explora la frontera tecnológica de Gano Digital.',
            'content'  => '<!-- SOTA Catalog -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Catálogo SOTA | Gano Digital', 'description' => 'Servicios de vanguardia digital.', 'keywords' => 'tecnologia sota' ],
        ],

        'dashboard-infraestructura' => [
            'title'    => 'Dashboard Inteligente',
            'excerpt'  => 'Visualización en tiempo real de tu ecosistema.',
            'content'  => '<!-- SOTA Dashboard -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Dashboard SOTA | Gestión Total', 'description' => 'Métrica y control de red en vivo.', 'keywords' => 'panel hosting colombia' ],
        ],

        'arquitectura-cloud' => [
            'title'    => 'Arquitectura Cloud',
            'excerpt'  => 'Diseño de infraestructuras elásticas y resilientes.',
            'content'  => '<!-- SOTA Cloud Architecture -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Cloud SOTA | Gano Digital', 'description' => 'Nube de alto rendimiento.', 'keywords' => 'cloud server colombia' ],
        ],
    ];
}

// ─── CONTENIDO: NOSOTROS ──────────────────────────────────────────────────────
function gano_p3_about_content() {
    return <<<HTML
<!-- wp:group {"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group">

<!-- wp:heading {"level":1} -->
<h1>Somos Gano Digital</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Somos una empresa colombiana de servicios digitales con sede en Bogotá. Nuestra misión es hacer que cualquier negocio o persona pueda construir y hacer crecer su presencia en internet, con herramientas profesionales y soporte en español.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>¿Por qué Gano Digital?</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
  <li><strong>Soporte local en español</strong> — Resolvemos tus dudas sin barreras de idioma ni de horario.</li>
  <li><strong>Precios en pesos colombianos (COP)</strong> — Sin sorpresas con la TRM. Pagas exactamente lo que ves.</li>
  <li><strong>Tecnología de nivel global</strong> — Infraestructura respaldada por los mejores centros de datos.</li>
  <li><strong>Soluciones integrales</strong> — De la idea a la tienda online: dominio, hosting, diseño, seguridad y marketing.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Nuestra historia</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Gano Digital nació en Bogotá con una convicción simple: las pequeñas y medianas empresas colombianas merecen las mismas herramientas digitales que las grandes corporaciones, pero con precios justos y soporte que entiende su realidad. Desde nuestros inicios hemos acompañado a emprendedores, comercios locales y agencias en la construcción de su huella digital.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Nuestro compromiso</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Trabajamos con los estándares más exigentes de seguridad y disponibilidad. Nuestros servidores garantizan un uptime del <strong>99.9%</strong>, y nuestro equipo de soporte está disponible para acompañarte en cada paso.</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
HTML;
}

// ─── CONTENIDO: DOMINIOS ──────────────────────────────────────────────────────
function gano_p3_domains_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Registra tu dominio</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Tu dominio es la dirección de tu negocio en internet. Elige el nombre perfecto y regístralo hoy.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Extensiones disponibles</h2>
<!-- /wp:heading -->

<!-- wp:table -->
<figure class="wp-block-table"><table>
  <thead><tr><th>Extensión</th><th>¿Para quién?</th><th>Precio/año</th></tr></thead>
  <tbody>
    <tr><td>.com</td><td>Negocios globales — la extensión más reconocida</td><td>$85.000 COP</td></tr>
    <tr><td>.co</td><td>Empresas colombianas — autoridad local</td><td>$95.000 COP</td></tr>
    <tr><td>.net</td><td>Tecnología y redes</td><td>$85.000 COP</td></tr>
    <tr><td>.org</td><td>ONGs y organizaciones sin ánimo de lucro</td><td>$75.000 COP</td></tr>
    <tr><td>.store</td><td>Tiendas en línea</td><td>$90.000 COP</td></tr>
    <tr><td>.digital</td><td>Agencias y negocios digitales</td><td>$110.000 COP</td></tr>
  </tbody>
</table></figure>
<!-- /wp:table -->

<!-- wp:heading {"level":2} -->
<h2>¿Qué incluye tu dominio?</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
  <li>Panel de control intuitivo para gestionar DNS, redirecciones y más.</li>
  <li>Bloqueo de dominio contra transferencias no autorizadas.</li>
  <li>Renovación automática para que nunca pierdas tu dirección.</li>
  <li>Opción de protección de privacidad WHOIS.</li>
</ul>
<!-- /wp:list -->
HTML;
}

// ─── CONTENIDO: HOSTING ───────────────────────────────────────────────────────
function gano_p3_hosting_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Planes de Hosting Web</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Elige el plan que se adapta al tamaño y las necesidades de tu proyecto digital.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Hosting cPanel (Compartido)</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ideal para sitios web personales, blogs y pequeñas empresas que están comenzando su presencia en línea.</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} -->
    <h3>Inicial</h3>
    <!-- /wp:heading -->
    <!-- wp:list -->
    <ul>
      <li>1 sitio web</li>
      <li>10 GB almacenamiento SSD</li>
      <li>Ancho de banda sin límite</li>
      <li>5 cuentas de email</li>
      <li>Certificado SSL gratis</li>
      <li><strong>Desde $18.000 COP/mes</strong></li>
    </ul>
    <!-- /wp:list -->
  </div>
  <!-- /wp:column -->
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} -->
    <h3>Económico</h3>
    <!-- /wp:heading -->
    <!-- wp:list -->
    <ul>
      <li>Sitios web ilimitados</li>
      <li>50 GB almacenamiento SSD</li>
      <li>Ancho de banda sin límite</li>
      <li>Emails ilimitados</li>
      <li>Certificado SSL gratis</li>
      <li><strong>Desde $35.000 COP/mes</strong></li>
    </ul>
    <!-- /wp:list -->
  </div>
  <!-- /wp:column -->
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} -->
    <h3>Deluxe</h3>
    <!-- /wp:heading -->
    <!-- wp:list -->
    <ul>
      <li>Sitios web ilimitados</li>
      <li>100 GB almacenamiento SSD</li>
      <li>Ancho de banda sin límite</li>
      <li>Emails y bases de datos ilimitadas</li>
      <li>SSL gratis + CDN</li>
      <li><strong>Desde $65.000 COP/mes</strong></li>
    </ul>
    <!-- /wp:list -->
  </div>
  <!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:heading {"level":2} -->
<h2>Hosting WordPress</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Optimizado exclusivamente para WordPress. Más velocidad, mejor rendimiento y actualizaciones automáticas.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Servidores VPS</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Para proyectos que necesitan más control y recursos dedicados. Configura tu servidor según tus necesidades exactas.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>¿Por qué nuestro hosting?</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
  <li><strong>Uptime 99.9%</strong> — Tu sitio siempre disponible.</li>
  <li><strong>Servidores SSD</strong> — Carga 3x más rápida que disco duro.</li>
  <li><strong>cPanel</strong> — Panel de control líder en la industria.</li>
  <li><strong>Backups diarios</strong> — Tu información siempre protegida.</li>
  <li><strong>Soporte en español</strong> — Atención técnica 24/7.</li>
</ul>
<!-- /wp:list -->
HTML;
}

// ─── CONTENIDO: SERVICIOS ─────────────────────────────────────────────────────
function gano_p3_services_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Servicios Digitales</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Todo lo que tu empresa necesita para tener una presencia digital profesional, segura y visible.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>🔒 Certificados SSL</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Protege la información de tus clientes y mejora tu posicionamiento en Google. Un candado verde que genera confianza. Disponible en DV (dominio), OV (organización) y EV (validación extendida).</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>📈 SEO — Posicionamiento en Buscadores</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Aparece en los primeros resultados de Google cuando tus clientes te buscan. Auditoría SEO, optimización on-page, link building y reportes mensuales de rendimiento.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>📧 Email Marketing</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Llega directamente a la bandeja de entrada de tus clientes con campañas de email marketing. Planes para principiantes hasta equipos profesionales con automatizaciones avanzadas.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>🛡️ Seguridad Web</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Protege tu sitio contra hackeos, malware y vulnerabilidades. Monitoreo 24/7, eliminación de malware, firewall WAF y reportes de seguridad detallados.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>💾 Backups & Respaldo</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Copias de seguridad automáticas y diarias de todo tu sitio. Restauración en un clic ante cualquier incidente. Planes desde 5 GB hasta 50 GB.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>📧 Correo Profesional</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Correo con tu dominio (tú@tuempresa.com). Disponible con Microsoft 365 (Outlook, Teams, Office) o Google Workspace (Gmail, Drive, Meet).</p>
<!-- /wp:paragraph -->
HTML;
}

// ─── CONTENIDO: CONTACTO ──────────────────────────────────────────────────────
function gano_p3_contact_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Contáctanos</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Estamos aquí para ayudarte. Cuéntanos tu proyecto y te asesoramos sin costo.</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class="wp-block-columns">

  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} -->
    <h3>Información de contacto</h3>
    <!-- /wp:heading -->
    <!-- wp:list -->
    <ul>
      <li>📧 <a href="mailto:hola@gano.digital">hola@gano.digital</a></li>
      <li>🗺️ Calle 184 #18-22, Bogotá, Colombia</li>
      <li>🕒 Lunes a Viernes: 8:00 AM – 6:00 PM</li>
      <li>🕒 Sábados: 9:00 AM – 1:00 PM</li>
    </ul>
    <!-- /wp:list -->
  </div>
  <!-- /wp:column -->

  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} -->
    <h3>Soporte técnico 24/7</h3>
    <!-- /wp:heading -->
    <!-- wp:paragraph -->
    <p>Para incidencias técnicas urgentes, nuestro equipo de soporte está disponible las 24 horas del día, los 7 días de la semana.</p>
    <!-- /wp:paragraph -->
    <!-- wp:paragraph -->
    <p>📩 <a href="mailto:soporte@gano.digital">soporte@gano.digital</a></p>
    <!-- /wp:paragraph -->
  </div>
  <!-- /wp:column -->

</div>
<!-- /wp:columns -->

<!-- wp:paragraph -->
<p><em>Responderemos tu mensaje en menos de 24 horas hábiles.</em></p>
<!-- /wp:paragraph -->
HTML;
}

// ─── CONTENIDO: SOPORTE ───────────────────────────────────────────────────────
function gano_p3_support_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Centro de Soporte</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Encuentra respuestas a las preguntas más frecuentes de nuestros clientes.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Dominios</h2>
<!-- /wp:heading -->
<!-- wp:heading {"level":3} -->
<h3>¿Cómo registro un dominio?</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Busca tu dominio en nuestra tienda, agrégalo al carrito y sigue el proceso de pago. Una vez confirmada la transacción, tu dominio quedará registrado a tu nombre en menos de 5 minutos.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>¿Cómo transfiero mi dominio a Gano Digital?</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Necesitas el código de autorización EPP de tu registrador actual. Contáctanos a soporte@gano.digital y te guiamos en el proceso paso a paso.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Hosting</h2>
<!-- /wp:heading -->
<!-- wp:heading {"level":3} -->
<h3>¿Cómo accedo a cPanel?</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Después de contratar tu plan, recibirás un email con tus credenciales de cPanel. Puedes acceder desde <code>tudominio.com/cpanel</code> o desde el panel de cliente en gano.digital.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3>¿Puedo migrar mi sitio web existente?</h3>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Sí. Ofrecemos migración gratuita para sitios WordPress. Contacta a nuestro equipo de soporte y lo hacemos por ti sin tiempo de inactividad.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>¿No encontraste tu respuesta?</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Escríbenos a <a href="mailto:soporte@gano.digital">soporte@gano.digital</a> y nuestro equipo técnico te responderá en menos de 4 horas.</p>
<!-- /wp:paragraph -->
HTML;
}

// ─── TÉRMINOS Y CONDICIONES ───────────────────────────────────────────────────
function gano_p3_terms_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Términos y Condiciones</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><em>Última actualización: Marzo 2026</em></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>1. Aceptación de los términos</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Al contratar cualquier servicio de Gano Digital, usted acepta quedar vinculado por estos Términos y Condiciones. Si no está de acuerdo con alguno de estos términos, le recomendamos no utilizar nuestros servicios.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>2. Descripción del servicio</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Gano Digital ofrece servicios de registro de dominios, alojamiento web, certificados SSL, correo electrónico profesional, seguridad web y servicios relacionados con la presencia digital.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>3. Pagos y facturación</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Todos los precios están expresados en pesos colombianos (COP) e incluyen IVA del 19%. Los servicios de suscripción se facturan de forma mensual o anual según el plan seleccionado. La falta de pago en la fecha de vencimiento puede resultar en la suspensión del servicio.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>4. Política de reembolsos</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Los servicios de hosting tienen una garantía de devolución de 30 días. Los registros de dominio no son reembolsables una vez completado el registro. Para solicitar un reembolso, contacte a facturacion@gano.digital.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>5. Uso aceptable</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Queda estrictamente prohibido el uso de nuestros servicios para: actividades ilegales, envío masivo de spam, distribución de malware, almacenamiento de contenido ilícito o actividades que violen los derechos de terceros.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>6. Ley aplicable</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Estos términos se rigen por las leyes de la República de Colombia. Cualquier disputa será resuelta ante los tribunales competentes de la ciudad de Bogotá, D.C.</p>
<!-- /wp:paragraph -->
HTML;
}

// ─── POLÍTICA DE PRIVACIDAD ───────────────────────────────────────────────────
function gano_p3_privacy_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Política de Privacidad</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><em>Última actualización: Marzo 2026</em></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>1. Responsable del tratamiento</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Gano Digital SAS, con domicilio en Calle 184 #18-22, Bogotá, Colombia, es responsable del tratamiento de los datos personales recopilados a través de este sitio web, en cumplimiento de la Ley 1581 de 2012 (Habeas Data) y el Decreto 1377 de 2013.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>2. Datos que recopilamos</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul>
  <li><strong>Datos de registro:</strong> nombre, correo electrónico, teléfono y dirección para la creación de cuenta.</li>
  <li><strong>Datos de pago:</strong> procesados de forma segura por nuestra pasarela de pagos. No almacenamos datos de tarjetas.</li>
  <li><strong>Datos de navegación:</strong> dirección IP, tipo de navegador, páginas visitadas (vía cookies analíticas).</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>3. Finalidad del tratamiento</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul>
  <li>Gestionar la contratación y prestación de servicios.</li>
  <li>Enviar comunicaciones relacionadas con su cuenta.</li>
  <li>Mejorar nuestros servicios mediante análisis estadístico.</li>
  <li>Cumplir con obligaciones legales y contables.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>4. Derechos del titular</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Usted tiene derecho a conocer, actualizar, rectificar y suprimir sus datos personales, así como a revocar la autorización otorgada. Puede ejercer estos derechos escribiendo a privacidad@gano.digital.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>5. Transferencia de datos</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>No vendemos ni cedemos sus datos personales a terceros con fines comerciales. Podemos compartir información con proveedores de servicios tecnológicos que actúan bajo estrictas obligaciones de confidencialidad.</p>
<!-- /wp:paragraph -->
HTML;
}

// ─── ARTÍCULOS DE BLOG ────────────────────────────────────────────────────────
function gano_p3_create_blog_posts() {
    $posts = [
        [
            'title'   => '¿Por qué tu empresa necesita una página web en 2026?',
            'slug'    => 'por-que-empresa-necesita-pagina-web',
            'excerpt' => 'Si todavía no tienes presencia digital, estás perdiendo clientes cada día. Descubre por qué una página web es la inversión más rentable para tu negocio.',
            'content' => <<<HTML
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">En Colombia, el 78% de los consumidores busca información en internet antes de hacer una compra. Si tu empresa no aparece en Google, estás cediendo esos clientes a tu competencia.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Tu vitrina abierta las 24 horas</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>A diferencia de un local físico, tu página web trabaja por ti mientras duermes. Un cliente en Cali puede conocer tu oferta a las 2 AM y hacer un pedido antes del amanecer. Sin horarios, sin intermediarios.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Credibilidad y confianza</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>¿Cuántas veces has buscado una empresa y desconfiado porque no tenía página web? Tus clientes potenciales hacen lo mismo. Un sitio profesional con SSL (candado verde) genera la confianza necesaria para que decidan comprarte a ti y no a otro.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>¿Cuánto cuesta tener una página web?</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Con Gano Digital puedes tener tu sitio en funcionamiento desde <strong>$50.000 COP al mes</strong> (dominio + hosting + SSL). Menos que un almuerzo de negocios, con un impacto exponencialmente mayor.</p>
<!-- /wp:paragraph -->
HTML,
            'category' => 'presencia-digital',
        ],
        [
            'title'   => 'Guía completa: cómo elegir el mejor nombre de dominio para tu negocio',
            'slug'    => 'como-elegir-nombre-dominio-negocio',
            'excerpt' => 'El dominio es la primera impresión de tu negocio en internet. Aprende a elegir uno que sea memorable, profesional y fácil de posicionar en buscadores.',
            'content' => <<<HTML
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Tu dominio es como la dirección de tu oficina, pero en internet. Elegirlo bien puede marcar la diferencia entre ser encontrado o quedar invisible.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Reglas de oro para un buen dominio</h2>
<!-- /wp:heading -->
<!-- wp:list -->
<ul>
  <li><strong>Corto y memorable</strong>: máximo 2-3 palabras. Fácil de decir en voz alta.</li>
  <li><strong>Sin guiones ni números</strong>: dificultan la memorización y la escritura.</li>
  <li><strong>Incluye tu marca o servicio</strong>: si vendes zapatos en Colombia, algo como <em>zapatoscolombia.co</em> dice exactamente lo que eres.</li>
  <li><strong>Evita marcas registradas</strong>: usar el nombre de otra empresa puede traerte problemas legales.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>.com vs .co: ¿cuál elegir?</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Si tu negocio opera principalmente en Colombia, el <strong>.co</strong> te da autoridad local y suele tener mejor posicionamiento para búsquedas desde Colombia. El <strong>.com</strong> es ideal si tienes o planeas tener clientes internacionales.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><strong>Recomendación:</strong> Registra ambos si el presupuesto lo permite. Apunta el .co a tu sitio principal y el .com a una redirección.</p>
<!-- /wp:paragraph -->
HTML,
            'category' => 'dominios',
        ],
        [
            'title'   => 'Hosting compartido vs VPS: ¿cuál necesita tu negocio?',
            'slug'    => 'hosting-compartido-vs-vps',
            'excerpt' => 'Entender la diferencia entre tipos de hosting puede ahorrarte dinero o evitar que tu sitio se caiga en el momento más crítico. Esta guía te ayuda a elegir.',
            'content' => <<<HTML
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Elegir el tipo de hosting equivocado es como alquilar una bodega para guardar una moto, o comprar una moto cuando necesitas una bodega.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Hosting compartido: económico y suficiente para empezar</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>En hosting compartido, tu sitio web "comparte" recursos del servidor (CPU, RAM, almacenamiento) con otros sitios. Es como vivir en un edificio de apartamentos: más económico, pero con recursos limitados.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><strong>Ideal para:</strong> sitios nuevos, blogs, portafolios, pequeñas tiendas online con tráfico menor a 10.000 visitas/mes.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>VPS: tu propio espacio, más potencia</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Un VPS (Servidor Privado Virtual) te da recursos dedicados dentro de un servidor físico. Es como tener tu propio apartamento: pagas más, pero nadie más afecta tu rendimiento.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><strong>Ideal para:</strong> tiendas online en crecimiento, plataformas SaaS, sitios con más de 20.000 visitas/mes, aplicaciones que requieren configuraciones personalizadas.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Resumen rápido</h2>
<!-- /wp:heading -->
<!-- wp:table -->
<figure class="wp-block-table"><table>
  <thead><tr><th>Característica</th><th>Compartido</th><th>VPS</th></tr></thead>
  <tbody>
    <tr><td>Precio/mes</td><td>Desde $18.000 COP</td><td>Desde $85.000 COP</td></tr>
    <tr><td>Recursos</td><td>Compartidos</td><td>Dedicados</td></tr>
    <tr><td>Control del servidor</td><td>Limitado (cPanel)</td><td>Total (root access)</td></tr>
    <tr><td>Escalabilidad</td><td>Limitada</td><td>Alta</td></tr>
    <tr><td>Para sitios con</td><td>&lt; 10.000 visitas/mes</td><td>&gt; 20.000 visitas/mes</td></tr>
  </tbody>
</table></figure>
<!-- /wp:table -->
HTML,
            'category' => 'hosting',
        ],
    ];

    $created = 0;
    foreach ( $posts as $post_data ) {
        if ( ! get_page_by_path( $post_data['slug'], OBJECT, 'post' ) ) {
            $id = wp_insert_post( [
                'post_type'    => 'post',
                'post_title'   => $post_data['title'],
                'post_name'    => $post_data['slug'],
                'post_content' => $post_data['content'],
                'post_excerpt' => $post_data['excerpt'],
                'post_status'  => 'publish',
                'post_author'  => 1,
            ] );
            if ( $id && ! is_wp_error( $id ) ) {
                $created++;
            }
        }
    }
    return $created;
}

// ─── MOSTRAR RESULTADOS ───────────────────────────────────────────────────────
add_action( 'admin_notices', 'gano_p3_notice' );
function gano_p3_notice() {
    $log = get_option( 'gano_p3_log', [] );
    if ( empty( $log ) ) return;

    $errors = array_filter( $log, fn($r) => str_starts_with( $r, '❌' ) );
    $class  = empty( $errors ) ? 'notice-success' : 'notice-warning';

    echo '<div class="notice ' . esc_attr( $class ) . ' is-dismissible">';
    echo '<p><strong>📝 Gano Digital — Phase 3 Content Builder</strong></p><ul>';
    foreach ( $log as $entry ) {
        echo '<li>' . esc_html( $entry ) . '</li>';
    }
    echo '</ul><p><em>Puedes desactivar y eliminar este plugin.</em></p></div>';
}

register_deactivation_hook( __FILE__, function() {
    delete_option( 'gano_p3_log' );
    delete_option( 'gano_p3_ran_at' );
} );
