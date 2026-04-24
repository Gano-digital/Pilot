<?php
if (!function_exists('gano_panel')) {
    function gano_panel($args = []) {
        $defaults = [
            'title'       => 'Panel Title',
            'content'     => '',
            'icon'        => null,          // Font Awesome class
            'type'        => 'default',     // default, highlight, dark
            'featured'    => false,
            'footer'      => null,
            'cta_text'    => null,
            'cta_url'     => '#',
            'class'       => '',
        ];
        $args = wp_parse_args($args, $defaults);

        $featured_class = $args['featured'] ? 'gano-panel--featured' : '';

        $class = sprintf(
            'gano-panel gano-panel--%s %s %s',
            esc_attr($args['type']),
            $featured_class,
            esc_attr($args['class'])
        );

        $icon_html = '';
        if ($args['icon']) {
            $icon_html = sprintf(
                '<div class="gano-panel__icon"><i class="fa-solid %s" aria-hidden="true"></i></div>',
                esc_attr($args['icon'])
            );
        }

        $cta_html = '';
        if ($args['cta_text']) {
            $cta_html = sprintf(
                '<a href="%s" class="gano-panel__cta">%s <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>',
                esc_url($args['cta_url']),
                esc_html($args['cta_text'])
            );
        }

        $footer_html = '';
        if ($args['footer']) {
            $footer_html = sprintf(
                '<div class="gano-panel__footer">%s</div>',
                wp_kses_post($args['footer'])
            );
        }

        return sprintf(
            '<div class="%s">%s<div class="gano-panel__content"><h3 class="gano-panel__title">%s</h3><p class="gano-panel__text">%s</p>%s</div>%s%s</div>',
            $class,
            $icon_html,
            esc_html($args['title']),
            wp_kses_post($args['content']),
            $cta_html,
            $footer_html,
            $args['featured'] ? '<div class="gano-panel__accent"></div>' : ''
        );
    }
}
?>
