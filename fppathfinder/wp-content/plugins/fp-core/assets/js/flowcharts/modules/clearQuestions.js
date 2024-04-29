// Import Modules
import clearClass from './clearClass';

const clearQuestions = () => {
	const questions = document.querySelectorAll('.node-question');
	questions.forEach(question => {
		clearClass(question, 'last');
	});
};

export default clearQuestions;
