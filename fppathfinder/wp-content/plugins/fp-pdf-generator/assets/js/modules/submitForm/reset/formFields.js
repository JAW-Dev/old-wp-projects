const formFields = () => {
	const fields = [
		'pdf-generator-business-display-name',
		'pdf-generator-adviser-name',
		'pdf-second-page-title',
		'pdf-second-page-body-title',
		'pdf-second-page-job-title',
		'pdf-second-page-email',
		'pdf-second-page-phone',
		'pdf-second-page-website'
	];

	for (let i = 0; i < fields.length; i++) {
		const field = document.getElementById(fields[i]);
		field.setAttribute('value', '');
	}

	const secondPageCopy = document.getElementById('pdf-second-page-body-copy');
	const wysiwyg = document.querySelector('.jodit_wysiwyg');
	secondPageCopy.innerHTML = '';
	wysiwyg.innerHTML = '';

	const secondPageAddress = document.getElementById('pdf-second-page-address');
	secondPageAddress.innerHTML = '';
};

export default formFields;
