const shareCounts = () => {
	const shareButton = document.querySelector('.shared-counts-button.email');

	if (!shareButton) {
		return;
	}

	shareButton.addEventListener('click', () => {
		const nameField = document.getElementById('shared-counts-modal-name');
		const emailField = document.getElementById('shared-counts-modal-email');

		if (nameField && emailField) {
			const { user } = kitcesUserInfo;

			if (user) {
				if (user.name && user.email) {
					nameField.value = user.name;
					emailField.value = user.email;
				}
			}
		}
	});
};

export default shareCounts;
