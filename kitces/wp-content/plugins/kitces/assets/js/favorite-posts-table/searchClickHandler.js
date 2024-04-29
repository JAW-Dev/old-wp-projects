const searchClickHandler = () => {
	const button = document.getElementById('kitces-saved-articles-search-button');

	if (button) {
		button.addEventListener('click', e => {
			e.preventDefault();

			const field = document.getElementById('kitces-saved-articles-search-field');

			if (!field) {
				return false;
			}

			const fieldValue = field.value;
			const urlParams = new URLSearchParams(window.location.search);

			if (fieldValue.length > 0) {
				urlParams.set('search', fieldValue);
			} else {
				urlParams.delete('search');
			}

			window.location.search = urlParams;
		});
	}
};

export default searchClickHandler;
