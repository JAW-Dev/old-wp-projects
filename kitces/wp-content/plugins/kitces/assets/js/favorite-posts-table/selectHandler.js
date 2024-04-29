const selectHandler = () => {
	const select = document.getElementById('kitces-saved-articles-categories-select');

	if (!select) {
		return false;
	}

	select.addEventListener('change', () => {
		const selectValue = select.value;
		const urlParams = new URLSearchParams(window.location.search);

		if (selectValue.length > 0) {
			urlParams.set('category', selectValue);
		} else {
			urlParams.delete('category');
		}

		window.location.search = urlParams;
	});
};

export default selectHandler;
