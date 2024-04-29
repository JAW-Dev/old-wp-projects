// Import Modules
import rebuildModal from './rebuildModal';
import { ifElementExists, isObjectEmpty } from '../helpers/index';

const getFileData = args => {
	// Bail if args is empty
	if (isObjectEmpty(args)) {
		return;
	}

	const input = document.getElementById(args.inputFiled);
	const image = document.getElementById(args.previewImage);

	if (input === null || image === null) {
		return;
	}

	if (ifElementExists(input)) {
		input.addEventListener('change', e => {
			const { files } = e.target;
			if (files && files[0] && files[0].size < 245760) {
				const reader = new FileReader();
				reader.onload = e => {
					const imgSrc = e.target.result ? e.target.result : '';

					// Set the preview image.
					const imageWrapper = document.getElementById(args.previewImageWrap);

					if (ifElementExists(imageWrapper)) {
						imageWrapper.classList.add('show');
					}

					if (imgSrc) {
						image.setAttribute('src', imgSrc);
					}

					// Build a new modal
					rebuildModal(args, imgSrc);
				};
				reader.readAsDataURL(files[0]);
			}
		});
	}
};

export default getFileData;
