/* global document */

// Import Modules.
import datePicker from './datePicker';
import clickHandler from './clickHandler';
import filterClickHandler from './filterClickHandler';

const report = params => {
	// Ba if action is not defined
	if (typeof params === 'undefined' || params === null) {
		console.error('Action was not defined!'); // eslint-disable-line
		return false;
	}

	let target;

	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	const page = urlParams.get('page');

	if (params.target === 'member-report') {
		datePicker(params.target);
		filterClickHandler(params.target);
	}

	target = params.target !== 'undefined' ? document.querySelector(`#${params.target}`) : null;
	clickHandler(target, params);
};

export default report;
