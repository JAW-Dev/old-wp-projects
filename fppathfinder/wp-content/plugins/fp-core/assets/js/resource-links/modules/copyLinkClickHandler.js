import copyToClipboard from './copyToClipboard';

const copyLinkClickHandler = () => {
	const button = document.getElementById('resource-share-link-modal-copy-button');

	if (button !== null) {
		button.addEventListener('click', e => {
			e.preventDefault();
			const input = document.getElementById('resource-share-link-modal-copy-input');
			const text = input.value;

			copyToClipboard(text);
		});
	}
};

export default copyLinkClickHandler;
