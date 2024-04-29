const advanced = () => {
	const section = document.getElementById('back-page-fields');
	const fields = Array.from(section.querySelectorAll('.form-control'));
	const advancedFields: HTMLElement[] = [];
	const basicFields: HTMLElement[] = [];
	const button = document.getElementById('advanced-button');

	fields.forEach((field: any) => {
		if (field.classList.contains('advanced')) {
			advancedFields.push(field);
		}
		if (!field.classList.contains('advanced')) {
			basicFields.push(field);
		}
	});

	button.addEventListener('click', e => {
		e.preventDefault();

		if (!button.classList.contains('toggled')) {
			button.classList.add('toggled');
		}

		if (button.classList.contains('toggled')) {
			button.classList.remove('toggled');
		}

		fields.forEach(field => {
			if (!field.classList.contains('advanced')) {
				field.classList.add('hide');
			}
		});

		advancedFields.forEach((advancedField: HTMLElement) => {
			advancedField.classList.remove('hide');
			advancedField.classList.add('toggled');
		});

		if (button.classList.contains('toggled')) {
			fields.forEach(field => {
				field.classList.remove('hide');
			});

			advancedFields.forEach((advancedField: HTMLElement) => {
				advancedField.classList.remove('toggled');
				if (advancedField.classList.contains('to-hide')) {
					advancedField.classList.add('hide');
				}
			});
		}
	});
};

export default advanced;
