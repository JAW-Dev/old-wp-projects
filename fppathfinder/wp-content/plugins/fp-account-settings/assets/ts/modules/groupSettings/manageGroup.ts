const manageGroup = () => {
	const table = document.getElementById('rcpga-group-dashboard-selection');

	if (table) {
		const links = Array.from(table.getElementsByTagName('A'));

		links.forEach((link: HTMLLinkElement) => {
			const og_url = link.href;
			link.setAttribute('href', `${og_url}#group-settings`);
		});
	}
};

export default manageGroup;
