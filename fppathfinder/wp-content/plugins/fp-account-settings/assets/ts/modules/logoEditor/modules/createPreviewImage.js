// Import Modules
import { ifElementExists, isObjectEmpty } from '../helpers/index';

const createPreviewImage = (args, target, selector, imgSrc) => {
	// Bail if args is empty
	if (isObjectEmpty(args)) {
		return;
	}

	// Create the element
	const newElement = document.createElement('div');
	newElement.setAttribute('class', args.previewImageContainer);

	if (ifElementExists(target)) {
		target.appendChild(newElement);
	}

	const wrapper = document.createElement('div');
	newElement.appendChild(wrapper);

	// Create child element
	const childElement = document.createElement('img');

	if (selector) {
		childElement.setAttribute('class', selector);
		childElement.setAttribute('id', selector);
	}

	childElement.setAttribute('src', imgSrc);

	if (!imgSrc) {
		childElement.setAttribute('src', '');
	}

	if (ifElementExists(newElement)) {
		wrapper.appendChild(childElement);
	}
};

export default createPreviewImage;
