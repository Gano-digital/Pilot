<?php
namespace WprAddons\Modules\WidgetBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles Elementor live preview for custom widgets.
 * When a custom widget post is opened with Elementor editor,
 * this sets up the canvas template and injects the widget for preview.
 */
class LiveAction {

	private $id;

	public function __construct() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$this->id = isset( $_GET['post'] ) ? intval( wp_unslash( $_GET['post'] ) ) : 0;

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $this->id === 0 || ! isset( $_GET['action'] ) || $_GET['action'] !== 'elementor' ) {
			return;
		}

		if ( get_post_type( $this->id ) !== 'wpr_custom_widget' ) {
			return;
		}

		add_action( 'init', [ $this, 'setup_elementor_preview' ] );
	}

	/**
	 * Set up the Elementor canvas and inject the custom widget for live preview.
	 */
	public function setup_elementor_preview() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		update_post_meta( $this->id, '_wp_page_template', 'elementor_canvas' );
		update_post_meta( $this->id, '_elementor_edit_mode', 'builder' );

		$widget_type = 'wpr_wb_' . $this->id;

		$elementor_data = '[{"id":"' . wp_generate_uuid4() . '","elType":"section","settings":[],"elements":[{"id":"' . wp_generate_uuid4() . '","elType":"column","settings":{"_column_size":100,"_inline_size":null},"elements":[{"id":"' . wp_generate_uuid4() . '","elType":"widget","settings":{},"elements":[],"widgetType":"' . $widget_type . '"}],"isInner":false}],"isInner":false}]';

		update_post_meta( $this->id, '_elementor_data', $elementor_data );
	}
}
