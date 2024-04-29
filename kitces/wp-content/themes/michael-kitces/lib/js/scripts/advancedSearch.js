const searchControl = (parent, fit = false) => {
	const form = jQuery(`${parent} .search-advanced`);

	const fullAdvancedSearchfield = jQuery('.search-wrap .search-form-term');
	const mobileAdvancedSearchfield = jQuery('.mobile-search-wrap .search-form-term');

	fullAdvancedSearchfield.prop('disabled', true);
	mobileAdvancedSearchfield.prop('disabled', true);

	jQuery('.datepicker').datepicker({
		dateFormat: 'mm/dd/yy'
	});

	jQuery(document.body).on('click', `${parent} .advanced-toggle`, e => {
		e.preventDefault();
		const button = jQuery(e.target);
		const searchField = document.querySelector(`${parent} .search-basic`);
		const searchFieldWidth = searchField.clientWidth;
		const advanced = jQuery(`${parent} .search-advanced`);
		const fullBasicSearchfield = jQuery('.search-wrap .search-form-input');
		const mobileBasicSearchfield = jQuery('.mobile-search-wrap .search-form-input');

		fullAdvancedSearchfield.prop('disabled', false);
		mobileAdvancedSearchfield.prop('disabled', false);

		if (fit) {
			advanced.css('width', `${searchFieldWidth}px`);
		}

		form.toggleClass('opened');

		if (form.hasClass('opened')) {
			button.text('Close');

			if (fullBasicSearchfield.val()) {
				fullAdvancedSearchfield.val(fullBasicSearchfield.val());
			} else {
				fullAdvancedSearchfield.val('');
			}

			if (mobileBasicSearchfield.val()) {
				mobileAdvancedSearchfield.val(mobileBasicSearchfield.val());
			} else {
				mobileAdvancedSearchfield.val('');
			}
		} else {
			button.text('Advanced');
			form.removeClass('opened');
		}
	});

	jQuery(document.body).on('click', '.search-toggle', e => {
		e.preventDefault();
		const button = jQuery(`${parent} .advanced-toggle`);

		if (form.hasClass('opened')) {
			form.removeClass('opened');
			button.text('Advanced');
		}
	});
};

jQuery(document).ready(() => {
	searchControl('.search-wrap', true);
	searchControl('.mobile-search-wrap');
});
