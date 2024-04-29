// Import Modules
import setCustomColor from './setCustomColor';
import setColor from './setColor';

const colorSelect = prefixes => {
	prefixes.forEach(prefix => {
		const container = document.getElementById(`${prefix}-colors-container`);
		if (!container) {
			return false;
		}

		const colors = container.querySelectorAll('.set-colors');
		const inputSelector = '.selector input';

		setCustomColor(prefix, colors, inputSelector);
		setColor(prefix, colors, inputSelector);
	});
};

export default colorSelect;
