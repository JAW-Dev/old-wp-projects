const showNodes = (data, node, id, type, answerId = null) => {
	if (type === 'question' || type === 'result') {
		node.classList.add('show');
		node.classList.remove('last');
	}

	const textHidden = node.querySelector(`input#${type}-${id}-hidden-text`);
	const idHidden = node.querySelector(`input#${type}-${id}-hidden-id`);

	if (Object.keys(textHidden).length !== 0) {
		textHidden.disabled = false;
	}

	if (Object.keys(idHidden).length !== 0) {
		idHidden.disabled = false;
	}

	if (type === 'question') {
		const element = node.querySelector('.node');

		if (!node.classList.contains('first')) {
			element.classList.add('target');
		}
	}

	if (type === 'answer') {
		const parentIdHidden = node.querySelector(`input#${type}-${id}-hidden-parentId`);

		if (Object.keys(parentIdHidden).length !== 0) {
			parentIdHidden.disabled = false;
		}

		const answersItems = node.querySelectorAll('.answers__item');

		answersItems.forEach(item => {
			const answerWrap = item.querySelector('.answer-wrap');
			if (item.classList.contains(`answer-${answerId}`)) {
				answerWrap.classList.add('selected');
			} else {
				answerWrap.classList.add('not-selected');
			}
		});
	}
};

export default showNodes;
