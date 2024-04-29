declare var fp_account_settingsAdminAjax;

const removeSharedLinkClickHandlers = () => {
	const buttons = Array.from(document.querySelectorAll('.share_links_remove_button')) as HTMLButtonElement[];

	if (!buttons.length) {
		return;
	}

	buttons.forEach((button: HTMLElement | null) => {
		button.addEventListener('click', e => {
			e.preventDefault();
			const { nonce, sharekey } = button.dataset;
			jQuery.ajax({
				type: 'post',
				url: fp_account_settingsAdminAjax,
				data: {
					action: 'remove_shared_link',
					nonce,
					sharekey
				},
				success: response => {
					if (response) {
						button.parentElement.parentElement.parentElement.style.display = 'none';
					}
				}
			});
		});
	});
};

export default removeSharedLinkClickHandlers;
