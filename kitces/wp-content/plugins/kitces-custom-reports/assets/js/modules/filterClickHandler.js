const UrlAddParameter = (param, value) => {
	if (value) {
		return `&${param}=${value}`;
	}
	return '';
}

const filterClickHandler = target => {
	const button = document.getElementById('table-date-range-filter');

	if (!button) {
		return false;
	}

	button.addEventListener('click', e => {
		e.preventDefault();

		// Remove old url params
		window.history.replaceState(null, "", location.href.split("&")[0]);

		let addParams = {};

		const fields = [
			'from-date',
			'to-date',
			'status',
			'subscriptions',
			'activity',
			'has-trialed',
			'upgraded-from',
			'group'
		];

		for (let i = 0; i < fields.length; i++) {
			const field = document.getElementById(`${target}-${fields[i]}`);
			if (field) {
				addParams[fields[i]] = field.value;
			}
		}

		let params = '';

		for (let key in addParams) {
			const added = UrlAddParameter(key, addParams[key]);
			if (added) {
				params += added;
			}
		}

		window.location.href += params;
	})
}

export default filterClickHandler;
