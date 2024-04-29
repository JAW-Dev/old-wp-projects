const ajaxHandler = () => {
	const button = document.getElementById('member-sync-button');

	button.addEventListener('click', e => {
		e.preventDefault();

		const { nonce } = button.dataset;
		const { userid } = button.dataset;

		jQuery.ajax({
			type: 'post',
			url: ajaxurl,
			data: {
				action: 'sync_ac_member',
				nonce,
				userid
			},
			beforeSend() {
				button.innerHTML = 'Syncing Member';
			},
			success() {
				button.innerHTML = 'Member Synced';

				setTimeout(() => {
					button.innerHTML = 'Sync Member';
				}, 1500);
			}
		});
	});
};

export default ajaxHandler;
