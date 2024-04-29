import { Selectors } from './interfaces';

const setActiveSubTab = (id: string, selectors: Selectors): void => {
	for (const tab of selectors.tabs) {
		if (tab.getAttribute('aria-controls') === id) {
			tab.setAttribute('aria-selected', 'true');
			tab.classList.add('selected');
			tab.focus();
			selectors.activeTab = tab;
		} else {
			tab.setAttribute('aria-selected', 'false');
			tab.classList.remove('selected');
		}

		for (const tabpanel of selectors.tabpanels) {
			if (tabpanel.getAttribute('id') === id) {
				tabpanel.classList.add('selected');
				tabpanel.setAttribute('aria-expanded', 'true');
			} else {
				tabpanel.classList.remove('selected');
				tabpanel.setAttribute('aria-expanded', 'false');
			}
		}
	}
};

export default setActiveSubTab;
