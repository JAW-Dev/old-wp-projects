// Import Modules
import clearColors from './clearColors';
import selectedColor from './selectedColor';
import toggleCustomColors from './toggleCustomColors';

const setColor = (prefix, colors, inputSelector) => {
	colors.forEach(color => {
		color.addEventListener('click', e => {
			e.preventDefault();

			const selected = selectedColor(colors);
			const radio = color.querySelector(inputSelector);
			const isUsingCustomColors = document.getElementById(`${prefix}-is-using-custom-colors`);

			clearColors(colors, selected, inputSelector);

			color.classList.add('selected');
			radio.setAttribute('checked', '');

			isUsingCustomColors.setAttribute('value', false);
			toggleCustomColors(prefix, isUsingCustomColors);
		});
	});
};

export default setColor;
