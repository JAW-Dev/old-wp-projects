const linkClickHandler = () => {
	jQuery(document.body).on('click', '#signup-email-submit-existing-message-links-email', e => {
		e.preventDefault();

		const body = jQuery('.signup-email-submit__body');
		const message = jQuery('.signup-email-submit__existing-message');

		body.removeClass('hide');
		message.removeClass('show');
	});
};

export default linkClickHandler;
