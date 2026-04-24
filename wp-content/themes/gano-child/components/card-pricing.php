<?php
/**
 * Pricing Card Component
 *
 * Uso:
 * echo gano_card_pricing([
 *   'name'        => 'Plan Pro',
 *   'price'       => '$299',
 *   'period'      => 'COP / año',
 *   'description' => 'Ideal para startups',
 *   'features'    => ['Feature 1', 'Feature 2', 'Feature 3'],
 *   'cta_text'    => 'Contratar',
 *   'cta_url'     => '/checkout',
 *   'featured'    => true, // Mark as featured/popular
 * ])
 */

if (!function_exists('gano_card_pricing')) {
    function gano_card_pricing($tier = []) {
        $defaults = [
            'name'        => 'Plan',
            'price'       => '$0',
            'period'      => 'COP / año',
            'description' => '',
            'features'    => [],
            'cta_text'    => 'Contratar',
            'cta_url'     => '#',
            'featured'    => false,
        ];

        $tier = wp_parse_args($tier, $defaults);

        $card_class = 'gano-card-pricing';
        if ($tier['featured']) {
            $card_class .= ' gano-card-pricing--featured';
        }

        // Features list
        $features_html = '';
        if (!empty($tier['features'])) {
            $features_html = '<ul class="gano-card-pricing__features">';
            foreach ($tier['features'] as $feature) {
                $features_html .= sprintf(
                    '<li class="gano-card-pricing__feature"><i class="fas fa-check" aria-hidden="true"></i> %s</li>',
                    esc_html($feature)
                );
            }
            $features_html .= '</ul>';
        }

        // Keycap if featured
        $keycap_html = '';
        if ($tier['featured']) {
            $keycap_html = sprintf(
                '<span class="gano-keycap gano-keycap--gold">%s</span>',
                esc_html__('Más popular', 'gano')
            );
        }

        return sprintf(
            '<div class="%s">
                %s
                <h3 class="gano-card-pricing__name">%s</h3>
                %s
                <div class="gano-card-pricing__price">
                    <strong>%s</strong>
                    <small>%s</small>
                </div>
                %s
                <a href="%s" class="gano-btn gano-btn--cta gano-btn--light">%s</a>
            </div>',
            esc_attr($card_class),
            $keycap_html,
            esc_html($tier['name']),
            !empty($tier['description']) ? '<p class="gano-card-pricing__description">' . esc_html($tier['description']) . '</p>' : '',
            esc_html($tier['price']),
            esc_html($tier['period']),
            $features_html,
            esc_url($tier['cta_url']),
            esc_html($tier['cta_text'])
        );
    }
}
