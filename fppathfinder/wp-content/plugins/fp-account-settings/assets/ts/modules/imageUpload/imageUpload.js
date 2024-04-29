// Import Modules
import getSelectedLogo from './getSelectedLogo';
import fileValidate from './fileValidate';
import initModal from '../logoEditor/modules/initModal';
import createPreviewButton from '../logoEditor/modules/createPreviewButton';
import { ifElementExists } from '../logoEditor/helpers/index';

const imageUpload = args => {
	const dummyBtn = document.getElementById(args.mockButton);
	const fileField = document.getElementById(args.inputFiled);
	const logoData = document.getElementById(args.logoData);

	if (dummyBtn !== null) {
		dummyBtn.addEventListener('click', () => {
			fileField.click();
		});
	}

	if (fileField !== null) {
		fileField.addEventListener('change', e => {
			const file = fileField.files[0];

			if (file) {
				// uploadStatus.innerHTML = file.name;
				fileValidate(args, e.target);
				getSelectedLogo(file, el => {
					logoData.setAttribute('value', el.target.result);
				});

				args.logoImage = logoData.value;

				const element = document.getElementById(args.previewImageWrap);

				if (ifElementExists(element)) {
					const uploadButton = document.getElementById(args.mockButton);
					const adjustButton = document.getElementById('pdf-generator-logo-preview-link');

					uploadButton.innerHTML = 'Replace';

					if (!adjustButton) {
						createPreviewButton(args, element);
					}

					initModal(args);
				}
			}
		});
	}
};

export default imageUpload;
