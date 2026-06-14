<?php
/**
 * Product Card Component (Ecosistema / Catalog)
 *
 * Uso:
 * echo gano_card_product([
 *   'tier_name'   => 'Ecosistema Premium',
 *   'title'       => 'WordPress Managed Cloud',
 *   'icon'        => 'fa-cloud',
 *   'specs'       => [
 *     'Almacenamiento' => '100 GB',
 *     'Visitas'        => 'Ilimitadas',
 *     'Dominios'       => '5 dominio(s)',
 *   ],
 *   'price'       => '$299',
 *   'period'      => 'COP / año',
 *   'cta_text'    => 'Explorar',
 *   'cta_url'     => '/ecosistema-premium',
 *   'featured'    => true,
 * ])
 */

if (!function_exists('gano_card_product')) {
    function gano_card_product($product = []) {
        $defaults = [
            'tier_name'   => 'Ecosistema',
            'title'       => '',
            'icon'        => 'fa-database',
            'specs'       => [],
            'price'       => '$0',
            'period'      => 'COP / año',
            'cta_text'    => 'Explorar',
            'cta_url'     => '#',
            'featured'    => false,
        ];

        $product = wp_parse_args($product, $defaults);
        $title = !empty($product['title']) ? $product['title'] : $product['tier_name'];

        // Specs
        $specs_html = '';
        if (!empty($product['specs'])) {
            foreach ($product['specs'] as $label => $value) {
                $specs_html .= sprintf(
                    '<div class="gano-card-product__spec">
                        <span class="gano-card-product__spec-label">%s</span>
                        <span class="gano-card-product__spec-value">%s</span>
                    </div>',
                    strtoupper(esc_html($label)),
                    esc_html($value)
                );
            }
        }

        $btn_variant = $product['featured'] ? 'gold' : 'ghost';

        return sprintf(
            '<div class="gano-card-product">
                <div class="gano-card-product__accent"></div>
                <div class="gano-card-product__icon">
                    <i class="fas %s" aria-hidden="true"></i>
                </div>
                <span class="gano-card-product__eyebrow">%s</span>
                <h3 class="gano-card-product__title">%s</h3>
                <div class="gano-card-product__specs">%s</div>
                <div class="gano-card-product__price">
                    <small>Desde</small>
                    <strong>%s</strong>
                    <small>%s</small>
                </div>
                %s
            </div>',
            esc_attr($product['icon']),
            esc_html($product['tier_name']),
            esc_html($title),
            $specs_html,
            esc_html($product['price']),
            esc_html($product['period']),
            gano_button([
                'text'    => $product['cta_text'],
                'url'     => $product['cta_url'],
                'variant' => $btn_variant,
                'theme'   => 'dark',
            ])
        );
    }
}
