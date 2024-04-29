// Import Modules
import clearColors from './clearColors';
import selectedColor from './selectedColor';
import toggleCustomColors from './toggleCustomColors';

const setCustomColor = (colors, inputSelector) => {
	const customButton = document.getElementById('pdf-generator-logo-custom-color-scheme-btn');

	if (customButton !== null) {
		customButton.addEventListener('click', e => {
			e.preventDefault();

			const isUsingCustomColors = document.getElementById('is-using-custom-colors');
			const selected = selectedColor(colors);

			clearColors(colors, selected, inputSelector);

			isUsingCustomColors.setAttribute('value', true);
			toggleCustomColors(isUsingCustomColors);
		});
	}
};

export default setCustomColor;
