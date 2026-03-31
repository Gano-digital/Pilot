<?php
namespace WprAddons\Modules\WidgetBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Init {

	private $dir;
	private $url;

	public function __construct() {
		$this->dir = dirname( __FILE__ ) . '/';
		$this->url = WPR_ADDONS_MODULES_URL . 'widget-builder/';

		new Cpt();
		new Api\Endpoints();
		new LiveAction();

		// Admin UI
		add_action( 'add_meta_boxes', [ $this, 'register_meta_boxes' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );
		add_action( 'admin_notices', [ $this, 'admin_notices' ] );

		// Elementor editor CSS for custom widget preview
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'editor_css' ] );

		// Register custom widgets with Elementor
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		// Cleanup on widget deletion
		add_action( 'before_delete_post', [ $this, 'on_delete_widget' ] );
	}

	public function register_meta_boxes() {
		add_meta_box(
			'wpr-widget-builder',
			esc_html__( 'Widget Builder', 'wpr-addons' ),
			[ $this, 'render_meta_box' ],
			'wpr_custom_widget',
			'normal',
			'high'
		);
	}

	public function render_meta_box( $post ) {
		include $this->dir . 'views/builder.php';
	}

	public function enqueue_admin_scripts() {
		$screen = get_current_screen();

		if ( ! $screen || $screen->id !== 'wpr_custom_widget' ) {
			return;
		}

		// Elementor icons font (for icon preview & UI icons)
		wp_enqueue_style( 'elementor-icons' );

		// Google Roboto font
		wp_enqueue_style(
			'wpr-wb-google-roboto',
			'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap',
			[],
			null
		);

		// Visual builder CSS
		wp_enqueue_style(
			'wpr-widget-builder-visual',
			$this->url . 'assets/css/widget-builder-visual.css',
			[ 'elementor-icons' ],
			WPR_ADDONS_VERSION
		);

		// Visual builder JS
		wp_enqueue_script(
			'wpr-widget-builder-visual',
			$this->url . 'assets/js/widget-builder-visual.js',
			[ 'jquery', 'jquery-ui-sortable' ],
			WPR_ADDONS_VERSION,
			true
		);
	}

	public function register_widgets( $widgets_manager ) {
		$widgets = get_posts([
			'post_type'   => 'wpr_custom_widget',
			'post_status' => 'publish',
			'numberposts' => -1,
		]);

		$upload = wp_upload_dir();

		foreach ( $widgets as $widget ) {
			$slug       = 'wpr_wb_' . $widget->ID;
			$dir        = $upload['basedir'] . '/wpr-addons/custom_widgets/' . $slug . '/';
			$file       = $dir . 'widget.php';
			$class_name = '\Elementor\Wpr_Wb_' . $widget->ID;

			if ( file_exists( $file ) ) {
				include_once $file;
				if ( class_exists( $class_name ) ) {
					$widgets_manager->register( new $class_name() );
				}
			}
		}
	}

	public function admin_notices() {
		$screen = get_current_screen();

		if ( ! $screen || $screen->id !== 'wpr_custom_widget' ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['saved'] ) && $_GET['saved'] === '1' ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Widget saved and generated successfully!', 'wpr-addons' ) . '</p></div>';
		}
	}

	public function editor_css() {
		$screen = get_current_screen();

		if ( $screen && $screen->id === 'wpr_custom_widget' ) {
			wp_enqueue_style(
				'wpr-widget-builder-editor',
				$this->url . 'assets/css/widget-builder-editor.css',
				[],
				WPR_ADDONS_VERSION
			);
		}
	}

	public function on_delete_widget( $post_id ) {
		if ( get_post_type( $post_id ) !== 'wpr_custom_widget' ) {
			return;
		}

		$upload = wp_upload_dir();
		$slug   = 'wpr_wb_' . $post_id;
		$dir    = $upload['basedir'] . '/wpr-addons/custom_widgets/' . $slug . '/';

		if ( is_dir( $dir ) ) {
			$files = glob( $dir . '*' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					wp_delete_file( $file );
				}
			}
			rmdir( $dir );
		}
	}
}
