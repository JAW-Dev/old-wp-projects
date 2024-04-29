import getColors from './colors/getColors';

declare var fp_account_settingsAdminAjax;

const previewClickHandler = () => {
	const previewButton = document.getElementById('pdf-generator-settings-preview');
	const action = document.getElementById('pdf-generator-settings-action');
	const form = document.getElementById('pdf-generator-settings-form') as HTMLFormElement;

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
