import clearAllAjaxHandler from './clearAllAjaxHandler';
import createWorkingOverlay from '../../utilities/workingOverlay/createWorkingOverlay';

const clearAllClickHandler = () => {
	const button = document.querySelector('.transient-user-all .ab-item');

	if (button) {
		button.addEventListener('click', () => {
			createWorkingOverlay();
			clearAllAjaxHandler();
		});
	}
};

export default clearAllClickHandler;
