const setOption = (button, toggle, dataAttr) => {
	toggle.addEventListener('change', () => {
		if (toggle.checked) {
			button.dataAttr = 'true';
		} else {
			button.dataAttr = 'false';
		}
	});
};

const optionToggleClickHandler = () => {
	const groupButton = document.getElementById('checklist-control-group-path-button').classList.contains('active');
	let button = document.getElementById('resource-share-link-button');
	let bottomButton = document.querySelector('.resource-share-link__button-trigger.last');

	if (fpSettings.checklists_v_two && groupButton) {
		button = document.querySelector('.resource-share-link__button-trigger.first');
		bottomButton = document.querySelector('.resource-share-link__button-trigger.last');
	}

	const hideMoreIconsToggle = document.getElementById('checklist-hide-more-icons');
	const showMoreDetailsToggle = document.getElementById('checklist-show-more-details');
	const removeQuestionsToggle = document.getElementById('checklist-remove-questions');
	const rows = document.querySelectorAll('.first-row');
	const reviewButton = document.querySelector('.review-notes-button');

	if (hideMoreIconsToggle) {
		hideMoreIconsToggle.addEventListener('change', () => {
			reviewButton.disabled = false;

			if (hideMoreIconsToggle.checked) {
				button.dataset.hidemoreicons = 'true';
				bottomButton.dataset.hidemoreicons = 'true';
				reviewButton.disabled = true;
			} else {
				button.dataset.hidemoreicons = 'false';
				bottomButton.dataset.hidemoreicons = 'false';
				reviewButton.disabled = false;
			}
		});
	}

	if (showMoreDetailsToggle) {
		showMoreDetailsToggle.addEventListener('change', () => {
			reviewButton.disabled = false;

			if (showMoreDetailsToggle.checked) {
				button.dataset.showmoredetails = 'true';
				bottomButton.dataset.showmoredetails = 'true';
				reviewButton.disabled = true;
			} else {
				button.dataset.showmoredetails = 'false';
				bottomButton.dataset.showmoredetails = 'false';
				reviewButton.disabled = false;
			}
		});
	}

	if (removeQuestionsToggle) {
		removeQuestionsToggle.addEventListener('change', () => {
			reviewButton.disabled = false;

			if (removeQuestionsToggle.checked) {
				button.dataset.removequestions = 'true';
				bottomButton.dataset.removequestions = 'true';
				reviewButton.disabled = true;
			} else {
				button.dataset.removequestions = 'false';
				bottomButton.dataset.removequestions = 'false';
				reviewButton.disabled = false;

				rows.forEach(row => {
					const checkmark = row.querySelector('.question__checkbox');
					const text = row.querySelector('.text');
					const tooltip = row.querySelector('.tooltip-button');
					const notes = row.querySelector('.notes-button');
					const inputs = row.querySelector('.inputs');

					if (checkmark.checked === true) {
						checkmark.click();
					}

					if (text && text.classList.contains('hidden-question')) {
						text.classList.remove('hidden-question');
					}

					if (tooltip && tooltip.classList.contains('hidden-question')) {
						tooltip.classList.remove('hidden-question');
					}

					if (notes.classList.contains('hidden-question')) {
						notes.cnotes && lassList.remove('hidden-question');
					}

					if (inputs && inputs.classList.contains('hidden-question')) {
						inputs.classList.remove('hidden-question');
					}
				});
			}
		});
	}
};

export default optionToggleClickHandler;
