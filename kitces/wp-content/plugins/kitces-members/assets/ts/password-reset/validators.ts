import PasswordValidator from 'password-validator';
import doValidate from './doValidate';
import showIndicator from './showIndicator';

const validateCharLength = (element: HTMLInputElement, id: string): boolean => {
	const schema = new PasswordValidator();
	const checkedElement = <HTMLElement>document.getElementById(id);
	return doValidate(schema.is().min(12), element, checkedElement);
};

const validateUppercase = (element: HTMLInputElement, id: string): boolean => {
	const schema = new PasswordValidator();
	const checkedElement = <HTMLElement>document.getElementById(id);
	return doValidate(schema.has().uppercase(1), element, checkedElement);
};

const validateSymbols = (element: HTMLInputElement, id: string): boolean => {
	const schema = new PasswordValidator();
	const checkedElement = <HTMLElement>document.getElementById(id);
	return doValidate(schema.has().symbols(1), element, checkedElement);
};

const validateNumbers = (element: HTMLInputElement, id: string): boolean => {
	const schema = new PasswordValidator();
	const checkedElement = <HTMLElement>document.getElementById(id);
	return doValidate(schema.has().digits(1), element, checkedElement);
};

const validateMatch = (element: HTMLInputElement, id: string, elements: HTMLInputElement[]): boolean => {
	const checkedElement = <HTMLElement>document.getElementById(id);
	const matched = elements[0].value === elements[1].value;
	return showIndicator(matched, checkedElement);
};

const validateBlackList = (element: HTMLInputElement, id: string): boolean => {
	const blacklist = ['Kitces', 'kitces', 'Passw0rd', 'Password123', 'password', 'Password'];
	return blacklist.some(substring => element.value.includes(substring));
};

const validateSpace = (element: HTMLInputElement, id: string): boolean => {
	const schema = new PasswordValidator();
	return doValidate(schema.has().not().spaces(), element);
};

export default { validateCharLength, validateUppercase, validateSymbols, validateNumbers, validateMatch, validateBlackList, validateSpace };
