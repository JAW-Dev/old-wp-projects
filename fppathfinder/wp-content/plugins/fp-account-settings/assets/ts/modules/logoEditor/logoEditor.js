// Import Modules
import imageUpload from '../imageUpload/imageUpload';
import initModal from './modules/initModal';
import getFileData from './modules/getFileData';
import createPreviewImageWrap from './modules/createPreviewImageWrap';
import createModal from './modules/createModal';
import logoReset from './modules/logoReset';
import { isObjectEmpty } from './helpers/index';

const logoEditor = prefixes => {
	prefixes.forEach(prefix => {
		let getLogo = '';

		if (isObjectEmpty(fp_account_settingsData)) {
			return false;
		}

		if (prefix.includes('pdf-generator')) {
			if (fp_account_settingsData.whitelabel !== undefined) {
				const logo = fp_account_settingsData.whitelabel.logo ? fp_account_settingsData.whitelabel.logo : '';

				getLogo = logo || '';
			}
		}

		if (prefix.includes('group-settings') || fp_account_settingsData.useGroupLogo !== undefined) {
			if (fp_account_settingsData.group_whitelabel_settings !== undefined) {
				let logo = fp_account_settingsData.group_whitelabel_settings.logo ? fp_account_settingsData.group_whitelabel_settings.logo : '';

				if (logo.length === 0) {
					logo = fp_account_settingsData.whitelabel.logo ? fp_account_settingsData.whitelabel.logo : '';
				}

				getLogo = logo || '';
			}
		}

		const args = {
			prefix,
			// Input Form
			fileUploadWrap: `${prefix}-file-upload-wrap`,
			fileContainer: `${prefix}-file-container`,
			inputFiled: `${prefix}-upload-field`,
			logoData: `${prefix}-data`,
			logoButton: `${prefix}-btn`,
			errorMessage: `${prefix}-error-message`,
			removebutton: `${prefix}-file-upload-remove-btn`,
			mockButton: `${prefix}-file-upload-mock-btn`,
			saveButton: `${prefix}-settings-save`,
			logoImage: getLogo,
			// Preview Image
			previewImageWrap: `${prefix}-preview-image-wrap`,
			previewImageContainer: `${prefix}-preview-image-container`,
			previewImage: `${prefix}-preview-image`,
			perviewLink: `${prefix}-preview-link`,
			// Modal
			modal: `${prefix}-cropper-modal`,
			modalDialog: `${prefix}-modal-dialog`,
			modalBody: `${prefix}-modal-body`,
			modalContent: `${prefix}-modal-content`,
			modalHeader: `${prefix}-modal-header`,
			modalControls: `${prefix}-modal-controls`,
			croppableImage: `${prefix}-croppable-image`,
			cropButton: `${prefix}-crop-button`,
			cancelButton: `${prefix}-cancel-button`,
			zoomInButton: `${prefix}-zoom-in-button`,
			zoomInSvg: `${prefix}-zoom-in`,
			zoomOutButton: `${prefix}-zoom-out-button`,
			zoomOutSvg: `${prefix}-zoom-out`,
			horizontalButton: `${prefix}-horizontal-button`,
			horizontalSvg: `${prefix}-hor`,
			verticalButton: `${prefix}-vertical-button`,
			verticalSvg: `${prefix}-vert`,
			text: {
				modalHeader: 'Crop Your Image',
				perviewLink: 'Adjust',
				cancelButton: 'Cancel',
				cropButton: 'Crop & Save',
				zoomInButton: 'Zoom In',
				zoomOutButton: 'Zoom Out',
				horizontalButton: 'Horizontally Center Image',
				verticalButton: 'Vertically Center Image'
			}
		};
		imageUpload(args);
		createPreviewImageWrap(args);
		createModal(args);
		getFileData(args);
		initModal(args);
		logoReset(args);
	});
};

export default logoEditor;
