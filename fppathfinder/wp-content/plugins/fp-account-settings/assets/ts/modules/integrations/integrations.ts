import ajaxHandler from './ajaxHandler';

const clickHandler = () => {
	const buttons = Array.from(document.querySelectorAll('.integration-activation-toggle'));

	buttons.forEach((button: HTMLInputElement) => {
		button.addEventListener('click', e => {
			e.preventDefault();
			ajaxHandler(e, button);
		});
	});
};

export default clickHandler;
