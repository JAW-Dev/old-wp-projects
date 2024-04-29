const disableInputs = element => {
	const inputs = element.querySelectorAll('input');
	inputs.forEach(input => {
		input.disabled = true;
	});
};

export default disableInputs;
