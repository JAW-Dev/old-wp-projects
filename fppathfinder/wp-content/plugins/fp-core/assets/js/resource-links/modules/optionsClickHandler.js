import optionToggleClickHandler from './optionToggleClickHandler';

const optionsClickHandler = () => {
	const button = document.getElementById('share-link-options-button');
	const submitButton = document.getElementById('submit-button');
	const reviewButton = document.querySelector('.review-notes-button');

	if (!button) {
		return false;
	}

	submitButton.display = 'inline-block';
	reviewButton.disabled = false;

	button.addEventListener('click', e => {
		e.preventDefault();

		const options = document.getElementById('share-link-options');
		const hidden = options.style.display;

		const hideMoreIconsToggle = document.getElementById('checklist-hide-more-icons');
		const showMoreDetailsToggle = document.getElementById('checklist-show-more-details');
		const removeQuestionsToggle = document.getElementById('checklist-remove-questions');

		if (hidden === 'none') {
			options.style.display = 'block';
			reviewButton.disabled = true;
			optionToggleClickHandler();
		}

		if (hidden === 'block') {
			options.style.display = 'none';
			reviewButton.disabled = false;
		}

		if (hideMoreIconsToggle) {
			if (hideMoreIconsToggle.checked) {
				reviewButton.disabled = true;
			}
		}

		if (showMoreDetailsToggle) {
			if (showMoreDetailsToggle.checked) {
				reviewButton.disabled = true;
			}
		}

		if (removeQuestionsToggle) {
			if (removeQuestionsToggle.checked) {
				reviewButton.disabled = true;
			}
		}
	});
};

export default optionsClickHandler;
