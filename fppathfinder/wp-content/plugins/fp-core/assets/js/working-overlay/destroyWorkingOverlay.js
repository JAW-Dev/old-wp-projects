const destroyWorkingOverlay = () => {
	const overlay = document.getElementById('working-overlay');
	const parent = document.body;

	parent.removeChild(overlay);
};

export default destroyWorkingOverlay;
