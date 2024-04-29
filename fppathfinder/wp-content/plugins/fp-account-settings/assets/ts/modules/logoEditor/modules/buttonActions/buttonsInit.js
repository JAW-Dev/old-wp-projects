// Import Modules
import controls from './controls';
import crop from './crop';
import cancel from './cancel';
import { ifElementExists, isObjectEmpty } from '../../helpers/index';

const buttonsInit = (args, cropper) => {
	// Bail if args is empty
	if (isObjectEmpty(args) || isObjectEmpty(cropper)) {
		return;
	}

	const content = document.getElementById(args.modalContent);

	if (ifElementExists(content)) {
		setTimeout(() => {
			controls(args, content, cropper);
		}, 100);
	}

	crop(args, cropper);
	cancel(args);
};

export default buttonsInit;
