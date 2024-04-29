const enableInputs = element => {
	const inputs = element.querySelectorAll('input');
	inputs.forEach(input => {
		input.disabled = false;
	});
};

export default enableInputs;
