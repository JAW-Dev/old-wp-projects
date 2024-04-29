jQuery(document).ready(function() {
	jQuery('.kitces-event-block.is-not-wide .content-wrapper').each(function() {
		var contentWrapper = jQuery(this);

		if (contentWrapper.outerHeight() > 150) {
			contentWrapper.addClass('too-tall');
		}
	});

	jQuery('.kitces-event-block.is-not-wide .content-wrapper .content-wrapper-open').on('click', function() {
		var contentWrapper = jQuery(this).parent('.content-wrapper');
		contentWrapper.addClass('is-open');
	});
});
