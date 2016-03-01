jQuery(document).ready(function ($) {
	var $form_table = $('.et-divi-100-form'),
		$menu_type  = $('#et_divi_100_custom_hamburger_menu-type'),
		$menu_style = $('#et_divi_100_custom_hamburger_menu-style');

	// Prepend preview container
	$form_table.find('.epanel-box:last .box-content').append( $( '<div />', { id : 'hamburger-menu-preview' }) );

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
	$form_table.on( 'change', 'select', function() {
		update_hamburger_menu_preview();
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