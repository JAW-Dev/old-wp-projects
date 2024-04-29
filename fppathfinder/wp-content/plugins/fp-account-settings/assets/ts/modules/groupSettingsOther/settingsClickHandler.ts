import saveSuccess from '../notifications/saveSuccess';

declare var fp_account_settingsAdminAjax;

const settingsClickHandler = () => {
	const button: HTMLElement = document.getElementById('group-settings-other-save');

	if (!button) {
		return false;
	}

	button.addEventListener('click', e => {
		e.preventDefault();

		const action = document.getElementById('group-settings-other-action');
		action.setAttribute('value', 'group_settings_other');

		const saveNotification = document.getElementById('group-settings-whitelabel-save-successful');
		saveNotification.style.display = 'none';

		const form: any = document.getElementById('group-settings-other-form');
		const formData = jQuery(form).serialize();

		jQuery.ajax({
			type: 'post',
			url: fp_account_settingsAdminAjax,
			data: {
				action: 'group_settings_other',
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
