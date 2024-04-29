// Import Modules
import clearColors from './clearColors';
import selectedColor from './selectedColor';
import toggleCustomColors from './toggleCustomColors';

const setColor = (colors, inputSelector) => {
	colors.forEach(color => {
		color.addEventListener('click', e => {
			e.preventDefault();

			const selected = selectedColor(colors);
			const radio = color.querySelector(inputSelector);
			const isUsingCustomColors = document.getElementById('is-using-custom-colors');

			clearColors(colors, selected, inputSelector);

			color.classList.add('selected');
			radio.setAttribute('checked', '');

			isUsingCustomColors.setAttribute('value', false);
			toggleCustomColors(isUsingCustomColors);
		});
	});
};

export default setColor;
