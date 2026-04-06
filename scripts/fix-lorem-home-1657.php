<?php
/**
 * Fix: Reemplazar Lorem ipsum en Elementor JSON — post ID 1657 (home)
 *
 * Uso (WP-CLI, ejecutar en el servidor de producción/staging):
 *   wp --path=/ruta/a/wordpress eval-file scripts/fix-lorem-home-1657.php
 *
 * Qué hace:
 *   1. Lee _elementor_data del post 1657.
 *   2. Decodifica el JSON y recorre recursivamente todos los widgets.
 *   3. Localiza widgets heading/text-editor con texto Lorem ipsum.
 *   4. Sustituye el copy con el texto final de homepage-copy-2026-04.md:
 *      — 4 tarjetas de pilares (gano-el-pillar)
 *      — bloque "Un socio tecnológico" (gano-el-prose-narrow)
 *   5. Re-serializa y hace UPDATE en postmeta.
 *   6. Llama a Elementor::flush() y wp_cache_flush() para limpiar caché.
 *
 * Ref: Issue #118 · memory/content/homepage-copy-2026-04.md
 */

declare(strict_types=1);

// Doble guardia: ABSPATH garantiza que WP está cargado; WP_CLI restringe
// la ejecución exclusivamente al entorno WP-CLI, impidiendo el acceso HTTP.
if ( ! defined( 'ABSPATH' ) || ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    exit( 'Este script debe ejecutarse vía wp eval-file.' . PHP_EOL );
}

// ─── Copy definitivo (fuente: homepage-copy-2026-04.md) ──────────────────────

/** @var array<int,array{titulo:string,texto:string}> */
$gano_pilares = [
    [
        'titulo' => 'NVMe que se nota en Core Web Vitals, no solo en el folleto.',
        'texto'  => 'Almacenamiento de nueva generación y stack optimizado para WordPress: menos espera, más conversión. Tu sitio carga cuando el cliente ya decidió quedarse.',
    ],
    [
        'titulo' => 'WordPress endurecida para el tráfico real de un negocio.',
        'texto'  => 'Hardening continuo, controles de acceso y visibilidad sobre lo que ocurre en tu instalación. Menos superficie de ataque, más tranquilidad operativa.',
    ],
    [
        'titulo' => 'Confianza cero por defecto: identidad, sesiones y permisos bajo control.',
        'texto'  => 'La seguridad no es un cartel: es política aplicada en capas. Menos suposiciones, más trazabilidad cuando importa.',
    ],
    [
        'titulo' => 'Contenido más cerca del usuario, sin magia barata.',
        'texto'  => 'Arquitectura pensada para entregar experiencias rápidas donde vive tu audiencia. Menos saltos innecesarios, más respuesta perceptible.',
    ],
];

$gano_socio_parrafo = 'Gano Digital no compite en &ldquo;hosting barato&rdquo;. Compite en <strong>continuidad</strong>: infraestructura seria, soporte en tu idioma y visibilidad sobre lo que pasa detrás del sitio. Tú creces; nosotros sostenemos el piso técnico para que no te enteres de los incidentes por redes sociales.';

// ─── Constantes ──────────────────────────────────────────────────────────────

const GANO_FIX_POST_ID  = 1657;
const GANO_FIX_META_KEY = '_elementor_data';

// ─── Helpers ─────────────────────────────────────────────────────────────────

/**
 * Devuelve true si $value contiene "lorem" (insensible a mayúsculas).
 *
 * @param mixed $value
 */
function gano_fix_is_lorem( $value ): bool {
    if ( ! is_string( $value ) ) {
        return false;
    }
    return stripos( $value, 'lorem' ) !== false;
}

/**
 * Recorre recursivamente los elementos de Elementor y sustituye Lorem ipsum.
 *
 * La estrategia es por orden de aparición:
 *   — heading widgets con Lorem en "title" → asignar título de pilar 1, 2, 3, 4 en secuencia.
 *   — text-editor widgets con Lorem en "editor" → asignar texto de pilar o socio en secuencia.
 *
 * Los contadores se pasan por referencia para mantener el índice correcto
 * aunque la función se llame recursivamente.
 *
 * @param array<mixed>                            $elements        Árbol de elementos Elementor.
 * @param array<int,array{titulo:string,texto:string}> $pilares    Copy de los 4 pilares.
 * @param string                                  $socio_parrafo   Copy del bloque socio.
 * @param int                                     $pilar_titulo_idx Índice de títulos de pilar usados.
 * @param int                                     $pilar_texto_idx  Índice de textos de pilar usados.
 * @param bool                                    $socio_replaced   Si ya se reemplazó el párrafo socio.
 * @param int                                     $replaced_count   Total de reemplazos realizados.
 * @return array<mixed> Árbol modificado.
 */
function gano_fix_traverse_elements(
    array  $elements,
    array  $pilares,
    string $socio_parrafo,
    int   &$pilar_titulo_idx,
    int   &$pilar_texto_idx,
    bool  &$socio_replaced,
    int   &$replaced_count
): array {
    foreach ( $elements as &$el ) {

        // Recurrir en subelementos primero.
        if ( ! empty( $el['elements'] ) && is_array( $el['elements'] ) ) {
            $el['elements'] = gano_fix_traverse_elements(
                $el['elements'],
                $pilares,
                $socio_parrafo,
                $pilar_titulo_idx,
                $pilar_texto_idx,
                $socio_replaced,
                $replaced_count
            );
        }

        if ( empty( $el['widgetType'] ) || empty( $el['settings'] ) ) {
            continue;
        }

        $widget_type = (string) $el['widgetType'];
        $settings    = &$el['settings'];

        // ── Widget: heading ───────────────────────────────────────────────────
        if ( 'heading' === $widget_type ) {
            $title_key = 'title';
            if (
                isset( $settings[ $title_key ] ) &&
                gano_fix_is_lorem( $settings[ $title_key ] ) &&
                $pilar_titulo_idx < count( $pilares )
            ) {
                $settings[ $title_key ] = $pilares[ $pilar_titulo_idx ]['titulo'];
                WP_CLI::log( sprintf(
                    '  ✅ Pilar %d — título reemplazado (widget %s)',
                    $pilar_titulo_idx + 1,
                    $el['id'] ?? '?'
                ) );
                $pilar_titulo_idx++;
                $replaced_count++;
            }
        }

        // ── Widget: text-editor ───────────────────────────────────────────────
        if ( 'text-editor' === $widget_type ) {
            $editor_key = 'editor';
            if ( isset( $settings[ $editor_key ] ) && gano_fix_is_lorem( $settings[ $editor_key ] ) ) {

                // Primero intentamos completar textos de pilares.
                if ( $pilar_texto_idx < count( $pilares ) ) {
                    $settings[ $editor_key ] = '<p>' . wp_kses_post( $pilares[ $pilar_texto_idx ]['texto'] ) . '</p>';
                    WP_CLI::log( sprintf(
                        '  ✅ Pilar %d — texto reemplazado (widget %s)',
                        $pilar_texto_idx + 1,
                        $el['id'] ?? '?'
                    ) );
                    $pilar_texto_idx++;
                    $replaced_count++;

                // Luego el bloque socio tecnológico.
                } elseif ( ! $socio_replaced ) {
                    $settings[ $editor_key ] = '<p>' . wp_kses_post( $socio_parrafo ) . '</p>';
                    WP_CLI::log( sprintf(
                        '  ✅ Socio tecnológico — párrafo reemplazado (widget %s)',
                        $el['id'] ?? '?'
                    ) );
                    $socio_replaced = true;
                    $replaced_count++;
                }
            }
        }

        unset( $settings ); // liberar referencia en bucle.
    }
    unset( $el );

    return $elements;
}

// ─── Main ─────────────────────────────────────────────────────────────────────

WP_CLI::log( '──────────────────────────────────────────────────────────────' );
WP_CLI::log( 'Gano Digital — Fix Lorem ipsum · post ' . GANO_FIX_POST_ID );
WP_CLI::log( '──────────────────────────────────────────────────────────────' );

// 1. Leer meta.
$raw = get_post_meta( GANO_FIX_POST_ID, GANO_FIX_META_KEY, true );

if ( empty( $raw ) ) {
    WP_CLI::error( 'No se encontró _elementor_data para post ' . GANO_FIX_POST_ID . '. Verifica el ID.' );
}

WP_CLI::log( sprintf( 'JSON leído: %s caracteres.', number_format( strlen( $raw ) ) ) );

// 2. Decodificar.
$data = json_decode( $raw, true );

if ( ! is_array( $data ) ) {
    WP_CLI::error( 'El JSON de _elementor_data no es válido o no es un array.' );
}

WP_CLI::log( sprintf( 'Elementos raíz en Elementor JSON: %d', count( $data ) ) );

// 3. Traversal y sustitución.
$pilar_titulo_idx = 0;
$pilar_texto_idx  = 0;
$socio_replaced   = false;
$replaced_count   = 0;

$data = gano_fix_traverse_elements(
    $data,
    $gano_pilares,
    $gano_socio_parrafo,
    $pilar_titulo_idx,
    $pilar_texto_idx,
    $socio_replaced,
    $replaced_count
);

WP_CLI::log( sprintf( 'Total de reemplazos realizados: %d', $replaced_count ) );

if ( 0 === $replaced_count ) {
    WP_CLI::warning( 'No se encontró texto Lorem ipsum en el JSON. ¿Ya fue corregido o cambió la estructura?' );
    WP_CLI::halt( 0 );
}

// 4. Re-serializar.
$new_json = wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

if ( false === $new_json ) {
    WP_CLI::error( 'Error al re-serializar el JSON modificado.' );
}

// 5. Actualizar postmeta.
$updated = update_post_meta( GANO_FIX_POST_ID, GANO_FIX_META_KEY, wp_slash( $new_json ) );

if ( false === $updated ) {
    // update_post_meta devuelve false cuando el valor nuevo es idéntico al anterior.
    WP_CLI::warning( 'update_post_meta devolvió false — el valor podría ser idéntico al anterior.' );
} else {
    WP_CLI::log( '  ✅ postmeta actualizado en la base de datos.' );
}

// 6. Invalidar caché de Elementor y WP.
if ( class_exists( '\Elementor\Plugin' ) ) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    WP_CLI::log( '  ✅ Caché de archivos CSS de Elementor limpiada.' );
} else {
    WP_CLI::warning( 'Clase Elementor\Plugin no disponible — limpia la caché manualmente desde WP Admin → Elementor → Herramientas.' );
}

wp_cache_flush();
WP_CLI::log( '  ✅ Caché de objetos de WordPress limpiada.' );

// Invalidar caché de la página específica (compatibilidad Elementor Pro + Cache plugins).
clean_post_cache( GANO_FIX_POST_ID );

WP_CLI::success( 'Fix completado. Ningún Lorem ipsum debe aparecer en la home. Verifica en ' . get_permalink( GANO_FIX_POST_ID ) );
WP_CLI::log( '──────────────────────────────────────────────────────────────' );
