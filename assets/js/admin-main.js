!(function ($) {
	"use strict";
	// Product Extra Content - Admin Functions
	function freskaExtraContentHandlers() {
		// Edit / Create Elementor links - toggle based on section selection
		function updateGlobalEditElementorLink() {
			var $editLink = $('#freska_global_edit_elementor');
			var $createLink = $('#freska_global_create_elementor');
			var $select = $('#freska_global_extra_content_section_id');
			var baseUrl = $editLink.data('base-url');
			var sectionId = ($select.val() || '').toString().trim();
			if (sectionId && baseUrl) {
				$editLink.attr('href', baseUrl + '?post=' + sectionId + '&action=elementor');
				$editLink.show();
				$createLink.hide();
			} else {
				$editLink.hide();
				$createLink.show();
			}
		}

		// Extra Content mode select - show/hide sections
		function toggleExtraContentSections() {
			var mode = $('#freska_extra_content_mode').val();
			var $globalWrap = $('.freska-global-extra-section-wrap');
			var $statusRow = $('.freska-extra-status-row');
			if (mode === 'global') {
				$globalWrap.slideDown(150, updateGlobalEditElementorLink);
				$statusRow.slideUp(150);
			} else if (mode === 'current') {
				$globalWrap.slideUp(150);
				$statusRow.slideDown(150);
			} else {
				$globalWrap.slideUp(150);
				$statusRow.slideUp(150);
			}
			updateGlobalEditElementorLink();
		}
		$(document).on('change', '#freska_extra_content_mode', toggleExtraContentSections);
		toggleExtraContentSections();

		$(document).on('change', '#freska_global_extra_content_section_id', updateGlobalEditElementorLink);
		updateGlobalEditElementorLink();

		// Create Extra Content
		$(document).on('click', '.freska-create-extra-content', function (e) {
			e.preventDefault();

			var $button = $(this);
			var $box = $button.closest('.freska-extra-content-box');
			var $loading = $box.find('.freska-extra-loading');
			var productId = $button.data('product-id');

			if (!productId) {
				alert('Invalid Product ID!');
				return;
			}

			// Confirm before creating
			if (!confirm('Do you want to create Extra Content for this product?')) {
				return;
			}

			// Show loading
			$button.prop('disabled', true);
			$loading.show();

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'freska_create_extra_content',
					product_id: productId,
					nonce: freskaExtraContent.nonce
				},
				success: function (response) {
					if (response.success) {
						var newHTML = '<p class="freska-extra-status">' +
							'<span class="dashicons dashicons-yes-alt"></span>' +
							'Extra Content Created' +
							'</p>' +
							'<p>' +
							'<a href="' + response.data.edit_link + '" ' +
							'class="button button-primary button-large freska-edit-extra-content" ' +
							'target="_blank">' +
							'<span class="dashicons dashicons-edit"></span> ' +
							'Edit Extra Content' +
							'</a>' +
							'</p>' +
							'<p>' +
							'<button type="button" ' +
							'class="button button-link-delete freska-delete-extra-content" ' +
							'data-product-id="' + productId + '" ' +
							'data-extra-id="' + response.data.extra_content_id + '">' +
							'<span class="dashicons dashicons-trash"></span> ' +
							'Delete Extra Content' +
							'</button>' +
							'</p>';
						$box.find('.freska-extra-status-row').html(newHTML);

						// Update hidden field
						$('#freska_extra_content_post_id').val(response.data.extra_content_id);

						// Show notification
						if (typeof wp !== 'undefined' && wp.data) {
							wp.data.dispatch('core/notices').createNotice(
								'success',
								response.data.message,
								{ isDismissible: true }
							);
						} else {
							alert(response.data.message);
						}
						// Open Elementor editor in new tab
						window.open(response.data.edit_link, '_blank');

					} else {
						alert(response.data.message || 'An error occurred!');
					}
				},
				error: function () {
					alert('Server connection error!');
				},
				complete: function () {
					$button.prop('disabled', false);
					$loading.hide();
				}
			});
		});

		// Delete Extra Content
		$(document).on('click', '.freska-delete-extra-content', function (e) {
			e.preventDefault();

			var $button = $(this);
			var $box = $button.closest('.freska-extra-content-box');
			var $loading = $box.find('.freska-extra-loading');
			var productId = $button.data('product-id');
			var extraId = $button.data('extra-id');

			if (!productId || !extraId) {
				alert('Invalid ID!');
				return;
			}

			// Confirm before deleting
			if (!confirm('Are you sure you want to delete this Extra Content? This action cannot be undone!')) {
				return;
			}

			// Show loading
			$button.prop('disabled', true);
			$loading.show();

			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'freska_delete_extra_content',
					product_id: productId,
					extra_id: extraId,
					nonce: freskaExtraContent.nonce
				},
				success: function (response) {
					if (response.success) {
						var newHTML = '<p class="freska-extra-status">' +
							'<span class="dashicons dashicons-info"></span>' +
							'No Extra Content Yet' +
							'</p>' +
							'<p>' +
							'<button type="button" ' +
							'class="button button-primary button-large freska-create-extra-content" ' +
							'data-product-id="' + productId + '">' +
							'<span class="dashicons dashicons-plus-alt"></span> ' +
							'Create Extra Content' +
							'</button>' +
							'</p>';
						$box.find('.freska-extra-status-row').html(newHTML);

						// Update hidden field
						$('#freska_extra_content_post_id').val('');

						// Show notification
						if (typeof wp !== 'undefined' && wp.data) {
							wp.data.dispatch('core/notices').createNotice(
								'success',
								response.data.message,
								{ isDismissible: true }
							);
						} else {
							alert(response.data.message);
						}

					} else {
						alert(response.data.message || 'An error occurred!');
					}
				},
				error: function () {
					alert('Server connection error!');
				},
				complete: function () {
					$button.prop('disabled', false);
					$loading.hide();
				}
			});
		});
	}
	// Handle variation gallery images
	function freskaVariationGalleryHandlers() {
		// Add gallery images
		$(document).on('click', '.add-variation-gallery-image', function (e) {
			e.preventDefault();
			var button = $(this);
			var wrapper = button.closest('.variation-gallery-wrapper');

			var frame = wp.media({
				title: 'Add Gallery Images',
				multiple: true,
				library: { type: 'image' }
			});

			frame.on('select', function () {
				var selection = frame.state().get('selection');
				var attachmentIds = wrapper.find('.variation-gallery-ids').val();
				attachmentIds = attachmentIds ? attachmentIds.split(',') : [];

				selection.map(function (attachment) {
					attachment = attachment.toJSON();
					if (attachmentIds.indexOf(attachment.id.toString()) === -1) {
						attachmentIds.push(attachment.id);
						wrapper.find('.variation-gallery-images').append(
							'<div class="image" data-id="' + attachment.id + '">' +
							'<img src="' + attachment.sizes.thumbnail.url + '" />' +
							'<a href="#" class="delete-variation-gallery-image">×</a>' +
							'</div>'
						);
					}
				});

				wrapper.find('.variation-gallery-ids').val(attachmentIds.join(','));
				wrapper.closest('.woocommerce_variation').addClass('variation-needs-update');
			});

			frame.open();
		});

		// Remove gallery image
		$(document).on('click', '.delete-variation-gallery-image', function (e) {
			e.preventDefault();
			var wrapper = $(this).closest('.variation-gallery-wrapper');
			var image = $(this).closest('.image');
			var imageId = image.data('id');

			var attachmentIds = wrapper.find('.variation-gallery-ids').val().split(',');
			var index = attachmentIds.indexOf(imageId.toString());
			if (index > -1) {
				attachmentIds.splice(index, 1);
			}

			wrapper.find('.variation-gallery-ids').val(attachmentIds.join(','));
			wrapper.closest('.woocommerce_variation').addClass('variation-needs-update');
			image.remove();
		});
	}
	// Handle 360 GLB file upload
	function freska360GLBUploadHandlers() {
		var frame360;

		// Upload button click handler
		$('.upload_360_images_button').on('click', function (e) {
			e.preventDefault();

			if (frame360) {
				frame360.open();
				return;
			}

			frame360 = wp.media({
				title: 'Select GLB File for 360° View',
				button: {
					text: 'Use this file'
				},
				multiple: false,
				library: {
					type: ['model/gltf-binary', 'application/octet-stream']
				}
			});

			frame360.on('select', function () {
				var attachment = frame360.state().get('selection').first().toJSON();
				var fileId = attachment.id;
				var fileName = attachment.filename;
				var fileSize = attachment.filesizeHumanReadable;
				var fileUrl = attachment.url;

				// Update hidden input
				$('#_product_360_images').val(fileId);

				// Create preview HTML
				var previewHtml = '<div class="file-preview" style="display: flex; align-items: center; padding: 10px; background: #f5f5f5; border-radius: 4px; max-width: 400px;">';
				previewHtml += '<span class="dashicons dashicons-media-document" style="font-size: 24px; margin-right: 10px;"></span>';
				previewHtml += '<div style="flex: 1;">';
				previewHtml += '<strong>' + fileName + '</strong><br>';
				previewHtml += '<small style="color: #666;">' + fileSize + '</small>';
				previewHtml += '</div>';
				previewHtml += '<a href="' + fileUrl + '" target="_blank" class="button button-small" style="margin-left: 10px;">View</a>';
				previewHtml += '<button type="button" class="button button-small remove_360_file_button" style="margin-left: 5px; color: #b32d2e;">Remove</button>';
				previewHtml += '</div>';

				// Update or create preview container
				if ($('.product-360-preview').length) {
					$('.product-360-preview').html(previewHtml);
				} else {
					$('.product-360-fields').append('<div class="product-360-preview" style="margin-left: 150px;">' + previewHtml + '</div>');
				}
			});

			frame360.open();
		});

		// Remove button click handler
		$(document).on('click', '.remove_360_file_button', function (e) {
			e.preventDefault();
			$('#_product_360_images').val('');
			$('.product-360-preview').remove();
		});
	}
	// Handle product info fields
	function freskaToggleProductInfoFields() {
		var layoutValue = $('#_layout_product').val();
		var displayMode = $('#_product_info_display_mode').val();
		var $displayModeSelect = $('#_product_info_display_mode');
		var currentValue = $displayModeSelect.val();
		var thumbnailLayouts = ['bottom-thumbnail', 'left-thumbnail', 'right-thumbnail'];
		var isTabAllowed = thumbnailLayouts.indexOf(layoutValue) !== -1;

		// Handle Tab Position visibility
		$('.freska_tab_position_field')[displayMode === 'tab' && isTabAllowed ? 'show' : 'hide']();

		// Handle Toggle State visibility  
		$('.freska_toggle_state_field')[displayMode === 'toggle' ? 'show' : 'hide']();

		// If current value is 'tab' but layout doesn't support it, switch to toggle
		if (!isTabAllowed && currentValue === 'tab') {
			$displayModeSelect.val('toggle').trigger('change');
		}

		// Hide/show Tab option based on layout
		$displayModeSelect.find('option[value="tab"]')[isTabAllowed ? 'show' : 'hide']();


		// Handle gallery show more fields visibility
		var $gallerySettings = $('.gallery-show-more-settings');
		var galleryLayouts = [
			'gallery-one-column',
			'gallery-two-columns', 
			'gallery-three-columns',
			'gallery-four-columns',
			'gallery-grid-fullwidth',
			'gallery-stacked'
		];
		
		// Show/hide gallery settings based on layout
		if (galleryLayouts.indexOf(layoutValue) !== -1) {
			$gallerySettings.show();
		} else {
			$gallerySettings.hide();
		}
	}
	
	// Attribute types: image uploader + color picker (term edit/add forms)
	function freskaAttributeTypesHandlers() {
		if (!$('.freska-term-image-wrapper').length && !$('.freska-color-picker').length) {
			return;
		}
		// Image uploader
		$(document).on('click', '.freska-upload-image-button', function (e) {
			e.preventDefault();
			var button = $(this);
			var wrapper = button.closest('.freska-term-image-wrapper');
			var input = wrapper.find('input[type="hidden"]');
			var preview = wrapper.find('.freska-term-image-preview');
			var removeBtn = wrapper.find('.freska-remove-image-button');

			var frame = wp.media({
				title: 'Select Image',
				button: { text: 'Use this image' },
				multiple: false
			});

			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				input.val(attachment.id);
				preview.html('<img src="' + attachment.url + '" style="max-width: 150px; height: auto; display: block;" />');
				removeBtn.show();
			});

			frame.open();
		});

		$(document).on('click', '.freska-remove-image-button', function (e) {
			e.preventDefault();
			var button = $(this);
			var wrapper = button.closest('.freska-term-image-wrapper');
			var input = wrapper.find('input[type="hidden"]');
			var preview = wrapper.find('.freska-term-image-preview');
			input.val('');
			preview.html('');
			button.hide();
		});

		// Color picker init
		function initColorPicker() {
			if ($('.freska-color-picker').length) {
				$('.freska-color-picker').each(function () {
					if (!$(this).hasClass('wp-color-picker')) {
						$(this).wpColorPicker({
							change: function (event, ui) {
								var $input = $(this);
								$input.val(ui.color.toString());
								// Show input wrap when color has value
								$input.closest('.wp-picker-input-wrap').show();
							},
							clear: function () {
								var $input = $(this);
								if ($input.hasClass('wp-color-picker')) {
									$input.wpColorPicker('color', '');
								}
								// Hide input wrap when cleared
								$input.closest('.wp-picker-input-wrap').hide();
							}
						});
					}
				});
				// Initial state: hide input wrap when empty
				$('.freska-color-picker').each(function () {
					var $input = $(this);
					var $wrap = $input.closest('.wp-picker-input-wrap');
					if ($wrap.length) {
						if ($.trim($input.val())) {
							$wrap.show();
						} else {
							$wrap.hide();
						}
					}
				});
			}
		}
		initColorPicker();

		function syncColorValues() {
			$('.freska-color-picker').each(function () {
				if ($(this).hasClass('wp-color-picker')) {
					var colorValue = $(this).wpColorPicker('color');
					if (colorValue) {
						$(this).val(colorValue);
					}
				}
			});
		}

		$('#edittag, #addtag').on('submit', function () {
			syncColorValues();
		});

		$(document).ajaxSend(function (event, jqxhr, settings) {
			if (settings.data && (settings.data.indexOf('action=add-tag') !== -1 || settings.data.indexOf('action=inline-save-tax') !== -1)) {
				syncColorValues();
			}
		});

		$(document).ajaxComplete(function (event, xhr, settings) {
			if (settings.data && settings.data.indexOf('action=add-tag') !== -1 && xhr.status === 200) {
				setTimeout(function () {
					$('#addtag .freska-remove-image-button').trigger('click');
					$('#addtag .wp-picker-clear').trigger('click');
					initColorPicker();
				}, 200);
			}
		});
	}

	// Mega Menu handlers
	function freskaMegamenuHandlers() {
		if (!$('.freska-megamenu-fields').length) {
			return;
		}

		// Update edit link visibility and URL when dropdown changes
		function updateEditLink($select) {
			var blockId = $select.val(),
				$container = $select.closest('.freska-megamenu-fields'),
				$editLink = $container.find('.freska-megamenu-edit-link'),
				$metaSep = $container.find('.meta-sep'),
				baseUrl = $editLink.data('base-edit-url');

			if (blockId) {
				$editLink.attr('href', baseUrl.replace('%id%', blockId)).show();
				if ($metaSep.length === 0 && $editLink.length) {
					$editLink.after('<span class="meta-sep"> | </span>');
				}
				$metaSep.show();
			} else {
				$editLink.hide();
				$metaSep.hide();
			}
		}

		// Update Content Horizontal Position field visibility based on Content Width
		function updateHorizontalPositionVisibility($contentWidthSelect) {
			var contentWidth = $contentWidthSelect.val(),
				$container = $contentWidthSelect.closest('.freska-megamenu-fields'),
				$horizontalPositionField = $container.find('.freska-megamenu-horizontal-position-field');

			if (contentWidth === 'fit-to-content') {
				$horizontalPositionField.show();
			} else {
				$horizontalPositionField.hide();
			}
		}

		// Initialize edit link visibility on page load
		$('.freska-megamenu-select').each(function () {
			updateEditLink($(this));
		});

		// Initialize Content Horizontal Position visibility on page load
		$('.freska-megamenu-content-width-select').each(function () {
			updateHorizontalPositionVisibility($(this));
		});

		// Update when dropdown changes
		$(document).on('change', '.freska-megamenu-select', function () {
			updateEditLink($(this));
		});

		// Update Content Horizontal Position visibility when Content Width changes
		$(document).on('change', '.freska-megamenu-content-width-select', function () {
			updateHorizontalPositionVisibility($(this));
		});

		// Toggle visibility of dependent fields and bar label based on checkbox
		function toggleMegamenuFields($checkbox) {
			var $container = $checkbox.closest('.freska-megamenu-fields'),
				$dependentFields = $container.find('.freska-megamenu-dependent-fields'),
				$menuItem = $container.closest('.menu-item'),
				$itemTitle = $menuItem.find('.menu-item-bar .item-title');

			if ($checkbox.is(':checked')) {
				$dependentFields.slideDown(200);
				// Add badge on menu item bar if not exists
				if (!$itemTitle.find('.freska-megamenu-badge').length) {
					$itemTitle.append('<span class="freska-megamenu-badge">Mega Menu</span>');
				}
			} else {
				$dependentFields.slideUp(200);
				$itemTitle.find('.freska-megamenu-badge').remove();
			}
		}

		// Initialize visibility and bar label on page load
		$('.freska-megamenu-enable').each(function () {
			toggleMegamenuFields($(this));
		});

		// Update when checkbox changes
		$(document).on('change', '.freska-megamenu-enable', function () {
			toggleMegamenuFields($(this));
		});

		// Handle menu item added via AJAX (WordPress menu)
		$(document).ajaxComplete(function (event, xhr, settings) {
			if (settings.data && typeof settings.data === 'string' && settings.data.indexOf('action=add-menu-item') !== -1) {
				setTimeout(function () {
					$('.freska-megamenu-enable').each(function () {
						toggleMegamenuFields($(this));
					});
				}, 100);
			}
		});

		// Handle "Add megamenu" click
		$(document).on('click', '.freska-megamenu-add-link', function (e) {
			e.preventDefault();

			var $link = $(this),
				$container = $link.closest('.freska-megamenu-fields'),
				$select = $container.find('.freska-megamenu-select'),
				itemId = $link.data('item-id');

			// Show loading state
			var originalText = $link.text();
			$link.text(freskaMegamenu.megamenu.creating || 'Creating...').prop('disabled', true);

			// AJAX request to create block
			$.ajax({
				url: freskaMegamenu.ajaxurl,
				type: 'POST',
				data: {
					action: 'freska_create_megamenu',
					nonce: freskaMegamenu.nonce,
				},
				success: function (response) {
					if (response.success) {
						// Add new option to dropdown
						var option = $('<option>', {
							value: response.data.block_id,
							text: response.data.block_title || (freskaMegamenu.megamenu.newMegaMenu || 'New Mega Menu'),
							selected: true
						});
						$select.append(option);

						// Update edit link
						updateEditLink($select);

						// Open Elementor editor in new tab
						window.open(response.data.edit_link, '_blank');
					} else {
						alert(response.data.message || (freskaMegamenu.megamenu.errorCreating || 'Error creating mega menu'));
					}
				},
				error: function () {
					alert(freskaMegamenu.megamenu.errorCreating || 'Error creating mega menu');
				},
				complete: function () {
					$link.text(originalText).prop('disabled', false);
				}
			});
		});
	}

	// Icon Menu Admin Functions
	function freskaIconMenuHandlers() {
		if (!$('body.nav-menus-php').length) {
			return;
		}

		// Handle icon enable/disable
		$(document).on('change', '.freska-icon-enable', function() {
			var $checkbox = $(this);
			var $dependentFields = $checkbox.closest('.freska-megamenu-fields').find('.freska-icon-dependent-fields');
			
			if ($checkbox.is(':checked')) {
				$dependentFields.slideDown(200);
			} else {
				$dependentFields.slideUp(200);
			}
		});

		// Handle SVG upload
		$(document).on('click', '.freska-upload-svg-button', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var itemId = $button.data('item-id');
			var $urlInput = $('#edit-icon-svg-' + itemId);
			
			var mediaUploader = wp.media({
				title: 'Select SVG Icon',
				button: {
					text: 'Use this SVG'
				},
				library: {
					type: 'image/svg+xml'
				},
				multiple: false
			});
			
			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				$urlInput.val(attachment.url);
				
				// Remove any existing preview for this item
				$button.closest('.freska-icon-dependent-fields').find('.freska-icon-preview').remove();
				
				// Create new preview and insert after input
				var $preview = $('<div class="freska-icon-preview"></div>');
				$preview.html('<img src="' + attachment.url + '" alt="Icon preview" />');
				$urlInput.after($preview);
				
				// Show remove button
				$button.siblings('.freska-remove-svg-button').show();
			});
			
			mediaUploader.open();
		});
		
		// Handle SVG remove
		$(document).on('click', '.freska-remove-svg-button', function(e) {
			e.preventDefault();
			
			var $button = $(this);
			var itemId = $button.data('item-id');
			var $urlInput = $('#edit-icon-svg-' + itemId);
			
			// Clear input value
			$urlInput.val('');
			
			// Remove preview
			$button.closest('.freska-icon-dependent-fields').find('.freska-icon-preview').remove();
			
			// Hide remove button
			$button.hide();
		});
	}

	// Label Menu Admin Functions
	function freskaLabelMenuHandlers() {
		if (!$('body.nav-menus-php').length) {
			return;
		}
		// Function to initialize color picker
		function initColorPickerLabel() {
			$(".wp-color-picker").not(".wp-picker-active").wpColorPicker();
		}

		// Initialize color picker for existing fields
		initColorPickerLabel();

		// Initialize color picker for dynamically added menu items
		$(document).on("menu-item-added", function () {
			setTimeout(initColorPickerLabel, 100);
		});

		// Re-initialize when menu items are moved/sorted
		$("#menu-to-edit").on("sortstop", function () {
			setTimeout(initColorPickerLabel, 100);
		});

		// Re-initialize color picker when label fields are shown
		$(document).on("change", ".freska-label-enable", function () {
			var $checkbox = $(this);
			var $dependentFields = $checkbox.closest(".freska-megamenu-fields").find(".freska-label-dependent-fields");

			if ($checkbox.is(":checked")) {
				$dependentFields.slideDown(200, function () {
					// Re-initialize color picker after animation completes
					setTimeout(initColorPickerLabel, 50);
				});
			} else {
				$dependentFields.slideUp(200);
			}
		});

		// Handle click outside to close color picker properly
		$(document).on("click", function (e) {
			if (!$(e.target).closest(".wp-picker-container").length &&
				!$(e.target).hasClass("wp-color-picker") &&
				!$(e.target).hasClass("wp-picker-open")) {
				$(".wp-picker-container .wp-picker-open").each(function () {
					$(this).wpColorPicker("close");
				});
			}
		});
		// Add helpful tooltips
		$('.freska-label-enable').attr('title', 'Enable label for this menu item');
		$('input[name*="_freska_label_text"]').attr('title', 'Enter short text like NEW, HOT, SALE');
		$('input[name*="_freska_label_color"]').attr('title', 'Choose background color for the label');
	}

	jQuery(document).ready(function ($) {
		freskaExtraContentHandlers();
		freskaAttributeTypesHandlers();
		freskaMegamenuHandlers();
		freskaIconMenuHandlers();
		freskaLabelMenuHandlers();
		if (!$('#woocommerce-product-data').length) {
			return;
		}
		freskaVariationGalleryHandlers();
		freska360GLBUploadHandlers();
		freskaToggleProductInfoFields();
		$('#_layout_product, #_product_info_display_mode').on('change', freskaToggleProductInfoFields);
	});

	jQuery(window).on('resize', function () {

	});

	jQuery(window).on('scroll', function () {

	});
})(jQuery);
