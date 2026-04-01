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
            'title'   => 'Nuestra Filosofía',
            'excerpt' => 'Arquitectos de la soberanía digital en Colombia.',
            'content' => gano_p3_about_content(),
            'seo'     => [
                'title'       => 'Nuestra Misión SOTA | Gano Digital — Infraestructura Soberana',
                'description' => 'Conoce la visión de Gano Digital: devolver el control de la infraestructura a las empresas colombianas mediante tecnología de vanguardia.',
                'keywords'    => 'soberania digital colombia, gano digital vision, hosting etico',
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
            'title'   => 'Ecosistemas de Infraestructura',
            'excerpt' => 'Arquitectura de alta disponibilidad y soberanía digital en Colombia.',
            'content' => gano_p3_hosting_content(),
            'seo'     => [
                'title'       => 'Infraestructura SOTA | Hosting NVMe Gen4 en Colombia | Gano Digital',
                'description' => 'Ecosistemas de hosting diseñados para la soberanía de tus datos. Velocidad NVMe sin precedentes, blindaje Zero-Trust y soporte local de élite en Bogotá.',
                'keywords'    => 'hosting nvme colombia, soberania digital, hosting premium bogota',
            ],
        ],

        'servicios' => [
            'title'   => 'Blindaje y Optimización',
            'excerpt' => 'Capas adicionales de inteligencia y seguridad para tu activo digital.',
            'content' => gano_p3_services_content(),
            'seo'     => [
                'title'       => 'Servicios SOTA | Seguridad y Rendimiento Avanzado | Gano Digital',
                'description' => 'Certificados de encriptación de grado militar, SEO de autoridad y blindaje perimetral. Protege tu imperio digital con Gano.',
                'keywords'    => 'seguridad web colombia, ssl avanzado, seo de autoridad',
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
            'title'    => 'Fortaleza Zero-Trust',
            'excerpt'  => 'Manifiesto de Inmunidad: Perímetro blindado mediante verificación continua.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Inmunidad Zero-Trust | SOTA Gano Digital', 'description' => 'Blindaje perimetral SOTA mediante el modelo de confianza cero.', 'keywords' => 'zero trust colombia' ],
        ],

        'almacenamiento-nvme' => [
            'title'    => 'Núcleo NVMe Gen4',
            'excerpt'  => 'Ingeniería de Latencia Cero: Almacenamiento de estado sólido para misión crítica.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'NVMe Gen4 | Ingeniería SOTA Gano Digital', 'description' => 'I/O ultra rápido para aplicaciones empresariales.', 'keywords' => 'nvme hosting colombia' ],
        ],

        'soberania-digital' => [
            'title'    => 'Soberanía Digital Absoluta',
            'excerpt'  => 'Jurisdicción Soberana: El poder total sobre tus datos y hardware.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Soberanía Digital | Independencia Tecnológica', 'description' => 'Control estratégico de infraestructura en Colombia.', 'keywords' => 'soberania digital colombia' ],
        ],

        'inteligencia-sintetica' => [
            'title'    => 'Cerebro IA Predictivo',
            'excerpt'  => 'Gestión Autónoma: Prevención de fallos mediante redes neuronales.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'IA Infraestructura | Agente Soberano Gano Digital', 'description' => 'Optimización y autocuración mediante Inteligencia Artificial SOTA.', 'keywords' => 'ia hosting colombia' ],
        ],

        'red-global-anycast' => [
            'title'    => 'Malla de Borde Anycast',
            'excerpt'  => 'Proximidad de Élite: Tu contenido en el borde de la red global.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Anycast Network | Distribución SOTA', 'description' => 'Latencia mínima distribuida regionalmente.', 'keywords' => 'anycast cdn colombia' ],
        ],

        'computacion-serverless' => [
            'title'    => 'Elasticidad Serverless',
            'excerpt'  => 'Escalabilidad Monolítica: Potencia sin gestión de infraestructura.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Serverless Hosting | Escalabilidad SOTA', 'description' => 'Infraestructura elástica para alta demanda.', 'keywords' => 'serverless colombia' ],
        ],

        'ecosistemas-hibridos' => [
            'title'    => 'Ecosistemas Híbridos SOTA',
            'excerpt'  => 'Sinergia Crítica: Lo mejor del cloud privado en una infraestructura soberana.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Hybrid Cloud SOTA | Gano Digital', 'description' => 'Malla única de infraestructura de alto rendimiento.', 'keywords' => 'nube hibrida colombia' ],
        ],

        'edge-computing-pro' => [
            'title'    => 'Edge Computing de Autoridad',
            'excerpt'  => 'Lógica Proximal: Ejecución milimétrica en el nodo más cercano.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Edge Computing | SOTA Infraestructura', 'description' => 'Rendimiento de borde para aplicaciones de élite.', 'keywords' => 'edge computing colombia' ],
        ],

        'ciber-resiliencia-fractal' => [
            'title'    => 'Ciber-Resiliencia Fractal',
            'excerpt'  => 'Inmunidad ante Desastres: Recuperación automática y redundancia infinita.',
            'content'  => '<!-- SOTA Architecture Content -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Resiliencia Digital | Gano Digital', 'description' => 'Continuidad de negocio mediante protocolos SOTA.', 'keywords' => 'disaster recovery colombia' ],
        ],

        'catalogo-sota' => [
            'title'    => 'Gano SOTA Index',
            'excerpt'  => 'Explora el Estado del Arte: El catálogo de la vanguardia tecnológica.',
            'content'  => '<!-- SOTA Catalog -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'SOTA Index | El Estado del Arte en Colombia', 'description' => 'Directorio de innovaciones de valor estratégico.', 'keywords' => 'tecnologia sota colombia' ],
        ],

        'dashboard-infraestructura' => [
            'title'    => 'Panel de Control Soberano',
            'excerpt'  => 'Soberanía Visual: Monitoreo en tiempo real de tu activo digital.',
            'content'  => '<!-- SOTA Dashboard -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Dashboard SOTA | Gestión Total', 'description' => 'Control absoluto de red e infraestructura.', 'keywords' => 'panel hosting colombia' ],
        ],

        'arquitectura-cloud' => [
            'title'    => 'Arquitectura Cloud SOTA',
            'excerpt'  => 'Diseño de Resiliencia: Ingeniería de nubes de alto desempeño.',
            'content'  => '<!-- SOTA Cloud Architecture -->',
            'category' => 'sota',
            'seo'      => [ 'title' => 'Cloud SOTA | Infraestructura Gano Digital', 'description' => 'Ecosistemas en la nube para empresas de misión crítica.', 'keywords' => 'cloud server colombia' ],
        ],

    ];
}

// ─── CONTENIDO: NOSOTROS ──────────────────────────────────────────────────────
function gano_p3_about_content() {
    return <<<HTML
<!-- wp:group {"layout":{"type":"constrained","contentSize":"900px"}} -->
<div class="wp-block-group">

<!-- wp:heading {"level":1} -->
<h1>Arquitectos de tu Libertad Digital</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">En Gano Digital no solo proveemos servidores; diseñamos <strong>Soberanía Digital</strong>. Somos una firma tecnológica colombiana nacida en Bogotá con una misión clara: devolver el control de la infraestructura crítica a las empresas que mueven la economía del país.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>El Manifiesto SOTA</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
  <li><strong>Soberanía de Datos</strong> — Tus activos digitales bajo jurisdicción y protección local absoluta.</li>
  <li><strong>Rendimiento Crítico</strong> — Infraestructura NVMe Gen4 diseñada para la latencia cero en el mercado colombiano.</li>
  <li><strong>Confianza Zero-Trust</strong> — Un modelo de seguridad donde la confianza no se asume, se verifica en cada paquete.</li>
  <li><strong>Soporte de Élite</strong> — Ingeniería en español que entiende la presión de tu operación en tiempo real.</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading {"level":2} -->
<h2>Nuestra Visión</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Nacimos para romper la dependencia de las "nubes opacas". Gano Digital es el puente entre la potencia global y el control local. Creemos que la independencia tecnológica es el único camino hacia una competitividad real en la era de la Inteligencia Artificial.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Compromiso Monolítico</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Operamos bajo el concepto de <strong>Kinetic Monolith</strong>: nuestras soluciones son sólidas como el granito pero ágiles como la fibra óptica. Garantizamos un ecosistema de alta disponibilidad (99.9% Uptime) blindado contra las amenazas del mañana.</p>
<!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
HTML;
}

// ─── CONTENIDO: DOMINIOS ──────────────────────────────────────────────────────
function gano_p3_domains_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Gestión de Activos de Soberanía</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Tu dominio no es solo una dirección; es el nodo primario de tu autoridad en internet. Orquestamos la gestión técnica de tus activos DNS bajo criterios de seguridad de misión crítica.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Extensiones de Alta Autoridad</h2>
<!-- /wp:heading -->

<!-- wp:table -->
<figure class="wp-block-table"><table>
  <thead><tr><th>Extensión de Soberanía</th><th>Aplicabilidad Estratégica</th><th>Gestión / Auditoría</th></tr></thead>
  <tbody>
    <tr><td>.com</td><td>Proyectos Globales — La extensión de mayor autoridad técnica y comercial.</td><td>Bajo demanda estratégica</td></tr>
    <tr><td>.co / .com.co</td><td>Autoridad Local Colombia — Posicionamiento y resiliencia nacional.</td><td>Bajo demanda estratégica</td></tr>
    <tr><td>.net / .store</td><td>Ecosistemas de Red y Plataformas de Comercio SOTA de alto volumen.</td><td>Bajo demanda estratégica</td></tr>
    <tr><td>SOTA Custom</td><td>Extensiones de lujo (ej. .ai, .security) para infraestructura crítica.</td><td>Bajo demanda estratégica</td></tr>
  </tbody>
</table></figure>
<!-- /wp:table -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><em>La gestión de dominios en Gano Digital es un servicio exclusivo para socios de infraestructura soberana. No operamos como registrador minorista.</em></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Protocolo de Gestión SOTA</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
  <li><strong>Control Autoritario de DNS</strong> — Infraestructura de nombres redundante para latencia cero.</li>
  <li><strong>Blindaje contra Secuestro</strong> — Bloqueo de registro multicapa y verificación de identidad proactiva.</li>
  <li><strong>Resiliencia de Renovación</strong> — Ciclos de vida gestionados para garantizar la continuidad total de la operación.</li>
  <li><strong>Privacidad Soberana WHOIS</strong> — Protección de los datos de contacto del titular bajo estándares corporativos.</li>
</ul>
<!-- /wp:list -->
HTML;
}

// ─── CONTENIDO: HOSTING ───────────────────────────────────────────────────────
function gano_p3_hosting_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Ecosistemas de Infraestructura SOTA</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Potencia pura cimentada en almacenamiento NVMe Gen4 y blindaje perimetral constante. Nuestra arquitectura de élite garantiza la <strong>Soberanía Digital</strong> absoluta de tus activos institucionales.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Arquitectura de Misión Crítica</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns">
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} -->
    <h3>Núcleo Prime</h3>
    <!-- /wp:heading -->
    <!-- wp:list -->
    <ul>
      <li>Despliegue de Activo Digital Soberano</li>
      <li>NVMe Ultra de Latencia Cero</li>
      <li>Transferencia Ilimitada SOTA</li>
      <li>Blindaje Zero-Trust Proactivo</li>
      <li>Encriptación SSL de Grado Militar</li>
      <li><em>Activación bajo Auditoría Estratégica</em></li>
    </ul>
    <!-- /wp:list -->
  </div>
  <!-- /wp:column -->
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} -->
    <h3>Fortaleza Delta</h3>
    <!-- /wp:heading -->
    <!-- wp:list -->
    <ul>
      <li>Ecosistemas Multi-Activos Ilimitados</li>
      <li>Infraestructura NVMe de Alta Densidad</li>
      <li>Capa de Inteligencia Predictiva SOTA</li>
      <li>Cifrado de Datos en Reposo (AES-256)</li>
      <li>Soporte de Ingeniería de Élite en Bogotá</li>
      <li><em>Activación bajo Auditoría Estratégica</em></li>
    </ul>
    <!-- /wp:list -->
  </div>
  <!-- /wp:column -->
  <!-- wp:column -->
  <div class="wp-block-column">
    <!-- wp:heading {"level":3} -->
    <h3>Bastión SOTA</h3>
    <!-- /wp:heading -->
    <!-- wp:list -->
    <ul>
      <li>Escalabilidad Monolítica Infinita</li>
      <li>Arquitectura NVMe Gen4 Enterprise</li>
      <li>Red Global Anycast BGP Integrada</li>
      <li>Auditoría de Vulnerabilidades SOTA</li>
      <li>Inmunidad Digital Garantizada por SLA</li>
      <li><em>Activación bajo Auditoría Estratégica</em></li>
    </ul>
    <!-- /wp:list -->
  </div>
  <!-- /wp:column -->
</div>
<!-- /wp:columns -->

<!-- wp:heading {"level":2} -->
<h2>¿Por qué nuestra arquitectura es el Estado del Arte?</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul>
  <li><strong>Ingeniería NVMe Gen4 Nativa</strong> — Latencia eliminada para siempre. Tu ecosistema responde a la velocidad de la luz.</li>
  <li><strong>Blindaje Zero-Trust</strong> — Ningún acceso se concede sin verificación criptográfica. Seguridad absoluta por diseño.</li>
  <li><strong>Soberanía de Datos en Colombia</strong> — Jurisdicción local soberana y tiempos de respuesta nacionales de élite.</li>
  <li><strong>IA de Autocuración</strong> — Detectamos y remediamos anomalías antes de que el usuario note la degradación.</li>
</ul>
<!-- /wp:list -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><em>Nuestros servicios se activan previa sesión de consultoría técnica para asegurar el alineamiento estratégico de tu infraestructura.</em></p>
HTML;
}

// ─── CONTENIDO: SERVICIOS ─────────────────────────────────────────────────────
function gano_p3_services_content() {
    return <<<HTML
<!-- wp:heading {"level":1} -->
<h1>Blindaje y Optimización de Activos</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Capas de seguridad avanzada e inteligencia aplicadas a tu presencia digital.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>🛡️ Inmunidad Digital (SSL + WAF)</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Más que un candado verde: implementamos encriptación de grado militar y firewalls de aplicación web (WAF) que aprenden y neutralizan amenazas antes de que toquen tu servidor.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>📐 SEO de Autoridad Monolítica</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>No solo buscamos tráfico; buscamos relevancia absoluta. Estrategias de posicionamiento diseñadas para dominar las búsquedas más competitivas en el mercado colombiano.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>⚡ Aceleración Anycast</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Distribución de contenido en el borde (Edge Computing). Tu sitio web se sirve desde el nodo más cercano a tu cliente, eliminando la distancia física.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>💾 Resiliencia y Backup SOTA</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Snapshotting continuo y backups granulares. En caso de desastre, tu infraestructura se reconstruye en segundos, garantizando la continuidad total de tu negocio.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>🌐 Elitismo en Conectividad</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Correo profesional y herramientas de colaboración (GWS / M365) configuradas bajo estándares de seguridad Gano para evitar phishing y filtraciones.</p>
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
            'title'   => 'Soberanía Digital: El Derecho Humano a la Propiedad de los Datos',
            'slug'    => 'soberania-digital-derecho-propiedad-datos',
            'excerpt' => 'En la era de la IA, ceder el control de tus activos digitales es ceder el futuro de tu empresa. Conoce el Manifiesto de Soberanía de Gano Digital.',
            'content' => <<<HTML
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">La infraestructura no es un commodity; es el sistema nervioso de tu soberanía. Gano Digital redefine el poder tecnológico en Colombia devolviendo el control total a las organizaciones.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>El Fin de la Dependencia Extranjera</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Nuestra misión es asegurar que cada byte de tu información resida bajo tu jurisdicción y mando. Mediante el uso de tecnologías SOTA y cifrado de grado militar, blindamos tu libertad frente a ecosistemas opacos y centralizados.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Inversión Estratégica en Autoridad</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Un activo digital soberano no tiene "costo de hosting"; tiene una tasa de retorno sobre la seguridad institucional. En Gano Digital, cada solución se orquesta para maximizar tu independencia y autoridad en el mercado global.</p>
<!-- /wp:paragraph -->
HTML,
            'category' => 'soberania-digital',
        ],
        [
            'title'   => 'Manifiesto NVMe: Por qué la Velocidad es la Nueva Seguridad',
            'slug'    => 'manifiesto-nvme-velocidad-nueva-seguridad',
            'excerpt' => 'En el Estado del Arte, la latencia es una vulnerabilidad. Descubre cómo la arquitectura NVMe Gen4 de Gano Digital blinda tu rendimiento crítico.',
            'content' => <<<HTML
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">Cada milisegundo de retraso es una oportunidad perdida para la conversión y un vector de ataque para la obsolescencia. La velocidad no es un lujo; es ingeniería de misión crítica.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Ingeniería de Latencia Cero</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Gano Digital implementa exclusivamente almacenamiento NVMe Gen4. Al eliminar los cuellos de botella del protocolo SATA heredado, permitimos que tu infraestructura WordPress se comunique directamente con el procesador, garantizando respuestas instantáneas a escala masiva.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Resiliencia que se Siente</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Nuestra malla de infraestructura está diseñada para soportar picos de carga imprevistos sin degradar la experiencia de usuario. La velocidad es el primer paso hacia la inmunidad digital absoluta.</p>
<!-- /wp:paragraph -->
HTML,
            'category' => 'infraestructura',
        ],
        [
            'title'   => 'Zero-Trust: Blindando el Perímetro en un Mundo sin Perímetro',
            'category' => 'seguridad',
            'slug'    => 'zero-trust-blindando-perimetro',
            'excerpt' => 'Confianza Cero: La arquitectura de seguridad donde cada acceso se verifica. Conoce el blindaje fractal de Gano Digital.',
            'content' => <<<HTML
<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size">El concepto tradicional de "firewall" ha muerto. En Gano Digital, implementamos inmunidad activa mediante verificación continua de identidad y permisos.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Micro-Segmentación por Diseño</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Cada componente de tu ecosistema —desde la base de datos hasta los activos estáticos— vive en un entorno aislado y verificado. Si un componente se ve amenazado, el resto de la fortaleza permanece inexpugnable.</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":2} -->
<h2>Autoridad Criptográfica</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>No esperamos a que ocurra el ataque. Nuestra IA predictiva analiza patrones de tráfico en tiempo real para neutralizar amenazas antes de que traspasen la primera capa de nuestra arquitectura soberana.</p>
<!-- /wp:paragraph -->
HTML,
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
