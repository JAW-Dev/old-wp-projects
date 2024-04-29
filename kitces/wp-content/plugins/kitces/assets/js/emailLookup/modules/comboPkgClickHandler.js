const comboPkgClickHandler = () => {
	const button = document.getElementById('signup-toggle-email');

	button.addEventListener('click', e => {
		e.preventDefault();

		const email = document.getElementById('combo-signup-email-submit');
		const message = document.getElementById('combo-message');

		email.classList.remove('hide');
		email.classList.add('show');
		message.classList.remove('show');
		message.classList.add('hide');
	});
};

export default comboPkgClickHandler;
