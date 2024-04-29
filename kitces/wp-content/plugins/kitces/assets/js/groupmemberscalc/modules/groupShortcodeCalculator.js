// Import modules
import getEntriesCost from './getEntriesCost';
import { isEmpty } from '../../modules/utils/utils';

const groupShortcodeCalculator = () => {
	const total = document.getElementById('group-price-quote-total');
	const select = document.getElementById('group-price-quote-select');
	const cost = document.getElementById('group-price-quote-cost');
	const limit = document.getElementById('group-price-quote-limit');

	if (!isEmpty(cost)) {
		cost.style.display = 'block';
	}
	if (!isEmpty(limit)) {
		limit.style.display = 'none';
	}

	if (!isEmpty(total)) {
		let count = select.options[select.selectedIndex].value;
		total.innerHTML = getEntriesCost(Number(count));

		if (!isEmpty(select)) {
			select.addEventListener('change', e => {
				count = e.target.options[e.target.selectedIndex].value;

				if (count === '50+') {
					if (!isEmpty(cost)) {
						cost.style.display = 'none';
					}
					if (!isEmpty(limit)) {
						limit.style.display = 'block';
					}
				} else {
					total.innerHTML = getEntriesCost(Number(count));
				}
			});
		}
	}
};

export default groupShortcodeCalculator;
