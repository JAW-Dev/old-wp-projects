const clearColors = (colors, selected, inputSelector) => {
	for (let i = 0; i < colors.length; i++) {
		colors[i].classList.remove('selected');
		colors[i].querySelector(inputSelector).removeAttribute('checked');
	}
};

export default clearColors;
