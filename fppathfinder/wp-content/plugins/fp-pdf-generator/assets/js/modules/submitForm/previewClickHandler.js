// Import Module
import formValidate from './formValidate/formValidate';
import fieldSetup from './fieldSetup';

const previewClickHandler = () => {
	const previewButton = document.getElementById('pdf-preview-flow-chart-btn');
	const action = document.getElementById('pdf-generator-action-1');
	const form = document.getElementById('pdf-generator-preview-form-1');

	if (previewButton !== null) {
		previewButton.addEventListener('click', async e => {
			e.preventDefault();
			action.setAttribute('value', 'generate_pdf_preview');
			form.setAttribute('target', '_blank');
			form.setAttribute('action', fppdfgAdminAjax);

			await fieldSetup();

			if (formValidate()) {
				form.submit();
			}
		});
	}
};

export default previewClickHandler;
