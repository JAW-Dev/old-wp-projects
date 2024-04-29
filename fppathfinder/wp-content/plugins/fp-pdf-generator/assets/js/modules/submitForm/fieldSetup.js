// Import Modules
import getColors from './colors/getColors';

const fieldSetup = () => {
	return new Promise(resolve => {
		try {
			getColors();
			resolve();
		} catch (err) {
			console.error(err);
		}
	});
};

export default fieldSetup;
