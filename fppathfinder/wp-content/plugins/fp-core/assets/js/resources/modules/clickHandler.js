const clickHandler = () => {
	const buttons = document.querySelectorAll('.send-to-crm');

	buttons.forEach(button => {
		button.addEventListener('click', e => {
			e.target.value = 'Sending';
		});
	});
};

export default clickHandler;
