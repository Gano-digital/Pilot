<?php
/**
 * Plugin Name: Gano Digital — Fix: Unpublish Coming Soon (ID 1698)
 * Plugin URI:  https://gano.digital
 * Description: Fix único. Al activar: cambia el post ID 1698 (Coming Soon) de publish a draft. Desactivar y eliminar después de aplicado. Cierra issue #116.
 * Version:     1.0.0
 * Author:      Gano Digital
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

register_activation_hook( __FILE__, 'gano_fix_coming_soon_activate' );

/**
 * Cambia el post ID 1698 (Coming Soon) a estado draft.
 */
function gano_fix_coming_soon_activate(): void {
	$post_id = 1698;
	$post    = get_post( $post_id );

	if ( ! $post ) {
		add_action(
			'admin_notices',
			function() use ( $post_id ) {
				echo '<div class="notice notice-warning"><p>';
				echo esc_html(
					sprintf(
						/* translators: %d: post ID */
						__( 'Gano Fix Coming Soon: Post ID %d no encontrado. No se realizó ningún cambio.', 'gano-fix-coming-soon' ),
						$post_id
					)
				);
				echo '</p></div>';
			}
		);
		return;
	}

	if ( 'draft' === $post->post_status ) {
		// Ya está en draft, nada que hacer.
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
		add_action(
			'admin_notices',
			function() use ( $result ) {
				echo '<div class="notice notice-error"><p>';
				echo esc_html(
					sprintf(
						/* translators: %s: error message */
						__( 'Gano Fix Coming Soon: Error al actualizar el post: %s', 'gano-fix-coming-soon' ),
						$result->get_error_message()
					)
				);
				echo '</p></div>';
			}
		);
	}
}
