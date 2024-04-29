declare var Jodit;

const options = {
	toolbarAdaptive: false,
	buttons: 'bold,strikethrough,underline,italic,align,,|,ul,ol,|,fontsize,hr,|,image,|,undo,redo,\n,|',
	uploader: {
		insertImageAsBase64URI: true
	},
	popup: {
		a: [
			{
				name: 'eye',
				tooltip: 'Open link',
				exec: 'function(e,t){var o=t.getAttribute("href");t&&o&&e.ownerWindow.open(o)}'
			},
			{
				name: 'link',
				tooltip: 'Edit link',
				icon: 'pencil'
			},
			'unlink',
			'brush',
			'file'
		]
	},
	askBeforePasteFromWord: false,
	askBeforePasteHTML: false
};

const showSVG = () => {
	const images = Array.from(document.querySelectorAll('.jodit-wysiwyg p img '));

	if (images.length <= 0) {
		return;
	}

	images.forEach((image: HTMLImageElement) => {
		const ogImageSrc = image.getAttribute('src');
		const prefix = ogImageSrc.substr(0, ogImageSrc.indexOf(','));
		let imageSrc = '';

		if (prefix.includes('image/')) {
			imageSrc = ogImageSrc.replace('image/', 'data:image/');

			image.setAttribute('src', imageSrc);
			return imageSrc;
		}
	});
};

const wysiwyg = (): void => {
	const textareas = Array.from(document.querySelectorAll('.wysiwyg'));

	textareas.forEach((textarea: HTMLElement) => {
		const { id } = textarea;

		new Jodit(`#${id}`, options);
	});

	showSVG();
};

export default wysiwyg;
