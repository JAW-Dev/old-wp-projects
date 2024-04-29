const populateDataField = () => {
	const inputField = document.getElementById('merge-member-email');

	inputField?.addEventListener('input', (e: Event) => {
		const button = document.getElementById('merge-member-button') as HTMLLinkElement;
		const target = e.target as HTMLInputElement;

		button.dataset.mergeEmail = target.value;
	});
};

export default populateDataField;
