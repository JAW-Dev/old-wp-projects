import tippy from 'tippy.js';

const toggleAdvisorPath = (trigger, path) => {
	const button = document.querySelector(trigger);
	const desc = document.getElementById(`checklist-control-description-${path}`);
	jQuery(button).hover(() => {
		desc.classList.add('show');

		tippy(trigger, {
			content: desc.innerHTML,
			arrow: true,
			theme: 'light',
			maxWidth: 500,
			allowHTML: true,
			delay: 100
		});
	});
};

const advisorPathDescription = () => {
	const paths = ['advisor', 'client', 'group'];

	paths.forEach(path => {
		const trigger = `#checklist-control-${path}-path-button svg`;
		toggleAdvisorPath(trigger, path);
	});
};

export default advisorPathDescription;
