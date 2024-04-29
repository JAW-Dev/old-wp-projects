import { Selectors } from './interfaces';

const hasActiveSubTab = (selectors: Selectors, urlHash: string): string => {
	let targetSetting = '';

	if (urlHash) {
		const subHash = urlHash.includes('-');

		if (subHash) {
			let activeTag = `${urlHash.substring(1)}-sub-tab`;
			activeTag = activeTag.substring(activeTag.indexOf('_') + 1);

			for (const tabpanel of selectors.tabpanels) {
				if (tabpanel.getAttribute('id') === activeTag) {
					targetSetting = tabpanel.getAttribute('id');
				}
			}

			if (activeTag === targetSetting) {
				return activeTag;
			}
		}
	}

	return '';
};

export default hasActiveSubTab;
