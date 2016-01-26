jQuery(document).ready(function ($) {
	// Update preview whenever select is changed
	$('.form-table select').change( function() {
		var $select          = $(this),
			preview_prefix   = $select.attr( 'data-preview-prefix' ),
			$selected_option = $select.find('option:selected'),
			selected_value   = $selected_option.val(),
			preview_file     = preview_prefix + selected_value,
			$preview_wrapper = $select.parents('td').find('.option-preview'),
			$preview;

		if( selected_value !== '' ) {
			$preview = $('<img />', {
				src : et_divi_100_.preview_dir_url + preview_file + '.gif'
			});

			$preview_wrapper.css({ 'minHeight' : 182 }).html( $preview );
		} else {
			$preview_wrapper.css({ 'minHeight' : '' }).empty();
		}
	});
});