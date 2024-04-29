// Import Modules
import initModal from './modules/initModal';
import getFileData from './modules/getFileData';
import createPreviewImageWrap from './modules/createPreviewImageWrap';
import createModal from './modules/createModal';

const logoEditor = () => {
	const args = {
		// Input Form
		fileUploadWrap: 'file-upload-wrap',
		inputFiled: 'logo-upload-field',
		logoData: 'pdf-generator-logo-data-1',
		// Preview Image
		previewImageWrap: 'preview-image-wrap',
		previewImageContainer: 'preview-image-container',
		previewImage: 'preview-image',
		perviewLink: 'preview-link',
		// Modal
		modal: 'cropper-modal',
		modalDialog: 'modal-dialog',
		modalBody: 'modal-body',
		modalContent: 'modal-content',
		modalHeader: 'modal-header',
		modalControls: 'modal-controls',
		croppableImage: 'croppable-image',
		cropButton: 'crop-button',
		cancelButton: 'cancel-button',
		zoomInButton: 'zoom-in-button',
		zoomInSvg: 'zoom-in',
		zoomOutButton: 'zoom-out-button',
		zoomOutSvg: 'zoom-out',
		horizontalButton: 'horizontal-button',
		horizontalSvg: 'hor',
		verticalButton: 'vertical-button',
		verticalSvg: 'vert',
		text: {
			modalHeader: 'Crop Your Image',
			perviewLink: 'Adjust Logo',
			cancelButton: 'Cancel',
			cropButton: 'Crop & Save',
			zoomInButton: 'Zoom In',
			zoomOutButton: 'Zoom Out',
			horizontalButton: 'Horizontally Center Image',
			verticalButton: 'Vertically Center Image'
		}
	};
	createPreviewImageWrap(args);
	createModal(args);
	getFileData(args);
	initModal(args);
};

export default logoEditor;
