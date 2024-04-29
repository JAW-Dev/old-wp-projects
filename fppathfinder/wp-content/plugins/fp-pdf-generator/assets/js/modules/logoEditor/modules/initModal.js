// Import Packages
import modaal from 'modaal';

// Import Modules
import initCropper from './initCropper';
import { isObjectEmpty } from '../helpers/index';

const initModal = args => {
	// Bail if args is empty
	if (isObjectEmpty(args)) {
		return;
	}

	jQuery(`#${args.perviewLink}`).modaal({
		content_source: `#${args.modal}`,
		animation: 'none',
		overlay_close: false,
		hide_close: true,
		after_open: () => {
			initCropper(args);
		}
	});
};

export default initModal;
