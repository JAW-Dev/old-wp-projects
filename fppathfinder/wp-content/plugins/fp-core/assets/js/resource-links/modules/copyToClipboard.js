import modaal from 'modaal';

const fallbackCopyTextToClipboard = text => {
	const textArea = document.createElement('textarea');
	textArea.value = text;

	// Avoid scrolling to bottom
	textArea.style.top = '0';
	textArea.style.left = '0';
	textArea.style.position = 'fixed';

	document.body.appendChild(textArea);
	textArea.focus();
	textArea.select();

	try {
		const successful = document.execCommand('copy');

		if (successful) {
			message.classList.add('success');
		}

		setTimeout(() => {
			jQuery('.resource-share-link__button-trigger').modaal('close');
		}, 1000);
	} catch (err) {
		console.error('Fallback: Oops, unable to copy', err);
	}

	document.body.removeChild(textArea);
};

const copyToClipboard = text => {
	if (!navigator.clipboard) {
		fallbackCopyTextToClipboard(text);
		return;
	}

	navigator.clipboard.writeText(text).then(
		() => {
			const message = document.getElementById('resource-share-link-modal-copy-successful');
			message.classList.add('success');

			setTimeout(() => {
				jQuery('.resource-share-link__button-trigger').modaal('close');
			}, 1000);
		},
		err => {
			console.error('Could not copy text: ', err);
		}
	);
};

export default copyToClipboard;
