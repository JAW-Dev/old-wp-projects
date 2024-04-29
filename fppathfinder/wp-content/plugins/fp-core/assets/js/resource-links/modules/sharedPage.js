const sharedPage = () => {
	const { pdfSettings } = fpResourceLinksData;

	if (pdfSettings !== null) {
		const clientLookup = document.querySelector('.interactive-resource__client-wrap');
		if (clientLookup !== null) {
			clientLookup.style.display = 'none';
		}

		const footer = document.querySelector('.footer-widgets');
		const footerWidgets = document.querySelector('.site-footer');
		const footerCTA = document.querySelector('.footer-cta');

		if (footer !== null) {
			footer.style.display = 'none';
		}

		if (footerWidgets !== null) {
			footerWidgets.style.display = 'none';
		}

		if (footerCTA !== null) {
			footerCTA.style.display = 'none';
		}
	}
};

export default sharedPage;
