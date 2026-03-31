(function($) {
	'use strict';

	var WprWidgetBuilder = {
		controlCounter: 0,
		typesWithOptions: ['select', 'select2', 'choose'],

		init: function() {
			this.bindEvents();
			this.loadWidget();
			this.initSortable();
		},

		bindEvents: function() {
			var self = this;

			// Tab switching
			$(document).on('click', '.wpr-wb-tab', function() {
				var tab = $(this).data('tab');
				$('.wpr-wb-tab').removeClass('active');
				$(this).addClass('active');
				$('.wpr-wb-tab-panel').removeClass('active');
				$('.wpr-wb-tab-panel[data-tab="' + tab + '"]').addClass('active');
			});

			// Add control
			$(document).on('click', '.wpr-wb-add-control', function() {
				var tab = $(this).data('tab');
				self.addControl(tab);
				self.updateTabCounts();
			});

			// Remove control
			$(document).on('click', '.wpr-wb-control-remove', function(e) {
				e.stopPropagation();
				if (confirm('Remove this control?')) {
					$(this).closest('.wpr-wb-control-item').slideUp(200, function() {
						$(this).remove();
						self.updateTabCounts();
					});
				}
			});

			// Duplicate control
			$(document).on('click', '.wpr-wb-control-duplicate', function(e) {
				e.stopPropagation();
				var $item = $(this).closest('.wpr-wb-control-item');
				var tab = $item.closest('.wpr-wb-controls-list').data('tab');
				var data = self.getControlItemData($item);
				data.key = data.key + '_copy';
				data.label = data.label + ' (Copy)';
				self.addControl(tab, data);
				self.updateTabCounts();
			});

			// Toggle control
			$(document).on('click', '.wpr-wb-control-header', function(e) {
				if ($(e.target).closest('.wpr-wb-control-actions').length) return;
				$(this).closest('.wpr-wb-control-item').toggleClass('collapsed');
			});

			// Update control title and auto-generate key
			$(document).on('input', '.wpr-wb-ctrl-label', function() {
				var label = $(this).val() || 'New Control';
				var $item = $(this).closest('.wpr-wb-control-item');
				$item.find('.wpr-wb-control-title').text(label);

				// Auto-generate key if key field is empty or was auto-generated
				var $keyField = $item.find('.wpr-wb-ctrl-key');
				var currentKey = $keyField.val();
				if (!currentKey || $keyField.data('auto')) {
					var autoKey = label.toLowerCase()
						.replace(/[^a-z0-9]+/g, '_')
						.replace(/^_|_$/g, '')
						.substring(0, 40);
					$keyField.val(autoKey).data('auto', true);
				}
			});

			// Mark key as manually edited
			$(document).on('input', '.wpr-wb-ctrl-key', function() {
				$(this).data('auto', false);
			});

			// Show/hide options field based on type
			$(document).on('change', '.wpr-wb-ctrl-type', function() {
				var type = $(this).val();
				var wrap = $(this).closest('.wpr-wb-control-body').find('.wpr-wb-ctrl-options-wrap');
				if (self.typesWithOptions.indexOf(type) !== -1) {
					wrap.slideDown(150);
				} else {
					wrap.slideUp(150);
				}
			});

			// Save widget
			$('#wpr-wb-save').on('click', function() {
				self.saveWidget();
			});

			// Ctrl+S to save
			$(document).on('keydown', function(e) {
				if ((e.ctrlKey || e.metaKey) && e.key === 's') {
					e.preventDefault();
					self.saveWidget();
				}
			});

			// Collapse/Expand all controls
			$(document).on('click', '.wpr-wb-collapse-all', function() {
				var tab = $(this).closest('.wpr-wb-tab-panel').data('tab');
				var $items = $('.wpr-wb-controls-list[data-tab="' + tab + '"] .wpr-wb-control-item');
				var allCollapsed = $items.filter('.collapsed').length === $items.length;
				if (allCollapsed) {
					$items.removeClass('collapsed');
				} else {
					$items.addClass('collapsed');
				}
			});

			// Insert tag helper
			$(document).on('click', '.wpr-wb-insert-tag', function() {
				var tag = $(this).data('tag');
				var $textarea = $('#wpr-wb-markup');
				var pos = $textarea[0].selectionStart;
				var text = $textarea.val();
				$textarea.val(text.substring(0, pos) + tag + text.substring(pos));
				$textarea[0].selectionStart = $textarea[0].selectionEnd = pos + tag.length;
				$textarea.focus();
			});
		},

		initSortable: function() {
			if (!$.fn.sortable) return;

			$('.wpr-wb-controls-list').sortable({
				handle: '.wpr-wb-control-header',
				placeholder: 'wpr-wb-sortable-placeholder',
				opacity: 0.7,
				tolerance: 'pointer',
				cursor: 'move'
			});
		},

		getControlItemData: function($item) {
			var data = {
				key: $item.find('.wpr-wb-ctrl-key').val().trim(),
				label: $item.find('.wpr-wb-ctrl-label').val().trim(),
				type: $item.find('.wpr-wb-ctrl-type').val(),
				default: $item.find('.wpr-wb-ctrl-default').val(),
				section: $item.find('.wpr-wb-ctrl-section').val().trim() || 'general',
				section_label: $item.find('.wpr-wb-ctrl-section-label').val().trim() || 'General'
			};

			var optionsText = $item.find('.wpr-wb-ctrl-options').val().trim();
			if (optionsText) {
				data.options = {};
				optionsText.split('\n').forEach(function(line) {
					line = line.trim();
					if (line) {
						var parts = line.split('|');
						data.options[parts[0].trim()] = parts.length > 1 ? parts[1].trim() : parts[0].trim();
					}
				});
			}

			return data;
		},

		addControl: function(tab, data) {
			var template = $('#tmpl-wpr-wb-control').html();
			var $control = $(template);
			var key = 'control_' + (++this.controlCounter);

			$control.attr('data-key', key);

			if (data) {
				$control.find('.wpr-wb-ctrl-key').val(data.key || '');
				$control.find('.wpr-wb-ctrl-label').val(data.label || '');
				$control.find('.wpr-wb-ctrl-type').val(data.type || 'text');
				$control.find('.wpr-wb-ctrl-default').val(data.default || '');
				$control.find('.wpr-wb-ctrl-section').val(data.section || 'general');
				$control.find('.wpr-wb-ctrl-section-label').val(data.section_label || '');
				$control.find('.wpr-wb-control-title').text(data.label || 'New Control');

				// Type badge
				$control.find('.wpr-wb-control-type-badge').text(data.type || 'text');

				if (data.options) {
					var lines = [];
					for (var optKey in data.options) {
						if (data.options.hasOwnProperty(optKey)) {
							lines.push(optKey + '|' + data.options[optKey]);
						}
					}
					$control.find('.wpr-wb-ctrl-options').val(lines.join('\n'));
				}

				if (this.typesWithOptions.indexOf(data.type) !== -1) {
					$control.find('.wpr-wb-ctrl-options-wrap').show();
				}

				$control.addClass('collapsed');
			} else {
				// New control — focus the label field
				setTimeout(function() {
					$control.find('.wpr-wb-ctrl-label').focus();
				}, 50);
			}

			$('.wpr-wb-controls-list[data-tab="' + tab + '"]').append($control);

			// Update type badge on change
			$control.find('.wpr-wb-ctrl-type').on('change', function() {
				$control.find('.wpr-wb-control-type-badge').text($(this).val());
			});
		},

		updateTabCounts: function() {
			['content', 'style', 'advanced'].forEach(function(tab) {
				var count = $('.wpr-wb-controls-list[data-tab="' + tab + '"] .wpr-wb-control-item').length;
				var $badge = $('.wpr-wb-tab[data-tab="' + tab + '"] .wpr-wb-tab-count');
				if (count > 0) {
					if (!$badge.length) {
						$('.wpr-wb-tab[data-tab="' + tab + '"]').append(' <span class="wpr-wb-tab-count">' + count + '</span>');
					} else {
						$badge.text(count);
					}
				} else {
					$badge.remove();
				}
			});
		},

		getControlsData: function(tab) {
			var self = this;
			var controls = [];
			$('.wpr-wb-controls-list[data-tab="' + tab + '"] .wpr-wb-control-item').each(function() {
				var data = self.getControlItemData($(this));
				if (data.key) {
					controls.push(data);
				}
			});
			return controls;
		},

		collectData: function() {
			var cssIncludes = $('#wpr-wb-css-includes').val().trim();
			var jsIncludes = $('#wpr-wb-js-includes').val().trim();

			return {
				title: $('#wpr-wb-title').val().trim(),
				icon: $('#wpr-wb-icon').val().trim() || 'eicon-cog',
				categories: [$('#wpr-wb-category').val()],
				markup: $('#wpr-wb-markup').val(),
				css: $('#wpr-wb-css').val(),
				js: $('#wpr-wb-js').val(),
				css_includes: cssIncludes ? cssIncludes.split('\n').map(function(s) { return s.trim(); }).filter(Boolean) : [],
				js_includes: jsIncludes ? jsIncludes.split('\n').map(function(s) { return s.trim(); }).filter(Boolean) : [],
				tabs: {
					content: this.getControlsData('content'),
					style: this.getControlsData('style'),
					advanced: this.getControlsData('advanced')
				}
			};
		},

		populateData: function(data) {
			$('#wpr-wb-title').val(data.title || '');
			$('#wpr-wb-icon').val(data.icon || 'eicon-cog');

			if (data.categories && data.categories.length) {
				$('#wpr-wb-category').val(data.categories[0]);
			}

			$('#wpr-wb-markup').val(data.markup || '');
			$('#wpr-wb-css').val(data.css || '');
			$('#wpr-wb-js').val(data.js || '');

			if (data.css_includes) {
				var cssArr = Array.isArray(data.css_includes) ? data.css_includes : [];
				$('#wpr-wb-css-includes').val(cssArr.join('\n'));
			}
			if (data.js_includes) {
				var jsArr = Array.isArray(data.js_includes) ? data.js_includes : [];
				$('#wpr-wb-js-includes').val(jsArr.join('\n'));
			}

			// Populate controls
			var self = this;
			if (data.tabs) {
				['content', 'style', 'advanced'].forEach(function(tab) {
					if (data.tabs[tab] && Array.isArray(data.tabs[tab])) {
						data.tabs[tab].forEach(function(control) {
							self.addControl(tab, control);
						});
					}
				});
			}

			this.updateTabCounts();

			// Sync post title
			var $postTitle = $('#title');
			if ($postTitle.length && data.title) {
				$postTitle.val(data.title);
			}
		},

		setStatus: function(message, type) {
			var $status = $('#wpr-wb-status');
			$status.text(message).removeClass('error saving').addClass(type || '');

			if (type !== 'error') {
				setTimeout(function() {
					$status.text('');
				}, 3000);
			}
		},

		saveWidget: function() {
			var self = this;
			var data = this.collectData();
			var postId = wprWidgetBuilder.postId;

			if (!data.title) {
				this.setStatus('Please enter a widget title.', 'error');
				$('#wpr-wb-title').focus();
				return;
			}

			this.setStatus('Saving...', 'saving');
			$('#wpr-wb-save').prop('disabled', true).text('Saving...');

			var url = wprWidgetBuilder.apiBase + 'save';
			if (postId) {
				url += '/' + postId;
			}

			$.ajax({
				url: url,
				method: 'POST',
				contentType: 'application/json',
				data: JSON.stringify(data),
				beforeSend: function(xhr) {
					xhr.setRequestHeader('X-WP-Nonce', wprWidgetBuilder.nonce);
				},
				success: function(response) {
					if (response.success) {
						self.setStatus('Widget saved!', '');

						// Update post ID for new widgets
						if (response.post_id && !wprWidgetBuilder.postId) {
							wprWidgetBuilder.postId = response.post_id;
							window.location.href = 'post.php?post=' + response.post_id + '&action=edit&saved=1';
						}

						$('#title').val(data.title);
					} else {
						self.setStatus(response.message || 'Save failed.', 'error');
					}
				},
				error: function(xhr) {
					var msg = 'Save failed.';
					if (xhr.responseJSON && xhr.responseJSON.message) {
						msg = xhr.responseJSON.message;
					}
					self.setStatus(msg, 'error');
				},
				complete: function() {
					$('#wpr-wb-save').prop('disabled', false).text('Save Widget');
				}
			});
		},

		loadWidget: function() {
			var self = this;
			var postId = wprWidgetBuilder.postId;

			if (!postId) return;

			$.ajax({
				url: wprWidgetBuilder.apiBase + 'load/' + postId,
				method: 'GET',
				beforeSend: function(xhr) {
					xhr.setRequestHeader('X-WP-Nonce', wprWidgetBuilder.nonce);
				},
				success: function(response) {
					if (response.success && response.data) {
						self.populateData(response.data);
					}
				}
			});
		}
	};

	$(document).ready(function() {
		if ($('#wpr-widget-builder-app').length) {
			WprWidgetBuilder.init();
		}
	});

})(jQuery);
