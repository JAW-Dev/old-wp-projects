import groupSyncAjaxHandler from './groupSyncAjaxHandler';

const groupSync = () => {
	jQuery(document).ready(() => {
		jQuery(document.body).on('click', '#member-group-sync-button', e => {
			e.preventDefault();
			const indicator = jQuery('#member-group-sync-indicator');
			const progressBar = jQuery('#progress-bar');
			const progressTotal = jQuery('#progress-total');

			indicator.css('display', 'none');
			progressBar.val(0);
			progressTotal.html('0%');

			groupSyncAjaxHandler();
		});
	});
};

export default groupSync;
