const removeEvent = () => {
	const navWrap = document.getElementById('account-settings-nav');
	const navItems = navWrap.querySelectorAll('.nav-list__item');

	navItems.forEach(navItem => {
		const func = () => {};
		navItem.removeEventListener('click', func);
	});
};

export default removeEvent;
