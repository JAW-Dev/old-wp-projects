const fileValidate = fileUpload => {
	const uploadField = document.getElementById('logo-upload-field');
	const saveButton = document.getElementById('pdf-save-flow-chart-btn');
	const message = document.getElementById('file-upload-status');

	if (fileUpload.files[0].size > 245760) {
		uploadField.setAttribute('value', '');
		saveButton.setAttribute('disabled', '');
		message.innerHTML = 'The logo image size must be 240kb or smaller!';
		message.style.color = 'red';
		message.style.fontWeight = 'bold';
	} else if (fileUpload.files[0].size <= 245760 && fppdfgData.canSaveWhitelable !== '0') {
		if (jQuery('body').hasClass('logged-in')) {
			saveButton.removeAttribute('disabled', '');
		}
		message.style.color = 'inherit';
		message.style.fontWeight = 'inherit';
	}
};

export default fileValidate;
