// Import Module
import previewClickHandler from './previewClickHandler';
import saveClickHandler from './saveClickHandler';
import resetClickHandler from './resetClickHandler';

const submitForm = () => {
	previewClickHandler();
	saveClickHandler();
	resetClickHandler();
};

export default submitForm;
