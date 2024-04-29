// Import Modules
import colorScheme from './reset/colorScheme';
import imagePreview from './reset/imagePreview';
import fileUpload from './reset/fileUpload';
import formFields from './reset/formFields';
import alerts from './reset/alerts';
import scrollTo from './scrollTo';

const resetClickHandler = () => {
	const resetButton = document.getElementById('pdf-reset-flow-chart-btn');

	if (resetButton !== null) {
		resetButton.addEventListener('click', e => {
			e.preventDefault();
			colorScheme();
			imagePreview();
			fileUpload();
			formFields();
			alerts();

			const title = document.querySelector('.form-1-title');
			const rect = title.offsetTop + title.offsetHeight;

			scrollTo(document.documentElement, rect, 500);
		});
	}
};

export default resetClickHandler;
