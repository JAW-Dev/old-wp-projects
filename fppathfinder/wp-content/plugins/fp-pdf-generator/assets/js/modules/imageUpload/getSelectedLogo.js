const getSelectedLogo = (file, callback) => {
	const fileReader = new FileReader();
	fileReader.onload = callback;
	fileReader.readAsDataURL(file);
};

export default getSelectedLogo;
