const resetButtons = () => {
	const buttons = document.querySelectorAll('.integration-activation-toggle');
	const parent = document.querySelector('.body-section__integrations');
	const statusMessages = parent.querySelectorAll('.app-itegration__status');

	statusMessages.forEach((message: HTMLElement) => {
		const classes = message.classList;
		classes.remove('active');
		classes.add('inactive');
		message.innerHTML = '';
	});

	buttons.forEach((button: HTMLInputElement) => {
		const classes = button.classList;

		button.dataset.isActive = '0';
		button.value = 'Inactive';

		if (classes.contains('pill-button__red')) {
			classes.remove('pill-button__red');
			classes.add('pill-button__grey');
		}
	});
};

export default resetButtons;
