// Import Modules
import clearClass from './clearClass';
import disableInputs from './disableInputs';
import getNextSiblings from './getNextSiblings';
import getParent from './getParent';

const clearSiblings = (element, selectedClass, notSelectedClass) => {
	const siblings = getNextSiblings(getParent(element, 6));

	siblings.forEach(sibling => {
		const childAnswers = sibling.querySelectorAll('.answer-wrap');

		childAnswers.forEach(childAnswer => {
			clearClass(childAnswer, selectedClass, notSelectedClass);
		});

		disableInputs(sibling);
		clearClass(sibling, 'show');
	});
};

export default clearSiblings;
