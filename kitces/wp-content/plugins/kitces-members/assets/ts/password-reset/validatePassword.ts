import updateChecks from './updateChecks';

const validatePassword = (elementArray: HTMLInputElement[]) => {
	const button = <HTMLInputElement>document.getElementById('password-reset-form-submit');

	elementArray.forEach(element => {
		const elementId = element.id;
		const id = elementId.slice(-1);

		element.addEventListener('input', () => {
			updateChecks(element, id, elementArray, button);
		});

		element.addEventListener('focus', () => {
			updateChecks(element, id, elementArray, button);
		});
	});
};

export default validatePassword;
