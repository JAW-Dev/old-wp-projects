import saveSuccess from '../notifications/saveSuccess';

declare var fp_account_settingsAdminAjax;

const settingsClickHandler = () => {
	const button: HTMLElement = document.getElementById('group-settings-share-link-settings-save');

	if (!button) {
		return false;
	}

	button.addEventListener('click', e => {
		e.preventDefault();

		const action = document.getElementById('group-settings-share-link-settings-action');
		action.setAttribute('value', 'whitelabel_settings');

		const saveNotification = document.getElementById('group-settings-whitelabel-save-successful');
		saveNotification.style.display = 'none';

		const form: any = document.querySelector('#group-settings-share-link-settings-form');
		const formData = jQuery(form).serialize();

		jQuery.ajax({
			type: 'post',
			url: fp_account_settingsAdminAjax,
			data: {
				action: 'group_settings_share_link_settings',
				data: formData
			},
			beforeSend: () => {
				button.innerHTML = 'Saving';
			},
			success: () => {
				saveSuccess(saveNotification);
				button.innerHTML = 'Save Settings';
			}
		});
	});
};

export default settingsClickHandler;
