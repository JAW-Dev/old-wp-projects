const getNextSiblings = element => {
	const siblings = [];

	while ((element = element.nextSibling)) {
		if (element.nodeType === 3) {
			continue;
		}
		siblings.push(element);
	}
	return siblings;
};

export default getNextSiblings;
