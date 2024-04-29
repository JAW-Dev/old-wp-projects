import slideDown from '../slide/slideDown';
import slideUp from '../slide/slideUp';

const clickHandler = () => {
	const navWrap = document.getElementById('account-settings-nav');
	const body: HTMLElement = navWrap.querySelector('.accordian-body');

	if (navWrap.classList.contains('opened')) {
		navWrap.classList.remove('opened');
		slideUp(body);
	} else {
		navWrap.classList.add('opened');
		slideDown(body);
	}
};

export default clickHandler;
