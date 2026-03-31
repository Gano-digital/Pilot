<?php
namespace WprAddons\Admin\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WPR_Editor_Hooks
 *
 * Handles Elementor editor hooks and modifications
 * Including removal of Elementor Pro promotion widgets
 */
class WPR_Editor_Hooks {

	/**
	 * Instance of this class
	 *
	 * @var WPR_Editor_Hooks
	 */
	private static $_instance = null;

	/**
	 * Get instance
	 *
	 * @return WPR_Editor_Hooks
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		$this->add_hooks();
	}

	/**
	 * Register all hooks
	 */
	private function add_hooks() {
		// Remove Elementor Pro Promotion Widgets - Only in editor (not during import)
		if ( current_user_can('administrator') ) {
			add_action('elementor/editor/before_enqueue_scripts', [$this, 'conditional_remove_elementor_pro_promotions'], 1);
			// Detect widgets when page is saved
			add_action('elementor/editor/after_save', [$this, 'detect_widgets_on_save'], 10, 2);
		}
	}

	/**
	 * Remove pro widgets from Elementor transient
	 *
	 * @param mixed $transient Transient data
	 * @return mixed Modified transient data
	 */
	public function remove_pro_widgets_from_transient($transient) {
		// Remove pro_widgets from the Elementor API data
		if ($transient && isset($transient['pro_widgets'])) {
			$transient['pro_widgets'] = [];
		}
		return $transient;
	}

	/**
	 * Conditionally remove Elementor Pro promotions
	 * Uses persistent flag for performance - once Royal widgets are used, always hide promotions
	 */
	public function conditional_remove_elementor_pro_promotions() {
		// Check persistent flag first (most efficient) - works everywhere
		$flag = get_option('wpr_has_used_royal_widgets');

		if ($flag) {
			$this->remove_elementor_promotions();
			return;
		}

		// Only continue for first-time detection in actual Elementor editor (not during imports)
		$is_editor = isset(\Elementor\Plugin::$instance->editor);
		$is_edit_mode = $is_editor && \Elementor\Plugin::$instance->editor->is_edit_mode();

		if (!$is_editor || !$is_edit_mode) {
			return;
		}

		// Fallback: Check current page for first-time detection
		$post_id = get_the_ID();

		if (!$post_id) {
			return;
		}

		// Check if this page uses Royal widgets
		$has_widgets = $this->page_has_royal_widgets($post_id);

		if ($has_widgets) {
			// Set persistent flag for future use
			update_option('wpr_has_used_royal_widgets', time());

			// Remove promotions
			$this->remove_elementor_promotions();
		}
	}

	/**
	 * Detect Royal widgets when page is saved and set persistent flag
	 *
	 * @param int $post_id Post ID that was saved
	 * @param array $data Elementor data being saved
	 */
	public function detect_widgets_on_save($post_id, $data) {
		// Skip if flag already set
		if (get_option('wpr_has_used_royal_widgets')) {
			return;
		}

		// Clear cache to force fresh detection
		delete_transient('wpr_has_widgets_' . $post_id);

		// Check if this page has Royal widgets
		if ($this->page_has_royal_widgets($post_id)) {
			update_option('wpr_has_used_royal_widgets', time());
		}
	}

	/**
	 * Apply filters to remove Elementor promotions
	 */
	private function remove_elementor_promotions() {
		add_filter('pre_transient_elementor_remote_info_api_data_' . ELEMENTOR_VERSION,
				   [$this, 'remove_pro_widgets_from_transient'], 999);
		add_filter('transient_elementor_remote_info_api_data_' . ELEMENTOR_VERSION,
				   [$this, 'remove_pro_widgets_from_transient'], 999);
	}

	/**
	 * Check if a page has Royal Addons widgets
	 *
	 * @param int $post_id Post ID to check
	 * @return bool True if Royal widgets found
	 */
	private function page_has_royal_widgets($post_id) {
		// Check cache first
		$cache_key = 'wpr_has_widgets_' . $post_id;
		$cached = get_transient($cache_key);
		if (false !== $cached) {
			return $cached === 'yes';
		}

		$has_widgets = false;

		// Method 1: Check _elementor_controls_usage (fastest and most reliable)
		$controls_usage = get_post_meta($post_id, '_elementor_controls_usage', true);

		if (is_array($controls_usage)) {
			foreach ($controls_usage as $widget_type => $data) {
				if (is_string($widget_type) && strpos($widget_type, 'wpr-') === 0) {
					$has_widgets = true;
					break;
				}
			}
		}

		// Method 2: Fallback to _elementor_data for unsaved pages
		if (!$has_widgets) {
			$elementor_data = get_post_meta($post_id, '_elementor_data', true);

			if (!empty($elementor_data)) {
				// Handle both array (during import) and string (normal) cases
				if (is_array($elementor_data)) {
					$elementor_data = wp_json_encode($elementor_data);
				}

				if (is_string($elementor_data)) {
					if (strpos($elementor_data, '"widgetType":"wpr-') !== false) {
						$has_widgets = true;
					}
				}
			}
		}

		// Cache result for 1 hour
		$cache_value = $has_widgets ? 'yes' : 'no';
		set_transient($cache_key, $cache_value, HOUR_IN_SECONDS);

		return $has_widgets;
	}

}

// Initialize
WPR_Editor_Hooks::instance();
