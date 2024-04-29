import getColors from './colors/getColors';

declare var fp_account_settingsAdminAjax;

const previewClickHandler = () => {
	const previewButton = document.getElementById('group-settings-pdf-settings-preview');
	const action = document.getElementById('group-settings-pdf-settings-action');
	const form = document.getElementById('group-settings-pdf-settings-form') as HTMLFormElement;

	if (previewButton !== null) {
		previewButton.addEventListener('click', e => {
			e.preventDefault();
			action.setAttribute('value', 'generate_pdf_preview');
			form.setAttribute('target', '_blank');
			form.setAttribute('action', fp_account_settingsAdminAjax);

			getColors();
			form.submit();
		});
	}
};

export default previewClickHandler;
