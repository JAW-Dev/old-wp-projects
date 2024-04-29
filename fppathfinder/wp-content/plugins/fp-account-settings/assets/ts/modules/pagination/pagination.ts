const pagination = () => {
	const current = document.querySelector('.current');

	if (current) {
		current.parentElement.classList.add('active');
	}
};

export default pagination;
