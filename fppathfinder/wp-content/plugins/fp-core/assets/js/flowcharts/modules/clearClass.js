const clearClass = (element, ...classNames) => {
	for (const className of classNames) {
		if (element.classList.contains(className)) {
			element.classList.remove(className);
		}
	}
};

export default clearClass;
