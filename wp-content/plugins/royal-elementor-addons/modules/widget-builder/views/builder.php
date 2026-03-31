<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:ignore WordPress.Security.NonceVerification.Recommended
$widget_id    = isset( $_GET['post'] ) ? intval( wp_unslash( $_GET['post'] ) ) : 0;
$widget_title = $widget_id ? get_the_title( $widget_id ) : esc_html__( 'New Widget', 'wpr-addons' );
$back_url     = admin_url( 'edit.php?post_type=wpr_custom_widget' );
$preview_url  = '';

if ( $widget_id ) {
	$preview_url = str_replace(
		[ '&amp;', 'action=edit' ],
		[ '&', 'action=elementor' ],
		get_edit_post_link( $widget_id )
	);
}
?>
<script>
	var wprWidgetBuilder = {
		apiBase: '<?php echo esc_url( rest_url( 'wpr-addons/v1/widget-builder/' ) ); ?>',
		postId: <?php echo intval( $widget_id ); ?>,
		nonce: '<?php echo esc_js( wp_create_nonce( 'wp_rest' ) ); ?>',
		previewUrl: '<?php echo esc_url( $preview_url ); ?>',
		backUrl: '<?php echo esc_url( $back_url ); ?>',
		eiconsUrl: '<?php echo esc_url( ELEMENTOR_ASSETS_URL . 'lib/eicons/eicons.json' ); ?>'
	};
</script>

<div id="wpr-wb-app">

	<!-- LEFT PANEL: Settings -->
	<div class="wpr-wb-panel wpr-wb-panel-left">
		<!-- Panel Header -->
		<div class="wpr-wb-panel-header">
			<span class="wpr-wb-panel-header-title" id="wpr-wb-header-title"><?php echo esc_html( $widget_title ); ?></span>
			<button type="button" class="wpr-wb-panel-collapse-btn" id="wpr-wb-collapse-left" title="<?php esc_attr_e( 'Collapse Panel', 'wpr-addons' ); ?>">
				<i class="eicon-chevron-left"></i>
			</button>
		</div>

		<!-- Settings Content -->
		<div class="wpr-wb-panel-body" id="wpr-wb-settings-body">
			<div class="wpr-wb-setting-group">
				<label class="wpr-wb-setting-label"><?php esc_html_e( 'Widget Title', 'wpr-addons' ); ?></label>
				<input type="text" id="wpr-wb-title" class="wpr-wb-setting-input" value="<?php echo esc_attr( $widget_title ); ?>" placeholder="<?php esc_attr_e( 'New Widget', 'wpr-addons' ); ?>">
				<span class="wpr-wb-setting-desc"><?php esc_html_e( 'The Widget Title will show on the widgets list.', 'wpr-addons' ); ?></span>
			</div>

			<div class="wpr-wb-setting-group">
				<label class="wpr-wb-setting-label"><?php esc_html_e( 'Widget Icon', 'wpr-addons' ); ?></label>
				<div class="wpr-wb-icon-preview-wrap">
					<div class="wpr-wb-icon-preview" id="wpr-wb-icon-preview">
						<i class="eicon-cog" id="wpr-wb-icon-preview-i"></i>
					</div>
					<button type="button" class="wpr-wb-btn-outline" id="wpr-wb-change-icon"><?php esc_html_e( 'Change Icon', 'wpr-addons' ); ?></button>
				</div>
				<input type="hidden" id="wpr-wb-icon" value="eicon-cog">
				<span class="wpr-wb-setting-desc"><?php esc_html_e( 'It lets you set the widget icon. You can use any of the eicon or font-awesome icons, simply return the class name as a string.', 'wpr-addons' ); ?></span>
			</div>

			<div class="wpr-wb-setting-group">
				<label class="wpr-wb-setting-label"><?php esc_html_e( 'Widget Category', 'wpr-addons' ); ?></label>
				<select id="wpr-wb-category" class="wpr-wb-setting-input">
					<option value="basic"><?php esc_html_e( 'Basic', 'wpr-addons' ); ?></option>
					<option value="general"><?php esc_html_e( 'General', 'wpr-addons' ); ?></option>
					<option value="wpr-widgets" selected><?php esc_html_e( 'Royal Addons', 'wpr-addons' ); ?></option>
				</select>
				<span class="wpr-wb-setting-desc"><?php esc_html_e( 'Widget categories in Elementor are used to organize the widgets into groups.', 'wpr-addons' ); ?></span>
			</div>
		</div>

		<!-- Bottom Action Bar -->
		<div class="wpr-wb-panel-footer">
			<button type="button" class="wpr-wb-footer-btn" id="wpr-wb-btn-preview" title="<?php esc_attr_e( 'Preview in Elementor', 'wpr-addons' ); ?>">
				<?php esc_html_e( 'PREVIEW', 'wpr-addons' ); ?>
			</button>
			<button type="button" class="wpr-wb-footer-btn wpr-wb-footer-btn-save" id="wpr-wb-btn-save" title="<?php esc_attr_e( 'Save Widget', 'wpr-addons' ); ?>">
				<?php esc_html_e( 'SAVE', 'wpr-addons' ); ?>
			</button>
		</div>
		<button type="button" class="wpr-wb-collapsed-save-btn" id="wpr-wb-collapsed-save" title="<?php esc_attr_e( 'Save Widget', 'wpr-addons' ); ?>">
			<i class="eicon-save"></i>
		</button>
	</div>

	<!-- CENTER PANEL: Controls Builder -->
	<div class="wpr-wb-panel wpr-wb-panel-center">
		<!-- Tabs -->
		<div class="wpr-wb-center-tabs">
			<button type="button" class="wpr-wb-center-tab active" data-tab="content">
				<i class="eicon-edit"></i>
				<span><?php esc_html_e( 'Content', 'wpr-addons' ); ?></span>
			</button>
			<button type="button" class="wpr-wb-center-tab" data-tab="style">
				<i class="eicon-paint-brush"></i>
				<span><?php esc_html_e( 'Style', 'wpr-addons' ); ?></span>
			</button>
			<button type="button" class="wpr-wb-center-tab" data-tab="advanced">
				<i class="eicon-cog"></i>
				<span><?php esc_html_e( 'Advanced', 'wpr-addons' ); ?></span>
			</button>
		</div>

		<!-- Tab Content Areas -->
		<div class="wpr-wb-center-body">
			<div class="wpr-wb-center-panel active" data-tab="content">
				<div class="wpr-wb-sections-list" data-tab="content"></div>
				<div class="wpr-wb-add-section-wrap">
					<button type="button" class="wpr-wb-add-section-btn" data-tab="content">
						<span class="wpr-wb-add-section-icon">+</span>
					</button>
					<span class="wpr-wb-add-section-label"><?php esc_html_e( 'Add Section', 'wpr-addons' ); ?></span>
				</div>
			</div>
			<div class="wpr-wb-center-panel" data-tab="style">
				<div class="wpr-wb-sections-list" data-tab="style"></div>
				<div class="wpr-wb-add-section-wrap">
					<button type="button" class="wpr-wb-add-section-btn" data-tab="style">
						<span class="wpr-wb-add-section-icon">+</span>
					</button>
					<span class="wpr-wb-add-section-label"><?php esc_html_e( 'Add Section', 'wpr-addons' ); ?></span>
				</div>
			</div>
			<div class="wpr-wb-center-panel" data-tab="advanced">
				<div class="wpr-wb-sections-list" data-tab="advanced"></div>
				<div class="wpr-wb-add-section-wrap">
					<button type="button" class="wpr-wb-add-section-btn" data-tab="advanced">
						<span class="wpr-wb-add-section-icon">+</span>
					</button>
					<span class="wpr-wb-add-section-label"><?php esc_html_e( 'Add Section', 'wpr-addons' ); ?></span>
				</div>
			</div>
		</div>
	</div>

	<!-- RIGHT PANEL: Code Editor -->
	<div class="wpr-wb-panel wpr-wb-panel-right">
		<!-- Code Tabs -->
		<div class="wpr-wb-code-tabs">
			<button type="button" class="wpr-wb-code-tab active" data-editor="html"><?php esc_html_e( 'HTML', 'wpr-addons' ); ?></button>
			<button type="button" class="wpr-wb-code-tab" data-editor="css"><?php esc_html_e( 'CSS', 'wpr-addons' ); ?></button>
			<button type="button" class="wpr-wb-code-tab" data-editor="js"><?php esc_html_e( 'JavaScript', 'wpr-addons' ); ?></button>
			<button type="button" class="wpr-wb-code-tab" data-editor="includes"><?php esc_html_e( 'CSS/JS Includes', 'wpr-addons' ); ?></button>
			<button type="button" class="wpr-wb-code-info-btn" id="wpr-wb-template-info-btn" title="<?php esc_attr_e( 'Template Tags Reference', 'wpr-addons' ); ?>"><i class="eicon-info-circle-o"></i></button>
		</div>

		<!-- Template Tags Info Popup -->
		<div id="wpr-wb-template-info-popup" class="wpr-wb-template-info-popup">
			<div class="wpr-wb-template-info-inner">
				<div class="wpr-wb-template-info-header">
					<span><?php esc_html_e( 'Template Tags Reference', 'wpr-addons' ); ?></span>
					<button type="button" class="wpr-wb-template-info-close"><i class="eicon-close"></i></button>
				</div>
				<div class="wpr-wb-template-info-body">
					<p><?php esc_html_e( 'Write your widget\'s HTML here. To show values from the controls you created on the left panel, use template tags.', 'wpr-addons' ); ?></p>
					<p><?php esc_html_e( 'Template tags look like {{key}} — when the widget renders, they get replaced with whatever the user enters in that control. The "key" is the control\'s unique name shown in the left panel (e.g. text_1, color_2).', 'wpr-addons' ); ?></p>
					<p><?php echo wp_kses( __( 'Example: if you have a Text control with key "title_1", writing <code>&lt;h2&gt;{{title_1}}&lt;/h2&gt;</code> here will display whatever text the user types into that field.', 'wpr-addons' ), [ 'code' => [] ] ); ?></p>
					<h4><?php esc_html_e( 'Output Values', 'wpr-addons' ); ?></h4>
					<table>
						<tr><td><code>{{key}}</code></td><td><?php esc_html_e( 'Text, Number, Textarea, Select, Choose, Color, Switcher, Hidden, Font, Date/Time', 'wpr-addons' ); ?></td></tr>
						<tr><td><code>{{key.url}}</code></td><td><?php esc_html_e( 'Media — outputs the image/file URL', 'wpr-addons' ); ?></td></tr>
						<tr><td><code>{{key.size}}</code></td><td><?php esc_html_e( 'Slider — outputs the numeric value', 'wpr-addons' ); ?></td></tr>
						<tr><td><code>{{icon(key)}}</code></td><td><?php esc_html_e( 'Icons — renders the selected icon SVG/font', 'wpr-addons' ); ?></td></tr>
						<tr><td><code>{{key}}</code></td><td><?php esc_html_e( 'Code — displays escaped code in &lt;pre&gt;&lt;code&gt; block', 'wpr-addons' ); ?></td></tr>
						<tr><td><code>{{key}}</code></td><td><?php esc_html_e( 'URL — outputs the link URL', 'wpr-addons' ); ?></td></tr>
					</table>

					<h4><?php esc_html_e( 'Conditionals', 'wpr-addons' ); ?></h4>
					<table>
						<tr><td><code>{{#if key}}</code></td><td><?php esc_html_e( 'Show block if value is not empty (great for Switcher)', 'wpr-addons' ); ?></td></tr>
						<tr><td><code>{{#if key == val}}</code></td><td><?php esc_html_e( 'Show block if value equals "val" (great for Select/Choose)', 'wpr-addons' ); ?></td></tr>
						<tr><td><code>{{#if key != val}}</code></td><td><?php esc_html_e( 'Show block if value does NOT equal "val"', 'wpr-addons' ); ?></td></tr>
						<tr><td><code>{{else}}</code></td><td><?php esc_html_e( 'Optional else branch', 'wpr-addons' ); ?></td></tr>
						<tr><td><code>{{/if}}</code></td><td><?php esc_html_e( 'End conditional block', 'wpr-addons' ); ?></td></tr>
					</table>

					<h4><?php esc_html_e( 'Examples', 'wpr-addons' ); ?></h4>
					<pre><code>&lt;h2&gt;{{title_1}}&lt;/h2&gt;
&lt;img src="{{media_1.url}}"&gt;
{{icon(icons_1)}}

{{#if switcher_1}}
  &lt;span class="badge"&gt;New&lt;/span&gt;
{{/if}}

{{#if select_1 == grid}}
  &lt;div class="grid"&gt;...&lt;/div&gt;
{{else}}
  &lt;div class="list"&gt;...&lt;/div&gt;
{{/if}}</code></pre>

					<h4><?php esc_html_e( 'Notes', 'wpr-addons' ); ?></h4>
					<ul>
						<li><?php esc_html_e( 'CSS Styles use {{VALUE}}, {{SIZE}}{{UNIT}}, {{TOP}}, etc. — these are Elementor placeholders, not template tags.', 'wpr-addons' ); ?></li>
						<li><?php esc_html_e( 'Switcher returns "yes" when ON and "" (empty) when OFF.', 'wpr-addons' ); ?></li>
						<li><?php esc_html_e( 'Group controls (Typography, Background, Border) only use CSS Styles — no template tags needed.', 'wpr-addons' ); ?></li>
					</ul>
				</div>
			</div>
		</div>

		<!-- Editor Areas -->
		<div class="wpr-wb-code-body">
			<div class="wpr-wb-code-panel active" data-editor="html">
				<div id="wpr-wb-editor-html" class="wpr-wb-monaco-editor"></div>
			</div>
			<div class="wpr-wb-code-panel" data-editor="css">
				<div id="wpr-wb-editor-css" class="wpr-wb-monaco-editor"></div>
			</div>
			<div class="wpr-wb-code-panel" data-editor="js">
				<div id="wpr-wb-editor-js" class="wpr-wb-monaco-editor"></div>
			</div>
			<div class="wpr-wb-code-panel" data-editor="includes">
				<div class="wpr-wb-includes-panel">
					<div class="wpr-wb-includes-group">
						<label class="wpr-wb-includes-label"><?php esc_html_e( 'External CSS URLs (one per line)', 'wpr-addons' ); ?></label>
						<textarea id="wpr-wb-css-includes" class="wpr-wb-includes-textarea" rows="5" placeholder="https://example.com/style.css"></textarea>
					</div>
					<div class="wpr-wb-includes-group">
						<label class="wpr-wb-includes-label"><?php esc_html_e( 'External JS URLs (one per line)', 'wpr-addons' ); ?></label>
						<textarea id="wpr-wb-js-includes" class="wpr-wb-includes-textarea" rows="5" placeholder="https://example.com/script.js"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>

</div>

<!-- Status Toast -->
<div id="wpr-wb-toast" class="wpr-wb-toast"></div>

<!-- Control Type Picker Modal -->
<div id="wpr-wb-control-modal" class="wpr-wb-modal-overlay">
	<div class="wpr-wb-modal wpr-wb-modal-lg">
		<div class="wpr-wb-modal-header">
			<span class="wpr-wb-modal-title"><?php esc_html_e( 'Add Control', 'wpr-addons' ); ?></span>
			<button type="button" class="wpr-wb-modal-close" id="wpr-wb-control-modal-close">&times;</button>
		</div>
		<div class="wpr-wb-modal-search">
			<input type="text" id="wpr-wb-control-search" class="wpr-wb-setting-input" placeholder="<?php esc_attr_e( 'Search control types...', 'wpr-addons' ); ?>">
		</div>
		<div class="wpr-wb-control-type-grid" id="wpr-wb-control-type-grid">
			<!-- Populated via JS -->
		</div>
	</div>
</div>

<!-- Icon Picker Modal -->
<div id="wpr-wb-icon-modal" class="wpr-wb-modal-overlay">
	<div class="wpr-wb-modal">
		<div class="wpr-wb-modal-header">
			<span class="wpr-wb-modal-title"><?php esc_html_e( 'Choose Icon', 'wpr-addons' ); ?></span>
			<button type="button" class="wpr-wb-modal-close" id="wpr-wb-icon-modal-close">&times;</button>
		</div>
		<div class="wpr-wb-modal-search">
			<input type="text" id="wpr-wb-icon-search" class="wpr-wb-setting-input" placeholder="<?php esc_attr_e( 'Search icons...', 'wpr-addons' ); ?>">
		</div>
		<div class="wpr-wb-icon-grid" id="wpr-wb-icon-grid">
			<!-- Icons populated via JS -->
		</div>
	</div>
</div>
