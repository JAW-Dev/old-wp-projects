// Import Modules
import { ifElementExists, isObjectEmpty } from '../helpers/index';

const createPreviewButton = (args, target) => {
	// Bail if args is empty
	if (isObjectEmpty(args)) {
		return;
	}

	// Creste the element
	const newElement = document.createElement('button');

	if (args.perviewLink) {
		newElement.setAttribute('class', args.perviewLink);
		newElement.setAttribute('id', args.perviewLink);
	}

	if (args.text.perviewLink) {
		newElement.setAttribute('title', args.text.perviewLink);
		newElement.setAttribute('aria-label', args.text.perviewLink);
	}

	if (ifElementExists(target)) {
		target.appendChild(newElement);
	}

	if (ifElementExists(newElement) && args.text.perviewLink) {
		newElement.innerHTML = args.text.perviewLink;
	}
};

export default createPreviewButton;
