export const toggleDisplay = (display, element) => {
	if (element.style.display !== 'undefined') {
		if (element.style.display === 'none') {
			element.style.display = 'block';
		}

		if (element.style.display === 'block') {
			element.style.display = 'none';
		}
	}
};

export const toggleDisplayMulti = (display, elements) => {
	elements.forEach(element => {
		if (element !== null) {
			toggleDisplay(display, element);
		}
	});
};

export const toggleDisplayClickHandler = (display, element, elements = []) => {
	if (element === null) {
		return;
	}

	if (display.length <= 0) {
		display = 'block';
	}

	toggleDisplay(display, element);

	if (elements.length > 0) {
		toggleDisplayMulti(display, elements);
	}
};
