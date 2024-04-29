function debounce(func, wait, immediate) {
	var timeout;
	return function() {
		var context = this,
			args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) func.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) func.apply(context, args);
	};
}

jQuery.expr[':'].icontains = function(a, i, m) {
	return (
		jQuery(a)
			.text()
			.toUpperCase()
			.indexOf(m[3].toUpperCase()) >= 0
	);
};

// Announcements
jQuery(document).ready(function() {
	const listItems = jQuery('.mk-list-block');
	const listFilterReset = jQuery('.list-filter-reset');
	const allCheckboxes = jQuery('.list-filters-wrap .list-filter-checkbox input[type="checkbox"]');
	const textSearchInput = jQuery('.list-filters-wrap .list-filter-text-search #list-filter-text-search');
	const allSelects = jQuery('.list-filters-wrap select.list-filter-select');
	const noneFound = jQuery('.mk-list-section .list-error-none');
	listFilterReset.hide();

	var filterList = debounce(function() {
		var classString = '';
		var searchActive = false;

		if (textSearchInput.length) {
			var searchText = textSearchInput.val();

			if (searchText.length) {
				var searchTextLower = searchText.toLowerCase();

				searchActive = true;
			}
		}

		if (allCheckboxes.length) {
			allCheckboxes.each(function(index, checkbox) {
				var theCheckbox = jQuery(checkbox);
				if (theCheckbox.is(':checked')) {
					var filterKey = theCheckbox.data('filter-key');
					classString = classString + '.' + filterKey;
				}
			});
		}

		if (allSelects.length) {
			allSelects.each(function(index, select) {
				var theSelectVal = jQuery(select).val();
				var theSelectFilterKey = jQuery(select).data('filter-key');

				if (theSelectVal !== 'all') {
					classString = classString + '.' + theSelectFilterKey + '_' + theSelectVal;
				}
			});
		}

		if (classString === '') {
			if (!searchActive) {
				listItems.slideDown();
				listItems.addClass('active');
				listFilterReset.fadeOut();
				noneFound.fadeOut();
			}
		} else {
			listFilterReset.fadeIn();
			jQuery('.mk-list-block:not(' + classString + ')').slideUp();
			jQuery('.mk-list-block:not(' + classString + ')').removeClass('active');
			jQuery('.mk-list-block' + classString).slideDown();
			jQuery('.mk-list-block' + classString).addClass('active');
		}

		if (searchActive) {
			var matched = jQuery('.mk-list-block:icontains(' + searchTextLower + ')');
			var notMatched = jQuery('.mk-list-block:not(:icontains(' + searchTextLower + '))');

			matched.slideDown().addClass('active');
			notMatched.slideUp().removeClass('active');
			listFilterReset.fadeIn();
		}

		if (jQuery('.mk-list-block.active').length < 1) {
			noneFound.slideDown();
		} else {
			noneFound.fadeOut();
		}
	}, 150);

	jQuery('.list-filters-wrap .list-filter-text-search #list-filter-text-search').on('keyup', function() {
		filterList();
	});

	// Filter the list when checkbox toggled or select changed
	jQuery('.list-filters-wrap .list-filter-checkbox input[type="checkbox"], .list-filters-wrap select.list-filter-select').on('change', function() {
		filterList();
	});

	listFilterReset.on('click', function() {
		allSelects.val('all');
		textSearchInput.val('');
		allCheckboxes.prop('checked', false);
		listItems.slideDown();
		listItems.addClass('active');
		listFilterReset.fadeOut();
		noneFound.fadeOut();
	});
});
