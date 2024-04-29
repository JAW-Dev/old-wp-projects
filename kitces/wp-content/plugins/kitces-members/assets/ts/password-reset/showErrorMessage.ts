const showErrorMessage = (check: boolean, id: string) => {
	if (check) {
		const selector = `${id} .password-reset-form__content-text`;
		const blacklistMessage = <HTMLElement>document.querySelector(selector);
		blacklistMessage.style.display = 'block';
		blacklistMessage.style.color = 'red';
	} else {
		const selector = `${id} .password-reset-form__content-text`;
		const blacklistMessage = <HTMLElement>document.querySelector(selector);
		blacklistMessage.style.display = 'none';
	}
};

export default showErrorMessage;
