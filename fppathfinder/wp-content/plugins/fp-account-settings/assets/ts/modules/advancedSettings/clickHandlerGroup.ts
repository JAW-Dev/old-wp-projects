const clickHandlerGroup = () => {
	let button = document.getElementById('group-advanced-button');

	if (!button) {
		return false;
	}

	button.addEventListener('click', (e: Event) => {
		e.preventDefault();

		const useInput = document.getElementById('group-settings-pdf-use-advanced') as HTMLInputElement;
		const bpSettings = document.getElementById('group-back-page-fields');
		const bpaSettings = document.getElementById('group-advanced-back-page-fields');
		button = document.getElementById('group-advanced-button');

		if (useInput.value === 'false') {
			useInput.value = 'true';
			bpSettings.style.display = 'none';
			bpaSettings.style.display = 'block';
			button.innerHTML = 'Switch to Basic';
		} else {
			useInput.value = 'false';
			bpSettings.style.display = 'block';
			bpaSettings.style.display = 'none';
			button.innerHTML = 'Switch to Advanced';
		}
	});
};

export default clickHandlerGroup;
