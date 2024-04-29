const mobileTabs = () => {
	const mobileAccordians = document.querySelectorAll('.mobile-tabs');
	const tabs = document.querySelectorAll('.tabs__list-item');

	mobileAccordians.forEach((accordian: HTMLElement) => {
		const tabData = accordian.dataset.tab;
		const linkSibling = document.getElementById(tabData);
		const header = accordian.querySelector(`#mobile-tab-${tabData}`);

		if (!linkSibling) {
			return false;
		}

		if (linkSibling.classList.contains('selected')) {
			accordian.classList.add('opened');
		}

		header.addEventListener('click', e => {});
	});
};

export default mobileTabs;
