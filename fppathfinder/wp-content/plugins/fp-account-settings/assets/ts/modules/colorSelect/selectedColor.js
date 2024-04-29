const selectedColor = colors => {
	for (let i = 0; i < colors.length; i++) {
		if (colors[i].classList.contains('selected')) {
			return colors[i].id;
		}
	}
};

export default selectedColor;
