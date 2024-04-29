// Import Modules
import checkFields from './checkFields';
import addAlert from './addAlert';
import scrollTo from '../scrollTo';

const formValidate = () => {
	const saveSettingsNotification = document.getElementById('save-notification-successful');
	const formNotificationArea = document.getElementById('form-notification-area-wrap');
	const formNotificationAreaList = formNotificationArea.getElementsByTagName('ul');

	const requiredFields = {
		businessDisplayName: document.getElementById('pdf-generator-business-display-name')
	};

	if (fppdfgData.advisorNameRequired === 'true') {
		requiredFields.adviserName = document.getElementById('pdf-generator-adviser-name');
	}

	const isValidated = checkFields(requiredFields);

	saveSettingsNotification.style.display = 'none';
	formNotificationArea.style.display = 'none';
	formNotificationArea.innerHTML = '<ul></ul>';

	if (!isValidated) {
		addAlert(requiredFields, formNotificationAreaList);
		const title = document.querySelector('.form-1-title');
		const rect = title.offsetTop + title.offsetHeight;

		formNotificationArea.style.display = 'block';

		scrollTo(document.documentElement, rect, 500);

		return false;
	}

	return true;
};

export default formValidate;
