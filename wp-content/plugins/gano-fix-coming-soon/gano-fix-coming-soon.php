<?php
/**
 * Plugin Name: Gano Digital — Fix: Unpublish Coming Soon (ID 1698)
 * Plugin URI:  https://gano.digital
 * Description: Fix único. Al activar: cambia el post ID 1698 (Coming Soon) de publish a draft. Protección en tiempo de ejecución: redirige /coming-soon a home y la oculta de menús públicos. Cierra issues #116 y #227.
 * Version:     1.1.0
 * Author:      Gano Digital
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** ID del post "Coming Soon" que debe permanecer oculto al público. */
const GANO_COMING_SOON_POST_ID = 1698;

register_activation_hook( __FILE__, 'gano_fix_coming_soon_activate' );
add_action( 'admin_notices', 'gano_fix_coming_soon_admin_notice' );
add_action( 'template_redirect', 'gano_redirect_coming_soon_post' );
add_filter( 'wp_nav_menu_objects', 'gano_filter_coming_soon_from_menus' );

/**
 * Redirige al inicio con 301 si alguien intenta acceder al post
 * "Coming Soon" (ID 1698) mientras está publicado.
 *
 * Los usuarios con capacidad `edit_posts` (editores y superiores) pueden
 * seguir accediendo para previsualizar o editar el post.
 *
 * Cubre el caso en que el post sea re-publicado accidentalmente tras la
 * ejecución del activation hook.
 */
function gano_redirect_coming_soon_post(): void {
	if ( ! is_singular() ) {
		return;
	}
	if ( current_user_can( 'edit_posts' ) ) {
		return;
	}
	$post = get_queried_object();
	if ( ! ( $post instanceof \WP_Post ) ) {
		return;
	}
	if ( absint( $post->ID ) === GANO_COMING_SOON_POST_ID ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}

/**
 * Elimina el post "Coming Soon" (ID 1698) de los menús de navegación públicos.
 *
 * @param \WP_Post[] $items  Ítems del menú.
 * @return \WP_Post[]
 */
function gano_filter_coming_soon_from_menus( array $items ): array {
	foreach ( $items as $key => $item ) {
		if (
			'post_type' === $item->type &&
			absint( $item->object_id ) === GANO_COMING_SOON_POST_ID
		) {
			unset( $items[ $key ] );
		}
	}
	return $items;
}

/**
 * Cambia el post ID 1698 (Coming Soon) a estado draft.
 * Guarda el resultado en un transient para mostrarlo en la siguiente carga de admin.
 */
function gano_fix_coming_soon_activate(): void {
	$post_id = GANO_COMING_SOON_POST_ID;
	$post    = get_post( $post_id );

	if ( ! $post ) {
		set_transient(
			'gano_fix_coming_soon_notice',
			array(
				'type'    => 'warning',
				/* translators: %d: post ID */
				'message' => sprintf( __( 'Gano Fix Coming Soon: Post ID %d no encontrado. No se realizó ningún cambio.', 'gano-fix-coming-soon' ), $post_id ),
			),
			45
		);
		return;
	}

	if ( 'draft' === $post->post_status ) {
		set_transient(
			'gano_fix_coming_soon_notice',
			array(
				'type'    => 'info',
				/* translators: %d: post ID */
				'message' => sprintf( __( 'Gano Fix Coming Soon: Post ID %d ya estaba en estado draft. No se realizó ningún cambio.', 'gano-fix-coming-soon' ), $post_id ),
			),
			45
		);
		return;
	}

	$result = wp_update_post(
		array(
			'ID'          => $post_id,
			'post_status' => 'draft',
		),
		true
	);

	if ( is_wp_error( $result ) ) {
		set_transient(
			'gano_fix_coming_soon_notice',
			array(
				'type'    => 'error',
				/* translators: %s: error message */
				'message' => sprintf( __( 'Gano Fix Coming Soon: Error al actualizar el post: %s', 'gano-fix-coming-soon' ), $result->get_error_message() ),
			),
			45
		);
	} else {
		set_transient(
			'gano_fix_coming_soon_notice',
			array(
				'type'    => 'success',
				/* translators: %d: post ID */
				'message' => sprintf( __( 'Gano Fix Coming Soon: Post ID %d actualizado a draft correctamente. /coming-soon ya no es accesible públicamente.', 'gano-fix-coming-soon' ), $post_id ),
			),
			45
		);
	}
}

/**
 * Muestra el aviso de resultado almacenado en transient y lo elimina.
 */
function gano_fix_coming_soon_admin_notice(): void {
	$notice = get_transient( 'gano_fix_coming_soon_notice' );
	if ( ! $notice ) {
		return;
	}
	delete_transient( 'gano_fix_coming_soon_notice' );

	printf(
		'<div class="notice notice-%s is-dismissible"><p>%s</p></div>',
		esc_attr( $notice['type'] ),
		esc_html( $notice['message'] )
	);
}
