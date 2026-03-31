<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !is_customize_preview() ) {
    return;
}

// Include Customizer Files
function rek_include_customizer_files( $wp_customize ) {
    // Add Section
    $wp_customize->add_section('rek_general_section', array(
        'title'    => 'Royal Elementor Kit',
        'priority' => 1
    ));

    // Add Setting
    $wp_customize->add_setting('rek_templates_buttons', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw'
    ));

    // Add Control
    $wp_customize->add_control(
        new WP_Customize_Control(
            $wp_customize,
            'rek_templates_buttons',
            array(
                'section' => 'rek_general_section',
                'type'    => 'hidden',
                'description' => sprintf(
                    '<p style="margin-bottom: 15px;">%s</p><strong><a href="https://royal-elementor-addons.com/royal-elementor-kit/?ref=royal-elementor-theme-customizer-textlink#widgets" target="_blank">%s</a> %s</strong><br><br>
                    <a href="%s" class="button button-primary widefat rek-get-started-btn" style="margin-right: 5px;">%s <span class="dashicons dashicons-arrow-right-alt"></span></a>
                    <a href="%s" class="button button-secondary widefat" target="_blank">%s <span class="dashicons dashicons-external"></span></a>',
                    sprintf(
                        '<strong>%s</strong> comes with Elementor based <strong>%s</strong> library with various designs to pick from.',
                        esc_html__('Royal Elementor Kit', 'royal-elementor-kit'),
                        esc_html__('140+ sites', 'royal-elementor-kit')
                    ),
                    esc_html__('100+ Premium Elementor Widgets', 'royal-elementor-kit'),
                    esc_html__('like Post and Product Grid, Slider, Menu, Theme & Woocommerce Shop Builder, Popup Builder', 'royal-elementor-kit'),
                    is_plugin_active('royal-elementor-addons/wpr-addons.php') 
                        ? admin_url('admin.php?page=wpr-templates-kit')
                        : admin_url('admin.php?page=rek-options'),
                    esc_html__('Get Started with Templates Kit', 'royal-elementor-kit'),
                    'https://demosites.royal-elementor-addons.com/elementor-templates/?ref=royal-elementor-theme-customizer',
                    esc_html__('Templates Kit Demo Preview', 'royal-elementor-kit')
                )
            )
        )
    );
}

add_action( 'customize_register', 'rek_include_customizer_files' );

// Enqueue Styles
function rek_customizer_styles() {
    if ( is_customize_preview() ) {
        wp_enqueue_style( 'rek-customizer-css', get_template_directory_uri() . '/inc/admin/assets/css/customizer-styles.css' );
    }
}
add_action( 'customize_controls_enqueue_scripts', 'rek_customizer_styles' );
