const logoReset = args => {
	const resetButton = document.getElementById(args.removebutton);
	if (!resetButton) {
		return false;
	}

	const logoResetValue = resetButton.dataset.src;
	const logoField = document.getElementById(args.croppableImage);
	const logoPreview = document.getElementById(args.previewImage);
	const perviewLink = document.getElementById(args.perviewLink);
	const mockButton = document.getElementById(args.mockButton);

	resetButton.addEventListener('click', e => {
		e.preventDefault();
		logoField.setAttribute('src', logoResetValue);
		logoPreview.setAttribute('src', '');
		perviewLink.style.display = 'none';
		resetButton.style.display = 'none';
		mockButton.innerHTML = 'Upload';
	});
};

export default logoReset;
