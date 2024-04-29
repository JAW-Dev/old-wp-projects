import destroyWorkingOverlay from '../../utilities/workingOverlay/destroyWorkingOverlay';

declare let fp_account_settingsAdminAjax: any;
declare let jQuery: any;

const ClearCurrentAjaxHandler = () => {
	jQuery.ajax({
		type: 'post',
		url: fp_account_settingsAdminAjax,
		data: {
			action: 'current_user_transients'
		},
		success: response => {
			if (response) {
				console.log(response);
				destroyWorkingOverlay();
			}
		},
		fail: err => {
			console.error(`There was an error: ${err}`);
		}
	});
};

export default ClearCurrentAjaxHandler;
