jQuery(document).ready(function ($) {
	// Update preview whenever select is changed
	$('.form-table select').change( function() {
		var $preview_wrapper = $('.option-preview'),
			preview_prefix = 'hamburger-',
			$type = $('#hamburger-menu-type'),
			type = $type.find('option:selected').val(),
			$style = $('#hamburger-menu-style'),
			style = $style.find('option:selected').val(),
			preview_file = preview_prefix + type + '-' + style;

		if( type !== '' && style !== '' ) {
			$preview = $('<img />', {
				src : et_divi_100_.preview_dir_url + preview_file + '.gif'
			});

			$preview_wrapper.css({ 'minHeight' : 65 }).html( $preview );
		} else {
			$preview_wrapper.css({ 'minHeight' : '' }).empty();
		}
	});
});