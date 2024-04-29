const fileValidate = (args, fileUpload) => {
	const uploadField = document.getElementById(args.inputFiled);
	const saveButton = document.getElementById('pdf-generator-settings-save');
	const errorMessage = document.getElementById(args.errorMessage);
	errorMessage.style.display = 'none';

	if (fileUpload.files[0].size > 245760) {
		uploadField.setAttribute('value', '');
		saveButton.setAttribute('disabled', '');
		errorMessage.style.display = 'block';
		errorMessage.innerHTML = 'The logo image size must be 240kb or smaller!';
		errorMessage.style.color = 'red';
		errorMessage.style.fontWeight = 'bold';
		setTimeout(() => {
			errorMessage.style.display = 'none';
		}, 3000);
	} else if (fileUpload.files[0].size <= 245760 && fp_account_settingsData.canSaveWhitelable !== '0') {
		if (jQuery('body').hasClass('logged-in')) {
			if (saveButton) {
				saveButton.removeAttribute('disabled', '');
			}
		}
	} else {
		errorMessage.style.display = 'none';
	}
};

export default fileValidate;
