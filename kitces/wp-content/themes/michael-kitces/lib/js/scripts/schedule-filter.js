// Announcements
jQuery(document).ready(function() {
	jQuery('.filter-reset').hide();
	jQuery('.events-no-results-for-filter').hide();

	let resetEventListSpeakers = function() {
		var yearOuter = jQuery('.events-years__year');
		yearOuter.find('.filter-reset').fadeOut();
		jQuery('.events-no-results-for-filter').fadeOut();
		yearOuter.find('.event-list__speakers-filter').val('all');
		yearOuter.find('.event-list__type-filter').val('all');
		yearOuter.find('input.event-list__future-filter').attr('checked', false);
		yearOuter.find('.events-month').slideDown();
		yearOuter.find('.event-list__event_wrapper').slideDown();
		yearOuter.find('.events-year__break-title').slideDown();
	};

	jQuery('.event-list__speakers-filter, .event-list__type-filter, .event-list__future-filter').on('change', function() {
		var yearOuter = jQuery(this).parents('.events-years__year');
		var year = yearOuter.attr('id');
		var speaker = yearOuter.find('.event-list__speakers-filter').val();
		var type = yearOuter.find('.event-list__type-filter').val();
		var future = yearOuter.find('input.event-list__future-filter').is(':checked');

		if (type === 'all') {
			typeClass = '';
		} else {
			typeClass = '.type-' + type;
		}

		if (speaker === 'all') {
			speakerClass = '';
		} else {
			speakerClass = '.speaker-' + speaker;
		}

		if (!future) {
			futureClass = '';
		} else {
			futureClass = '.future-event';
		}

		var classString = typeClass + speakerClass + futureClass;

		if (speaker === 'all' && type === 'all' && !future) {
			resetEventListSpeakers();
		} else {
			yearOuter.find('.filter-reset').fadeIn();
			yearOuter.find('.event-list__event_wrapper:not(' + classString + ')').slideUp();
			yearOuter.find('.event-list__event_wrapper:not(' + classString + ')').removeClass('is-visible');
			yearOuter.find('.event-list__event_wrapper' + classString).slideDown();
			yearOuter.find('.event-list__event_wrapper' + classString).addClass('is-visible');
		}

		var allMonths = yearOuter.find('.events-month');

		if (allMonths.length) {
			allMonths.each(function(index, value) {
				var thisMonth = jQuery(this);
				var monthEvents = thisMonth.find('.event-list__event_wrapper.is-visible');

				if (monthEvents.length < 1) {
					thisMonth.slideUp();
				} else {
					thisMonth.slideDown();
				}
			});
		}

		var allEventsVisible = jQuery('.event-list__event_wrapper.is-visible');
		if (allEventsVisible.length < 1) {
			yearOuter.find('.events-month').slideUp();
			yearOuter.find('.events-year__break-title').slideUp();
			jQuery('.events-no-results-for-filter').slideDown();
		}
	});

	jQuery('.filter-reset').on('click', function() {
		resetEventListSpeakers();
	});
});
