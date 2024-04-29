import tippy from 'tippy.js';

const optionsButtonDescription = () => {
	const button = document.getElementById('share-link-options-button');
	const desc = document.getElementById('share-options-description');

	jQuery(button).hover(() => {
		desc.classList.add('show');

		tippy(button, {
			content: desc.innerHTML,
			arrow: true,
			theme: 'light',
			maxWidth: 500,
			allowHTML: true,
			delay: 100
		});
	});
};

export default optionsButtonDescription;
