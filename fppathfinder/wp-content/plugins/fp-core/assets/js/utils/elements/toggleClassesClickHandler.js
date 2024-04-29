export const toggleClasses = (element, classes) => {
	if (element.classList.contains(classes)) {
		element.classList.remove(classes);
		return;
	}

	element.classList.add(classes);
};

export const toogleClassesMulti = (elements, classes) => {
	elements.forEach(element => {
		if (element !== null) {
			element.classList.remove(classes);
		}
	});
};

export const toggleClassesClickHandler = (classes, element, elements = []) => {
	if (classes.length <= 0) {
		return;
	}

	if (element === null) {
		return;
	}

	const isActive = element.classList.contains(classes);

	if (isActive) {
		return;
	}

	toggleClasses(element, classes);

	if (elements.length > 0) {
		toogleClassesMulti(elements, classes);
	}
};
