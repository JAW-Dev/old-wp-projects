const getParent = (element, number) => {
	let parent = element;
	for (let i = 0; i < number; i++) {
		if (element.parentNode) {
			parent = parent.parentNode;
		}
	}

	return parent;
};

export default getParent;
