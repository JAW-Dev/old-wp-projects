import slideDown from '../slide/slideDown';
import slideUp from '../slide/slideUp';

const clickHandler = box => {
	const header = box.querySelector('.accordian-header');

	if (!header) {
		return;
	}

	header.addEventListener('click', () => {
		const body: HTMLElement = box.querySelector('.accordian-body');

		if (!body) {
			return false;
		}

		if (box.classList.contains('opened')) {
			box.classList.remove('opened');
			slideUp(body);
		} else {
			box.classList.add('opened');
			slideDown(body);
		}
	});
};

export default clickHandler;
