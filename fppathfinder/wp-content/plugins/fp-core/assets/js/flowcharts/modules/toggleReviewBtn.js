const toggleReviewBtn = () => {
	const results = document.querySelectorAll('.node-result');
	const button = document.querySelector('[name=review-submit]');

	let letSubmit = false;
	for (let i = 0; i < results.length; i++) {
		if (results[i].classList.contains('show')) {
			letSubmit = true;
			break;
		}
	}

	if (!button) {
		return false;
	}

	if (!letSubmit) {
		button.disabled = true;
	} else {
		button.disabled = false;
	}
};

export default toggleReviewBtn;
