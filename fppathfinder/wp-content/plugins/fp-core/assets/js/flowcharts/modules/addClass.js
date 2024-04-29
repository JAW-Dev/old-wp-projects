const addClass = (element, ...classNames) => {
	for (const className of classNames) {
		if (!element.classList.contains(className)) {
			element.classList.add(className);
		}
	}
};

export default addClass;
