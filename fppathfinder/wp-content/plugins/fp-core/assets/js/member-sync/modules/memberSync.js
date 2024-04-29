const memberSync = () => {
	jQuery(document).ready(() => {
		jQuery(document.body).on('click', '#member-sync-button', e => {
			e.preventDefault();

			const nonce = jQuery('#member-sync-button').data('nonce');
			const userid = jQuery('#member-sync-button').data('userid');
			const indicator = jQuery('#member-sync-indicator');

			indicator.html('');

			jQuery.ajax({
				type: 'post',
				url: ajaxurl,
				data: {
					action: 'tools_ac_sync',
					nonce,
					userid
				},
				beforeSend() {
					indicator.html('<img src="/wp-content/plugins/fp-core/assets/images/26.gif" style="width: 25px; height: 25px"/> ');
				},
				success(response) {
					if (response) {
						indicator.html('Member Synced!');
					} else {
						indicator.html('An error occurred!');
					}
				}
			});
		});
	});
};

export default memberSync;
