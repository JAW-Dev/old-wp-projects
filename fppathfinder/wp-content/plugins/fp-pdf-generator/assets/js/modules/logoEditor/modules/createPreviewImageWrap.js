// Import Modules
import createPreviewImage from './createPreviewImage';
import createPreviewButton from './createPreviewButton';
import { ifElementExists, isObjectEmpty } from '../helpers/index';

const createPreviewImageWrap = args => {
	// Bail if args is empty
	if (isObjectEmpty(args)) {
		return;
	}

	const target = document.querySelector(`.${args.fileUploadWrap}`);
	const parent = target !== null ? target.parentNode : null;
	const imgSrc = !isObjectEmpty(fppdfgData) && fppdfgData.logo ? fppdfgData.logo : '';

	if (parent !== null && imgSrc !== null) {
		// Create the element
		const newElement = document.createElement('div');

		if (args.previewImageWrap && newElement) {
			newElement.setAttribute('class', args.previewImageWrap);
			newElement.setAttribute('id', args.previewImageWrap);
		}

		if (ifElementExists(parent)) {
			parent.appendChild(newElement);
		}

		// If has saved logo, ass class to show the element
		if (imgSrc) {
			newElement.classList.add('show');
		}

		// Create child elements
		const element = document.getElementById(args.previewImageWrap);
		if (ifElementExists(element)) {
			createPreviewImage(args, element, args.previewImage, imgSrc);
			createPreviewButton(args, element);
		}
	}
};

export default createPreviewImageWrap;
