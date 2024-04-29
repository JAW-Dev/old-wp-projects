import removeWhitespace from './removeWhitespace';

const svgInit = () => {
	const sidebar = document.querySelector('.account-settings__main');
	const svgs = Array.from(sidebar.getElementsByTagName('svg'));

	svgs.forEach(svg => {
		removeWhitespace(svg);
	});
};

export default svgInit;
