import getColors from './colors/getColors';
import saveSuccess from '../notifications/saveSuccess';

declare var fp_account_settingsAdminAjax;

const settingsClickHandler = (): void => {
	const button: HTMLElement = document.getElementById('pdf-generator-settings-save');

	button.addEventListener('click', e => {
		e.preventDefault();

		const action = document.getElementById('pdf-generator-settings-action');
		action.setAttribute('value', 'whitelabel_settings');

		const saveNotification = document.getElementById('pdf-generator-save-successful');
		saveNotification.style.display = 'none';

		getColors();

		const form: any = document.getElementById('pdf-generator-settings-form');
		const formData = jQuery(form).serialize();

		jQuery.ajax({
			type: 'post',
			url: fp_account_settingsAdminAjax,
			data: {
				action: 'whitelabel_settings',
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
