// Import Modules
import getSelectedColors from './getSelectedColors';
import getCustomColors from './getCustomColors';

const getColors = () => {
	const selectedColors = document.getElementById('group-settings-pdf-color-set-settings-selected-colors');
	const isUsingCustomColors = document.getElementById('group-settings-pdf-color-set-is-using-custom-colors');

	if (isUsingCustomColors.value === 'true') {
		selectedColors.setAttribute('value', JSON.stringify(getCustomColors(isUsingCustomColors)));
	} else {
		selectedColors.setAttribute('value', JSON.stringify(getSelectedColors()));
	}
};

export default getColors;
