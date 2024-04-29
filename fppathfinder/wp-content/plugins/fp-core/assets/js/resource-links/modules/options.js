const options = () => {
	const button = document.getElementById('share-link-options-button');
	const clientName = document.getElementById('client-name');

	if (!button || !clientName) {
		return false;
	}

	const hasCRM = button.dataset.crm;
	const { linked } = button.dataset;

	if (hasCRM === 'false') {
		clientName.addEventListener('input', e => {
			const query = e.currentTarget.value;

			if (query.length >= 3) {
				button.disabled = false;
			} else {
				button.disabled = true;
			}
		});
	}

	if (hasCRM === 'true' && !linked) {
		button.disabled = true;
	}
};

export default options;
