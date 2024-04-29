const addClass = (elements): void => {
	elements.forEach(element => {
		const type = element.innerHTML.toLocaleLowerCase().trim();
		element.classList.add(type);
	});
};

export default addClass;
