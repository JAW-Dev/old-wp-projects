// Announcements
jQuery(document).ready(function() {
	jQuery('.filter-reset').hide();

	jQuery(
		'.conf-focus-select, .conf-industry-channel-select, .conf-organization-select, .conf-states-select, .conf_kitces_speakers, .filter-select-wraps #top-conference, .conf-type-select'
	).on('change', function() {
		var focus = jQuery('.conf-focus-select').val();
		var channel = jQuery('.conf-industry-channel-select').val();
		var organization = jQuery('.conf-organization-select').val();
		var state = jQuery('.conf-states-select').val();
		var speaker = jQuery('.conf_kitces_speakers').val();
		var type = jQuery('.conf-type-select').val();
		var topConf = jQuery('.filter-select-wraps #top-conference').is(':checked');

		var focusClass = '';
		if (focus === 'all') {
			focusClass = '';
		} else {
			focusClass = '.' + focus;
		}

		var channelClass = '';
		if (channel === 'all') {
			channelClass = '';
		} else {
			channelClass = '.' + channel;
		}

		var organizationClass = '';
		if (organization === 'all') {
			organizationClass = '';
		} else {
			organizationClass = '.' + organization;
		}

		var stateClass = '';
		if (state === 'all') {
			stateClass = '';
		} else {
			stateClass = '.state-' + state;
		}

		var speakerClass = '';
		if (speaker === 'all') {
			speakerClass = '';
		} else {
			speakerClass = '.' + speaker;
		}

		var typeClass = '';
		if (type === 'all') {
			typeClass = '';
		} else {
			typeClass = '.' + type;
		}

		var topConfClass = '';
		if (topConf) {
			topConfClass = '.top-conf';
		}

		var classString = focusClass + channelClass + organizationClass + stateClass + speakerClass + topConfClass + typeClass;

		// console.log(classString);

		if (classString === '') {
			jQuery('.conference-block').slideDown();
			jQuery('.conference-block').addClass('active');
			jQuery('.no-conferences-error').slideDown();
			jQuery('.month-error').slideUp();
			jQuery('.filter-reset').fadeOut();
		} else {
			jQuery('.filter-reset').fadeIn();
			jQuery('.conference-block:not(' + classString + ')').slideUp();
			jQuery('.conference-block:not(' + classString + ')').removeClass('active');
			jQuery('.no-conferences-error').slideUp();
			jQuery('.conference-block' + classString).slideDown();
			jQuery('.conference-block' + classString).addClass('active');

			jQuery('.conference-list-month').each(function() {
				var confInMonth = jQuery(this).find('.conference-block.active');

				if (confInMonth.length == 0) {
					jQuery(this)
						.find('.month-error')
						.slideDown();
				} else {
					jQuery(this)
						.find('.month-error')
						.slideUp();
				}
			});
		}
	});

	jQuery('.filter-reset').on('click', function() {
		jQuery('.conf-focus-select').val('all');
		jQuery('.conf-industry-channel-select').val('all');
		jQuery('.conf-organization-select').val('all');
		jQuery('.conf-states-select').val('all');
		jQuery('.conf_kitces_speakers').val('all');
		jQuery('.conf-type-select').val('all');
		jQuery('.filter-select-wraps #top-conference').prop('checked', false);
		jQuery('.conference-block').slideDown();
		jQuery('.no-conferences-error').slideDown();
		jQuery('.conference-block').addClass('active');
		jQuery('.month-error').slideUp();
		jQuery('.filter-reset').fadeOut();
	});
});
