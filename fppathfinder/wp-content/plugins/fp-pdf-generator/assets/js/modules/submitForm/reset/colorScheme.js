const colorScheme = () => {
	const defaultColorSet = document.querySelectorAll('.set-colors');
	const firstColorSet = defaultColorSet[0];
	const firstColorSetInput = firstColorSet.querySelector('input');
	firstColorSetInput.setAttribute('checked', '');
	firstColorSetInput.click();
};

export default colorScheme;
