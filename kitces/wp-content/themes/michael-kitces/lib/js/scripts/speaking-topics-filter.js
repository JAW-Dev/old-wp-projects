// Announcements
jQuery(document).ready(function() {
	let resetPresentationFilters = function() {
		var tabsOuter = jQuery('.speaking-topics-tabs__outer');

		tabsOuter.find('.speaking-topics__speakers-filter').val('all');
		tabsOuter.find('.speaking-topics__tag-filter').val('all');

		tabsOuter.find('.filter-reset').fadeOut();
		tabsOuter.find('.speaking-topics__no-results').fadeOut();
		tabsOuter.find('.speaking-tab__nav-item').slideDown();
		tabsOuter.find('.tab-presentation').slideDown();
		tabsOuter.find('.tab-presentation').addClass('is-visible');
		tabsOuter.find('.speaking-topics-tabs').slideDown();
		tabsOuter.find('.resp-tab-content.resp-tab-content-active').slideDown();
	};

	jQuery('.speaking-topics-tabs__outer .filter-reset').hide();
	jQuery('.speaking-topics-tabs__outer .speaking-topics__no-results').hide();

	jQuery('.speaking-topics__speakers-filter, .speaking-topics__tag-filter').on('change', function() {
		var tabsOuter = jQuery('.speaking-topics-tabs__outer');
		var speaker = jQuery('.speaking-topics__speakers-filter').val();
		var tag = jQuery('.speaking-topics__tag-filter').val();

		if (tag === 'all') {
			tagClass = '';
		} else {
			tagClass = '.' + tag;
		}

		if (speaker === 'all') {
			speakerClass = '';
		} else {
			speakerClass = '.' + speaker;
		}

		var classString = tagClass + speakerClass;

		if (classString === '') {
			resetPresentationFilters();
		} else {
			tabsOuter.find('.filter-reset').fadeIn();
			tabsOuter.find('.tab-presentation:not(' + classString + ')').slideUp();
			tabsOuter.find('.tab-presentation:not(' + classString + ')').removeClass('is-visible');
			tabsOuter.find('.tab-presentation' + classString).slideDown();
			tabsOuter.find('.tab-presentation' + classString).addClass('is-visible');

			var firstVisibleTab = false;
			var displayNoneError = true;

			// Find empty tabs and close them
			jQuery('.speaking-tab__nav-item').each(function(index) {
				var navItem = jQuery(this);
				var navItemAria = navItem.attr('aria-controls');
				var tabContent = jQuery('.resp-tab-content[aria-labelledby=' + navItemAria + ']');
				var tabAccordion = jQuery('.resp-accordion[aria-labelledby=' + navItemAria + ']');

				var visibleBlocks = tabContent.find('.tab-presentation.is-visible');

				if (visibleBlocks.length < 1) {
					navItem.slideUp();
					navItem.removeClass('resp-tab-active');
					tabContent.fadeOut();
				} else {
					navItem.slideDown();
					displayNoneError = false;

					if (!firstVisibleTab) {
						firstVisibleTab = true;
						navItem.addClass('resp-tab-active');
						navItem.fadeIn();
						tabContent.fadeIn();
					} else {
						tabContent.fadeOut();
						navItem.removeClass('resp-tab-active');
					}
				}
			});

			if (displayNoneError) {
				jQuery('.speaking-topics-tabs__outer .speaking-topics__no-results').fadeIn();
				jQuery('.speaking-topics-tabs').slideUp();
			} else {
				jQuery('.speaking-topics-tabs__outer .speaking-topics__no-results').hide();
				jQuery('.speaking-topics-tabs').slideDown();
			}
		}

		jQuery('#book-tabs, #tabs').easyResponsiveTabs({
			type: 'vertical'
		});
	});

	jQuery('.filter-reset').on('click', function() {
		resetPresentationFilters();
	});
});
