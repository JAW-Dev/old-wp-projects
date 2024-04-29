/* global document, jQuery */

const datePicker = target => {
	if (typeof target === 'undefined' || target === null) {
		return false;
	}

	jQuery(document).ready(() => {
		const from = jQuery(`#${target}-from-date`);
		const options = {
			dateFormat: 'yy-mm-dd'
		};
		if (from.length > 0) {
			jQuery(from).datepicker(options);
		}

		const to = jQuery(`#${target}-to-date`);
		if (to.length > 0) {
			jQuery(to).datepicker(options);
		}
	});
};

export default datePicker;
