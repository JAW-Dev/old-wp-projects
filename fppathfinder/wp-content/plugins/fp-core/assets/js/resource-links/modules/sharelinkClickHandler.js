import modaal from 'modaal';

import sharelinkAjaxHandler from './sharelinkAjaxHandler';
import sharelinkAjaxHandlerV2 from './sharelinkAjaxHandlerV2';
import copyLinkClickHandler from './copyLinkClickHandler';

const singleContact = () => {
	const button = document.getElementById('resource-share-link-button');
	const buttons = document.querySelectorAll('.resource-share-link__button-trigger');
	const demoButton = document.getElementById('resource-share-link-button-demo');

	if (buttons !== null) {
		buttons.forEach(buttona => {
			if (buttona !== null) {
				const message = document.getElementById('resource-share-link-modal-copy-successful');
				const { contactid } = buttona.dataset;

				buttona.addEventListener('click', e => {
					e.preventDefault();

					const clientField = document.getElementById('client-name');

					const clientButton = document.getElementById('checklist-control-client-path-button').classList.contains('active');

					if (!clientField.checkValidity() && clientButton) {
						const errorField = document.querySelector('.client-field-error.error');
						errorField.style.display = 'block';

						e.preventDefault();
						e.stopImmediatePropagation();
					}
				});

				jQuery(buttona).modaal({
					should_open: () => {
						let clientName = '';

						if (fpResourceLinksData.hasCrm) {
							const clientLookupError = document.querySelector('.client-field-error');
							clientLookupError.classList.remove('show');

							if ((contactid === undefined || contactid === '0') && !fpSettings.checklists_v_two) {
								clientLookupError.classList.add('show');
								return false;
							}

							const clientNameField = document.getElementById('hidden-client-name');
							clientName = clientNameField !== null ? clientNameField.value : '';
						}

						if (fpResourceLinksData.hasCrm === null) {
							const clientNameInputError = document.querySelector('.client-name-field-error');
							const clientNameInput = document.getElementById('client-name').value;

							if (clientNameInput !== null && clientNameInputError !== null) {
								clientNameInputError.classList.remove('show');

								if (clientNameInput.length <= 0) {
									clientNameInputError.classList.add('show');
								} else {
									clientName = clientNameInput;
								}
							}

							if (clientNameInput === null || clientNameInput.length <= 0) {
								return false;
							}
						}

						sharelinkAjaxHandler(contactid, clientName, buttona).then(() => {
							copyLinkClickHandler();
						});
						return true;
					},
					content_source: '#resource-share-link-modal',
					after_close: () => {
						message.classList.remove('success');
					}
				});
			}

			if (demoButton !== null) {
				demoButton.addEventListener('click', e => {
					e.preventDefault();
				});
				jQuery('#resource-share-link-button-demo').modaal({
					content_source: '#resource-share-link-modal-demo'
				});
			}
		});
	}

	if (button !== null) {
		const message = document.getElementById('resource-share-link-modal-copy-successful');
		const { contactid } = button.dataset;

		button.addEventListener('click', e => {
			e.preventDefault();
		});

		jQuery('#resource-share-link-button').modaal({
			should_open: () => {
				let clientName = '';

				if (fpResourceLinksData.hasCrm) {
					const clientLookupError = document.querySelector('.client-field-error');
					clientLookupError.classList.remove('show');

					if (contactid === undefined || contactid === '0') {
						clientLookupError.classList.add('show');
						return false;
					}

					const clientNameField = document.getElementById('hidden-client-name');
					clientName = clientNameField !== null ? clientNameField.value : '';
				}

				if (fpResourceLinksData.hasCrm === null) {
					const clientNameInputError = document.querySelector('.client-name-field-error');
					const clientNameInput = document.getElementById('client-name').value;

					if (clientNameInput !== null && clientNameInputError !== null) {
						clientNameInputError.classList.remove('show');

						if (clientNameInput.length <= 0) {
							clientNameInputError.classList.add('show');
						} else {
							clientName = clientNameInput;
						}
					}

					if (clientNameInput === null || clientNameInput.length <= 0) {
						return false;
					}
				}

				sharelinkAjaxHandler(contactid, clientName, button).then(() => {
					copyLinkClickHandler();
				});
				return true;
			},
			content_source: '#resource-share-link-modal',
			after_close: () => {
				message.classList.remove('success');
			}
		});
	}
};

const groupContact = () => {
	const buttonTop = document.querySelector('.resource-share-link__button-trigger.first');
	const buttonBottom = document.querySelector('.resource-share-link__button-trigger.last');

	if (buttonTop === null || buttonBottom === null) {
		return false;
	}

	const buttons2 = [buttonTop, buttonBottom];

	if (buttons2.length <= 0) {
		return false;
	}

	buttons2.forEach(buttona => {
		buttona.addEventListener('click', e => {
			e.preventDefault();
		});

		if (buttona === null) {
			return false;
		}

		const message = document.getElementById('resource-share-link-modal-copy-successful');

		jQuery(buttona).modaal({
			should_open: () => {
				sharelinkAjaxHandlerV2(buttona).then(() => {
					copyLinkClickHandler();
				});
				return true;
			},
			content_source: '#resource-share-link-modal',
			after_close: () => {
				message.classList.remove('success');
			}
		});
	});
};

const sharelinkClickHandler = () => {
	const optionsButton = document.getElementById('share-link-options-button');

	optionsButton.addEventListener('click', () => {
		const advisorButton = document.getElementById('checklist-control-advisor-path-button').classList.contains('active');
		const clientButton = document.getElementById('checklist-control-client-path-button').classList.contains('active');
		const groupButton = document.getElementById('checklist-control-group-path-button').classList.contains('active');

		if (fpSettings.checklists_v_two && groupButton) {
			groupContact();
			return false;
		}

		if (advisorButton || clientButton) {
			singleContact();
			return false;
		}
	});

	const demoButton = document.getElementById('resource-share-link-button-demo');

	if (demoButton !== null) {
		demoButton.addEventListener('click', e => {
			e.preventDefault();
		});
		jQuery('#resource-share-link-button-demo').modaal({
			content_source: '#resource-share-link-modal-demo'
		});
	}
};

export default sharelinkClickHandler;
