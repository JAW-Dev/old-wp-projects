Math.easeInOutQuad = (currentTime, start, change, duration) => {
	currentTime /= duration / 2;
	if (currentTime < 1) {
		return (change / 2) * currentTime * currentTime + start;
	}
	currentTime--;
	return (-change / 2) * (currentTime * (currentTime - 2) - 1) + start;
};

const scrollTo = (element, to, duration) => {
	const start = element.scrollTop;
	const change = to - start;
	let currentTime = 0;
	const increment = 20;

	const animateScroll = () => {
		currentTime += increment;
		const val = Math.easeInOutQuad(currentTime, start, change, duration);
		element.scrollTop = val;
		if (currentTime < duration) {
			setTimeout(animateScroll, increment);
		}
	};
	animateScroll();
};

export default scrollTo;
