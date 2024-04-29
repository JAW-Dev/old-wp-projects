// Import Modules
import getSelectedLogo from './getSelectedLogo';
import fileValidate from './fileValidate';

const imageUpload = () => {
	const dummyBtn = document.getElementById('file-upload-mock-btn');
	const fileField = document.getElementById('logo-upload-field');
	const uploadStatus = document.getElementById('file-upload-status');
	const logoData = document.getElementById('pdf-generator-logo-data-1');

	if (dummyBtn !== null) {
		dummyBtn.addEventListener('click', () => {
			fileField.click();
		});
	}

	if (fileField !== null) {
		fileField.addEventListener('change', e => {
			const file = fileField.files[0];

			if (file) {
				uploadStatus.innerHTML = file.name;
				fileValidate(e.target);
				getSelectedLogo(file, e => {
					logoData.setAttribute('value', e.target.result);
				});
			}
		});
	}
};

export default imageUpload;
