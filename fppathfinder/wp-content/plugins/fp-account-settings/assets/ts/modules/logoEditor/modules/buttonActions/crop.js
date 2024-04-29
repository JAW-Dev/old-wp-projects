// Import Modules
import rebuildModal from '../rebuildModal';
import { ifElementExists, isObjectEmpty } from '../../helpers/index';

const crop = (args, cropper) => {
	// Bail if args is empty
	if (isObjectEmpty(args) || isObjectEmpty(cropper)) {
		return;
	}

	const cropButton = args.cropButton ? args.cropButton : '';

	if (cropButton) {
		const element = document.getElementById(cropButton);

		if (ifElementExists(element)) {
			element.addEventListener('click', () => {
				const previewImage = document.getElementById(args.previewImage);

				if (cropper.getCroppedCanvas()) {
					const imgSrc = cropper
						.getCroppedCanvas({
							fillColor: '#fff',
							imageSmoothingEnabled: true,
							imageSmoothingQuality: 'high'
						})
						.toDataURL();
					const logoData = document.getElementById(args.logoData);

					logoData.setAttribute('value', imgSrc);
					previewImage.setAttribute('src', imgSrc);
				}

				jQuery(`#${args.perviewLink}`).modaal('close');
				setTimeout(() => {
					rebuildModal(args, previewImage);
				}, 100);
			});
		}
	}
};

export default crop;
