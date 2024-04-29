// Import Modules
import pickerChanged from './pickerChanged';

const setPickers = () => {
	const colorPickers = jQuery('.spectrum-color-box');

	colorPickers.each((index, picker) => {
		const $picker = jQuery(picker);

		jQuery(picker).spectrum({
			color: $picker.val()
		});

		pickerChanged($picker);
	});
};

export default setPickers;
