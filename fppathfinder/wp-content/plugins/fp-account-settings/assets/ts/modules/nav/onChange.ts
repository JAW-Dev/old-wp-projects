import clickHandler from './clickHandler';
import itemClickHandler from './itemClickHandler';

const onChange = mediaQuery => {
	if (mediaQuery.matches) {
		const menu = document.getElementById('account-settings-nav-list');
		const navWrap = document.getElementById('account-settings-nav');

		if (!navWrap) {
			return false;
		}

		const header = navWrap.querySelector('.accordian-header');
		const navItems = navWrap.querySelectorAll('.nav-list__item');

		navWrap.classList.remove('opened');

		menu.removeAttribute('style');

		header.removeEventListener('click', clickHandler);

		navItems.forEach(navItem => {
			navItem.removeEventListener('click', itemClickHandler);
		});
	} else {
		const navWrap = document.getElementById('account-settings-nav');

		if (!navWrap) {
			return false;
		}

		const header = navWrap.querySelector('.accordian-header');
		const navItems = navWrap.querySelectorAll('.nav-list__item');

		header.addEventListener('click', clickHandler);

		navItems.forEach(navItem => {
			navItem.addEventListener('click', itemClickHandler);
		});
	}
};
export default onChange;
