const pickerChanged = element => {
	element.on('change.spectrum', el => {
		const target = jQuery(el.currentTarget);
		const color = jQuery(target).spectrum('get').toHexString().substr(1);
		target.val(jQuery(target).spectrum('get').toHexString());

		target.parent().find('.custom-color-input').val(color);
	});
};

export default pickerChanged;
