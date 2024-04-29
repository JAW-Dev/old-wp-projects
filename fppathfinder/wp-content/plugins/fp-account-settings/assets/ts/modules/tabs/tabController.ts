import { Selectors } from './interfaces';
import eventListeners from './eventListeners';
import setActiveTab from './setActiveTab';
import hasActiveTab from './hasActiveTab';
import hasSubActiveTab from './hasActiveSubTab';

const tabController = (container: string, className: string, setDefault: boolean = true): any => {
	const base: HTMLElement = document.querySelector(container);

	if (!base) {
		return false;
	}

	const selectors: Selectors = {
		container: base,
		tablist: base.querySelector(`${className}[role=tablist]`),
		tabs: base.querySelectorAll(`${className}[role=tab]`),
		tabpanels: base.querySelectorAll(`${className}[role=tabpanel]`),
		activeTab: base.querySelector(`${className}[role=tab][aria-selected=true]`)
	};

	// Set the first tab as selected.
	if (setDefault) {
		const urlHash = window.location.hash;
		const activeTab: string = hasActiveTab(selectors, urlHash);
		const activeSubTab: string = hasSubActiveTab(selectors, urlHash);

		if (activeTab) {
			setActiveTab(activeTab, selectors);
		} else if (activeSubTab) {
			setActiveTab(activeSubTab, selectors);
		} else {
			setActiveTab(selectors.tabs[0].getAttribute('aria-controls'), selectors);
		}
	}

	eventListeners(selectors);
};

export default tabController;
