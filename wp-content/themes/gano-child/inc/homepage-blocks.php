<?php
/**
 * Gano Digital — Bloques de homepage (shortcodes)
 *
 * Proporciona dos shortcodes para insertar en Elementor (widget Código corto):
 *
 *   [gano_socio_tecnologico]  → Bloque "Un socio tecnológico que trabaja en silencio."
 *   [gano_metrics]            → Franja de métricas SLA (cifras prudentes, sin inflar).
 *
 * Copy de referencia: memory/content/homepage-copy-2026-04.md
 * Clases CSS:         style.css → .gano-el-prose-narrow, .gano-el-metrics, .gano-el-metric
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
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
