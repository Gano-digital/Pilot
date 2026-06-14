<?php
/**
 * Componente CTA Registro
 * Call-to-action con animaciones SOTA para guiar usuarios al registro
 *
 * @package gano-child
 * @example gano_cta_registro(array('heading' => '¿No sabes por dónde empezar?'))
 */

if ( ! function_exists( 'gano_client_journey_landing_url' ) ) {
    /**
     * URL del flujo “primera compra / cuenta en operador Reseller” (no registro wp-admin).
     */
    function gano_client_journey_landing_url(): string {
        if ( function_exists( 'gano_resolve_page_url' ) ) {
            return gano_resolve_page_url( 'comenzar-aqui', 'comenzar', 'registro-y-compra' );
        }
        return home_url( '/comenzar-aqui/' );
    }
}

if ( ! function_exists( 'gano_cta_registro' ) ) {
    /**
     * Genera un CTA creativo de registro con animaciones SOTA
     *
     * @param array $args {
     *     @type string $heading          Texto del encabezado. Default: "¿No sabes por dónde empezar?"
     *     @type string $description      Descripción principal (4 líneas sugeridas). Default: mensaje completo.
     *     @type string $button_text      Texto del botón. Default: "Registra tu cuenta"
     *     @type string $button_url       URL destino del botón. Default: guía comenzar-aqui (checkout Reseller).
     *     @type string $class            Clases CSS adicionales para el wrapper. Default: ""
     * }
     * @return void Imprime el HTML del CTA
     */
    function gano_cta_registro( $args = array() ) {
        $defaults = array(
            'heading'       => '¿No sabes por dónde empezar?',
            'description'   => 'Registra tu cuenta y recibe soporte inmediato. Nosotros te agendamos y te acompañamos en cada decisión: siempre donde verdaderamente importa.',
            'button_text'   => 'Registra tu cuenta',
            'button_url'    => gano_client_journey_landing_url(),
            'class'         => '',
        );

        $args = wp_parse_args( $args, $defaults );

        // Sanitizar valores
        $heading      = esc_html( $args['heading'] );
        $description  = esc_html( $args['description'] );
        $button_text  = esc_html( $args['button_text'] );
        $button_url   = esc_url( $args['button_url'] );
        $wrapper_class = 'gano-cta-registro-wrapper ' . esc_attr( $args['class'] );

        ?>
        <div class="<?php echo $wrapper_class; ?>">
            <div class="gano-cta-registro">
                <h3 class="gano-cta-registro__heading"><?php echo $heading; ?></h3>
                <p class="gano-cta-registro__description"><?php echo $description; ?></p>
                <a href="<?php echo $button_url; ?>" class="gano-cta-registro__button">
                    <?php echo $button_text; ?>
                    <span class="gano-cta-registro__ripple" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        <?php
    }
}
