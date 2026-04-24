<?php
if (!function_exists('gano_badge')) {
    function gano_badge($args = []) {
        $defaults = [
            'text'      => 'Badge',
            'type'      => 'primary',   // primary, success, warning, error, info
            'count'     => null,        // Optional count/number
            'pulse'     => false,       // Animated pulse
            'class'     => '',
        ];
        $args = wp_parse_args($args, $defaults);

        $pulse_class = $args['pulse'] ? 'gano-badge--pulse' : '';

        $class = sprintf(
            'gano-badge gano-badge--%s %s %s',
            esc_attr($args['type']),
            $pulse_class,
            esc_attr($args['class'])
        );

        $count_html = '';
        if ($args['count'] !== null) {
            $count_html = sprintf(
                ' <span class="gano-badge__count">%s</span>',
                esc_html($args['count'])
            );
        }

        return sprintf(
            '<span class="%s">%s%s</span>',
            $class,
            esc_html($args['text']),
            $count_html
        );
    }
}
?>
