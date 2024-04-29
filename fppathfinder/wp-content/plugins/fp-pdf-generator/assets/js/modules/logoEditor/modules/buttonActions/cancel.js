// Import Modules
import rebuildModal from '../rebuildModal';
import { ifElementExists, isObjectEmpty } from '../../helpers/index';

const cancel = args => {
	// Bail if args is empty
	if (isObjectEmpty(args)) {
		return;
	}

	const cancelButton = args.cancelButton ? args.cancelButton : '';

	if (cancelButton) {
		const element = document.getElementById(args.cancelButton);

		if (ifElementExists(element)) {
			element.addEventListener('click', function () {
				const previewLink = args.perviewLink ? args.perviewLink : '';

				if (previewLink) {
					jQuery(`#${previewLink}`).modaal('close');
				}

				setTimeout(() => {
					rebuildModal(args);
				}, 100);
			});
		}
	}
};

export default cancel;
