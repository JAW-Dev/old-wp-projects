const toggleCustomColors = isUsingCustomColors => {
	const customColor = document.getElementById('custom-color-box-button-wrap');
	const customColors = document.getElementById('custom-color-scheme-table-container-wrap');

	if (isUsingCustomColors.value === 'true') {
		customColor.classList.add('selected');
		customColors.classList.add('show');
	} else {
		customColor.classList.remove('selected');
		customColors.classList.remove('show');
	}
};

export default toggleCustomColors;
