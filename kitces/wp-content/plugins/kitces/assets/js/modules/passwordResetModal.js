import 'modaal';

const passwordResetModal = () => {
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	const reset = urlParams.get('password-reset');
	const closeButton = jQuery('#kitces-password-reset-modal-dismiss');
	const updateButton = jQuery('.kitces-password-reset-modal__update');

	if (!reset) {
		return false;
	}

	jQuery('#kitces-password-reset-modal').modaal({
		content_source: '#kitces-password-reset-modal',
		start_open: true,
		hide_close: false,
		width: 330
	});

	closeButton.on('click', () => {
		jQuery('#kitces-password-reset-modal').modaal('close');
	});

	updateButton.on('click', () => {
		jQuery('#kitces-password-reset-modal').modaal('close');
	});
};

export default passwordResetModal;
