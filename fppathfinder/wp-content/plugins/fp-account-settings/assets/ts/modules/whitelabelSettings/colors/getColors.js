// Import Modules
import getSelectedColors from './getSelectedColors';
import getCustomColors from './getCustomColors';
import { isObjectEmpty } from '../../logoEditor/helpers/index';

const getColors = () => {
	const selectedColors = document.getElementById('pdf-generator-color-set-settings-selected-colors');
	const isUsingCustomColors = document.getElementById('pdf-generator-color-set-is-using-custom-colors');

	if (!isUsingCustomColors) {
		if (isObjectEmpty(fp_account_settingsData)) {
			return false;
		}

		if (fp_account_settingsData.group_whitelabel_settings.color_set !== undefined) {
			selectedColors.setAttribute('value', JSON.stringify(fp_account_settingsData.group_whitelabel_settings.color_set));
		}

		return false;
	}

	if (isUsingCustomColors.value === 'true') {
		selectedColors.setAttribute('value', JSON.stringify(getCustomColors(isUsingCustomColors)));
	} else {
		selectedColors.setAttribute('value', JSON.stringify(getSelectedColors()));
	}
};

export default getColors;
