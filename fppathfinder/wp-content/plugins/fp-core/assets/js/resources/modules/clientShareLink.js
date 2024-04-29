const isShareLink = () => {
	const params = new URLSearchParams(window.location.search);

	if (params.has('sh')) {
		return true;
	}

	return false;
};

const hideQuestionGroup = (groups, count) => {
	if (!isShareLink()) {
		return false;
	}

	groups.forEach(group => {
		group.style.display = 'none';
	});

	groups.forEach(group => {
		if (group.id === `question-group-${count}`) {
			group.style.display = 'block';
		}
	});
};

const showPaginationButtons = (count, prev, next, totalGroups, submit) => {
	if (count === 1) {
		prev.style.display = 'none';
	}

	if (count > 1) {
		prev.style.display = 'block';
	}

	if (count >= totalGroups) {
		next.style.display = 'none';
		submit.style.display = 'block';
	}

	if (count < totalGroups) {
		next.style.display = 'block';
		submit.style.display = 'none';
	}
};

const clientShareLink = () => {
	if (!isShareLink()) {
		return false;
	}

	const groups = document.querySelectorAll('.pagination-group');

	if (!groups) {
		return false;
	}

	const tempGroups = [];

	groups.forEach(group => {
		tempGroups.push(group);
	});

	const totalGroups = tempGroups.length;
	const pages = document.getElementById('pagination-steps-total');

	if (!pages) {
		return false;
	}

	pages.innerHTML = totalGroups;

	let count = 0;

	hideQuestionGroup(tempGroups, count);

	const next = document.getElementById('group_next');
	const prev = document.getElementById('group_prev');
	const submit = document.getElementById('submit-button');

	if (count === 0) {
		showPaginationButtons(count + 1, prev, next, totalGroups, submit);
	}

	next.addEventListener('click', e => {
		e.preventDefault();

		count++;

		showPaginationButtons(count + 1, prev, next, totalGroups, submit);

		const currentPage = document.getElementById('pagination-steps-current');

		currentPage.innerHTML = count + 1;
		hideQuestionGroup(groups, count);
	});

	prev.addEventListener('click', e => {
		e.preventDefault();

		count--;

		showPaginationButtons(count + 1, prev, next, totalGroups, submit);

		const currentPage = document.getElementById('pagination-steps-current');

		currentPage.innerHTML = count + 1;
		hideQuestionGroup(groups, count);
	});
};

export default clientShareLink;
