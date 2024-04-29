const resetButtons = () => {
	const buttons = document.querySelectorAll('.user-integration-activation-toggle');

	buttons.forEach(button => {
		const { slug } = button.dataset;
		const status = document.getElementById(`${slug}_status`);

		button.dataset.isActive = 0;
		button.value = 'Activate';
		status.innerHTML = 'Inactive';
	});
};

export default resetButtons;
