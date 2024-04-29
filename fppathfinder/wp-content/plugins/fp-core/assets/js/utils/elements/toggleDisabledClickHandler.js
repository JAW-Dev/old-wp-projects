export const toggleDisabled = element => {
	if (element.disabled) {
		element.classlist.remove('active');

		return false;
	}

	element.classlist.add('active');
};

export const toogleDisabledMulti = elements => {
	elements.forEach(element => {
		if (element !== null) {
			toggleDisabled(element);
		}
	});
};

export const toggleDisabledClickHandler = (element, elements = []) => {
	if (element === null) {
		return;
	}

	toggleDisabled(element);
	if (elements.length > 0) {
		toogleDisabledMulti(elements);
	}
};
