jQuery(document).ready(() => {
	const button = jQuery('.tingle-modal__close');

	jQuery(document).on('gform_post_render', () => {
		const header = jQuery('.gpnf-modal-header');
		header.append(button);
	});

	const closeBtns = jQuery('.tingle-modal__close', '.gpnf-btn-cancel', '.gpnf-btn-submit');
	const modal = jQuery('.tingle-modal');

	closeBtns.each((index, buttons) => {
		jQuery(buttons).on('click', () => {
			modal.prepend(button);
		});
	});
});
