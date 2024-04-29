// Import Modules
import clearClass from './clearClass';
import disableInputs from './disableInputs';
import getSiblings from './getSiblings';
import getParent from './getParent';

const clearDirectSiblings = (element, selectedClass) => {
	const siblings = getSiblings(getParent(element, 2));

	siblings.forEach(sibling => {
		const answerWrap = sibling.querySelector('.answer-wrap');
		clearClass(answerWrap, selectedClass);
		disableInputs(sibling);
	});
};

export default clearDirectSiblings;
