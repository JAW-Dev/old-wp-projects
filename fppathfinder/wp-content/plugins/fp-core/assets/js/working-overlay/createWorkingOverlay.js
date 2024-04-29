const createWorkingOverlay = () => {
	const newElement = document.createElement('div');
	const putTarget = document.body;

	newElement.setAttribute('class', 'working-overlay');
	newElement.setAttribute('id', 'working-overlay');
	newElement.innerHTML = '<div class="working-spinner"><div></div><div></div><div></div><div></div></div>';

	putTarget.appendChild(newElement);
};

export default createWorkingOverlay;
