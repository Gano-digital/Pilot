<?php
/**
 * Plugin Name: Gano Digital — Content Hub Importer v2.0
 * Description: Importa automáticamente las 20 páginas del Hub de Innovación SOTA con animaciones, mejor UX y engagement. Elimínalo tras la activación.
 * Version: 2.0.0
 * Author: Gano Digital Dev
 * Requires: WordPress 5.9+, PHP 7.4+
 * License: GPL v2+
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Define constants
define( 'GANO_CONTENT_IMPORTER_VERSION', '2.0.0' );
define( 'GANO_CONTENT_IMPORTER_PATH', plugin_dir_path( __FILE__ ) );

// ============================================================================
// ACTIVATION HOOK & EXECUTION
// ============================================================================

register_activation_hook( __FILE__, 'gano_import_content_hub_v2' );

function gano_import_content_hub_v2() {
    if ( ! current_user_can( 'activate_plugins' ) ) {
        wp_die( 'No tienes permisos para activar plugins.' );
    }

    $pages = gano_get_pages_data_v2();
    $imported_count = 0;
    $error_count = 0;

    foreach ( $pages as $page ) {
        $exists = get_page_by_path( sanitize_title( $page['title'] ), OBJECT, 'page' );
        if ( $exists ) {
            continue;
        }

        $safe_content = wp_kses_post( $page['content'] );

        $post_id = wp_insert_post( [
            'post_title'   => wp_strip_all_tags( $page['title'] ),
            'post_content' => $safe_content,
            'post_status'  => 'draft',
            'post_type'    => 'page',
            'post_author'  => 1,
            'meta_input'   => [
                '_wp_page_template'        => 'elementor_canvas',
                '_gano_sota_category'      => sanitize_key( $page['category'] ),
                '_gano_sota_version'       => GANO_CONTENT_IMPORTER_VERSION,
                '_gano_sota_created_date'  => current_time( 'mysql' ),
            ],
        ] );

        if ( is_wp_error( $post_id ) ) {
            $error_count++;
            continue;
        }

        if ( ! empty( $page['feature_img_name'] ) ) {
            gano_attach_image_by_filename_v2( $page['feature_img_name'], $post_id );
        }

        do_action( 'gano_sota_page_created', $post_id, $page );
        $imported_count++;
    }

    // Create the SOTA Hub page if it doesn't exist yet.
    $hub_id = gano_create_sota_hub_page();

    update_option( 'gano_sota_import_stats', [
        'version'       => GANO_CONTENT_IMPORTER_VERSION,
        'imported'      => $imported_count,
        'errors'        => $error_count,
        'hub_page_id'   => $hub_id,
        'timestamp'     => current_time( 'mysql' ),
    ] );
}

/**
 * Creates the Hub de Innovación SOTA page as a draft if it does not exist.
 *
 * @return int|false Post ID on success, false on failure.
 */
function gano_create_sota_hub_page() {
    $existing = get_page_by_path( 'hub-sota', OBJECT, 'page' );
    if ( $existing ) {
        return $existing->ID;
    }

    $admin_user = get_users( [ 'role' => 'administrator', 'number' => 1, 'fields' => 'ID' ] );
    $author_id  = ! empty( $admin_user ) ? (int) $admin_user[0] : 1;

    $post_id = wp_insert_post( [
        'post_title'   => 'Hub de Innovación SOTA',
        'post_name'    => 'hub-sota',
        'post_content' => '',
        'post_status'  => 'draft',
        'post_type'    => 'page',
        'post_author'  => $author_id,
        'meta_input'   => [
            '_wp_page_template'       => 'templates/page-sota-hub.php',
            '_gano_sota_hub'          => '1',
            '_gano_sota_version'      => GANO_CONTENT_IMPORTER_VERSION,
            '_gano_sota_created_date' => current_time( 'mysql' ),
        ],
    ] );

    if ( is_wp_error( $post_id ) ) {
        error_log( 'gano-content-importer: error creating hub page — ' . $post_id->get_error_message() );
        return false;
    }

    return $post_id;
}

register_deactivation_hook( __FILE__, 'gano_content_importer_deactivate' );

function gano_content_importer_deactivate() {
    delete_option( 'gano_sota_import_stats' );
}

function gano_attach_image_by_filename_v2( $filename, $post_id ) {
    if ( empty( $filename ) ) return false;
    global $wpdb;
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' AND post_title LIKE %s LIMIT 1", '%' . $wpdb->esc_like( $filename ) . '%' ) );
    if ( ! empty( $results ) ) {
        set_post_thumbnail( $post_id, $results[0]->ID );
        return true;
    }
    return false;
}

add_action( 'wp_enqueue_scripts', 'gano_enqueue_sota_styles' );

function gano_enqueue_sota_styles() {
    if ( ! is_singular( 'page' ) ) return;
    $post = get_post();
    if ( ! $post || get_post_meta( $post->ID, '_gano_sota_category', true ) === '' ) return;

    wp_enqueue_style( 'gano-sota-animations', get_stylesheet_directory_uri() . '/gano-sota-animations.css', [], GANO_CONTENT_IMPORTER_VERSION );
}

function gano_get_cta_url( $category = '' ) {
    $base_url = get_option( 'gano_sota_cta_base_url', '/contacto' );
    return esc_url( apply_filters( 'gano_sota_cta_url', $base_url, sanitize_key( $category ) ) );
}

function gano_get_pages_data_v2() {
    $cta_base = gano_get_cta_url();

    return [
        // PAGE 1 — NVMe Architecture
        [
            'title'            => 'Arquitectura NVMe: El Manifiesto de la Velocidad Crítica',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_nvme_speed.png',
            'cta_url'          => $cta_base . '#nvme',
            'stats'            => [['label' => 'Latencia IOPS', 'value' => 'Récord'], ['label' => 'Soberanía Técnica', 'value' => 'Total']],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Arquitectura NVMe">
<h1>⚡ Manifiesto NVMe: Ingeniería para la Latencia Cero</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>La infraestructura convencional se asfixia en el protocolo SATA. Nuestra arquitectura NVMe Gen4 se comunica directamente con el procesador, eliminando el cuello de botella más crítico de la web moderna.</p>
</div>
<section>
<h2>🧠 Anatomía del Estado del Arte</h2>
<ul role="list">
<li><strong>Instrucciones Paralelas:</strong> Capacidad de procesar miles de colas de comandos simultáneos.</li>
<li><strong>Transferencia Directa:</strong> Eliminamos las capas de abstracción heredadas.</li>
<li><strong>Resiliencia de Hardware:</strong> Almacenamiento diseñado para ciclos de escritura intensivos.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"El almacenamiento no es un commodity; es el sistema circulatorio de tu soberanía digital."</em></p>
</div>
<section>
<h2>🛠️ Integración en tu Ecosistema</h2>
<p>La certificación <strong>NVMe-SOTA</strong> es nuestra base de ingeniería en el ecosistema <strong>Núcleo Prime</strong>.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary">🚀 Activar Ingeniería NVMe</a>
</div>
</article>',
        ],

        // PAGE 2 — Zero-Trust Security
        [
            'title'            => 'Zero-Trust: El Fin de la Confianza Implícita',
            'category'         => 'seguridad',
            'feature_img_name' => 'icon_zero_trust_security.png',
            'cta_url'          => $cta_base . '#security',
            'stats'            => [['label' => 'Protocolo', 'value' => 'SOTA'], ['label' => 'Autenticación', 'value' => 'Multicapa']],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Zero-Trust Security">
<h1>🛡️ Manifiesto Zero-Trust: Nunca Confiar, Siempre Verificar</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>El perímetro tradicional ya no existe. En Gano Digital, cada solicitud es tratada como una amenaza potencial hasta que se demuestre lo contrario mediante criptografía avanzada.</p>
</div>
<section>
<h2>🧠 La Filosofía de Inmunidad Digital</h2>
<ul role="list">
<li><strong>Identidad Monolítica:</strong> Eliminamos las contraseñas estáticas por tokens dinámicos.</li>
<li><strong>Micro-Segmentación:</strong> Cada componente de tu WordPress está aislado.</li>
<li><strong>Visibilidad Total:</strong> Auditoría en tiempo real de cada intento de acceso.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<div class="gano-quote-box" role="doc-pullquote">
<p>💬 <em>"La seguridad no es un muro; es un sistema inmunológico que aprende de cada ataque."</em></p>
</div>
<section>
<h2>🛠️ Blindaje en tu Ecosistema</h2>
<p>El protocolo Zero-Trust es la columna vertebral de nuestra <strong>Fortaleza Delta</strong>.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary">🔐 Blindar mi Activo Digital</a>
</div>
</article>',
        ],

        // PAGE 3 — Predictive AI Management
        [
            'title'            => 'IA Predictiva: La Mente que Anticipa el Fallo',
            'category'         => 'inteligencia-artificial',
            'feature_img_name' => 'icon_predictive_ai_server.png',
            'cta_url'          => $cta_base . '#ai',
            'stats'            => [['label' => 'MTTR', 'value' => 'Cero'], ['label' => 'Autonomía', 'value' => 'Total']],
            'content'          => '
<article class="gano-sota-page" role="main" aria-label="Gestión Predictiva con IA">
<h1>🤖 Inteligencia Predictiva: El Fin del Soporte Reaccionario</h1>
<div class="gano-hook-box" role="doc-introduction">
<p>Nuestros algoritmos auditan millones de puntos de datos en tiempo real para predecir anomalías antes de que afecten tu facturación.</p>
</div>
<section>
<h2>🧠 Arquitectura de Auto-Cura SOTA</h2>
<ul role="list">
<li><strong>Detección Temprana:</strong> Redes neuronales que identifican patrones de carga inusual.</li>
<li><strong>Optimización Autónoma:</strong> Redirección de recursos y purga de caché inteligente.</li>
<li><strong>Soporte Proactivo:</strong> Intervención antes de que tú notes la incidencia.</li>
</ul>
</section>
<div class="gano-divider" aria-hidden="true"></div>
<section>
<h2>🛠️ El Agente IA en tu Bastión SOTA</h2>
<p>Administra tu infraestructura 24/7/365 con precisión quirúrgica en el <strong>Bastión SOTA</strong>.</p>
</section>
<div class="gano-cta-box">
<a href="' . esc_url( $cta_base ) . '" class="gano-btn-primary">🧠 Desplegar IA Predictiva</a>
</div>
</article>',
        ],

        // PAGE 4 — Digital Sovereignty
        [
            'title'            => 'Soberanía Digital: Jurisdicción y Control Total',
            'category'         => 'estrategia',
            'feature_img_name' => 'icon_sovereignty_data.png',
            'stats'            => [['label' => 'Jurisdicción', 'value' => 'LATAM'], ['label' => 'Control', 'value' => 'Total']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🌎 Manifiesto de Soberanía: El Poder de la Propiedad</h1>
<div class=\"gano-hook-box\">
<p>En el estado del arte, ceder el control de tus datos a jurisdicciones extranjeras es ceder el futuro de tu empresa. Gano Digital garantiza la soberanía absoluta de tu activo digital.</p>
</div>
<section>
<h2>🧠 Independencia Tecnológica</h2>
<ul role=\"list\">
<li><strong>Jurisdicción Soberana:</strong> Protección bajo leyes locales de privacidad y propiedad.</li>
<li><strong>Inmunidad de Datos:</strong> Tus activos no son sujetos de normativas externas intrusivas.</li>
<li><strong>Propiedad del Metal:</strong> Control total desde el hardware hasta la capa de aplicación.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🛡️ Reclamar Soberanía Digital</a>
</div>
</article>",
        ],

        // PAGE 5 — Headless WordPress
        [
            'title'            => 'Arquitectura Headless: Desacoplando el Futuro',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_headless_speed.png',
            'stats'            => [['label' => 'Lighthouse', 'value' => '100%'], ['label' => 'Flexibilidad', 'value' => 'Total']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>⚙️ Manifiesto Headless: Velocidad sin Límites</h1>
<div class=\"gano-hook-box\">
<p>Separamos el cerebro (gestión) del cuerpo (visualización) para entregar contenido a la velocidad de la luz mediante APIs modernas.</p>
</div>
<section>
<h2>🧠 Ingeniería de Alto Desempeño</h2>
<ul role=\"list\">
<li><strong>Seguridad por Diseño:</strong> Eliminamos los vectores de ataque tradicionales del frontend PHP.</li>
<li><strong>Experiencia Instantánea:</strong> Carga de milisegundos mediante Next.js o Astro.</li>
<li><strong>Escalabilidad Infinita:</strong> Diseñado para soportar millones de hits sin degradación.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🚀 Evolucionar a Headless</a>
</div>
</article>",
        ],

        // PAGE 6 — DDoS Immunity
        [
            'title'            => 'Inmunidad DDoS: Blindaje IA en el Perímetro',
            'category'         => 'seguridad',
            'feature_img_name' => 'icon_ddos_firewall.png',
            'stats'            => [['label' => 'Mitigación', 'value' => 'IA Activa'], ['label' => 'Falsos Positivos', 'value' => '0%']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🔥 Inmunidad ante el Caos: Blindaje DDoS SOTA</h1>
<div class=\"gano-hook-box\">
<p>Neutralizamos ataques volumétricos en el borde de la red, antes de que toquen tu infraestructura. Inmunidad garantizada.</p>
</div>
<section>
<h2>🧠 Inteligencia Perimetral</h2>
<ul role=\"list\">
<li><strong>Filtrado Heurístico:</strong> Diferenciamos clientes reales de bots maliciosos en microsegundos.</li>
<li><strong>Red Anycast Global:</strong> Distribuimos el impacto para que sea imperceptible.</li>
<li><strong>Uptime de Hierro:</strong> Blindaje permanente para los negocios más críticos.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🛡️ Activar Inmunidad Digital</a>
</div>
</article>",
        ],

        // PAGE 7 — Containers & Isolation
        [
            'title'            => 'Contenedores Aislados: Tu Isla de Rendimiento',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_shared_hosting_death.png',
            'stats'            => [['label' => 'Aislamiento', 'value' => 'Total'], ['label' => 'Recursos', 'value' => 'Dedicados']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🛡️ Manifiesto de Aislamiento: El Fin del Hosting Compartido</h1>
<div class=\"gano-hook-box\">
<p>Tus recursos son tuyos y solo tuyos. Eliminamos el riesgo de vecinos ruidosos mediante orquestación de contenedores de clase mundial.</p>
</div>
<section>
<h2>🧠 Ingeniería de Recursos Garantizados</h2>
<ul role=\"list\">
<li><strong>Sandbox Estricto:</strong> Ningún proceso externo puede afectar tu tiempo de respuesta.</li>
<li><strong>Seguridad Compartimentada:</strong> Aislamiento total de datos y ejecución.</li>
<li><strong>Potencia Predecible:</strong> Rendimiento constante, 24/7.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🚀 Activar Aislamiento Total</a>
</div>
</article>",
        ],

        // PAGE 8 — Edge Computing
        [
            'title'            => 'Edge Computing: Colapsando la Latencia Geográfica',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_edge_computing.png',
            'stats'            => [['label' => 'Latencia Local', 'value' => '< 10ms'], ['label' => 'Despliegue', 'value' => 'Borde']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🌐 Manifiesto del Borde: La Web donde está el Usuario</h1>
<div class=\"gano-hook\">
<p>Llevamos la ejecución y el contenido al nodo más cercano al visitante, eliminando la tiranía de la distancia física.</p>
</div>
<section>
<h2>🧠 Arquitectura de Proximidad</h2>
<ul role=\"list\">
<li><strong>Edge Workers:</strong> Lógica de negocio ejecutada en milisegundos.</li>
<li><strong>Fastest Routing:</strong> Algoritmos que encuentran el camino más corto al usuario.</li>
<li><strong>Cache Predictivo:</strong> Distribución inteligente de activos críticos.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">⚡ Desplegar en el Edge</a>
</div>
</article>",
        ],

        // PAGE 9 — Green Infrastructure
        [
            'title'            => 'Ingeniería Sostenible: Rendimiento con Conciencia',
            'category'         => 'estrategia',
            'feature_img_name' => 'icon_green_hosting.png',
            'stats'            => [['label' => 'Energía', 'value' => 'Renovable'], ['label' => 'Huella', 'value' => 'Neutral']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🌱 Manifiesto Sostenible: Potencia para un Futuro Consciente</h1>
<div class=\"gano-hook-box\">
<p>La tecnología SOTA es inherentemente eficiente. Operamos bajo estándares de consumo energético neutral para un impacto planetario positivo.</p>
</div>
<section>
<h2>🧠 Eficiencia con Propósito</h2>
<ul role=\"list\">
<li><strong>Ciclo Termodinámico Optimizado:</strong> Enfriamiento inteligente y hardware eficiente.</li>
<li><strong>Soberanía Ecológica:</strong> Tu infraestructura digital es aliada del medio ambiente.</li>
<li><strong>Certificación Green:</strong> Valor diferencial para marcas con propósito.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🌿 Activar Ecosistema Verde</a>
</div>
</article>",
        ],

        // PAGE 10 — Quantum-Safe Encryption
        [
            'title'            => 'Blindaje Post-Cuántico: Cifrado para la Próxima Década',
            'category'         => 'seguridad',
            'feature_img_name' => 'icon_quantum_encryption.png',
            'stats'            => [['label' => 'Inmunidad', 'value' => 'Cuántica'], ['label' => 'Resiliencia', 'value' => 'Futura']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🔐 Inmunidad Cuántica: Protegiendo el Mañana Hoy</h1>
<div class=\"gano-hook-box\">
<p>Implementamos algoritmos resistentes a la computación cuántica, asegurando que tus secretos corporativos permanezcan privados para siempre.</p>
</div>
<section>
<h2>🧠 Criptografía de Vanguardia</h2>
<ul role=\"list\">
<li><strong>Lattice-based Security:</strong> Estándares de cifrado irrompibles por IA o computación cuántica.</li>
<li><strong>Privacidad Persistente:</strong> Blindaje de datos a largo plazo frente a futuras amenazas.</li>
<li><strong>Confianza Total:</strong> Tu soberanía digital protegida por la matemática más avanzada.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🛡️ Activar Blindaje Cuántico</a>
</div>
</article>",
        ],

        // PAGE 11 — CI/CD Pipeline
        [
            'title'            => 'Orquestación CI/CD: Evolución sin Interrupción',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_cicd_automation.png',
            'stats'            => [['label' => 'Despliegues', 'value' => 'Automáticos'], ['label' => 'Error Rate', 'value' => '0.01%']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>⚙️ Manifiesto CI/CD: El Ciclo de Innovación Perpetua</h1>
<div class=\"gano-hook-box\">
<p>Eliminamos el factor de error humano en los despliegues. Tu código fluye desde el desarrollo hasta la producción de forma atómica y segura.</p>
</div>
<section>
<h2>🧠 Ingeniería de Despliegue Continuo</h2>
<ul role=\"list\">
<li><strong>Tests Automatizados:</strong> Validación de integridad antes de cada cambio.</li>
<li><strong>Zero Downtime:</strong> Actualizaciones invisibles para el usuario final.</li>
<li><strong>Rollback Instantáneo:</strong> Seguridad absoluta en cada iteración.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🚀 Activar Ingeniería CI/CD</a>
</div>
</article>",
        ],

        // PAGE 12 — Real-Time Backups
        [
            'title'            => 'Resiliencia de Datos: Máquina del Tiempo Digital',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_backup_time_machine.png',
            'stats'            => [['label' => 'RPO', 'value' => '5 min'], ['label' => 'Ubicaciones', 'value' => 'Tri-redundante']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>⏮️ Manifiesto de Continuidad: Tu Información es Inmortal</h1>
<div class=\"gano-hook-box\">
<p>En el estado del arte, los incidentes son lecciones, no desastres. Nuestra replicación continua garantiza la supervivencia absoluta de tus activos.</p>
</div>
<section>
<h2>🧠 Ingeniería de Respaldo Premium</h2>
<ul role=\"list\">
<li><strong>Snapshots de Bloques:</strong> Replicación en tiempo real a nivel de infraestructura.</li>
<li><strong>Recuperación Quirúrgica:</strong> Vuelve a cualquier punto del tiempo en milisegundos.</li>
<li><strong>Georedundancia:</strong> Tus datos están seguros en múltiples puntos soberanos.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">💾 Activar Resiliencia Total</a>
</div>
</article>",
        ],

        // PAGE 13 — UX Acceleration
        [
            'title'            => 'Skeleton Screens: Psicología del Rendimiento',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_skeleton_screens.png',
            'stats'            => [['label' => 'UX Score', 'value' => 'Elite'], ['label' => 'Ansiedad Carga', 'value' => '0%']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🎭 Manifiesto de Percepción: Velocidad que se Siente</h1>
<div class=\"gano-hook-box\">
<p>Optimizamos la experiencia subjetiva mediante estructuras visuales instantáneas que pre-abastecen la mente del usuario.</p>
</div>
<section>
<h2>🧠 Ingeniería de Interfaz Fluida</h2>
<ul role=\"list\">
<li><strong>Carga Progresiva SOTA:</strong> Contenido disponible antes de que la página termine su proceso.</li>
<li><strong>Placeholders Inteligentes:</strong> Mantén el flujo visual y reduce el rebote.</li>
<li><strong>Optimización de Fricción:</strong> Diseño centrado en la continuidad cognitiva.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🎭 Activar Aceleración UX</a>
</div>
</article>",
        ],

        // PAGE 14 — Elastic Scaling
        [
            'title'            => 'Escalamiento Elástico: El Ecosistema Infinito',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_elastic_scaling.png',
            'stats'            => [['label' => 'Rango Scaling', 'value' => 'Ilimitado'], ['label' => 'Activación', 'value' => '< 30s']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>📈 Manifiesto de Elasticidad: Sobreviviendo al Éxito Viral</h1>
<div class=\"gano-hook-box\">
<p>Tu infraestructura se expande y contrae como un organismo vivo, respondiendo instantáneamente a la demanda del mercado.</p>
</div>
<section>
<h2>🧠 Arquitectura de Auto-Expansión</h2>
<ul role=\"list\">
<li><strong>Orquestación Kubernetes:</strong> Gestión de picos de carga mediante nodos dinámicos.</li>
<li><strong>Soberanía de Recursos:</strong> Capacidad infinita bajo demanda estratégica.</li>
<li><strong>Eficiencia SOTA:</strong> Inversión optimizada automáticamente según el flujo de tráfico.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">📈 Activar Escalamiento Pro</a>
</div>
</article>",
        ],

        // PAGE 15 — Self-Healing
        [
            'title'            => 'Self-Healing: Resiliencia Autónoma',
            'category'         => 'inteligencia-artificial',
            'feature_img_name' => 'icon_self_healing.png',
            'stats'            => [['label' => 'MTTR', 'value' => '< 2 min'], ['label' => 'Falla Manual', 'value' => '0%']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🏥 Manifiesto de Autocuración: La Infraestructura Viva</h1>
<div class=\"gano-hook-box\">
<p>Eliminamos la intervención humana en la recuperación de fallos. El sistema diagnostica, aísla y repara anomalías en tiempo real.</p>
</div>
<section>
<h2>🧠 Ingeniería de Inmunidad Digital</h2>
<ul role=\"list\">
<li><strong>Remediación IA:</strong> Protocolos automáticos que restauran servicios en segundos.</li>
<li><strong>Prevención Activa:</strong> Identificación de patrones de degradación pre-fallo.</li>
<li><strong>Soberanía Operativa:</strong> Tu negocio continúa operando mientras tú descansas.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🏥 Activar Autocuración SOTA</a>
</div>
</article>",
        ],

        // PAGE 16 — Kinetic Experiences
        [
            'title'            => 'Experiencias Cinéticas: Diseño que se Siente Premium',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_microanimations.png',
            'stats'            => [['label' => 'Engagement', 'value' => '+45%'], ['label' => 'Fluidez', 'value' => '60fps']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>✨ Manifiesto de Cinética UX: La Belleza de lo Fluido</h1>
<div class=\"gano-hook-box\">
<p>En el estado del arte, la interfaz debe responder con elegancia háptica. Las micro-interacciones cinéticas elevan tu marca a un estándar de élite.</p>
</div>
<section>
<h2>🧠 Ingeniería de Interacción Sensorial</h2>
<ul role=\"list\">
<li><strong>Transiciones Orgánicas:</strong> Animaciones que guían la mirada y la atención del usuario.</li>
<li><strong>Feedback Háptico Digital:</strong> Cada acción tiene una respuesta visual coherente y premium.</li>
<li><strong>Ritmo de Marca:</strong> Coherencia estética en cada micro-movimiento del ecosistema.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🎨 Activar Experiencia Cinética</a>
</div>
</article>",
        ],

        // PAGE 17 — Advanced Protocol Delivery
        [
            'title'            => 'Protocolos de Vanguardia: HTTP/3 & QUIC Transmisión',
            'category'         => 'rendimiento',
            'feature_img_name' => 'icon_http3_quic.png',
            'stats'            => [['label' => 'Handshake', 'value' => '0-RTT'], ['label' => 'Velocidad', 'value' => 'Ultra']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🚀 Manifiesto de Transmisión: Rompiendo la Barrera del TCP</h1>
<div class=\"gano-hook-box\">
<p>Implementamos el protocolo HTTP/3 para asegurar una entrega de datos inmune a la congestión de red, optimizada para la movilidad global.</p>
</div>
<section>
<h2>🧠 Ingeniería de Conectividad Futurista</h2>
<ul role=\"list\">
<li><strong>QUIC Protocol:</strong> Conexiones persistentes y resilientes en cualquier entorno de red.</li>
<li><strong>Multiplexión sin Bloqueo:</strong> Los datos fluyen de forma independiente y paralela.</li>
<li><strong>Carga Instantánea Móvil:</strong> Rendimiento SOTA en dispositivos de baja latencia.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">⚡ Activar Protocolos SOTA</a>
</div>
</article>",
        ],

        // PAGE 18 — Enterprise Indestructibility
        [
            'title'            => 'Arquitectura Indestructible: Alta Disponibilidad Enterprise',
            'category'         => 'infraestructura',
            'feature_img_name' => 'icon_ha_infrastructure.png',
            'stats'            => [['label' => 'Uptime SLA', 'value' => '99.99%'], ['label' => 'Redundancia', 'value' => '∞']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🔗 Manifiesto de Invulnerabilidad: Tu Legado no tiene Off-switch</h1>
<div class=\"gano-hook-box\">
<p>Diseñamos infraestructuras hiper-disponibles donde la falla de un componente activa instantáneamente un ecosistema de respaldo gemelo.</p>
</div>
<section>
<h2>🧠 Orquestación de Negocio Continuo</h2>
<ul role=\"list\">
<li><strong>Escalamiento Multi-Región:</strong> Tu activo digital vive en múltiples puntos soberanos simultáneamente.</li>
<li><strong>Geo-Failover Inteligente:</strong> Respuesta automática ante desastres de infraestructura masiva.</li>
<li><strong>Garantía de Soberanía:</strong> Continuidad absoluta para operaciones que no permiten el error.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🛡️ Activar Arquitectura Indestructible</a>
</div>
</article>",
        ],

        // PAGE 19 — Privacy Sovereignty
        [
            'title'            => 'Soberanía de Datos: Analytics Privado Server-Side',
            'category'         => 'estrategia',
            'feature_img_name' => 'icon_serverside_analytics.png',
            'stats'            => [['label' => 'Privacidad', 'value' => 'GDPR/SOTA'], ['label' => 'Scripts Terceros', 'value' => '0']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>📊 Manifiesto de Inteligencia Privada: Datos de tu Propiedad</h1>
<div class=\"gano-hook-box\">
<p>Captura inteligencia de mercado vital sin comprometer la privacidad del usuario ni depender de scripts de terceros invasivos.</p>
</div>
<section>
<h2>🧠 Ingeniería de Datos Soberanos</h2>
<ul role=\"list\">
<li><strong>Analytics Invisible:</strong> Procesamiento en el lado del servidor para máxima velocidad y precisión.</li>
<li><strong>Propiedad del Insight:</strong> Tus datos de comportamiento no son compartidos con gigantes tecnológicos.</li>
<li><strong>Transparencia Ética:</strong> Construye confianza de marca mediante el respeto absoluto a la privacidad.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🔒 Reclamar Soberanía de Datos</a>
</div>
</article>",
        ],

        // PAGE 20 — Sovereign AI Co-Pilot
        [
            'title'            => 'Co-Piloto de IA Soberana: Administración por Conversación',
            'category'         => 'inteligencia-artificial',
            'feature_img_name' => 'icon_ai_agent_admin.png',
            'stats'            => [['label' => 'Respuesta', 'value' => 'Instantánea'], ['label' => 'Idioma', 'value' => 'Español']],
            'content'          => "
<article class=\"gano-sota-page\" role=\"main\">
<h1>🤖 Manifiesto de Control Natural: El Fin de la Línea de Comandos</h1>
<div class=\"gano-hook-box\">
<p>Administra tu arquitectura soberana mediante lenguaje natural. Nuestro Agente IA entiende tus objetivos y los ejecuta con precisión técnica.</p>
</div>
<section>
<h2>🧠 Orquestación Inteligente SOTA</h2>
<ul role=\"list\">
<li><strong>DevOps Conversacional:</strong> Optimiza, escala o repara tu infraestructura mediante un chat seguro.</li>
<li><strong>Prevención Heurística:</strong> El agente detecta y sugiere mejoras antes de que las necesites.</li>
<li><strong>IA de Grado Soberano:</strong> Ejecución interna y privada, alineada con los valores de tu negocio.</li>
</ul>
</section>
<div class=\"gano-cta-box\">
<a href=\"" . esc_url( $cta_base ) . "\" class=\"gano-btn-primary\">🧠 Activar Co-Piloto Soberano</a>
</div>
</article>",
        ],
    ];
}
