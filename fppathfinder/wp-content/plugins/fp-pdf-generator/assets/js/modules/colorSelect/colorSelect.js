// Import Modules
import setCustomColor from './setCustomColor';
import setColor from './setColor';

const colorSelect = () => {
	const colors = document.querySelectorAll('.set-colors');
	const inputSelector = '.selector input';

	setCustomColor(colors, inputSelector);
	setColor(colors, inputSelector);
};

export default colorSelect;
