// Import Modules
import getOffsetPosition from './getOffsetPosition';
import { ifElementExists, isObjectEmpty } from '../../helpers/index';

const controls = (args, content, cropper) => {
	// Bail if args is empty
	if (isObjectEmpty(args) || isObjectEmpty(cropper) || !ifElementExists(content)) {
		return;
	}

	const modalControls = args.modalControls ? args.modalControls : '';

	if (modalControls) {
		const element = content.querySelector(`.${args.modalControls}`);

		if (ifElementExists(element)) {
			content.querySelector(`.${args.modalControls}`).onclick = function (event) {
				const e = event || window.event;
				const image = document.querySelector('.cropper-view-box > img');
				let target = e.target || e.srcElement;
				let result;
				let data;
				let offset;

				if (isObjectEmpty(cropper) || !ifElementExists(image)) {
					return;
				}

				const cropperData = cropper.getContainerData();

				if (!isObjectEmpty(cropperData)) {
					const containerWidth = cropperData.width ? cropperData.width : '';
					const containerHeight = cropperData.height ? cropperData.height : '';

					const imageWidth = image.width ? image.width : '';
					const imageHeight = image.height ? image.height : '';
					const horizontalButton = document.getElementById(args.horizontalButton);
					const verticalButton = document.getElementById(args.verticalButton);

					if (ifElementExists(horizontalButton) && containerWidth && imageWidth) {
						offset = !isObjectEmpty(getOffsetPosition(image.left)) ? getOffsetPosition(image.left) : 0;
						horizontalButton.setAttribute('data-option', (containerWidth - imageWidth) / 2);
						horizontalButton.setAttribute('data-second-option', offset);
					}

					if (ifElementExists(verticalButton) && containerHeight && imageHeight) {
						offset = !isObjectEmpty(getOffsetPosition(image.top)) ? getOffsetPosition(image.top) : 0;
						verticalButton.setAttribute('data-option', offset);
						verticalButton.setAttribute('data-second-option', (containerHeight - imageHeight) / 2);
					}
				}

				while (target !== this) {
					if (target.getAttribute('data-method')) {
						break;
					}
					target = target.parentNode;
				}

				if (target === this || target.disabled || target.className.indexOf('disabled') > -1) {
					return;
				}

				data = {
					method: target.getAttribute('data-method'),
					option: target.getAttribute('data-option') || undefined,
					secondOption: target.getAttribute('data-second-option') || undefined
				};

				if (data.method) {
					result = cropper[data.method](data.option, data.secondOption);
				}
			};
		}
	}
};

export default controls;
