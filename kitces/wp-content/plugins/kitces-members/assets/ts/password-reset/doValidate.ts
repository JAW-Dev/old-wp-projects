import validateSchema from './validateSchema';
import showIndicator from './showIndicator';

const doValidate = (schema: any, element: HTMLInputElement, checkedElement?: HTMLElement): boolean => {
	let isValidated = validateSchema(element.value, schema);

	element.addEventListener('input', () => {
		isValidated = validateSchema(element.value, schema);

		if (checkedElement) {
			showIndicator(isValidated, checkedElement);
		}

		return isValidated;
	});

	element.addEventListener('focus', () => {
		isValidated = validateSchema(element.value, schema);

		if (checkedElement) {
			showIndicator(isValidated, checkedElement);
		}

		return isValidated;
	});

	return isValidated;
};

export default doValidate;
