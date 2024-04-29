// Import Modules
import getSelectedColors from './getSelectedColors';
import getCustomColors from './getCustomColors';

const getColors = () => {
	const selectedColors = document.getElementById('pdf-generator-selected-colors-1');
	const isUsingCustomColors = document.getElementById('is-using-custom-colors');

	if (isUsingCustomColors.value === 'true') {
		selectedColors.setAttribute('value', JSON.stringify(getCustomColors(isUsingCustomColors)));
	} else {
		selectedColors.setAttribute('value', JSON.stringify(getSelectedColors()));
	}

	return selectedColors;
};

export default getColors;
