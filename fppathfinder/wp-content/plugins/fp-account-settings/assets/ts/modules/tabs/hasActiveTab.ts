import { Selectors } from './interfaces';

const hasActiveTab = (selectors: Selectors, urlHash: string): string => {
	let targetSetting = '';

	if (urlHash) {
		const activeTag = `${urlHash.substring(1).split('_')[0]}-tab`;

		for (const tabpanel of selectors.tabpanels) {
			if (tabpanel.getAttribute('id') === activeTag) {
				targetSetting = tabpanel.getAttribute('id');
			}
		}

		if (activeTag === targetSetting) {
			return activeTag;
		}
	}

	return '';
};

export default hasActiveTab;
