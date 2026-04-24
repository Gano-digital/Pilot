<?php
if (!function_exists('gano_hero')) {
    function gano_hero($args = []) {
        $defaults = [
            'heading'     => 'Welcome to Gano Digital',
            'subheading'  => 'WordPress Reseller Solutions',
            'content'     => '',
            'cta_text'    => 'Get Started',
            'cta_url'     => '/register/',
            'cta_variant' => 'primary',   // primary, secondary, ghost, cta
            'bg_image'    => null,
            'theme'       => 'light',    // light, dark
            'size'        => 'lg',       // sm, md, lg
            'alignment'   => 'center',   // left, center, right
            'class'       => '',
        ];
        $args = wp_parse_args($args, $defaults);

        $class = sprintf(
            'gano-hero gano-hero--%s gano-hero--%s gano-hero--align-%s %s',
            esc_attr($args['theme']),
            esc_attr($args['size']),
            esc_attr($args['alignment']),
            esc_attr($args['class'])
        );

        $bg_style = '';
        if ($args['bg_image']) {
            $bg_style = sprintf(
                'style="background-image: url(\'%s\');"',
                esc_url($args['bg_image'])
            );
        }

        $cta_html = '';
        if ($args['cta_text']) {
            $cta_html = sprintf(
                '<a href="%s" class="gano-hero__cta gano-btn gano-btn--%s gano-btn--lg">%s</a>',
                esc_url($args['cta_url']),
                esc_attr($args['cta_variant']),
                esc_html($args['cta_text'])
            );
        }

        $content_html = '';
        if ($args['content']) {
            $content_html = sprintf(
                '<div class="gano-hero__content">%s</div>',
                wp_kses_post($args['content'])
            );
        }

        return sprintf(
            '<section class="%s" %s><div class="gano-hero__overlay"></div><div class="gano-hero__container"><h1 class="gano-hero__heading">%s</h1><p class="gano-hero__subheading">%s</p>%s%s</div></section>',
            $class,
            $bg_style,
            esc_html($args['heading']),
            esc_html($args['subheading']),
            $content_html,
            $cta_html
        );
    }
}
?>
