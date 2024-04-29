const groupSyncAjaxHandler = (offset = 0, total = 0) => {
	jQuery(document).ready(() => {
		const nonce = jQuery('#member-group-sync-button').data('nonce');
		const groupid = jQuery('#member-group-sync-button').data('groupid');
		const indicator = jQuery('#progress-indicator');

		jQuery.ajax({
			type: 'post',
			url: ajaxurl,
			data: {
				action: 'sync_ac_group_member',
				nonce,
				groupid,
				offset,
				total
			},
			beforeSend() {
				indicator.css('display', 'inline');
			},
			success(response) {
				if (response) {
					const json = JSON.parse(response);
					const newOffset = json.offset;
					const newTotal = json.total;
					const progressBar = jQuery('#progress-bar');
					const progressTotal = jQuery('#progress-total');

					if (newOffset < newTotal) {
						groupSyncAjaxHandler(newOffset, newTotal);
						const percent = (100 * newOffset) / newTotal;
						progressBar.val(percent.toFixed(2));
						progressTotal.html(`${percent.toFixed(2)}%`);
					} else {
						progressBar.val(100);
						progressTotal.html('100%');
					}
				} else {
					progressTotal.html('An error occurred!');
				}
			}
		});
	});
};

export default groupSyncAjaxHandler;
