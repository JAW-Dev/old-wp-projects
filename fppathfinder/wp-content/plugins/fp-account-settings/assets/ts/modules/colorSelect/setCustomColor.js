// Import Modules
import clearColors from './clearColors';
import selectedColor from './selectedColor';
import toggleCustomColors from './toggleCustomColors';

const setCustomColor = (prefix, colors, inputSelector) => {
	const customButton = document.getElementById(`${prefix}-custom-color-scheme-btn`);

	if (customButton !== null) {
		customButton.addEventListener('click', e => {
			e.preventDefault();
			customButton.blur();

			const isUsingCustomColors = document.getElementById(`${prefix}-is-using-custom-colors`);
			const selected = selectedColor(colors);

			clearColors(colors, selected, inputSelector);

			isUsingCustomColors.setAttribute('value', true);
			toggleCustomColors(prefix, isUsingCustomColors);
		});
	}
};

export default setCustomColor;
