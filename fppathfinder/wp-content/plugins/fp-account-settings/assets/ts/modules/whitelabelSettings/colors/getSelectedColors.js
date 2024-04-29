const getSelectedColors = () => {
	const colorRadios = document.getElementsByName('pdf-generator-color-set-color-scheme');
	let setRadio;

	for (let i = 0; i < colorRadios.length; i++) {
		if (colorRadios[i].checked) {
			setRadio = colorRadios[i];
		}
	}

	const selectedParentColor = setRadio.closest('.set-colors');
	const colorElements = selectedParentColor.querySelectorAll('.colors .color-set-color');
	const colorObj = {};

	for (let i = 0; i < colorElements.length; i++) {
		const colorHex = colorElements[i].dataset.hexColor;
		const g = i + 1;
		colorObj[`color${g}`] = colorHex;
	}

	return colorObj;
};

export default getSelectedColors;
