const removeWhitespace = (svg: SVGSVGElement): void => {
	const bbox = svg.getBBox();
	const viewBox = [bbox.x, bbox.y, bbox.width, bbox.height].join(' ');
	svg.setAttribute('viewBox', viewBox);
};

export default removeWhitespace;
