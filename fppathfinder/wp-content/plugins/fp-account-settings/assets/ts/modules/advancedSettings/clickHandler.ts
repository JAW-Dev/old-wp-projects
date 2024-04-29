const clickHandler = () => {
	let button = document.getElementById('advanced-button');

	button.addEventListener('click', (e: Event) => {
		e.preventDefault();

		const useInput = document.getElementById('pdf-generator-use-advanced') as HTMLInputElement;
		const bpSettings = document.getElementById('back-page-fields');
		const bpaSettings = document.getElementById('advanced-back-page-fields');
		button = document.getElementById('advanced-button');

		if (useInput === null || bpSettings === null || bpaSettings === null || button === null) {
			return;
		}

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

export default clickHandler;
