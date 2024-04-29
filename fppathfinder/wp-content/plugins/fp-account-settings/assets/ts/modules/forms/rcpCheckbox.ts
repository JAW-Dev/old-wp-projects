import insertAfter from './insertAfter';

const rcpCheckbox = () => {
	const forms = document.querySelectorAll('#tabs-group-settings .rcp_form');

	forms.forEach(form => {
		const checkboxes = form.querySelectorAll('input[type="checkbox"]');

		checkboxes.forEach((checkbox: HTMLElement) => {
			const parent = checkbox.parentElement;
			const children = checkbox.parentElement.childNodes;
			let text;

			children.forEach(child => {
				const type = child.nodeType;

				if (type === Node.TEXT_NODE) {
					text = child;
				}
			});

			const wrapElement = document.createElement('div');
			wrapElement.classList.add('label-text');
			insertAfter(checkbox, wrapElement);
			wrapElement.appendChild(text);

			parent.parentElement.classList.add('styled-switches');

			const newElement = document.createElement('div');
			newElement.classList.add('switch');
			insertAfter(checkbox, newElement);
		});
	});
};

export default rcpCheckbox;
