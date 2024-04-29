// Import Packages
import Cropper from 'cropperjs';
import buttonsInit from './buttonActions/buttonsInit';

// Import Modules
import { ifElementExists, isObjectEmpty } from '../helpers/index';

const initCropper = args => {
	// Bail if args is empty
	if (isObjectEmpty(args)) {
		return;
	}
	const image = document.getElementById(args.croppableImage);

	if (ifElementExists(image)) {
		const cropper = new Cropper(image, {
			viewMode: 0,
			aspectRatio: 3 / 1,
			dragMode: 'move',
			restore: false,
			guides: false,
			center: true,
			highlight: false,
			cropBoxMovable: false,
			cropBoxResizable: false,
			toggleDragModeOnDblclick: false,
			background: true,
			wheelZoomRatio: 0.01,
			autoCropArea: 1,
			modal: false
		});

		buttonsInit(args, cropper);
	}
};

export default initCropper;
