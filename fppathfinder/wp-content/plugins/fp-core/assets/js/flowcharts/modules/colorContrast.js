const colorContrast = color => {
	let r;
	let g;
	let b;
	let hsp;

	// Check the format of the color, HEX or RGB?
	if (color.match(/^rgb/)) {
		color = color.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+(?:\.\d+)?))?\)$/);
		r = color[1];
		g = color[2];
		b = color[3];
	} else {
		color = +`0x${color.slice(1).replace(color.length < 5 && /./g, '$&$&')}`;

		r = color >> 16;
		g = (color >> 8) & 255;
		b = color & 255;
	}

	hsp = Math.sqrt(0.299 * (r * r) + 0.587 * (g * g) + 0.114 * (b * b));

	if (hsp > 127.5) {
		return 'light';
	}

	return 'dark';
};

export default colorContrast;
