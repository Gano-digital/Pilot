<?php
/**
 * Fix: Importar assets faltantes como attachments de WordPress.
 *
 * Uso (wp-cli):
 *   wp eval-file scripts/fix-missing-media-attachments.php \
 *       --path=/home/f1rml03th382/public_html/gano.digital
 *
 * Qué hace:
 *   1. Busca hero_digital_garden.png y decorative_abstract_seed.png en el
 *      directorio de uploads (raíz y subdirectorios yyyy/mm).
 *   2. Si el archivo existe pero no tiene registro en wp_posts como
 *      'attachment', lo importa con wp_insert_attachment() y genera metadata.
 *   3. Imprime el attachment ID resultante para cada imagen.
 *
 * Ref: Issue #117 — [prod-fix] Assets faltantes en DB
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
	die( "Este script debe ejecutarse con wp-cli: wp eval-file scripts/fix-missing-media-attachments.php\n" );
}

require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$missing_assets = [
	'hero_digital_garden.png'      => 'Hero Digital Garden',
	'decorative_abstract_seed.png' => 'Decorative Abstract Seed',
];

$upload_dir = wp_upload_dir();
$base_dir   = $upload_dir['basedir'];

/**
 * Encuentra la ruta física de un archivo en el directorio de uploads.
 * Busca en la raíz y en subdirectorios yyyy/mm.
 *
 * @param string $base_dir Ruta al directorio base de uploads.
 * @param string $filename Nombre de archivo a buscar.
 * @return string Ruta absoluta si se encuentra, cadena vacía si no.
 */
function gano_find_file_in_uploads( string $base_dir, string $filename ): string {
	// Buscar en la raíz.
	$root_path = trailingslashit( $base_dir ) . $filename;
	if ( file_exists( $root_path ) ) {
		return $root_path;
	}

	// Buscar en subdirectorios yyyy/mm.
	$subdirs = glob( $base_dir . '/[0-9][0-9][0-9][0-9]/[0-9][0-9]', GLOB_ONLYDIR );
	if ( is_array( $subdirs ) ) {
		// Ordenar descendiente para encontrar primero los más recientes.
		rsort( $subdirs );
		foreach ( $subdirs as $subdir ) {
			$candidate = trailingslashit( $subdir ) . $filename;
			if ( file_exists( $candidate ) ) {
				return $candidate;
			}
		}
	}

	return '';
}

/**
 * Verifica si un archivo ya tiene un registro de attachment en WordPress.
 *
 * @param string $filename Nombre del archivo.
 * @return int|false Attachment ID o false si no existe.
 */
function gano_get_existing_attachment_id( string $filename ) {
	global $wpdb;

	// Buscar por _wp_attached_file.
	$id = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta}
			 WHERE meta_key = '_wp_attached_file'
			   AND meta_value LIKE %s
			 LIMIT 1",
			'%' . $wpdb->esc_like( $filename ) . '%'
		)
	);
	if ( $id ) {
		return (int) $id;
	}

	// Buscar por post_title.
	$id = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts}
			 WHERE post_type = 'attachment'
			   AND post_title LIKE %s
			 LIMIT 1",
			'%' . $wpdb->esc_like( pathinfo( $filename, PATHINFO_FILENAME ) ) . '%'
		)
	);

	return $id ? (int) $id : false;
}

WP_CLI::line( '' );
WP_CLI::line( '=== Gano Digital — Fix: Importar assets faltantes ===' );
WP_CLI::line( '' );

foreach ( $missing_assets as $filename => $title ) {
	WP_CLI::line( "→ Procesando: {$filename}" );

	// 1. ¿Ya existe en la DB?
	$existing_id = gano_get_existing_attachment_id( $filename );
	if ( $existing_id ) {
		WP_CLI::success( "  Ya existe como attachment ID {$existing_id}. No se necesita importar." );
		continue;
	}

	// 2. ¿Existe el archivo físicamente?
	$file_path = gano_find_file_in_uploads( $base_dir, $filename );
	if ( ! $file_path ) {
		WP_CLI::warning( "  Archivo no encontrado en uploads. Sube manualmente desde wp-admin → Medios." );
		continue;
	}

	WP_CLI::line( "  Archivo encontrado en: {$file_path}" );

	// 3. Importar como attachment.
	$filetype    = wp_check_filetype( $file_path );
	$relative    = _wp_relative_upload_path( $file_path );
	$guid        = trailingslashit( $upload_dir['baseurl'] ) . $relative;

	$attachment_data = [
		'guid'           => esc_url_raw( $guid ),
		'post_mime_type' => sanitize_mime_type( $filetype['type'] ),
		'post_title'     => sanitize_text_field( $title ),
		'post_status'    => 'inherit',
	];

	$attachment_id = wp_insert_attachment( $attachment_data, $file_path );

	if ( is_wp_error( $attachment_id ) ) {
		WP_CLI::error( "  Error al insertar attachment: " . $attachment_id->get_error_message(), false );
		continue;
	}

	$metadata = wp_generate_attachment_metadata( $attachment_id, $file_path );
	wp_update_attachment_metadata( $attachment_id, $metadata );

	WP_CLI::success( "  Importado correctamente. Nuevo attachment ID: {$attachment_id}" );
}

WP_CLI::line( '' );
WP_CLI::line( 'Limpiar caché de Elementor y objeto:' );
WP_CLI::line( '  wp elementor flush-css' );
WP_CLI::line( '  wp cache flush' );
WP_CLI::line( '' );
WP_CLI::line( 'Verificar en DB:' );
WP_CLI::line( "  wp post list --post_type=attachment --post_status=inherit --search='Digital Garden'" );
WP_CLI::line( "  wp post list --post_type=attachment --post_status=inherit --search='Abstract Seed'" );
WP_CLI::line( '' );
