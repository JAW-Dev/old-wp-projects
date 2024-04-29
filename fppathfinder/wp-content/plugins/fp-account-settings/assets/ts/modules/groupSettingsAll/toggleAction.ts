const toggleAction = (field: HTMLInputElement, fieldsWrap: HTMLElement) => {
	const toggles: any = Array.from(fieldsWrap.getElementsByTagName('input'));

	for (let i = 0; i < toggles.length; i++) {
		if (toggles[i] === field) {
			toggles.splice(i, 1);
		}
	}

	if (field.checked) {
		toggles.forEach(toggle => {
			toggle.setAttribute('disabled', true);
			toggle.removeAttribute('checked');
		});
	}

	field.addEventListener('change', () => {
		if (field.checked) {
			toggles.forEach(toggle => {
				toggle.setAttribute('disabled', true);
				toggle.removeAttribute('checked');

				if (toggle.checked) {
					toggle.checked = false;
				}
			});
		} else {
			toggles.forEach(toggle => {
				toggle.removeAttribute('disabled');
			});
		}
	});
};

export default toggleAction;
