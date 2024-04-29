import slideUp from '../slide/slideUp';

const itemClickHandler = () => {
	const navWrap = document.getElementById('account-settings-nav');
	const body: HTMLElement = navWrap.querySelector('.accordian-body');

	navWrap.classList.remove('opened');
	slideUp(body);
};

export default itemClickHandler;
