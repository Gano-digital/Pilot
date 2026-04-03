<?php
/**
 * Gano Digital — SEO & Performance MU Plugin
 * Fase 3: SEO y Performance, Marzo 2026.
 *
 * Funcionalidades:
 *   1. Schema JSON-LD: Organization (o LocalBusiness si hay local físico configurado), WebSite
 *   2. Schema JSON-LD: Product con precios COP para los 4 ecosistemas de WooCommerce
 *   3. Schema JSON-LD: BreadcrumbList automático
 *   4. Schema JSON-LD: FAQPage en homepage (preguntas de hosting frecuentes)
 *   5. Resource hints: preconnect a dominios críticos de terceros
 *   6. LCP Hero: preload del hero image configurado vía wp-admin
 *   7. Open Graph y Twitter Card (fallback si Rank Math no los genera)
 *   8. Eliminación de head elements de bajo valor y filtrado de generator tag
 *
 * NOTA: Rank Math tiene prioridad — este plugin complementa pero no duplica RM.
 *       Si Rank Math está activo, este plugin solo agrega schema que RM no genera
 *       por defecto (BreadcrumbList personalizado, FAQ custom).
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * RANK MATH SETUP WIZARD — Configuración recomendada para Gano Digital
 * (negocio digital sin tienda física, servicio en todo Colombia)
 * ─────────────────────────────────────────────────────────────────────────────
 *
 * Pasos del wizard (wp-admin → Rank Math → Setup Wizard):
 *
 *   1. Compatibilidad: activar modo Avanzado (Advanced Mode) para control total.
 *
 *   2. Tipo de sitio → "Empresa/Organización" (Company/Organization).
 *      - NO seleccionar "Negocio local" (Local Business): Gano Digital opera
 *        100 % de forma digital; no tiene mostrador ni atención presencial.
 *      - Subir logo en el campo solicitado.
 *
 *   3. Datos de la empresa:
 *      - Nombre legal: Gano Digital SAS (o razón social definitiva)
 *      - URL: https://gano.digital
 *      - NO rellenar dirección física si no existe un local verificable;
 *        dejar en blanco o colocar "Colombia" como país de operación.
 *
 *   4. Webmaster Tools: pegar el código de Google Search Console.
 *      (Propiedades recomendadas: https://gano.digital y gano.digital)
 *
 *   5. Sitemap: activar Sitemap XML → incluir Páginas, Posts, Productos.
 *      Excluir: páginas de carrito/checkout/cuenta (WooCommerce las añade).
 *
 *   6. Optimizaciones: activar "Noindex" para páginas de archivo de autor,
 *      búsqueda y tags de bajo valor.
 *
 *   7. Schema por defecto en Rank Math → Ajustes generales → Schema:
 *      - Tipo de artículo: "Article" para posts/noticias.
 *      - Dejar que este MU plugin emita el schema de Organization y FAQ,
 *        ya que Rank Math puede no generar `areaServed` ni `hasOfferCatalog`.
 *
 *   Estado (Abril 2026): wizard pendiente de ejecución en producción.
 *   Acción: wp-admin → Rank Math → Dashboard → "Re-run Setup Wizard".
 * ─────────────────────────────────────────────────────────────────────────────
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// 1. CONFIGURACIÓN BASE — Editable desde wp-admin → Ajustes → Gano SEO
// =============================================================================

/**
 * Devuelve la configuración SEO del sitio.
 * Diego puede sobrescribir cualquier valor con update_option().
 * Ejemplo: update_option('gano_seo_phone', '+57 300 123 4567');
 *
 * @return array<string, string>
 */
function gano_seo_config(): array {
    return array(
        'name'            => get_bloginfo( 'name' ) ?: 'Gano Digital',
        'legal_name'      => get_option( 'gano_seo_legal_name',  'Gano Digital SAS' ),
        'nit'             => get_option( 'gano_seo_nit',          '' ),        // Ej: '900.123.456-7'
        'phone'           => get_option( 'gano_seo_phone',        '' ),        // Ej: '+57 300 123 4567'
        'whatsapp'        => get_option( 'gano_seo_whatsapp',     '' ),        // Solo número sin + ni espacios
        'email'           => get_option( 'gano_seo_email',        'hola@gano.digital' ),
        // Dirección física: vacío por defecto — Gano Digital es un negocio 100 % digital.
        // Completar en wp-admin → Ajustes → Gano SEO solo si existe un local físico verificable.
        'address_street'  => get_option( 'gano_seo_street',       '' ),
        'address_city'    => get_option( 'gano_seo_city',         '' ),
        'address_region'  => get_option( 'gano_seo_region',       '' ),
        'address_country' => 'CO',
        'address_postal'  => get_option( 'gano_seo_postal',       '' ),
        // Coordenadas vacías por defecto: solo se emiten al schema si están explícitamente configuradas.
        'latitude'        => get_option( 'gano_seo_lat',          '' ),
        'longitude'       => get_option( 'gano_seo_lng',          '' ),
        'logo_url'        => get_option( 'gano_seo_logo',         get_site_url() . '/wp-content/uploads/logo.png' ),
        'hero_image_url'  => get_option( 'gano_seo_hero_image',   '' ),
        'founded_year'    => get_option( 'gano_seo_founded',      '2024' ),
        'site_url'        => get_site_url(),
        'description'     => 'Infraestructura soberana y misiones críticas en Colombia. Gano Digital orquesta ecosistemas WordPress de alta disponibilidad con ingeniería NVMe Gen4, seguridad Zero-Trust y soberanía de datos absoluta.',
        // Tipo de negocio: 'organization' (digital, sin local físico) o 'local_business' (con local físico verificable).
        // Controla el @type del schema JSON-LD. Por defecto: 'organization'.
        'business_type'   => get_option( 'gano_seo_business_type', 'organization' ),
    );
}

// =============================================================================
// 2. SCHEMA JSON-LD — ORGANIZACIÓN + LOCAL BUSINESS + WEBSITE (todas las páginas)
// =============================================================================

add_action( 'wp_head', 'gano_output_base_schema', 5 );
function gano_output_base_schema(): void {
    // Si Rank Math ya genera Organization, solo agregar WebSite y datos faltantes.
    // Usamos rank_math_json_ld filter para complementar en lugar de duplicar.
    $cfg = gano_seo_config();
    $url = $cfg['site_url'];

    // ── Tipo de schema: Organization (digital) o LocalBusiness (con local físico) ────────
    // Para modelo 100 % digital sin dirección verificable → ['Organization'].
    // Si Diego configura un local físico en Ajustes → Gano SEO, usar ['Organization','LocalBusiness'].
    if ( 'local_business' === $cfg['business_type'] ) {
        $schema_type = array( 'Organization', 'LocalBusiness' );
    } else {
        $schema_type = array( 'Organization' );
    }

    // ── Organization ───────────────────────────────────────────────────────────
    $organization = array(
        '@type'           => $schema_type,
        '@id'             => $url . '/#organization',
        'name'            => $cfg['name'],
        'legalName'       => $cfg['legal_name'],
        'url'             => $url,
        'logo'            => array(
            '@type' => 'ImageObject',
            '@id'   => $url . '/#logo',
            'url'   => $cfg['logo_url'],
            'caption' => $cfg['name'],
        ),
        'image'           => array( '@id' => $url . '/#logo' ),
        'description'     => $cfg['description'],
        'foundingDate'    => $cfg['founded_year'],
        'sameAs'          => array(
            // Agregar aquí las URLs de perfiles en otras plataformas:
            // Ej: 'https://www.linkedin.com/company/gano-digital',
            //     'https://twitter.com/ganodigital',
            //     'https://www.facebook.com/ganodigital'
        ),
        'areaServed'      => array(
            array( '@type' => 'Country',  'name' => 'Colombia' ),
            array( '@type' => 'Country',  'name' => 'México' ),
            array( '@type' => 'Continent','name' => 'América Latina' ),
        ),
        'currenciesAccepted' => 'COP',
        'paymentAccepted' => 'PSE, Tarjeta de Crédito, Tarjeta de Débito, Nequi, Daviplata',
    );

    // priceRange solo aplica a LocalBusiness / ProfessionalService
    if ( 'local_business' === $cfg['business_type'] ) {
        $organization['priceRange'] = '$$$';
    }

    // Dirección física: solo incluir si está explícitamente configurada
    if ( ! empty( $cfg['address_street'] ) ) {
        $organization['address'] = array(
            '@type'           => 'PostalAddress',
            'streetAddress'   => $cfg['address_street'],
            'addressLocality' => $cfg['address_city'],
            'addressRegion'   => $cfg['address_region'],
            'addressCountry'  => $cfg['address_country'],
            'postalCode'      => $cfg['address_postal'],
        );
    }

    // Coordenadas geográficas: solo incluir si están explícitamente configuradas
    if ( ! empty( $cfg['latitude'] ) && ! empty( $cfg['longitude'] ) ) {
        $organization['geo'] = array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => $cfg['latitude'],
            'longitude' => $cfg['longitude'],
        );
    }

    // Agregar datos de contacto solo si están configurados
    if ( ! empty( $cfg['phone'] ) ) {
        $organization['telephone'] = $cfg['phone'];
    }
    if ( ! empty( $cfg['email'] ) ) {
        $organization['email'] = $cfg['email'];
    }
    if ( ! empty( $cfg['nit'] ) ) {
        $organization['taxID'] = $cfg['nit'];
    }
    if ( ! empty( $cfg['whatsapp'] ) ) {
        $organization['sameAs'][] = 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $cfg['whatsapp'] );
    }

    // ── WebSite ────────────────────────────────────────────────────────────────
    $website = array(
        '@type'           => 'WebSite',
        '@id'             => $url . '/#website',
        'url'             => $url,
        'name'            => $cfg['name'],
        'description'     => $cfg['description'],
        'publisher'       => array( '@id' => $url . '/#organization' ),
        'inLanguage'      => 'es-CO',
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'       => 'EntryPoint',
                'urlTemplate' => $url . '/?s={search_term_string}',
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );

    // ── FAQ en homepage (mejora CTR en SERPs) ─────────────────────────────────
    // Las preguntas base se pueden ampliar con el filtro 'gano_faq_questions'.
    // Estructura de cada elemento: array( 'question' => '…', 'answer' => '…' ).
    // Ejemplo de uso en functions.php del tema hijo:
    //
    //   add_filter( 'gano_faq_questions', function( array $q ): array {
    //       $q[] = array( 'question' => '¿…?', 'answer' => '…' );
    //       return $q;
    //   } );
    $faq_schema = null;
    if ( is_front_page() ) {
        $faq_base = array(
            array(
                'question' => '¿Qué define la infraestructura SOTA de Gano Digital?',
                'answer'   => 'Definimos el Estado del Arte (SOTA) mediante la convergencia de latencia cero (NVMe Gen4), soberanía jurídica de datos en Colombia e inmunidad perimetral Zero-Trust. No somos hosting; somos tu socio de infraestructura soberana.',
            ),
            array(
                'question' => '¿Cómo garantizan la soberanía de mis activos digitales?',
                'answer'   => 'Operamos bajo jurisdicción colombiana, asegurando que tus datos y hardware residan en bóvedas digitales locales, blindadas contra la dependencia de nubes públicas opacas y legislación extranjera.',
            ),
            array(
                'question' => '¿Qué ecosistemas de resiliencia ofrecen?',
                'answer'   => 'Orquestamos tres niveles de blindaje soberano: Núcleo Prime (Infraestructura base de alta velocidad), Fortaleza Delta (Seguridad predictiva avanzada) y Bastión SOTA (Resiliencia total y alta disponibilidad).',
            ),
            array(
                'question' => '¿Incluyen blindaje de seguridad avanzado?',
                'answer'   => 'Todos los ecosistemas incluyen certificación TLS avanzada, firewall de aplicación (WAF) activo y un sistema inmunológico digital basado en principios Zero-Trust para neutralizar amenazas en tiempo real.',
            ),
            array(
                'question' => '¿Realizan migraciones de infraestructura crítica?',
                'answer'   => 'Sí, orquestamos la migración completa de tus activos digitales hacia nuestra infraestructura soberana sin interrupciones de servicio, garantizando la integridad de cada byte durante el proceso.',
            ),
        );

        /**
         * Filtra las preguntas FAQ del schema homepage.
         *
         * Cada elemento debe ser: array( 'question' => string, 'answer' => string ).
         * Ver candidatos en memory/content/faq-schema-candidates-wave3.md.
         *
         * @param array<int, array{question: string, answer: string}> $faq_base Preguntas base.
         */
        $faq_items = apply_filters( 'gano_faq_questions', $faq_base );

        $main_entity = array();
        foreach ( $faq_items as $item ) {
            if ( empty( $item['question'] ) || empty( $item['answer'] ) ) {
                continue;
            }
            $main_entity[] = array(
                '@type'          => 'Question',
                'name'           => sanitize_text_field( $item['question'] ),
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text'  => wp_strip_all_tags( $item['answer'] ),
                ),
            );
        }

        if ( ! empty( $main_entity ) ) {
            $faq_schema = array(
                '@type'      => 'FAQPage',
                '@id'        => $url . '/#faqpage',
                'mainEntity' => $main_entity,
            );
        }
    }

    // ── Compilar y emitir ──────────────────────────────────────────────────────
    $graph = array( $organization, $website );
    if ( $faq_schema ) {
        $graph[] = $faq_schema;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    );

    echo "\n<!-- Gano Digital: Schema JSON-LD Base -->\n";
    echo '<script type="application/ld+json">'
        . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
        . '</script>' . "\n";
}

// =============================================================================
// 3. SCHEMA JSON-LD — PRODUCTOS WOOCOMMERCE (páginas individuales de producto)
// =============================================================================

add_action( 'wp_head', 'gano_output_product_schema', 6 );
function gano_output_product_schema(): void {
    if ( ! function_exists( 'is_product' ) || ! is_product() ) {
        return;
    }

    global $product;
    if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
        $product = wc_get_product( get_the_ID() );
    }
    if ( ! $product ) {
        return;
    }

    $cfg     = gano_seo_config();
    $url     = $cfg['site_url'];
    $price   = $product->get_regular_price();
    $img_url = get_the_post_thumbnail_url( $product->get_id(), 'full' ) ?: $cfg['logo_url'];

    $product_schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        '@id'         => get_permalink( $product->get_id() ) . '#product',
        'name'        => $product->get_name(),
        'description' => wp_strip_all_tags( $product->get_description() ?: $product->get_short_description() ),
        'image'       => $img_url,
        'sku'         => $product->get_sku() ?: $product->get_id(),
        'brand'       => array(
            '@type' => 'Brand',
            'name'  => $cfg['name'],
        ),
        'offers'      => array(
            '@type'         => 'Offer',
            '@id'           => get_permalink( $product->get_id() ) . '#offer',
            'url'           => get_permalink( $product->get_id() ),
            'priceCurrency' => get_woocommerce_currency() ?: 'COP',
            'price'         => $price,
            'priceValidUntil' => gmdate( 'Y-12-31', strtotime( '+1 year' ) ),
            'availability'  => $product->is_in_stock()
                ? 'https://schema.org/InStock'
                : 'https://schema.org/OutOfStock',
            'seller'        => array( '@id' => $url . '/#organization' ),
            'acceptedPaymentMethod' => array(
                array( '@type' => 'PaymentMethod', 'name' => 'PSE' ),
                array( '@type' => 'PaymentMethod', 'name' => 'Nequi' ),
                array( '@type' => 'PaymentMethod', 'name' => 'Tarjeta de Crédito' ),
                array( '@type' => 'PaymentMethod', 'name' => 'Tarjeta de Débito' ),
            ),
        ),
        'category'    => 'Infraestructura Soberana SOTA',
        'inLanguage'  => 'es-CO',
    );

    echo "\n<!-- Gano Digital: Schema JSON-LD Producto -->\n";
    echo '<script type="application/ld+json">'
        . wp_json_encode( $product_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
        . '</script>' . "\n";
}

// =============================================================================
// 4. SCHEMA JSON-LD — BREADCRUMB (interior pages)
// =============================================================================

add_action( 'wp_head', 'gano_output_breadcrumb_schema', 7 );
function gano_output_breadcrumb_schema(): void {
    // Solo en páginas internas (no homepage)
    if ( is_front_page() || is_home() ) {
        return;
    }
    // Rank Math ya genera breadcrumbs si está activo — evitar duplicar
    if ( class_exists( 'RankMath' ) ) {
        return;
    }

    $url      = get_site_url();
    $position = 1;
    $items    = array(
        array(
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => 'Inicio',
            'item'     => $url,
        ),
    );

    // Página actual
    $items[] = array(
        '@type'    => 'ListItem',
        'position' => $position,
        'name'     => wp_strip_all_tags( get_the_title() ),
        'item'     => get_permalink(),
    );

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    );

    echo "\n<!-- Gano Digital: Schema JSON-LD Breadcrumb -->\n";
    echo '<script type="application/ld+json">'
        . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
        . '</script>' . "\n";
}

// =============================================================================
// 5. RESOURCE HINTS — Preconnect a dominios críticos de terceros
//    Se emiten en prioridad 1 para procesarse lo antes posible por el navegador.
// =============================================================================

add_action( 'wp_head', 'gano_resource_hints', 1 );
function gano_resource_hints(): void {
    $hints = array(
        // Google Fonts (Elementor las carga habitualmente)
        array( 'href' => 'https://fonts.googleapis.com', 'rel' => 'preconnect' ),
        array( 'href' => 'https://fonts.gstatic.com',    'rel' => 'preconnect', 'crossorigin' => true ),

        // Wompi — necesario antes del checkout
        array( 'href' => 'https://checkout.wompi.co',    'rel' => 'preconnect' ),

        // Google Analytics / GTM — prefetch DNS si no es preconnect
        array( 'href' => 'https://www.google-analytics.com', 'rel' => 'dns-prefetch' ),
        array( 'href' => 'https://www.googletagmanager.com', 'rel' => 'dns-prefetch' ),

        // CDN de Google APIs (jQuery de Elementor)
        array( 'href' => 'https://ajax.googleapis.com',  'rel' => 'dns-prefetch' ),
    );

    echo "\n<!-- Gano Digital: Resource Hints -->\n";
    foreach ( $hints as $hint ) {
        $crossorigin = ! empty( $hint['crossorigin'] ) ? ' crossorigin' : '';
        echo '<link rel="' . esc_attr( $hint['rel'] ) . '" href="' . esc_url( $hint['href'] ) . '"' . $crossorigin . ">\n";
    }

    // LCP Hero Image preload (configurar URL en wp-admin → Ajustes → Gano SEO)
    $hero_url = get_option( 'gano_seo_hero_image', '' );
    if ( is_front_page() && ! empty( $hero_url ) ) {
        echo '<link rel="preload" as="image" href="' . esc_url( $hero_url ) . '" fetchpriority="high">' . "\n";
    }
}

// =============================================================================
// 6. GOOGLE SEARCH CONSOLE — Verification meta tag
//    Configura el código en: wp-admin → Ajustes → Gano SEO → Código GSC
// =============================================================================

/**
 * Sanitiza el token de verificación de Google Search Console.
 * Los tokens GSC son cadenas alfanuméricas (base64url sin padding).
 *
 * @param string $value Token crudo ingresado por el usuario.
 * @return string Token limpio (solo [A-Za-z0-9]).
 */
function gano_sanitize_gsc_token( string $value ): string {
    return (string) preg_replace( '/[^A-Za-z0-9]/', '', $value );
}

add_action( 'wp_head', 'gano_gsc_verification_meta', 2 );
function gano_gsc_verification_meta(): void {
    $code = gano_sanitize_gsc_token( get_option( 'gano_seo_gsc_verification', '' ) );
    if ( empty( $code ) ) {
        return;
    }
    echo '<meta name="google-site-verification" content="' . esc_attr( $code ) . '">' . "\n";
}

// =============================================================================
// 7. OPEN GRAPH + TWITTER CARD FALLBACK
//    Solo emite si Rank Math NO está activo (para no duplicar meta tags).
// =============================================================================

add_action( 'wp_head', 'gano_og_fallback', 3 );
function gano_og_fallback(): void {
    // Rank Math gestiona OG tags cuando está activo
    if ( class_exists( 'RankMath' ) ) {
        return;
    }

    $cfg         = gano_seo_config();
    $title       = wp_strip_all_tags( is_singular() ? get_the_title() : get_bloginfo( 'name' ) );
    $description = is_singular() ? ( get_the_excerpt() ?: $cfg['description'] ) : $cfg['description'];
    $description = wp_strip_all_tags( $description );
    $url         = is_singular() ? get_permalink() : $cfg['site_url'];
    $image       = is_singular() ? ( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: $cfg['logo_url'] ) : $cfg['logo_url'];
    $site_name   = $cfg['name'];

    echo "\n<!-- Gano Digital: Open Graph / Twitter Card -->\n";
    echo '<meta property="og:type"        content="' . ( is_singular() ? 'article' : 'website' ) . '">' . "\n";
    echo '<meta property="og:title"       content="' . esc_attr( $title )       . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta property="og:url"         content="' . esc_url( $url )          . '">' . "\n";
    echo '<meta property="og:image"       content="' . esc_url( $image )        . '">' . "\n";
    echo '<meta property="og:site_name"   content="' . esc_attr( $site_name )   . '">' . "\n";
    echo '<meta property="og:locale"      content="es_CO">' . "\n";
    echo '<meta name="twitter:card"       content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title"      content="' . esc_attr( $title )       . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta name="twitter:image"      content="' . esc_url( $image )        . '">' . "\n";
}

// =============================================================================
// 8. PÁGINA DE AJUSTES SEO en wp-admin (para que Diego configure los datos)
// =============================================================================

add_action( 'admin_menu', 'gano_seo_settings_menu' );
function gano_seo_settings_menu(): void {
    add_options_page(
        'Gano SEO — Datos del Negocio',
        'Gano SEO',
        'manage_options',
        'gano-seo-settings',
        'gano_seo_settings_page'
    );
}

add_action( 'admin_init', 'gano_seo_register_settings' );
function gano_seo_register_settings(): void {
    $fields = array(
        'gano_seo_business_type' => 'Tipo de negocio (organization o local_business)',
        'gano_seo_legal_name' => 'Nombre legal (ej: Gano Digital SAS)',
        'gano_seo_nit'        => 'NIT (ej: 900.123.456-7)',
        'gano_seo_phone'      => 'Teléfono (+57 300 123 4567)',
        'gano_seo_whatsapp'   => 'WhatsApp (solo número: 573001234567)',
        'gano_seo_email'      => 'Email de contacto',
        'gano_seo_street'     => 'Dirección (calle y número)',
        'gano_seo_city'       => 'Ciudad',
        'gano_seo_region'     => 'Departamento',
        'gano_seo_postal'     => 'Código postal',
        'gano_seo_logo'       => 'URL del logo (ruta completa)',
        'gano_seo_hero_image'        => 'URL del hero image (para preload LCP)',
        'gano_seo_founded'           => 'Año de fundación',
        'gano_seo_gsc_verification'  => 'Google Search Console — código de verificación',
    );
    foreach ( $fields as $option_name => $label ) {
        $callback = ( 'gano_seo_gsc_verification' === $option_name )
            ? 'gano_sanitize_gsc_token'
            : 'sanitize_text_field';
        register_setting( 'gano_seo_settings', $option_name, array( 'sanitize_callback' => $callback ) );
    }
}

function gano_seo_settings_page(): void {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( 'Sin permisos.' );
    }
    if ( isset( $_POST['submit'] ) ) {
        check_admin_referer( 'gano_seo_settings_save', '_gano_seo_nonce' );
        $fields = array(
            'gano_seo_business_type', 'gano_seo_legal_name', 'gano_seo_nit', 'gano_seo_phone', 'gano_seo_whatsapp',
            'gano_seo_email', 'gano_seo_street', 'gano_seo_city', 'gano_seo_region',
            'gano_seo_postal', 'gano_seo_logo', 'gano_seo_hero_image', 'gano_seo_founded',
            'gano_seo_gsc_verification',
        );
        foreach ( $fields as $f ) {
            $value = $f === 'gano_seo_gsc_verification'
                ? gano_sanitize_gsc_token( $_POST[ $f ] ?? '' )
                : sanitize_text_field( $_POST[ $f ] ?? '' );
            update_option( $f, $value );
        }
        echo '<div class="notice notice-success"><p>¡Ajustes guardados!</p></div>';
    }
    $cfg = gano_seo_config();
    ?>
    <div class="wrap">
        <h1>⚙️ Gano SEO — Datos del Negocio</h1>
        <p>Estos datos se usan en el Schema JSON-LD (Google, Bing) y en las etiquetas Open Graph.</p>
        <p><strong>Modelo de negocio digital:</strong> Gano Digital opera 100 % en línea (sin tienda física).
        El schema por defecto usa el tipo <code>Organization</code>, alineado con la configuración de Rank Math.
        Solo selecciona "Negocio local" si cuentas con un local físico verificable en Google My Business.</p>
        <form method="post">
            <?php wp_nonce_field( 'gano_seo_settings_save', '_gano_seo_nonce' ); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th><label for="gano_seo_business_type">Tipo de negocio</label></th>
                    <td>
                        <select id="gano_seo_business_type" name="gano_seo_business_type">
                            <option value="organization" <?php selected( $cfg['business_type'], 'organization' ); ?>>
                                Digital / Organización (sin local físico) — Recomendado
                            </option>
                            <option value="local_business" <?php selected( $cfg['business_type'], 'local_business' ); ?>>
                                Negocio local con local físico verificable
                            </option>
                        </select>
                        <p class="description">
                            Controla el <code>@type</code> del schema JSON-LD.
                            "Negocio local" requiere dirección física real y verificable.
                            En Rank Math: wizard → paso "Tipo de sitio" → seleccionar la opción equivalente.
                        </p>
                    </td>
                </tr>
                <?php
                $fields_labels = array(
                    'gano_seo_legal_name' => array( 'Nombre legal',    'Ej: Gano Digital SAS',     'legal_name' ),
                    'gano_seo_nit'        => array( 'NIT',              'Ej: 900.123.456-7',        'nit' ),
                    'gano_seo_phone'      => array( 'Teléfono',         '+57 300 123 4567',         'phone' ),
                    'gano_seo_whatsapp'   => array( 'WhatsApp',         '573001234567 (sin + ni espacios)', 'whatsapp' ),
                    'gano_seo_email'      => array( 'Email contacto',   'hola@gano.digital',        'email' ),
                    'gano_seo_street'     => array( 'Dirección física (opcional)', 'Solo si existe local verificable', 'address_street' ),
                    'gano_seo_city'       => array( 'Ciudad',           'Bogotá',                   'address_city' ),
                    'gano_seo_region'     => array( 'Departamento',     'Cundinamarca',             'address_region' ),
                    'gano_seo_postal'     => array( 'Código postal (opcional)', '110111',           'address_postal' ),
                    'gano_seo_logo'       => array( 'URL Logo',         get_site_url() . '/wp-content/uploads/logo.png', 'logo_url' ),
                    'gano_seo_hero_image' => array( 'URL Hero (LCP)',   'URL de la imagen principal del home', 'hero_image_url' ),
                    'gano_seo_founded'    => array( 'Año de fundación', '2024',                     'founded_year' ),
                );
                foreach ( $fields_labels as $opt => $meta ) :
                    [$label, $placeholder, $cfg_key] = $meta;
                    $current_val = get_option( $opt, $cfg[ $cfg_key ] ?? '' );
                ?>
                <tr>
                    <th><label for="<?php echo esc_attr( $opt ); ?>"><?php echo esc_html( $label ); ?></label></th>
                    <td>
                        <input
                            type="text"
                            id="<?php echo esc_attr( $opt ); ?>"
                            name="<?php echo esc_attr( $opt ); ?>"
                            value="<?php echo esc_attr( $current_val ); ?>"
                            placeholder="<?php echo esc_attr( $placeholder ); ?>"
                            class="regular-text"
                        >
                    </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <th><label for="gano_seo_gsc_verification">Google Search Console</label></th>
                    <td>
                        <input
                            type="text"
                            id="gano_seo_gsc_verification"
                            name="gano_seo_gsc_verification"
                            value="<?php echo esc_attr( get_option( 'gano_seo_gsc_verification', '' ) ); ?>"
                            placeholder="Token de verificación (43–44 caracteres, p.ej. AbCdEfGhIj1234567890AbCdEfGhIj1234567890abc)"
                            class="regular-text"
                        >
                        <p class="description">Solo el token (sin etiqueta HTML). Se emite como <code>&lt;meta name="google-site-verification"&gt;</code> en el <code>&lt;head&gt;</code>. Obtenerlo en: Search Console → Añadir propiedad → Etiqueta HTML.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Guardar ajustes SEO', 'primary', 'submit' ); ?>
        </form>
        <hr>
        <h2>📋 Rank Math — Pasos del wizard completados</h2>
        <ul>
            <li>✅ Plugin Rank Math instalado y activo.</li>
            <li>⏳ <strong>Pendiente:</strong> Ejecutar Setup Wizard → wp-admin → Rank Math → Dashboard → "Re-run Setup Wizard".</li>
            <li>⏳ <strong>Paso clave:</strong> Tipo de sitio → "Empresa/Organización" (NO "Negocio local").</li>
            <li>⏳ <strong>Paso clave:</strong> No rellenar dirección física — negocio 100 % digital.</li>
            <li>⏳ <strong>Paso clave:</strong> Agregar código de Google Search Console.</li>
            <li>⏳ <strong>Paso clave:</strong> Activar Sitemap XML → incluir Páginas, Posts, Productos.</li>
        </ul>
        <hr>
        <h2>🔍 Vista previa del Schema JSON-LD</h2>
        <p>Valida el schema en <a href="https://search.google.com/test/rich-results" target="_blank" rel="noopener">Google Rich Results Test</a> y <a href="https://validator.schema.org/" target="_blank" rel="noopener">Schema.org Validator</a>.</p>
        <p><strong>URL a probar:</strong> <a href="<?php echo esc_url( get_site_url() ); ?>" target="_blank"><?php echo esc_url( get_site_url() ); ?></a></p>
    </div>
    <?php
}
