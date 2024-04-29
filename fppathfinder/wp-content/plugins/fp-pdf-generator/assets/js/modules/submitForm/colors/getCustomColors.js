const getCustomColors = isUsingCustomColors => {
	const colorObj = {};

	const parent = isUsingCustomColors.parentNode;
	const colors = parent.querySelectorAll('.spectrum-color-box');

	for (let i = 0; i < colors.length; i++) {
		const colorHex = colors[i].value;
		const g = i + 1;
		colorObj[`color${g}`] = colorHex;
	}

	return colorObj;
};

export default getCustomColors;
