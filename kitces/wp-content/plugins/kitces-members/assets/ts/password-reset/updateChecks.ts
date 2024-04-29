import validators from './validators';
import showErrorMessage from './showErrorMessage';

const updateChecks = (element: HTMLInputElement, id: string, elementArray: HTMLInputElement[], button: HTMLElement) => {
	const charLength = validators.validateCharLength(element, `password-reset-form-validation-char-length-${id}`);
	const uppercase = validators.validateUppercase(element, `password-reset-form-validation-upper-case-${id}`);
	const symbol = validators.validateSymbols(element, `password-reset-form-special-char-${id}`);
	const number = validators.validateNumbers(element, `password-reset-form-validation-number-${id}`);
	const space = validators.validateSpace(element, '');
	const blacklist = validators.validateBlackList(element, `password-reset-form-forbidden-word-${id}`);
	const match = validators.validateMatch(element, `password-reset-form-pass-match-${id}`, elementArray);

	showErrorMessage(!space, `#password-reset-form-spaces-${id}`);
	showErrorMessage(blacklist, `#password-reset-form-forbidden-word-${id}`);

	// Update disabled submit button.
	if (charLength && uppercase && symbol && number && match && space && !blacklist) {
		button.removeAttribute('disabled');
	} else {
		button.setAttribute('disabled', 'disabled');
	}

	const value1 = elementArray[0].value.length > 0 ? elementArray[0].value : '';
	const value2 = elementArray[1].value.length > 0 ? elementArray[1].value : '';
	const noMatchMessage = <HTMLElement>document.getElementById('password-reset-form-pass-match');

	if (value1 !== value2) {
		noMatchMessage.style.display = 'block';
	} else {
		noMatchMessage.style.display = 'nonw';
	}
};

export default updateChecks;
