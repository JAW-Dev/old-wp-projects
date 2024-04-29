const resetFormButton = (button: HTMLInputElement, text: string) => {
	setTimeout(() => {
		button.value = text;
		button.removeAttribute('disabled');
	}, 2500);
};

export default resetFormButton;
