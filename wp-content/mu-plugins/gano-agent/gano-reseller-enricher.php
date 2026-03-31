<?php
/**
 * Gano Reseller Catalog Enricher
 * Automatically refines reseller_product posts to match Gano's SOTA identity.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function gano_enrich_reseller_catalog() {
    $args = array(
        'post_type'      => 'reseller_product',
        'posts_per_page' => -1,
        'post_status'    => 'publish'
    );

    $products = get_posts($args);
    
    // Mapping Table: Category Slug -> [Branding Title, Specs Array, Short Description]
    $mapping = array(
        'vps' => [
            'tier'  => 'Soberanía Digital',
            'desc'  => 'Infraestructura dedicada con autonomía total de datos y alto rendimiento.',
            'specs' => [
                ['label' => 'Almacenamiento', 'value' => 'NVMe Gen4'],
                ['label' => 'Acceso', 'value' => 'Root / SSH Dedicado'],
                ['label' => 'Soberanía', 'value' => 'Nodos Locales']
            ]
        ],
        'hosting-wp' => [
            'tier'  => 'Ecosistema WordPress SOTA',
            'desc'  => 'WordPress optimizado para velocidad extrema y blindaje de seguridad integrado.',
            'specs' => [
                ['label' => 'Motor', 'value' => 'LSCache + PHP 8.3'],
                ['label' => 'Seguridad', 'value' => 'Blindaje Proactivo'],
                ['label' => 'Soporte', 'value' => 'IA Nivel 1']
            ]
        ],
        'ssl' => [
            'tier'  => 'Blindaje SSL Administrado',
            'desc'  => 'Protección de identidad y cifrado de grado militar para transacciones seguras.',
            'specs' => [
                ['label' => 'Cifrado', 'value' => 'ECC / RSA 4096-bit'],
                ['label' => 'Validación', 'value' => 'EV / OV Premium'],
                ['label' => 'Garantía', 'value' => 'Protección Integrada']
            ]
        ],
        'security' => [
            'tier'  => 'Blindaje Perimetral Digital',
            'desc'  => 'WAF de Capa 7 y protección anti-DDoS constante para activos críticos.',
            'specs' => [
                ['label' => 'Protección', 'value' => 'WAF Capa 7'],
                ['label' => 'Mitigación', 'value' => 'Anti-DDoS Global'],
                ['label' => 'Escaneo', 'self' => 'Limpieza Automatizada']
            ]
        ],
        'workspace' => [
            'tier'  => 'Colaboración Empresarial Gano',
            'desc'  => 'Productividad profesional con control absoluto de la información empresarial y respaldo en la nube.',
            'specs' => [
                ['label' => 'Plataforma', 'value' => 'Productividad Enterprise'],
                ['label' => 'Cifrado', 'value' => 'AES-256 en Reposo'],
                ['label' => 'Compliance', 'value' => 'ISO 27001']
            ]
        ]
    );

    foreach ($products as $post) {
        $product_id = $post->ID;
        $categories = wp_get_object_terms($product_id, 'reseller_product_category');
        
        foreach ($categories as $cat) {
            if (isset($mapping[$cat->slug])) {
                $map = $mapping[$cat->slug];
                
                // 1. Update ACF fields if function exists
                if (function_exists('update_field')) {
                    update_field('short_description', $map['desc'], $product_id);
                    update_field('tech_specs', $map['specs'], $product_id);
                }

                // 2. Wrap content in Gano-styled container if not already enriched
                $content = $post->post_content;
                if (strpos($content, 'gano-product-card') === false) {
                    $specs_html = '<ul class="gano-product-specs">';
                    foreach ($map['specs'] as $spec) {
                        $specs_html .= '<li>' . esc_html($spec['label']) . ' <span>' . esc_html($spec['value']) . '</span></li>';
                    }
                    $specs_html .= '</ul>';

                    $new_content = '
                    <div class="gano-product-card" style="padding: 40px; margin-bottom: 30px;">
                        <div class="gano-product-badge">' . esc_html($map['tier']) . '</div>
                        <h2 class="gano-product-title">' . get_the_title($product_id) . '</h2>
                        <p style="color:#888; font-size: 0.95rem;">' . esc_html($map['desc']) . '</p>
                        ' . $specs_html . '
                        <div class="gano-product-description" style="font-size:0.9rem; opacity:0.7; margin-top:20px; border-top:1px solid rgba(255,255,255,0.05); padding-top:20px;">
                            ' . $content . '
                        </div>
                    </div>';
                    
                    wp_update_post(array(
                        'ID'           => $product_id,
                        'post_content' => $new_content
                    ));
                }
                
                break; // Stop after first mapping match
            }
        }
    }
}

// Trigger enrichment via URL: /wp-admin/?gano_enrich_reseller=1
if (isset($_GET['gano_enrich_reseller']) && is_admin()) {
    // ─── 2. GLOBALLY ALIGN PRICES (3rd-Year Structure) ───
    if (isset($_GET['gano_align_prices'])) {
        $args = array(
            'post_type' => 'reseller_product',
            'posts_per_page' => -1
        );
        $query = new WP_Query($args);
        $count = 0;

        foreach ($query->posts as $post) {
            $product_id = $post->ID;
            $title = strtolower($post->post_title);
            $new_price = 0;

            // Determine price based on keywords
            if (strpos($title, 'vps') !== false || strpos($title, 'dedicado') !== false) {
                if (strpos($title, '10') !== false || strpos($title, 'premium') !== false) $new_price = 48000000;
                elseif (strpos($title, '4') !== false || strpos($title, 'advanced') !== false) $new_price = 24000000;
                else $new_price = 9600000;
            } elseif (strpos($title, 'hosting') !== false || strpos($title, 'wordpress') !== false) {
                $new_price = 1910000;
            } elseif (strpos($title, 'ssl') !== false || strpos($title, 'seguridad') !== false) {
                $new_price = 890000;
            } else {
                // Default: Triple the current price if found
                $current = (float)get_post_meta($product_id, 'rstore_listPrice', true);
                $new_price = ($current > 0) ? ($current * 3 * 0.8) : 0; // 20% discount on 3yr
            }

            if ($new_price > 0) {
                update_post_meta($product_id, 'rstore_listPrice', $new_price);
                update_post_meta($product_id, 'rstore_salePrice', $new_price);
                update_post_meta($product_id, 'rstore_term', 'por 3 años');
                // Lock price from sync
                update_field('override_price', true, $product_id); 
                $count++;
            }
        }
        wp_die("Alineación completada. $count productos actualizados a estructura de 3 años.");
    }
    add_action('admin_init', 'gano_enrich_reseller_catalog');
}
