const quizFilter = () => {
	const selectElement = 'quiz-year-filter';
	const url = new URL(window.location.href);
	const queryString = url.search;
	const searchParams = new URLSearchParams(queryString);
	const filterParam = searchParams.get('filter') ? searchParams.get('filter') : 'all';

	if (filterParam && document.getElementById(selectElement)) {

		// Set the select option to the filter value.
		document.getElementById(selectElement).value = filterParam;

		// Detect select change.
		document.addEventListener('input', function (event) {

			// Only run on our select menu.
			if (event.target.id !== selectElement) {
				return;
			}

			// Delete the current filter param.
			searchParams.delete('filter');

			// Get the selected option value.
			const optionSelected = event.target.value;

			// Build the new URL.
			searchParams.append('filter', optionSelected);
			url.search = searchParams.toString();
			const newUrl = url.toString();

			// Refresh the page with the new URL.
			window.location = newUrl;

		}, false);
	}
}

quizFilter();
