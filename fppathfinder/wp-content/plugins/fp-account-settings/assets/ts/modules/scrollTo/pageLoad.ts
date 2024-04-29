const pageLoad = () => {
	const urlString: string = window.location.href;
	const url = new URL(urlString);
	const scroll = url.searchParams.get('scroll');

	if (scroll) {
		const target = document.getElementById(scroll);
		const message = document.querySelector('.rcp_success');
		if (message) {
			setTimeout(() => {
				target.scrollIntoView();
			}, 1000);
			return false;
		}

		if (target) {
			setTimeout(() => {
				target.scrollIntoView();
			}, 1000);
		}
	}
};

export default pageLoad;
