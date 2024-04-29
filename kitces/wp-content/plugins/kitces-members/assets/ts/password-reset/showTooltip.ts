const show = (e: Event, element: HTMLElement, tooltip: HTMLElement, tooltips: any) => {
	const value = (<HTMLInputElement>e.target).value;

	tooltips.forEach((tooltip: HTMLElement) => {
		if (tooltip.id !== element.id) {
			tooltip.classList.remove('show');
		}
	});

	if (value.length >= 3) {
		tooltip.classList.add('show');

		return true;
	}

	return false;
};

const showTooltip = (elementArray: HTMLInputElement[], tooltipArray: HTMLElement[], tooltips: any) => {
	elementArray.forEach((element, index) => {
		element.addEventListener('input', (e: Event) => {
			show(e, element, tooltipArray[index], tooltips);
		});

		element.addEventListener('focus', (e: Event) => {
			show(e, element, tooltipArray[index], tooltips);
		});

		element.addEventListener('focusout', e => {
			tooltipArray[index].classList.remove('show');
		});
	});
};

export default showTooltip;
