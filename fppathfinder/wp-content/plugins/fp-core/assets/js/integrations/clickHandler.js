import ajaxHandler from './ajaxHandler';

const clickHandler = () => {
	const buttons = document.querySelectorAll('.user-integration-activation-toggle');

	buttons.forEach(button => {
		button.addEventListener('click', e => {
			e.preventDefault();
			ajaxHandler(e, button);
		});
	});
};

export default clickHandler;
