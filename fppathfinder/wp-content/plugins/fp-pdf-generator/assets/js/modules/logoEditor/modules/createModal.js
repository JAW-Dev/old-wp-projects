// Import Modules
import createPreviewImage from './createPreviewImage';
import { ifElementExists, isObjectEmpty } from '../helpers/index';

const createModal = (args, imageSrc = '') => {
	// Bail if args is empty
	if (isObjectEmpty(args)) {
		return;
	}

	const target = args.fileUploadWrap ? document.querySelector(`.${args.fileUploadWrap}`) : null;
	const parent = target !== null ? target.parentNode : null;

	if (parent === null) {
		return;
	}

	// Create the element
	const modal = document.createElement('div');

	if (args.modal) {
		modal.setAttribute('class', args.modal);
		modal.setAttribute('id', args.modal);
	}

	modal.setAttribute('style', 'display:none;');
	modal.setAttribute('tabindex', '-1');
	modal.setAttribute('role', 'dialog');
	modal.setAttribute('aria-labelledby', 'logoModalTitle');
	modal.setAttribute('aria-hidden', 'true');

	if (ifElementExists(parent)) {
		parent.appendChild(modal);
	}

	const modalDialog = args.modalDialog ? args.modalDialog : '';
	const modalContent = args.modalContent ? args.modalContent : '';
	const modalHeader = args.modalHeader ? args.modalHeader : '';
	const modalHeaderText = args.text.modalHeader ? args.text.modalHeader : '';
	const modalBody = args.modalBody ? args.modalBody : '';
	const modalControls = args.modalControls ? args.modalControls : '';
	const cancelButton = args.cancelButton ? args.cancelButton : '';
	const cancelButtonText = args.text.cancelButton ? args.text.cancelButton : '';
	const zoomInButton = args.zoomInButton ? args.zoomInButton : '';
	const zoomInButtonText = args.text.zoomInButton ? args.text.zoomInButton : '';
	const zoomInSvg = args.zoomInSvg ? args.zoomInSvg : '';
	const zoomOutButton = args.zoomOutButton ? args.zoomOutButton : '';
	const zoomOutButtonText = args.text.zoomOutButton ? args.text.zoomOutButton : '';
	const zoomOutSvg = args.zoomOutSvg ? args.zoomOutSvg : '';
	const horizontalButton = args.horizontalButton ? args.horizontalButton : '';
	const horizontalButtonText = args.text.horizontalButton ? args.text.horizontalButton : '';
	const horizontalSvg = args.horizontalSvg ? args.horizontalSvg : '';
	const verticalButton = args.verticalButton ? args.verticalButton : '';
	const verticalButtonText = args.text.verticalButton ? args.text.verticalButton : '';
	const verticalSvg = args.verticalSvg ? args.verticalSvg : '';
	const cropButton = args.cropButton ? args.cropButton : '';
	const cropButtonText = args.text.cropButton ? args.text.cropButton : '';

	modal.innerHTML = `<div id="${modalDialog}" class="${modalDialog}" tabindex="-1" role="dialog">
		<div id="${modalContent}" class="${modalContent}" role="document">
			<div id="${modalHeader}" class="${modalHeader}">
				<h3 class="form-1-title">${modalHeaderText}</h3>
			</div>
			<div id="${modalBody}" class="${modalBody}">
			</div>
			<div id="${modalControls}" class="${modalControls}">
				<button id="${cancelButton}" class="control-button form-button ${cancelButton}" type="button" id="cancel-button" data-dismiss="modal" title="${cancelButtonText}" aria-label="${cancelButtonText}">
					${cancelButtonText}
				</button>
				<button id="${zoomOutButton}"class="control-button ${zoomOutButton}" data-method="zoom" data-option="0.1" title="${zoomOutButtonText}" aria-label="${zoomOutButtonText}">
					<svg class="svg ${zoomOutSvg}" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.23 15.52l6.486 6.5a1.204 1.204 0 0 1-1.709 1.684l-6.474-6.474a9.627 9.627 0 1 1 1.697-1.697v-.012zm-7.606 1.325a7.22 7.22 0 1 0 0-14.44 7.22 7.22 0 0 0 0 14.44zm1.204-8.424h2.407a1.203 1.203 0 0 1 0 2.407h-2.407v2.407a1.204 1.204 0 0 1-2.407 0v-2.407H6.014a1.204 1.204 0 0 1 0-2.407h2.407V6.014a1.203 1.203 0 1 1 2.407 0v2.407z" fill="#2A4053"/></svg>
				</button>
				<button id="${zoomInButton}" class="control-button ${zoomInButton}" data-method="zoom" data-option="-0.1" title="${zoomInButtonText}" aria-label="${zoomInButtonText}">
					<svg class="svg ${zoomInSvg}" width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M17.23 15.52l6.486 6.5a1.204 1.204 0 0 1-1.709 1.684l-6.474-6.474a9.627 9.627 0 1 1 1.697-1.697v-.012zm-7.606 1.325a7.22 7.22 0 1 0 0-14.44 7.22 7.22 0 0 0 0 14.44zm4.814-7.22a1.203 1.203 0 0 1-1.204 1.203h-7.22a1.204 1.204 0 0 1 0-2.407h7.22a1.203 1.203 0 0 1 1.204 1.203z" fill="#2A4053"/></svg>
				</button>
				<button id="${horizontalButton}" class="control-button ${horizontalButton}" data-method="moveTo" data-option="0" title="${horizontalButtonText}" aria-label="${horizontalButtonText}">
					<svg class="svg ${horizontalSvg}" width="24" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.333 10.667l-4-4v2.666H0V12h5.333v2.667l4-4zm9.334 4V12H24V9.333h-5.333V6.667l-4 4 4 4zM12 21.333c.737 0 1.333-.065 1.333-.8V.8c0-.737-.596-.8-1.333-.8-.736 0-1.333.063-1.333.8v19.733c0 .735.597.8 1.333.8z" fill="#2A4053"/></svg>
				</button>
				<button id="${verticalButton}" class="control-button ${verticalButton}" data-method="moveTo" data-option="0" title="${verticalButtonText}" aria-label="${verticalButtonText}">
					<svg class="svg ${verticalSvg}" width="24" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.333 10.667l-4-4v2.666H0V12h5.333v2.667l4-4zm9.334 4V12H24V9.333h-5.333V6.667l-4 4 4 4zM12 21.333c.737 0 1.333-.065 1.333-.8V.8c0-.737-.596-.8-1.333-.8-.736 0-1.333.063-1.333.8v19.733c0 .735.597.8 1.333.8z" fill="#2A4053"/></svg>
				</button>
				<button id="${cropButton}" class="control-button form-button ${cropButton}" type="button" id="crop-button" title="${cropButtonText}" aria-label="${cropButtonText}">
					${cropButtonText}
				</button>
			</div>
		</div>
	</div>`;

	// Set the modal croppable image on init.
	const element = args.modalBody ? document.getElementById(args.modalBody) : '';
	const previewImage = args.previewImage ? document.getElementById(args.previewImage) : '';
	imageSrc = ifElementExists(previewImage) && previewImage.src ? previewImage.src : imageSrc || (!isObjectEmpty(fppdfgData) && fppdfgData.logo ? fppdfgData.logo : '');

	if (ifElementExists(element)) {
		createPreviewImage(args, element, args.croppableImage, imageSrc);
	}
};

export default createModal;
