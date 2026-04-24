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
     * Genera un CTA creativo de registro con pre-registro (lead capture)
     */
    function gano_cta_registro( $args = array() ) {
        $defaults = array(
            'heading'       => 'Empieza tu viaje digital',
            'description'   => 'Regístrate para acceder al catálogo SOTA y recibe acompañamiento experto desde el primer minuto. Sin complicaciones, sin letra pequeña.',
            'button_text'   => 'Iniciar registro',
            'button_url'    => gano_client_journey_landing_url(),
            'class'         => '',
            'show_form'     => true,
        );

        $args = wp_parse_args( $args, $defaults );

        $heading       = $args['heading'];
        $description   = $args['description'];
        $button_text   = esc_html( $args['button_text'] );
        $button_url    = esc_url( $args['button_url'] );
        $wrapper_class = 'gano-cta-registro-wrapper ' . esc_attr( $args['class'] );

        ?>
        <div class="<?php echo $wrapper_class; ?>">
            <div class="gano-cta-registro gano-km-shell">
                <h3 class="gano-cta-registro__heading">
                    <span><?php esc_html_e( 'SOTA Hub Access', 'gano-child' ); ?></span>
                    <?php echo $heading; ?>
                </h3>
                <p class="gano-cta-registro__description"><?php echo $description; ?></p>
                
                <?php if ( $args['show_form'] ) : ?>
                    <form class="gano-pre-registro-form gano-cta-registro__form" method="POST">
                        <input type="text" name="gano_name" placeholder="Tu nombre" required>
                        <input type="email" name="gano_email" placeholder="Tu mejor email" required>
                        <input type="hidden" name="redirect_to" value="<?php echo $button_url; ?>">
                        <button type="submit" class="gano-cta-registro__button">
                            <?php echo $button_text; ?>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </form>
                    <div class="gano-pre-registro-feedback" style="display:none; color: var(--gano-color-accent); margin-top: 20px; font-weight: 600;">
                        <?php esc_html_e( 'Preparando tu acceso SOTA...', 'gano-child' ); ?>
                    </div>

                <?php else : ?>
                    <a href="<?php echo $button_url; ?>" class="gano-cta-registro__button">
                        <?php echo $button_text; ?>
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

