const hideQuestionClickHandler = () => {
	const toggle = document.getElementById('checklist-remove-questions');

	if (!toggle) {
		return false;
	}

	toggle.addEventListener('change', () => {
		const checkboxes = document.querySelectorAll('.question__checkbox-top-label');

		if (!checkboxes) {
			return false;
		}

		if (toggle.checked) {
			checkboxes.forEach(checkbox => {
				checkbox.style.display = 'flex';
			});
		} else {
			checkboxes.forEach(checkbox => {
				checkbox.style.display = 'none';
				checkbox.checked = false;
			});
		}
	});
};

export default hideQuestionClickHandler;
