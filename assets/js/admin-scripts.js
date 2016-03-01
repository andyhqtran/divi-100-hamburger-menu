jQuery(document).ready(function ($) {
	var $form_table = $('.et-divi-100-form-table'),
		$menu_type  = $('#et_divi_100_custom_hamburger_menu-type'),
		$menu_style = $('#et_divi_100_custom_hamburger_menu-style');

	// Prepend preview container
	$form_table.find('tr:last td:last').append( $( '<div />', { id : 'hamburger-menu-preview' }) );

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
});