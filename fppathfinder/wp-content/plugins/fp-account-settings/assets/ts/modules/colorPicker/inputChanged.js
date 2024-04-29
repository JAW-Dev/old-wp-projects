// Import Modules
import validateHash from './validateHash';

const inputChanged = element => {
	jQuery(element).on('keyup change', e => {
		const current = jQuery(e.currentTarget);
		const isValidColor = validateHash(current.val());

		if (isValidColor) {
			current
				.parent()
				.children('.spectrum-color-box')
				.spectrum({
					color: `#${current.val()}`
				})
				.val(`#${current.val()}`);
		}
	});
};

export default inputChanged;
