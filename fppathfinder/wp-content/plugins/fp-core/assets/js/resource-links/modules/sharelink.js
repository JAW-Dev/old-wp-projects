import sharelinkClickHandler from './sharelinkClickHandler';
import sharedPage from './sharedPage';
import options from './options';
import optionsClickHandler from './optionsClickHandler';
import hideQuestionClickHandler from './hideQuestionClickHandler';
import advisorPathClickHandler from './advisorPathClickHandler';
import advisorPathDescription from './advisorPathDescription';
import optionsButtonDescription from './optionsButtonDescription';

const shareLink = () => {
	// Clear Yes/No checkboxes on page load.
	if (fpSettings.isShareLink) {
		const checkboxes = document.querySelectorAll('input[type="radio"]');

		if (checkboxes.length > 0) {
			checkboxes.forEach(checkbox => {
				checkbox.checked = false;
			});
		}
	}

	if (fpSettings.checklists_v_two) {
		advisorPathClickHandler();
	}

	sharelinkClickHandler();
	sharedPage();
	options();
	optionsClickHandler();
	hideQuestionClickHandler();
	advisorPathDescription();
	optionsButtonDescription();
};

export default shareLink;
