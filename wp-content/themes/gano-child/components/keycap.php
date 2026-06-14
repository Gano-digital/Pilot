<?php
if (!function_exists('gano_keycap')) {
    function gano_keycap($args = []) {
        $defaults = [
            'text'      => 'New',
            'variant'   => 'gold',      // gold, primary, secondary, accent
            'size'      => 'sm',        // xs, sm, md
            'icon'      => null,        // Font Awesome class
            'class'     => '',
        ];
        $args = wp_parse_args($args, $defaults);

        $class = sprintf(
            'gano-keycap gano-keycap--%s gano-keycap--%s %s',
            esc_attr($args['variant']),
            esc_attr($args['size']),
            esc_attr($args['class'])
        );

        $icon_html = '';
        if ($args['icon']) {
            $icon_html = sprintf(
                '<i class="fa-solid %s" aria-hidden="true"></i> ',
                esc_attr($args['icon'])
            );
        }

        return sprintf(
            '<span class="%s">%s%s</span>',
            $class,
            $icon_html,
            esc_html($args['text'])
        );
    }
}
?>
