import scrollTo from '../scrollTo/scrollTo';
import fadeOut from '../fadeInOut/fadeOut';

const saveSuccess = (element: HTMLElement) => {
	element.style.display = 'none';
	const title = document.getElementById('body-section__title');
	const rect = title.offsetTop + title.offsetHeight;

	element.style.display = 'block';

	scrollTo(document.documentElement, rect, 500);
	fadeOut(element);
};

export default saveSuccess;
