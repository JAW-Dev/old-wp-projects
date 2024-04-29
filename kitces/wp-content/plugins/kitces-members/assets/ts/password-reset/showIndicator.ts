const showIndicator = (validated: Boolean, checkedElement: HTMLElement): boolean => {
	const check = <HTMLElement>checkedElement?.querySelector('.check');
	const times = <HTMLElement>checkedElement?.querySelector('.times');

	if (validated) {
		check.style.display = 'block';
		times.style.display = 'none';

		return true;
	} else {
		check.style.display = 'none';
		times.style.display = 'block';
	}

	return false;
};

export default showIndicator;
