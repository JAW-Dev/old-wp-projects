const imagePreview = () => {
	const previewImage = document.getElementById('preview-image');
	const previewImageWrap = document.getElementById('preview-image-wrap');
	previewImage.setAttribute('src', '');
	previewImageWrap.classList.remove('show');
};

export default imagePreview;
