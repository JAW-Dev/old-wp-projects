import slideDown from './slideDown';
import slideUp from './slideUp';

const slideToggle = (target: HTMLElement, duration: number = 300) => {
	if (window.getComputedStyle(target).display === 'none') {
		return slideDown(target, duration);
	}
	return slideUp(target, duration);
};

export default slideToggle;
