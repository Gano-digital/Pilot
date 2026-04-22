<?php
/**
 * Gano Digital — Bloques de homepage (shortcodes)
 *
 * Proporciona shortcodes para insertar en Elementor (widget Código corto):
 *
 *   [gano_hero]               → Hero completo: imagen, copy final y CTAs enlazados.
 *   [gano_socio_tecnologico]  → Bloque "Un socio tecnológico que trabaja en silencio."
 *   [gano_metrics]            → Franja de métricas SLA (cifras prudentes, sin inflar).
 *
 * Copy de referencia: memory/content/homepage-copy-2026-04.md
 * Clases CSS:         style.css → .gano-el-prose-narrow, .gano-el-metrics, .gano-el-metric
 *
 * Fix issue #222: registra hero_digital_garden.png (o hero-datacenter.jpg como fallback)
 * como attachment de WordPress para que el widget Imagen de Elementor obtenga un ID válido.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// =============================================================================
// HERO IMAGE — registro de attachment y fallback
//
// Problema: Elementor guarda {"id": 0, "url": "…hero_digital_garden.png"} en
// _elementor_data; el ID 0 provoca imagen rota en el widget de imagen.
//
// Solución: en 'init' buscamos si hero_digital_garden.png ya está registrado en
// la biblioteca de medios. Si no existe, intentamos registrarlo desde uploads
// (si el archivo está en disco). Si tampoco está en disco, usamos hero-datacenter.jpg
// del tema como fallback permanente. El ID resultante se guarda en wp_options.
//
// Uso en Elementor: después de correr este código una vez, el admin puede editar
// el widget de imagen del hero y usar la opción "Seleccionar" → buscar la imagen
// registrada, o directamente actualizar el ID en el JSON de Elementor a través
// de WP-CLI: wp post meta update 1657 _elementor_data '<json_actualizado>'.
// =============================================================================

add_action( 'init', 'gano_register_hero_attachment' );

/**
 * Registra la imagen del hero como attachment de WordPress si aún no lo está.
 *
 * Prioridad de búsqueda:
 *   1. Attachment existente con título/nombre 'hero_digital_garden'.
 *   2. Archivo hero_digital_garden.png en el directorio de uploads de WP.
 *   3. Archivo hero-datacenter.jpg embebido en el tema (fallback seguro).
 *
 * El ID resultante se almacena en la option 'gano_hero_image_id' para que
 * gano_get_hero_image_id() lo devuelva sin hacer una consulta DB en cada request.
 *
 * @return void
 */
function gano_register_hero_attachment(): void {
    // Solo correr si el ID aún no está guardado o si el attachment fue eliminado.
    $stored_id = (int) get_option( 'gano_hero_image_id', 0 );
    if ( $stored_id > 0 && 'attachment' === get_post_type( $stored_id ) ) {
        return; // Ya tenemos un attachment válido.
    }

    // 1. Buscar attachment existente por slug (post_name).
    $existing = get_posts( array(
        'post_type'      => 'attachment',
        'name'           => 'hero_digital_garden',
        'posts_per_page' => 1,
        'post_status'    => 'inherit',
        'fields'         => 'ids',
    ) );
    if ( ! empty( $existing ) ) {
        update_option( 'gano_hero_image_id', (int) $existing[0] );
        return;
    }

    // 2. Buscar por título (el archivo puede haberse subido con nombre distinto).
    $by_title = get_posts( array(
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => '_wp_attachment_metadata',
                'compare' => 'EXISTS',
            ),
        ),
        's'              => 'hero_digital_garden',
    ) );
    if ( ! empty( $by_title ) ) {
        update_option( 'gano_hero_image_id', (int) $by_title[0] );
        return;
    }

    // 3. Intentar registrar desde disco: primero hero_digital_garden.png en uploads.
    $upload_dir = wp_upload_dir();
    $candidates = array(
        // Candidato A — archivo esperado en uploads raíz.
        $upload_dir['basedir'] . '/hero_digital_garden.png',
        // Candidato B — directorio del año/mes actual.
        $upload_dir['path'] . '/hero_digital_garden.png',
        // Candidato C — fallback local del tema (siempre disponible en el repo).
        get_stylesheet_directory() . '/assets/images/hero-datacenter.jpg',
    );

    foreach ( $candidates as $file_path ) {
        if ( ! file_exists( $file_path ) ) {
            continue;
        }

        $filetype  = wp_check_filetype( basename( $file_path ), null );
        $mime_type = $filetype['type'] ?? '';

        // Solo aceptar JPEG / PNG / WebP.
        if ( ! in_array( $mime_type, array( 'image/jpeg', 'image/png', 'image/webp' ), true ) ) {
            continue;
        }

        // Determinar la URL pública del archivo.
        if ( str_starts_with( $file_path, $upload_dir['basedir'] ) ) {
            $file_url = $upload_dir['baseurl'] . substr( $file_path, strlen( $upload_dir['basedir'] ) );
        } else {
            // Es un archivo del tema; usar la URL del tema.
            $theme_dir = get_stylesheet_directory();
            $file_url  = get_stylesheet_directory_uri() . substr( $file_path, strlen( $theme_dir ) );
        }

        $attachment = array(
            'post_mime_type' => $mime_type,
            'post_title'     => sanitize_file_name( basename( $file_path ) ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        );

        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attachment_id = wp_insert_attachment( $attachment, $file_path );

        if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
            $attach_data = wp_generate_attachment_metadata( $attachment_id, $file_path );
            wp_update_attachment_metadata( $attachment_id, $attach_data );
            update_option( 'gano_hero_image_id', $attachment_id );
            return;
        }
    }
}

/**
 * Devuelve el attachment_id del hero registrado en wp_options.
 * Fallback: 0 (indica que no hay ID válido todavía).
 *
 * @return int Attachment ID o 0.
 */
function gano_get_hero_image_id(): int {
    return absint( get_option( 'gano_hero_image_id', 0 ) );
}

/**
 * Devuelve la URL de la imagen del hero:
 *   - La URL del attachment registrado si hay un ID válido.
 *   - La imagen hero-datacenter.jpg del tema como fallback.
 *
 * @param string $size Tamaño de imagen de WordPress (default: 'full').
 * @return string URL absoluta de la imagen.
 */
function gano_get_hero_image_url( string $size = 'full' ): string {
    $id = gano_get_hero_image_id();
    if ( $id > 0 ) {
        $src = wp_get_attachment_image_src( $id, $size );
        if ( ! empty( $src[0] ) ) {
            return esc_url( $src[0] );
        }
    }
    // Fallback: imagen local del tema (siempre disponible).
    return esc_url( get_stylesheet_directory_uri() . '/assets/images/hero-datacenter.jpg' );
}

// =============================================================================
// Shortcode: [gano_hero]
//
// Uso en Elementor: widget "Código corto" → pega [gano_hero]
// O en cualquier página/template WordPress.
//
// Renderiza el hero completo con:
//   - Imagen (attachment_id válido o fallback hero-datacenter.jpg)
//   - Headline H1 y subheadline (copy final de homepage-copy-2026-04.md)
//   - CTA primario → #ecosistemas
//   - CTA secundario → /contacto/
//   - Microcopy: "NVMe · Monitoreo proactivo · Facturación en COP"
//
// Clases CSS: gano-el-stack, gano-el-layer-top, gano-el-layer-base,
//             gano-el-hero-readability, gano-el-hero-microcopy (style.css).
// =============================================================================

add_shortcode( 'gano_hero', 'gano_render_hero' );

/**
 * Renderiza el bloque hero completo con imagen válida y CTAs enlazados.
 *
 * @return string HTML del hero listo para Elementor o cualquier template.
 */
function gano_render_hero(): string {
    $hero_img_url = gano_get_hero_image_url( 'large' );
    $hero_img_id  = gano_get_hero_image_id();

    // Alt text: del attachment si está registrado, fallback descriptivo.
    $alt_text = '';
    if ( $hero_img_id > 0 ) {
        $alt_text = (string) get_post_meta( $hero_img_id, '_wp_attachment_image_alt', true );
    }
    if ( '' === $alt_text ) {
        $alt_text = __( 'Infraestructura NVMe de Gano Digital — datacenter moderno', 'gano-child' );
    }

    ob_start();
    ?>
    <div class="gano-el-stack gano-hero-block" id="gano-main-content">
        <div class="gano-el-layer-top gano-hero-block__copy">
            <h1 class="gano-el-hero-readability">
                <?php esc_html_e( 'Infraestructura NVMe blindada y un agente IA que trabaja antes de que algo falle.', 'gano-child' ); ?>
            </h1>
            <p class="gano-hero-block__sub">
                <?php esc_html_e( 'Hosting WordPress de alto rendimiento con seguridad de nivel empresarial, soporte en español y operación en pesos colombianos. Menos ruido, más continuidad.', 'gano-child' ); ?>
            </p>
            <div class="gano-hero-block__cta-row">
                <a href="#ecosistemas"
                   class="gano-btn gano-btn--primary">
                    <?php esc_html_e( 'Ver arquitecturas y planes', 'gano-child' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>"
                   class="gano-btn gano-btn--secondary">
                    <?php esc_html_e( 'Hablar con el equipo', 'gano-child' ); ?>
                </a>
            </div>
            <p class="gano-el-hero-microcopy">
                <?php esc_html_e( 'NVMe · Monitoreo proactivo · Facturación en COP', 'gano-child' ); ?>
            </p>
        </div>
        <div class="gano-el-layer-base gano-hero-block__img" aria-hidden="true">
            <img src="<?php echo esc_url( $hero_img_url ); ?>"
                 alt="<?php echo esc_attr( $alt_text ); ?>"
                 class="gano-hero-block__img-el"
                 loading="eager"
                 fetchpriority="high">
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// =============================================================================
// Shortcode: [gano_socio_tecnologico]
// Uso en Elementor: widget "Código corto" → pega [gano_socio_tecnologico]
// Clase CSS aplicada: gano-el-prose-narrow (max-width 42 rem, centrado).
// =============================================================================

add_shortcode( 'gano_socio_tecnologico', 'gano_render_socio_tecnologico' );

/**
 * Renderiza el bloque "Un socio tecnológico que trabaja en silencio."
 *
 * @return string HTML escapado listo para Elementor.
 */
function gano_render_socio_tecnologico(): string {
    ob_start();
    ?>
    <div class="gano-el-prose-narrow gano-socio-block">
        <h2 class="gano-socio-block__titulo">
            <?php esc_html_e( 'Un socio tecnológico que trabaja en silencio.', 'gano-child' ); ?>
        </h2>
        <p class="gano-socio-block__parrafo">
            <?php
            /* translators: texto promocional del bloque "socio tecnológico" en la homepage; la etiqueta <strong> resalta "continuidad" */
            echo wp_kses(
                __( 'Gano Digital no compite en &ldquo;hosting barato&rdquo;. Compite en <strong>continuidad</strong>: infraestructura seria, soporte en tu idioma y visibilidad sobre lo que pasa detrás del sitio. Tú creces; nosotros sostenemos el piso técnico para que no te enteres de los incidentes por redes sociales.', 'gano-child' ),
                array( 'strong' => array() )
            );
            ?>
        </p>
        <ul class="gano-socio-block__bullets">
            <li><?php esc_html_e( 'Infraestructura alineada a cargas reales de WordPress y comercio.', 'gano-child' ); ?></li>
            <li><?php esc_html_e( 'Monitoreo y respuesta con foco en negocio, no en excusas.', 'gano-child' ); ?></li>
            <li><?php esc_html_e( 'Roadmap claro hacia soberanía digital y cumplimiento en Colombia.', 'gano-child' ); ?></li>
        </ul>
        <p class="gano-socio-block__cta-link">
            <a href="<?php echo esc_url( home_url( '/nosotros' ) ); ?>">
                <?php esc_html_e( 'Conoce nuestra historia →', 'gano-child' ); ?>
            </a>
        </p>
    </div>
    <?php
    return ob_get_clean();
}

// =============================================================================
// Shortcode: [gano_metrics]
// Uso en Elementor: widget "Código corto" → pega [gano_metrics]
// Clases CSS: gano-el-metrics (fila flex) + gano-el-metric (cada celda).
// Cifras prudentes: solo SLA reales, sin inflar números.
// =============================================================================

add_shortcode( 'gano_metrics', 'gano_render_metrics' );

/**
 * Renderiza la franja de métricas SLA de Gano Digital.
 *
 * Solo se usan cifras que el servicio puede respaldar:
 *   - 99,9 % de disponibilidad (objetivo de SLA según plan)
 *   - Almacenamiento NVMe (incluido en cada arquitectura)
 *   - Soporte 24/7 en español (canal: ticket / chat)
 *
 * @return string HTML escapado listo para Elementor.
 */
function gano_render_metrics(): string {
    $metrics = array(
        array(
            'valor'      => '99,9&nbsp;%',
            'etiqueta'   => __( 'Disponibilidad — objetivo SLA', 'gano-child' ),
        ),
        array(
            'valor'      => 'NVMe',
            'etiqueta'   => __( 'Almacenamiento por arquitectura', 'gano-child' ),
        ),
        array(
            'valor'      => '24/7',
            'etiqueta'   => __( 'Soporte en español', 'gano-child' ),
        ),
    );

    ob_start();
    ?>
    <div class="gano-el-metrics" role="list" aria-label="<?php esc_attr_e( 'Métricas de servicio Gano Digital', 'gano-child' ); ?>">
        <?php foreach ( $metrics as $m ) : ?>
        <div class="gano-el-metric" role="listitem">
            <strong><?php echo wp_kses_post( $m['valor'] ); ?></strong>
            <span><?php echo esc_html( $m['etiqueta'] ); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
