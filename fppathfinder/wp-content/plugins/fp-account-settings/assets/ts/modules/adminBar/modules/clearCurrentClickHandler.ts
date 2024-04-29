import clearCurrentAjaxHandler from './clearCurrentAjaxHandler';
import createWorkingOverlay from '../../utilities/workingOverlay/createWorkingOverlay';

const clearCurrentClickHandler = () => {
	const button = document.querySelector('.transient-user-current .ab-item');

	if (button) {
		button.addEventListener('click', () => {
			createWorkingOverlay();
			clearCurrentAjaxHandler();
		});
	}
};

export default clearCurrentClickHandler;
