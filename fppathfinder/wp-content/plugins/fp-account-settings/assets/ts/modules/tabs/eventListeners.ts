import { Selectors } from './interfaces';
import setActiveTab from './setActiveTab';

const eventListeners = (selectors: Selectors): void => {
	for (const tab of selectors.tabs) {
		// Prevent focus styles on click.
		tab.addEventListener('mousedown', e => {
			e.preventDefault();
		});

		tab.addEventListener('click', e => {
			e.preventDefault();
			setActiveTab(tab.getAttribute('aria-controls'), selectors);
			history.pushState(undefined, undefined, `#${tab.id}`);
		});

		// Prevent page scroll.
		tab.addEventListener('keydown', e => {
			if ((e.keyCode === 32 && e.key === ' ') || (e.keyCode === 35 && e.key === 'End') || (e.keyCode === 36 && e.key === 'Home')) {
				e.preventDefault();
			}
		});

		tab.addEventListener('keyup', e => {
			if ((e.keyCode === 13 && e.key === 'Enter') || (e.keyCode === 32 && e.key === ' ')) {
				e.preventDefault();
				setActiveTab(tab.getAttribute('aria-controls'), selectors);
				history.pushState(undefined, undefined, `#${tab.id}`);
			}
		});
	}

	selectors.tablist.addEventListener('keyup', e => {
		const action: string | number = e.keyCode || e.key;
		let previous: number = [...selectors.tabs].indexOf(selectors.activeTab) - 1;
		let next: number = [...selectors.tabs].indexOf(selectors.activeTab) + 1;

		switch (action) {
			case 35: // end key
			case 'End':
				e.preventDefault();
				setActiveTab(selectors.tabs[selectors.tabs.length - 1].getAttribute('aria-controls'), selectors);
				break;
			case 36: // home key
			case 'Home':
				e.preventDefault();
				setActiveTab(selectors.tabs[0].getAttribute('aria-controls'), selectors);
				break;
			case 37: // left arrow
			case 'ArrowLeft':
				e.preventDefault();
				previous = previous >= 0 ? previous : selectors.tabs.length - 1;
				setActiveTab(selectors.tabs[previous].getAttribute('aria-controls'), selectors);
				break;
			case 39: // right arrow
			case 'ArrowRight':
				e.preventDefault();
				next = next < selectors.tabs.length ? next : 0;
				setActiveTab(selectors.tabs[next].getAttribute('aria-controls'), selectors);
				break;
			default:
				break;
		}
	});
};

export default eventListeners;
