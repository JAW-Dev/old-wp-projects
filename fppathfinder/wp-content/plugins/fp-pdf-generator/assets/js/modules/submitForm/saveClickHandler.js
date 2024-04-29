// Import Module
import formValidate from './formValidate/formValidate';
import fieldSetup from './fieldSetup';
import scrollTo from './scrollTo';

const saveClickHandler = () => {
	const saveButton = document.getElementById('pdf-save-flow-chart-btn');
	const action = document.getElementById('pdf-generator-action-1');

	if (saveButton !== null) {
		saveButton.addEventListener('click', async e => {
			e.preventDefault();
			action.setAttribute('value', 'pdf_save_settings');

			await fieldSetup();

			if (formValidate()) {
				const saveNotificationArea = document.getElementById('save-notification-successful');

				saveNotificationArea.style.display = 'none';

				jQuery.ajax({
					type: 'post',
					url: fppdfgAdminAjax,
					data: {
						action: 'pdf_save_settings',
						form: jQuery('#pdf-generator-preview-form-1').serialize()
					},
					success(response) {
						saveNotificationArea.style.display = 'none';
						const title = document.querySelector('.form-1-title');
						const rect = title.offsetTop + title.offsetHeight;

						saveNotificationArea.style.display = 'block';

						scrollTo(document.documentElement, rect, 500);
					}
				});
			}
		});
	}
};

export default saveClickHandler;
