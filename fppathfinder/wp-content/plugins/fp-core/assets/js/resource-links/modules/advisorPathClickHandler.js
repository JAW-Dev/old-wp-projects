import { toggleClassesClickHandler } from '../../utils/elements/toggleClassesClickHandler';

const advisorPathClickHandler = () => {
	const advisorButton = document.getElementById('checklist-control-advisor-path-button');
	const clientButton = document.getElementById('checklist-control-client-path-button');
	const groupButton = document.getElementById('checklist-control-group-path-button');

	const clientField = document.querySelector('.interactive-resource__client-name-field');
	const shareLink = document.querySelector('.interactive-resource__client-links');
	const shareLinkOptions = document.getElementById('share-link-options');

	const reviewButton = document.querySelector('.review-notes-button');
	const bottomShareLink = document.querySelector('.resource-share-link__button.resource-share-link__button-trigger.last');
	const errorField = document.querySelector('.client-field-error.error');

	if (clientField !== null) {
		clientField.style.display = 'block';
	}

	if (shareLink !== null) {
		shareLink.style.display = 'none';
	}

	if (reviewButton !== null) {
		reviewButton.style.display = 'block';
	}

	if (bottomShareLink !== null) {
		bottomShareLink.style.display = 'none';
	}

	if (shareLinkOptions !== null) {
		shareLinkOptions.style.display = 'none';
	}

	if (errorField !== null) {
		errorField.style.display = 'none';
	}

	if (advisorButton !== null) {
		advisorButton.addEventListener('click', e => {
			e.preventDefault();
			document.getElementById('interactive-checklist-form').reset();
			toggleClassesClickHandler('active', advisorButton, [clientButton, groupButton]);

			if (clientField !== null) {
				clientField.style.display = 'block';
			}

			if (shareLink !== null) {
				shareLink.style.display = 'none';
			}

			if (reviewButton !== null) {
				reviewButton.style.display = 'block';
			}

			if (bottomShareLink !== null) {
				bottomShareLink.style.display = 'none';
			}

			if (shareLinkOptions !== null) {
				shareLinkOptions.style.display = 'none';
			}

			if (errorField !== null) {
				errorField.style.display = 'none';
			}
		});
	}

	if (clientButton !== null) {
		clientButton.addEventListener('click', e => {
			e.preventDefault();
			document.getElementById('interactive-checklist-form').reset();
			toggleClassesClickHandler('active', clientButton, [advisorButton, groupButton]);

			if (clientField !== null) {
				clientField.style.display = 'block';
			}

			if (shareLink !== null) {
				shareLink.style.display = 'block';
			}

			if (reviewButton !== null) {
				reviewButton.style.display = 'none';
			}

			if (bottomShareLink !== null) {
				bottomShareLink.style.display = 'block';
			}

			if (shareLinkOptions !== null) {
				shareLinkOptions.style.display = 'none';
			}

			if (errorField !== null) {
				errorField.style.display = 'none';
			}
		});
	}

	if (groupButton !== null) {
		groupButton.addEventListener('click', e => {
			e.preventDefault();
			document.getElementById('interactive-checklist-form').reset();
			toggleClassesClickHandler('active', groupButton, [advisorButton, clientButton]);

			if (clientField !== null) {
				clientField.style.display = 'none';
			}

			if (shareLink !== null) {
				shareLink.style.display = 'block';
			}

			if (reviewButton !== null) {
				reviewButton.style.display = 'none';
			}

			if (bottomShareLink !== null) {
				bottomShareLink.style.display = 'block';
			}

			if (shareLinkOptions !== null) {
				shareLinkOptions.style.display = 'none';
			}

			if (errorField !== null) {
				errorField.style.display = 'none';
			}
		});
	}
};

export default advisorPathClickHandler;
