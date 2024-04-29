const fadeOut = (element: HTMLElement) => {
	setTimeout(() => {
		const notification = jQuery(element);
		notification.fadeOut(1000, 'linear');
	}, 1000);
};

export default fadeOut;
