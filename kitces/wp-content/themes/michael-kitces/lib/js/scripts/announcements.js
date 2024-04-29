jQuery(document).ready(function() {
	let ajaxNotification = jQuery('.inner-ajax-notification');

	if (ajaxNotification !== undefined && ajaxNotification.length) {
		ajaxNotification.each(function() {
			let notificationArea = jQuery(this);
			let notificationLocation = notificationArea.data('notification-location');
			jQuery.ajax({
				type: 'POST',
				url: kitces_data.ajax_url,
				data: {
					action: 'announcements_callback',
					dataType: 'json',
					notificationLocation: notificationLocation
				},
				success: function(response) {
					let responseData = JSON.parse(response);
					if (responseData.success) {
						notificationArea.append(responseData.html);
						ajaxNotification.addClass('notification-number-' + responseData.count);

						let announcements = notificationArea.find('.announcement');
						announcements.fadeIn();
						announcements.removeClass('hidden');

						let memberAnnouncementsWrap = jQuery('.member-announcements-wrap');

						if (memberAnnouncementsWrap !== undefined && announcements.length) {
							memberAnnouncementsWrap.addClass('finished-loading');
						}
					}
				}
			});
		});
	}
});
