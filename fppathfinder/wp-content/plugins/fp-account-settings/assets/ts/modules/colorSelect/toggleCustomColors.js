const toggleCustomColors = (prefix, isUsingCustomColors) => {
	const customColor = document.getElementById(`${prefix}-custom-color-box-button-wrap`);
	const customColors = document.getElementById(`${prefix}-custom-color-scheme-table-container-wrap`);

	if (isUsingCustomColors.value === 'true') {
		customColor.classList.add('selected');
		customColors.classList.add('show');
	} else {
		customColor.classList.remove('selected');
		customColors.classList.remove('show');
	}
};

export default toggleCustomColors;
