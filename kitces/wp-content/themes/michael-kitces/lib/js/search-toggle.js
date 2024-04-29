jQuery(document).ready(function() {
	if ( ! jQuery('body').hasClass('search-open') ) {
		jQuery('.search-wrap .search-form-input').attr("disabled", true);
	} else {
		jQuery('.search-wrap .search-form-input').attr("disabled", false);
	}

	jQuery(".search-toggle a, button.search-toggle").on('click', function(e) {
		e.preventDefault();

		jQuery('.search-wrap .search-form-input').focus();
		jQuery('body').toggleClass('search-open');

		if ( ! jQuery('body').hasClass('search-open') ) {
			jQuery('.search-wrap .search-form-input').attr("disabled", true);
		} else {
			jQuery('.search-wrap .search-form-input').attr("disabled", false);
		}
	});
});
