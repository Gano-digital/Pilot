<?php
/**
 * Programmatically register ACF fields for reseller_product CPT.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'acf/init', 'gano_reseller_register_fields' );

function gano_reseller_register_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( array(
        'key' => 'group_reseller_product_meta',
        'title' => 'Metadatos del Producto Reseller',
        'fields' => array(
            array(
                'key' => 'field_short_description',
                'label' => 'Descripción Corta',
                'name' => 'short_description',
                'type' => 'textarea',
                'instructions' => 'Una descripción breve para mostrar en los listados de productos.',
                'required' => 0,
                'rows' => 3,
            ),
            array(
                'key' => 'field_override_price',
                'label' => 'Bloquear Sincronización de Precios',
                'name' => 'override_price',
                'type' => 'true_false',
                'instructions' => 'Si está activado, el proceso de sincronización automática NO sobrescribirá el precio de lista y de oferta de este producto.',
                'default_value' => 0,
                'ui' => 1,
            ),
            array(
                'key' => 'field_tech_specs',
                'label' => 'Especificaciones Técnicas',
                'name' => 'tech_specs',
                'type' => 'repeater',
                'instructions' => 'Detalles técnicos (ej: RAM, Almacenamiento, etc.)',
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => 'Añadir Atributo',
                'sub_fields' => array(
                    array(
                        'key' => 'field_spec_label',
                        'label' => 'Etiqueta',
                        'name' => 'label',
                        'type' => 'text',
                        'placeholder' => 'RAM',
                    ),
                    array(
                        'key' => 'field_spec_value',
                        'label' => 'Valor',
                        'name' => 'value',
                        'type' => 'text',
                        'placeholder' => '8GB',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'reseller_product',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
    ));
}
