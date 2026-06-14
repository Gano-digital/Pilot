<?php
/**
 * Button Component
 *
 * Uso:
 * gano_button([
 *   'text'      => 'Button',
 *   'url'       => '#',
 *   'variant'   => 'primary|secondary|outline|ghost|gold|cta',
 *   'size'      => 'sm|md|lg',
 *   'theme'     => 'light|dark',
 *   'icon'      => 'fas fa-arrow-right',
 *   'target'    => '',
 *   'rel'       => '',
 *   'class'     => '',
 * ])
 */

if (!function_exists('gano_button')) {
    function gano_button($args = []) {
        $defaults = [
            'text'      => 'Button',
            'url'       => '#',
            'variant'   => 'primary',  // primary, secondary, outline, ghost, gold, cta
            'size'      => 'md',       // sm, md, lg
            'theme'     => 'light',    // light, dark
            'class'     => '',
            'icon'      => null,       // Font Awesome class
            'target'    => '',
            'rel'       => '',
        ];

        $args = wp_parse_args($args, $defaults);

        // Build classes
        $classes = [
            'gano-btn',
            'gano-btn--' . esc_attr($args['variant']),
            'gano-btn--' . esc_attr($args['size']),
            'gano-btn--' . esc_attr($args['theme']),
        ];

        if (!empty($args['class'])) {
            $classes[] = $args['class'];
        }

        $class_string = implode(' ', $classes);

        // Icon HTML
        $icon_html = '';
        if (!empty($args['icon'])) {
            $icon_html = sprintf('<i class="%s" aria-hidden="true"></i> ', esc_attr($args['icon']));
        }

        // Target/rel attributes
        $target_attr = '';
        if (!empty($args['target'])) {
            $target_attr = ' target="' . esc_attr($args['target']) . '"';
        }

        $rel_attr = '';
        if (!empty($args['rel'])) {
            $rel_attr = ' rel="' . esc_attr($args['rel']) . '"';
        }

        return sprintf(
            '<a href="%s" class="%s"%s%s>%s%s</a>',
            esc_url($args['url']),
            $class_string,
            $target_attr,
            $rel_attr,
            $icon_html,
            esc_html($args['text'])
        );
    }
}
