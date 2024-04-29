// Inport Modules
import inputChanged from './inputChanged';

const setInput = () => {
	const colorInputs = jQuery('.custom-color-input');

	colorInputs.each((index, input) => {
		inputChanged(input);
	});
};

export default setInput;
