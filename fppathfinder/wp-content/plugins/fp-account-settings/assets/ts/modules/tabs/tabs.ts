import tabController from './tabController';

const tabs = () => {
	tabController('#account-settings', '.account-settings__main');

	const subTabs = ['group-settings', 'personalizations'];

	subTabs.forEach(slug => {
		tabController(`#tabs-${slug}`, `.sub-tabs-${slug}`);
	});
};

export default tabs;
