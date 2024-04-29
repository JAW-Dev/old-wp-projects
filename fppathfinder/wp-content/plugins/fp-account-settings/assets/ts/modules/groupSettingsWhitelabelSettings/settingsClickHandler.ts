import getColors from './colors/getColors';
import saveSuccess from '../notifications/saveSuccess';

declare var fp_account_settingsAdminAjax;

const settingsClickHandler = () => {
	const button: HTMLElement = document.getElementById('group-settings-pdf-settings-save');

	if (!button) {
		return false;
	}

	button.addEventListener('click', e => {
		e.preventDefault();

		const action = document.getElementById('group-settings-pdf-settings-action');
		action.setAttribute('value', 'group_settings_whitelabel_settings');

		const saveNotification = document.getElementById('group-settings-whitelabel-save-successful');
		saveNotification.style.display = 'none';

		getColors();

		const form: any = document.getElementById('group-settings-pdf-settings-form');
		const formData = jQuery(form).serialize();

		jQuery.ajax({
			type: 'post',
			url: fp_account_settingsAdminAjax,
			data: {
				action: 'group_settings_whitelabel_settings',
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
