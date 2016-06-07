jQuery(document).ready(function ($) {
	var $form       = $('.et-divi-100-form'),
		$menu_type  = $('#et_divi_100_custom_hamburger_menu-type'),
		$menu_style = $('#et_divi_100_custom_hamburger_menu-style'),
		$idTabs     = $('.idTabs'),
		$tabs_wrap  = $('#wrap-general');

	// Prepend preview container
	$form.find('.epanel-box[data-type="select"]:last .box-content').append( $( '<div />', { id : 'hamburger-menu-preview' }) );

	// Append Reference Styles
	$idTabs.append('<li><a href="#styles-reference">Styles Reference</a></li>');
	$tabs_wrap.append( $( '<div />', { id: 'styles-reference', class: 'tab-content', style: 'display: block; display: none;' } ).html('<p data-height="625" data-theme-id="21742" data-slug-hash="adVxNg" data-default-tab="result" data-user="elegantthemes" data-embed-version="2" class="codepen">See the Pen <a href="http://codepen.io/team/elegantthemes/pen/adVxNg/">Hamburger Menu</a> by Elegant Themes (<a href="http://codepen.io/elegantthemes">@elegantthemes</a>) on <a href="http://codepen.io">CodePen</a>.</p><script async src="//assets.codepen.io/assets/embed/ei.js"></script>') );

	// Add tab switching mechanism
	$idTabs.on( 'click', 'a', function(e) {
		e.preventDefault();

		var $clicked_link = $(this),
			$selected_tab = $( $clicked_link.attr('href') );

		$idTabs.find('li').removeClass('ui-tabs-active');
		$tabs_wrap.find('.tab-content').hide();

		$clicked_link.closest('li').addClass('ui-tabs-active');
		$selected_tab.fadeIn();
	});

	// Update preview
	function update_hamburger_menu_preview() {
		var $preview = $('#hamburger-menu-preview');

		if ( $menu_type.val() !== '' && $menu_style.val() !== '' ) {
			$preview.html( $( '<img />', {
				src : et_divi_100_js_params.preview_dir_url + 'hamburger-' + $menu_type.val()  + '-' + $menu_style.val() + '.gif',
				style : 'margin-top: 20px;'
			} ) );
		} else {
			$preview.empty();
		}
	}

	// Update preview on page load
	update_hamburger_menu_preview();

	// Live update preview
	$form.on( 'change', 'select', function() {
		update_hamburger_menu_preview();
	});

	// Loop each option
	$form.find('.epanel-box').each(function(){
		var $field = $(this),
			type = $field.attr('data-type'),
			$input,
			$reset_button,
			et_divi_100_file_frame;

		switch( type ) {
			case "color":
				$input = $field.find('input.colorpicker');

				$reset_button = $field.find( '.reset-color' );

				// Setup colorpicker
				$input.iris({
					hide : false,
					width : 350,
					palettes : true
				});

				// Reset color
				$reset_button.click( function(e) {
					e.preventDefault();
					$input.iris( 'color', $input.attr( 'data-default' ) );
				});

				break;
			case "toggle":
				var $input       = $field.find( 'select' ),
					$toggle      = $field.find( '.et_pb_yes_no_button' );

				$toggle.on( 'click', '.et_pb_value_text, .et_pb_button_slider', function(){
					var input_value  = $input.find('option:selected').val() === 'on' ? 'on' : 'off',
						toggle_state = input_value === 'on',
						toggle_new_state = toggle_state ? false : true,
						input_new_value  = toggle_new_state ? 'on' : 'off',
						toggle_state_class = 'et_pb_' + input_new_value + '_state';

					$input.val( input_new_value );
					$toggle.removeClass('et_pb_on_state et_pb_off_state').addClass( toggle_state_class );
				});

				break;
			case "upload":
				var $input_src = $field.find('.input-src'),
					$input_id = $field.find('.input-id'),
					$button_upload = $field.find('.button-upload'),
					$button_remove = $field.find('.button-remove'),
					button_upload_active_text = $button_upload.attr('data-button-active-text'),
					button_upload_inactive_text = $button_upload.attr('data-button-inactive-text'),
					media_uploader_title = $button_upload.attr('data-media-uploader-title'),
					media_uploader_button_text = $button_upload.attr('data-media-uploader-button-text'),
					$preview = $field.find('.option-preview');

				// Check background image status
				if ( $input_src.val() !== '' ) {
					$button_upload.text( button_upload_active_text);
					$button_remove.show();
				}

				// Upload background image button
				$button_upload.on( 'click', function( event ){
					event.preventDefault();

					// If the media frame already exists, reopen it.
					if ( et_divi_100_file_frame ) {

						// Open frame
						et_divi_100_file_frame.open();

						return;
					} else {

						// Create the media frame.
						et_divi_100_file_frame = wp.media.frames.et_divi_100_file_frame = wp.media({
							title: media_uploader_title,
							button: {
								text: media_uploader_button_text
							},
							multiple: false,
							library : {
								type: 'image'
							}
						});

						// When an image is selected, run a callback.
						et_divi_100_file_frame.on( 'select', function() {
							// We set multiple to false so only get one image from the uploader
							attachment = et_divi_100_file_frame.state().get('selection').first().toJSON();

							// Update input fields
							$input_src.val( attachment.url );

							$input_id.val( attachment.id );

							// Update Previewer
							$preview.html( $( '<img />', {
								src : attachment.url,
								style : 'max-width: 100%;'
							} ) );

							// Update button text
							$button_upload.text( button_upload_active_text );
							$button_remove.show();
						});

						// Finally, open the modal
						et_divi_100_file_frame.open();
					}
				});

				// Remove background image
				$button_remove.on( 'click', function( event ) {
					event.preventDefault();

					// Remove input
					$input_src.val('');
					$input_id.val('');

					// Remove preview
					$preview.empty();

					// Update button text
					$button_upload.text( button_upload_inactive_text );
					$button_remove.hide();
				});
				break;
		}
	});

	// Top button
	$('#epanel-save-top').click(function(e){
		e.preventDefault();

		$('#epanel-save').trigger('click');
	});

	// Help box
	$(".box-description").click(function(){
		var descheading = $(this).parent('.epanel-box').find(".box-title h3").html();
		var desctext = $(this).parent('.epanel-box').find(".box-title .box-descr").html();

		$('body').append("<div id='custom-lbox'><div class='box-desc'><div class='box-desc-top'>"+ et_divi_100_js_params.help_label +"</div><div class='box-desc-content'><h3>"+descheading+"</h3>"+desctext+"<div class='lightboxclose'></div> </div> <div class='box-desc-bottom'></div>	</div></div>");

		$( '.lightboxclose' ).click( function() {
			et_pb_close_modal( $( '#custom-lbox' ) );
		});
	});

	function et_pb_close_modal( $overlay, no_overlay_remove ) {
		var $modal_container = $overlay;

		// add class to apply the closing animation to modal
		$modal_container.addClass( 'et_pb_modal_closing' );

		//remove the modal with overlay when animation complete
		setTimeout( function() {
			if ( 'no_remove' !== no_overlay_remove ) {
				$modal_container.remove();
			}
		}, 600 );
	}
});