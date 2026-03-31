<?php

use Elementor\Controls_Manager;
use WprAddonsPro\Wpr_Dynamic_Tags_Module;
use Elementor\Core\DynamicTags\Data_Tag as Base_Data_Tag;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Wpr_Featured_Image extends Base_Data_Tag {

    public function get_name() {
        return 'wpr-featured-image';
    }

    public function get_title() {
        return __( 'Featured Image', 'wpr-addons' );
    }

    public function get_group() {
        return 'wpr_addons_featured_image';
    }

    public function get_categories() {
        return [
            'image',
            'url',
            'media'
        ];
    }

    protected function register_controls() {
        $this->add_control(
            'wpr_image_size',
            [
                'label' => esc_html__( 'Image Size', 'wpr-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => esc_html__( 'Thumbnail', 'wpr-addons' ),
                    'medium'    => esc_html__( 'Medium', 'wpr-addons' ),
                    'large'     => esc_html__( 'Large', 'wpr-addons' ),
                    'full'      => esc_html__( 'Full', 'wpr-addons' ),
                ],
                'default' => 'full',
            ]
        );
    }

    protected function get_value( $options = [] ) {
        $image_size = 'full';
        $post_id = get_the_ID();

        if ( ! $post_id ) {
            return '';
        }

        $image_id = get_post_thumbnail_id( $post_id );

        if ( ! $image_id ) {
            return '';
        }

        $image_url = wp_get_attachment_image_src( $image_id, $image_size )[0];

        if ( ! $image_url ) {
            return '';
        }

        // // Return array for widgets that expect image array
        return [
            'id'  => $image_id,
            'url' => $image_url,
        ];
    }
}