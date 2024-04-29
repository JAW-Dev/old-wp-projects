const setLocalStorage = (tab: HTMLElement): void => {
	const local = localStorage;

	if (!tab.parentElement.closest('[role=tabpanel]')) {
		const now: Date = new Date();
		now.setHours(now.getHours() + 1);

		const item = {
			value: `${tab.id}-tab`,
			expiry: now.getTime() + 4
		};

		local.setItem('activeTab', JSON.stringify(item));
	}
};

export default setLocalStorage;
