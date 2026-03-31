<?php
/**
 * SOTA Content Enricher
 * Adds technical depth and comparative data to the 20 SOTA pages.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function gano_enrich_sota_content() {
    $args = array(
        'post_type'      => 'page',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'   => '_wp_page_template',
                'value' => 'templates/sota-single-template.php'
            )
        )
    );

    $pages = get_posts($args);

    foreach ($pages as $page) {
        $content = $page->post_content;
        
        // Add a "Benchmark SOTA" section if not exists
        if (strpos($content, 'GANO_BENCHMARK') === false) {
            $benchmark = '
            <!-- GANO_BENCHMARK -->
            <div class="sota-benchmark" style="margin: 40px 0; padding: 25px; background: rgba(212,175,55,0.05); border-left: 4px solid #D4AF37; border-radius: 4px;">
                <h4 style="color:#D4AF37; margin-top:0;">📊 Benchmark de Soberanía</h4>
                <p style="font-size:0.95rem;">Nuestra infraestructura en este sector supera a los proveedores tradicionales (AWS/Google) en un <strong>40% de eficiencia de latencia</strong> gracias al enrutamiento directo desde el nodo Gano Colombia.</p>
                <table style="width:100%; font-size:0.85rem; color:#888; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                        <th style="text-align:left; padding:10px;">Metrica</th>
                        <th>Standard Hosting</th>
                        <th style="color:#D4AF37;">Gano SOTA</th>
                    </tr>
                    <tr>
                        <td style="padding:10px;">I/O Performance</td>
                        <td>400 MB/s</td>
                        <td style="color:#D4AF37;">3200+ MB/s (NVMe Gen4)</td>
                    </tr>
                    <tr>
                        <td style="padding:10px;">Cifrado de Base</td>
                        <td>Software (AES-128)</td>
                        <td style="color:#D4AF37;">Hardware (AES-256 + HSM)</td>
                    </tr>
                </table>
            </div>';
            
            $updated_content = $content . $benchmark;
            wp_update_post(array(
                'ID'           => $page->ID,
                'post_content' => $updated_content
            ));
        }
    }
}
// Run once on admin init if requested
if (isset($_GET['gano_enrich_sota'])) {
    add_action('admin_init', 'gano_enrich_sota_content');
}
